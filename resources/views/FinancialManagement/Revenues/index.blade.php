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
            <tbody>
                @php
                    $totalContainers = 0;
                    $totalRemainingRevenue = 0;
                @endphp
                @foreach ($users as $user)
                    @php
                        $userContainerCount = $containersCount[$user->id] ?? 0;
                        $totalContainers += $userContainerCount;
                        $userRemainingRevenue =
                            $user->container
                                ->whereIn('status', ['transport', 'done'])
                                ->where('is_rent', 0)
                                ->sum('price') - $user->clientdaily->sum('price');
                        $totalRemainingRevenue += $userRemainingRevenue;
                    @endphp
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td><a href="{{ route('getAccountStatement', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $userContainerCount }}</td>
                        <td><a href="{{ route('getAccountYears', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $userRemainingRevenue }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold">
                    <th scope="row"></th>
                    <td></td>
                    <td>{{ $totalContainers }} مجموع الحاويات </td>
                    <td></td>
                    <td>{{ $totalRemainingRevenue }} مجموع باقي الايرادات </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

@stop
