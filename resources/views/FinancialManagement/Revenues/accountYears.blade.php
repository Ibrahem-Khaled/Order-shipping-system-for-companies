@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-white text-right">كشف حساب سنوي ل {{ $user->name }}</h1>
    </div>

    @include('layouts.search-box')

    @php
        $year = request('year', now()->year);

        $userDailyTransactions =
            $user->role !== 'rent'
                ? $user->clientdaily
                    ->where('type', 'deposit')
                    ->filter(fn($transaction) => $transaction->created_at->year == $year)
                    ->sortBy('created_at')
                : $user->employeedaily
                    ->where('type', 'withdraw')
                    ->filter(fn($transaction) => $transaction->created_at->year == $year)
                    ->sortBy('created_at');

        $annualStatement = [];
        $cumulativeResidual = 0;

        // Calculate the cumulative residual for previous years
        for ($pastYear = $year - 1; $pastYear >= now()->year - 5; $pastYear--) {
            $yearlyTransactions =
                $user->role == 'rent'
                    ? $user->rentCont->filter(fn($transaction) => $transaction->created_at->year == $pastYear)
                    : $user->container->filter(fn($transaction) => $transaction->created_at->year == $pastYear);

            $yearlyTotalFromContainer = $yearlyTransactions->sum('price');
            $yearlyDeposit =
                $user->role == 'rent'
                    ? $user->employeedaily
                        ->where('type', 'withdraw')
                        ->filter(fn($transaction) => $transaction->created_at->year == $pastYear)
                        ->sum('price')
                    : $user->clientdaily
                        ->where('type', 'deposit')
                        ->filter(fn($transaction) => $transaction->created_at->year == $pastYear)
                        ->sum('price');

            $yearlyResidual = $yearlyTotalFromContainer - $yearlyDeposit;
            $cumulativeResidual += $yearlyResidual;
        }

        for ($month = 1; $month <= 12; $month++) {
            $priceTransfer = 0;

            $monthTransactions =
                $user->role == 'rent'
                    ? $user->rentCont->filter(
                        fn($transaction) => $transaction->created_at->year == $year &&
                            $transaction->created_at->month == $month,
                    )
                    : $user->container->filter(
                        fn($transaction) => $transaction->created_at->year == $year &&
                            $transaction->created_at->month == $month,
                    );

            $sumRentprice = $monthTransactions->sum('rent_price');

            if ($user->role != 'rent') {
                $monthDeposit = $user->clientdaily->filter(
                    fn($transaction) => $transaction->created_at->year == $year &&
                        $transaction->created_at->month == $month,
                );

                foreach ($monthTransactions as $transaction) {
                    $priceTransfer += $transaction->daily->filter(fn($item) => $item->type == 'withdraw')->sum('price');
                }
            } else {
                $monthDeposit = $userDailyTransactions->filter(
                    fn($transaction) => $transaction->created_at->year == $year &&
                        $transaction->created_at->month == $month,
                );
            }

            $monthlyTotalFromContainer =
                $user->role == 'rent' ? $sumRentprice : $monthTransactions->sum('price') + $priceTransfer;

            $monthlyDeposit =
                $user->role == 'rent'
                    ? $monthDeposit->where('type', 'withdraw')->sum('price')
                    : (isset($monthDeposit)
                        ? $monthDeposit->where('type', 'deposit')->sum('price')
                        : 0);

            $residual = $monthlyTotalFromContainer - $monthlyDeposit;
            $cumulativeResidual += $residual;

            $annualStatement[] = [
                'month' => $month,
                'monthlyTotalFromContainer' => $monthlyTotalFromContainer + $cumulativeResidual,
                'monthlyTotalFromContainerCurrent' => $monthlyTotalFromContainer,
                'monthlyDeposit' => $monthlyDeposit,
                'residual' => $monthlyTotalFromContainer || $monthlyDeposit ? $cumulativeResidual : 0,
            ];
        }
    @endphp

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">المتبقي</th>
                            <th scope="col">{{ $user->role == 'rent' ? 'المدفوع للناقل' : 'اجمالي الوارد' }}</th>
                            <th scope="col">{{ $user->role == 'rent' ? 'المستحق للناقل' : 'الرصيد الكلي' }}</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ $statement['residual'] }}</td>
                                <td>{{ $statement['monthlyDeposit'] }}</td>
                                <td>{{ $statement['monthlyTotalFromContainerCurrent'] }}</td>
                                <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6 mb-4">
                <div class="table-container" style="max-height: 800px; overflow-y: auto;">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">المبلغ</th>
                                <th scope="col">الوصف</th>
                                <th scope="col">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userDailyTransactions as $item)
                                <tr>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h1 class="text-primary">المتبقي</h1>
            <h3 class="text-white">{{ $cumulativeResidual }}</h3>
        </div>
    </div>

@stop
