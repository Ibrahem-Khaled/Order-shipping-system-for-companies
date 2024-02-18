@extends('layouts.default')

@section('content')


    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-success">
                            <th scope="col"></th>
                            @if ($user->role !== 'company')
                                <th scope="col">الفواتير</th>
                            @endif
                            <th scope="col">المنصرف</th>
                            <th scope="col">البيان</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user->employeedaily as $item)
                            <tr>
                                <td>
                                    @if ($item->created_at != $item->updated_at)
                                        معدل
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                                @if ($user->role !== 'company')
                                    <td>
                                        @if ($item->type == 'deposit')
                                            {{ $item->price }}
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    @if ($item->type == 'withdraw')
                                        {{ $item->price }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->car_id !== null)
                                        {{ $item->description }} - {{ $item->car->number }}
                                    @elseif ($item->client_id !== null)
                                        {{ $item->description }} - {{ $item->client->name }}
                                    @elseif ($item->employee_id !== null)
                                        {{ $item->description }} - {{ $item->emplyee->name }}
                                    @else
                                        {{ $item->description }}
                                    @endif
                                </td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $withdraw = $user->employeedaily->where('type', 'withdraw')->sum('price');
                        $deposit = $user->employeedaily->where('type', 'deposit')->sum('price');
                    @endphp
                </table>
                @if ($user->role !== 'company')
                    <h3> الباقي {{ $deposit - $withdraw }}</h3>
                @endif
                @if ($user->role == 'company')
                    <h3> الاجمالي {{ $withdraw }}</h3>
                @endif
            </div>
        </div>
    </div>


@stop
