<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\User;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use ArPHP\I18N\Arabic;


class ConvertPdfToTextController extends Controller
{
    public function convert(Request $request)
    {
        // التحقق من وجود ملف PDF في الطلب
        if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');

            // حفظ الملف مؤقتًا
            $path = $pdfFile->store('temp');

            // استخدام حزمة Spatie لتحويل PDF إلى نص
            $text = (new Pdf(config('pdf-to-text.pdf_to_text.binaries.windows')))
                ->setPdf(storage_path('app/' . $path))
                ->text();

            // حذف الملف المؤقت بعد التحويل
            unlink(storage_path('app/' . $path));

            // استخراج البيانات المطلوبة من النص
            $institutionName = $this->extractInstitutionName($text);
            $statementNumber = $this->extractStatementNumber($text);
            $subclientId = $this->extractSubclientId($text); // المستورد
            $expireCustoms = $this->extractExpireCustoms($text);
            $customsWeight = $this->extractCustomsWeight($text);

            // الحصول على العميل (client) من قاعدة البيانات
            $client = $this->findClosestMatch($institutionName);
            if (!$client) {
                return response()->json(['error' => 'Client not found'], 404);
            }

            // // حفظ البيانات في جدول customs_declarations
            // $customsDeclaration = CustomsDeclaration::create([
            //     'statement_number' => $statementNumber,
            //     'client_id' => $client->id,
            //     'subclient_id' => $subclientId,
            //     'expire_customs' => $expireCustoms,
            //     'customs_weight' => $customsWeight,
            // ]);

            // استخراج بيانات الحاوية (Container) من النص
            $containerNumber = $this->extractContainerNumbers($text);
            $containerSize = $this->extractContainerSize($text);

            // // حفظ البيانات في جدول containers
            // $container = Container::create([
            //     'customs_id' => $customsDeclaration->id,
            //     'client_id' => $client->id,
            //     'number' => $containerNumber,
            //     'size' => $containerSize,
            //     'status' => 'wait', // الحالة الافتراضية
            // ]);

            // إرجاع النتيجة
            return response()->json([
                // 'customs_declaration' => $customsDeclaration,
                // 'container' => $container,
                'text' => $text,
                // 'institution_name' => $institutionName,
                // 'client' => $client,
                // 'statement_number' => intval($statementNumber),
                // 'subclient_id' => $subclientId,
                // 'expire_customs' => $expireCustoms,
                // 'customs_weight' => intval($customsWeight),
                'container_number' => $containerNumber,
                // 'container_size' => $containerSize,
            ]);
        }

        // في حالة عدم وجود ملف PDF في الطلب
        return response()->json([
            'error' => 'No PDF file uploaded'
        ], 400);
    }

    private function findClosestMatch($institutionName)
    {
        // تحويل الاسم المدخل إلى UTF8 لضمان التوافق مع النصوص العربية
        $institutionName = mb_strtolower(trim($institutionName), 'UTF-8');

        // استخدام Full-Text Search مع النصوص العربية
        return User::whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$institutionName])
            ->orderByRaw("MATCH(name) AGAINST(?) DESC", [$institutionName]) // ترتيب النتائج حسب الأفضل
            ->first();
    }


    private function extractInstitutionName($text)
    {
        // البحث عن النص الذي يسبق ".Licence No"
        preg_match('/\r\n(.*?)\r\n‫‪\.Licence No‬‬/u', $text, $matches);

        // تنظيف النص إذا تم العثور عليه
        if (!empty($matches[1])) {
            $name = preg_replace('/[\r\n]+/', ' ', $matches[1]); // إزالة الانتقالات بين الأسطر
            $name = preg_replace('/\s+/', ' ', $name); // إزالة المسافات الزائدة
            return trim($name); // تنظيف المسافات من البداية والنهاية
        }

        return null; // إذا لم يتم العثور على النص
    }

    // دالة لاستخراج رقم البيان الجمركي
    private function extractStatementNumber($text)
    {
        preg_match('/\.Dec No[\s\S]*?(\d+)/u', $text, $matches);
        return $matches[1] ?? null;
    }

    // دالة لاستخراج المستورد (subclient_id)
    private function extractSubclientId($text)
    {
        // البحث عن المستورد باستخدام نمط معين
        preg_match('/Delivery Order.*\R+\s*([^\d]+)/u', $text, $matches);
        $result = $matches[1] ?? null;

        // تنظيف النص إذا تم العثور عليه
        if ($result) {
            // إزالة الرموز غير المرئية والمسافات الزائدة
            $result = preg_replace('/[\r\n]+/', ' ', $result); // إزالة الانتقالات بين الأسطر
            $result = preg_replace('/\s+/', ' ', $result); // إزالة المسافات الزائدة
            $result = trim($result); // إزالة المسافات من بداية ونهاية النص
        }

        return $result;
    }

    // دالة لاستخراج تاريخ انتهاء الجمارك
    private function extractExpireCustoms($text)
    {
        // إزالة الرموز غير المرئية وتحسين النص
        $cleanedText = preg_replace('/[\r\n]+/', ' ', $text); // إزالة الانتقالات بين الأسطر
        $cleanedText = preg_replace('/\s+/', ' ', $cleanedText); // إزالة المسافات الزائدة

        // البحث عن النص "Importer \ Exporter" متبوعًا بتاريخ
        preg_match('/Importer\s*\\\\\s*Exporter.*?(\d{2}-\d{2}-\d{4})/u', $cleanedText, $matches);

        // إعادة التاريخ إذا وجد
        return $matches[1] ?? null;
    }

    // دالة لاستخراج وزن الجمارك
    private function extractCustomsWeight($text)
    {
        // تنظيف النص
        $cleanedText = preg_replace('/[\r\n]+/', ' ', $text); // إزالة الانتقالات بين الأسطر
        $cleanedText = preg_replace('/\s+/', ' ', $cleanedText); // إزالة المسافات الزائدة

        // البحث عن الرقم الذي يظهر قبل كلمة "Measurement"
        preg_match('/(\d+)\s*Measurement/u', $cleanedText, $matches);

        // إعادة الرقم إذا وُجد
        return $matches[1] ?? 0;
    }
    // دالة لاستخراج رقم الحاوية
    private function extractContainerNumbers($text)
    {
        // البحث عن القسم الخاص بـ "Marks & Numbers"
        if (preg_match('/Marks & Numbers(.*?)(?=(Port of Loading|Port of Discharge|$))/s', $text, $section)) {
            $relevantPart = $section[1]; // الجزء بين "Marks & Numbers" و "Port of Loading" أو "Port of Discharge"

            // البحث عن جميع أرقام الحاويات بصيغة AAAA 1234567
            preg_match_all('/\b[A-Z]{4}\s\d{7}\b/', $relevantPart, $matches);

            // التأكد من عدم وجود أرقام حاويات مكررة
            $uniqueContainers = array_unique($matches[0] ?? []);

            // إرجاع النتائج
            return $uniqueContainers;
        }

        // إذا لم يتم العثور على القسم
        return [];
    }


    // دالة لاستخراج حجم الحاوية
    private function extractContainerSize($text)
    {
        preg_match('/ﺣﺠﻢ ﺍﻟﺤﺎﻭﻳﺔ.*\R+\s*(20|40|box)/u', $text, $matches);
        return $matches[1] ?? null;
    }

}