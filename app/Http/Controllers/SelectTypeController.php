<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelectTypeController extends Controller
{
    public function index()
    {
        return view('SelectType');
    }
    public function runDash()
    {
        return view('run.DashRun');
    }
}
