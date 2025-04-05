<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\User;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;
use DeepSeek\DeepSeekClient;

class ConvertPdfToTextController extends Controller
{
    public function convert(Request $request)
    {
        set_time_limit(120);

        // التحقق من صحة الطلب
        $validator = validator($request->all(), [
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $pdfFile = $request->file('pdf_file');
        // حفظ الملف مؤقتًا
        $path = $pdfFile->store('temp');
        $fullPath = storage_path('app/' . $path);
        return response()->json([
            'status' => 'success',
            'message' => 'تم تحميل الملف بنجاح',
            'path' => $fullPath,
        ], 200);
        // استخراج النص من الملف
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($fullPath);
            $textContent = $pdf->getText();
        } catch (\Exception $e) {
            // إذا كان الخطأ بسبب Invalid object reference، نجرب طريقة بديلة باستخدام pdftotext
            if (strpos($e->getMessage(), 'Invalid object reference') !== false) {
                $output = shell_exec("pdftotext " . escapeshellarg($fullPath) . " -");
                if ($output) {
                    $textContent = $output;
                } else {
                    unlink($fullPath);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'فشل في قراءة الملف PDF باستخدام Smalot أو pdftotext.',
                        'error' => $e->getMessage()
                    ], 500);
                }
            } else {
                unlink($fullPath);
                return response()->json([
                    'status' => 'error',
                    'message' => 'فشل في قراءة الملف PDF.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        // حذف الملف المؤقت
        unlink($fullPath);

        // التحقق من وجود نص قابل للقراءة
        if (empty(trim($textContent))) {
            return response()->json([
                'status' => 'error',
                'message' => 'الملف PDF لا يحتوي على نص قابل للقراءة أو قد يكون مسحوباً ضوئياً'
            ], 400);
        }

        // التحقق من طول النص
        if (strlen($textContent) > 100000) {
            return response()->json([
                'status' => 'error',
                'message' => 'المستند كبير جداً. الحد الأقصى المسموح: 100000 حرف'
            ], 400);
        }

        // معالجة النص باستخدام DeepSeekClient
        try {
            $deepseek = app(DeepSeekClient::class);
            $response = $deepseek->query(
                "يرجى تحويل بيانات PDF التالية إلى صيغة JSON واستخراج الحقول التالية فقط:
            1. رقم الإعلان (statement_number) وهو ليس رقم الموحد
            2. اسم مكتب التخليص الجمركي (client_id)
            3. اسم المستورد أو المصدر (importer_name)
            4. تاريخ التفريغ (expire_customs)
            5. الوزن الإجمالي (customs_weight) وإذا كان الرقم غير صحيح يرجى تصحيحه.
            
            بالإضافة إلى ذلك، يرجى استخراج بيانات الحاويات في مصفوفة تحتوي على:
            - رقم الحاوية (number) مع ازالة الحروف غير الضرورية
            - حجم الحاوية (size) حيث تكون القيمة 20 أو 40 أو 'box'                
            النص: " . $textContent
            )->run();

            if (empty($response)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'لم يتم الحصول على استجابة من خدمة المعالجة'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء معالجة النص.',
                'error' => $e->getMessage()
            ], 500);
        }
        $data = json_decode($response, true);

        if (isset($data['choices'][0]['message']['content'])) {
            $content = $data['choices'][0]['message']['content'];
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم الحصول على استجابة صحيحة من خدمة المعالجة'
            ], 500);
        }
        if (preg_match('/```json(.*?)```/s', $content, $matches)) {
            $jsonString = trim($matches[1]);
            $jsonData = json_decode($jsonString, true);
        } else {
            // إذا لم يتم العثور على الجزء الصحيح، يمكن إرجاع خطأ
            return response()->json([
                'status' => 'error',
                'message' => 'لم يتم العثور على بيانات JSON صالحة في الاستجابة'
            ], 500);
        }

        return $this->addResponseFromModel($jsonData);
    }

    public function addResponseFromModel($data)
    {
        $user = User::where('name', 'like', '%' . $data['client_id'] . '%')->first();
        if (!$user) {
            $user = User::create([
                'name' => $data['client_id'],
                'role' => 'client',
            ]);
        }
        // تحقق مما إذا كان المستخدم موجودًا
        if ($user) {
            $customsDeclaration = CustomsDeclaration::create([
                'client_id' => $user->id,
                'statement_number' => $data['statement_number'],
                'importer_name' => $data['importer_name'],
                // 'expire_customs' => $data['expire_customs'],
                'customs_weight' => $data['customs_weight'],
            ]);

            foreach ($data['containers'] as $containerData) {
                $allowedSizes = ['20', '40', 'box'];
                $size = $containerData['size']; // استخراج القيمة من البيانات

                // تحويل القيمة إلى سلسلة إذا كانت رقمية
                if (is_numeric($size)) {
                    $size = (string) $size;
                }

                if (!in_array($size, $allowedSizes, true)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'حجم الحاوية غير صحيح. يجب أن يكون "20" أو "40" أو "box".'
                    ], 422);
                }

                Container::create([
                    'customs_id' => $customsDeclaration->id,
                    'client_id' => $user->id,
                    'number' => $containerData['number'],
                    'size' => $size,
                ]);
            }


        }
        return response()->json([
            'status' => 'success',
            'customsDeclaration' => $customsDeclaration,
            'client_id' => $user->id,
            'containers' => $data['containers'],
            'message' => 'تمت إضافة البيانات بنجاح',
        ], 200);
    }

}