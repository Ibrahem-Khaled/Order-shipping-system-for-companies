@extends('layouts.default')

@section('content')
    <div class="container-fluid py-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h2 class="h5 mb-0 text-center fw-bold">
                    <i class="fas fa-file-alt me-2"></i>عرض بيانات البيان الجمركي
                </h2>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 bg-primary text-white">تاريخ أرضية الجمارك</th>
                                <th class="py-3 bg-primary text-white">المكتب</th>
                                <th class="py-3 bg-primary text-white">العميل</th>
                                <th class="py-3 bg-primary text-white">الوزن</th>
                                <th class="py-3 bg-primary text-white text-center">الحاويات</th>
                                <th class="py-3 bg-primary text-white">رقم البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statements as $statement)
                                <tr class="align-middle">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <x-countdown-timer :id="$statement->id" :transfer_date="$statement->expire_customs" :date_empty="$statement->expire_customs"
                                                :type="'custom'" />
                                            <x-edit-modal :id="$statement->id" :date_empty="$statement->expire_customs" :type="'custom'" />
                                        </div>
                                    </td>
                                    <td class="py-3">{{ $statement->client->name }}</td>
                                    <td class="py-3">{{ $statement->importer_name }}</td>
                                    <td class="py-3">{{ $statement->customs_weight }} كجم</td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center gap-3">
                                            <div class="text-center">
                                                <div class="badge bg-success rounded-pill px-3 py-1">
                                                    {{ $statement->container->where('status', 'transport')->count() }}
                                                </div>
                                                <div class="small text-muted mt-1">محجوزة</div>
                                            </div>
                                            --
                                            <div class="text-center">
                                                <div class="badge bg-warning rounded-pill px-3 py-1 text-dark">
                                                    {{ $statement->container->where('status', 'wait')->count() }}
                                                </div>
                                                <div class="small text-muted mt-1">منتظرة</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('reservations.show', $statement->id) }}"
                                            class="text-primary fw-bold">
                                            {{ $statement->statement_number }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        لا توجد بيانات متاحة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        }

        @media (max-width: 768px) {
            .table-responsive {
                border: 0;
            }

            .table-responsive thead {
                display: none;
            }

            .table-responsive tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
            }

            .table-responsive td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border-bottom: 1px solid #dee2e6;
            }

            .table-responsive td::before {
                content: attr(data-label);
                font-weight: bold;
                margin-right: 1rem;
            }

            .table-responsive td:last-child {
                border-bottom: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // جعل الجدول متجاوبًا على الشاشات الصغيرة
        document.addEventListener('DOMContentLoaded', function() {
            const cells = document.querySelectorAll('tbody td');
            const headers = document.querySelectorAll('thead th');

            if (window.innerWidth <= 768) {
                cells.forEach((cell, index) => {
                    const headerIndex = index % headers.length;
                    cell.setAttribute('data-label', headers[headerIndex].textContent.trim());
                });
            }
        });
    </script>
@endpush
