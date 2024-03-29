<?php

namespace App\Http\Controllers\Run;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\User;
use Illuminate\Http\Request;

class CustomsController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        if (is_null($query)) {
            $users = User::where('role', 'client')->get();
        } else {
            $users = User::where('created_at', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->get();
        }
        return view('run.Customs', compact('users'));
    }
    public function getOfficeContainerData($clientId)
    {
        $users = User::find($clientId);
        return view('run.officeContanierData', compact('users'));
    }
    public function showContainerPost($customId)
    {
        $custom = CustomsDeclaration::find($customId);
        //return response()->json($custom);
        return view('run.addContanier', compact('custom'));
    }

    public function store(Request $request, $clientId)
    {
        $num = $request->statement_number;
        $sub_client = $request->subClient;
        $contNum = $request->contNum;

        $customNumis = CustomsDeclaration::where('statement_number', $num)->first();
        if ($customNumis && !$customNumis->container()->exists()) {
            $customNumis->delete();
        }

        $existingDeclaration = CustomsDeclaration::where('statement_number', $num)
            ->whereYear('created_at', now()->year)
            ->first();
        if ($existingDeclaration) {
            return redirect()->back()->with('error', 'رقم البيان موجود بالفعل لهذا العام.');
        }

        $data = CustomsDeclaration::create([
            'statement_number' => $num,
            'subclient_id' => $sub_client,
            'client_id' => $clientId,
            'created_at' => $request->created_at,
        ]);

        return redirect()->route('showContanierPost', [
            'contNum' => $contNum,
            'customId' => $data->id,
        ])->with('success', 'تم انشاء بيان بنجاح');
    }

    public function deleteOffices($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        if ($user->role === 'client') {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'You do not have permission to delete this user.');
        }
    }
}
