@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h2 class="text-center mb-4 text-white">{{ $user->name }}</h2>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase">
                            <th scope="col"></th>
                            <th scope="col">حالة التعديل</th>
                            <th scope="col">نوع الترب</th>
                            <th scope="col">السعر</th>
                            <th scope="col">حجم الحاوية</th>
                            <th scope="col">رقم الحاوية</th>
                            <th scope="col">اسم العميل</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($currentMonthContainers->whereNotNull('tips') as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($item->created_at != $item->updated_at)
                                        معدل
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->tips ?? 'N/A' }}</td>
                                <td>{{ $item->size ?? 'N/A' }}</td>
                                <td>{{ $item->number ?? 'N/A' }}</td>
                                <td>{{ $item->customs->importer_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->transfer_date ?? $item->created_at)->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($tipsEmpty as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($item->created_at != $item->updated_at)
                                        معدل
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->price ?? 'N/A' }}</td>
                                <td>{{ $item->container->size ?? 'N/A' }}</td>
                                <td>{{ $item->container->number ?? 'N/A' }}</td>
                                <td>{{ $item->container->customs->importer_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="8" class="text-center">
                                {{ $currentMonthContainers->whereNotNull('tips')->count() + $tipsEmpty->count() }}</td>
                        </tr>
                    </tbody>
                </table>
                @if ($user->role == 'driver')
                    <h3> إجمالي التربات: {{ $allTrips }}</h3>
                @endif
            </div>
        </div>
    </div>

@stop
