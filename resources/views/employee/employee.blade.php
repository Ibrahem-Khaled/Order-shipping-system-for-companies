<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموظفين</title>
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
                        <button type="button" style="margin: 5px" class="btn btn-success d-inline-block"
                            data-bs-toggle="modal" data-bs-target="#addcarModal">
                            اضافة سيارة
                        </button>
                        <!-- Add Employee Modal -->
                        <div class="modal fade" id="addcarModal" tabindex="-1" role="dialog"
                            aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Content Goes Here -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEmployeeModalLabel">اضافة الموظف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to Add Employee Data -->
                                        <form action="{{ route('postCar') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <input type="text" name="type_car" required class="form-control"
                                                        placeholder="نوع السيارة" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="number" name="model_car" required class="form-control"
                                                        placeholder="موديل السيارة" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="number" name="serial_number" required
                                                        class="form-control" placeholder="الرقم التسلسلي" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label>تاريخ انتهاء الترخيص</label>
                                                    <input type="date" name="license_expire" required
                                                        class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label>تاريخ الفحص</label>
                                                    <input type="date" name="scan_expire" required
                                                        class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label>انتهاء تاريخ بطاقة التشغيل</label>
                                                    <input type="date" name="card_run_expire" required
                                                        class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label>انتهاء تاريخ التامين</label>
                                                    <input type="date" name="insurance_expire" required
                                                        class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="text" name="number" required class="form-control"
                                                        placeholder="رقم السيارة" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <?php $driver = \App\Models\User::where('role', 'driver')->get(); ?>
                                                    <select name="driver_id" required class="form-control">
                                                        <option value="">اختيار السائق</option>
                                                        @foreach ($driver as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                    <li class="nav-item">
                        <button type="button" style="margin: 5px" class="btn btn-success d-inline-block"
                            data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            إضافة موظف
                        </button>
                        <!-- Add Employee Modal -->
                        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
                            aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <!-- Modal Content Goes Here -->
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEmployeeModalLabel">اضافة الموظف</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to Add Employee Data -->
                                        <form action="{{ route('postEmployee') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <input type="text" name="name" required
                                                        class="form-control" placeholder="الاسم" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <select type="text" name="gender" class="form-control">
                                                        <option value="male">ذكر</option>
                                                        <option value="female">انثي</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="number" required name="sallary"
                                                        class="form-control" placeholder="الراتب" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="number" name="age" class="form-control"
                                                        placeholder="السن" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">تاريخ التعيين"</label>
                                                    <input type="date" name="date_runer" class="form-control"
                                                        placeholder="تاريخ التعيين" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="tel" required name="phone"
                                                        class="form-control" placeholder="رقم الجوال" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="text" name="nationality" class="form-control"
                                                        placeholder="الجنسية" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="number" name="number_residence"
                                                        class="form-control" placeholder="رقم الاقامة" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">انتهاء الاقامة"</label>
                                                    <input type="date" name="expire_residence"
                                                        class="form-control" placeholder="انتهاء الاقامة" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="text" name="marital_status" class="form-control"
                                                        placeholder="الاحالة الاجتماعية" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">ارفع الصورة</label>
                                                    <input type="file" name="image" class="form-control"
                                                        accept="image/*" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <input type="number" required name="password"
                                                        class="form-control" placeholder="كلمة السر" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <select class="form-control" name="role">
                                                        <option value="driver">سائق</option>
                                                        <option value="administrative">اداري</option>
                                                    </select>
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
                            <th scope="col" class="text-center">الصورة الشخصية</th>
                            <th scope="col" class="text-center">المهنة</th>
                            <th scope="col" class="text-center">الراتب</th>
                            <th scope="col" class="text-center">الاسم</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($employee as $item)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ asset('storage/' . $item->userinfo->image) }}"
                                        alt="{{ $item->name }} Image" class="img-thumbnail"
                                        style="max-width: 100px; max-height: 100px;">
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'driver' ? 'سائق' : 'اداري' }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    ر.س{{ $item->sallary }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">{{ $item->name }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">{{ $item->id }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> {{ count($cars) }} السيارات </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">انتهاء التامين</th>
                            <th scope="col" class="text-center">انتهاء الفحص</th>
                            <th scope="col" class="text-center">انتهاء الرخصة</th>
                            <th scope="col" class="text-center">انتهاء بطاقة التشغيل</th>
                            <th scope="col" class="text-center">الرقم التسلسلي</th>
                            <th scope="col" class="text-center">نوع السيارة</th>
                            <th scope="col" class="text-center">الموديل</th>
                            <th scope="col" class="text-center">رقم السيارة</th>
                            <th scope="col" class="text-center">اسم السائق</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cars as $item)
                            <tr>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->card_run_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->scan_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->license_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->insurance_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->serial_number }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->type_car }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->model_car }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">{{ $item->number }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->driver->name }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">{{ $item->id }}
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
