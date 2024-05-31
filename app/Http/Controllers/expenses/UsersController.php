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
        $users = User::where('name', 'like', '%بنشر%')->get();
        return view('FinancialManagement.Expenses.users.albancher', compact('users'));
    }
    public function albancherDaily(Request $request, $id)
    {
        $query = $request->input('query');
        $q = null;
        if ($query) {
            $q = Carbon::createFromFormat('Y-m', $query);
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $user = User::with([
            'employeedaily' => function ($query) use ($currentMonth, $currentYear, $q) {
                if (!$q) {
                    $query->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear);
                } else {
                    $query->whereMonth('created_at', $q->month)->whereYear('created_at', $q->year);
                }
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
        $employee = User::find($id);

        return view('FinancialManagement.Expenses.users.employeeDaily', compact('user', 'employee'));
    }
    public function employeeTips($id)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $user = User::find($id);

        $currentMonthContainers = $user->driverContainer()
            ->whereMonth('transfer_date', Carbon::now()->month)
            ->whereYear('transfer_date', Carbon::now()->year)
            ->with('customs')
            ->get();

        $allTrips = $currentMonthContainers->sum('tips');

        return view('FinancialManagement.Expenses.users.employeeTips', compact('user', 'currentMonthContainers', 'allTrips'));
    }

    public function others()
    {
        $users = User::whereIn('role', ['driver'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();

        return view('FinancialManagement.Expenses.others', compact('users'));
    }
}
