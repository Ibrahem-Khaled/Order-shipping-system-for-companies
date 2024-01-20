<?php

namespace App\Http\Controllers\FinanceialManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class RevenuesController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'client')->get();
        return view('FinancialManagement.Revenues.index', compact('users'));
    }

    public function accountStatement($clientId)
    {
        $user = User::find($clientId);
        return view('FinancialManagement.Revenues.accountStatement', compact('user'));
    }
    public function accountYears($clientId)
    {
        // Get the current month
        $currentMonth = Carbon::now()->month;

        // Retrieve records for the current month
        $user = User::find($clientId);
        $daily = $user->clientdaily;
        // Retrieve container records for the current month
        $container = $user->container
            ->filter(function ($item) use ($currentMonth) {
                return $item->created_at->month == $currentMonth;
            })
            ->sum('price');

        // Retrieve container records for each month
        $containerByMonth = $user->container
            ->groupBy(function ($item) {
                return $item->created_at->format('F Y'); // Group by month and year
            })
            ->map(function ($group) {
                return $group->sum('price');
            });

        // return response()->json([
        //     'containerByMonth' => $containerByMonth,
        //     'container' => $container,
        // ]);

        return view('FinancialManagement.Revenues.accountYears', compact('user', 'daily', 'container', 'currentMonth'));
    }
}
