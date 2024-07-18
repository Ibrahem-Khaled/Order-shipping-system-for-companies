<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContanierController extends Controller
{
    public function store(Request $request, $customs_id)
    {
        $custom = CustomsDeclaration::find($customs_id);

        $validator = Validator::make($request->all(), [
            'number.*' => 'required|min:7|max:7',
        ]);
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'يجب أن يحتوي رقم الحاوية على 7 أرقام');
        }

        $sizes = $request->input('size');
        $numbers = $request->input('number');
        $rents = $request->input('rent');

        // Check if all arrays are not null and have the same count
        if (count($sizes)) {
            $count = count($sizes);
            // Loop through arrays to process the data
            for ($i = 0; $i < $count; $i++) {
                $size = $sizes[$i];
                $number = $numbers[$i];
                $rent = $rents[$i];

                // Create a new record in the database for each set of values
                Container::create([
                    'number' => $number,
                    'size' => $size,
                    'customs_id' => $customs_id,
                    'client_id' => $custom->client_id,
                    'status' => $rent == 'rent' ? 'rent' : 'wait',
                    'is_rent' => $rent == 'rent' ? 1 : 0,
                    'created_at' => $custom->created_at,
                ]);
            }

            return redirect()->route('getOfices')->with('success', 'تم الانشاء الحاويات بنجاح');
        } else {
            // Handle the case where arrays are null or have different counts
            return redirect()->back()->with('error', 'هناك خطأ في البيانات المدخلة');
        }
    }


    public function thanksGod(Request $request)
    {
        $year = $request->input('query');

        if ($year) {
            $containers = Container::with('daily')->whereYear('created_at', $year)->get();
        } else {
            $containers = Container::with('daily')->get();
        }

        return view('thanksGod', compact('containers'));
    }


}
