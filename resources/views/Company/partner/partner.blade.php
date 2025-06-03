@extends('layouts.default')

@section('content')
    @php
        use Carbon\Carbon;
        $totalSumcalculatedValue = 0;
    @endphp

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header bg-gradient-primary shadow-lg rounded-lg p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="text-white mb-0">
                    <i class="fas fa-handshake me-2"></i>إدارة الشركاء
                </h2>
                <div class="d-flex">
                    <span class="badge bg-white text-primary fs-6">
                        <i class="fas fa-users me-1"></i> عدد الشركاء: {{ count($partner) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons mb-4">
            <button type="button" class="btn btn-success btn-lg me-2" data-toggle="modal" data-target="#addEmployeeModal">
                <i class="fas fa-user-plus me-2"></i>إضافة شريك
            </button>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#addHeadMoney">
                <i class="fas fa-coins me-2"></i>إضافة رأس مال
            </button>
        </div>

        <!-- Partners Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center bg-primary text-white py-3">#</th>
                                <th class="text-center bg-primary text-white py-3">الشريك</th>
                                <th class="text-center bg-primary text-white py-3">الدور</th>
                                <th class="text-center bg-primary text-white py-3">رأس المال</th>
                                <th class="text-center bg-primary text-white py-3">النسبة</th>
                                <th class="text-center bg-primary text-white py-3">الأرباح</th>
                                <th class="text-center bg-primary text-white py-3">المتاح للسحب</th>
                                <th class="text-center bg-primary text-white py-3">التقارير</th>
                                <th class="text-center bg-primary text-white py-3">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partner as $item)
                                @php
                                    $userCreationMonth = Carbon::parse($item->created_at)->month;
                                    $totalEarnMoney = 0;
                                    $companyCanCashWithdraw = 0;
                                    $userShare = $item->partnerInfo?->sum('money') ?? 0;

                                    for ($month = 1; $month <= 12; $month++) {
                                        if ($month >= $userCreationMonth) {
                                            $monthlyDeposits = $containers
                                                ->filter(
                                                    fn($container) => Carbon::parse($container->created_at)->month ==
                                                        $month,
                                                )
                                                ->sum('price');

                                            $rent_price = $rentOffices->map(function ($office) use ($month) {
                                                return $office->employeedaily
                                                    ->filter(
                                                        fn($daily) => Carbon::parse($daily->created_at)->month ==
                                                            $month,
                                                    )
                                                    ->where('type', 'withdraw')
                                                    ->sum('price');
                                            });
                                            $totalRentPriceFromCurrentMonth = $rent_price->sum();

                                            $employeeSum = $employees->sum(
                                                fn($employee) => $employee->employeedaily
                                                    ->where('type', 'withdraw')
                                                    ->filter(
                                                        fn($daily) => Carbon::parse($daily->created_at)->month ==
                                                            $month,
                                                    )
                                                    ->sum('price'),
                                            );

                                            $carsFiltered = $cars->sum(
                                                fn($car) => $car->daily
                                                    ->filter(
                                                        fn($item) => Carbon::parse($item->created_at)->month == $month,
                                                    )
                                                    ->sum('price'),
                                            );

                                            $othersSum = $others->sum(
                                                fn($other) => $other->employeedaily
                                                    ->where('type', 'withdraw')
                                                    ->filter(
                                                        fn($daily) => Carbon::parse($daily->created_at)->month ==
                                                            $month,
                                                    )
                                                    ->sum('price'),
                                            );

                                            $elbancherFiltered = collect($mergedArrayAlbancher)
                                                ->filter(
                                                    fn($item) => Carbon::parse($item['created_at'])->month == $month &&
                                                        $item['type'] == 'withdraw',
                                                )
                                                ->sum('price');

                                            $withdrawMonth =
                                                $carsFiltered +
                                                $employeeSum +
                                                $elbancherFiltered +
                                                $totalRentPriceFromCurrentMonth +
                                                $othersSum;

                                            $totalPrice = $monthlyDeposits - $withdrawMonth;

                                            $activePartners = $partner->filter(
                                                fn($partner) => $partner->is_active == 1 &&
                                                    Carbon::parse($partner->created_at)->month <= $month,
                                            );

                                            $totalActivePartnerSum = $activePartners->sum(
                                                fn($partner) => $partner->partnerInfo->sum('money'),
                                            );

                                            $monthlyProfit = 0;

                                            if ($totalActivePartnerSum > 0) {
                                                $partnerSum = ($userShare / $totalActivePartnerSum) * 100;
                                                $monthlyProfit = ($totalPrice * $partnerSum) / 100;
                                            } else {
                                                $partnerSum = 100;
                                                $monthlyProfit = $totalPrice;
                                            }

                                            // حساب الأرباح والنفقات منذ انضمام الشريك
                                            $withdrawCashSum = $dailyWithdraw
                                                ->filter(
                                                    fn($transaction) => Carbon::parse($transaction->created_at) >=
                                                        Carbon::parse($item->created_at),
                                                )
                                                ->sum('price');

                                            $depositCash = collect();
                                            foreach ($clients as $client) {
                                                if ($client->clientdaily) {
                                                    $depositCash = $depositCash->merge(
                                                        $client->clientdaily->where('type', 'deposit'),
                                                    );
                                                }
                                            }

                                            $totalDepositSinceJoined = $depositCash
                                                ->filter(
                                                    fn($transaction) => Carbon::parse($transaction->created_at) >=
                                                        Carbon::parse($item->created_at),
                                                )
                                                ->sum('price');

                                            $partnerCashCanWithdraw =
                                                (($totalDepositSinceJoined - $withdrawCashSum) * $partnerSum) / 100;

                                            ////////////////////////

                                            $partnerWithdraw = $item->partnerdaily
                                                ->where('type', 'partner_withdraw')
                                                ->sum('price');

                                            $rateBuy =
                                                ($allSellAndBuy->where('type', 'buy')->sum('price') * $partnerSum) /
                                                100;
                                            $rateSellFromHeadMony =
                                                ($allSellAndBuy->where('type', 'sell_from_head_mony')->sum('price') *
                                                    $partnerSum) /
                                                100;
                                            $rateSell =
                                                ($allSellAndBuy->where('type', 'sell')->sum('price') * $partnerSum) /
                                                100;

                                            $Profits_from_buying_and_selling = $rateSell - $rateBuy;

                                            $companyHeadMoney =
                                                $userShare -
                                                $rateSellFromHeadMony -
                                                $Profits_from_buying_and_selling +
                                                ($sumAllSellTransaction * $partnerSum) / 100;

                                            $totalEarnMoney += $monthlyProfit;
                                        }
                                    }
                                @endphp

                                <tr class="{{ $item->is_active ? '' : 'table-secondary' }}">
                                    <!-- ID -->
                                    <td class="text-center fw-bold">{{ $item->id }}</td>

                                    <!-- Partner Info -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item?->userinfo?->image == null ? 'https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg' : asset('storage/' . $item->userinfo->image) }}"
                                                class="rounded-circle me-3" width="50" height="50"
                                                alt="{{ $item->name }}">
                                            <div>
                                                <a href="{{ route('profileSettings', $item->id) }}"
                                                    class="fw-bold text-dark">
                                                    {{ $item->name }}
                                                </a>
                                                <div class="text-muted small">{{ $item->phone }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role -->
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->role == 'company' ? 'bg-indigo' : 'bg-info' }} rounded-pill p-2">
                                            {{ $item->role == 'company' ? 'الشركة' : 'شريك' }}
                                        </span>
                                    </td>

                                    <!-- Capital -->
                                    <td class="text-center fw-bold text-success">
                                        ر.س{{ number_format($companyHeadMoney, 2) }}
                                    </td>

                                    <!-- Percentage -->
                                    <td class="text-center">
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-{{ $item->is_active ? 'primary' : 'secondary' }}"
                                                role="progressbar" style="width: {{ $item->is_active ? $partnerSum : 0 }}%"
                                                aria-valuenow="{{ $item->is_active ? $partnerSum : 0 }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $item->is_active ? $partnerSum : 0 }}%
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Profits -->
                                    <td class="text-center fw-bold text-warning">
                                        {{ $item->is_active == 1 ? number_format($totalEarnMoney, 2) : 0 }} ر.س
                                    </td>

                                    <!-- Available -->
                                    <td class="text-center fw-bold text-primary">
                                        {{ $item->is_active == 1 ? number_format($totalEarnMoney - $partnerWithdraw, 2) : 0 }}
                                        ر.س
                                    </td>

                                    <!-- Reports -->
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                                id="reportsDropdown{{ $item->id }}" data-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="reportsDropdown{{ $item->id }}">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('partnerYearStatement', $item->id) }}">
                                                        <i class="fas fa-calendar-alt me-2"></i>كشف سنوي
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('partnerStatement', $item->id) }}">
                                                        <i class="fas fa-calendar-day me-2"></i>كشف شهري
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-outline-secondary" data-toggle="modal"
                                                data-target="#editModal{{ $item->id }}" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Status Button -->
                                            @if ($item->role == 'partner')
                                                <form action="{{ route('partnerinActive', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $item->is_active == 1 ? 'btn-success' : 'btn-danger' }}"
                                                        title="{{ $item->is_active == 1 ? 'مفعل' : 'غير مفعل' }}">
                                                        <i
                                                            class="fas {{ $item->is_active == 1 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">تعديل بيانات
                                                    الشريك</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('partnerUpdate', $item->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">الاسم</label>
                                                            <input type="text" name="name"
                                                                value="{{ $item->name }}" class="form-control"
                                                                placeholder="الاسم" required />
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">رقم الإقامة</label>
                                                            <input type="number"
                                                                value="{{ $item->userinfo->number_residence }}"
                                                                name="number_residence" class="form-control"
                                                                placeholder="رقم الاقامة" />
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">رقم الجوال</label>
                                                            <input type="tel" value="{{ $item->phone }}"
                                                                name="phone" class="form-control"
                                                                placeholder="رقم الجوال" required />
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">كلمة السر</label>
                                                            <input type="number" name="password" class="form-control"
                                                                placeholder="كلمة السر" />
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">الصورة الشخصية</label>
                                                            <input type="file" name="image" class="form-control"
                                                                accept="image/*" />
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">الدور</label>
                                                            <select class="form-control" name="role">
                                                                <option value="partner"
                                                                    {{ $item->role == 'partner' ? 'selected' : '' }}>شريك
                                                                </option>
                                                                @if ($partner->where('role', 'company')->count() == 0)
                                                                    <option value="company"
                                                                        {{ $item->role == 'company' ? 'selected' : '' }}>
                                                                        الشركة</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-secondary me-2"
                                                            data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-primary">حفظ
                                                            التغييرات</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Partner Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addEmployeeModalLabel">إضافة شريك جديد</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('partnerStore') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الاسم</label>
                                <input type="text" name="name" required class="form-control"
                                    placeholder="الاسم" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رأس المال</label>
                                <input type="number" name="money" class="form-control"
                                    placeholder="راس مال الشريك" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الإقامة</label>
                                <input type="number" name="number_residence" class="form-control"
                                    placeholder="رقم الاقامة" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رقم الجوال</label>
                                <input type="tel" required name="phone" class="form-control"
                                    placeholder="رقم الجوال" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الصورة الشخصية</label>
                                <input type="file" name="image" class="form-control" accept="image/*" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">كلمة السر</label>
                                <input type="number" required name="password" class="form-control"
                                    placeholder="كلمة السر" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الدور</label>
                                <select class="form-control" name="role">
                                    <option value="partner">شريك</option>
                                    @if ($partner->where('role', 'company')->count() == 0)
                                        <option value="company">الشركة</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Capital Modal -->
    <div class="modal fade" id="addHeadMoney" tabindex="-1" role="dialog" aria-labelledby="addHeadMoneylLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addHeadMoneylLabel">إضافة رأس مال</h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updateHeadMoney') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">المبلغ</label>
                                <input type="number" name="money" class="form-control" placeholder="راس مال" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الشريك</label>
                                <select class="form-control" name="id">
                                    @foreach ($partner as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .header {
                background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            }

            .table-hover tbody tr:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .progress {
                border-radius: 10px;
                background-color: #f0f0f0;
            }

            .progress-bar {
                border-radius: 10px;
                font-weight: bold;
            }

            .bg-indigo {
                background-color: #6610f2;
            }

            @media (max-width: 768px) {
                .table-responsive {
                    display: block;
                    width: 100%;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                .action-buttons .btn {
                    width: 100%;
                    margin-bottom: 10px;
                }
            }
        </style>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stop
