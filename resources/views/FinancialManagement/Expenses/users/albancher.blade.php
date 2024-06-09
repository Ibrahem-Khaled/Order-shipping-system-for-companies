@extends('layouts.default')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="container mt-5">
        <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
            <h3 class="text-center mb-4"> {{ count($users) }} اجمالي حسابات البنشري </h3>
            <table class="table table-striped table-bordered table-hover table-sm">
                <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                    <tr>
                        <th scope="col" class="text-center">اجمالي الواصل (الشهر الحالي)</th>
                        <th scope="col" class="text-center">الاسم</th>
                        <th scope="col" class="text-center">#</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $item)
                        @php
                            $currentMonthWithdrawals = $item->employeedaily
                                ->where('type', 'withdraw')
                                ->filter(function ($transaction) {
                                    return Carbon::parse($transaction->created_at)->year == Carbon::now()->year &&
                                        Carbon::parse($transaction->created_at)->month == Carbon::now()->month;
                                })
                                ->sum('price');
                        @endphp
                        <tr>
                            <td class="text-center font-weight-bold" style="font-size: 18px;">
                                {{ number_format($currentMonthWithdrawals, 2) }}
                            </td>
                            <td class="text-center font-weight-bold" style="font-size: 18px;">
                                <a href="{{ route('expensesAlbancherDaily', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="text-center font-weight-bold" style="font-size: 18px;">
                                {{ $item->id }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
