@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> اجمالي المصروفات </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">اجمالي المنصرف</th>
                            <th scope="col" class="text-center">الاسم</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $item)
                            <tr>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->employeedaily->where('type', 'withdraw')->sum('price') }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    <a href="{{ route('expensesAlbancherDaily', $item->id) }}">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->id }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

@stop
