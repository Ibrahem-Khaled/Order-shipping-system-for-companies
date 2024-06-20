@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-white" style="text-align: right">كشف حساب سنوي ل {{ $user->name }}</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            @php
                                // Generate the year columns dynamically
                                $userCreationYear = Carbon\Carbon::parse($user->created_at)->year;
                                $currentYear = now()->year;
                            @endphp
                            @for ($year = $userCreationYear; $year <= $currentYear; $year++)
                                <th scope="col">{{ $year }}</th>
                            @endfor
                            <th scope="col">الشهر</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $annualStatement = [];
                            $totalEarnMoney = 0;
                            $userCreationMonth = Carbon\Carbon::parse($user->created_at)->month;

                            // Loop through each year from user creation year to the current year
                            for ($year = $userCreationYear; $year <= $currentYear; $year++) {
                                for ($month = 1; $month <= 12; $month++) {
                                    if ($year == $userCreationYear && $month < $userCreationMonth) {
                                        $annualStatement[$month][$year] = '0.00';
                                        continue;
                                    }

                                    $monthlyDeposits = $container
                                        ->filter(
                                            fn($item) => Carbon\Carbon::parse($item->created_at)->year == $year &&
                                                Carbon\Carbon::parse($item->created_at)->month == $month,
                                        )
                                        ->sum('price');

                                    $rent_price = $rentOffices->map(function ($items) use ($month, $year) {
                                        return $items->employeedaily
                                            ->filter(function ($daily) use ($month, $year) {
                                                return Carbon\Carbon::parse($daily->created_at)->year == $year &&
                                                    Carbon\Carbon::parse($daily->created_at)->month == $month;
                                            })
                                            ->where('type', 'withdraw')
                                            ->sum('price');
                                    });
                                    $totalRentPriceFromCurrentMonth = $rent_price->sum();

                                    $employeeSum = $employees->sum(
                                        fn($employee) => $employee->employeedaily
                                            ->where('type', 'withdraw')
                                            ->filter(
                                                fn($daily) => Carbon\Carbon::parse($daily->created_at)->year == $year &&
                                                    Carbon\Carbon::parse($daily->created_at)->month == $month,
                                            )
                                            ->sum('price'),
                                    );

                                    $carsFiltered = $cars->sum(
                                        fn($car) => $car->daily
                                            ->filter(
                                                fn($item) => Carbon\Carbon::parse($item->created_at)->year == $year &&
                                                    Carbon\Carbon::parse($item->created_at)->month == $month,
                                            )
                                            ->sum('price'),
                                    );

                                    $othersSum = $others->sum(
                                        fn($other) => $other->employeedaily
                                            ->where('type', 'withdraw')
                                            ->filter(
                                                fn($daily) => Carbon\Carbon::parse($daily->created_at)->year == $year &&
                                                    Carbon\Carbon::parse($daily->created_at)->month == $month,
                                            )
                                            ->sum('price'),
                                    );

                                    $elbancherFiltered = collect($mergedArrayAlbancher)
                                        ->filter(
                                            fn($item) => Carbon\Carbon::parse($item['created_at'])->year == $year &&
                                                Carbon\Carbon::parse($item['created_at'])->month == $month &&
                                                $item['type'] == 'withdraw',
                                        )
                                        ->sum('price');

                                    $withdrawMonth =
                                        $carsFiltered +
                                        $employeeSum +
                                        $elbancherFiltered +
                                        $totalRentPriceFromCurrentMonth +
                                        $othersSum;

                                    $totalPrice = $monthlyDeposits - $withdrawMonth;

                                    // الشركاء النشطين في هذا الشهر
                                    $activePartners = $partners->filter(
                                        fn($partner) => $partner->is_active == 1 &&
                                            Carbon\Carbon::parse($partner->created_at)->year <= $year &&
                                            Carbon\Carbon::parse($partner->created_at)->month <= $month,
                                    );

                                    // جمع إجمالي أموال الشركاء النشطين
                                    $totalActivePartnerSum = $activePartners->sum(
                                        fn($partner) => $partner->partnerInfo->sum('money'),
                                    );

                                    $monthlyProfit = 0;

                                    if ($totalActivePartnerSum > 0) {
                                        $userShare = $user->partnerInfo?->sum('money') ?? 0;
                                        $partnerSum = ($userShare / $totalActivePartnerSum) * 100;
                                        $monthlyProfit = ($totalPrice * $partnerSum) / 100;
                                    }

                                    $totalEarnMoney += $monthlyProfit;

                                    $annualStatement[$month][$year] = number_format($monthlyProfit, 2);
                                }
                            }
                        @endphp

                        @for ($month = 1; $month <= 12; $month++)
                            <tr>
                                @for ($year = $userCreationYear; $year <= $currentYear; $year++)
                                    <td>{{ $annualStatement[$month][$year] ?? '0.00' }}</td>
                                @endfor
                                <td>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</td>
                            </tr>
                        @endfor
                    </tbody>

                </table>
            </div>

            <div class="col-md-12 mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">المبلغ</th>
                            <th scope="col">الوصف</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $partnerSumWithdraw = $user->partnerdaily->where('type', 'partner_withdraw')->sum('price');
                        @endphp
                        @foreach ($user->partnerdaily as $item)
                            <tr>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <h1 class="text-primary">اجمالي الارباح</h1>
        <h3 class="text-white">{{ number_format($totalEarnMoney - $partnerSumWithdraw, 2) }}</h3>
    </div>

@stop
