@extends('layouts.default')

@section('content')


    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">البيان الجمركي</th>
                    <th scope="col">رقم الحاوية</th>
                    <th scope="col">حالة الحاوية</th>
                    <th scope="col">اسم العميل</th>
                    <th scope="col">حجم الحاوية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users->container->whereIn('status', ['wait', 'rent']) as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
                        <td style="font-weight: bold">{{ $item->customs->statement_number }}</td>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->status == 'wait' ? 'انتظار' : 'ايجار' }}</td>
                        <td>{{ $item->customs->subclient_id }}</td>
                        <td>{{ $item->size }}</td>
                    </tr>
                @endforeach
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

@stop
