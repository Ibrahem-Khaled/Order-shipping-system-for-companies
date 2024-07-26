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

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'يجب أن يحتوي رقم الحاوية على 7 أرقام');
        }

        $sizes = $request->input('size');
        $numbers = $request->input('number');
        $rents = $request->input('rent');

        if (count($sizes)) {
            $count = count($sizes);
            for ($i = 0; $i < $count; $i++) {
                $size = $sizes[$i];
                $number = $numbers[$i];
                $rent = $rents[$i];

                $status = ($size == 'box') ? 'done' : ($rent == 'rent' ? 'rent' : 'wait');
                $is_rent = $rent == 'rent' ? 1 : 0;

                Container::create([
                    'number' => $number,
                    'size' => $size,
                    'customs_id' => $customs_id,
                    'client_id' => $custom->client_id,
                    'status' => $status,
                    'is_rent' => $is_rent,
                    'created_at' => $custom->created_at,
                ]);
            }

            return redirect()->route('getOfices')->with('success', 'تم إنشاء الحاويات بنجاح');
        } else {
            return redirect()->back()->with('error', 'هناك خطأ في البيانات المدخلة');
        }
    }


    public function thanksGod(Request $request)
    {
        $containers = Container::with('daily')->get();

        return view('thanksGod', compact('containers'));
    }


}
