<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;

class ContanierController extends Controller
{
    public function store(Request $request, $customs_id)
    {
        $custom = CustomsDeclaration::find($customs_id);

        $sizes = $request->input('size');
        $numbers = $request->input('number');
        $rent = $request->has('rent') ? 'rent' : 'wait';

        //return response()->json($numbers);
        // Check if all arrays are not null and have the same count
        if (count($sizes)) {
            $count = count($sizes);

            // Loop through arrays to process the data
            for ($i = 0; $i < $count; $i++) {
                $size = $sizes[$i];
                $number = $numbers[$i];

                // Create a new record in the database for each set of values
                Container::create([
                    'number' => $number,
                    'size' => $size,
                    'customs_id' => $customs_id,
                    'client_id' => $custom->client_id,
                    'status' => $rent,
                ]);
            }

            return redirect()->route('getOfices')->with('success', 'تم الانشاء الحاويات بنجاح');
        } else {
            // Handle the case where arrays are null or have different counts
            return redirect()->back()->with('error', 'هناك خطأ في البيانات المدخلة');
        }
    }


}
