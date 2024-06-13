<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\CustomsDeclaration;
use App\Models\Daily;
use App\Models\SellAndBuy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;


class CompanyController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $container = Container::all();
        $employee = User::whereIn('role', ['driver', 'administrative'])->whereNotNull('sallary')->get();
        $employeeSum = 0;
        foreach ($employee as $key => $employe) {
            $employeeSum += $employe->employeedaily->where('type', 'withdraw')->sum('price');
        }
        $employeeTips = Container::whereNotNull('driver_id')
            ->get();


        $rentOffecis = User::where('role', 'rent')->get();
        $totalPriceFromRent = 0;
        foreach ($rentOffecis as $key => $value) {
            $totalPriceFromRent += $value->employeedaily->where('type', 'withdraw')->sum('price');
        }

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشر')) {
                $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                $elbancherSum = $elbancherSum + $sum;
            }
        }

        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();
        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
            $othersSum = $othersSum + $sum;
        }

        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->sum('price');

        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();

        $clients = User::all();

        $depositCash = 0;
        $withdrawCash = Daily::whereIn('type', ['withdraw', 'partner_withdraw']);

        foreach ($clients as $client) {
            $depositCash += $client?->clientdaily->where('type', 'deposit')->sum('price');
        }

        $containerTransport = Container::whereIn('status', ['transport', 'done'])->get();
        $containerTransferPrice = 0;

        foreach ($containerTransport as $container) {
            $containerTransferPrice += $container->daily()->whereNotNull('container_id')->sum('price');
        }

        $clintPriceMinesContainer = $containerTransport->sum('price') + $containerTransferPrice - $depositCash;



        $dailyData = Daily::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $UserData = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $CustomsDeclarationData = CustomsDeclaration::with('container')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $containerData = Container::with('customs', 'driver', 'car')
            ->where('status', 'transport')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $dailyDataArray = $dailyData->toArray();
        $CustomsDeclarationDataArray = $CustomsDeclarationData->toArray();
        $userDataArray = $UserData->toArray();
        $containerDataArray = $containerData->toArray();

        $notifications = array_merge($dailyDataArray, $CustomsDeclarationDataArray, $userDataArray, $containerDataArray);
        usort($notifications, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });


        $carData = Cars::with([
            'driver',
            'container' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereIn('status', ['transport', 'done'])->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            },
            'daily' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('type', 'withdraw')->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }
        ])->get();

        $buyTransactions = SellAndBuy::where('type', 'buy')->get();
        $sellTransactions = SellAndBuy::where('type', 'sell')->get();
        $sellFromHeadMony = SellAndBuy::where('type', 'sell_from_head_mony')->get();
        $partnerWithdraw = Daily::where('type', 'partner_withdraw')->get();

        $sellTransaction = SellAndBuy::where('type', 'sell')->get();
        $sellTransactionSum = $sellTransaction
            ->filter(function ($item) {
                return $item->parent()->exists();
            })
            ->map(function ($item) {
                $buyPrice = $item->parent->price;
                return $item->price - $buyPrice;
            })
            ->sum();

        $Profits_from_buying_and_selling = $buyTransactions->sum('price') - $sellTransactions->sum('price');

        $canCashWithdraw =
            $depositCash -
            $withdrawCash->sum('price') -
            $sellFromHeadMony->sum('price') -
            $Profits_from_buying_and_selling;


        $transferPrice = Daily::where('type', 'withdraw')->whereNotNull('container_id')->sum('price');

        $deposits = $container->sum('price') + $transferPrice + $sellTransactionSum;

        $withdraws = $cars + $employeeSum + $totalPriceFromRent + $elbancherSum + $othersSum + $partnerWithdraw->sum('price') + $transferPrice;

        $allCustoms = User::where('role', 'client')->with('container')->get();

        return view(
            'Company.index',
            compact(
                'container',
                'notifications',
                'daily',
                'carData',
                'clintPriceMinesContainer',
                'canCashWithdraw',
                'withdraws',
                'deposits',
                'allCustoms'
            )
        );
    }
    public function companyDetailes()
    {
        $partner = User::where('role', 'partner')->get();

        $container = Container::all();
        $employee = User::whereIn('role', ['driver', 'administrative'])->whereNotNull('sallary')->get();
        $employeeSum = 0;
        foreach ($employee as $key => $employe) {
            $employeeSum += $employe->employeedaily->where('type', 'withdraw')->sum('price');
        }

        $rentOffecis = User::where('role', 'rent')->get();
        $totalPriceFromRent = 0;
        foreach ($rentOffecis as $key => $value) {
            $totalPriceFromRent += $value->employeedaily->where('type', 'withdraw')->sum('price');
        }


        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشر')) {
                $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                $elbancherSum = $elbancherSum + $sum;
            }
        }
        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();
        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
            $othersSum = $othersSum + $sum;
        }

        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();
        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();

        $partnerWithdraw = Daily::where('type', 'partner_withdraw')->get();
        $sellTransaction = SellAndBuy::where('type', 'sell')->get();
        $sellTransactionSum = $sellTransaction
            ->filter(function ($item) {
                return $item->parent()->exists();
            })
            ->map(function ($item) {
                $buyPrice = $item->parent->price;
                return $item->price - $buyPrice;
            })
            ->sum();

        $transferPrice = Daily::where('type', 'withdraw')->whereNotNull('container_id')->sum('price');

        $deposit = $container->sum('price') + $transferPrice + $sellTransactionSum;

        $withdraw = $cars->sum('price') +
            $totalPriceFromRent +
            $employeeSum +
            $elbancherSum +
            $othersSum +
            $transferPrice +
            $partnerWithdraw->sum('price');

        return view(
            'Company.companyDetailes',
            compact(
                'container',
                'daily',
                'withdraw',
                'partner',
                'deposit',
            )
        );
    }
    public function companyRevExp()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $companyPriceWithdraw = User::where('role', 'company')->with('employeedaily')
            ->first();


        $rentOffices = User::where('role', 'rent')->with('employeedaily')->get();

        //return response()->json($rentOfices);

        $transferPrice = Daily::whereNotNull('container_id')->where('type', 'withdraw');

        $employees = User::whereIn('role', ['driver', 'administrative'])
            ->whereNotNull('sallary')
            ->with('employeedaily')
            ->get();

        $customs = User::where('role', 'client')->get();

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        $elbancher = [];

        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if ($user && Str::contains($user->name, 'بنشر')) {
                $sum = $user->employeedaily()
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancherSum += $sum;
                $elbancher[] = $user->employeedaily;
                $elbancher = [...$elbancher];
            }
        }
        $mergedArrayAlbancher = [];
        foreach ($elbancher as $collection) {
            $mergedArrayAlbancher = array_merge($mergedArrayAlbancher, $collection->toArray());
        }

        //return response()->json($mergedArrayAlbancher);

        $others = User::whereIn('role', ['driver'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();

        $container = Container::with('daily')->get();

        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();

        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();

        $partnerWithdraw = Daily::where('type', 'partner_withdraw')->get();

        $sellTransaction = SellAndBuy::where('type', 'sell')->get();
        return view(
            'Company.companyRevExp',
            compact(
                'container',
                'mergedArrayAlbancher',
                'employees',
                'daily',
                'cars',
                'elbancherSum',
                'rentOffices',
                'others',
                'customs',
                'companyPriceWithdraw',
                'transferPrice',
                'sellTransaction',
                'partnerWithdraw'
            )
        );
    }
}
