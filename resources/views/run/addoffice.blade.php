@extends('layouts.default')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-sm-10 col-12 text-center">
                <h3>{{ Route::current()->parameter('role') == 'rent' ? 'اضافة مكتب ايجار' : 'اضافة مكتب جمركي' }}</h3>
                <p class="text-white">من هنا يتم اضافة الجميع<br>من الموظف والمكاتب والعملاء ولخ...</p>
                <div class="card">
                    <h5 class="card-header text-center">برجاء ملئ جميع المعلومات بدقة</h5>
                    <form class="card-body" action="{{ route('postOffice', Route::current()->parameter('role')) }}"
                        method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="fname">الاسم <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fname" name="name" placeholder="الاسم"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">اضافة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
