<?php

namespace App\Http\Controllers\FinanceialManagement;

use App\Http\Controllers\Controller;
use App\Models\Daily;
use Illuminate\Http\Request;
use App\Models\SellAndBuy;

class SellAndBuyController extends Controller
{
    public function index()
    {
        $transactions = SellAndBuy::all();
        $buyTransactions = SellAndBuy::where('type', 'buy')->get();
        $sellTransactions = SellAndBuy::where('type', 'sell')->get();
        $deposit = Daily::where('type', 'deposit')->get();
        $withdraw = Daily::where('type', 'withdraw')->get();
        $partnerWithdraw = Daily::where('type', 'partner_withdraw')->get();

        $canCashWithdraw = $deposit->sum('price') - $withdraw->sum('price') - $partnerWithdraw->sum('price');

        return view('FinancialManagement.Revenues.SellAndBuy', compact('transactions', 'buyTransactions', 'sellTransactions', 'canCashWithdraw'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:sell,buy',
            'parent_id' => 'nullable|',
            'price' => 'required|numeric|min:0',
        ]);

        // Create a new transaction
        $transaction = new SellAndBuy();
        $transaction->title = $validatedData['title'];
        $transaction->type = $validatedData['type'];
        $transaction->price = $validatedData['price'];

        // Set parent_id only if the transaction type is 'sell'
        if ($validatedData['type'] == 'sell' && isset($validatedData['parent_id'])) {
            $transaction->parent_id = $validatedData['parent_id'];
        }

        $transaction->save();

        return redirect()->back()->with('success', 'Transaction added successfully.');
    }
    public function update(Request $request, $transactionId)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $transaction = SellAndBuy::findOrFail($transactionId);

        $transaction->title = $validatedData['title'];
        $transaction->price = $validatedData['price'];

        $transaction->save();

        return redirect()->back()->with('success', 'Transaction updated successfully.');
    }
    public function destroy(Request $request, $transactionId)
    {
        $transaction = SellAndBuy::find($transactionId);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }
        $transaction->delete();
        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }


}
