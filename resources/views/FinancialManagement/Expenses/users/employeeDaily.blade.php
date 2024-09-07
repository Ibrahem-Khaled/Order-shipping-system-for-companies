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
                                    $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth; // عدد أيام الشهر
                                    $startOfMonth = Carbon::createFromDate($year, $month, 1);
                                    $endOfMonth = Carbon::createFromDate($year, $month, $daysInMonth);

                                    // حساب عدد الأيام التي عملها الموظف من يوم تعيينه
                                    $dateHired = Carbon::parse($user->userInfo->date_runer);
                                    if ($dateHired->year == $year && $dateHired->month == $month) {
                                        // إذا تم تعيين الموظف خلال الشهر
                                        $workedDays = $endOfMonth->diffInDays($dateHired) + 1;
                                    } else {
                                        $workedDays = $daysInMonth; // إذا كان الموظف قد عمل كامل الشهر
                                    }

                                    // حساب الراتب اليومي وعدد الأيام الفعلية
                                    $dailySallary = $sallary / $daysInMonth;
                                    $actualSallary = $dailySallary * $workedDays;

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

                                    $total = $actualSallary + $tips + $tipsEmptyMonth;
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
                                            'sallary' => $actualSallary,
                                        ];
                                    }

                                    $yearlyTotal += $saving;
                                }

                                $totalSaving += $yearlyTotal;
                            }

                            // تجهيز بيانات المعاملات لـ JavaScript
                            $transactionsData = $user->employeedaily->map(function ($transaction) {
                                return [
                                    'description' => $transaction->description,
                                    'price' => $transaction->price,
                                    'date' => $transaction->created_at->format('Y-m-d'),
                                    'year' => $transaction->created_at->year,
                                    'month' => $transaction->created_at->month,
                                ];
                            });
                        @endphp

                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ intval($statement['saving']) }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm"
                                        onclick="showDetailsModal({{ $statement['year'] }}, {{ $statement['month'] }})">
                                        {{ $statement['dailyTransaction'] }}
                                    </button>
                                </td>
                                <td>{{ intval($statement['total']) }}</td>
                                <td>{{ $statement['tips'] }}</td>
                                <td>{{ $statement['tipsEmpty'] }}</td>
                                <td>{{ intval($statement['sallary']) }}</td>
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

    <!-- Modal for showing daily transactions -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">تفاصيل الواصل له</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">الوصف</th>
                                <th scope="col">المبلغ</th>
                                <th scope="col">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody id="detailsModalBody">
                            <!-- سيتم تعبئة التفاصيل هنا بواسطة JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function showDetailsModal(year, month) {
            const detailsModalBody = document.getElementById('detailsModalBody');
            detailsModalBody.innerHTML = ''; // مسح المحتوى الحالي

            // جلب التفاصيل التي تتعلق بالشهر والسنة المحددين
            const transactions = @json($transactionsData);

            const filteredTransactions = transactions.filter(function(transaction) {
                return transaction.year === year && transaction.month === month;
            });

            // عرض التفاصيل في المودال
            filteredTransactions.forEach(function(transaction) {
                const row = `<tr>
                    <td>${transaction.description}</td>
                    <td>${transaction.price}</td>
                    <td>${transaction.date}</td>
                </tr>`;
                detailsModalBody.innerHTML += row;
            });

            // عرض المودال
            const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
            detailsModal.show();
        }
    </script>
@stop
