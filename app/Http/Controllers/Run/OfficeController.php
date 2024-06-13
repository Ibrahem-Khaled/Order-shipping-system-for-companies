<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        return view('run.addoffice');
    }
    public function store(Request $request, $role)
    {
        $name = $request->name;

        User::create([
            'name' => $name,
            'role' => $role,
        ]);

        if ($role == 'rent') {
            return redirect()->route('getOfficesRent')->with('success', 'تم الانشاء بنجاح');
        }

        return redirect()->route('getOfices')->with('success', 'تم الانشاء بنجاح');
    }

}
