@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <!-- Search Form -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('dates') }}" class="row align-items-center" method="GET">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control border-primary"
                                placeholder="ابحث برقم الحاوية أو العميل..." aria-label="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-left">
                        <span class="badge badge-pill badge-info p-2">
                            <i class="fas fa-boxes"></i> إجمالي الحاويات: {{ count($container) + count($containerPort) }}
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="containerTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="waiting-tab" data-toggle="tab" href="#waiting" role="tab">
                    <i class="fas fa-clock"></i> في انتظار التحميل ({{ count($container) }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="loaded-tab" data-toggle="tab" href="#loaded" role="tab">
                    <i class="fas fa-truck-loading"></i> المحملة ({{ count($containerPort) }})
                </a>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="containerTabsContent">
            <!-- Waiting Containers Tab -->
            <div class="tab-pane fade show active" id="waiting" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-clock"></i> حاويات في انتظار التحميل
                        </h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-white"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#pdfUploadModal">
                                    <i class="fas fa-file-pdf"></i> رفع ملف PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-gradient-primary text-white">
                                    <tr>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">حجم الحاوية</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">مكتب التخليص</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($container as $item)
                                        <tr class="align-middle">
                                            <td class="text-center font-weight-bold">{{ $item->number }}</td>
                                            <td class="text-center">{{ $item->size }}</td>
                                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center">{{ $item->client->name }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-{{ $item->status == 'wait' ? 'warning' : 'danger' }}">
                                                    {{ $item->status == 'wait' ? 'انتظار' : 'إيجار' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <!-- Change Status Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-toggle="modal"
                                                        data-target="#changeStatusModal{{ $item->id }}">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>

                                                    <!-- Rent/Unrent Button -->
                                                    <form action="{{ route('ContainerRentStatus', $item->id) }}"
                                                        method="GET" class="d-inline">
                                                        <input name="status" value="{{ $item->status }}" hidden />
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $item->status == 'wait' ? 'danger' : 'warning' }}">
                                                            <i
                                                                class="fas {{ $item->status == 'wait' ? 'fa-hand-holding-usd' : 'fa-times-circle' }}"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Storage Button -->
                                                    <button type="button" class="btn btn-sm btn-success"
                                                        data-toggle="modal"
                                                        data-target="#storageContainer{{ $item->id }}">
                                                        <i class="fas fa-warehouse"></i>
                                                    </button>

                                                    <!-- Delete Button (for non-operators) -->
                                                    @if (!auth()->user()?->userinfo?->job_title == 'operator')
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            data-toggle="modal"
                                                            data-target="#deleteModal{{ $item->id }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Change Status Modal -->
                                        <div class="modal fade" id="changeStatusModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="changeStatusModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="changeStatusModalLabel{{ $item->id }}">
                                                            تغيير حالة الحاوية {{ $item->number }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('updateContainer', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="status" value="transport">

                                                            <div class="form-group">
                                                                <label>تاريخ النقل</label>
                                                                <input type="date" class="form-control"
                                                                    name="transfer_date" required>
                                                            </div>

                                                            @if ($item->status == 'rent')
                                                                <div class="form-group">
                                                                    <label>شركة التأجير</label>
                                                                    <select class="form-control" name="rent_id" required>
                                                                        <option value="">اختر شركة التأجير</option>
                                                                        @foreach ($rents as $rentItem)
                                                                            <option value="{{ $rentItem->id }}">
                                                                                {{ $rentItem->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <label>السيارة</label>
                                                                    <select class="form-control" name="car" required>
                                                                        <option value="">اختر السيارة</option>
                                                                        @foreach ($cars as $car)
                                                                            <option value="{{ $car->id }}">
                                                                                {{ $car->driver?->name }} -
                                                                                {{ $car->number }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>السائق</label>
                                                                    <select class="form-control" name="driver" required>
                                                                        <option value="">اختر السائق</option>
                                                                        @foreach ($driver as $driverItem)
                                                                            <option value="{{ $driverItem->id }}">
                                                                                {{ $driverItem->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>موقع الحاوية</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        name="direction" placeholder="موقع الحاوية">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">مثال:
                                                                            ميناء، مستودع، إلخ</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-primary">تأكيد
                                                                التغيير</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Storage Modal -->
                                        <div class="modal fade" id="storageContainer{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="storageContainerLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title"
                                                            id="storageContainerLabel{{ $item->id }}">
                                                            <i class="fas fa-warehouse"></i> تفاصيل التخزين
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('container.storage', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>السائق</label>
                                                                        <select class="form-control" name="driver"
                                                                            required>
                                                                            <option value="">اختر السائق</option>
                                                                            @foreach ($driver as $driverItem)
                                                                                <option value="{{ $driverItem->id }}">
                                                                                    {{ $driverItem->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>السيارة</label>
                                                                        <select class="form-control" name="car"
                                                                            required>
                                                                            <option value="">اختر السيارة</option>
                                                                            @foreach ($cars as $car)
                                                                                <option value="{{ $car->id }}">
                                                                                    {{ $car->driver?->name }} -
                                                                                    {{ $car->number }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>الحوافز</label>
                                                                        <div class="input-group">
                                                                            <input type="number" class="form-control"
                                                                                name="tips" placeholder="مبلغ الحوافز"
                                                                                required>
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">دينار</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>تاريخ النقل</label>
                                                                        <input type="date" class="form-control"
                                                                            name="transfer_date" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-success">تأكيد
                                                                التخزين</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        @if (!auth()->user()?->userinfo?->job_title == 'operator')
                                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel{{ $item->id }}">
                                                                <i class="fas fa-exclamation-triangle"></i> تأكيد الحذف
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <h5>هل أنت متأكد من حذف الحاوية؟</h5>
                                                            <p class="text-muted">الحاوية رقم: {{ $item->number }}<br>
                                                                العميل: {{ $item->customs->importer_name }}</p>
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-info-circle"></i> سيتم حذف جميع البيانات
                                                                المرتبطة بهذه الحاوية
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <form action="{{ route('deleteContainer', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-trash-alt"></i> تأكيد الحذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loaded Containers Tab -->
            <div class="tab-pane fade" id="loaded" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-gradient-success">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-truck-loading"></i> الحاويات المحملة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-gradient-success text-white">
                                    <tr>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">حجم الحاوية</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">مكتب التخليص</th>
                                        <th class="text-center">تاريخ التحميل</th>
                                        <th class="text-center">وسيلة النقل</th>
                                        <th class="text-center">الحالة</th>
                                        <th class="text-center">موقع الحاوية</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($containerPort as $item)
                                        <tr class="align-middle">
                                            <td class="text-center font-weight-bold">{{ $item->number }}</td>
                                            <td class="text-center">{{ $item->size }}</td>
                                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center">{{ $item->client->name }}</td>
                                            <td class="text-center">{{ $item->transfer_date }}</td>
                                            <td class="text-center">
                                                @if ($item->rent_id == null)
                                                    {{ $item->car->number ?? 'غير محدد' }}
                                                    ({{ $item->driver->name ?? 'غير محدد' }})
                                                @else
                                                    {{ $item->rent->name }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-success">محملة</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="direction-label"
                                                    data-id="{{ $item->id }}">{{ $item->direction ?? 'غير محدد' }}</span>
                                                <input type="text"
                                                    class="direction-input form-control form-control-sm d-none text-center"
                                                    value="{{ $item->direction }}" data-id="{{ $item->id }}">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#confirmationModal{{ $item->id }}">
                                                    <i class="fas fa-undo"></i> إلغاء التحميل
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Unload Confirmation Modal -->
                                        <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="confirmationModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title"
                                                            id="confirmationModalLabel{{ $item->id }}">
                                                            <i class="fas fa-exclamation-circle"></i> تأكيد إلغاء التحميل
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center mb-4">
                                                            <i class="fas fa-question-circle fa-3x text-warning"></i>
                                                        </div>
                                                        <h5 class="text-center">هل تريد إلغاء تحميل الحاوية التالية؟</h5>
                                                        <div class="container-details mt-4 p-3 bg-light rounded">
                                                            <p class="mb-1"><strong>رقم الحاوية:</strong>
                                                                {{ $item->number }}</p>
                                                            <p class="mb-1"><strong>العميل:</strong>
                                                                {{ $item->customs->importer_name }}</p>
                                                            <p class="mb-1"><strong>تاريخ التحميل:</strong>
                                                                {{ $item->transfer_date }}</p>
                                                            <p class="mb-0"><strong>وسيلة النقل:</strong>
                                                                @if ($item->rent_id == null)
                                                                    {{ $item->car->number ?? 'غير محدد' }}
                                                                    ({{ $item->driver->name ?? 'غير محدد' }})
                                                                @else
                                                                    {{ $item->rent->name }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('updateContainer', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status"
                                                            value="{{ $item->rent_id ? 'rent' : 'wait' }}">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-warning">
                                                                <i class="fas fa-check-circle"></i> تأكيد الإلغاء
                                                            </button>
                                                        </div>
                                                    </form>
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
        </div>
    </div>

    <!-- PDF Upload Modal -->
    <div class="modal fade" id="pdfUploadModal" tabindex="-1" role="dialog" aria-labelledby="pdfUploadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="pdfUploadModalLabel">
                        <i class="fas fa-file-pdf"></i> رفع ملف PDF لتحليل الحاويات
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('analyze.pdf') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>اختر ملف PDF</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pdfFile" name="pdf_file"
                                    accept=".pdf" required>
                                <label class="custom-file-label" for="pdfFile">اختر ملف...</label>
                            </div>
                            <small class="form-text text-muted">يجب أن يكون الملف بصيغة PDF ويحتوي على بيانات
                                الحاويات</small>
                        </div>

                        <div class="alert alert-info mt-3">
                            <h5 class="alert-heading"><i class="fas fa-robot"></i> ميزة الذكاء الاصطناعي</h5>
                            <p>سيقوم نظامنا بتحليل الملف تلقائياً واستخراج بيانات الحاويات باستخدام تقنيات الذكاء الاصطناعي
                                المتقدمة.</p>
                            <hr>
                            <p class="mb-0">البيانات التي يمكن استخراجها: أرقام الحاويات، الأحجام، العملاء، التواريخ،
                                وغيرها.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-upload"></i> رفع وتحليل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script for file input label -->
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("pdfFile").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
        }

        .bg-gradient-success {
            background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
        }

        .container-details {
            border-left: 4px solid #5e72e4;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(94, 114, 228, 0.1);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .badge {
            font-size: 0.85em;
            font-weight: 500;
            padding: 0.5em 0.8em;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            border: none;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #5e72e4;
            border-bottom: 3px solid #5e72e4;
            background-color: transparent;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = document.querySelectorAll('.direction-label');
            const inputs = document.querySelectorAll('.direction-input');

            labels.forEach(label => {
                label.addEventListener('click', function() {
                    label.classList.add('d-none');
                    const input = label.nextElementSibling;
                    input.classList.remove('d-none');
                    input.focus();
                });
            });

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    updateDirection(input);
                });

                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        updateDirection(input);
                    }
                });
            });

            function updateDirection(input) {
                const id = input.dataset.id;
                const newValue = input.value;

                fetch(`/system/direction/update/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            direction: newValue
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const label = input.previousElementSibling;
                            label.textContent = newValue || 'غير محدد';
                            label.classList.remove('d-none');
                            input.classList.add('d-none');
                        } else {
                            alert('حدث خطأ أثناء التحديث');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('تعذر الاتصال بالسيرفر');
                    });
            }
        });
    </script>
@endsection
