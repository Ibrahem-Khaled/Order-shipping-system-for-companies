
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
                    <th scope="col">سعر النقل</th>
                    <th scope="col">العميل</th>
                    <th scope="col">رقم الحاوية</th>
                    <th scope="col">رقم البيان</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <form action="{{ route('updateRentContainerPrice') }}" method="POST">
                @csrf
                <tbody>
                    @foreach ($user->rentCont->whereIn('status', ['transport','done']) as $item)
                        @php
                            $custom = App\Models\CustomsDeclaration::find($item->customs_id);
                        @endphp
                        <input hidden value="{{ $item->id }}" name="id[]" />
                        <tr>
                            <td><a href="#">{{ $item->name }}</a></td>
                            <td>
                                @if ($item->is_rent == 1)
                                    <div class="input-group mb-3">
                                        <input type="text" name="price[]"  value="{{ $item->price }}"
                                            class="form-control" placeholder="سعر الحاوية" aria-label="سعر الحاوية"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">ريال</span>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td scope="row">{{ $custom->subclient_id }}</td>
                            <td scope="row">{{ $item->number }}</td>
                            <td scope="row">{{ $custom->statement_number }}</td>
                            <th scope="row">{{ $item->id }}</th>
                        </tr>
                    @endforeach
                </tbody>
                <button type="submit" class="btn btn-primary">تاكيد سعر الحاوية</button>
            </form>
        </table>


        <div class="container">
            <div class="col-md-12">
                <h1 class="text-danger">الاجمالي</h1>
                <h3 class="text-dark">
                    {{ $user->rentCont->whereIn('status', ['transport','done'])->sum('price') }}
                </h3>
            </div>
        </div>

    </div>


@stop