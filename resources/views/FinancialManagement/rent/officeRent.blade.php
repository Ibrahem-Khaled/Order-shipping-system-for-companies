@extends('layouts.default')

@section('content')
    @php
        $total = 0;
    @endphp
    <div class="container mt-5">
        <a class="btn btn-primary mb-3" href="{{ route('addOffice', 'rent') }}">
            <i class="fas fa-plus"></i> اضافة مكتب
        </a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">كشف الشهري</th>
                    <th scope="col">اجمالي عدد الحاويات</th>
                    <th scope="col">كشف السنوي</th>
                    <th scope="col">اجمالي المطلوب من المكتب</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @php
                        $userTotal = $user->rentCont->where('is_rent', 1)->sum('rent_price') -
                                     $user->employeedaily->where('type', 'withdraw')->sum('price');
                        $total += $userTotal;
                    @endphp
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td><a href="{{ route('getrentMonth', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->rentCont->where('is_rent', 1)->count() }}</td>
                        <td><a href="{{ route('getAccountYears', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ number_format($userTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h3 class="text-center">الاجمالي المستحق لشركات الايجار : {{ number_format($total, 2) }}</h3>
    </div>
@endsection
