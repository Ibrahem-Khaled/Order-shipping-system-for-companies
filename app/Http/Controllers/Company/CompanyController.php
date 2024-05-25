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
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

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

        $dailyData = Daily::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $UserData = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $CustomsDeclarationData = CustomsDeclaration::with('container')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $containerData = Container::with('customs', 'driver', 'car')
            ->where('status', 'transport')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $dailyDataArray = $dailyData->toArray();
        $CustomsDeclarationDataArray = $CustomsDeclarationData->toArray();
        $userDataArray = $UserData->toArray();
        $containerDataArray = $containerData->toArray();

        $notifications = array_merge($dailyDataArray, $CustomsDeclarationDataArray, $userDataArray, $containerDataArray);
        usort($notifications, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        //return response()->json($notifications);

        $carData = Cars::with([
            'driver',
            'container' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereIn('status', ['transport', 'done'])->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            },
            'daily' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('type', 'withdraw')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }
        ])->get();

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
            })->whereRaw('name NOT LIKE "%بنشر%"')
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
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $employees = User::whereIn('role', ['driver', 'administrative'])
            ->whereNotNull('sallary')
            ->with('employeedaily')
            ->get();

        $customs = User::where('role', 'client')->get();

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        $elbancher = [];

        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if ($user && Str::contains($user->name, 'بنشر')) {
                $sum = $user->employeedaily()
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancherSum += $sum;
                $elbancher[] = $user->employeedaily;
                $elbancher = [...$elbancher];
            }
        }
        $mergedArrayAlbancher = [];
        foreach ($elbancher as $collection) {
            $mergedArrayAlbancher = array_merge($mergedArrayAlbancher, $collection->toArray());
        }

        //return response()->json($mergedArrayAlbancher);

        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();

        $container = Container::all();

        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();
        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();
        return view(
            'Company.companyRevExp',
            compact(
                'container',
                'mergedArrayAlbancher',
                'employees',
                'daily',
                'cars',
                'elbancherSum',
                'others',
                'customs',
            )
        );
    }
}
