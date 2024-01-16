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
    public function store(Request $request)
    {
        $name = $request->name;
        
        User::create([
            'name' => $name,
            'role' => 'client',
        ]);
        return redirect()->back()->with('success', 'تم الانشاء بنجاح');
    }
}
