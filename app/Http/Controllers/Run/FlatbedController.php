<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Flatbed;
use Illuminate\Http\Request;

class FlatbedController extends Controller
{
    public function index()
    {
        $flatbeds = Flatbed::all();
        return view('flatbed.index', compact('flatbeds'));
    }

    public function store(Request $request)
    {
        $flatbed = Flatbed::create($request->all());
        return redirect()->route('flatbeds.index');
    }

    public function update(Request $request, Flatbed $flatbed)
    {
        $flatbed->update($request->all());
        return redirect()->route('flatbeds.index');
    }

    public function destroy(Flatbed $flatbed)
    {
        $flatbed->delete();
        return redirect()->route('flatbeds.index');
    }
}
