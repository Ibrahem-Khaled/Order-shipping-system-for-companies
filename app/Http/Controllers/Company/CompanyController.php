<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Cars;
use App\Models\Container;
use App\Models\Daily;
use App\Models\Flatbed;
use App\Models\SellAndBuy;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;


class CompanyController extends Controller
{
    public function index()
    {
        // 1. تحديد بداية ونهاية الشهر الحالي
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // 2. جلب جميع الحاويات
        $container = Container::all();

        // 3. مجموع سحوبات الموظفين ذوي الرواتب (driver, administrative)
        $employeeSum = Daily::whereIn('employee_id', function ($q) {
            $q->select('id')
                ->from('users')
                ->whereIn('role', ['driver', 'administrative'])
                ->whereNotNull('sallary');
        })
            ->where('type', 'withdraw')
            ->sum('price'); // تنفذ SUM في SQL :contentReference[oaicite:0]{index=0}

        // 4. بقشيش السائقين (مجرد جلب دون تجميع)
        $employeeTips = Container::whereNotNull('driver_id')->get();

        // 5. مجموع سحوبات مكاتب الإيجار (role = rent)
        $totalPriceFromRent = Daily::whereIn('employee_id', User::where('role', 'rent')->pluck('id'))
            ->where('type', 'withdraw')
            ->sum('price'); // تنفذ SUM في SQL :contentReference[oaicite:1]{index=1}

        // 6. مجموع سحوبات من أسمائهم تحوي "بنشر"
        $elbancherSum = Daily::whereIn('employee_id', function ($q) {
            $q->select('id')
                ->from('users')
                ->where('name', 'like', '%بنشر%');
        })
            ->where('type', 'withdraw')
            ->sum('price');

        // 7. مجموع سحوبات الباقي (drivers & company بدون راتب وبغير "بنشر")
        $othersSum = Daily::whereIn('employee_id', function ($q) {
            $q->select('id')
                ->from('users')
                ->whereIn('role', ['driver', 'company'])
                ->whereNull('sallary')
                ->where('name', 'not like', '%بنشر%');
        })
            ->where('type', 'withdraw')
            ->sum('price');

        // 8. مجموع سحوبات السيارات
        $cars = Daily::whereNotNull('car_id')
            ->where('type', 'withdraw')
            ->sum('price');

        // 9. مصفوفة الحركات الخاصة بالعملاء للإيداع
        $daily = Daily::whereNotNull('client_id')
            ->where('type', 'deposit')
            ->get();

        // 10. مجموع إيداعات العملاء (depositCash)
        //    يعادل: SUM على جميع سجلات Daily من نوع deposit
        $depositCash = Daily::where('type', 'deposit')
            ->whereNotNull('client_id')
            ->sum('price');

        // 11. حساب نقل الحاويات
        $containerTransport    = Container::all();
        $containerTransferPrice = Daily::whereNotNull('container_id')
            ->sum('price');

        // 12. باقي حساب العملاء بعد الخصم
        $clintPriceMinesContainer = $containerTransport->sum('price')
            + $containerTransferPrice
            - $depositCash;

        // 13. بيانات السيارات مع التحميل المسبق للعلاقات لتفادي N+1
        $carData = Cars::with([
            'driver',
            'container' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereIn('status', ['transport', 'done'])
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            },
            'daily' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where('type', 'withdraw')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            },
        ])->get(); // Eager Loading يقلل عدد الاستعلامات :contentReference[oaicite:2]{index=2}

        // 14. مجموع عمليات الشراء والبيع والسحب للشركاء
        $buySum             = SellAndBuy::where('type', 'buy')->sum('price');
        $sellSum            = SellAndBuy::where('type', 'sell')->sum('price');
        $sellFromHeadMony   = SellAndBuy::where('type', 'sell_from_head_mony')->sum('price');
        $partnerWithdrawSum = Daily::where('type', 'partner_withdraw')->sum('price');

        // 15. أرباح الفرق بين الشراء والبيع
        $Profits_from_buying_and_selling = $buySum - $sellSum;

        // 16. النقد المتاح للسحب
        $canCashWithdraw = $depositCash
            - $partnerWithdrawSum
            - $sellFromHeadMony
            - $Profits_from_buying_and_selling;

        // 17. مجموع التحويلات (نفس containerTransferPrice)
        $transferPrice = $containerTransferPrice;

        // 18. إجمالي الإيداعات
        $deposits = $container->sum('price')
            + $transferPrice
            + $sellSum;

        // 19. إجمالي السحوبات
        $withdraws = $cars
            + $employeeSum
            + $totalPriceFromRent
            + $elbancherSum
            + $othersSum
            + $partnerWithdrawSum
            + $transferPrice
            + $buySum;

        // 20. بيانات العملاء والحاويات الخاصة بهم، والسطحات
        $allCustoms = User::where('role', 'client')->with('container')->get();
        $flatbeds   = Flatbed::all();

        // عرض الـ View بنفس المتغيرات
        return view('Company.index', compact(
            'container',
            'daily',
            'carData',
            'clintPriceMinesContainer',
            'canCashWithdraw',
            'withdraws',
            'deposits',
            'allCustoms',
            'flatbeds'
        ));
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
        $buyTransactions = SellAndBuy::where('type', 'buy')->get();
        $sellTransactions = SellAndBuy::where('type', 'sell')->get();

        $transferPrice = Daily::where('type', 'withdraw')->whereNotNull('container_id')->sum('price');

        $deposit = $container->sum('price') + $transferPrice + $sellTransactions->sum('price');

        $withdraw = $cars->sum('price') +
            $totalPriceFromRent +
            $employeeSum +
            $elbancherSum +
            $othersSum +
            $transferPrice +
            $partnerWithdraw->sum('price') +
            $buyTransactions->sum('price');

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

        $elbancher = [];

        foreach ($uniqueEmployeeIds as $value) {
            $user = User::find($value);
            if ($user && Str::contains($user->name, 'بنشر')) {
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

        $sellAndBuyTransactions = SellAndBuy::get();
        return view(
            'Company.companyRevExp',
            compact(
                'container',
                'mergedArrayAlbancher',
                'employees',
                'daily',
                'cars',
                'rentOffices',
                'others',
                'customs',
                'companyPriceWithdraw',
                'transferPrice',
                'sellAndBuyTransactions',
                'partnerWithdraw'
            )
        );
    }
}
