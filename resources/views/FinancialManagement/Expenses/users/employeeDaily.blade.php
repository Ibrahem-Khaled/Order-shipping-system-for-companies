@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h2>{{ $user->name }}</h2>
                <h2> الراتب {{ $user->sallary }}</h2>

                @include('layouts.search-box')

                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-success">
                            <th scope="col">الباقي</th>
                            <th scope="col">الواصل له</th>
                            <th scope="col">اجمالي</th>
                            <th scope="col">الحافز - الترب</th>
                            <th scope="col">الراتب</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            use Carbon\Carbon;

                            $annualStatement = [];
                            $currentYear = now()->year;
                            $currentMonth = now()->month;
                            $sallary = $user->sallary;
                            $totalSaving = 0; // To store the total saving across all years
                            $searchYear = request('year') ?? $currentYear; // Get the search year or use current year
                            $startYear = Carbon::parse($user->userInfo->date_runer)->year;

                            // Loop through each year from the start year to the current year
                            for ($year = $startYear; $year <= $currentYear; $year++) {
                                $yearlyTotal = 0; // To store the total saving for the current year

                                $startMonth =
                                    $year == $startYear ? Carbon::parse($user->userInfo->date_runer)->month : 1;
                                $endMonth = $year == $currentYear ? $currentMonth : 12;

                                // Loop through each month of the current year
                                for ($month = $startMonth; $month <= $endMonth; $month++) {
                                    // Filter daily records for the current month
                                    $monthTransactions = $user->employeedaily->filter(function ($transaction) use (
                                        $year,
                                        $month,
                                    ) {
                                        return $transaction->created_at->year == $year &&
                                            $transaction->created_at->month == $month;
                                    });

                                    $tipsMonth = $user->driverContainer->filter(function ($transaction) use (
                                        $year,
                                        $month,
                                    ) {
                                        $transferDate = Carbon::parse($transaction->transfer_date);
                                        return $transferDate->year == $year && $transferDate->month == $month;
                                    });

                                    // Calculate the total balance for the month
                                    $dailyTransaction = $monthTransactions->where('type', 'withdraw')->sum('price');
                                    $tips = $tipsMonth->sum('tips');

                                    $total = $sallary + $tips;
                                    $saving = $total - $dailyTransaction;

                                    // Store the month and total balance in the annual statement array for the search year
                                    if ($year == $searchYear) {
                                        $annualStatement[] = [
                                            'total' => $total,
                                            'dailyTransaction' => $dailyTransaction,
                                            'saving' => $saving,
                                            'month' => $month,
                                            'year' => $year,
                                            'tips' => $tips,
                                            'sallary' => $sallary,
                                        ];
                                    }

                                    $yearlyTotal += $saving;
                                }

                                $totalSaving += $yearlyTotal;
                            }
                        @endphp

                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ $statement['saving'] }}</td>
                                <td>{{ $statement['dailyTransaction'] }}</td>
                                <td>{{ $statement['total'] }}</td>
                                <td>{{ $statement['tips'] }}</td>
                                <td>{{ $statement['sallary'] }}</td>
                                <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}
                                    {{ $statement['year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3> الاجمالي لكل السنوات: {{ $totalSaving }}</h3>
            </div>
        </div>
    </div>
@stop
