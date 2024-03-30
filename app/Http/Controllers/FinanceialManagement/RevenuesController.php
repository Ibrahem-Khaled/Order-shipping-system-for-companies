<?php

namespace App\Http\Controllers\FinanceialManagement;

use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class RevenuesController extends Controller
{
    public function index()
    {

        $users = User::where('role', 'client')->get();
        return view('FinancialManagement.Revenues.index', compact('users'));
    }
    public function rent()
    {
        $users = User::where('role', 'rent')->get();
        return view('FinancialManagement.rent.officeRent', compact('users'));
    }

    public function accountStatement(Request $request, $clientId)
    {
        $user = User::find($clientId);
        $query = $request->input('query');

        $container = Container::where('number', 'like', '%' . $query . '%')
            ->first();

        return view('FinancialManagement.Revenues.accountStatement', compact('user', 'container'));
    }
    public function rentMonth($clientId)
    {
        $user = User::find($clientId);
        return view('FinancialManagement.rent.rentMonth', compact('user'));
    }

    public function accountYears($clientId)
    {
        // Get the current month
        $currentYear = Carbon::now()->year;

        // Retrieve records for the current month
        $user = User::find($clientId);

        $daily = $user->clientdaily
            ->filter(function ($item) use ($currentYear) {
                return $item->created_at?->year == $currentYear;
            });
        // Retrieve container records for the current month
        $container = $user->container
            ->filter(function ($item) use ($currentYear) {
                return $item->created_at->year == $currentYear;
            });

        return view('FinancialManagement.Revenues.accountYears', compact('user', 'daily', 'container'));
    }

    public function updateContainerPrice(Request $request)
    {
        $customIds = $request->input('id');
        $prices = $request->input('price');

        $count = count($prices);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $customId = $customIds[$i];
                $price = $prices[$i];
                $custom = Container::where('customs_id', $customId)
                    ->whereIn('status', ['transport', 'done', 'rent'])
                    ->update([
                        'price' => $price,
                    ]);
            }
            return redirect()->back()->with('success', 'تم التحديث بنجاح');
        } else {
            return redirect()->back()->with('error', 'يوجد خطا ما');
        }
    }
    public function updateContainerOnly(Request $request)
    {
        $number = $request->input('number');
        $price = $request->input('price');

        $container = Container::where('number', $number)->first();

        if ($container) {
            $container->update([
                'price' => $price,
            ]);
            return redirect()->back()->with('success', 'Container price updated successfully');
        } else {
            return redirect()->back()->with('error', 'Container not found');
        }
    }
    public function updateRentContainerPrice(Request $request)
    {
        $customIds = $request->input('id');
        $prices = $request->input('rent_price');

        $count = count($prices);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $customId = $customIds[$i];
                $price = $prices[$i];
                // Find the CustomsDeclaration and its associated Container
                $custom = Container::find($customId);
                if ($custom->is_rent == 1) {
                    $custom->update([
                        'rent_price' => $price,
                    ]);
                }
            }
            return redirect()->back()->with('success', 'تم التحديث بنجاح');
        } else {
            return redirect()->back()->with('error', 'يوجد خطا ما');
        }
    }

    public function priceContainerEdit(Request $request, $id)
    {
        $container = Container::find($id);
        $container->update([
            'price' => $request->price
        ]);

        return redirect()->back()->with('success', 'done');
    }
}
