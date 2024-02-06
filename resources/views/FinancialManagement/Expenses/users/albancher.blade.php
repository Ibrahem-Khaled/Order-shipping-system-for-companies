<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البنشري</title>
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
                <h3 class="text-center mb-4"> {{ count($users) }} اجمالي حسابات البنشري </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">الباقي</th>
                            <th scope="col" class="text-center">اجمالي الدفع</th>
                            <th scope="col" class="text-center">اجمالي المستحق</th>
                            <th scope="col" class="text-center">الاسم</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $item)
                            <tr>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->employeedaily->where('type', 'deposit')->sum('price') - $item->employeedaily->where('type', 'withdraw')->sum('price') }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->employeedaily->where('type', 'withdraw')->sum('price') }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->employeedaily->where('type', 'deposit')->sum('price') }}</td>
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

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

</body>

</html>
