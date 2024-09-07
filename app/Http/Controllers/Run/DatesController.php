<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\Flatbed;
use App\Models\FlatbedContainer;
use App\Models\Tips;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

// use Illuminate\Pagination\Paginator;
// use Illuminate\Support\Collection;
// use Illuminate\Pagination\LengthAwarePaginator;

class DatesController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        if (is_null($query)) {
            $container = Container::whereIn('status', ['wait', 'rent'])->orderBy('created_at', 'desc')->get();
            $containerPort = Container::where('status', 'transport')->latest('updated_at')->get();
        } else {
            $container = Container::whereIn('status', ['wait', 'rent'])
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
                ->where('status', 'done')
                ->with('tipsEmpty')
                ->paginate(1);
            $containerPort = Container::where('status', 'transport')->latest('updated_at')->paginate(10);
            $storageContainer = Container::whereIn('status', ['storage', 'rent'])->latest('updated_at')->paginate(10);

        } else {
            $done = Container::where('status', 'done')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('created_at', 'like', '%' . $query . '%')
                        ->orWhere('number', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->paginate(1);

            $containerPort = Container::where('status', 'transport')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('number', 'like', '%' . $query . '%')
                        ->orWhere('created_at', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->paginate(10);

            $storageContainer = Container::whereIn('status', ['storage', 'rent'])
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('number', 'like', '%' . $query . '%')
                        ->orWhere('created_at', 'like', '%' . $query . '%')
                        ->orWhere('price', 'like', '%' . $query . '%');
                })
                ->paginate(10);
        }

        $driver = User::where('role', 'driver')->whereNotNull('sallary')->get();
        $rents = User::where('role', 'rent')->get();
        $cars = Cars::where('type', 'transfer')->get();
        $rents = User::where('role', 'rent')->get();

        return view('run.dates.empty', compact('done', 'driver', 'containerPort', 'cars', 'rents', 'storageContainer', 'rents'));
    }

    public function update(Request $request, $id)
    {
        $container = Container::find($id);
        $driver = User::find($request->driver);
        // الحصول على السطحات المتاحة فقط من نفس النوع
        $flatbeds = Flatbed::where('type', $container->size)->where('status', 1)->get();

        // إذا كانت الحاوية من نوع 'box'
        if ($container->size == 'box') {
            $request->merge(['status' => 'done']);
        }

        // تحديث بيانات الحاوية
        $containerUpdated = $container->update([
            'status' => $request->status,
            'transfer_date' => $request->transfer_date,
            'driver_id' => $request->driver,
            'tips' => $driver->tips ?? null,
            'car_id' => $request->car,
            'rent_id' => $request->rent_id ?? null,
        ]);

        // إذا كانت الحالة 'transport' (تحميل الحاوية)
        if ($containerUpdated && $request->status == 'transport') {
            $countFlatBedType = $flatbeds->count(); // عدد السطحات المتاحة من نفس نوع الحاوية

            // تحقق إذا كانت هناك سطحة متاحة
            if ($countFlatBedType > 0) {
                $flatbed = $flatbeds->first(); // احصل على أول سطحة متاحة من نفس النوع

                // إنشاء سجل في FlatbedContainer
                FlatbedContainer::create([
                    'container_id' => $id,
                    'flatbed_id' => $flatbed->id,
                ]);

                // تحديث حالة السطحة إلى غير متاحة (0) بعد تحميل الحاوية عليها
                $flatbed->update(['status' => 0]);

                return redirect()->back()->with('success', 'تم التحميل بنجاح');
            } else {
                // إذا لم تكن هناك سطحات متاحة من نفس الحجم
                return redirect()->back()->with('error', 'لا توجد سطحة متاحة لهذا الحجم');
            }
        }

        // إذا كانت الحالة 'wait' (إلغاء التحميل)
        if ($request->status == 'wait') {
            return redirect()->back()->with('success', 'تم الغاء التحميل');
        }

        return redirect()->back()->with('success', 'تم التحديث بنجاح');
    }


    public function updateEmpty(Request $request, $id)
    {
        $container = Container::findOrFail($id);
        $status = ($container->size == 'box') ? 'wait' : $request->status;

        if ($container->is_rent == 0) {
            if ($request->status == 'done') {
                Tips::create([
                    'container_id' => $id,
                    'user_id' => $request->user_id,
                    'car_id' => $request->car_id,
                    'price' => $request->price,
                ]);
                
                $flatbedContainer = FlatbedContainer::where('container_id', $id)->first();
                if ($flatbedContainer) {
                    $flatbed = Flatbed::find($flatbedContainer->flatbed_id);
                    if ($flatbed) {
                        // إعادة حالة السطحة إلى متاحة
                        $flatbed->update(['status' => 1]);
                    }
                }

            } elseif ($request->status == 'transport') {
                $lastTip = $container->tipsEmpty()->orderBy('created_at', 'desc')->first();
                if ($lastTip) {
                    $lastTip->delete();
                }
            }
            $container->update([
                'status' => $status,
            ]);

        } else {
            $container->update([
                'status' => $status,
            ]);
        }

        return redirect()->back()->with('success', 'تم التحميل بنجاح');
    }



    public function ContainerRentStatus($id, Request $request)
    {
        $status = $request->status;
        $storage = $request->storage !== null ? 'storage' : 'wait';

        $container = Container::find($id)->update([
            'status' => $status !== 'rent' ? 'rent' : $storage,
            'is_rent' => $status !== 'rent' ? 1 : 0,
        ]);

        $message = $status == 'rent' ? 'تم الغاء تاجير' : 'تم تاجير الحاوية';

        return redirect()->back()->with('success', $message);
    }

    public function deleteContainer($id)
    {
        $container = Container::find($id)->delete();
        return redirect()->back()->with('success', 'تم حذف الحاوية بنجاح ');
    }
}
