<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DatesController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        if (is_null($query)) {
            $container = Container::whereIn('status', ['wait', 'rent'])->get();
            $containerPort = Container::where('status', 'transport')->latest('updated_at')->get();
        } else {
            $container = Container::where('status', 'wait')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('created_at', 'like', '%' . $query . '%')
                        ->orWhere('number', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->get();

            $containerPort = Container::where('status', 'transport')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('number', 'like', '%' . $query . '%')
                        ->orWhere('created_at', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->get();
        }

        $driver = User::where('role', 'driver')
            ->whereNotNull('sallary')
            ->get();

        $rents = User::where('role', 'rent')->get();
        $cars = Cars::where('type', 'transfer')->get();
        return view('run.dates.date', compact('container', 'driver', 'containerPort', 'cars', 'rents'));
    }
    public function empty(Request $request)
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;

        $query = $request->input('query');
        if (is_null($query)) {
            $done = Container::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereIn('status', ['done'])
                ->get();
            $containerPort = Container::where('status', 'transport')->latest('updated_at')->get();

        } else {
            $done = Container::where('status', 'done')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('created_at', 'like', '%' . $query . '%')
                        ->orWhere('number', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->get();

            $containerPort = Container::where('status', 'transport')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('number', 'like', '%' . $query . '%')
                        ->orWhere('created_at', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->get();
        }


        $driver = User::where('role', 'driver')->get();
        $rents = User::where('role', 'rent')->get();
        $cars = Cars::where('type', 'transfer')->get();
        return view('run.dates.empty', compact('done', 'driver', 'containerPort', 'cars', 'rents'));
    }

    public function update(Request $request, $id)
    {
        $container = Container::find($id);
        $driver = User::find($request->driver);
        $container->update([
            'status' => $request->status,
            'driver_id' => $request->driver,
            'tips' => $driver->tips ?? null,
            'car_id' => $request->car,
            'rent_id' => $request->rent_id ?? null,
        ]);
        if ($request->status == 'wait') {
            return redirect()->back()->with('success', 'تم الغاء التحميل ');
        } else {
            return redirect()->back()->with('success', 'تم التحميل بنجاح');
        }
    }
    public function updateEmpty(Request $request, $id)
    {
        $container = Container::find($id);
        $container->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'تم التحميل بنجاح');

    }
}
