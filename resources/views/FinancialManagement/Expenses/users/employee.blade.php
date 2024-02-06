<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرواتب</title>
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
                <h3 class="text-center mb-4"> {{ count($employee) }} الموظفين </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            @if (Auth()->user()->role == 'superAdmin')
                                <th scope="col" class="text-center"></th>
                            @endif
                            <th scope="col" class="text-center">الصورة الشخصية</th>
                            <th scope="col" class="text-center">المهنة</th>
                            <th scope="col" class="text-center">كشف حساب ترب</th>
                            <th scope="col" class="text-center">الراتب</th>
                            <th scope="col" class="text-center">الاسم</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($employee as $item)
                            <tr>
                                @if (Auth()->user()->role == 'superAdmin')
                                    <td class="text-center">
                                        {{ $item->created_at != $item->updated_at ? 'معدلة' : '' }}
                                    </td>
                                @endif
                                <td class="text-center">
                                    <img src="{{ $item?->userinfo?->image == null ? 'https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg' : asset('storage/' . $item->userinfo->image) }}"
                                        alt="{{ $item->name }}" class="img-thumbnail"
                                        style="max-width: 100px; max-height: 100px;">
                                </td>

                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'driver' ? 'سائق' : 'اداري' }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'driver' ? $item->driverContainer->sum('tips') : null }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    ر.س{{ $item->sallary + $item->driverContainer->sum('tips') }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    @if ($item->role == 'driver')
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#expenses{{ $item->id }}">
                                            {{ $item->name }}
                                        </button>
                                        <div class="modal fade" id="expenses{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Content Goes Here -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع
                                                            الطلب:</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Form to Add Employee Data -->
                                                        <div class="list-group">
                                                            <a href="{{ route('expensesEmployeeTips', $item->id) }}"
                                                                class="list-group-item list-group-item-action">كشف حساب
                                                                الترب</a>
                                                            <a href="{{ route('expensesEmployeeDaily', $item->id) }}"
                                                                class="list-group-item list-group-item-action">كشف حساب
                                                                السائق</a>
                                                        </div>
                                                        <!-- Include your form elements for adding employee data here -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <!-- Include your "Add" button here to submit the form -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a class="btn btn-primary"
                                            href="{{ route('expensesEmployeeDaily', $item->id) }}">
                                            {{ $item->name }}
                                        </a>
                                    @endif
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
