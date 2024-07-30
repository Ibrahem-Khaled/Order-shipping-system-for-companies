<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Tips;
use Illuminate\Http\Request;

class StorageContainerController extends Controller
{
    public function storageContainer(Request $request, $id)
    {
        $container = Container::find($id);
        $container->update([
            'status' => 'storage',
            'driver_id' => $request->driver,
            'car_id' => $request->car,
            'transfer_date' => $request->transfer_date,
        ]);
        Tips::create([
            'container_id' => $id,
            'user_id' => $request->driver,
            'car_id' => $request->car,
            'price' => $request->tips,
            'created_at' => $request->transfer_date,
            'type' => 'storage',
        ]);

        return redirect()->back()->with('success', 'تم تخزين الحاوية بنجاح');
    }
}
