<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            color: #000;
            overflow-x: hidden;
            height: 100%;
            background-image: url("https://i.imgur.com/GMmCQHC.png");
            background-repeat: no-repeat;
            background-size: 100% 100%
        }

        .card {
            padding: 30px 40px;
            margin-top: 60px;
            margin-bottom: 60px;
            border: none !important;
            box-shadow: 0 6px 12px 0 rgba(0, 0, 0, 0.2)
        }

        .blue-text {
            color: #00BCD4
        }

        .form-control-label {
            margin-bottom: 0
        }

        input,
        textarea,
        select,
        button {
            margin: 5px 0px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            font-size: 18px !important;
            font-weight: 300;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border: 1px solid #00BCD4;
            outline-width: 0;
            font-weight: 400;
        }

        .btn-block {
            text-transform: uppercase;
            font-size: 15px !important;
            font-weight: 400;
            height: 43px;
            cursor: pointer;
        }

        .btn-block:hover {
            color: #fff !important;
        }

        button:focus {
            outline-width: 0;
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

            <a href="{{ url()->previous() }}">Go Back</a>

            <!-- Responsive navigation toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-1 py-5 mx-auto">
        <div class="row d-flex justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-11 col-11 text-center">
                <h3>اضافة حاوية جديدة الي {{ $custom->client->name }}</h3>
                <div class="card p-4">
                    <h5 class="text-center mb-4">برجاء ملئ جميع المعلومات بدقة</h5>
                    <form class="form-card" action="{{ route('addContainer', $custom->id) }}" method="POST">
                        @csrf
                        @for ($i = 0; $i < Route::current()->parameter('contNum'); $i++)
                            <div class="row justify-content-between text-left">
                                <div class="mb-3 col-md-3">
                                    <label for="containerSize">هل الحاوية ايجار</label>
                                    <select id="containerSize" name="rent[]" class="form-select">
                                        <option value="">هل الحاوية ايجار</option>
                                        <option value="rent">ايجار</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="containerNumber">اسم العميل</label>
                                    <input type="text" id="containerNumber" name="subclient_id"
                                        value="{{ $custom->subclient_id }}" class="form-control" disabled>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="containerSize">حجم الحاوية</label>
                                    <select id="containerSize" name="size[]" class="form-select" required>
                                        <option value="">اختر</option>
                                        <option value="20">20</option>
                                        <option value="40">40</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="containerNumber">رقم الحاوية</label>
                                    <input type="text" id="containerNumber" name="number[]" class="form-control"
                                        placeholder="N123" required>
                                </div>
                            </div>
                        @endfor

                        <div class="modal-footer col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
