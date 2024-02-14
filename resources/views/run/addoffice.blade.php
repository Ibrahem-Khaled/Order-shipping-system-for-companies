@extends('layouts.default')

@section('content')

    <div class="container-fluid px-1 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
                <h3>{{ Route::current()->parameter('role') == 'rent' ? 'اضافة مكتب ايجار' : 'اضافة مكتب جمركي' }}</h3>
                <p class="blue-text">من هنا يتم اضافة الجميع<br>من الموظف والمكاتب والعملاء ولخ...</p>
                <div class="card">
                    <h5 class="text-center mb-4">برجاء ملئ جميع المعلومات بدقة</h5>
                    <form class="form-card" action="{{ route('postOffice', Route::current()->parameter('role')) }}"
                        method="POST">
                        @csrf
                        <div class="row justify-content-between text-left">
                            <div class="form-group col-sm-6 flex-column d-flex"> <label
                                    class="form-control-label px-3">الاسم<span class="text-danger">
                                        *</span></label> <input type="text" id="fname" name="name"
                                    placeholder="name" required onblur="validate(1)">
                            </div>
                            <button type="submit" class="btn-block btn-primary">اضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
