@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success" style="text-align: right"> كشف حساب {{ $user->name }}</h1>
    </div>


    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ملاحظات</th>
                    <th scope="col">اجمالي سعر النقل</th>
                    <th scope="col">سعر النقل</th>
                    <th scope="col">عدد الحاويات</th>
                    <th scope="col">العميل</th>
                    <th scope="col">رقم البيان</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <form action="{{ route('updateContainerPrice') }}" method="POST">
                @csrf
                <tbody>
                    @foreach ($user->customs as $custom)
                        @php
                            $transportContainers = $custom->container->whereIn('status', ['transport', 'done']);
                        @endphp
                        @if ($transportContainers->isNotEmpty())
                            <input hidden value="{{ $custom->id }}" name="id[]" />
                            <tr>
                                <td><a href="#">{{ $custom->name }}</a></td>
                                <td>{{ $custom->container->whereIn('status', ['transport', 'done'])->sum('price') }}</td>
                                <td>
                                    @if ($custom->container->whereIn('status', ['transport', 'done'])->sum('price') == 0)
                                        <div class="input-group mb-3">
                                            <input type="text" name="price[]"
                                                value="{{ $custom->container->whereIn('status', ['transport', 'done'])->isNotEmpty() ? $custom->container->whereIn('status', ['transport', 'done'])->sum('price') / $custom->container->whereIn('status', ['transport', 'done'])->count() : 0 }}"
                                                class="form-control" placeholder="سعر الحاوية" aria-label="سعر الحاوية"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">ريال</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mb-3">
                                            <input type="text" name="price[]"
                                                value="{{ $custom->container->whereIn('status', ['transport', 'done'])->isNotEmpty() ? $custom->container->whereIn('status', ['transport', 'done'])->sum('price') / $custom->container->whereIn('status', ['transport', 'done'])->count() : 0 }}"
                                                class="form-control" placeholder="سعر الحاوية" aria-label="سعر الحاوية"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">ريال</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $custom->container->whereIn('status', ['transport', 'done'])->count() }}</td>
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


        <div class="container">
            <div class="col-md-12">
                <h1 class="text-primary">المجموع</h1>
                @php
                    $sumPrice = $user->container->whereIn('status', ['transport', 'done'])->sum('price');
                @endphp
                <h3 class="text-dark">
                    {{ $sumPrice }}
                </h3>
            </div>

            <div class="col-md-12">
                <h1 class="text-success"> (% 15) القيمة المضافة</h1>
                <h3 class="text-dark">
                    @php
                        $sumWith = $sumPrice * 0.15;
                    @endphp
                    {{ $sumWith }}
                </h3>
            </div>

            <div class="col-md-12">
                <h1 class="text-danger">الاجمالي</h1>
                <h3 class="text-dark">

                    {{ $sumPrice + $sumWith }}
                </h3>
            </div>
        </div>

    </div>


@stop
