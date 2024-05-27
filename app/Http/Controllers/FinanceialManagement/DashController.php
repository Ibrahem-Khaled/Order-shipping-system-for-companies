<?php

namespace App\Http\Controllers\FinanceialManagement;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'company')
            ->get();

        return view('FinancialManagement.index', compact('users'));
    }
}
