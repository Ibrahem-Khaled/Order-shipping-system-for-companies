<?php

namespace App\Http\Controllers\daily;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Daily as days;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class Daily extends Controller
{
    public function index()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $daily = days::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();

        $cars = Cars::all();
        $client = User::whereIn('role', ['client','rent'])->get();
        $employee = User::whereIn('role', ['driver', 'administrative'])->get();
        return view('FinancialManagement.Daily.index', compact('daily', 'cars', 'client', 'employee'));
    }

    public function delete($id)
    {
        $daily = days::find($id);
        $daily->delete();
        return redirect()->back()->with('success', 'تم بنجاح');
    }

    public function store(Request $request)
    {
        $rules = [
            'car_id' => 'required_without_all:client_id,employee_id',
            'client_id' => 'required_without_all:car_id,employee_id',
            'employee_id' => 'required_without_all:car_id,client_id',
        ];

        $messages = [
            'car_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'client_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'employee_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
        ];

        // تطبيق قواعد الصحة
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'الرجاء اختيار قيمة واحدة على الأقل لحساب السيارة أو العميل أو الموظف');
        }

        // If validation passes, create the record
        days::create($request->all());

        return redirect()->back()->with('success', 'تم تريحل البيانات بنجاح');
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'car_id' => 'required_without_all:client_id,employee_id',
            'client_id' => 'required_without_all:car_id,employee_id',
            'employee_id' => 'required_without_all:car_id,client_id',
        ];

        $messages = [
            'car_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'client_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'employee_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
        ];

        // تطبيق قواعد الصحة
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'الرجاء اختيار قيمة واحدة على الأقل لحساب السيارة أو العميل أو الموظف');
        }

        // If validation passes, create the record
        $daily = days::find($id);
        $daily->update($request->all());

        return redirect()->back()->with('success', 'تم تريحل البيانات بنجاح');
    }

    public function addStatement(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'role' => 'driver',
        ]);
        return redirect()->back()->with('success', 'تم انشاء بيانات الموظف بنجاح');

    }
    public function editContanierTips(Request $request)
    {
        $container = Container::where('number', $request->number)->first();
        if (!$container) {
            return redirect()->back()->with('error', 'لا توجد حاوية بهذا الرقم');
        } else {
            $container->update([
                'tips' => $request->tips,
            ]);
            return redirect()->back()->with('success', 'تم التعديل بنجاح');
        }
    }


}
