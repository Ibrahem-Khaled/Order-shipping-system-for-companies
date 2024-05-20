<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\Daily;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;


class CompanyController extends Controller
{
    public function index()
    {
        $container = Container::all();
        $employee = User::whereIn('role', ['driver', 'administrative'])->whereNotNull('sallary')->get();
        $employeeSum = 0;
        foreach ($employee as $key => $employe) {
            $employeeSum += $employe->employeedaily->where('type', 'withdraw')->sum('price');
        }
        $employeeTips = Container::whereNotNull('driver_id')
            ->get();

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشري')) {
                $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                $elbancherSum = $elbancherSum + $sum;
            }
        }
        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشري%"')
            ->get();
        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
            $othersSum = $othersSum + $sum;
        }

        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();
        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();

        $clients = User::all();

        $deposit = 0;
        $withdraw = Daily::where('type', 'withdraw')->sum('price');
        foreach ($clients as $client) {
            $deposit += $client?->clientdaily->where('type', 'deposit')->sum('price');
        }
        $containerTransport = Container::whereIn('status', ['transport', 'done'])->sum('price');
        $clintPriceMinesContainer = $containerTransport - $deposit;

        $dailyData = Daily::latest()->take(8)->get();
        $UserData = User::latest()->take(8)->get();
        $CustomsDeclarationData = CustomsDeclaration::with('container')->latest()->take(8)->get();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $carData = Cars::with([
            'driver',
            'container' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereIn('status', ['transport','done'])->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            },
            'daily' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('type', 'withdraw')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }
        ])->get();

        $dailyDataArray = $dailyData->toArray();
        $CustomsDeclarationDataArray = $CustomsDeclarationData->toArray();
        $userDataArray = $UserData->toArray();

        $notifications = array_merge($dailyDataArray, $CustomsDeclarationDataArray, $userDataArray);
        // return response()->json($notifications);

        return view(
            'Company.index',
            compact('container', 'deposit', 'notifications', 'withdraw', 'container', 'employeeSum', 'daily', 'cars', 'carData', 'elbancherSum', 'othersSum', 'clintPriceMinesContainer')
        );
    }
    public function companyDetailes()
    {
        $partner = User::where('role', 'partner')->get();

        $container = Container::all();
        $employee = User::whereIn('role', ['driver', 'administrative'])->whereNotNull('sallary')->get();
        $employeeSum = 0;
        foreach ($employee as $key => $employe) {
            $employeeSum += $employe->employeedaily->where('type', 'withdraw')->sum('price');
        }

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشري')) {
                $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                $elbancherSum = $elbancherSum + $sum;
            }
        }
        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشري%"')
            ->get();
        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
            $othersSum = $othersSum + $sum;
        }

        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();
        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();
        return view('Company.companyDetailes', compact('container', 'employeeSum', 'daily', 'cars', 'elbancherSum', 'othersSum', 'partner'));
    }
    public function companyRevExp()
    {
        $employee = User::whereIn('role', ['driver', 'administrative'])->whereNotNull('sallary')->get();
        $employeeSum = 0;
        foreach ($employee as $key => $employe) {
            $employeeSum += $employe->employeedaily->where('type', 'withdraw')->sum('price');
        }

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشري')) {
                $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                $elbancherSum = $elbancherSum + $sum;
            }
        }
        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشري%"')
            ->get();
        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
            $othersSum = $othersSum + $sum;
        }

        $container = Container::get();
        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();
        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();
        return view('Company.companyRevExp', compact('container', 'employee', 'employeeSum', 'daily', 'cars', 'elbancherSum', 'othersSum'));
    }
}
