@extends('layouts.default')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-sm-10 col-12 text-center">
                <h3>{{ Route::current()->parameter('name') == 'driver' ? 'اضافة ترب للسائق' : 'اضافة المسمي الوظيفي' }}</h3>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">
                            <?php $user = \App\Models\User::find(Route::current()->parameter('userId')); ?>
                            {{ $user->name }}
                        </h5>
                        <form action="{{ route('updateEmployeeData', Route::current()->parameter('userId')) }}"
                            method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="fname"
                                    class="form-control-label">{{ Route::current()->parameter('name') == 'driver' ? ' ترب ' : ' الوظيفية' }}<span
                                        class="text-danger">*</span></label>
                                @if (Route::current()->parameter('name') == 'driver')
                                    <input type="hidden" name="role" value="driver">
                                    <input type="number" class="form-control" id="fname" name="tips"
                                        placeholder="ترب" required>
                                @else
                                    <input type="hidden" name="role" value="administrative">
                                    <label for="job_title">المسمي الوظيفي</label>
                                    <select class="form-control" id="job_title" name="job_title" required>
                                        <option value="">اختر المسمي الوظيفي</option>
                                        <option value="administrative">محاسب</option>
                                        <option value="operator">مشغل </option>
                                        <!-- Add more options as necessary -->
                                    </select>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">اضافة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
