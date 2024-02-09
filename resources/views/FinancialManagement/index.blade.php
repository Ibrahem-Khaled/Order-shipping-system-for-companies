<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الادارة المالية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        .card {
            --blur: 16px;
            --size: clamp(300px, 50vmin, 600px);
            width: var(--size);
            aspect-ratio: 4 / 3;
            position: relative;
            border-radius: 2rem;
            overflow: hidden;
            color: #000;
            transform: translateZ(0);
        }

        .card__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(calc(1 + (var(--hover, 0) * 0.25))) rotate(calc(var(--hover, 0) * -5deg));
            transition: transform 0.2s;
        }

        .card__footer {
            padding: 0 1.5rem;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: red;
            display: grid;
            grid-template-row: auto auto;
            gap: 0.5ch;
            background: hsl(0 0% 100% / 0.5);
            backdrop-filter: blur(var(--blur));
            height: 30%;
            align-content: center;
        }

        .card__action {
            position: absolute;
            aspect-ratio: 1;
            width: calc(var(--size) * 0.15);
            bottom: 30%;
            right: 1.5rem;
            transform: translateY(50%) translateY(calc((var(--size) * 0.4))) translateY(calc(var(--hover, 0) * (var(--size) * -0.4)));
            background: purple;
            display: grid;
            place-items: center;
            border-radius: 0.5rem;
            background: hsl(0 0% 100% / 0.5);
            /*   backdrop-filter: blur(calc(var(--blur) * 0.5)); */
            transition: transform 0.2s;
        }

        .card__action svg {
            aspect-ratio: 1;
            width: 50%;
        }

        .card__footer span:nth-of-type(1) {
            font-size: calc(var(--size) * 0.065);
        }

        .card__footer span:nth-of-type(2) {
            font-size: calc(var(--size) * 0.035);
        }

        .card:is(:hover, :focus-visible) {
            --hover: 1;
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




    <div style="display: flex; justify-content: space-around; flex-wrap: wrap; margin-top: 10px; align-items: stretch;">
        <button type="button" class="card" data-bs-toggle="modal" data-bs-target="#expenses">
            <img src="https://plus.unsplash.com/premium_photo-1679496828905-1f6d9ac5721a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YWNjb3VudGluZ3xlbnwwfHwwfHx8MA%3D%3D"
                alt="balloon with an emoji face" class="card__img">
            <span class="card__footer">
                <span>المصروفات</span>
            </span>
            <span class="card__action">
                <svg viewBox="0 0 448 512" title="play">
                    <path
                        d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                </svg>
            </span>
        </button>
        <div class="modal fade" id="expenses" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Content Goes Here -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add Employee Data -->
                        <div class="list-group">
                            <a href="{{ route('expensesCarsData') }}" class="list-group-item list-group-item-action">كشف
                                حساب السيارات</a>
                            <a href="{{ route('expensesSallaryeEmployee') }}"
                                class="list-group-item list-group-item-action">المرتبات</a>
                            <a href="{{ route('expensesSallaryAlbancher') }}"
                                class="list-group-item list-group-item-action">البنشري</a>
                            <a href="#" class="list-group-item list-group-item-action">مصروفات الشركة</a>
                        </div>
                        <!-- Include your form elements for adding employee data here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Include your "Add" button here to submit the form -->
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="card" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <img src="https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHRyYW5zcG9ydHxlbnwwfHwwfHx8MA%3D%3D"
                alt="balloon with an emoji face" class="card__img">
            <span class="card__footer">
                <span>الايرادات</span>
            </span>
            <span class="card__action">
                <svg viewBox="0 0 448 512" title="play">
                    <path
                        d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                </svg>
            </span>
        </button>
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
            aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Content Goes Here -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add Employee Data -->
                        <div class="list-group">
                            <a href="{{ route('getRevenuesClient') }}"
                                class="list-group-item list-group-item-action">كشف حساب مكتب تخليص جمركي</a>
                            <a href="#" class="list-group-item list-group-item-action">حركة البيع وشراء</a>
                            <a href="#" class="list-group-item list-group-item-action">أخرى</a>
                        </div>
                        <!-- Include your form elements for adding employee data here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Include your "Add" button here to submit the form -->
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('dailyManagement') }}" class="card">
            <img src="https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHRyYW5zcG9ydHxlbnwwfHwwfHx8MA%3D%3D"
                alt="balloon with an emoji face" class="card__img">
            <span class="card__footer">
                <span>اليومية</span>
            </span>
            <span class="card__action">
                <svg viewBox="0 0 448 512" title="play">
                    <path
                        d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                </svg>
            </span>
        </a>
        <button type="button" class="card" data-bs-toggle="modal" data-bs-target="#rent">
            <img src="https://plus.unsplash.com/premium_photo-1679496828905-1f6d9ac5721a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YWNjb3VudGluZ3xlbnwwfHwwfHx8MA%3D%3D"
                alt="balloon with an emoji face" class="card__img">
            <span class="card__footer">
                <span>الايجارات</span>
            </span>
            <span class="card__action">
                <svg viewBox="0 0 448 512" title="play">
                    <path
                        d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                </svg>
            </span>
        </button>
        <div class="modal fade" id="rent" tabindex="-1" role="dialog"
            aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Content Goes Here -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add Employee Data -->
                        <div class="list-group">
                            <a href="{{ route('getOfficesRent') }}" class="list-group-item list-group-item-action">كشف
                                حساب مكاتب الايجار</a>
                            <!-- Include your form elements for adding employee data here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- Include your "Add" button here to submit the form -->
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>
</body>

</html>
