@extends('layouts.default')

@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\Daily;

        $currentYear = Carbon::now()->year;
        $searchYear = request('query', $currentYear); // Get the search year from the request, default to current year
        $totalPriceForYear = 0;

        // Sum of employee withdrawals for the search year
        $employeeSum = $employees->sum(function ($employee) use ($searchYear) {
            return $employee->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($searchYear) {
                    return Carbon::parse($daily->created_at)->year == $searchYear;
                })
                ->sum('price');
        });

        // Filter containers for the search year
        $containerFilteredYear = $container->filter(function ($item) use ($searchYear) {
            return Carbon::parse($item->created_at)->year == $searchYear;
        });

        // Calculate total prices for the search year per container
        $totals = $containerFilteredYear->mapWithKeys(function ($items) use ($searchYear) {
            $totalPrice = $items->daily
                ->filter(function ($daily) use ($searchYear) {
                    return Carbon::parse($daily->created_at)->year == $searchYear;
                })
                ->sum('price');
            return [$items->id => $totalPrice];
        });

        $sumContainer = $containerFilteredYear->sum('price') + $totals->sum();

        // Calculate total transfer price for the search year
        $totalTransferPriceYear = $container->mapWithKeys(function ($items) use ($searchYear) {
            $totalPrice = $items->daily
                ->filter(function ($daily) use ($searchYear) {
                    return Carbon::parse($daily->created_at)->year == $searchYear;
                })
                ->sum('price');
            return [$items->id => $totalPrice];
        });

        // Sum of rent prices for the search year
        $rent_price = $rentOffices->map(function ($items) use ($searchYear) {
            return $items->employeedaily
                ->filter(function ($daily) use ($searchYear) {
                    return Carbon::parse($daily->created_at)->year == $searchYear;
                })
                ->where('type', 'withdraw')
                ->sum('price');
        });
        $totalRentPriceFromYear = $rent_price->sum();

        // Filter cars for the search year and sum prices
        $carsFiltered = $cars->filter(function ($item) use ($searchYear) {
            return Carbon::parse($item->created_at)->year == $searchYear;
        });
        $carSum = $carsFiltered->sum('price');

        // Sum of other expenses for the search year
        $othersSum = $others->sum(function ($other) use ($searchYear) {
            return $other->employeedaily
                ->where('type', 'withdraw')
                ->filter(function ($daily) use ($searchYear) {
                    return Carbon::parse($daily->created_at)->year == $searchYear;
                })
                ->sum('price');
        });

        // Company price withdraw for the search year
        $companyPriceWithdrawYear = $companyPriceWithdraw
            ->employeedaily()
            ->where('type', 'withdraw')
            ->whereYear('created_at', $searchYear)
            ->sum('price');

        // Calculate net sell transactions for the search year
        $sellTransactionYear = $sellTransaction
            ->filter(function ($item) use ($searchYear) {
                return Carbon::parse($item->created_at)->year == $searchYear && $item->parent()->exists();
            })
            ->map(function ($item) {
                $buyPrice = $item->parent->price;
                return $item->price - $buyPrice;
            })
            ->sum();

        // Calculate total withdrawals for the search year
        $withdraw = $carSum + $employeeSum + $elbancherSum + $othersSum + $totalRentPriceFromYear;
    @endphp

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('companyRevExp') }}" method="GET" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="year" class="sr-only">السنة</label>
                        <input type="text" name="query" class="form-control" id="year" placeholder="YYYY"
                            value="{{ request('year', $currentYear) }}">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">بحث</button>
                </form>
            </div>
        </div>

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
                            <td>{{ $sellTransactionYear }}</td>
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
                            </ </thead>
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
                            <td>{{ $companyPriceWithdrawYear }}</td>
                        </tr>
                        <tr>
                            <td>
                                <a href="#">
                                    اجمالي اوامر النقل
                                </a>
                            </td>
                            <td>{{ $totalTransferPriceYear->sum() }}</td>
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
                            <td>{{ $totalRentPriceFromYear }}</td>
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
                        $containerFiltered = $container->filter(function ($item) use ($month, $searchYear) {
                            return Carbon::parse($item->created_at)->month == $month &&
                                Carbon::parse($item->created_at)->year == $searchYear;
                        });

                        $rent_price = $rentOffices->map(function ($items) use ($month, $searchYear) {
                            return $items->employeedaily
                                ->filter(function ($daily) use ($month, $searchYear) {
                                    return Carbon::parse($daily->created_at)->month == $month &&
                                        Carbon::parse($daily->created_at)->year == $searchYear;
                                })
                                ->where('type', 'withdraw')
                                ->sum('price');
                        });
                        $totalRentPriceFromMonth = $rent_price->sum();

                        $totals = $containerFiltered->mapWithKeys(function ($items) use ($month, $searchYear) {
                            $totalPrice = $items->daily
                                ->filter(function ($daily) use ($month, $searchYear) {
                                    return Carbon::parse($daily->created_at)->month == $month &&
                                        Carbon::parse($daily->created_at)->year == $searchYear;
                                })
                                ->sum('price');
                            return [$items->id => $totalPrice];
                        });

                        $sumContainer = $containerFiltered->sum('price') + $totals->sum();

                        $customSum = $customs->sum(function ($item) use ($month, $searchYear) {
                            return $item->clientdaily
                                ->where('type', 'deposit')
                                ->filter(function ($daily) use ($month, $searchYear) {
                                    return Carbon::parse($daily->created_at)->month == $month &&
                                        Carbon::parse($daily->created_at)->year == $searchYear;
                                })
                                ->sum('price');
                        });

                        $carsFiltered = $cars->filter(function ($item) use ($month, $searchYear) {
                            return Carbon::parse($item->created_at)->month == $month &&
                                Carbon::parse($item->created_at)->year == $searchYear;
                        });

                        $employeeSumfromMonth = $employees->sum(function ($employee) use ($month, $searchYear) {
                            return $employee->employeedaily
                                ->filter(function ($daily) use ($month, $searchYear) {
                                    return Carbon::parse($daily->created_at)->month == $month &&
                                        Carbon::parse($daily->created_at)->year == $searchYear &&
                                        $daily->type == 'withdraw';
                                })
                                ->sum('price');
                        });

                        $elbancherFiltered = collect($mergedArrayAlbancher)
                            ->filter(function ($item) use ($month, $searchYear) {
                                return Carbon::parse($item['created_at'])->month == $month &&
                                    Carbon::parse($item['created_at'])->year == $searchYear &&
                                    $item['type'] == 'withdraw';
                            })
                            ->sum('price');

                        $otherSumfromMonth = $others
                            ->filter(function ($item) use ($month, $searchYear) {
                                return $item->employeedaily
                                    ->where('type', 'withdraw')
                                    ->filter(function ($daily) use ($month, $searchYear) {
                                        return Carbon::parse($daily->created_at)->month == $month &&
                                            Carbon::parse($daily->created_at)->year == $searchYear;
                                    })
                                    ->isNotEmpty();
                            })
                            ->sum(function ($item) use ($month, $searchYear) {
                                return $item->employeedaily
                                    ->where('type', 'withdraw')
                                    ->filter(function ($daily) use ($month, $searchYear) {
                                        return Carbon::parse($daily->created_at)->month == $month &&
                                            Carbon::parse($daily->created_at)->year == $searchYear;
                                    })
                                    ->sum('price');
                            });

                        $totalPriceForMonthCompanyWithdraw = $companyPriceWithdraw->employeedaily
                            ->filter(function ($daily) use ($month, $searchYear) {
                                return $daily->type == 'withdraw' &&
                                    Carbon::parse($daily->created_at)->month == $month &&
                                    Carbon::parse($daily->created_at)->year == $searchYear;
                            })
                            ->sum('price');

                        $transferPriceFromMonth = Daily::whereNotNull('container_id')
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $searchYear)
                            ->where('type', 'withdraw')
                            ->sum('price');

                        $partnerWithdraw = Daily::whereNull('container_id')
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $searchYear)
                            ->where('type', 'partner_withdraw')
                            ->sum('price');

                        $sellTransactionMonthly = $sellTransaction
                            ->filter(function ($item) use ($month, $searchYear) {
                                return Carbon::parse($item->created_at)->month === $month &&
                                    Carbon::parse($item->created_at)->year == $searchYear &&
                                    $item->parent()->exists();
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
                            $totalRentPriceFromMonth +
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
