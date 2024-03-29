@extends('layouts.default')

@section('content')



    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h2>{{ $user->name }}</h2>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-success">
                            <th scope="col"></th>
                            <th scope="col">سعر الترب</th>
                            <th scope="col">حجم الحاوية</th>
                            <th scope="col">رقم الحاوية</th>
                            <th scope="col">اسم العميل</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user->driverContainer as $item)
                            @php
                                $clint = App\Models\CustomsDeclaration::find($item->customs_id);
                            @endphp
                            <tr>
                                <td>
                                    @if ($item->created_at != $item->updated_at)
                                        معدل
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->tips }}
                                </td>
                                <td>
                                    {{ $item->size }}
                                </td>
                                <td>
                                    {{ $item->number }}
                                </td>
                                <td>
                                    {{ $clint->subclient_id }}
                                </td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $allTrips = $user->driverContainer->sum('tips');
                    @endphp
                </table>
                @if ($user->role == 'driver')
                    <h3> اجمالي التربات {{ $allTrips }}</h3>
                @endif
            </div>
        </div>
    </div>


@stop
