@extends('layouts.default')

@section('styles')
    <style>
        /* تنسيقات منطقة السحب والإفلات */
        #pdfDropZone {
            transition: all 0.3s;
            cursor: pointer;
            border: 2px dashed #dee2e6;
            background-color: #f8f9fa;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        #pdfDropZone:hover,
        #pdfDropZone.highlight {
            border-color: #0d6efd;
            background-color: #e9f5ff;
        }

        /* تنسيقات الأزرار */
        .btn-upload {
            padding: 10px 25px;
            font-size: 1.1rem;
        }

        /* تأثيرات أيقونة PDF */
        .pdf-icon {
            transition: transform 0.3s;
        }

        .pdf-icon:hover {
            transform: scale(1.1);
        }
    </style>
@endsection

@section('content')
    <!-- نموذج البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('getOfices') }}" class="row align-items-center" method="GET">
                <div class="col-md-8">
                    <input type="text" name="query" class="form-control" placeholder="ابحث عن مكتب...">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- مودال استيراد PDF -->
    <div class="modal fade" id="pdfImportModal" tabindex="-1" aria-labelledby="pdfImportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="pdfImportModalLabel">
                        <i class="fas fa-file-import mr-2"></i> استيراد بيان جمركي من PDF
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle mr-2"></i> يرجى تحميل ملف PDF للبيان الجمركي فقط (الحد الأقصى
                        10MB)
                    </div>

                    <form action="{{ route('process.customs.pdf') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="pdf_file" class="form-label">
                                <i class="fas fa-file-pdf mr-2 text-danger"></i> ملف البيان الجمركي (PDF)
                            </label>
                            <input type="file" class="form-control-file @error('pdf_file') is-invalid @enderror"
                                id="pdf_file" name="pdf_file" accept=".pdf,application/pdf" required>
                            @error('pdf_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى لحجم الملف: 10MB</small>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-upload mr-2"></i> رفع الملف ومعالجة البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- الجدول الرئيسي -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">قائمة المكاتب</h5>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#pdfImportModal">
                    <i class="fas fa-file-import mr-2"></i> استيراد من PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn-primary" href="{{ route('addOffice', 'client') }}">
                    <i class="fas fa-plus mr-2"></i> إضافة مكتب
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">الاسم</th>
                            <th scope="col">عدد الحاويات</th>
                            <th scope="col">عدد البيان</th>
                            <th scope="col">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    <a href="{{ route('getOfficeContainerData', $item->id) }}" class="text-primary">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td>{{ $item->container->whereIn('status', ['wait', 'rent'])->count() }}</td>
                                <td>
                                    {{ $item->customs->filter(function ($custom) {
                                            return $custom->container->whereIn('status', ['wait', 'rent'])->count() > 0;
                                        })->count() }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#customsModal{{ $item->id }}">
                                        <i class="fas fa-plus mr-1"></i> إضافة بيان
                                    </button>

                                    @if (!Auth()->user()?->userinfo?->job_title == 'operator')
                                        <form action="{{ route('deleteOffices', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i> حذف
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal إضافة بيان جمركي -->
                            <div class="modal fade" id="customsModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">إضافة بيان جمركي</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
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
                                <td colspan="5" class="text-center py-4">لا يوجد بيانات لعرضها</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
