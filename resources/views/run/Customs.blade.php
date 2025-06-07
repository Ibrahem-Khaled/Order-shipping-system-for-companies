@extends('layouts.default')

@section('styles')
    <style>
        /* تحسينات البانر التحذيري */
        .alert-banner {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #ffc107;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 193, 7, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        /* تحسينات الجدول */
        .table-hover tbody tr {
            transition: all 0.2s;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: scale(1.005);
        }
    </style>
@endsection

@section('content')
    <!-- بانر تحذيري مع إحصائيات -->
    @if ($getLastClintsFromthreeDays->count() > 0)
        <div class="alert alert-banner alert-warning mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="alert-heading mb-1"><i class="fas fa-exclamation-triangle me-2"></i> تنبيه!
                    </h4>
                    <p class="mb-0">تنبيه تم اضافة مكتب جديد من قبل الذكاء الاصطناعي معك 3 ايام قبل اختفاء الرسالة</p>
                </div>
                <div class="d-flex">
                    <div class="me-4 text-center">
                        <span class="badge bg-danger rounded-pill p-2 text-white">
                            <i class="fas fa-bell me-1"></i> {{ $getLastClintsFromthreeDays->count() }} مكاتب جديدة
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- بطاقات الإحصائيات -->
    <div class="row mb-4">
        <x-stat-card title="اجمالي المكاتب" :count="$totalActiveClients" icon="fas fa-building" color="primary" />
        <x-stat-card title="اجمالي المكاتب المحذوفة" :count="$totalDeletedClients" icon="fas fa-building" color="danger" />
        <x-stat-card title="اجمالي الحاويات" :count="$totalActiveContainers" icon="fas fa-boxes" color="primary" />
        <x-stat-card title="اجمالي البيانتات" :count="$totalActiveCustoms" icon="fas fa-boxes" color="primary" />

    </div>

    <!-- نموذج البحث -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('getOfices') }}" class="row align-items-center g-3" method="GET">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="office" class="form-control form-control-lg"
                            placeholder="ابحث عن مكتب...">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-search me-2"></i> بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('components.alerts')

    <!-- الجدول الرئيسي -->
    <div class="card border-0 shadow">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i> قائمة المكاتب
                </h5>
                <div>

                    <a class="btn btn-light" href="{{ route('addOffice', 'client') }}">
                        <i class="fas fa-plus me-2"></i> إضافة مكتب
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col" width="30%">الاسم</th>
                            <th scope="col" width="15%">عدد الحاويات</th>
                            <th scope="col" width="15%">عدد البيان</th>
                            <th scope="col" width="35%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    <a href="{{ route('getOfficeContainerData', $item->id) }}" class="text-primary fw-bold">
                                        <i class="fas fa-building me-2"></i> {{ $item->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-success rounded-pill p-2 text-white">
                                        {{ $item->active_containers_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info rounded-pill p-2 text-white">
                                        {{ $item->active_customs_count }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm me-2" data-toggle="modal"
                                        data-target="#customsModal{{ $item->id }}">
                                        <i class="fas fa-plus me-1"></i> إضافة بيان
                                    </button>

                                    @if (!Auth()->user()?->userinfo?->job_title == 'operator')
                                        <form action="{{ route('deleteOffices', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-{{ $item->is_active ? 'success' : 'danger' }} btn-sm">
                                                <i class="fas fa-{{ $item->is_active ? 'trash' : 'undo' }} me-1"></i>
                                                {{ $item->is_active ? 'حذف' : 'استعادة' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal إضافة بيان جمركي -->
                            <div class="modal fade" id="customsModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-file-alt me-2"></i> إضافة بيان جمركي
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('postCustoms', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="statement_number" class="form-label">رقم البيان</label>
                                                    <input type="text" class="form-control" name="statement_number"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subClient" class="form-label">اسم العميل</label>
                                                    <input type="text" class="form-control" name="subClient" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="contNum" class="form-label">عدد الحاويات</label>
                                                    <input type="number" class="form-control" name="contNum" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="customs_weight" class="form-label">وزن البيان</label>
                                                    <input type="number" class="form-control" name="customs_weight"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="created_at" class="form-label">التاريخ</label>
                                                    <input type="date" class="form-control" name="created_at"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="expire_customs" class="form-label">تاريخ أرضية
                                                        الجمرك</label>
                                                    <input type="date" class="form-control" name="expire_customs">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">إغلاق</button>
                                                    <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-database me-2"></i> لا يوجد بيانات لعرضها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id='paginationLinks' class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        // تفعيل منطقة السحب والإفلات
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('pdfDropZone');
            const fileInput = document.getElementById('pdf_file');

            // عند النقر على منطقة السحب
            dropZone.addEventListener('click', () => fileInput.click());

            // عند تغيير الملف المختار
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    const fileName = fileInput.files[0].name;
                    dropZone.innerHTML = `
                        <i class="fas fa-check-circle text-success mb-2" style="font-size: 2.5rem;"></i>
                        <h5 class="mb-1">${fileName}</h5>
                        <small class="text-muted">تم اختيار الملف بنجاح</small>
                    `;
                    dropZone.classList.add('highlight');
                }
            });

            // منع السلوك الافتراضي عند السحب فوق المنطقة
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // إضافة تأثيرات عند السحب فوق المنطقة
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropZone.classList.add('highlight');
            }

            function unhighlight() {
                dropZone.classList.remove('highlight');
            }

            // التعامل مع الملف المسقط
            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;

                if (files.length) {
                    const fileName = files[0].name;
                    dropZone.innerHTML = `
                        <i class="fas fa-check-circle text-success mb-2" style="font-size: 2.5rem;"></i>
                        <h5 class="mb-1">${fileName}</h5>
                        <small class="text-muted">تم تحميل الملف بنجاح</small>
                    `;
                }
            }
        });
    </script>
@endsection
