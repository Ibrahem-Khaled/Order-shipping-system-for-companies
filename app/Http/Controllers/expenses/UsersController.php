<?php

namespace App\Http\Controllers\expenses;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function albancher()
    {
        $users = User::where('name', 'like', '%البنشري%')->get();
        return view('FinancialManagement.Expenses.users.albancher', compact('users'));
    }
    public function albancherDaily($id)
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $user = User::with([
            'employeedaily' => function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear);
            }
        ])->find($id);

        return view('FinancialManagement.Expenses.users.albancherDaily', compact('user'));
    }

    public function employee()
    {
        $employee = User::where('role', ['administrative', 'driver'])
            ->orWhereNotNull('sallary')
            ->get();

        return view('FinancialManagement.Expenses.users.employee', compact('employee'));
    }
    public function employeeDaily($id)
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $user = User::with([
            'employeedaily' => function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear);
            }
        ])->find($id);

        return view('FinancialManagement.Expenses.users.employeeDaily', compact('user'));
    }
    public function employeeTips($id)
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $user = User::with([
            'employeedaily' => function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear);
            }
        ])->find($id);

        return view('FinancialManagement.Expenses.users.employeeTips', compact('user'));
    }
}
