<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employee = User::whereIn('role', ['driver', 'administrative'])
            ->whereNotNull('sallary')
            ->get();
        $cars = Cars::all();
        return view('employee.employee', compact('employee', 'cars'));
    }
    public function type(Request $request, $userId)
    {
        $user = User::find($userId);
        return view('employee.employeeType', compact('user'));
    }

    public function storeType(Request $request, $userId)
    {
        $user = User::find($userId);

        $userinfo = UserInfo::where('user_id', $userId)->first(); // Use first() instead of get()

        if (!$userinfo) {
            return redirect()->route('getEmployee')->with('error', 'Userinfo not found.');
        }

        if ($request->role == 'driver') {
            $user->update([
                'tips' => $request->tips,
            ]);
            return redirect()->route('getEmployee')->with('success', 'تم اضافة الترب بنجاح');
        } else {
            $userinfo->update([
                'job_title' => $request->job_title,
            ]);
            return redirect()->route('getEmployee')->with('success', 'تم تحديث المسمي الوظيفي');
        }
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'sallary' => $request->sallary,
            'password' => $request->password,
            'role' => $request->role,
            'tips' => $request->tips,
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('user_images', 'public');
        } else {
            $image = null;
        }

        UserInfo::create([
            'user_id' => $user->id,
            'gender' => $request->gender,
            'number_residence' => $request->number_residence,
            'age' => $request->age,
            'date_runer' => $request->date_runer,
            'nationality' => $request->nationality,
            'marital_status' => $request->marital_status,
            'expire_residence' => $request->expire_residence,
            'image' => $image,
        ]);

        if ($request->role == 'driver') {
            return redirect()->route('getEmployeeType', [
                'name' => $request->role,
                'userId' => $user->id,
            ])->with('success', 'تم انشاء بيانات الموظف بنجاح');
        } else {
            return redirect()->route('getEmployeeType', [
                'name' => $request->role,
                'userId' => $user->id,
            ])->with('success', 'تم انشاء بيانات الموظف بنجاح');
        }
    }

    public function storeCar(Request $request)
    {
        if (is_null($request->id)) {
            Cars::create($request->all());
            return redirect()->route('getEmployee')->with('success', 'تم إضافة السيارة بنجاح');
        } else {
            $car = Cars::find($request->id);
            if ($car) {
                $car->update($request->except('id'));
                return redirect()->route('getEmployee')->with('success', 'تم تعديل السيارة بنجاح');
            } else {
                return redirect()->route('getEmployee')->with('error', 'لم يتم العثور على السيارة المطلوبة');
            }
        }
    }
}
