<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function index()
    {
        $statements = CustomsDeclaration::all();

        return view('run.Reservations.index', compact('statements'));
    }
}
