@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4">
        <!-- Quick Stats Section -->
        <div class="container-fluid mt-4">
            <div class="row align-items-center justify-content-between g-4">
                <x-stat-card title="إجمالي الحاويات" :count="$containerPort->total()" icon="fas fa-boxes" color="primary" />
                <x-stat-card title="إجمالي الحاويات الفارغة" :count="$done->count()" icon="fas fa-box-open" color="success" />
                <x-stat-card title="إجمالي الحاويات في التخزين" :count="$storageContainer->total()" icon="fas fa-warehouse" color="warning" />

            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="containersTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="loaded-tab" data-toggle="tab" data-target="#loaded" type="button"
                    role="tab">
                    <i class="fas fa-truck-loading me-2"></i> المحملة ({{ $containerPort->total() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="storage-tab" data-toggle="tab" data-target="#storage" type="button"
                    role="tab">
                    <i class="fas fa-warehouse me-2"></i> التخزين ({{ $storageContainer->total() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="empty-tab" data-toggle="tab" data-target="#empty" type="button"
                    role="tab">
                    <i class="fas fa-box-open me-2"></i> الفارغة
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="containersTabContent">
            <!-- Loaded Containers Tab -->
            <div class="tab-pane fade show active" id="loaded" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-truck-loading me-2"></i> الحاويات المحملة</h5>
                        <x-search-form />
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">موقع الحاوية</th>
                                        <th class="text-center">أرضية الفارغ</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">مكتب التخليص</th>
                                        <th class="text-center">الحجم</th>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">رقم البيان</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($containerPort as $item)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $item->direction ? 'info' : 'secondary' }}">
                                                    {{ $item->direction ?? 'غير محدد' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <x-countdown-timer :id="$item->id" :transfer_date="$item->transfer_date"
                                                        :date_empty="$item->date_empty" />
                                                    <x-edit-modal :id="$item->id" :date_empty="$item->date_empty" />
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center">{{ $item->client->name }}</td>
                                            <td class="text-center">
                                                <span class="">{{ $item->size }}</span>
                                            </td>
                                            <td class="text-center">{{ $item->number }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('showContainer', $item->customs->id) }}"
                                                    class="text-primary fw-bold">
                                                    {{ $item->customs->statement_number }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                @if ($item->is_rent == 1)
                                                    <form action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="done">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-primary rounded-pill px-3">
                                                            <i class="fas fa-check-circle me-1"></i> محملة
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3"
                                                        data-toggle="modal" data-target="#updateModal{{ $item->id }}">
                                                        <i class="fas fa-truck me-1"></i> محملة
                                                    </button>
                                                    <x-update-container-status :item="$item" :driver="$driver"
                                                        :cars="$cars" :rents="$rents" />

                                                    <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
                                                        data-toggle="modal"
                                                        data-target="#containerModal{{ $item->id }}">
                                                        <i class="fas fa-eye me-1"></i> عرض
                                                    </button>
                                                    <x-container-details :item="$item" />
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->id }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-center">
                            {{ $containerPort->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storage Containers Tab -->
            <div class="tab-pane fade" id="storage" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i> الحاويات التخزين</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">موقع الحاوية</th>
                                        <th class="text-center">تاريخ أرضية الفارغ</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">مكتب التخليص</th>
                                        <th class="text-center">الحجم</th>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">رقم البيان</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($storageContainer as $item)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $item->direction ? 'info' : 'secondary' }}">
                                                    {{ $item->direction ?? 'غير محدد' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <x-countdown-timer :id="$item->id" :transfer_date="$item->transfer_date"
                                                        :date_empty="$item->date_empty" />
                                                    <x-edit-modal :id="$item->id" :date_empty="$item->date_empty" />
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center">{{ $item->client->name }}</td>
                                            <td class="text-center">
                                                <span class="">{{ $item->size }}</span>
                                            </td>
                                            <td class="text-center">{{ $item->number }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('showContainer', $item->customs->id) }}"
                                                    class="text-primary fw-bold">
                                                    {{ $item->customs->statement_number }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-warning rounded-pill px-3"
                                                        data-toggle="modal"
                                                        data-target="#confirmationModal{{ $item->id }}">
                                                        <i class="fas fa-truck-loading me-1"></i> تحميل
                                                    </button>
                                                    <x-confirmation-modal :item="$item" :driver="$driver"
                                                        :cars="$cars" :rents="$rents" />

                                                    <form action="{{ route('ContainerRentStatus', $item->id) }}"
                                                        method="GET" class="d-inline">
                                                        <input name="status" value="{{ $item->status }}" hidden />
                                                        <input name="storage" value="storage" hidden />
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $item->status == 'storage' ? 'danger' : 'success' }} rounded-pill px-3">
                                                            <i
                                                                class="fas fa-{{ $item->status == 'storage' ? 'hand-holding-usd' : 'times-circle' }} me-1"></i>
                                                            {{ $item->status == 'storage' ? 'تأجير' : 'إلغاء التأجير' }}
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
                                                        data-toggle="modal"
                                                        data-target="#containerModal{{ $item->id }}">
                                                        <i class="fas fa-eye me-1"></i> عرض
                                                    </button>
                                                    <x-container-details :item="$item" />

                                                    <form action="{{ route('change.container.status', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <input name="status" value="wait" hidden />
                                                        <button type="submit"
                                                            class="btn btn-sm btn-secondary rounded-pill px-3">
                                                            <i class="fas fa-undo me-1"></i> إلغاء التخزين
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->id }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-center">
                            {{ $storageContainer->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Containers Tab -->
            <div class="tab-pane fade" id="empty" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-box-open me-2"></i> الحاويات الفارغة</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">مكتب التخليص</th>
                                        <th class="text-center">الحجم</th>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">رقم البيان</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($done as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center">{{ $item->client->name }}</td>
                                            <td class="text-center">
                                                <span class="">{{ $item->size }}</span>
                                            </td>
                                            <td class="text-center">{{ $item->number }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('showContainer', $item->customs->id) }}"
                                                    class="text-primary fw-bold">
                                                    {{ $item->customs->statement_number }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <form id="confirmationForm{{ $item->id }}"
                                                        action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="transport">
                                                        <button type="button"
                                                            class="btn btn-sm btn-warning rounded-pill px-3"
                                                            onclick="showConfirmation({{ $item->id }})">
                                                            <i class="fas fa-box me-1"></i> فارغ
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
                                                        data-toggle="modal"
                                                        data-target="#containerModal{{ $item->id }}">
                                                        <i class="fas fa-eye me-1"></i> عرض
                                                    </button>
                                                    <x-container-details :item="$item" />
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->id }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showConfirmation(itemId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم تغيير حالة الحاوية إلى فارغة",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، تأكيد',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("confirmationForm" + itemId).submit();
                }
            });
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .badge {
            font-weight: bold;
            color: #fff;
            padding: 0.35em 0.65em;
        }

        @media (max-width: 768px) {
            .nav-tabs .nav-link {
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            .table {
                font-size: 0.85rem;
            }
        }
    </style>
@endsection
