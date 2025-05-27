@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-right">
                <h1 class="text-success">كشف حساب {{ $user->name }}</h1>
            </div>
        </div>

        @include('components.alerts')

        <table class="table table-sm table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th scope="col">ملاحظات</th>
                    <th scope="col">سعر النقل</th>
                    <th scope="col">العميل</th>
                    <th scope="col">اسم المكتب</th>
                    <th scope="col">رقم الحاوية</th>
                    <th scope="col">رقم البيان</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <form action="{{ route('updateRentContainerPrice') }}" method="POST">
                @csrf
                <tbody>
                    @foreach ($rentData as $item)
                        @php
                            $custom = App\Models\CustomsDeclaration::find($item->customs_id);
                        @endphp
                        <input hidden value="{{ $item->id }}" name="id[]" />
                        <tr>
                            <td><a href="#">{{ $item->name }}</a></td>
                            <td>
                                @if ($item->is_rent == 1)
                                    <div class="input-group input-group-sm mb-3">
                                        @if (auth()->user()?->userinfo?->job_title == 'administrative')
                                            <input type="text" value="{{ $item->rent_price }}" class="form-control form-control-sm" placeholder="سعر الحاوية" aria-label="سعر الحاوية" aria-describedby="basic-addon2" disabled>
                                        @else
                                            <input type="text" name="rent_price[]" value="{{ $item->rent_price }}" class="form-control form-control-sm" placeholder="سعر الحاوية" aria-label="سعر الحاوية" aria-describedby="basic-addon2">
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>{{ $custom->importer_name }}</td>
                            <td>{{ $custom->client->name }}</td>
                            <td>{{ $item->number }}</td>
                            <td>{{ $custom->statement_number }}</td>
                            <th>{{ $item->id }}</th>
                        </tr>
                    @endforeach
                </tbody>
                <div class="text-right mb-3">
                    <button type="submit" class="btn btn-primary btn-sm">تاكيد سعر الحاوية</button>
                </div>
            </form>
        </table>

        <div class="row">
            <div class="col-md-12 text-right">
                <h1 class="text-danger">الاجمالي</h1>
                <h3 class="text-dark">{{ $rentData->sum('rent_price') }}</h3>
            </div>
        </div>
    </div>

@stop
