@extends('layouts.default')
<style>
    .pagination {
        margin-top: 20px;
    }

    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .page-link {
        color: #007bff;
    }

    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: not-allowed;
    }
</style>
@section('content')
    <!-- نموذج البحث -->
    <form action="{{ route('getOfices') }}" class="row align-items-center" method="GET">
        <div class="col">
            <input type="text" name="query" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>

    <!-- الجدول الرئيسي -->
    <div class="container mt-5">
        <table class="table">
            <a class="btn btn-primary" href="{{ route('addOffice', 'client') }}">
                <i class="fas fa-plus"></i> اضافة مكتب
            </a>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">عدد الحاويات</th>
                    <th scope="col">عدد البيان</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
                        <td><a href="{{ route('getOfficeContainerData', $item->id) }}">{{ $item->name }}</a></td>
                        <td>{{ $item->container->whereIn('status', ['wait', 'rent'])->count() }}</td>
                        <td>
                            {{ $item->customs->filter(function ($custom) {
                                    return $custom->container->whereIn('status', ['wait', 'rent'])->count() > 0;
                                })->count() }}
                        </td>
                        <td>
                            <!-- زر فتح Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#myModal{{ $item->id }}">
                                اضافة بيان جمركي
                            </button>
                            @if (!Auth()->user()?->userinfo?->job_title == 'operator')
                                <form action="{{ route('deleteOffices', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">حذف المكتب</button>
                                </form>
                            @endif

                            <!-- Modal -->
                            <div class="modal fade" id="myModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">اضافة بيان جمركي</h5>
                                            <button type="button" class="btn-close" data-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="customsForm{{ $item->id }}"
                                                action="{{ route('postCustoms', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="statement_number" class="form-label">رقم البيان</label>
                                                    <input type="number" class="form-control" required
                                                        name="statement_number" id="statement_number" placeholder="123">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="subClient" class="form-label">اسم العميل</label>
                                                    <input type="text" class="form-control" required name="subClient"
                                                        id="subClient" placeholder="العميل">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="contNum" class="form-label">عدد الحاويات</label>
                                                    <input type="number" class="form-control" required name="contNum"
                                                        id="contNum" placeholder="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="customs_weight" class="form-label">وزن البيان</label>
                                                    <input type="number" class="form-control" required
                                                        name="customs_weight" id="customs_weight" placeholder="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="created_at" class="form-label">التاريخ</label>
                                                    <input type="date" class="form-control" required name="created_at"
                                                        id="created_at">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="expire_customs" class="form-label">تاريخ أرضية
                                                        الجمرك</label>
                                                    <input type="text" class="form-control" required
                                                        name="expire_customs" id="expire_customs{{ $item->id }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">انهاء</button>
                                                    <button type="submit" class="btn btn-primary">التالي</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا يوجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($users->hasPages())
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">&raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">&raquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>

    <!-- مكتبات JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-hijri/2.1.2/moment-hijri.min.js" defer></script>

    <!-- تحويل التاريخ الهجري إلى ميلادي قبل الإرسال -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // لكل نموذج في الصفحة
            document.querySelectorAll('form[id^="customsForm"]').forEach(form => {
                form.addEventListener('submit', function(event) {
                    const hijriInput = form.querySelector('input[name="expire_customs"]');
                    const hijriDate = hijriInput.value;

                    // تحويل التاريخ الهجري إلى ميلادي
                    const gregorianDate = moment(hijriDate, 'iYYYY/iMM/iDD');

                    // إذا كان التاريخ غير صالح
                    if (!gregorianDate.isValid()) {
                        alert('الرجاء إدخال تاريخ هجري صحيح (YYYY/MM/DD)');
                        event.preventDefault(); // منع إرسال النموذج
                    } else {
                        // تحديث قيمة الحقل بالتاريخ الميلادي
                        hijriInput.value = gregorianDate.format('YYYY-MM-DD');
                    }
                });
            });
        });
    </script>
@stop
