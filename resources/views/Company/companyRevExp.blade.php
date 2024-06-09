@extends('layouts.default')

@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\Daily;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $totalPriceForYear = 0;

        // Sum of employee withdrawals for the current month
        $employeeSum = $employees->sum(function ($employee) use ($currentMonth) {
            return $employee->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month === $currentMonth;
                })
                ->sum('price');
        });

        // Filter containers for the current month
        $containerFilteredCurrentMonth = $container->filter(function ($item) use ($currentMonth) {
            return Carbon::parse($item->created_at)->month == $currentMonth;
        });

        // Calculate total prices for the current month per container
        $totals = $containerFilteredCurrentMonth->mapWithKeys(function ($items) use ($currentMonth) {
            $totalPrice = $items->daily
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month == $currentMonth;
                })
                ->sum('price');
            return [$items->id => $totalPrice];
        });

        $sumContainer = $containerFilteredCurrentMonth->sum('price') + $totals->sum();

        // Calculate total transfer price for the current month
        $totalTransferPriceCurrentMonth = $container->mapWithKeys(function ($items) use ($currentMonth) {
            $totalPrice = $items->daily
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month == $currentMonth;
                })
                ->sum('price');
            return [$items->id => $totalPrice];
        });

        // Sum of rent prices for the current month
        $rent_price = $rentOffices->map(function ($items) use ($currentMonth) {
            return $items->employeedaily
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month == $currentMonth;
                })
                ->where('type', 'withdraw')
                ->sum('price');
        });
        $totalRentPriceFromCurrentMonth = $rent_price->sum();

        // Filter cars for the current month and sum prices
        $carsFiltered = $cars->filter(function ($item) use ($currentMonth) {
            return Carbon::parse($item->created_at)->month == $currentMonth;
        });
        $carSum = $carsFiltered->sum('price');

        // Sum of other expenses for the current month
        $othersSum = $others->sum(function ($other) use ($currentMonth) {
            return $other->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month === $currentMonth;
                })
                ->sum('price');
        });

        // Company price withdraw for the current month
        $companyPriceWithdrawCurrentMonth = $companyPriceWithdraw
            ->employeedaily()
            ->where('type', 'withdraw')
            ->whereYear('created_at', $currentYear)
            ->get()
            ->filter(function ($daily) use ($currentMonth) {
                return Carbon::parse($daily->created_at)->month == $currentMonth;
            })
            ->sum('price');

        // Calculate net sell transactions for the current month
        $sellTransactionCurrentMonth = $sellTransaction
            ->filter(function ($item) use ($currentMonth) {
                return Carbon::parse($item->created_at)->month === $currentMonth && $item->parent()->exists();
            })
            ->map(function ($item) {
                $buyPrice = $item->parent->price; // Corrected to directly access the parent price
                return $item->price - $buyPrice;
            })
            ->sum();

        // Calculate total withdrawals for the current month
        $withdraw = $carSum + $employeeSum + $elbancherSum + $othersSum + $totalRentPriceFromCurrentMonth;
    @endphp

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">كشوف حسابات الواردة</th>
                            <th scope="col">اجمالي الوارد</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold">
                        <tr>
                            <td>
                                <a href="{{ route('getRevenuesClient') }}">
                                    اجمالي كشوف حساب العملاء
                                </a>
                            </td>
                            <td>{{ $sumContainer }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('sell.buy') }}">
                                    اجمالي ارباح حركة البيع وشراء
                                </a>
                            </td>
                            <td>{{ $sellTransactionCurrentMonth }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">كشوف حسابات المصروفات</th>
                            <th scope="col">اجمالي المنصرف</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold">
                        <tr>
                            <td>
                                <a href="{{ route('expensesCarsData') }}">
                                    اجمالي مصروفات السيارات
                                </a>
                            </td>
                            <td>{{ $carSum }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('expensesSallaryeEmployee') }}">
                                    اجمالي المرتبات
                                </a>
                            </td>
                            <td>{{ $employeeSum }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('expensesAlbancherDaily', $companyPriceWithdraw->id) }}">
                                    اجمالي المصروفات النثرية والادارية
                                </a>
                            </td>
                            <td>{{ $companyPriceWithdrawCurrentMonth }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#">
                                    اجمالي اوامر النقل
                                </a>
                            </td>
                            <td>{{ $totalTransferPriceCurrentMonth->sum() }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('expensesSallaryAlbancher') }}">
                                    اجمالي مصروفات البنشري
                                </a>
                            </td>
                            <td>{{ $elbancherSum }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('expensesOthers') }}">
                                    اجمالي مصروفات الكشوف الاخري
                                </a>
                            </td>
                            <td>{{ $othersSum }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('getOfficesRent') }}">
                                    اجمالي كشوف حسابات الايجارات
                                </a>
                            </td>
                            <td>{{ $totalRentPriceFromCurrentMonth }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">الصافي</th>
                    <th scope="col">اجمالي المنصرف</th>
                    <th scope="col">اجمالي الوارد</th>
                    <th scope="col">الشهر</th>
                </tr>
            </thead>
            <tbody class="fw-bold">
                @php
                    $totalSumContainer = 0;
                @endphp
                @for ($month = 1; $month <= 12; $month++)
                    @php
                        $containerFiltered = $container->filter(function ($item) use ($month) {
                            return Carbon::parse($item->created_at)->month == $month;
                        });

                        $rent_price = $rentOffices->map(function ($items) use ($month) {
                            return $items->employeedaily
                                ->filter(function ($daily) use ($month) {
                                    return Carbon::parse($daily->created_at)->month == $month;
                                })
                                ->where('type', 'withdraw')
                                ->sum('price');
                        });
                        $totalRentPriceFromCurrentMonth = $rent_price->sum();

                        $totals = $containerFiltered->mapWithKeys(function ($items) use ($month) {
                            $totalPrice = $items->daily
                                ->filter(function ($daily) use ($month) {
                                    return Carbon::parse($daily->created_at)->month == $month;
                                })
                                ->sum('price');
                            return [$items->id => $totalPrice];
                        });

                        $sumContainer = $containerFiltered->sum('price') + $totals->sum();

                        $customSum = $customs->sum(function ($item) use ($month) {
                            return $item->clientdaily
                                ->where('type', 'deposit')
                                ->filter(function ($daily) use ($month) {
                                    return Carbon::parse($daily->created_at)->month === $month;
                                })
                                ->sum('price');
                        });

                        $carsFiltered = $cars->filter(function ($item) use ($month) {
                            return Carbon::parse($item->created_at)->month == $month;
                        });

                        $employeeSumfromMonth = $employees->sum(function ($employee) use ($month) {
                            return $employee->employeedaily
                                ->filter(function ($daily) use ($month) {
                                    return Carbon::parse($daily->created_at)->month == $month &&
                                        $daily->type == 'withdraw';
                                })
                                ->sum('price');
                        });

                        $elbancherFiltered = collect($mergedArrayAlbancher)
                            ->filter(function ($item) use ($month) {
                                return Carbon::parse($item['created_at'])->month == $month &&
                                    $item['type'] == 'withdraw';
                            })
                            ->sum('price');

                        $otherSumfromMonth = $others
                            ->filter(function ($item) use ($month) {
                                return $item->employeedaily
                                    ->where('type', 'withdraw')
                                    ->filter(function ($daily) use ($month) {
                                        return Carbon::parse($daily->created_at)->month == $month;
                                    })
                                    ->isNotEmpty();
                            })
                            ->sum(function ($item) use ($month) {
                                return $item->employeedaily
                                    ->where('type', 'withdraw')
                                    ->filter(function ($daily) use ($month) {
                                        return Carbon::parse($daily->created_at)->month == $month;
                                    })
                                    ->sum('price');
                            });

                        $totalPriceForMonthCompanyWithdraw = $companyPriceWithdraw->employeedaily
                            ->filter(function ($daily) use ($month) {
                                return $daily->type == 'withdraw' && Carbon::parse($daily->created_at)->month == $month;
                            })
                            ->sum('price');

                        $transferPriceFromMonth = Daily::whereNotNull('container_id')
                            ->whereMonth('created_at', $month)
                            ->where('type', 'withdraw')
                            ->sum('price');

                        $partnerWithdraw = Daily::whereNull('container_id')
                            ->whereMonth('created_at', $month)
                            ->where('type', 'partner_withdraw')
                            ->sum('price');

                        $sellTransactionMonthly = $sellTransaction
                            ->filter(function ($item) use ($month) {
                                return Carbon::parse($item->created_at)->month === $month && $item->parent()->exists();
                            })
                            ->map(function ($item) {
                                $buyPrice = $item->parent->price;
                                return $item->price - $buyPrice;
                            })
                            ->sum();

                        $carSumfromMonth = $carsFiltered->sum('price');
                        $rent_priceSumfromMonth = $containerFiltered->sum('rent_price');

                        $withdrawMonth =
                            $carSumfromMonth +
                            $employeeSumfromMonth +
                            $elbancherFiltered +
                            $totalPriceForMonthCompanyWithdraw +
                            $transferPriceFromMonth +
                            $partnerWithdraw +
                            $totalRentPriceFromCurrentMonth +
                            $otherSumfromMonth;

                        $totalPriceForYear += $withdrawMonth;
                        $totalSumContainer += $sumContainer + $sellTransactionMonthly;
                    @endphp
                    <tr>
                        <td>{{ $sumContainer - $withdrawMonth }}</td>
                        <td>{{ $withdrawMonth }}</td>
                        <td>{{ $sumContainer + $sellTransactionMonthly }}</td>
                        <td>{{ Carbon::create()->month($month)->format('F') }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <table class="table table-striped table-bordered" style="margin-top: 5%">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">صافي ربح الشركة</th>
                    <th scope="col">اجمالي المصروفات</th>
                    <th scope="col">اجمالي الايرادات</th>
                </tr>
            </thead>
            <tbody class="fw-bold">
                <tr>
                    <td>{{ $totalSumContainer - $totalPriceForYear }}</td>
                    <td>{{ $totalPriceForYear }}</td>
                    <td>{{ $totalSumContainer }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@stop
