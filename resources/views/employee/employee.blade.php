@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> {{ count($employee) }} الموظفين </h3>
                <button type="button" style="margin: 5px" class="btn btn-success d-inline-block" data-toggle="modal"
                    data-target="#addcarModal">
                    اضافة سيارة
                </button>
                <div class="modal fade" id="addcarModal" tabindex="-1" role="dialog"
                    aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Content Goes Here -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="addEmployeeModalLabel">اضافة سيارة</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form to Add Employee Data -->
                                <form action="{{ route('postCar') }}" method="POST" enctype="multipart/form-data">
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
                                            <input type="number" name="serial_number" required class="form-control"
                                                placeholder="الرقم التسلسلي" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label>تاريخ انتهاء الترخيص</label>
                                            <input type="date" name="license_expire" required class="form-control" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label>تاريخ الفحص</label>
                                            <input type="date" name="scan_expire" required class="form-control" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label>انتهاء تاريخ بطاقة التشغيل</label>
                                            <input type="date" name="card_run_expire" required class="form-control" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label>انتهاء تاريخ التامين</label>
                                            <input type="date" name="insurance_expire" required class="form-control" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <input type="text" name="number" required class="form-control"
                                                placeholder="رقم السيارة" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <select name="type" required class="form-control">
                                                <option value="">اختيار نوع السيارة</option>
                                                <option value="transfer">نقل</option>
                                                <option value="private">ملاكي</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <select name="driver_id" class="form-control">
                                                <option value="">بدون سائق</option>
                                                @foreach ($employee as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">حفظ</button>
                                    </div>
                                </form>
                                <!-- Include your form elements for adding employee data here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <!-- Include your "Add" button here to submit the form -->
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" style="margin: 5px" class="btn btn-success d-inline-block" data-toggle="modal"
                    data-target="#addEmployeeModal">
                    إضافة موظف
                </button>
                <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
                    aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Content Goes Here -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="addEmployeeModalLabel">اضافة الموظف</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form to Add Employee Data -->
                                <form action="{{ route('postEmployee') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <input type="text" name="name" required class="form-control"
                                                placeholder="الاسم" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <select type="text" name="gender" class="form-control">
                                                <option value="male">ذكر</option>
                                                <option value="female">انثي</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <input type="number" required name="sallary" class="form-control"
                                                placeholder="الراتب" />
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
                                            <input type="tel" required name="phone" class="form-control"
                                                placeholder="رقم الجوال" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <input type="text" name="nationality" class="form-control"
                                                placeholder="الجنسية" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <input type="number" name="number_residence" class="form-control"
                                                placeholder="رقم الاقامة" />
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">انتهاء الاقامة"</label>
                                            <input type="date" name="expire_residence" class="form-control"
                                                placeholder="انتهاء الاقامة" />
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
                                            <input type="number" required name="password" class="form-control"
                                                placeholder="كلمة السر" />
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <!-- Include your "Add" button here to submit the form -->
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            @if (Auth()->user()->role == 'superAdmin')
                                <th scope="col" class="text-center"></th>
                            @endif
                            <th scope="col" class="text-center">action</th>
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
                                @if (Auth()->user()->role == 'superAdmin')
                                    <td class="text-center">
                                        {{ $item->created_at != $item->updated_at ? 'معدلة' : '' }}
                                    </td>
                                @endif
                                <td class="text-center">
                                    <form action="{{ route('partnerinActive', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $item->is_active == 1 ? 'success' : 'danger' }}">
                                            {{ $item->is_active == 1 ? 'مفعل' : 'غير مفعل' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <img src="{{ $item?->userinfo?->image == null ? 'https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg' : asset('storage/' . $item->userinfo->image) }}"
                                        alt="{{ $item->name }}" class="img-thumbnail"
                                        style="max-width: 100px; max-height: 100px;">
                                </td>

                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'driver' ? 'سائق' : 'اداري' }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    ر.س{{ $item->sallary }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    <a href="{{ route('profileSettings', $item->id) }}">
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
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> {{ count($cars) }} السيارات </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua " style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">action</th>
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
                        @foreach ($cars as $car)
                            <tr>
                                <td class="text-center">
                                    <button class="btn btn-success" data-toggle="modal"
                                        data-target="#editCarModal{{ $car->id }}">
                                        تعديل
                                    </button>
                                    <div class="modal fade" id="editCarModal{{ $car->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Content Goes Here -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addEmployeeModalLabel"> تعديل سيارة
                                                    </h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to Add Employee Data -->
                                                    <form action="{{ route('postCar') }}" method="POST">
                                                        @csrf
                                                        <input type="number" name="id" value="{{ $car->id }}"
                                                            hidden />
                                                        <div class="row">
                                                            <div class="mb-3 col-md-6">
                                                                <input type="text" name="type_car"
                                                                    value="{{ $car->type_car }}" class="form-control"
                                                                    placeholder="نوع السيارة" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="number" name="model_car"
                                                                    value="{{ $car->model_car }}" class="form-control"
                                                                    placeholder="موديل السيارة" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="number" name="serial_number"
                                                                    value="{{ $car->serial_number }}"
                                                                    class="form-control" placeholder="الرقم التسلسلي" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label>تاريخ انتهاء الترخيص</label>
                                                                <input type="date" name="license_expire"
                                                                    value="{{ $car->license_expire }}"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label>تاريخ الفحص</label>
                                                                <input type="date" name="scan_expire"
                                                                    value="{{ $car->scan_expire }}"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label>انتهاء تاريخ بطاقة التشغيل</label>
                                                                <input type="date" name="card_run_expire"
                                                                    value="{{ $car->card_run_expire }}"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label>انتهاء تاريخ التامين</label>
                                                                <input type="date" name="insurance_expire"
                                                                    value="{{ $car->insurance_expire }}"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="text" name="number"
                                                                    value="{{ $car->number }}" class="form-control"
                                                                    placeholder="رقم السيارة" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <select name="type" class="form-control">
                                                                    <option value="{{ $car->type }}">
                                                                        {{ $car->type == 'transfer' ? 'نقل' : 'ملاكي' }}
                                                                    </option>
                                                                    <option value="transfer">نقل</option>
                                                                    <option value="private">ملاكي</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <select name="driver_id" class="form-control">
                                                                    <option value="{{ $car?->driver_id }}">
                                                                        {{ $car?->driver?->name }}</option>
                                                                    <option value="">بدون سائق</option>
                                                                    @foreach ($employee as $cars)
                                                                        <option value="{{ $cars->id }}">
                                                                            {{ $cars->name }}
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
                                                        data-dismiss="modal">Close</button>
                                                    <!-- Include your "Add" button here to submit the form -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->card_run_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->scan_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->license_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->insurance_expire }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->serial_number }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->type_car }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car->model_car }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">{{ $car->number }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $car?->driver?->name }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">{{ $car->id }}
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
