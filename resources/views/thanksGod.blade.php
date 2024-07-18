@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="input-group mb-3">
            <input type="number" id="priceMultiplier" class="form-control" placeholder="أدخل الرقم">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" onclick="storeMultiplier()">تخزين الرقم</button>
            </div>
        </div>
        <div class="mt-5">
            <h3 class="text-center text-primary">عدد الحاويات لكل شهر وكل سنة</h3>
            @php
                $containersPerYearMonth = [];
                $years = [];

                foreach ($containers as $item) {
                    $year = $item->created_at->format('Y');
                    $month = $item->created_at->format('F');
                    if (!isset($containersPerYearMonth[$year])) {
                        $containersPerYearMonth[$year] = [];
                    }
                    if (!isset($containersPerYearMonth[$year][$month])) {
                        $containersPerYearMonth[$year][$month] = 0;
                    }
                    $containersPerYearMonth[$year][$month]++;
                    $years[$year] = true;
                }
                ksort($years); // ترتيب السنوات
            @endphp

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">الشهر</th>
                        @foreach ($years as $year => $value)
                            <th scope="col">
                                <div class="d-flex flex-column align-items-center">
                                    <span>{{ $year }}</span>
                                    <div class="d-flex justify-content-between w-100">
                                        <span class="header-title text-success">عدد الحاويات</span>
                                        <span class="header-title text-info">السعر</span>
                                    </div>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                        <tr>
                            <td><strong>{{ $month }}</strong></td>
                            @foreach ($years as $year => $value)
                                <td>
                                    @php
                                        $count = $containersPerYearMonth[$year][$month] ?? 0;
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span
                                            class="container-count text-success font-weight-bold">{{ $count }}</span>
                                        <span class="multiplied-value text-info" data-count="{{ $count }}"></span>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function storeMultiplier() {
            const multiplier = document.getElementById('priceMultiplier').value;
            if (multiplier) {
                localStorage.setItem('priceMultiplier', multiplier);
                updateMultipliedValues();
            } else {
                alert('يرجى إدخال رقم صحيح');
            }
        }

        function getMultiplier() {
            const multiplier = localStorage.getItem('priceMultiplier');
            return multiplier ? parseFloat(multiplier) : 1;
        }

        function updateMultipliedValues() {
            const multiplier = getMultiplier();
            document.querySelectorAll('.multiplied-value').forEach(function(element) {
                const count = element.getAttribute('data-count');
                element.textContent = count > 0 ? `(${(count * multiplier).toFixed(2)})` : '';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateMultipliedValues();
            document.getElementById('priceMultiplier').value = getMultiplier();
        });
    </script>

    <style>
        .header-title {
            font-size: 0.85em;
            font-weight: bold;
        }
    </style>

@stop
