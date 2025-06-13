<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Run\DatesController;
use App\Models\Cars;
use App\Models\Container;
use App\Models\User;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
    function normalizeVehicleNumber($number)
    {
        // 1. تحويل الأرقام العربية إلى أرقام إنجليزية
        $eastern_arabic_digits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $western_arabic_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $number = str_replace($eastern_arabic_digits, $western_arabic_digits, $number);

        // 2. حذف كل المسافات والفواصل والعلامات
        $number = preg_replace('/[\s\-\_\.]/u', '', $number);

        // 3. تحويل الحروف إلى صيغة موحدة (اختياري: مثلا من عربية إلى إنجليزية إن كانت هناك قاعدة واضحة)

        return $number;
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
            "يرجى تحويل بيانات ملف PDF التالي إلى صيغة JSON واستخراج الحقول التالية فقط:\n" .
            "1. رقم الإعلان (statement_number): يجب أن يكون رقم الإعلان وليس الرقم الموحد.\n" .
            "2. اسم مكتب التخليص الجمركي (client_id): اكتب الاسم كما هو مكتوب في الملف.\n" .
            "3. اسم المستورد أو المصدر (importer_name): اكتب الاسم الكامل كما هو.\n" .
            "4. تاريخ التفريغ (expire_customs): استخدم تنسيق التاريخ (YYYY-MM-DD).\n" .
            "5. الوزن الإجمالي (customs_weight): تحقق أن الوزن رقم صحيح فقط، وإذا كان الرقم غير صحيح أو يحتوي على رموز غير رقمية، يرجى تصحيحه ليكون رقمًا فقط.\n\n" .
            "بالإضافة إلى ذلك، يرجى استخراج بيانات الحاويات في مصفوفة باسم containers تحتوي على:\n" .
            "- رقم الحاوية (number): يجب أن يتكون من 4 حروف لاتينية كبيرة تليها 7 أرقام (مثال: ABCD1234567)، وأي رقم لا يطابق هذا النمط يعتبر غير صحيح.\n" .
            "- حجم الحاوية (size): يجب أن يكون أحد القيم التالية فقط: 20 أو 40 أو 'box'، وأي قيمة غير ذلك تعتبر غير صحيحة أو يتم تصحيحها إذا أمكن.\n\n" .
            "استخرج فقط الحقول المطلوبة وأعرض الناتج النهائي في صيغة JSON منظمة وواضحة.\n";


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

        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o',
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
        return $this->addResponseFromModel($jsonData);
    }

    public function addResponseFromModel($data)
    {
        $rawName = $data['client_id'];
        // 2. نطبّق توحيد التنسيق عليه
        $normalizedName = $this->normalizeName($rawName);

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
            }
        }

        return redirect()
            ->route('getOfices')
            ->with('success', 'اي خدمة يا مهرهر يا تعبان ملة')
            ->with('warning', $user->name);
    }

    public function PDFAnalysisOfTheContainer(Request $request)
    {
        // 1. التحقق من صحة الطلب
        $request->validate([
            'pdfFile' => 'required|mimes:pdf|max:8192',
        ]);

        // 2. تخزين الملف مؤقتاً
        $pdfFile = $request->file('pdfFile');
        $relativePath = $pdfFile->store('public');
        $absolutePath = Storage::path($relativePath);
        $filename = basename($absolutePath);

        // 3. قراءة الملف وتشفيره
        try {
            $binaryData = file_get_contents($absolutePath);
            $base64Pdf = base64_encode($binaryData);
        } catch (\Exception $e) {
            Storage::delete($relativePath);
            return response()->json([
                'status'  => 'error',
                'message' => 'فشل في قراءة ملف PDF.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // 4. حذف الملف المؤقت بعد الاستخدام
        Storage::delete($relativePath);

        // 5. تحضير التعليمات الخاصة بـ GPT
        $instructions = "
                    الرجاء تحليل النص الوارد واستخراج المعلومات التالية بدقة:
                    1. \"اسم السائق\"
                    2. \"رقم الحاوية وامسح المسافة بين الحروف والارقام ووعلي ان يكون 4 احرف و 7 ارقام\"
                    3. \"تاريخ النقل\"
                    4. \"نوع الموعد\" (استيراد أو تفريغ)
                    5. \"رقم السيارة\" إن وُجد
                    6. \"رقم هاتف السائق\" إن وُجد

                    — أرجو عرض النتيجة في شكل JSON يلتزم بهذا النموذج:
                    ```json
                    {
                    \"driver_name\": \"...\",           // اسم السائق
                    \"container_number\": \"...\",     // رقم الحاوية
                    \"transfer_date\": \"YYYY-MM-DD\", // تاريخ النقل
                    \"appointment_type\": \"...\"      // نوع الموعد: \"استيراد\" أو \"تفريغ\"
                    \"vehicle_number\": \"...\"        // رقم السيارة إن وُجد
                    \"driver_phone\": \"...\"          // رقم هاتف السائق إن وُجد
                    }
                    — إذا لم يكن أحد هذه الحقول متوفرًا في النص، ضع له القيمة null.
                    — تأكد من تنسيق التاريخ بالصيغة الموحدة (YYYY-MM-DD).";

        // 6. تحضير الرسائل للنموذج
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

        // 7. إرسال الطلب إلى OpenAI
        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o',
                'messages'    => $messages,
                'temperature' => 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ أثناء إرسال الطلب إلى OpenAI.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // 8. معالجة الاستجابة
        $content = $response['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            return response()->json([
                'status'  => 'error',
                'message' => 'لم يتم الحصول على استجابة صحيحة من نموذج OpenAI.',
            ], 500);
        }

        // 9. استخراج JSON من الاستجابة
        if (preg_match('/```json(.*?)```/s', $content, $matches)) {
            $jsonString = trim($matches[1]);
        } else {
            $jsonString = $content;
        }

        $jsonData = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status'  => 'error',
                'message' => 'فشل في تحليل الاستجابة JSON: ' . json_last_error_msg(),
            ], 500);
        }

        if (isset($jsonData['container_number'])) {
            $rawContainerNumber = $jsonData['container_number'];

            // نستخدم regex لاستخراج 4 أحرف يليها 7 أرقام فقط
            if (preg_match('/([A-Z]{4})(\d{7})/', strtoupper($rawContainerNumber), $matches)) {
                $cleanedNumber = $matches[1] . $matches[2]; // 4 أحرف + 7 أرقام

                // البحث في قاعدة البيانات
                $container = Container::where('number', 'like', '%' . $cleanedNumber . '%')->first();
            }
        }
        $driver = User::where('name', 'like', '%' . $jsonData['driver_name'] . '%')
            ->orWhere('phone', 'like', '%' . $jsonData['driver_phone'] . '%')
            ->first();

        if (!empty($jsonData['vehicle_number'])) {
            $cleanNumber = $this->normalizeVehicleNumber($jsonData['vehicle_number']);

            // نبحث بنفس الشكل في قاعدة البيانات (مفضل يكون الحقل مخزن بنفس الشكل)
            $car = Cars::whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(number, '٠', '0'), '١', '1'), '٢', '2'), '٣', '3'), '٤', '4'), '٥', '5'), '٦', '6'), '٧', '7'), '٨', '8'), '٩', '9')")
                ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(number, ' ', ''), '-', ''), '_', ''), '.', ''), '\r', ''), '\n', '') LIKE ?", ["%{$cleanNumber}%"])
                ->first();
        }
        if (!$container) {
            return response()->json([
                'status'  => 'لا توجد حاوية بهذا الرقم',
                'message' => $jsonData,
            ]);
            return redirect()->back()->with('error', 'لا توجد حاوية بهذا الرقم');
        }
        if (!$driver) {
            return response()->json([
                'status'  => 'لا توجد سائق بهذا الاسم',
                'message' => $jsonData,
            ]);
            return redirect()->back()->with('error', 'لا توجد سائق بهذا الاسم');
        }
        if (!$car) {
            return response()->json([
                'status'  => 'لا توجد سيارة بهذا الرقم',
                'message' => $jsonData,
            ]);
            return redirect()->back()->with('error', 'لا توجد سيارة بهذا الرقم');
        }

        // ✅ إرسال البيانات إلى الراوت الآخر
        $requestData = new \Illuminate\Http\Request([
            'status'        => $jsonData['appointment_type'] == 'استيراد' ? 'transport' : 'done',
            'transfer_date' => $jsonData['transfer_date'] ?? null,
            'driver'        => $driver->id,
            'car'           => $car->id,
        ]);

        $response = app()->call([app(DatesController::class), 'update'], [
            'id'      => $container->id,
            'request' => $requestData,
        ]);

        return $response; // إعادة الريدايركت الذي أرجعته دالة update

    }
}
