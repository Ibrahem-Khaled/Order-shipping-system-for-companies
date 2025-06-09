@extends('layouts.default')
@section('content')
    <div class="container-fluid py-4">
        @include('components.alerts')

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">إدارة الحاويات</h4>
                    <div class="d-flex">
                        <span class="badge bg-light text-dark me-2">الحاويات النشطة:
                            {{ $users->container->where('status', '!=', 'done')->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="120px" class="text-center">الاجراءات</th>
                                <th width="200px" class="text-center">موعد إخلاء الحاوية</th>
                                <th class="text-center">رقم البيان</th>
                                <th class="text-center">رقم الحاوية</th>
                                <th class="text-center">حالة الحاوية</th>
                                <th class="text-center">اسم العميل</th>
                                <th class="text-center">حجم الحاوية</th>
                                <th class="text-center">تاريخ الإدخال</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users->container->where('status', '!=', 'done') as $item)
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('deleteContainer', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذه الحاوية؟')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#containerModal{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">

                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        <a href="{{ route('showContainer', $item->customs->id) }}"
                                            class="text-primary fw-bold">
                                            {{ $item->customs->statement_number }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $item->number }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $item->status == 'wait' ? 'warning' : 'success' }}">
                                            {{ $item->status == 'wait' ? 'انتظار' : 'منقول' }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item->customs->importer_name }}</td>
                                    <td class="text-center text-white">
                                        <span class="badge bg-secondary">{{ $item->size }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->created_at->format('Y-m-d') }}</td>
                                </tr>

                                <!-- Details Modal -->
                                <x-container-details :item="$item" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($users->container->where('status', '!=', 'done')->isEmpty())
                    <div class="text-center py-5">
                        <img src="{{ asset('img/empty.svg') }}" alt="No containers" style="height: 150px;"
                            class="mb-4">
                        <h5 class="text-muted">لا توجد حاويات لعرضها</h5>
                        {{-- <a href="{{ route('addContainer') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>إضافة حاوية جديدة
                        </a> --}}
                    </div>
                @endif
            </div>

            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        عرض {{ $users->container->where('status', '!=', 'done')->count() }} حاوية
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .countdown-timer {
            background: linear-gradient(135deg, #cb0c9f, #7928ca);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .countdown-timer:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(203, 12, 159, 0.3);
        }

        .time-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px 8px;
        }

        .time-unit .number {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .time-unit .label {
            font-size: 0.65rem;
            opacity: 0.8;
        }

        .time-separator {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.7);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(203, 12, 159, 0.05);
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
        }

        .list-group-item {
            border-left: none;
            border-right: none;
        }

        .list-group-item:first-child {
            border-top: none;
        }
    </style>
@endpush
