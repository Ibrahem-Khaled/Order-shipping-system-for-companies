@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
            <h3 class="text-center mb-4">{{ count($employee) }} الموظفين</h3>
            <table class="table table-striped table-bordered table-hover table-sm">
                <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                    <tr>
                        @if (auth()->user()->role == 'superAdmin')
                            <th scope="col" class="text-center"></th>
                        @endif
                        <th scope="col" class="text-center">الصورة الشخصية</th>
                        <th scope="col" class="text-center">المهنة</th>
                        <th scope="col" class="text-center">كشف حساب ترب</th>
                        <th scope="col" class="text-center">الراتب</th>
                        <th scope="col" class="text-center">الاسم</th>
                        <th scope="col" class="text-center">#</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($employee as $item)
                        @if (auth()->user()->role != 'driver' || auth()->user()->id == $item->id)
                            @php
                                $currentMonth = Carbon\Carbon::now()->month;
                                $date_runer = \Carbon\Carbon::parse($item->userInfo->date_runer);
                                $month = $date_runer->month;
                                $salary = 0;

                                $currentMonthTips =
                                    $item->role == 'driver'
                                        ? $item->driverContainer
                                                ->filter(function ($transaction) use ($currentMonth) {
                                                    return $transaction->created_at->month == $currentMonth;
                                                })
                                                ->sum('tips') +
                                            $item->tipsEmpty()->whereMonth('created_at', $currentMonth)->sum('price')
                                        : 0;

                                $totalTips = $item->driverContainer->sum('tips');
                                $totalTipEmpty = $item->tipsEmpty?->sum('price');

                                $withdrawFromDaily = $item->employeedaily->where('type', 'withdraw')->sum('price');
                                for ($month; $month <= $currentMonth; $month++) {
                                    $salary += $item->sallary;
                                }
                                $totalSalary = $salary + $totalTips + $totalTipEmpty - $withdrawFromDaily;
                            @endphp
                            <tr>
                                @if (auth()->user()->role == 'superAdmin')
                                    <td class="text-center">
                                        {{ $item->created_at != $item->updated_at ? 'معدلة' : '' }}
                                    </td>
                                @endif
                                <td class="text-center">
                                    <img src="{{ $item?->userinfo?->image ?? 'https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg' }}"
                                        alt="{{ $item->name }}" class="img-thumbnail"
                                        style="max-width: 100px; max-height: 100px;">
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'driver' ? 'سائق' : 'اداري' }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $currentMonthTips }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    ر.س{{ $totalSalary }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    @if ($item->role == 'driver')
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#expenses{{ $item->id }}">
                                            {{ $item->name }}
                                        </button>
                                        <div class="modal fade" id="expenses{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="expensesLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="expensesLabel{{ $item->id }}">اختر
                                                            نوع
                                                            الطلب:</h5>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="list-group">
                                                            <a href="{{ route('expensesEmployeeTips', $item->id) }}"
                                                                class="list-group-item list-group-item-action">كشف حساب
                                                                الترب</a>
                                                            <a href="{{ route('expensesEmployeeDaily', $item->id) }}"
                                                                class="list-group-item list-group-item-action">كشف حساب
                                                                السائق</a>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a class="btn btn-primary" href="{{ route('expensesEmployeeDaily', $item->id) }}">
                                            {{ $item->name }}
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->id }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

@stop
