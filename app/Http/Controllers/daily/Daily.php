<?php

namespace App\Http\Controllers\daily;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Daily as days;

class Daily extends Controller
{
    public function index()
    {
        $daily = days::all();
        $cars = Cars::all();
        $client = User::where('role', 'client')->get();
        $employee = User::whereIn('role', ['driver', 'administrative'])->get();
        return view('FinancialManagement.Daily.index', compact('daily', 'cars', 'client', 'employee'));
    }

    public function store(Request $request)
    {
        days::create($request->all());
        return redirect()->back()->with('success', 'تم تريحل البيانات بنجاح');
    }
}
