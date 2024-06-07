@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success" style="text-align: right">كشف حساب سنوي ل {{ $user->name }}</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">الأرباح</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            use Carbon\Carbon;

                            $annualStatement = [];
                            $totalEarnMoney = 0;

                            // User creation month
                            $userCreationMonth = Carbon::parse($user->created_at)->month;

                            for ($month = 1; $month <= 12; $month++) {
                                if ($month >= $userCreationMonth) {
                                    $monthlyDeposits = $container
                                        ->filter(fn($item) => Carbon::parse($item->created_at)->month == $month)
                                        ->sum('price');

                                    $employeeSum = $employees->sum(
                                        fn($employee) => $employee->employeedaily
                                            ->where('type', 'withdraw')
                                            ->filter(fn($daily) => Carbon::parse($daily->created_at)->month == $month)
                                            ->sum('price'),
                                    );

                                    $carsFiltered = $cars->sum(
                                        fn($car) => $car->daily
                                            ->filter(fn($item) => Carbon::parse($item->created_at)->month == $month)
                                            ->sum('price'),
                                    );

                                    $othersSum = $others->sum(
                                        fn($other) => $other->employeedaily
                                            ->where('type', 'withdraw')
                                            ->filter(fn($daily) => Carbon::parse($daily->created_at)->month == $month)
                                            ->sum('price'),
                                    );

                                    $elbancherFiltered = collect($mergedArrayAlbancher)
                                        ->filter(
                                            fn($item) => Carbon::parse($item['created_at'])->month == $month &&
                                                $item['type'] == 'withdraw',
                                        )
                                        ->sum('price');

                                    $withdrawMonth = $carsFiltered + $employeeSum + $elbancherFiltered + $othersSum;
                                    $totalPrice = $monthlyDeposits - $withdrawMonth;

                                    $partnerSum =
                                        $user->is_active == 1
                                            ? (($user->partnerInfo?->sum('money') ?? 0) / $sumCompany) * 100
                                            : 0;
                                    $monthlyProfit = $user->is_active == 1 ? ($totalPrice * $partnerSum) / 100 : 0;

                                    $totalEarnMoney += $monthlyProfit;

                                    $annualStatement[$month] = [
                                        'month' => $month,
                                        'deposits' => number_format($monthlyProfit, 2), // Format numbers
                                    ];
                                } else {
                                    $annualStatement[$month] = [
                                        'month' => $month,
                                        'deposits' => '0.00',
                                    ];
                                }
                            }
                        @endphp

                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ $statement['deposits'] }}</td>
                                <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="col-md-6">
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
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <h1 class="text-primary">اجمالي الارباح</h1>
        <h3 class="text-dark">{{ number_format($totalEarnMoney - $partnerSumWithdraw, 2) }}</h3>
    </div>

@stop
