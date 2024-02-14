@extends('layouts.default')

@section('content')



    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-success">
                            <th scope="col"></th>
                            <th scope="col">الديزل</th>
                            <th scope="col">المنصرف</th>
                            <th scope="col">البيان</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($car->daily as $item)
                            <tr>
                                <td>
                                    @if ($item->created_at != $item->updated_at)
                                        معدل
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                                <td>
                                    @if (Str::contains($item->description, 'ديزل') && $item->type === 'withdraw')
                                        {{ $item->price }}
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                                <td>
                                    @if (Str::contains($item->description, 'ديزل') && $item->type == 'withdraw')
                                        {{ null }}
                                    @else
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
                        $totalPrice = 0;
                        $desl = 0;
                    @endphp

                    @foreach ($car->daily as $daily)
                        @if ($daily->type == 'withdraw')
                            @php
                                $totalPrice += $daily->price;
                            @endphp
                        @endif
                        @if (Str::contains($daily->description, 'ديزل') && $daily->type == 'withdraw')
                            @php
                                $desl += $daily->price;
                            @endphp
                        @endif
                    @endforeach
                </table>
                <h3> اجمالي الديزل {{ $desl }}</h3>
                <h3> الاجمالي {{ $totalPrice }}</h3>
            </div>
        </div>
    </div>


@stop