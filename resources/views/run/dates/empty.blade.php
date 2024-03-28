@extends('layouts.default')

@section('content')
    <form action="{{ route('empty') }}" class="row align-items-center" method="GET">
        <div class="col">
            <input type="text" name="query" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style=" overflow: auto; position: relative;">
                <h3 class="text-center mb-4" style="position: sticky; top: 0; z-index: 0;"> {{ count($containerPort) }}
                    الحاويات المحملة</h3>
                <table id="example" class="table table-striped" style="width:100%">
                    <thead class="bg-aqua " style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
                            <th scope="col" class="text-center">التاريخ</th>
                            <th scope="col" class="text-center">السيارة</th>
                            <th scope="col" class="text-center">السائق</th>
                            <th scope="col" class="text-center">حجم الحاوية</th>
                            <th scope="col" class="text-center">رقم الحاوية</th>
                            <th scope="col" class="text-center">رقم البيان</th>
                            <th scope="col" class="text-center">الحالة</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($containerPort as $item)
                            <tr>
                                <td class="text-center">{{ $item->customs->subclient_id }}</td>
                                <td class="text-center">{{ $item->client->name }}</td>
                                <td class="text-center">{{ $item->created_at }}</td>
                                @if ($item->rent_id == null)
                                    <td class="text-center">{{ $item->car->number ?? 0 }}</td>
                                    <td class="text-center">{{ $item->driver->name ?? 0 }}</td>
                                @else
                                    <td class="text-center">{{ $item->rent->name }}</td>
                                    <td class="text-center">{{ $item->rent->name }}</td>
                                @endif
                                <td class="text-center">{{ $item->size }}</td>
                                <td class="text-center">{{ $item->number }}</td>
                                <td class="text-center">{{ $item->customs->statement_number }}</td>
                                <td class="text-center">
                                    <form id="confirmationForm{{ $item->id }}"
                                        action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="status" value="done">
                                        <button type="button" class="btn btn-success"
                                            onclick="showConfirmation({{ $item->id }})">محملة</button>
                                    </form>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style=" overflow: auto; position: relative;">
                <h3 class="text-center mb-4" style="position: sticky; top: 0; z-index: 0;"> {{ count($done) }}
                    الحاويات الفارغة</h3>
                <table id="example2" class="table table-striped" style="width:100%">
                    <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
                            <th scope="col" class="text-center">السيارة</th>
                            <th scope="col" class="text-center">السائق</th>
                            <th scope="col" class="text-center">حجم الحاوية</th>
                            <th scope="col" class="text-center">رقم الحاوية</th>
                            <th scope="col" class="text-center">رقم البيان</th>
                            <th scope="col" class="text-center">الحالة</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($done as $item)
                            <tr>
                                <td class="text-center">{{ $item->customs->subclient_id }}</td>
                                <td class="text-center">{{ $item->client->name }}</td>
                                @if ($item->rent_id == null)
                                    <td class="text-center">{{ $item->car->number ?? 0 }}</td>
                                    <td class="text-center">{{ $item->driver->name ?? 0 }}</td>
                                @else
                                    <td class="text-center">{{ $item->rent->name }}</td>
                                    <td class="text-center">اسم شركة الايجار</td>
                                @endif
                                <td class="text-center">{{ $item->size }}</td>
                                <td class="text-center">{{ $item->number }}</td>
                                <td class="text-center">{{ $item->customs->statement_number }}</td>
                                <td class="text-center">
                                    <form id="confirmationForm{{ $item->id }}"
                                        action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="transport">
                                        <button type="button" class="btn btn-warning"
                                            onclick="showConfirmation({{ $item->id }})">فارغ</button>
                                    </form>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showConfirmation(itemId) {
            if (confirm("هل انت متاكد من هذا الطلب?")) {
                document.getElementById("confirmationForm" + itemId).submit();
            } else {
                return false;
            }
        }
    </script>
@stop
