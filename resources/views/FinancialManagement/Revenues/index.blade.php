@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>كشف حسابات العملاء</h3>
                    <div>
                        <!-- زر إظهار/إخفاء العملاء بدون حركة باستخدام collapse -->
                        <button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target=".no-activity"
                            aria-expanded="true" aria-controls="inactiveRows">
                            <i class="fas fa-eye-slash me-1"></i> إظهار/إخفاء العملاء بدون حركة
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">العميل</th>
                                <th width="15%">الحاويات</th>
                                <th width="15%">المطلوب</th>
                                <th width="15%">الحالة</th>
                                <th width="30%">خيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr data-container-count="{{ $containersCount[$user->id] }}"
                                    data-revenue="{{ $user->remaining_revenue }}" {{-- إذا كان بدون حركة (لا حاويات ولا ديون) نضيف collapse show --}}
                                    class="{{ $containersCount[$user->id] == 0 && $user->remaining_revenue == 0 ? 'no-activity collapse show' : '' }}">
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge text-white bg-primary rounded-pill">
                                            {{ $containersCount[$user->id] }}
                                        </span>
                                    </td>
                                    <td class="{{ $user->remaining_revenue > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                        {{ number_format($user->remaining_revenue, 2) }} ر.س
                                    </td>
                                    <td>
                                        @if ($user->remaining_revenue == 0)
                                            <span class="badge bg-success text-white">مسدد</span>
                                        @else
                                            <span class="badge bg-warning text-white">مدين</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            {{-- رابط الكشف الشهري --}}
                                            <a href="{{ route('getAccountStatement', $user->id) }}"
                                                class="btn btn-outline-primary" title="كشف شهري">
                                                <i class="fas fa-calendar-alt me-1"></i> شهري
                                            </a>
                                            {{-- رابط الكشف السنوي --}}
                                            <a href="{{ route('getAccountYears', $user->id) }}"
                                                class="btn btn-outline-success" title="كشف سنوي">
                                                <i class="fas fa-calendar me-1"></i> سنوي
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-active">
                            <tr>
                                <th colspan="2" class="text-end">المجموع:</th>
                                <th class="text-primary">{{ $totalContainers }}</th>
                                <th colspan="2" class="text-danger">{{ number_format($totalRemainingRevenue, 2) }} ر.س
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="legend-color bg-success me-2" style="width: 15px; height: 15px;"></div>
                            <small>عملاء مسددين</small>

                            <div class="legend-color bg-warning mx-2" style="width: 15px; height: 15px;"></div>
                            <small>عملاء مدينين</small>

                            <div class="legend-color bg-light mx-2"
                                style="width: 15px; height: 15px; border: 1px solid #ddd;"></div>
                            <small>بدون حركة</small>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">آخر تحديث: {{ now()->format('Y-m-d H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .no-activity {
            /* عند collapse سيصبح display:none تلقائياً */
            background-color: #f8f9fa;
        }

        .legend-color {
            display: inline-block;
            vertical-align: middle;
        }

        .client-status-badge {
            font-size: 1rem;
            font-weight: bold;
        }

        .table-hover tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }
    </style>
    {{-- تمت إزالة كتلة <script> بالكامل لأنها لم تعد ضرورية --}}
@stop
