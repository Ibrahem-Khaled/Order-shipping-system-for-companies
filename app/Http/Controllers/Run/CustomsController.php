<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\User;
use Illuminate\Http\Request;

class CustomsController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'client')->get();

        //return response()->json($users);
        return view('run.Customs', compact('users'));
    }
    public function showContainerPost($customId)
    {
        $custom = CustomsDeclaration::find($customId);
        //return response()->json($custom);
        return view('run.addContanier',compact('custom'));
    }
    
    public function store(Request $request, $clientId)
    {
        $num = $request->statement_number;
        $sub_client = $request->subClient;
        $contNum = $request->contNum;

        $data = CustomsDeclaration::create([
            'statement_number' => $num,
            'subclient_id' => $sub_client,
            'client_id' => $clientId,
        ]);

        return redirect()->route('showContanierPost', [
            'contNum' => $contNum,
            'customId' => $data->id,
        ])->with('success', 'تم انشاء بيان بنجاح');
    }
}
