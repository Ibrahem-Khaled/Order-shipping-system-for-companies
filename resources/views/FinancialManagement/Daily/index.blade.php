<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الادارة المالية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        html,
        body,
        .intro {
            height: 100%;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }


        table td,
        table th {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .card {
            border-radius: .5rem;
        }

        .table-scroll {
            border-radius: .5rem;
        }

        thead {
            top: 0;
            position: sticky;
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
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addDailyModal"
                            class="nav-link btn btn-primary" style="margin: 5px;">اضافة حركة مالية</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" style="margin: 5px" class="btn btn-success d-inline-block"
                            data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            إضافة كشف حساب جديد
                        </button>
                        <!-- Add Employee Modal -->
                        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
                            aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Content Goes Here -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEmployeeModalLabel">اضافة كشف حساب</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to Add Employee Data -->
                                        <form action="{{ route('addOtherStateMent') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <input type="text" name="name" required class="form-control"
                                                        placeholder="الاسم" />
                                                </div>
                                                <button type="submit" class="btn btn-primary">حفظ</button>
                                            </div>
                                        </form>
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
                    </li>
                    <div class="modal fade" id="addDailyModal" tabindex="-1" role="dialog"
                        aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Content Goes Here -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addEmployeeModalLabel">اضافة حركة مالية جديد</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form to Add Employee Data -->
                                    <form action="{{ route('postDailyData') }}" id="myForm" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <input type="text" name="description" required class="form-control"
                                                    placeholder="الوصف" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <input type="number" name="price" required class="form-control"
                                                    placeholder="المبلغ" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <select name="type" required class="form-control">
                                                    <option value="">نوع الحركة</option>
                                                    <option value="deposit">وارد</option>
                                                    <option value="withdraw">منصرف</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <select name="car_id" class="form-control">
                                                    <option value="">اختيار السيارة</option>
                                                    @foreach ($cars as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->number }} - {{ $item->driver?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <select name="client_id" class="form-control">
                                                    <option value="">اختيار حساب العميل</option>
                                                    @foreach ($client as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <select name="employee_id" class="form-control">
                                                    <option value="">اختيار الحساب</option>
                                                    @foreach ($employee as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button id="submitBtn" class="btn btn-primary">Submit</button>
                                        <p id="error" class="error-message"></p>
                                    </form>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <section class="intro">
        <div class="bg-image h-100" style="background-color: #f5f7fa;">
            <div class="mask d-flex align-items-center h-100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card shadow-2-strong">
                                <div class="card-body p-0">
                                    <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true"
                                        style="position: relative; height: 700px">
                                        <table class="table table-white mb-0">
                                            <thead style="background-color: #ffffff;">
                                                <tr class="text-uppercase text-success">
                                                    <th scope="col"></th>
                                                    <th scope="col">العمليات</th>
                                                    <th scope="col">الوارد</th>
                                                    <th scope="col">المنصرف</th>
                                                    <th scope="col">البيان</th>
                                                    <th scope="col">التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($daily as $item)
                                                    <tr>
                                                        <td>
                                                            @if ($item->created_at != $item->updated_at)
                                                                معدل
                                                            @else
                                                                {{ null }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button type="button" data-bs-toggle="modal"
                                                                data-bs-target="#confirmationModal{{ $item->id }}"
                                                                class="btn btn-danger">مسح</button>
                                                            <div class="modal fade"
                                                                id="confirmationModal{{ $item->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="exampleModalLabel">تأكيد
                                                                                العملية</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body text-center"
                                                                            style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-weight: bold;">
                                                                            <p>
                                                                                هل تريد الغاء ذلك البيان
                                                                            </p>
                                                                        </div>
                                                                        <form method="POST"
                                                                            action="{{ route('deleteDailyData', $item->id) }}">
                                                                            @csrf

                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">إلغاء</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">تأكيد</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $item->id }}"
                                                                class="btn btn-success">تعديل</button>
                                                            <div class="modal fade" id="editModal{{ $item->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <!-- Modal Content Goes Here -->
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="addEmployeeModalLabel"> تعديل
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form
                                                                                action="{{ route('updateDailyData', $item->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <div class="row">
                                                                                    <div class="mb-3 col-md-6">
                                                                                        <input type="text"
                                                                                            name="description" required
                                                                                            class="form-control"
                                                                                            value="{{ $item->description }}"
                                                                                            placeholder="الوصف" />
                                                                                    </div>
                                                                                    <div class="mb-3 col-md-6">
                                                                                        <input type="number"
                                                                                            name="price" required
                                                                                            class="form-control"
                                                                                            value="{{ $item->price }}"
                                                                                            placeholder="المبلغ" />
                                                                                    </div>
                                                                                    <div class="mb-3 col-md-6">
                                                                                        <select name="type" required
                                                                                            class="form-control">
                                                                                            <option value="">نوع
                                                                                                الحركة</option>
                                                                                            <option value="deposit">
                                                                                                وارد</option>
                                                                                            <option value="withdraw">
                                                                                                منصرف</option>
                                                                                        </select>
                                                                                    </div>

                                                                                    <div class="mb-3 col-md-6">
                                                                                        <select name="car_id"
                                                                                            class="form-control">
                                                                                            <option value="">
                                                                                                اختيار السيارة</option>
                                                                                            @foreach ($cars as $items)
                                                                                                <option
                                                                                                    value="{{ $items->id }}">
                                                                                                    {{ $items->number }}
                                                                                                    -
                                                                                                    {{ $items->driver?->name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-3 col-md-6">
                                                                                        <select name="client_id"
                                                                                            class="form-control">
                                                                                            <option value="">
                                                                                                اختيار حساب العميل
                                                                                            </option>
                                                                                            @foreach ($client as $items)
                                                                                                <option
                                                                                                    value="{{ $items->id }}">
                                                                                                    {{ $items->name }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-3 col-md-6">
                                                                                        <select name="employee_id"
                                                                                            class="form-control">
                                                                                            <option value="">
                                                                                                اختيار الحساب</option>
                                                                                            @foreach ($employee as $items)
                                                                                                <option
                                                                                                    value="{{ $items->id }}">
                                                                                                    {{ $item->names }}
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <button id="submitBtn" type="submit"
                                                                                    class="btn btn-primary">عدل</button>
                                                                            </form>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">اغلاق</button>
                                                                            <!-- Include your "Add" button here to submit the form -->
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td>{{ $item->type == 'deposit' ? $item->price : null }}</td>
                                                        <td>{{ $item->type == 'withdraw' ? $item->price : null }}</td>
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
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
