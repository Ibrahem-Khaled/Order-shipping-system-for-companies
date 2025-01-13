<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\CarChanegOilData as CarChangeOilData;
use Illuminate\Http\Request;

class CarChangeOilsController extends Controller
{
    public function index()
    {
        // جلب البيانات مع العلاقات
        $cars = Cars::with('oilChanges')->get();

        return view('car_change_oils.index', compact('cars'));
    }


    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'km' => 'required|numeric',
            'date' => 'nullable|date',
        ]);

        // جلب السيارة المرتبطة
        $car = Cars::find($request->car_id);

        // جلب آخر تغيير زيت (إن وجد)
        $lastOilChange = $car->oilChanges()->latest('date')->first();
        $newKm = $request->km;

        // إذا لم يتم إدخال تاريخ
        if (!$request->date) {
            if ($lastOilChange) {
                // حساب الكيلومترات المتبقية
                $remainingKm = $newKm - $lastOilChange->km_before;

                // تحديث السجل الأخير
                $lastOilChange->update([
                    'km_before' => $newKm,
                    'km_after' => $remainingKm,
                ]);
            } else {
                // إذا لم يكن هناك سجلات سابقة، يتم إنشاء سجل جديد
                $car->oilChanges()->create([
                    'km_before' => $newKm,
                    // 'km_after' => $car->oil_change_number - $newKm, // حساب الكيلومترات المتبقية
                ]);
            }
        } else {
            // إذا تم إدخال تاريخ، يتم تصفير الكيلومترات المتبقية وإنشاء سجل جديد
            $car->oilChanges()->create([
                'km_before' => $newKm,
                'km_after' => $car->oil_change_number, // تصفير الكيلومترات المتبقية
                'date' => $request->date,
            ]);
        }

        return redirect()->route('car_change_oils.index')
            ->with('success', 'تم إضافة بيانات تغيير الزيت بنجاح.');
    }
}
