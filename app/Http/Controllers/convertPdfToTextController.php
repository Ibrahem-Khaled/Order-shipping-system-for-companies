<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\User;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class convertPdfToTextController extends Controller
{
    function normalizeName(string $name): string
    {
        // 1. نحذف الفراغات من الأطراف
        $name = trim($name);

        // 2. ندمج كل المتتاليات المتكررة من الفراغ في فراغ واحد
        //    مثال: "شركة   المثال   " -> "شركة المثال"
        $name = preg_replace('/\s+/u', ' ', $name);

        // 3. نحول إلى حروف صغيرة (lowercase)
        //    إذا كان الاسم باللغة العربية، يمكن الاستغناء عن هذا
        //    لكن قد يفيد في حال وجود حروف لاتينية ضمن الاسم
        $name = mb_strtolower($name);

        return $name;
    }

    public function index()
    {
        return view('ai');
    }

    public function convert(Request $request)
    {
        // 1. التحقق من صحة الطلب
        $validator = validator($request->all(), [
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10 ميجابايت
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // 2. تخزين الملف مؤقتاً في storage/app/public
        $pdfFile      = $request->file('pdf_file');
        $relativePath = $pdfFile->store('public');
        // مثال: "public/abc123.pdf"

        // 3. الحصول على المسار المطلق الفعلي
        $absolutePath = Storage::path($relativePath);
        // مثال: "/var/www/project/storage/app/public/abc123.pdf"

        // 4. قراءة محتوى ملف PDF ثنائيّاً ثمّ تشفيره إلى Base64
        try {
            $binaryData = file_get_contents($absolutePath);
            $base64Pdf  = base64_encode($binaryData);
            $filename   = basename($absolutePath);
        } catch (\Exception $e) {
            Storage::delete($relativePath);
            return response()->json([
                'status'  => 'error',
                'message' => 'فشل في قراءة ملف PDF.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // 5. حذف الملف المؤقت بعد القراءة
        Storage::delete($relativePath);

        // 6. تحضير التعليمات (prompt) لإرسالها مع الملف إلى نموذج gpt-4o-mini
        $instructions =
            "يرجى تحويل بيانات PDF التالية إلى صيغة JSON واستخراج الحقول التالية فقط:\n" .
            "1. رقم الإعلان (statement_number) وهو ليس رقم الموحد\n" .
            "2. اسم مكتب التخليص الجمركي (client_id)\n" .
            "3. اسم المستورد أو المصدر (importer_name)\n" .
            "4. تاريخ التفريغ (expire_customs)\n" .
            "5. الوزن الإجمالي (customs_weight) وإذا كان الرقم غير صحيح يرجى تصحيحه.\n\n" .
            "بالإضافة إلى ذلك، يرجى استخراج بيانات الحاويات في مصفوفة تحتوي على:\n" .
            "- رقم الحاوية (number) مع التاكد انه ليس رقم البوليصة وايضا رقم الحاوية مكون من 4 حروف و 7 ارقام وغير ذلك فهو غير صحيح\n" .
            "- حجم الحاوية (size) حيث تكون القيمة 20 أو 40 أو 'box'";

        // 7. بناء هيكل الرسائل مع تضمين ملف PDF مشفرًا بالـ Base64
        $messages = [
            [
                'role'    => 'system',
                'content' => 'أنت مساعد قادر على معالجة ملفات PDF مباشرةً واستخراج المعلومات المطلوبة.'
            ],
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'file',
                        'file' => [
                            'filename'  => $filename,
                            'file_data' => "data:application/pdf;base64,{$base64Pdf}",
                        ],
                    ],
                    [
                        'type' => 'text',
                        'text' => $instructions,
                    ],
                ],
            ],
        ];

        // 8. استدعاء نموذج gpt-4o-mini عبر OpenAI PHP Client
        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'messages'    => $messages,
                'temperature' => 0, // لضمان دقة واستقرار أكثر
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ أثناء إرسال الطلب إلى OpenAI.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // 9. التقاط النص المردود من النموذج
        if (!isset($response['choices'][0]['message']['content'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'لم يتم الحصول على استجابة صحيحة من نموذج OpenAI.',
            ], 500);
        }

        $content = $response['choices'][0]['message']['content'];

        // 10. استخراج JSON من داخل القالب ```json ... ``` إن وُجد
        if (preg_match('/```json(.*?)```/s', $content, $matches)) {
            $jsonString = trim($matches[1]);
            $jsonData   = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'فشل في تحويل السلسلة إلى مصفوفة JSON: ' . json_last_error_msg(),
                ], 500);
            }
        } else {
            // في حال أعاد النموذج JSON صريحًا دون القالب
            $jsonData = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'لم يتم العثور على بيانات JSON صالحة في الاستجابة.',
                ], 500);
            }
        }

        // 11. إرجاع النتيجة النهائية
        // return response()->json([
        //     'status'  => 'success',
        //     'data'    => $jsonData,
        //     'message' => 'تم تحويل ملف PDF بنجاح.',
        // ], 200);
        return $this->addResponseFromModel($jsonData);
    }

    public function addResponseFromModel($data)
    {
        $rawName = $data['client_id'];
        // 2. نطبّق توحيد التنسيق عليه
        $normalizedName = $this->normalizeName($rawName);

        // 3. نبحث في قاعدة البيانات عن سجلّ يناسب هذا الاسم بعد توحيده
        //    سنبحث بقيمة متطابقة (equals) في عمود "name_normalized" إذا أضفناه،
        //    أو بوضع دالة تحويل في البحث نفسه.
        $user = User::whereRaw('LOWER(TRIM(REGEXP_REPLACE(name, "\\s+", " "))) = ?', [
            $normalizedName
        ])->first();

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

                $container = Container::firstOrCreate(
                    [
                        'number' => $containerData['number'],
                        'size' => $size,
                    ],
                    [
                        'customs_id' => $customsDeclaration->id,
                        'client_id' => $user->id,
                    ]
                );


                // [
                //     'customs_id' => $customsDeclaration->id,
                //     'client_id' => $user->id,
                //     'number' => $containerData['number'],
                //     'size' => $size,
                // ]
            }
        }
        // return response()->json([
        //     'status' => 'success',
        //     'customsDeclaration' => $customsDeclaration,
        //     'client_id' => $user->id,
        //     'containers' => $data['containers'],
        //     'message' => 'تمت إضافة البيانات بنجاح',
        // ], 200);

        return redirect()
            ->route('getOfices')
            ->with('success', 'اي خدمة يا مهرهر يا تعبان ملة')
            ->with('warning', $user->name);
    }
}
