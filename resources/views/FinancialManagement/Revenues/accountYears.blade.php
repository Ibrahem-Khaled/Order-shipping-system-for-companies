@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success" style="text-align: right"> كشف حساب سنوي ل {{ $user->name }}</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">الرصيد الكلي</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $annualStatement = [];
                            $totalBalance = 0;
                            $currentYear = now()->year;

                            // Loop through each month of the current year
                            for ($month = 1; $month <= 12; $month++) {
                                // Filter daily records for the current month
                                if ($user->role == 'rent') {
                                    $monthTransactions = $user->rentCont->filter(function ($transaction) use ($currentYear, $month) {
                                        return $transaction->created_at->year == $currentYear && $transaction->created_at->month == $month;
                                    });
                                } else {
                                    $monthTransactions = $user->container->filter(function ($transaction) use ($currentYear, $month) {
                                        return $transaction->created_at->year == $currentYear && $transaction->created_at->month == $month;
                                    });
                                }

                                // Calculate the total balance for the month
                                $monthlyTotal = $monthTransactions->sum('price');

                                // Store the month and total balance in the annual statement array
                                $annualStatement[$month] = [
                                    'month' => $month,
                                    'monthlyTotal' => $monthlyTotal,
                                ];
                            }
                        @endphp

                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ $statement['monthlyTotal'] }}</td>
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
                            $sum = 0;
                            $totalPrice = 0;
                        @endphp
                        @foreach ($daily->sortBy('created_at') as $item)
                            <tr>
                                <td>{{ $item->price }}</td>
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
        <h1 class="text-primary">المتبقي</h1>
        @php
            if ($user->role == 'rent') {
                $sumPrice = $user->rentCont->sum('price');
            } else {
                $sumPrice = $user->container->sum('price');
            }
            $sumDaily = $user->clientdaily->sum('price');
        @endphp
        <h3 class="text-dark">
            {{ $sumPrice - $sumDaily }}
        </h3>
    </div>
@stop
