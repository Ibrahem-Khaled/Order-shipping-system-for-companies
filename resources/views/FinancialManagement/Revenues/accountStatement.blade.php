@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success" style="text-align: right">كشف حساب {{ $user->name }}</h1>
    </div>

    <form action="{{ route('getAccountStatement', Route::current()->parameter('clientId')) }}" class="row align-items-center"
        method="GET">
        <div class="col">
            <input type="text" name="query" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>

    <form action="{{ route('updateContainerOnly') }}" class="row align-items-center" method="post">
        @csrf
        <div class="col">
            <input type="text" name="number" value="{{ $container?->number }}" class="form-control"
                placeholder="رقم الحاوية">
        </div>
        <div class="col">
            <input type="text" name="price" value="{{ $container?->price }}" class="form-control"
                placeholder="سعر الحاوية">
        </div>
        <div class="col">
            <input type="text" value="{{ $container?->customs->subclient_id }}" class="form-control"
                placeholder="اسم العميل">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">تحديث</button>
        </div>
    </form>

    <div class="container mt-5">
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
            <form action="{{ route('updateContainerPrice') }}" method="POST">
                @csrf
                <tbody>
                    @php
                        $totalPrice = 0;
                        $totalWithdrawPrice = 0;
                    @endphp
                    @foreach ($customs as $custom)
                        @php
                            $transportContainers = $custom->container->whereIn('status', ['transport', 'done']);
                        @endphp
                        @if ($transportContainers->isNotEmpty())
                            <input hidden value="{{ $custom->id }}" name="id[]" />
                            <tr>
                                @php
                                    $containerPrice = $custom->container
                                        ->whereIn('status', ['transport', 'done', 'rent'])
                                        ->sum('price');
                                    $withdrawPrice = $custom->container
                                        ->flatMap(fn($c) => $c->daily)
                                        ->where('type', 'withdraw')
                                        ->sum('price');
                                    $totalPrice += $containerPrice;
                                    $totalWithdrawPrice += $withdrawPrice;
                                @endphp
                                <td>{{ $containerPrice }}</td>
                                <td>
                                    @if ($containerPrice == 0)
                                        <div class="input-group mb-3">
                                            <input type="text" name="price[]"
                                                value="{{ $custom->container->whereIn('status', ['transport', 'done', 'rent'])->isNotEmpty() ? $containerPrice / $custom->container->whereIn('status', ['transport', 'done', 'rent'])->count() : 0 }}"
                                                class="custom-form-control" placeholder="سعر الحاوية"
                                                aria-label="سعر الحاوية" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">ريال</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mb-3">
                                            <input type="text" name="price[]"
                                                value="{{ $custom->container->whereIn('status', ['transport', 'done', 'rent'])->isNotEmpty() ? $containerPrice / $custom->container->whereIn('status', ['transport', 'done'])->count() : 0 }}"
                                                class="custom-form-control" placeholder="سعر الحاوية"
                                                aria-label="سعر الحاوية" aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">ريال</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $withdrawPrice }}</td>
                                <td>{{ $custom->container->whereIn('status', ['transport', 'done', 'rent'])->count() }}
                                </td>
                                <td scope="row">{{ $custom->subclient_id }}</td>
                                <td scope="row">{{ $custom->statement_number }}</td>
                                <th scope="row">{{ $custom->id }}</th>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <button type="submit" class="btn btn-primary">تاكيد سعر الحاوية</button>
            </form>
        </table>

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
                        <h6 class="text-dark">{{ $totalPrice }}</h6>
                    </td>
                    <td>
                        <h6 class="text-primary">مجموع سعر الحاويات</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h6 class="text-dark">{{ $totalWithdrawPrice }}</h6>
                    </td>
                    <td>
                        <h6 class="text-dark">مجموع اوامر النقل</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h6 class="text-dark">{{ $totalWithdrawPrice + $totalPrice }}</h6>
                    </td>
                    <td>
                        <h6 class="text-dark">الاجمالي</h6>
                    </td>
                </tr>
                <tr>
                    <td>
                        @php
                            $sumWith = ($totalWithdrawPrice + $totalPrice) * 0.15;
                        @endphp
                        <h6 class="text-dark">{{ $sumWith }}</h6>
                    </td>
                    <td>
                        <h6 class="text-success">(% 15) القيمة المضافة</h6>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h6 class="text-dark">{{ $totalWithdrawPrice + $totalPrice + $sumWith }}</h6>
                    </td>
                    <td>
                        <h6 class="text-danger">الاجمالي شامل القيمة المضافة</h6>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

@stop
