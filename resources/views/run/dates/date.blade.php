@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <form action="{{ route('dates') }}" class="row align-items-center" method="GET">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control border-end-0"
                                        placeholder="ابحث برقم الحاوية أو العميل..." value="{{ request('query') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <!-- Button trigger PDF modal -->
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                    data-target="#pdfAnalysisModal">
                                    <i class="fas fa-file-pdf"></i> تحليل ملف PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body py-2 text-center">
                        <h5 class="mb-0">إجمالي الحاويات: {{ count($container) + count($containerPort) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Analysis Modal -->
        <div class="modal fade" id="pdfAnalysisModal" tabindex="-1" role="dialog" aria-labelledby="pdfAnalysisModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="pdfAnalysisModalLabel">تحليل ملف PDF بالذكاء الاصطناعي</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="pdfAnalysisForm" action="{{ route('analyze.pdf') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="pdfFile" class="form-label">اختر ملف PDF لتحليله:</label>
                                <input type="file" class="form-control" id="pdfFile" name="pdf_file" accept=".pdf"
                                    required>
                                <small class="text-muted">الحد الأقصى لحجم الملف: 5MB</small>
                            </div>
                            <div class="mb-3">
                                <label for="analysisType" class="form-label">نوع التحليل:</label>
                                <select class="form-select" id="analysisType" name="analysis_type" required>
                                    <option value="container_info">استخراج معلومات الحاويات</option>
                                    <option value="customs_data">استخراج بيانات التخليص الجمركي</option>
                                    <option value="shipping_details">استخراج تفاصيل الشحن</option>
                                </select>
                            </div>
                            <div id="analysisPreview" class="d-none p-3 bg-light rounded">
                                <h6>نتيجة التحليل:</h6>
                                <div id="analysisResults" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-brain"></i> بدء التحليل
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Waiting Containers Card -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>حاويات في انتظار التحميل
                        </h5>
                        <span class="badge bg-white text-dark rounded-pill">{{ count($container) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light" style="position: sticky; top: 0;">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">الحجم</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($container as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $item->id }}</td>
                                            <td class="text-center align-middle fw-bold">{{ $item->number }}</td>
                                            <td class="text-center align-middle">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-primary">{{ $item->size }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <!-- Rent/Unrent Button -->
                                                    <form action="{{ route('ContainerRentStatus', $item->id) }}"
                                                        method="GET" class="d-inline">
                                                        <input name="status" value="{{ $item->status }}" hidden />
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $item->status == 'wait' ? 'btn-outline-danger' : 'btn-outline-warning' }}"
                                                            title="{{ $item->status == 'wait' ? 'تأجير الحاوية' : 'إلغاء التأجير' }}">
                                                            <i
                                                                class="fas {{ $item->status == 'wait' ? 'fa-hand-holding-usd' : 'fa-ban' }}"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Storage Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        data-toggle="modal"
                                                        data-target="#storageContainer{{ $item->id }}"
                                                        title="تخزين">
                                                        <i class="fas fa-warehouse"></i>
                                                    </button>

                                                    @if (!auth()->user()?->userinfo?->job_title == 'operator')
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            data-toggle="modal"
                                                            data-target="#deleteModal{{ $item->id }}" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Storage Modal -->
                                                <div class="modal fade" id="storageContainer{{ $item->id }}"
                                                    tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title">تخزين الحاوية {{ $item->number }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('container.storage', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">السائق</label>
                                                                        <select class="form-select" name="driver"
                                                                            required>
                                                                            <option value="">اختر السائق</option>
                                                                            @foreach ($driver as $driverItem)
                                                                                <option value="{{ $driverItem->id }}">
                                                                                    {{ $driverItem->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">السيارة</label>
                                                                        <select class="form-select" name="car"
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
                                                                    <div class="mb-3">
                                                                        <label class="form-label">الحوافز</label>
                                                                        <input type="number" class="form-control"
                                                                            name="tips" placeholder="مبلغ الحوافز"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">تاريخ النقل</label>
                                                                        <input type="date" class="form-control"
                                                                            name="transfer_date">
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
                                                    <div class="modal fade" id="deleteModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title">حذف الحاوية</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <div class="mb-4">
                                                                        <i
                                                                            class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                                        <h5>هل أنت متأكد من حذف الحاوية؟</h5>
                                                                        <p>رقم الحاوية:
                                                                            <strong>{{ $item->number }}</strong></p>
                                                                        <p>العميل:
                                                                            <strong>{{ $item->customs->importer_name }}</strong>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">إلغاء</button>
                                                                    <form
                                                                        action="{{ route('deleteContainer', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-danger">تأكيد الحذف</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transported Containers Card -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-truck-moving me-2"></i>الحاويات المحملة
                        </h5>
                        <span class="badge bg-white text-success rounded-pill">{{ count($containerPort) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light" style="position: sticky; top: 0;">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">رقم الحاوية</th>
                                        <th class="text-center">العميل</th>
                                        <th class="text-center">التاريخ</th>
                                        <th class="text-center">الحجم</th>
                                        <th class="text-center">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($containerPort as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $item->id }}</td>
                                            <td class="text-center align-middle fw-bold">{{ $item->number }}</td>
                                            <td class="text-center align-middle">{{ $item->customs->importer_name }}</td>
                                            <td class="text-center align-middle">{{ $item->transfer_date }}</td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-primary">{{ $item->size }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal{{ $item->id }}"
                                                    title="إلغاء التحميل">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Cancel Transport Modal -->
                                        <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">إلغاء تحميل الحاوية</h5>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('updateContainer', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status"
                                                            value="{{ $item->rent_id ? 'rent' : 'wait' }}">
                                                        <div class="modal-body text-center">
                                                            <div class="mb-4">
                                                                <i
                                                                    class="fas fa-question-circle fa-3x text-warning mb-3"></i>
                                                                <h5>هل تريد إلغاء تحميل هذه الحاوية؟</h5>
                                                                <p>رقم الحاوية: <strong>{{ $item->number }}</strong></p>
                                                                <p>العميل:
                                                                    <strong>{{ $item->customs->importer_name }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-danger">تأكيد
                                                                الإلغاء</button>
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

    @push('scripts')
        <script>
            // PDF Analysis Form Submission
            $('#pdfAnalysisForm').on('submit', function(e) {
                e.preventDefault();

                // Show loading state
                $('#analysisResults').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">جاري تحليل الملف، الرجاء الانتظار...</p>
            </div>
        `);
                $('#analysisPreview').removeClass('d-none');

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#analysisResults').html(`
                        <div class="alert alert-success">
                            <h6>نتيجة التحليل:</h6>
                            <pre>${JSON.stringify(response.data, null, 2)}</pre>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="importAnalysisResults()">
                            <i class="fas fa-file-import"></i> استيراد النتائج
                        </button>
                    `);
                        } else {
                            $('#analysisResults').html(`
                        <div class="alert alert-danger">
                            <h6>خطأ في التحليل:</h6>
                            <p>${response.message}</p>
                        </div>
                    `);
                        }
                    },
                    error: function(xhr) {
                        $('#analysisResults').html(`
                    <div class="alert alert-danger">
                        <h6>حدث خطأ:</h6>
                        <p>${xhr.responseJSON?.message || 'حدث خطأ أثناء معالجة الملف'}</p>
                    </div>
                `);
                    }
                });
            });

            function importAnalysisResults() {
                // Implement your import logic here
                alert('سيتم تنفيذ استيراد النتائج هنا');
            }
        </script>
    @endpush

    <style>
        .table-container {
            scrollbar-width: thin;
            scrollbar-color: #adb5bd #f8f9fa;
        }

        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: #adb5bd;
            border-radius: 10px;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            font-weight: 600;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.35em 0.65em;
        }

        .form-select,
        .form-control {
            border-radius: 5px;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>

@stop
