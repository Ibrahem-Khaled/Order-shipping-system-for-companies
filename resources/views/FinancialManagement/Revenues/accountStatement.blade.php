@extends('layouts.default')

@section('content')
    <div class="col-md-12 text-right">
        <h1 class="text-success">كشف حساب {{ $user->name }}</h1>
    </div>

    <div class="container mt-5">
        <form action="{{ route('updateContainerPrice') }}" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">اجمالي سعر النقل</th>
                        <th scope="col">سعر النقل</th>
                        <th scope="col">سعر امر النقل</th>
                        <th scope="col">عدد الحاويات</th>
                        <th scope="col">العميل</th>
                        <th scope="col">رقم البيان</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPrice = 0;
                        $totalWithdrawPrice = 0;
                    @endphp
                    @foreach ($customs as $custom)
                        @php
                            $transportContainers = $custom->container->whereIn('status', [
                                'transport',
                                'done',
                                'rent',
                                'wait',
                            ]);
                        @endphp
                        @if ($transportContainers->isNotEmpty())
                            <input type="hidden" value="{{ $custom->id }}" name="id[]" />
                            <tr>
                                @php
                                    $containerPrice = $transportContainers->sum('price');
                                    $withdrawPrice = $custom->container
                                        ->flatMap(fn($c) => $c->daily)
                                        ->where('type', 'withdraw')
                                        ->sum('price');
                                    $totalPrice += $containerPrice;
                                    $totalWithdrawPrice += $withdrawPrice;
                                @endphp
                                <td>{{ $containerPrice }}</td>
                                <td>
                                    <div class="input-group mb-3">
                                        @if (auth()->user()?->userinfo?->job_title == 'administrative')
                                            <input type="text"
                                                value="{{ $containerPrice / $transportContainers->count() }}"
                                                class="form-control" placeholder="سعر الحاوية" disabled>
                                        @else
                                            <input type="text" name="price[]"
                                                value="{{ $containerPrice / $transportContainers->count() }}"
                                                class="form-control" placeholder="سعر الحاوية">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $withdrawPrice }}</td>
                                <td>{{ $transportContainers->count() }}</td>
                                <td>{{ $custom->subclient_id }}</td>
                                <td>
                                    <a href="{{ route('showContainer', $custom->id) }}">{{ $custom->statement_number }}</a>
                                </td>
                                <td>{{ $custom->id }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">تاكيد سعر الحاوية</button>
        </form>

        <table class="table mt-5">
            <thead>
                <tr>
                    <th scope="col">التفاصيل</th>
                    <th scope="col">المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h6 class="text-primary">مجموع سعر الحاويات</h6>
                    </td>
                    <td>
                        <h6 class="text-dark">{{ $totalPrice }}</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h6 class="text-dark">مجموع اوامر النقل</h6>
                    </td>
                    <td>
                        <h6 class="text-dark">{{ $totalWithdrawPrice }}</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h6 class="text-dark">الاجمالي</h6>
                    </td>
                    <td>
                        <h6 class="text-dark">{{ $totalWithdrawPrice + $totalPrice }}</h6>
                    </td>
                </tr>
                <tr id="vatRow">
                    <td>
                        <h6 class="text-success">(% 15) القيمة المضافة</h6>
                    </td>
                    <td>
                        <h6 class="text-dark" id="vatValue">{{ ($totalWithdrawPrice + $totalPrice) * 0.15 }}</h6>
                    </td>
                </tr>
                <tr id="totalWithVatRow">
                    <td>
                        <h6 class="text-danger">الاجمالي شامل القيمة المضافة</h6>
                    </td>
                    <td>
                        <h6 class="text-dark" id="totalWithVat">{{ ($totalWithdrawPrice + $totalPrice) * 1.15 }}</h6>
                    </td>
                </tr>
            </tbody>
        </table>

        <button id="toggleVatButton" class="btn btn-secondary mt-3" onclick="toggleVat()">اخفاء القيمة المضافة</button>
    </div>

    <script>
        function toggleVat() {
            var vatRow = document.getElementById('vatRow');
            var totalWithVatRow = document.getElementById('totalWithVatRow');
            var toggleButton = document.getElementById('toggleVatButton');

            if (vatRow.style.display === 'none') {
                vatRow.style.display = 'table-row';
                totalWithVatRow.style.display = 'table-row';
                toggleButton.textContent = 'اخفاء القيمة المضافة';
            } else {
                vatRow.style.display = 'none';
                totalWithVatRow.style.display = 'none';
                toggleButton.textContent = 'اظهار القيمة المضافة';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            var totalWithVatRow = document.getElementById('totalWithVatRow');
            totalWithVatRow.style.display = 'none'; // إخفاء الإجمالي شامل القيمة المضافة عند تحميل الصفحة
        });
    </script>
@endsection
