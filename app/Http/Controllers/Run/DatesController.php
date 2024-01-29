<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\User;
use Illuminate\Http\Request;

class DatesController extends Controller
{
    public function index()
    {
        $container = Container::whereIn('status', ['wait', 'rent'])->get();
        $containerPort = Container::where('status', 'transport')->latest('updated_at')->get();
        $driver = User::where('role', 'driver')->get();
        $rents = User::where('role', 'rent')->get();
        $cars = Cars::all();
        return view('run.dates.date', compact('container', 'driver', 'containerPort', 'cars', 'rents'));
    }

    public function update(Request $request, $id)
    {
        $container = Container::find($id);
        $container->update([
            'status' => $request->status,
            'driver_id' => $request->driver,
            'car_id' => $request->car,
            'rent_id' => $request->rent_id ?? null,
        ]);
        if ($request->status == 'wait') {
            return redirect()->back()->with('success', 'تم الغاء التحميل ');
        } else {
            return redirect()->back()->with('success', 'تم التحميل بنجاح');
        }
    }
}
