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
            'number.*' => 'required|min:7|max:14',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'يجب أن يحتوي رقم الحاوية على 7 أرقام');
        }

        $sizes = $request->input('size');
        $numbers = $request->input('number');
        $rents = $request->input('rent');
        $date_emptys = $request->input('date_empty');


        if (count($sizes)) {
            $count = count($sizes);
            for ($i = 0; $i < $count; $i++) {
                $size = $sizes[$i];
                $number = $numbers[$i];
                $rent = $rents[$i];
                $date_empty = $date_emptys[$i];

                Container::create([
                    'number' => $number,
                    'size' => $size,
                    'customs_id' => $customs_id,
                    'client_id' => $custom->client_id,
                    'status' => $rent == 'rent' ? 'rent' : 'wait',
                    'is_rent' => $rent == 'rent' ? 1 : 0,
                    'date_empty' => $date_empty,
                    'created_at' => $custom->created_at,
                ]);
            }

            return redirect()->route('getOfices')->with('success', 'تم الانشاء الحاويات بنجاح');
        } else {
            return redirect()->back()->with('error', 'هناك خطأ في البيانات المدخلة');
        }
    }


    public function thanksGod(Request $request)
    {
        $containers = Container::with('daily')->get();

        return view('thanksGod', compact('containers'));
    }

    public function updateDateEmpty(Request $request, $containerId)
    {
        $container = Container::findOrFail($containerId);
        $container->update([
            'date_empty' => $request->input('date_empty')
        ]);

        return redirect()->back()->with('success', 'تم تعديل التاريخ بنجاح');
    }


}
