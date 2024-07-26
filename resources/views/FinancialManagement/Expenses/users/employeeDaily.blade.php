@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h2 class="text-center mb-4 text-white">{{ $user->name }}</h2>
                <h2 class="text-center mb-4 text-white"> الراتب {{ $user->sallary }}</h2>

                @include('layouts.search-box')

                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-white">
                            <th scope="col">الباقي</th>
                            <th scope="col">الواصل له</th>
                            <th scope="col">اجمالي</th>
                            <th scope="col">الحافز - الترب</th>
                            <th scope="col">الحافز الفارغ</th>
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
                            $totalSaving = 0;
                            $searchYear = request('year') ?? $currentYear;
                            $startYear = Carbon::parse($user->userInfo->date_runer)->year;

                            for ($year = $startYear; $year <= $currentYear; $year++) {
                                $yearlyTotal = 0;

                                $startMonth =
                                    $year == $startYear ? Carbon::parse($user->userInfo->date_runer)->month : 1;
                                $endMonth = $year == $currentYear ? $currentMonth : 12;

                                for ($month = $startMonth; $month <= $endMonth; $month++) {
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

                                    $dailyTransaction = $monthTransactions->where('type', 'withdraw')->sum('price');
                                    $tips = $tipsMonth->sum('tips');

                                    $tipsEmptyMonth = $user
                                        ->tipsEmpty()
                                        ->whereMonth('created_at', $month)
                                        ->whereYear('created_at', $year)
                                        ->sum('price');

                                    $total = $sallary + $tips;
                                    $saving = $total - $dailyTransaction;

                                    if ($year == $searchYear) {
                                        $annualStatement[] = [
                                            'total' => $total,
                                            'dailyTransaction' => $dailyTransaction,
                                            'saving' => $saving,
                                            'month' => $month,
                                            'year' => $year,
                                            'tips' => $tips,
                                            'tipsEmpty' => $tipsEmptyMonth,
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
                                <td>{{ $statement['tipsEmpty'] }}</td>
                                <td>{{ $statement['sallary'] }}</td>
                                <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}
                                    {{ $statement['year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3 class="text-white"> الاجمالي لكل السنوات: {{ $totalSaving }}</h3>
            </div>
        </div>
    </div>
@stop
