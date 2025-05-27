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
        // جلب جميع العملاء مع الحاويات والإيرادات
        $users = User::with(['container.daily', 'clientdaily'])
            ->where('role', 'client')
            ->get();

        // حساب عدد الحاويات الشهرية لكل مستخدم
        $containersCount = $users->mapWithKeys(function ($user) {
            return [$user->id => $user->monthlyContainers()->count()];
        });

        // حساب الإيرادات المتبقية لكل مستخدم
        $users->each(function ($user) {
            $user->remaining_revenue = $user->remainingRevenue();
        });

        // حساب الإجماليات
        $totalContainers = $containersCount->sum();
        $totalRemainingRevenue = $users->sum('remaining_revenue');

        return view('FinancialManagement.Revenues.index', compact(
            'users',
            'containersCount',
            'totalContainers',
            'totalRemainingRevenue'
        ));
    }
    public function rent()
    {
        $users = User::where('role', 'rent')->get();
        return view('FinancialManagement.rent.officeRent', compact('users'));
    }

    public function accountStatement(Request $request, $clientId)
    {
        $user = User::with(['customs.container.daily'])->findOrFail($clientId);
        $query = $request->input('query');
        $date = $query ? Carbon::createFromFormat('Y-m', $query) : Carbon::now();
        $monthName = \Carbon\Carbon::parse($request->query('query'))->translatedFormat('Y F');

        $customs = $user->customs()
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->get();

        $container = Container::where('number', 'like', '%' . $query . '%')->first();

        return view('FinancialManagement.Revenues.accountStatement', compact('user', 'container', 'customs', 'monthName'));
    }
    public function rentMonth(Request $request, $clientId)
    {
        $user = User::find($clientId);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $query = $request->input('query');
        $date = $query ? Carbon::createFromFormat('Y-m', $query) : Carbon::now();

        $currentMonth = $date->month;
        $currentYear = $date->year;

        $rentData = $user->rentCont()
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->whereIn('status', ['transport', 'done'])
            ->get();

        if ($rentData->isEmpty()) {
            $rentData = collect();
        }

        return view('FinancialManagement.rent.rentMonth', compact('user', 'rentData'));
    }


    public function accountYears($clientId)
    {
        $currentYear = Carbon::now()->year;

        $user = User::find($clientId);

        $daily = $user->clientdaily
            ->filter(function ($item) use ($currentYear) {
                return $item->created_at?->year == $currentYear;
            })->sortByDesc('created_at');

        $container = $user->container
            ->filter(function ($item) use ($currentYear) {
                return $item->created_at->year == $currentYear;
            });

        return view('FinancialManagement.Revenues.accountYears', compact('user', 'daily', 'container'));
    }

    public function updateContainerPrice(Request $request)
    {
        // قراءة القيم المرسلة من النموذج: كل custom_id يحتوي على مجموعة أسعار جديدة
        $groupedPrices = $request->input('price_grouped');

        // التحقق من صحة البيانات: كل حقل price يجب أن يكون رقمًا موجبًا
        $request->validate([
            'price_grouped' => 'required|array',
            'price_grouped.*' => 'required|array',
            'price_grouped.*.*' => 'numeric|min:0',
        ]);

        // نمر على كل بيان (custom_id)
        foreach ($groupedPrices as $customId => $newPrices) {

            // نحصل على جميع الحاويات التابعة لهذا البيان والتي حالتها نشطة
            $containers = Container::where('customs_id', $customId)
                ->whereIn('status', ['transport', 'done', 'rent', 'wait'])
                ->get();

            // نجمع الحاويات حسب السعر الحالي (قبل التعديل)
            $groupedByOriginalPrice = $containers->groupBy('price')->values();

            // الآن نطابق كل مجموعة من الحاويات بسعرها الجديد (بنفس الترتيب)
            foreach ($newPrices as $index => $newPrice) {
                // التأكد من وجود مجموعة مقابلة لهذا السعر
                if (isset($groupedByOriginalPrice[$index])) {
                    $group = $groupedByOriginalPrice[$index];

                    // تحديث كل الحاويات في هذه المجموعة إلى السعر الجديد
                    foreach ($group as $container) {
                        $container->price = $newPrice;
                        $container->save();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'تم تحديث أسعار الحاويات بنجاح');
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
