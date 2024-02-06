<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://cdn-icons-png.flaticon.com/128/1239/1239682.png" alt="NomerGroup Logo" height="30"
                    class="d-inline-block align-top">
                NomerGroup
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr class="text-uppercase text-success">
                            <th scope="col"></th>
                            <th scope="col">الفواتير</th>
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
                                <td>
                                    @if ($item->type == 'deposit')
                                        {{ $item->price }}
                                    @endif
                                </td>
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
                <h3> الباقي {{ $deposit- $withdraw }}</h3>
            </div>
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

</body>

</html>
