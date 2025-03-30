@extends('layouts.default')

@section('content')
    <div class="container">
        <h1 class="text-white">السطحات</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createFlatbedModal">إضافة سطحة</button>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>رقم السطحة</th>
                    <th>نوع السطحة</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th>بيانات الحاوية</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($flatbeds as $flatbed)
                    <tr>
                        <td>{{ $flatbed->id }}</td>
                        <td>{{ $flatbed->number }}</td>
                        <td>{{ $flatbed->type }}</td>
                        <td>{{ $flatbed->description }}</td>
                        <td>{{ $flatbed->status ? 'فارغ' : 'محمل' }}</td>

                        <!-- بيانات الحاوية الأخيرة -->
                        <td>
                            @if (!$flatbed->status && $flatbed->containers->isNotEmpty())
                                @php
                                    $lastContainer = $flatbed->containers->last();
                                @endphp
                                <ul>
                                    <li>رقم الحاوية: {{ $lastContainer->number }}</li>
                                    <li>نوع الحاوية: {{ $lastContainer->size }}</li>
                                    <li>اسم العميل: {{ $lastContainer->customs->importer_name }}</li>
                                    <li>رقم البيان: {{ $lastContainer->customs->statement_number }}</li>
                                    <li>تاريخ التحميل:
                                        {{ \Carbon\Carbon::parse($lastContainer->transfer_date)->format('Y-m-d') }}</li>
                                </ul>
                            @else
                                <p>لا توجد بيانات للحاوية.</p>
                            @endif
                        </td>

                        <!-- العمليات -->
                        <td>
                            <button class="btn btn-info" data-toggle="modal"
                                data-target="#editFlatbedModal{{ $flatbed->id }}">تعديل</button>
                            <button class="btn btn-danger" data-toggle="modal"
                                data-target="#deleteFlatbedModal{{ $flatbed->id }}">حذف</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Create Modal -->
    <div class="modal fade" id="createFlatbedModal" tabindex="-1" aria-labelledby="createFlatbedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFlatbedModalLabel">إضافة سطحة جديدة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('flatbeds.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="number">رقم السطحة</label>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>
                        <div class="form-group">
                            <label for="type">نوع السطحة</label>
                            <select class="form-control" id="type" name="type">
                                <option value="20">20</option>
                                <option value="40">40</option>
                                <option value="box">صندوق</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">الوصف</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">الحالة</label>
                            <input type="checkbox" id="status" name="status" value="1" checked>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @foreach ($flatbeds as $flatbed)
        <div class="modal fade" id="editFlatbedModal{{ $flatbed->id }}" tabindex="-1"
            aria-labelledby="editFlatbedModalLabel{{ $flatbed->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFlatbedModalLabel{{ $flatbed->id }}">تعديل السطحة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('flatbeds.update', $flatbed->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="number{{ $flatbed->id }}">رقم السطحة</label>
                                <input type="text" class="form-control" id="number{{ $flatbed->id }}" name="number"
                                    value="{{ $flatbed->number }}" required>
                            </div>
                            <div class="form-group">
                                <label for="type{{ $flatbed->id }}">نوع السطحة</label>
                                <select class="form-control" id="type{{ $flatbed->id }}" name="type">
                                    <option value="20" {{ $flatbed->type == '20' ? 'selected' : '' }}>20</option>
                                    <option value="40" {{ $flatbed->type == '40' ? 'selected' : '' }}>40</option>
                                    <option value="box" {{ $flatbed->type == 'box' ? 'selected' : '' }}>صندوق</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description{{ $flatbed->id }}">الوصف</label>
                                <textarea class="form-control" id="description{{ $flatbed->id }}" name="description">{{ $flatbed->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="status{{ $flatbed->id }}">الحالة</label>
                                <input type="checkbox" id="status{{ $flatbed->id }}" name="status" value="1"
                                    {{ $flatbed->status ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteFlatbedModal{{ $flatbed->id }}" tabindex="-1"
            aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد أنك تريد حذف هذه السطحة؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <form action="{{ route('flatbeds.destroy', $flatbed->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
