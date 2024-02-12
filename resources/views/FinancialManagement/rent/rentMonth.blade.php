<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب شهري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <!-- Logo and website name -->
            <a class="navbar-brand" href="#">
                <img src="https://cdn-icons-png.flaticon.com/128/1239/1239682.png" alt="NomerGroup Logo" height="30"
                    class="d-inline-block align-top">
                NomerGroup
            </a>


            <!-- Responsive navigation toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ url()->previous() }}">Go Back</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> الرئيسية
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="col-md-12">
        <h1 class="text-success" style="text-align: right"> كشف حساب {{ $user->name }}</h1>
    </div>


    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ملاحظات</th>
                    <th scope="col">سعر النقل</th>
                    <th scope="col">العميل</th>
                    <th scope="col">رقم الحاوية</th>
                    <th scope="col">رقم البيان</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <form action="{{ route('updateRentContainerPrice') }}" method="POST">
                @csrf
                <tbody>
                    @foreach ($user->rentCont->whereIn('status', ['transport','done']) as $item)
                        @php
                            $custom = App\Models\CustomsDeclaration::find($item->customs_id);
                        @endphp
                        <input hidden value="{{ $item->id }}" name="id[]" />
                        <tr>
                            <td><a href="#">{{ $item->name }}</a></td>
                            <td>
                                @if ($item->is_rent == 1)
                                    <div class="input-group mb-3">
                                        <input type="text" name="price[]"  value="{{ $item->price }}"
                                            class="form-control" placeholder="سعر الحاوية" aria-label="سعر الحاوية"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">ريال</span>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td scope="row">{{ $custom->subclient_id }}</td>
                            <td scope="row">{{ $item->number }}</td>
                            <td scope="row">{{ $custom->statement_number }}</td>
                            <th scope="row">{{ $item->id }}</th>
                        </tr>
                    @endforeach
                </tbody>
                <button type="submit" class="btn btn-primary">تاكيد سعر الحاوية</button>
            </form>
        </table>


        <div class="container">
            <div class="col-md-12">
                <h1 class="text-danger">الاجمالي</h1>
                <h3 class="text-dark">
                    {{ $user->rentCont->whereIn('status', ['transport','done'])->sum('price') }}
                </h3>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
