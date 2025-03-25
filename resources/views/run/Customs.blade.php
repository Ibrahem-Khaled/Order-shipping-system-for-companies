@extends('layouts.default')
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

    <!-- الجدول الرئيسي -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">قائمة المكاتب</h5>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn-primary" href="{{ route('addOffice', 'client') }}">
                    <i class="fas fa-plus"></i> إضافة مكتب
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
                                <td><a href="{{ route('getOfficeContainerData', $item->id) }}"
                                        class="text-primary">{{ $item->name }}</a></td>
                                <td>{{ $item->container->whereIn('status', ['wait', 'rent'])->count() }}</td>
                                <td>
                                    {{ $item->customs->filter(function ($custom) {
                                            return $custom->container->whereIn('status', ['wait', 'rent'])->count() > 0;
                                        })->count() }}
                                </td>
                                <td>
                                    <!-- زر فتح Modal -->
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#myModal{{ $item->id }}">
                                        <i class="fas fa-plus"></i> إضافة بيان جمركي
                                    </button>
                                    @if (!Auth()->user()?->userinfo?->job_title == 'operator')
                                        <form action="{{ route('deleteOffices', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="exampleModalLabel">إضافة بيان جمركي</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="customsForm{{ $item->id }}"
                                                        action="{{ route('postCustoms', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="statement_number" class="form-label">رقم
                                                                البيان</label>
                                                            <input type="number" class="form-control" required
                                                                name="statement_number" id="statement_number"
                                                                placeholder="123">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="subClient" class="form-label">اسم العميل</label>
                                                            <input type="text" class="form-control" required
                                                                name="subClient" id="subClient" placeholder="العميل">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="contNum" class="form-label">عدد الحاويات</label>
                                                            <input type="number" class="form-control" required
                                                                name="contNum" id="contNum" placeholder="1">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="customs_weight" class="form-label">وزن
                                                                البيان</label>
                                                            <input type="number" class="form-control" required
                                                                name="customs_weight" id="customs_weight" placeholder="1">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="created_at" class="form-label">التاريخ</label>
                                                            <input type="date" class="form-control" required
                                                                name="created_at" id="created_at">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="expire_customs" class="form-label">تاريخ أرضية
                                                                الجمرك</label>
                                                            <input type="date" class="form-control" name="expire_customs">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">إغلاق</button>
                                                            <button type="submit" class="btn btn-primary">حفظ</button>
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
            </div>
        </div>
    </div>
@stop
