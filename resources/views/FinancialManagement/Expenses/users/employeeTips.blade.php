@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4" dir="rtl">
        <!-- Header Section -->
        <x-statement-header company-name="شركة الأمجاد المتعددة" company-address="الرياض، المملكة العربية السعودية"
            company-phone="+966 547777121" company-email="alamjad.multi@gmail.com" title="كشف حساب"
            client-name="{{ $user->name }}" month-name="{{ $monthName }}" />

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <x-stat-card color="bg-info" icon="fas fa-truck" title="إجمالي التربات" :count="$allTrips" />
            <x-stat-card color="bg-warning" icon="fas fa-exclamation-triangle" title="حاويات فارغة" :count="$tipsEmpty->count()" />
        </div>

        <!-- Main Table -->
        <div class="card shadow-lg border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                {{-- <th width="10%" class="text-center">حالة التعديل</th> --}}
                                <th width="15%" class="text-center">نوع الترب</th>
                                <th width="10%" class="text-center">السعر</th>
                                <th width="10%" class="text-center">حجم الحاوية</th>
                                <th width="15%" class="text-center">رقم الحاوية</th>
                                <th width="20%" class="text-center">اسم العميل</th>
                                <th width="15%" class="text-center">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currentMonthContainers->whereNotNull('tips') as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    {{-- <td class="text-center">
                                        @if ($item->created_at != $item->updated_at)
                                            <span class="badge bg-warning text-dark">معدل</span>
                                        @else
                                            <span class="badge bg-secondary">جديد</span>
                                        @endif
                                    </td> --}}
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            محملة
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-success">{{ $item->tips ?? 'N/A' }}
                                        <small>ر.س</small>
                                    </td>
                                    <td class="text-center">{{ $item->size ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $item->number ?? 'N/A' }}</td>
                                    <td>{{ $item->customs->importer_name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($item->transfer_date ?? $item->created_at)->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($tipsEmpty as $index => $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $currentMonthContainers->whereNotNull('tips')->count() + $index + 1 }}</td>
                                    {{-- <td class="text-center">
                                        @if ($item->created_at != $item->updated_at)
                                            <span class="badge bg-warning text-dark">معدل</span>
                                        @else
                                            <span class="badge bg-secondary">جديد</span>
                                        @endif
                                    </td> --}}
                                    <td class="text-center">
                                        <span class="badge bg-warning">
                                            فارغة
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-success">{{ $item->price ?? 'N/A' }}
                                        <small>ر.س</small>
                                    </td>
                                    <td class="text-center">{{ $item->container->size ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $item->container->number ?? 'N/A' }}</td>
                                    <td>{{ $item->container->customs->importer_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="8" class="text-center fw-bold py-2">
                                    إجمالي عدد الحاويات:
                                    {{ $currentMonthContainers->whereNotNull('tips')->count() + $tipsEmpty->count() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table thead th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            color: #fff;
        }
    </style>
@endsection
