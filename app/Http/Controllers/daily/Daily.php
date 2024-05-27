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
    public function index(Request $request)
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $query = $request->input('query');

        if (is_null($query)) {
            $daily = days::orderBy('created_at', 'desc')->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->get();
        } else {
            $daily = days::where('created_at', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->get();
        }

        $cars = Cars::all();
        $client = User::whereIn('role', ['client', 'rent'])->get();
        $partner = User::whereIn('role', ['partner', 'company'])->get();
        $employee = User::whereIn('role', ['driver', 'administrative', 'company'])->get();

        return view('FinancialManagement.Daily.index', compact('daily', 'cars', 'client', 'partner', 'employee'));
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
            'car_id' => 'required_without_all:client_id,employee_id,partner_id',
            'client_id' => 'required_without_all:car_id,employee_id,partner_id',
            'employee_id' => 'required_without_all:car_id,client_id,partner_id',
            'partner_id' => 'required_without_all:car_id,client_id,employee_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'الرجاء اختيار قيمة واحدة على الأقل لحساب السيارة أو العميل أو الموظف');
        }

        $data = days::create($request->except('created_at'));
        $daily = days::find($data->id);

        if (!is_null($request->created_at)) {
            $daily->update([
                'created_at' => $request->created_at,
                'updated_at' => $request->created_at,
            ]);
        }
        if ($request->partner_id !== null) {
            $daily->update([
                'type' => 'partner_withdraw'
            ]);
        }
        return redirect()->back()->with('success', 'تم تريحل البيانات بنجاح');
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'car_id' => 'required_without_all:client_id,employee_id,partner_id',
            'client_id' => 'required_without_all:car_id,employee_id,partner_id',
            'employee_id' => 'required_without_all:car_id,client_id,partner_id',
            'partner_id' => 'required_without_all:car_id,client_id,employee_id',
        ];

        $messages = [
            'car_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'client_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'employee_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
            'partner_id.required_without_all' => 'الرجاء اختيار السيارة أو حساب العميل أو حساب الموظف واحد على الأقل',
        ];

        // تطبيق قواعد الصحة
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'الرجاء اختيار قيمة واحدة على الأقل لحساب السيارة أو العميل أو الموظف');
        }

        // If validation passes, create the record
        $daily = days::find($id);
        $daily->update($request->all());
        if ($request->partner_id !== null) {
            $daily->update([
                'type' => 'partner_withdraw'
            ]);
        }

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
    public function addContanierPriceTransfer(Request $request)
    {
        $container = Container::where('number', $request->number)->first();
        if (!$container) {
            return redirect()->back()->with('error', 'لا توجد حاوية بهذا الرقم');
        } else {
            days::create([
                'type' => 'withdraw',
                'price' => $request->price,
                'container_id' => $container->id,
                'client_id' => $container->client_id,
            ]);
            return redirect()->back()->with('success', 'تم اضافة السعر بنجاح');
        }
    }


}
