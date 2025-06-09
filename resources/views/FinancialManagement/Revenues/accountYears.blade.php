@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-file-invoice-dollar ml-2"></i>كشف حساب سنوي ل {{ $user->name }}</h2>
                </div>
            </div>

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

                // حساب cumulativeResidual للسنوات السابقة فقط (بدون السنة الحالية)
                for ($pastYear = $year - 1; $pastYear >= $year - 5; $pastYear--) {
                    $yearlyTransactions =
                        $user->role == 'rent'
                            ? $user->rentCont->filter(fn($transaction) => $transaction->created_at->year == $pastYear)
                            : $user->container->filter(fn($transaction) => $transaction->created_at->year == $pastYear);

                    $yearlyTotalFromContainer = $yearlyTransactions->sum('price');

                    // حساب priceTransfer للسنوات السابقة
                    $priceTransfer = 0;
                    if ($user->role != 'rent') {
                        foreach ($yearlyTransactions as $transaction) {
                            $priceTransfer += $transaction->daily
                                ->filter(fn($item) => $item->type == 'withdraw')
                                ->sum('price');
                        }
                    }

                    $yearlyTotalFromContainer += $priceTransfer; // إضافة priceTransfer إلى yearlyTotalFromContainer

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

                // بدء الحساب الشهري للسنة الحالية مع تضمين cumulativeResidual للسنوات السابقة
                $cumulativeResidualCurrentYear = $cumulativeResidual; // حفظ قيمة cumulativeResidual للسنوات السابقة

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
                            $priceTransfer += $transaction->daily
                                ->filter(fn($item) => $item->type == 'withdraw')
                                ->sum('price');
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
                    $cumulativeResidualCurrentYear += $residual; // تحديث cumulativeResidual للسنة الحالية

                    $annualStatement[] = [
                        'month' => $month,
                        'monthlyTotalFromContainer' => $monthlyTotalFromContainer + $cumulativeResidualCurrentYear,
                        'monthlyTotalFromContainerCurrent' => $monthlyTotalFromContainer,
                        'monthlyDeposit' => $monthlyDeposit,
                        'residual' =>
                            $monthlyTotalFromContainer || $monthlyDeposit ? $cumulativeResidualCurrentYear : 0,
                    ];
                }
            @endphp

            <div class="card-body">
                <div class="row">
                    <!-- الجدول الشهري -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>البيانات الشهرية</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-nowrap">الشهر</th>
                                                <th class="text-nowrap text-end">
                                                    {{ $user->role == 'rent' ? 'المستحق للناقل' : 'الرصيد الكلي' }}</th>
                                                <th class="text-nowrap text-end">
                                                    {{ $user->role == 'rent' ? 'المدفوع للناقل' : 'اجمالي الوارد' }}</th>
                                                <th class="text-nowrap text-end">المتبقي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- صف السنوات الفائتة -->
                                            <tr class="table-info">
                                                <td><strong>السنوات الفائتة</strong></td>
                                                <td class="text-end">0</td>
                                                <td class="text-end">0</td>
                                                <td class="text-end fw-bold">{{ number_format($cumulativeResidual) }}</td>
                                            </tr>

                                            <!-- البيانات الشهرية -->
                                            @foreach ($annualStatement as $statement)
                                                <tr>
                                                    <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ $statement['monthlyTotalFromContainerCurrent'] }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ $statement['monthlyDeposit'] }}
                                                    </td>
                                                    <td
                                                        class="text-end {{ $statement['residual'] > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                                        {{ $statement['residual'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- حركات اليومية -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0"><i class="fas fa-list-alt me-2"></i>حركات اليومية</h4>
                                    @include('layouts.search-box')
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="text-nowrap">التاريخ</th>
                                                <th class="text-nowrap">الوصف</th>
                                                <th class="text-nowrap text-end">المبلغ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($userDailyTransactions as $item)
                                                <tr>
                                                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td
                                                        class="text-end {{ $user->role == 'rent' ? 'text-danger' : 'text-success' }}">
                                                        {{ number_format($item->price) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ملخص الرصيد -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-{{ $cumulativeResidualCurrentYear > 0 ? 'danger' : 'success' }}">
                            <div class="card-body text-center py-3">
                                <h3 class="mb-2">الرصيد المتبقي نهاية السنة</h3>
                                <h1
                                    class="display-4 fw-bold text-{{ $cumulativeResidualCurrentYear > 0 ? 'danger' : 'success' }}">
                                    {{ number_format($cumulativeResidualCurrentYear) }} ر.س
                                </h1>
                                <p class="mb-0 text-muted">حالة الحساب:
                                    <span
                                        class="badge text-white bg-{{ $cumulativeResidualCurrentYear > 0 ? 'danger' : 'success' }}">
                                        {{ $cumulativeResidualCurrentYear > 0 ? 'مدين' : 'مسدد' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .sticky-top {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 1;
        }

        .card {
            border-radius: 0.5rem;
        }

        .table th {
            border-top: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // جعل رأس الجدول ثابتًا عند التمرير
            const tableContainer = document.querySelector('.table-responsive');
            if (tableContainer) {
                tableContainer.addEventListener('scroll', function() {
                    const thead = this.querySelector('thead');
                    if (thead) {
                        thead.style.transform = `translateY(${this.scrollTop}px)`;
                    }
                });
            }
        });
    </script>
@stop
