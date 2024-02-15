@extends('layouts.default')

@section('content')


    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h2>{{ $user->name }}</h2>
                <h2> الراتب {{ $user->sallary }}</h2>

                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-success">
                            <th scope="col">الباقي</th>
                            <th scope="col">الواصل له</th>
                            <th scope="col">اجمالي</th>
                            <th scope="col">الحافز - الترب</th>
                            <th scope="col">الراتب</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                    @foreach ($employee->employeedaily as $item)
                        <tr>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody> --}}
                    <tbody>
                        @php
                            $annualStatement = [];
                            $currentYear = now()->year;
                            $currentMonth = now()->month;
                            $sallary = $employee->sallary;
                            $total = 0;
                            $saving = 0;
                            // Loop through each month of the current year
                            for ($month = 1; $month <= $currentMonth; $month++) {
                                // Filter daily records for the current month
                                $monthTransactions = $employee->employeedaily->filter(function ($transaction) use ($currentYear, $month) {
                                    return $transaction->created_at->year == $currentYear && $transaction->created_at->month == $month;
                                });

                                $tipsMonth = $employee->driverContainer->filter(function ($transaction) use ($currentYear, $month) {
                                    return $transaction->created_at->year == $currentYear && $transaction->created_at->month == $month;
                                });
                                // Calculate the total balance for the month
                                $dailyTransaction = $monthTransactions->where('type', 'withdraw')->sum('price');
                                $monthlyTotal = $monthTransactions->where('type', 'withdraw')->sum('price');
                                $tips = $tipsMonth->sum('tips');

                                $total = $sallary + $tips;
                                $saving = $saving + $total - $dailyTransaction;
                                $save = $total - $dailyTransaction;

                                // Store the month and total balance in the annual statement array
                                $annualStatement[$month] = [
                                    'total' => $total,
                                    'dailyTransaction' => $dailyTransaction,
                                    'saving' => $save,
                                    'month' => $month,
                                    'tips' => $tips,
                                    'sallary' => $sallary,
                                ];
                            }
                        @endphp

                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ $statement['saving'] }}</td>
                                <td>{{ $statement['dailyTransaction'] }}</td>
                                <td>{{ $statement['total'] }}</td>
                                <td>{{ $statement['tips'] }}</td>
                                <td>{{ $statement['sallary'] }}</td>
                                <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $withdraw = $user->employeedaily->where('type', 'withdraw')->sum('price');
                        $sallary = $user->sallary;
                    @endphp
                </table>

                <h3> الاجمالي {{ $saving }}</h3>

            </div>
        </div>
    </div>


@stop
