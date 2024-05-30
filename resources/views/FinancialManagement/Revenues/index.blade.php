@extends('layouts.default')

@section('content')


    <div class="container mt-5">
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
            @php
                $priceSum = 0;
                $contanierCount = 0;
            @endphp
            <tbody>
                @foreach ($users as $user)
                    @php
                        $contanierCount += $user->container->whereIn('status', ['transport', 'done'])->count();
                        $priceSum =
                            $priceSum +
                            $user->container
                                ->whereIn('status', ['transport', 'done'])
                                ->where('is_rent', 0)
                                ->sum('price') -
                            ($sumDaily = $user->clientdaily->sum('price'));
                    @endphp
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td><a href="{{ route('getAccountStatement', $user->id) }}">{{ $user->name }}</a></td>
                        @php
                            use Carbon\Carbon;

                            $containersCount = $user
                                ->container()
                                ->whereIn('status', ['transport', 'done'])
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->count();
                        @endphp
                        
                        <td>{{ $containersCount }}</td>
                        <td><a href="{{ route('getAccountYears', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->container->whereIn('status', ['transport', 'done'])->where('is_rent', 0)->sum('price') - ($sumDaily = $user->clientdaily->sum('price')) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="fw-bold">
                    <th scope="row"></th>
                    <td></td>
                    <td> {{ $contanierCount }} مجموع الحاويات </td>
                    <td></td>
                    <td> {{ $priceSum }} مجموع الايرادات </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>


@stop
