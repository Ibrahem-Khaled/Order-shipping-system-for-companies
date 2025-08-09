@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4">

        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-lg-6 col-7">
                    <h3 class="fw-bold mb-0">تفاصيل البيان الجمركي</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 ">
                            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="#">الرئيسية</a></li>
                            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">تفاصيل البيان</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                    @if ($statement->pdf_path)
                        <a href="{{ route('declarations.download', $statement->id) }}" class="btn btn-success mb-0">
                            <i class="fas fa-download me-1"></i> تحميل PDF
                        </a>
                    @endif
                    @can('is-super-admin')
                        <button class="btn btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                            <i class="fas fa-map-marker-alt me-1"></i> إضافة موقع
                        </button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="row">
            <x-stat-card title="رقم البيان" value="{{ $statement->statement_number }}" icon="fas fa-file-alt"
                color="primary" />
            <x-stat-card title="مكتب التخليص" value="{{ $statement->client->name }}" icon="fas fa-building"
                color="danger" />

            <x-stat-card title="المستورد" value="{{ $statement->importer_name }}" icon="fas fa-user-tie" color="success" />

            <x-stat-card title="الوزن" value="{{ $statement->customs_weight }}" icon="fas fa-weight-hanging"
                color="warning" />

        </div>

        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header pb-0">
                        <h5 class="mb-0"><i class="fas fa-box-open me-2"></i> حالة الحاويات</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-around text-center py-2">
                            <div>
                                <span
                                    class="badge bg-success-soft fs-4">
                                    {{ $statement->container->where('status', 'transport')->count() }}
                                </span>
                                <p class="text-sm fw-bold mt-1 mb-0">محجوزة</p>
                            </div>
                            <div>
                                <span
                                    class="badge bg-warning-soft fs-4">
                                    {{ $statement->container->where('status', 'wait')->count() }}
                                </span>
                                <p class="text-sm fw-bold mt-1 mb-0">منتظرة</p>
                            </div>
                            <div>
                                <span class="badge bg-secondary-soft fs-4">{{ $statement->container->count() }}</span>
                                <p class="text-sm fw-bold mt-1 mb-0">الإجمالي</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header pb-0">
                        <h5 class="mb-0"><i class="fas fa-map-signs me-2"></i> المواقع المرتبطة</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            @forelse ($statement->locations as $location)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-dark text-sm fw-bold mb-0">{{ $location->title }}</h6>
                                                <p class="text-secondary fw-bold text-xs mt-1 mb-0">
                                                    {{ $location->subtitle }}</p>
                                            </div>
                                            @can('is-super-admin')
                                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                                    onsubmit="return confirm('هل أنت متأكد؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-link text-danger text-gradient p-0 m-0"><i
                                                            class="far fa-trash-alt fa-lg"></i></button>
                                                </form>
                                            @endcan
                                        </div>
                                        <div class="mt-2 d-flex gap-2">
                                            @if ($location->maps_url)
                                                <a href="{{ $location->maps_url }}" target="_blank"
                                                    class="btn btn-sm btn-outline-dark mb-0"><i
                                                        class="fab fa-google me-1"></i> Google</a>
                                            @endif
                                            <button class="btn btn-sm btn-outline-info mb-0 share-btn"
                                                data-google="{{ $location->maps_url }}" title="نسخ الروابط">
                                                <i class="fas fa-share-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-compass fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">لا توجد مواقع مضافة بعد.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('is-super-admin')
        <div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('locations.store', $statement->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addLocationModalLabel">إضافة موقع جديد</h5>
                            {{-- Note: data-dismiss is for Bootstrap 4. BS5 uses data-bs-dismiss --}}
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">العنوان الرئيسي</label>
                                <input type="text" class="form-control" name="title" id="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">العنوان الفرعي (اختياري)</label>
                                <input type="text" class="form-control" name="subtitle" id="subtitle">
                            </div>
                            <div class="mb-3">
                                <label for="maps_url" class="form-label">رابط خرائط جوجل (اختياري)</label>
                                <input type="url" class="form-control" name="maps_url" id="maps_url"
                                    placeholder="https://maps.app.goo.gl/...">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ الموقع</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('scripts')
    {{-- =============================================================== --}}
    {{-- ================ الكود المحدث لزر المشاركة =================== --}}
    {{-- =============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /**
             * Copies text to the clipboard, using the modern API with a fallback.
             * @param {string} text The text to copy.
             * @param {HTMLElement} element The button element for visual feedback.
             */
            function copyToClipboard(text, element) {
                // Use modern API if available and in a secure context (HTTPS)
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(() => {
                        showSuccess(element);
                    }).catch(err => {
                        console.error('Modern clipboard API failed. Using fallback.', err);
                        fallbackCopy(text, element);
                    });
                } else {
                    // Fallback for insecure contexts (HTTP) or older browsers
                    console.warn('Using fallback clipboard method.');
                    fallbackCopy(text, element);
                }
            }

            /**
             * Fallback method to copy text using a temporary textarea.
             * @param {string} text The text to copy.
             * @param {HTMLElement} element The button element for visual feedback.
             */
            function fallbackCopy(text, element) {
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.top = "-9999px";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        showSuccess(element);
                    } else {
                        console.error('Fallback: Unable to copy.');
                        alert('لم نتمكن من نسخ الرابط، يرجى المحاولة يدوياً.');
                    }
                } catch (err) {
                    console.error('Fallback: Error copying text.', err);
                    alert('حدث خطأ أثناء محاولة نسخ الرابط.');
                }
                document.body.removeChild(textArea);
            }

            /**
             * Provides visual feedback on the button after a successful copy.
             * @param {HTMLElement} button The button that was clicked.
             */
            function showSuccess(button) {
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> تم النسخ';
                button.classList.add('btn-success');
                button.classList.remove('btn-outline-info');
                button.disabled = true;

                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-info');
                    button.disabled = false;
                }, 2000);
            }

            // Attach event listeners to all share buttons
            const shareButtons = document.querySelectorAll('.share-btn');
            shareButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const googleLink = this.dataset.google || '';
                    if (!googleLink) {
                        alert('لا يوجد رابط خرائط جوجل متاح لهذا الموقع.');
                        return;
                    }

                    // Find the location title from the parent element
                    const timelineContent = this.closest('.timeline-content');
                    const titleElement = timelineContent ? timelineContent.querySelector('h6') : null;
                    const locationTitle = titleElement ? titleElement.textContent.trim() : 'الموقع المحدد';

                    const textToCopy = `موقع: ${locationTitle}\nرابط خرائط جوجل: ${googleLink}`;

                    copyToClipboard(textToCopy, this);
                });
            });

            // Also fixing the modal trigger attributes for Bootstrap 5
            const addLocationButton = document.querySelector('button[data-target="#addLocationModal"]');
            if (addLocationButton) {
                addLocationButton.setAttribute('data-bs-toggle', 'modal');
                addLocationButton.setAttribute('data-bs-target', '#addLocationModal');
            }
            const closeButtons = document.querySelectorAll('[data-dismiss="modal"]');
            closeButtons.forEach(btn => {
                btn.setAttribute('data-bs-dismiss', 'modal');
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .icon-shape {
            width: 48px;
            height: 48px;
        }

        .icon-shape i {
            font-size: 1.5rem;
        }

        .badge.fs-4 {
            font-size: 1.5rem !important;
        }

        .bg-success-soft {
            background-color: rgba(45, 206, 137, 0.1);
            color: #2dce89;
        }

        .bg-warning-soft {
            background-color: rgba(251, 99, 64, 0.1);
            color: #fb6340;
        }

        .bg-secondary-soft {
            background-color: rgba(136, 152, 170, 0.1);
            color: #8898aa;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
        }

        .timeline.timeline-one-side::before {
            content: "";
            position: absolute;
            right: 11px; /* Adjusted for RTL */
            left: auto;
            top: 0;
            height: 100%;
            border-left: 2px solid #dee2e6;
        }

        .timeline-block {
            position: relative;
        }

        .timeline-step {
            position: absolute;
            right: 0; /* Adjusted for RTL */
            left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #5e72e4;
            z-index: 1;
        }

        .timeline-content {
            margin-right: 45px; /* Adjusted for RTL */
            margin-left: 0;
        }
    </style>
@endpush
