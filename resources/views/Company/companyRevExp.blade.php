@extends('layouts.default')

@section('content')
    @php
        use Carbon\Carbon;
        $currentMonth = Carbon::now()->month;
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

        $containerFiltered = $container->filter(function ($item) use ($currentMonth) {
            return Carbon::parse($item->created_at)->month == $currentMonth;
        });

        $depositFromMonth = $containerFiltered->sum('price');
        $rent_price = $containerFiltered->sum('rent_price');

        $carsFiltered = $cars->filter(function ($item) use ($currentMonth) {
            return Carbon::parse($item->created_at)->month == $currentMonth;
        });
        $carSum = $carsFiltered->sum('price');

        $sumYearContainer = $container->sum('price');

        $othersSum = 0;
        foreach ($others as $other) {
            $othersSum += $other->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($currentMonth) {
                    return Carbon::parse($daily->created_at)->month === $currentMonth;
                })
                ->sum('price');
        }

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
                            <td>{{ $depositFromMonth }}</td>
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
                                <a href="{{ route('expensesSallaryeEmployee') }}">
                                    كشوف نثرية وادارية
                                </a>
                            </td>
                            <td>{{ number_format($companyPriceWithdraw, 2) }}</td>
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

                        $customSum = $customs->sum(function ($item) use ($month) {
                            return $item->clientdaily
                                ->where('type', 'deposit')
                                ->filter(function ($daily) use ($month) {
                                    return Carbon::parse($daily->created_at)->month === $month;
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

                        $elbancherFiltered = collect($mergedArrayAlbancher)->filter(function ($item) use ($month) {
                            return \Carbon\Carbon::parse($item['created_at'])->month == $month &&
                                $item['type'] == 'withdraw';
                        });

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

                        $carSumfromMonth = $carsFiltered->sum('price');
                        $sumContainer = $containerFiltered->sum('price');
                        $rent_priceSumfromMonth = $containerFiltered->sum('rent_price');
                        $elbancherSumfromMonth = $elbancherFiltered->sum('price');

                        $withdrawMonth =
                            $carSumfromMonth +
                            $rent_priceSumfromMonth +
                            $employeeSumfromMonth +
                            $elbancherSumfromMonth +
                            $otherSumfromMonth;

                        $totalPriceForYear += $withdrawMonth;

                    @endphp
                    <tr>
                        <td>{{ $sumContainer - $withdrawMonth }}</td>
                        <td>{{ $withdrawMonth }}</td>
                        <td>
                            {{ $sumContainer }}
                        </td>
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
