<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\Daily;
use App\Models\PartnerInfo;
use App\Models\SellAndBuy;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PartnerController extends Controller
{
    public function index()
    {
        $partner = User::whereIn('role', ['partner', 'company'])
            ->get();

        $containers = Container::with('daily')->get();

        $treansferPrice = Daily::where('type', 'withdraw')->whereNotNull('container_id')->sum('price');

        $deposit = $containers->sum('price') + $treansferPrice;

        $employees = User::whereIn('role', ['driver', 'administrative'])
            ->whereNotNull('sallary')
            ->with('employeedaily')
            ->get();

        $employeeSum = 0;
        foreach ($employees as $key => $employe) {
            $employeeSum += $employe->employeedaily()
                ->where('type', 'withdraw')
                ->sum('price');
        }


        $rentOffices = User::where('role', 'rent')->with('employeedaily')->get();

        $rentOfficesSum = 0;
        foreach ($rentOffices as $key => $rentOffice) {
            $rentOfficesSum += $rentOffice->employeedaily()
                ->where('type', 'withdraw')
                ->sum('price');
        }

        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشر')) {
                $sum = $user?->employeedaily()
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancherSum += $sum;
            }
        }

        $elbancher = [];
        foreach ($uniqueEmployeeIds as $value) {
            $userElbancher = User::find($value);
            if ($userElbancher && Str::contains($userElbancher->name, 'بنشر')) {
                $sum = $userElbancher->employeedaily()
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancher[] = $userElbancher->employeedaily;
                $elbancher = [...$elbancher];
            }
        }
        $mergedArrayAlbancher = [];
        foreach ($elbancher as $collection) {
            $mergedArrayAlbancher = array_merge($mergedArrayAlbancher, $collection->toArray());
        }

        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();

        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily()
                ->where('type', 'withdraw')
                ->sum('price');
            $othersSum += $sum;
        }

        $cars = Cars::with('daily')->get();
        $carSum = $cars->sum(function ($car) {
            return $car->daily->sum('price');
        });

        $sumCompany = 0;
        foreach ($partner as $value) {
            if ($value->is_active == 1) {
                $sumCompany += $value->partnerInfo?->sum('money');
            }
        }

        $dailyWithdraw = Daily::where('type', 'withdraw')->get();
        $clients = User::where('role', 'client')->with('clientdaily')->get();

        $dipositCash = 0;
        foreach ($clients as $client) {
            $dipositCash += $client->clientdaily
                ->where('type', 'deposit')
                ->sum('price');
        }
        $withdrawCash = $dailyWithdraw->sum('price');

        $allSellAndBuy = SellAndBuy::all();
        $sellTransactions = $allSellAndBuy->where('type', 'sell')->load('parent');

        if ($sellTransactions->isNotEmpty() && $sellTransactions->first()->parent) {
            $sumAllSellTransaction = $sellTransactions
                ->map(function ($item) {
                    $buyPrice = $item->parent->price;
                    return $item->price - $buyPrice;
                })
                ->sum();
        } else {
            $sumAllSellTransaction = 0;
        }

        $withdraw = $carSum + $employeeSum + $rentOfficesSum + $elbancherSum + $othersSum + $treansferPrice;

        $TotalCashMoney = $dipositCash - $withdrawCash;

        return view(
            'Company.partner.partner',
            compact(
                'partner',
                'sumCompany',
                'clients',
                'dailyWithdraw',
                'withdraw',
                'deposit',
                'rentOfficesSum',
                'rentOffices',
                'containers',
                'employees',
                'cars',
                'others',
                'mergedArrayAlbancher',
                'TotalCashMoney',
                'allSellAndBuy',
                'sumAllSellTransaction',
            )
        );
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role,
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('user_images', 'public');
        } else {
            $image = null;
        }
        UserInfo::create([
            'user_id' => $user->id,
            'number_residence' => $request->number_residence,
            'image' => $image,
        ]);
        PartnerInfo::create([
            'partner_id' => $user->id,
            'money' => $request->money,
        ]);
        return redirect()->back()->with('success', 'تم انشاء بيانات بنجاح');
    }

    public function update(Request $request, $userid)
    {
        $user = User::find($userid);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role,
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('user_images', 'public');
        } else {
            $image = null;
        }
        $userInfo = UserInfo::where('user_id', $user->id)->latest();
        $userInfo->update([
            'user_id' => $user->id,
            'number_residence' => $request->number_residence,
            'image' => $image,
        ]);
        $partnerInfo = PartnerInfo::where('partner_id', $user->id)->latest();
        $partnerInfo->update([
            'partner_id' => $user->id,
        ]);
        return redirect()->back()->with('success', 'تم انشاء بيانات بنجاح');
    }
    public function inActive($id)
    {
        $user = User::find($id);
        $user->update([
            'is_active' => $user->is_active == 0 ? 1 : 0,
        ]);
        return redirect()->back()->with('success', 'تم تعديل بيانات بنجاح');

    }
    public function partnerStatement($id)
    {
        $user = User::find($id);
        return view('Company.partner.partnerStatement', compact('user'));
    }
    public function partnerYearStatement($id)
    {
        $user = User::find($id);
        $partners = User::whereIn('role', ['partner', 'company'])
            ->get();

        $container = Container::get();

        $employees = User::whereIn('role', ['driver', 'administrative'])
            ->whereNotNull('sallary')
            ->with('employeedaily')
            ->get();

        $rentOffices = User::where('role', 'rent')->with('employeedaily')->get();


        $uniqueEmployeeIds = Daily::select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $userBancher = User::find($value);
            if (Str::contains($userBancher->name, 'بنشر')) {
                $sum = $userBancher?->employeedaily()
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancherSum += $sum;
            }
        }

        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشر%"')
            ->get();

        $cars = Cars::with('daily')->get();

        $sumCompany = 0;
        foreach ($partners as $value) {
            if ($value->is_active == 1) {
                $sumCompany += $value->partnerInfo?->sum('money');
            }
        }

        $elbancher = [];
        foreach ($uniqueEmployeeIds as $value) {
            $userElbancher = User::find($value);
            if ($userElbancher && Str::contains($userElbancher->name, 'بنشر')) {
                $sum = $userElbancher->employeedaily()
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancher[] = $userElbancher->employeedaily;
                $elbancher = [...$elbancher];
            }
        }
        $mergedArrayAlbancher = [];
        foreach ($elbancher as $collection) {
            $mergedArrayAlbancher = array_merge($mergedArrayAlbancher, $collection->toArray());
        }

        return view(
            'Company.partner.partnerYear',
            compact(
                'user',
                'partners',
                'sumCompany',
                'container',
                'employees',
                'cars',
                'elbancherSum',
                'others',
                'rentOffices',
                'mergedArrayAlbancher',
            )
        );
    }
    public function updateHeadMoney(Request $request)
    {
        $partnerInfo = PartnerInfo::create([
            'partner_id' => $request->id,
            'money' => $request->money,
        ]);

        return redirect()->back()->with('success', 'تم اضافة راس المال بنجاح');
    }
}
