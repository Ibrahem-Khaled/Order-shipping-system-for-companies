<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشوف حسابات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">

    <style>

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
        <h1 class="text-success" style="text-align: right"> كشف حساب سنوي ل {{ $user->name }}</h1>
    </div>



    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">الرصيد الكلي</th>
                            <th scope="col">الشهر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $annualStatement = [];
                            $totalBalance = 0;
                            $currentYear = now()->year;

                            // Loop through each month of the current year
                            for ($month = 1; $month <= 12; $month++) {
                                // Filter daily records for the current month
                                if ($user->role == 'rent') {
                                    $monthTransactions = $user->rentCont->filter(function ($transaction) use ($currentYear, $month) {
                                        return $transaction->created_at->year == $currentYear && $transaction->created_at->month == $month;
                                    });
                                } else {
                                    $monthTransactions = $user->container->filter(function ($transaction) use ($currentYear, $month) {
                                        return $transaction->created_at->year == $currentYear && $transaction->created_at->month == $month;
                                    });
                                }

                                // Calculate the total balance for the month
                                $monthlyTotal = $monthTransactions->sum('price');

                                // Store the month and total balance in the annual statement array
                                $annualStatement[$month] = [
                                    'month' => $month,
                                    'monthlyTotal' => $monthlyTotal,
                                ];
                            }
                        @endphp

                        @foreach ($annualStatement as $statement)
                            <tr>
                                <td>{{ $statement['monthlyTotal'] }}</td>
                                <td>{{ DateTime::createFromFormat('!m', $statement['month'])->format('F') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ملاحظات</th>
                            <th scope="col">المبلغ</th>
                            <th scope="col">الوصف</th>
                            <th scope="col">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sum = 0;
                            $totalPrice = 0;
                        @endphp
                        @foreach ($daily->sortBy('created_at') as $item)
                            <tr>
                                <td>{{ $item->notes }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <h1 class="text-primary">المتبقي</h1>
        @php
            if ($user->role == 'rent') {
                $sumPrice = $user->rentCont->sum('price');
            } else {
                $sumPrice = $user->container->sum('price');
            }
            $sumDaily = $user->clientdaily->sum('price');
        @endphp
        <h3 class="text-dark">
            {{ $sumPrice - $sumDaily }}
        </h3>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
