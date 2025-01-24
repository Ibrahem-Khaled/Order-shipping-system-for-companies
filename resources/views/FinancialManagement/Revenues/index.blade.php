@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <button id="toggleButton" class="btn btn-primary mb-3 shadow-sm">إخفاء - اظهار العملاء</button>

        <div class="table-responsive">
            <table class="table table-hover table-bordered shadow-sm" id="dataTable">
                <thead class="thead-dark">
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
                        <tr data-container-count="{{ $containersCount[$user->id] }}"
                            data-revenue="{{ $user->remaining_revenue }}">
                            <th scope="row">{{ $user->id }}</th>
                            <td><a href="{{ route('getAccountStatement', $user->id) }}"
                                    class="text-primary">{{ $user->name }}</a></td>
                            <td>{{ $containersCount[$user->id] }}</td>
                            <td><a href="{{ route('getAccountYears', $user->id) }}"
                                    class="text-primary">{{ $user->name }}</a></td>
                            <td>{{ $user->remaining_revenue }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold bg-light">
                        <th scope="row"></th>
                        <td></td>
                        <td>{{ $totalContainers }} مجموع الحاويات </td>
                        <td></td>
                        <td>{{ $totalRemainingRevenue }} مجموع باقي الايرادات </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            const rows = document.querySelectorAll('#dataTable tbody tr');
            rows.forEach(row => {
                const containerCount = parseInt(row.getAttribute('data-container-count'));
                const revenue = parseFloat(row.getAttribute('data-revenue'));
                if (containerCount === 0 && revenue === 0) {
                    row.style.display = row.style.display === 'none' ? '' : 'none';
                }
            });
        });
    </script>
@stop
