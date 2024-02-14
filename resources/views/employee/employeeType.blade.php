@extends('layouts.default')

@section('content')

    <div class="container-fluid px-1 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9 col-11 text-center">
                <h3>{{ Route::current()->parameter('name') == 'driver' ? 'اضافة ترب للسائق' : 'اضافة المسمي الوظيفي' }}
                </h3>
                <div class="card">
                    <h5 class="text-center mb-4">
                        <?php $user = \App\Models\User::find(Route::current()->parameter('userId')); ?>
                        {{ $user->name }}
                    </h5>
                    <form class="form-card" action="{{ route('updateEmployeeData', Route::current()->parameter('userId')) }}"
                        method="POST">
                        @csrf
                        <div class="row justify-content-between text-left">
                            <label
                                class="form-control-label px-3">{{ Route::current()->parameter('name') == 'driver' ? ' ترب ' : ' الوظيفية' }}<span
                                    class="text-danger">
                                    *</span></label>
                            @if (Route::current()->parameter('name') == 'driver')
                                <input type="text" hidden id="fname" name="role" value="driver">
                                <input type="number" id="fname" name="tips" placeholder="ترب" required>
                            @else
                                <input type="text" hidden id="fname" name="role" value="administrative">
                                <input type="text" id="fname" name="job_title" placeholder="المسمي الوظيفي" required>
                            @endif
                            <button type="submit" class="btn-block btn-primary">اضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
