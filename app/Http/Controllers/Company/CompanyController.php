<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $container = Container::all();
        return view('Company.index', compact('container'));
    }
}
