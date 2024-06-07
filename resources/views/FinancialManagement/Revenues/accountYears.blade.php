@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success text-right">كشف حساب سنوي ل {{ $user->name }}</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">المتبقي</th>
                            <th scope="col">اجمالي الوارد</th>
                            <th scope="col">الرصيد الكلي</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $annualStatement = [];
                            $currentYear = now()->year;
                            $cumulativeResidual = 0;

                            for ($month = 1; $month <= 12; $month++) {
                                $priceTransfer = 0;

                                $monthTransactions =
                                    $user->role == 'rent'
                                        ? $user->rentCont->filter(
                                            fn($transaction) => $transaction->created_at->year == $currentYear &&
                                                $transaction->created_at->month == $month,
                                        )
                                        : $user->container->filter(
                                            fn($transaction) => $transaction->created_at->year == $currentYear &&
                                                $transaction->created_at->month == $month,
                                        );

                                if ($user->role != 'rent') {
                                    $monthDeposit = $user->clientdaily->filter(
                                        fn($transaction) => $transaction->created_at->year == $currentYear &&
                                            $transaction->created_at->month == $month,
                                    );

                                    foreach ($monthTransactions as $transaction) {
                                        $priceTransfer += $transaction->daily
                                            ->filter(fn($item) => $item->type == 'withdraw')
                                            ->sum('price');
                                    }
                                }

                                $monthlyTotalFromContainer = $monthTransactions->sum('price') + $priceTransfer;
                                $monthlyDeposit = isset($monthDeposit)
                                    ? $monthDeposit->where('type', 'deposit')->sum('price')
                                    : 0;

                                $residual = $monthlyTotalFromContainer - $monthlyDeposit;
                                $cumulativeResidual += $residual;

                                $annualStatement[] = [
                                    'month' => $month,
                                    'monthlyTotalFromContainer' => $monthlyTotalFromContainer + $cumulativeResidual,
                                    'monthlyTotalFromContainerCurrent' => $monthlyTotalFromContainer,
                                    'monthlyDeposit' => $monthlyDeposit,
                                    'residual' => $cumulativeResidual,
                                ];
                            }
                        @endphp

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
                        @foreach ($user->clientdaily->where('type', 'deposit')->sortBy('created_at') as $item)
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

        <h3 class="text-dark">
            {{ $cumulativeResidual }}
        </h3>
    </div>

@stop
