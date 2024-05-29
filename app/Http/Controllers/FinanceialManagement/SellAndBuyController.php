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
        $sellFromHeadMony = SellAndBuy::where('type', 'sell_from_head_mony')->get();

        $deposit = Daily::where('type', 'deposit')->get();
        $withdraw = Daily::where('type', 'withdraw')->get();
        $partnerWithdraw = Daily::where('type', 'partner_withdraw')->get();

        $Profits_from_buying_and_selling = $buyTransactions->sum('price') - $sellTransactions->sum('price');
        $canCashWithdraw =
            $deposit->sum('price') -
            $withdraw->sum('price') -
            $partnerWithdraw->sum('price') +
            $sellFromHeadMony->sum('price') -
            $Profits_from_buying_and_selling;

        return view('FinancialManagement.Revenues.SellAndBuy', compact('transactions', 'buyTransactions', 'sellTransactions', 'canCashWithdraw'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:sell,buy,sell_from_head_mony',
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
