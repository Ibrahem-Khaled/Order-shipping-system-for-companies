@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success" style="text-align: right"> رقم البيان {{ $custom->statement_number }}</h1>
    </div>

    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">action</th>
                    <th scope="col">سعر الحاوية</th>
                    <th scope="col">سعر امر النقل</th>
                    <th scope="col">حجم الحاوية</th>
                    <th scope="col">اسم العميل</th>
                    <th scope="col">اسم المكتب</th>
                    <th scope="col">رقم الحاوية</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($custom->container as $item)
                    <tr>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal{{ $item->id }}">
                                تعديل سعر الحاوية
                            </button>
                            <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">تعديل سعر الحاوية</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('updateContainerOnly') }}" class="row align-items-center"
                                                method="POST">
                                                @csrf
                                                <div class="col">
                                                    @if (auth()->user()?->userinfo?->job_title == 'administrative')
                                                        <input type="text" value="{{ $item?->number }}"
                                                            class="form-control" placeholder="رقم الحاوية" disabled>
                                                    @else
                                                        <input type="text" name="number" value="{{ $item?->number }}"
                                                            class="form-control" placeholder="رقم الحاوية">
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    @if (auth()->user()?->userinfo?->job_title == 'administrative')
                                                        <input type="text" value="{{ $item?->price }}"
                                                            class="form-control" placeholder="سعر الحاوية" disabled>
                                                    @else
                                                        <input type="text" name="price" value="{{ $item?->price }}"
                                                            class="form-control" placeholder="سعر الحاوية">
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    @if (auth()->user()?->userinfo?->job_title == 'administrative')
                                                        <input type="text" value="{{ $custom->subclient_id }}"
                                                            class="form-control" placeholder="اسم العميل" disabled>
                                                    @else
                                                        <input type="text" name="customs_subclient_id"
                                                            value="{{ $custom->subclient_id }}" class="form-control"
                                                            placeholder="اسم العميل">
                                                    @endif
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-success">تحديث</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->daily->sum('price') }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->client->name }}</td>
                        <td>{{ $custom->subclient_id }}</td>
                        <td>{{ $item->number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
