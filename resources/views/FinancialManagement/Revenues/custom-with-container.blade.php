@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">
                                <i class="fas fa-file-invoice mr-2"></i>
                                البيان الجمركي رقم: <span class="badge badge-light">{{ $custom->statement_number }}</span>
                            </h2>
                            <div>
                                <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#transferModal">
                                    <i class="fas fa-exchange-alt mr-1"></i> نقل البيان
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Modal -->
        <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-gradient-info text-white">
                        <h5 class="modal-title" id="transferModalLabel">
                            <i class="fas fa-truck-moving mr-2"></i> نقل البيان لمكتب آخر
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('transferCustom', $custom->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="officeSelect" class="font-weight-bold">
                                    <i class="fas fa-building mr-1"></i> اختر المكتب الجديد
                                </label>
                                <select class="form-control selectpicker" id="officeSelect" name="new_office_id"
                                    data-live-search="true" required>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}"
                                            {{ $office->id == $custom->client_id ? 'disabled' : '' }}>
                                            {{ $office->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="transferNotes" class="font-weight-bold">
                                    <i class="fas fa-sticky-note mr-1"></i> ملاحظات النقل (اختياري)
                                </label>
                                <textarea class="form-control" id="transferNotes" name="notes" rows="3"
                                    placeholder="أي ملاحظات حول عملية النقل..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> إلغاء
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check mr-1"></i> تأكيد النقل
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="120px">إجراءات</th>
                                        <th>سعر الحاوية</th>
                                        <th>سعر أمر النقل</th>
                                        <th>حجم الحاوية</th>
                                        <th>حالة الحاوية</th>
                                        <th>اسم العميل</th>
                                        <th>اسم المكتب</th>
                                        <th>رقم الحاوية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($custom->container as $item)
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#editModal{{ $item->id }}" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                                        data-target="#containerModal{{ $item->id }}" title="تفاصيل">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <x-container-details :item="$item" />
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-pill badge-success">{{ number_format($item->price) }}
                                                    ر.س</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-pill badge-warning">{{ number_format($item->daily->sum('price')) }}
                                                    ر.س</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-primary">{{ $item->size }}</span>
                                            </td>
                                            <td>
                                                @if ($item->status == 'rent')
                                                    <span class="badge badge-pill badge-secondary"><i
                                                            class="fas fa-key mr-1"></i> إيجار</span>
                                                @elseif ($item->status == 'done')
                                                    <span class="badge badge-pill badge-success"><i
                                                            class="fas fa-check-circle mr-1"></i> تم التسليم</span>
                                                @elseif($item->status == 'transport')
                                                    <span class="badge badge-pill badge-info"><i
                                                            class="fas fa-truck-moving mr-1"></i> في النقل</span>
                                                @elseif($item->status == 'wait')
                                                    <span class="badge badge-pill badge-warning"><i
                                                            class="fas fa-clock mr-1"></i> في الانتظار</span>
                                                @elseif($item->status == 'storage')
                                                    <span class="badge badge-pill badge-dark"><i
                                                            class="fas fa-warehouse mr-1"></i> في التخزين</span>
                                                @endif
                                            </td>
                                            <td>{{ $custom->importer_name }}</td>
                                            <td>{{ $item->client->name }}</td>
                                            <td>
                                                <span class="badge badge-light border">{{ $item->number }}</span>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <div class="modal-header bg-gradient-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit mr-2"></i> تعديل بيانات الحاوية
                                                        </h5>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('updateContainerOnly') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="containerNumber">رقم الحاوية</label>
                                                                    <input type="text" class="form-control"
                                                                        id="containerNumber" name="number"
                                                                        value="{{ $item?->number }}"
                                                                        {{ auth()->user()?->userinfo?->job_title == 'administrative' ? 'disabled' : '' }}>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="containerPrice">سعر الحاوية (ر.س)</label>
                                                                    <input type="text" class="form-control"
                                                                        id="containerPrice" name="price"
                                                                        value="{{ $item?->price }}"
                                                                        {{ auth()->user()?->userinfo?->job_title == 'administrative' ? 'disabled' : '' }}>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="clientName">اسم العميل</label>
                                                                <input type="text" class="form-control"
                                                                    id="clientName" name="customs_importer_name"
                                                                    value="{{ $custom->importer_name }}"
                                                                    {{ auth()->user()?->userinfo?->job_title == 'administrative' ? 'disabled' : '' }}>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                                <i class="fas fa-times mr-1"></i> إغلاق
                                                            </button>
                                                            @if (auth()->user()?->userinfo?->job_title != 'administrative')
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="fas fa-save mr-1"></i> حفظ التغييرات
                                                                </button>
                                                            @endif
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

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(106, 27, 154, 0.05);
        }

        .bg-gradient-primary {
            background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
        }

        .bg-gradient-info {
            background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;
        }

        .selectpicker {
            border-radius: 8px !important;
        }
    </style>
@endsection
