<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Daily;
use App\Models\PartnerInfo;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PartnerController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $partner = User::whereIn('role', ['partner', 'company'])
            ->get();

        $container = Container::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();


        $employee = User::whereIn('role', ['driver', 'administrative'])->get();
        $employeeSum = 0;
        foreach ($employee as $key => $employe) {
            $employeeSum += $employe->employeedaily()
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('type', 'withdraw')
                ->sum('price');
        }

        $uniqueEmployeeIds = Daily::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->select('employee_id')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        $elbancherSum = 0;
        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if (Str::contains($user->name, 'بنشري')) {
                $sum = $user?->employeedaily()
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->where('type', 'withdraw')
                    ->sum('price');
                $elbancherSum = $elbancherSum + $sum;
            }
        }
        $others = User::whereIn('role', ['driver', 'company'])
            ->Where(function ($query) {
                $query->whereNull('sallary');
            })->whereRaw('name NOT LIKE "%بنشري%"')
            ->get();
        $othersSum = 0;
        foreach ($others as $other) {
            $user = User::find($other->id);
            $sum = $user?->employeedaily()
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('type', 'withdraw')
                ->sum('price');
            $othersSum = $othersSum + $sum;
        }

        $cars = Daily::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->get();
        $daily = Daily::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();

        $sums = 0;
        foreach ($partner as $value) {
            if ($value->is_active == 1) {
                $sums += $value->partnerInfo?->sum('money') -
                    $value->partnerdaily->where('type', 'withdraw')->sum('price') +
                    $value->partnerdaily->where('type', 'deposit')->sum('price');
            }
        }
        return view(
            'Company.partner.partner',
            compact(
                'partner',
                'sums',
                'container',
                'employeeSum',
                'daily',
                'cars',
                'elbancherSum',
                'othersSum',
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
            'money' => $request->money,
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
    public function updateHeadMoney(Request $request)
    {
        $partnerInfo = PartnerInfo::create([
            'partner_id' => $request->id,
            'money' => $request->money,
        ]);

        return redirect()->back()->with('success', 'تم اضافة راس المال بنجاح');
    }
}
