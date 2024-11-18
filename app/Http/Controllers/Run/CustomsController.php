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
            $users = User::where('role', 'client')->where('is_active', 1)->get();
            $usersDeleted = User::where('role', 'client')->where('is_active', 0)->get();
        } else {
            $users = User::where('created_at', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->get();
        }
        return view('run.Customs', compact('users', 'usersDeleted'));
    }
    public function getOfficeContainerData($clientId)
    {
        $users = User::find($clientId);
        return view('run.officeContanierData', compact('users'));
    }
    public function showContainerPost($customId)
    {
        $custom = CustomsDeclaration::find($customId);
        return view('run.addContanier', compact('custom'));
    }

    public function store(Request $request, $clientId)
    {
        $statement_number = $request->statement_number;
        $sub_client = $request->subClient;
        $customs_weight = $request->customs_weight;
        $contNum = $request->contNum;

        $customNumis = CustomsDeclaration::where('statement_number', $statement_number)->first();
        if ($customNumis && !$customNumis->container()->exists()) {
            $customNumis->delete();
        }

        $existingDeclaration = CustomsDeclaration::where('statement_number', $statement_number)
            ->whereYear('created_at', now()->year)
            ->first();
        if ($existingDeclaration) {
            return redirect()->back()->with('error', 'رقم البيان موجود بالفعل لهذا العام.');
        }

        $data = CustomsDeclaration::create([
            'statement_number' => $statement_number,
            'subclient_id' => $sub_client,
            'client_id' => $clientId,
            'customs_weight' => $customs_weight,
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
            $user->update(['is_active' => $user->is_active ? 0 : 1]);
            return redirect()->back()->with('success', 'User deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'You do not have permission to delete this user.');
        }
    }

    public function showContainer($customId)
    {
        $custom = CustomsDeclaration::findOrFail($customId);
        return view('FinancialManagement.Revenues.custom-with-container', compact('custom'));
    }

}
