<?php

namespace App\Http\Controllers\FinanceialManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
}
