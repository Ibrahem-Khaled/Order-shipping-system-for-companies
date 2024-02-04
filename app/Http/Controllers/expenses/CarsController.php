<?php

namespace App\Http\Controllers\expenses;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function index()
    {
        $cars = Cars::all();
        return view('FinancialManagement.Expenses.cars', compact('cars'));
    }
    public function carsDaily($id)
    {
        $car = Cars::find($id);
        return view('FinancialManagement.Expenses.carsDaily', compact('car'));
    }
    public function sallary()
    {
        $cars = Cars::all();
        return view('FinancialManagement.Expenses.sallary', compact('cars'));
    }
}
