@extends('layouts.default')

@section('content')
    <!-- قسم البطل -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body text-center py-5">
            <h1 class="display-4 fw-bold mb-3">الخدمات الجمركية الذكية</h1>
            <p class="lead mb-4 mx-auto" style="max-width: 700px;">
                نظام متكامل لإدارة العمليات الجمركية باستخدام الذكاء الاصطناعي لتسهيل وتيرة العمل
            </p>
            <div class="d-flex justify-content-center gap-3">
                <button class="btn btn-light btn-lg px-4">بدء الخدمة</button>
                <button class="btn btn-outline-light btn-lg px-4">الدليل الإرشادي</button>
            </div>
        </div>
    </div>

    @include('components.alerts')

    <!-- قسم الأيقونات الرئيسية -->
    <div class="row g-4 mb-4 justify-content-around">

        <!-- بطاقة إضافة بيان جمركي -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="card-body text-center p-4">
                    <div class="icon-lg bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="fw-bold mb-3">إضافة بيان جمركي</h3>
                    <p class="text-muted mb-4">
                        أضف بيانات جمركية جديدة بسهولة مع نظام التعرف الذكي على المستندات
                    </p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                            data-target="#pdfImportModal">
                            <i class="fas fa-file-import me-2"></i> استيراد من PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة إضافة موعد حاوية -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                <div class="card-body text-center p-4">
                    <div class="icon-lg bg-info bg-gradient text-white rounded-circle mb-3 mx-auto">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="fw-bold mb-3">إضافة موعد حاوية</h3>
                    <p class="text-muted mb-4">
                        حدد مواعيد الحاويات وتتبعها تلقائياً مع نظام التنبيهات الذكي
                    </p>
                    <button type="button" class="btn btn-outline-info" data-toggle="modal"
                        data-target="#pdfAnalyzeOfContainer">
                        تحديد موعد
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال استيراد بيان جمركي من PDF -->
    <div class="modal fade" id="pdfImportModal" tabindex="-1" aria-labelledby="pdfImportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="pdfImportModalLabel">
                        <i class="fas fa-file-import me-2"></i> استيراد بيان جمركي من PDF
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1">ملاحظة هامة</h6>
                                <p class="mb-0">يرجى تحميل ملف PDF للبيان الجمركي فقط (الحد الأقصى 10MB)</p>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('analyze.pdf') }}" method="POST" enctype="multipart/form-data"
                        id="pdfUploadForm">
                        @csrf
                        <div class="mb-4">
                            <label for="pdf_file" class="form-label fw-bold">
                                <i class="fas fa-file-pdf me-2 text-danger"></i> ملف البيان الجمركي (PDF)
                            </label>
                            <div class="dropzone border rounded-3 p-5 text-center" id="pdfDropZone">
                                <i class="fas fa-file-pdf text-danger mb-3" style="font-size: 3rem;"></i>
                                <h5 class="mb-2">اسحب وأفلت ملف PDF هنا</h5>
                                <p class="text-muted mb-3">أو</p>
                                <button type="button" class="btn btn-primary" id="selectFileBtn">
                                    <i class="fas fa-folder-open me-2"></i> اختر ملف
                                </button>
                                <input type="file" class="d-none" id="pdf_file" name="pdf_file" accept="application/pdf"
                                    required>
                                <small class="text-muted mt-2 d-block">الحد الأقصى لحجم الملف: 10MB</small>
                                <div id="fileName" class="mt-2 text-truncate"></div>
                            </div>
                            @error('pdf_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-upload me-2"></i> رفع الملف ومعالجة البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال تحديد موعد حاوية -->
    <div class="modal fade" id="pdfAnalyzeOfContainer" tabindex="-1" aria-labelledby="pdfAnalyzeOfContainerLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfAnalyzeOfContainerLabel">استيراد من PDF</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('analyze.pdf.container') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="pdfFile" class="form-label">الملف PDF</label>
                            <input type="file" name="pdfFile" class="form-control" id="pdfFile" accept=".pdf"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .hover-shadow-lg:hover {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .icon-lg {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .dropzone {
            border: 2px dashed #dee2e6;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .dropzone.dragover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }

        #fileName {
            font-size: 0.9rem;
            color: #495057;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('pdfDropZone');
            const fileInput = document.getElementById('pdf_file');
            const selectBtn = document.getElementById('selectFileBtn');
            const fileNameDisplay = document.getElementById('fileName');

            selectBtn.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) {
                    fileNameDisplay.textContent = fileInput.files[0].name;
                }
            });

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, e => e.preventDefault());
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.classList.add('dragover');
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                if (dt.files && dt.files.length) {
                    const file = dt.files[0];
                    if (file.type === 'application/pdf') {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                        fileNameDisplay.textContent = file.name;
                    } else {
                        alert('يرجى اختيار ملف PDF فقط');
                    }
                }
            });
        });
    </script>
@endsection
