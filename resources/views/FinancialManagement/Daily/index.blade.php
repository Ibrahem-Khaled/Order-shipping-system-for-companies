@extends('layouts.default')

@section('content')


    <form action="{{ route('dailyManagement') }}" class="row align-items-center" method="GET">
        <div class="col">
            <input type="text" name="query" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-2-strong">
                    <div class="card-body p-0">
                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true">
                            <button type="button" style="margin: 5px" class="btn btn-success btn-lg" data-bs-toggle="modal"
                                data-bs-target="#editTips">
                                تعديل سعر الترب للحاوية
                            </button>
                            <div class="modal fade" id="editTips" tabindex="-1" role="dialog"
                                aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Content Goes Here -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addEmployeeModalLabel">تعديل سعر
                                                الترب</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form to Add Employee Data -->
                                            <form action="{{ route('editContanierTips') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label for="containerNumber" class="form-label">رقم
                                                            الحاوية:</label>
                                                        <input type="text" id="containerNumber" name="number" required
                                                            class="form-control" placeholder="رقم الحاوية">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label for="tarbPrice" class="form-label">سعر
                                                            الترب:</label>
                                                        <input type="text" id="tarbPrice" name="tips" required
                                                            class="form-control" placeholder="سعر الترب">
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">حفظ</button>
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

                            <button type="button" style="margin: 5px" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                data-bs-target="#priceTransfer">
                                اضافة سعر امر النقل
                            </button>
                            <div class="modal fade" id="priceTransfer" tabindex="-1" role="dialog"
                                aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Content Goes Here -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addEmployeeModalLabel">
                                                اضافة سعر امر النقل
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form to Add Employee Data -->
                                            <form action="{{ route('addContanierPriceTransfer') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label for="containerNumber" class="form-label">رقم
                                                            الحاوية:</label>
                                                        <input type="text" id="containerNumber" name="number" required
                                                            class="form-control" placeholder="رقم الحاوية">
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label for="tarbPrice" class="form-label">سعر
                                                            السعر:</label>
                                                        <input type="text" id="tarbPrice" name="price" required
                                                            class="form-control" placeholder="سعر ">
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">حفظ</button>
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

                            <button type="button" style="margin: 5px" class="btn btn-success btn-lg"
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
                                            <h5 class="modal-title" id="addEmployeeModalLabel">اضافة كشف
                                                حساب</h5>
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
                                                        <input type="text" name="name" required
                                                            class="form-control" placeholder="الاسم" />
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

                            <button type="button" data-bs-toggle="modal" style="margin: 5px"
                                data-bs-target="#addDailyModal" class="btn btn-primary btn-lg">اضافة حركة
                                مالية</button>
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
                                                        <input type="text" name="description" required
                                                            class="form-control" placeholder="الوصف" />
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <input type="number" name="price" required
                                                            class="form-control" placeholder="المبلغ" />
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <select name="type" id="type" required
                                                            class="form-control">
                                                            <option value="">نوع الحركة</option>
                                                            <option value="deposit">وارد</option>
                                                            <option value="withdraw">منصرف</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6 toggle-field" id="carField">
                                                        <select name="car_id" class="form-control">
                                                            <option value="">اختيار السيارة</option>
                                                            @foreach ($cars as $item)
                                                                <option value="{{ $item->id }}">{{ $item->number }} -
                                                                    {{ $item->driver?->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6 toggle-field" id="clientField">
                                                        <select name="client_id" class="form-control">
                                                            <option value="">اختيار حساب العميل</option>
                                                            @foreach ($client as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6 toggle-field" id="employeeField">
                                                        <select name="employee_id" class="form-control">
                                                            <option value="">اختيار الحساب</option>
                                                            @foreach ($employee as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6 toggle-field" id="partnerField">
                                                        <select name="partner_id" class="form-control">
                                                            <option value="">اختيار حساب الشريك</option>
                                                            @foreach ($partner as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label for="created_at">تاريخ الإنشاء:</label>
                                                        <input class="form-control" type="date" id="created_at"
                                                            name="created_at">
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
                                                <div class="modal fade" id="confirmationModal{{ $item->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">تأكيد
                                                                    العملية</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                    <button type="button" class="btn btn-secondary"
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
                                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <!-- Modal Content Goes Here -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addEmployeeModalLabel"> تعديل
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ route('updateDailyData', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="mb-3 col-md-6">
                                                                            <input type="text" name="description"
                                                                                required class="form-control"
                                                                                value="{{ $item->description }}"
                                                                                placeholder="الوصف" />
                                                                        </div>
                                                                        <div class="mb-3 col-md-6">
                                                                            <input type="number" name="price" required
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
                                                                            <select name="car_id" class="form-control">
                                                                                <option value="">
                                                                                    اختيار السيارة</option>
                                                                                @foreach ($cars as $items)
                                                                                    <option value="{{ $items->id }}">
                                                                                        {{ $items->number }}
                                                                                        -
                                                                                        {{ $items->driver?->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3 col-md-6">
                                                                            <select name="client_id" class="form-control">
                                                                                <option value="">
                                                                                    اختيار حساب العميل
                                                                                </option>
                                                                                @foreach ($client as $items)
                                                                                    <option value="{{ $items->id }}">
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
                                                                                    <option value="{{ $items->id }}">
                                                                                        {{ $items->name }}
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
                                                                <button type="button" class="btn btn-secondary"
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
                                                    {{ $item->description }} - {{ $item?->car?->number }}
                                                @elseif ($item->client_id !== null)
                                                    {{ $item?->description }}
                                                    @if ($item->container_id !== null)
                                                        {{ $item?->client?->name }}-
                                                        {{ $item?->client?->customs?->first()?->statement_number }}
                                                        {{ $item?->client?->customs?->first()?->subclient_id }}-
                                                        {{ $item?->container?->number }}
                                                    @endif
                                                @elseif ($item->employee_id !== null)
                                                    {{ $item->description }} - {{ $item?->emplyee?->name }}
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const carField = document.getElementById('carField');
            const clientField = document.getElementById('clientField');
            const employeeField = document.getElementById('employeeField');
            const partnerField = document.getElementById('partnerField');

            function toggleFields() {
                const selectedType = typeSelect.value;

                if (selectedType === 'withdraw') {
                    carField.style.display = 'block';
                    employeeField.style.display = 'block';
                    partnerField.style.display = 'block';
                    clientField.style.display = 'none';
                } else if (selectedType === 'deposit') {
                    clientField.style.display = 'block';
                    carField.style.display = 'none';
                    employeeField.style.display = 'none';
                    partnerField.style.display = 'none';
                } else {
                    carField.style.display = 'none';
                    clientField.style.display = 'none';
                    employeeField.style.display = 'none';
                    partnerField.style.display = 'none';
                }
            }

            // Initialize the fields visibility based on the current selected type
            toggleFields();

            // Add event listener to handle changes in the select element
            typeSelect.addEventListener('change', toggleFields);
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
@stop
