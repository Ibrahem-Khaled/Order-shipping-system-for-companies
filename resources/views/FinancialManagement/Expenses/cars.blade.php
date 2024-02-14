@extends('layouts.default')

@section('content')



    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> {{ count($cars) }} اجمالي عدد السيارات </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            @if (Auth()->user()->role == 'superAdmin')
                                <th scope="col" class="text-center"></th>
                            @endif
                            <th scope="col" class="text-center">نوع السيارة</th>
                            <th scope="col" class="text-center">رقم السيارة</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cars as $item)
                            <tr>
                                @if (Auth()->user()->role == 'superAdmin')
                                    <td class="text-center">
                                        {{ $item->created_at != $item->updated_at ? 'معدلة' : '' }}
                                    </td>
                                @endif
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->type_car }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    <a href="{{ route('expensesCarDaily', $item->id) }}">
                                        {{ $item->number }}
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
