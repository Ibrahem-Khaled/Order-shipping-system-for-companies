<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\CustomsDeclaration;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function index()
    {
        $statements = CustomsDeclaration::whereHas('container', function ($query) {
            $query->where('status', 'wait');
        })->with('container')->paginate(30);

        return view('run.Reservations.index', compact('statements'));
    }

    public function show($id)
    {
        $statement = CustomsDeclaration::with('container')->findOrFail($id);
        return view('run.Reservations.customs-containers', compact('statement'));
    }
}
