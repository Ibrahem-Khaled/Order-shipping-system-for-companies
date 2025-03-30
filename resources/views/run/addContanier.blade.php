@extends('layouts.default')

@section('content')

    <div class="container-fluid px-1 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-11 col-11 text-center">
                <h3 class="text-white">اضافة حاوية جديدة الي {{ $custom->client->name }}</h3>
                <div class="card p-4">
                    <h5 class="text-center mb-4">برجاء ملئ جميع المعلومات بدقة</h5>
                    <form class="form-card" action="{{ route('addContainer', $custom->id) }}" method="POST">
                        @csrf
                        @for ($i = 0; $i < Route::current()->parameter('contNum'); $i++)
                            <div class="row justify-content-between text-left">
                                <div class="mb-3 col-md-2">
                                    <label for="containerRent">هل الحاوية ايجار</label>
                                    <select id="containerRent" name="rent[]" class="form-select">
                                        <option value="">هل الحاوية ايجار</option>
                                        <option value="rent">ايجار</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="containerClient">اسم العميل</label>
                                    <input type="text" id="containerClient" name="importer_name"
                                        value="{{ $custom->importer_name }}" class="form-control" disabled>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="containerSize">حجم الحاوية</label>
                                    <select id="containerSize" name="size[]" class="form-select" required>
                                        <option value="">اختر</option>
                                        <option value="20">20</option>
                                        <option value="40">40</option>
                                        <option value="box">طرد</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="containerNumber">رقم الحاوية</label>
                                    <input type="number" id="containerNumber" name="number[]" class="form-control"
                                        placeholder="N123" required>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="dateEmpty">تاريخ ارضية الفارغ</label>
                                    <input type="date" id="dateEmpty" name="date_empty[]" class="form-control">
                                </div>
                            </div>
                        @endfor

                        <div class="modal-footer col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
