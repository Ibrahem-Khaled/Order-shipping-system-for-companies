@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4">{{ $user->name }} </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-gray" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">منصرف راس المال</th>
                            <th scope="col" class="text-center">وارد راس المال</th>
                            <th scope="col" class="text-center">الوصف</th>
                            <th scope="col" class="text-center">التاريخ</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($user->partnerdaily as $item)
                            <tr>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->type == 'partner_withdraw' ? $item->price : null }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->type == 'deposit' ? $item->price : null }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->description }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->created_at }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->id }}
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($user->partnerInfo as $item)
                            <tr>
                                <td class="text-center font-weight-bold" style="font-size: 18px;"></td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->money }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;"></td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->created_at }}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
@stop
