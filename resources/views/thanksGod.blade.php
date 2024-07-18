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
            <h3>عدد الحاويات لكل شهر</h3>
            @php
                $containersPerMonth = [];
                foreach ($containers as $item) {
                    $month = $item->created_at->format('F');
                    if (!isset($containersPerMonth[$month])) {
                        $containersPerMonth[$month] = 0;
                    }
                    $containersPerMonth[$month]++;
                }
            @endphp

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">الشهر</th>
                        <th scope="col">عدد الحاويات</th>
                        <th scope="col">العدد بعد الضرب في الرقم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($containersPerMonth as $month => $count)
                        <tr>
                            <td>{{ $month }}</td>
                            <td>{{ $count }}</td>
                            <td class="multiplied-value" data-count="{{ $count }}"></td>
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
                element.textContent = (count * multiplier).toFixed(2);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateMultipliedValues();
            document.getElementById('priceMultiplier').value = getMultiplier();
        });
    </script>

@stop
