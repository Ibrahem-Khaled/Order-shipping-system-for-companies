@extends('layouts.default')

@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\Daily;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $totalPriceForYear = 0;

        $employeeSum = 0;
        foreach ($employees as $employee) {
            $employeeSum += $employee->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month === $currentMonth;
                })
                ->sum('price');
        }

        $containerFilteredCurrentMonth = $container->filter(function ($item) use ($currentMonth) {
            return Carbon::parse($item->created_at)->month == $currentMonth;
        });

        $totals = [];
        foreach ($containerFilteredCurrentMonth as $items) {
            $filteredDaily = $items->daily->filter(function ($item) use ($currentMonth) {
                return Carbon::parse($item->created_at)->month;
            });
            $totalPrice = $filteredDaily->sum('price');
            $totals[$items->id] = $totalPrice;
        }
        $sumContainer = $containerFilteredCurrentMonth->sum('price') + array_sum($totals);

        $totalTransferPriceCurrentMonth = [];
        foreach ($container as $items) {
            $filteredDaily = $items->daily->filter(function ($item) use ($currentMonth) {
                return Carbon::parse($item->created_at)->month == $currentMonth;
            });
            $totalPrice = $filteredDaily->sum('price');
            $totalTransferPriceCurrentMonth[$items->id] = $totalPrice;
        }

        $rent_price = $containerFilteredCurrentMonth->sum('rent_price');

        $carsFiltered = $cars->filter(function ($item) use ($currentMonth) {
            return Carbon::parse($item->created_at)->month == $currentMonth;
        });

        $carSum = $carsFiltered->sum('price');

        $totalYear = [];
        foreach ($container as $items) {
            $filteredDaily = $items->daily->sum('price');
            $totalYear[$items->id] = $filteredDaily;
        }

        $sumYearContainer = $container->sum('price') + array_sum($totalYear);

        $othersSum = 0;
        foreach ($others as $other) {
            $othersSum += $other->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month === $currentMonth;
                })
                ->sum('price');
        }

        $companyPriceWithdrawCurrentMonth = $companyPriceWithdraw
            ->employeedaily()
            ->where('type', 'withdraw')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('price');

        $withdraw = $carSum + $employeeSum + $elbancherSum + $othersSum + $rent_price;

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
                            <td>{{ array_sum($totalTransferPriceCurrentMonth) }}</td>
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
                            <td>{{ $rent_price }}</td>
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
                @for ($month = 1; $month <= 12; $month++)
                    @php

                        $containerFiltered = $container->filter(function ($item) use ($month) {
                            return \Carbon\Carbon::parse($item->created_at)->month == $month;
                        });

                        $totals = [];
                        foreach ($containerFiltered as $items) {
                            $filteredDaily = $items->daily->filter(function ($item) use ($month) {
                                return Carbon::parse($item->created_at)->month;
                            });
                            $totalPrice = $filteredDaily->sum('price');
                            $totals[$items->id] = $totalPrice;
                        }

                        $sumContainer = $containerFiltered->sum('price') + array_sum($totals);

                        $customSum = $customs->sum(function ($item) use ($month) {
                            return $item->clientdaily
                                ->where('type', 'deposit')
                                ->filter(function ($daily) use ($month) {
                                    return \Carbon\Carbon::parse($daily->created_at)->month === $month;
                                })
                                ->sum('price');
                        });

                        $carsFiltered = $cars->filter(function ($item) use ($month) {
                            return \Carbon\Carbon::parse($item->created_at)->month == $month;
                        });

                        $employeeSumfromMonth = $employees->sum(function ($employee) use ($month) {
                            return $employee->employeedaily
                                ->filter(function ($daily) use ($month) {
                                    return \Carbon\Carbon::parse($daily->created_at)->month == $month &&
                                        $daily->type == 'withdraw';
                                })
                                ->sum('price');
                        });

                        $elbancherFiltered = collect($mergedArrayAlbancher)
                            ->filter(function ($item) use ($month) {
                                return \Carbon\Carbon::parse($item['created_at'])->month == $month &&
                                    $item['type'] == 'withdraw';
                            })
                            ->sum('price');

                        $otherSumfromMonth = $others
                            ->filter(function ($item) use ($month) {
                                return $item->employeedaily
                                    ->where('type', 'withdraw')
                                    ->filter(function ($daily) use ($month) {
                                        return \Carbon\Carbon::parse($daily->created_at)->month == $month;
                                    })
                                    ->isNotEmpty();
                            })
                            ->sum(function ($item) use ($month) {
                                return $item->employeedaily
                                    ->where('type', 'withdraw')
                                    ->filter(function ($daily) use ($month) {
                                        return \Carbon\Carbon::parse($daily->created_at)->month == $month;
                                    })
                                    ->sum('price');
                            });

                        $totalPriceForMonthCompanyWithdraw = 0;
                        if ($companyPriceWithdraw) {
                            foreach ($companyPriceWithdraw->employeedaily as $daily) {
                                if ($daily->type == 'withdraw' && Carbon::parse($daily->created_at)->month == $month) {
                                    $totalPriceForMonthCompanyWithdraw += $daily->price;
                                }
                            }
                        }

                        $transferPriceFromMonth = Daily::whereNotNull('container_id')
                            ->whereMonth('created_at', $month)
                            ->where('type', 'withdraw')
                            ->sum('price');

                        $partnerWithdraw = Daily::whereNull('container_id')
                            ->whereMonth('created_at', $month)
                            ->where('type', 'partner_withdraw')
                            ->sum('price');

                        $carSumfromMonth = $carsFiltered->sum('price');
                        $rent_priceSumfromMonth = $containerFiltered->sum('rent_price');

                        $withdrawMonth =
                            $carSumfromMonth +
                            $rent_priceSumfromMonth +
                            $employeeSumfromMonth +
                            $elbancherFiltered +
                            $totalPriceForMonthCompanyWithdraw +
                            $transferPriceFromMonth +
                            $partnerWithdraw +
                            $otherSumfromMonth;

                        $totalPriceForYear += $withdrawMonth;
                    @endphp
                    <tr>
                        <td>{{ $sumContainer - $withdrawMonth }}</td>
                        <td>{{ $withdrawMonth }}</td>
                        <td>{{ $sumContainer }}</td>
                        <td>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</td>
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
                    <td>{{ strval($sumYearContainer) - strval($totalPriceForYear) }}</td>
                    <td>{{ strval($totalPriceForYear) }}</td>
                    <td>{{ strval($sumYearContainer) }}</td>
                </tr>
            </tbody>
        </table>

    </div>



@stop
