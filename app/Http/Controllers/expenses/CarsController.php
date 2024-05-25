<?php

namespace App\Http\Controllers\expenses;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use Illuminate\Http\Request;
use Str;

class CarsController extends Controller
{
    public function index()
    {
        $cars = Cars::all();
        return view('FinancialManagement.Expenses.cars', compact('cars'));
    }
    public function carsDaily(Request $request, $id)
    {
        $date = $request->query('date');

        $car = Cars::with([
            'daily' => function ($query) use ($date) {
                if (!$date) {
                    $query->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month);
                } else {
                    $query->where('created_at', 'LIKE', "%$date%");
                }
            }
        ])->find($id);


        return view('FinancialManagement.Expenses.carsDaily', compact('car'));
    }



}
