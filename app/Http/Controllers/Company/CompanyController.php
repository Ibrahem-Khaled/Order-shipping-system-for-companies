<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Daily;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CompanyController extends Controller
{
    public function index()
    {
        $container = Container::all();
        $employee = User::whereIn('role', ['driver', 'administrative'])->get();
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
        $others = User::where('role', 'driver')
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
        return view('Company.index', compact('container', 'deposit', 'withdraw', 'container', 'employee', 'daily', 'cars', 'employeeTips', 'elbancherSum', 'othersSum'));
    }
    public function companyDetailes()
    {
        $partner = User::where('role', 'partner')->get();

        $container = Container::all();
        $employee = User::whereIn('role', ['driver', 'administrative'])->get();
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
        $others = User::where('role', 'driver')
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
        return view('Company.companyDetailes', compact('container', 'employee', 'daily', 'cars', 'employeeTips', 'elbancherSum', 'othersSum', 'partner'));
    }
    public function companyRevExp()
    {
        $employee = User::whereIn('role', ['driver', 'administrative'])->get();
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
        $others = User::where('role', 'driver')
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
        return view('Company.companyRevExp', compact('container', 'employee', 'daily', 'cars', 'employeeTips', 'elbancherSum', 'othersSum'));
    }
}
