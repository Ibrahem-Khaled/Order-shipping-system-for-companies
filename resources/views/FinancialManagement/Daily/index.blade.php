@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h4 class="text-center ">الحركات اليومية</h4>
                <div class="card shadow-2-strong">
                    <div class="card-body p-0">
                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true">
                            <div class="d-flex flex-wrap justify-content-around p-3">
                                <button type="button" class="btn btn-success btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#editTips">
                                    تعديل سعر الترب للحاوية
                                </button>
                                <button type="button" class="btn btn-danger btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#priceTransfer">
                                    اضافة سعر امر النقل
                                </button>
                                <button type="button" class="btn btn-success btn-sm m-1" data-bs-toggle="modal"
                                    data-bs-target="#addEmployeeModal">
                                    إضافة كشف حساب جديد
                                </button>
                                <button type="button" class="btn btn-primary btn-lg m-1" data-bs-toggle="modal"
                                    data-bs-target="#addDailyModal">
                                    اضافة حركة مالية
                                </button>
                                <form action="{{ route('dailyManagement') }}" method="GET" class="d-flex m-2 ">
                                    <input type="text" name="query" class="form-control" placeholder="Search...">
                                    <button type="submit" class="btn btn-success btn-sm m-1">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-info btn-lg m-1" data-bs-toggle="modal"
                                    data-bs-target="#ordersModal">
                                    عرض أوامر النقل
                                </button>
                            </div>

                            <!-- Modal for Edit Tips -->
                            <div class="modal fade" id="editTips" tabindex="-1" role="dialog"
                                aria-labelledby="editTipsLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTipsLabel">تعديل سعر الترب</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('editContanierTips') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="containerNumber" class="form-label">رقم الحاوية:</label>
                                                    <input type="text" id="containerNumber" name="number" required
                                                        class="form-control" placeholder="رقم الحاوية">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tarbPrice" class="form-label">سعر الترب:</label>
                                                    <input type="text" id="tarbPrice" name="tips" required
                                                        class="form-control" placeholder="سعر الترب">
                                                </div>
                                                <button type="submit" class="btn btn-primary">حفظ</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Price Transfer -->
                            <div class="modal fade" id="priceTransfer" tabindex="-1" role="dialog"
                                aria-labelledby="priceTransferLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="priceTransferLabel">اضافة سعر امر النقل</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('addContanierPriceTransfer') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="containerNumber" class="form-label">رقم الحاوية:</label>
                                                    <input type="text" id="containerNumber" name="number" required
                                                        class="form-control" placeholder="رقم الحاوية">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tarbPrice" class="form-label">سعر:</label>
                                                    <input type="text" id="tarbPrice" name="price" required
                                                        class="form-control" placeholder="سعر">
                                                </div>
                                                <button type="submit" class="btn btn-primary">حفظ</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Add Employee -->
                            <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
                                aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addEmployeeModalLabel">اضافة كشف حساب</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('addOtherStateMent') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <input type="text" name="name" required class="form-control"
                                                        placeholder="الاسم">
                                                </div>
                                                <button type="submit" class="btn btn-primary">حفظ</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Add Daily -->
                            <div class="modal fade" id="addDailyModal" tabindex="-1" role="dialog"
                                aria-labelledby="addDailyModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addDailyModalLabel">اضافة حركة مالية جديد</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('postDailyData') }}" id="myForm" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <input type="text" name="description" required
                                                        class="form-control" placeholder="الوصف">
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text" name="price" required class="form-control"
                                                        placeholder="المبلغ">
                                                </div>
                                                <div class="mb-3">
                                                    <select name="type" id="type" required class="form-control">
                                                        <option value="">نوع الحركة</option>
                                                        <option value="deposit">وارد</option>
                                                        <option value="withdraw">منصرف</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 toggle-field" id="carField">
                                                    <select name="car_id" class="form-control">
                                                        <option value="">اختيار السيارة</option>
                                                        @foreach ($cars as $item)
                                                            <option value="{{ $item->id }}">{{ $item->number }} -
                                                                {{ $item->driver?->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 toggle-field" id="clientField">
                                                    <select name="client_id" class="form-control">
                                                        <option value="">اختيار حساب العميل</option>
                                                        @foreach ($client as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 toggle-field" id="employeeField">
                                                    <select name="employee_id" class="form-control">
                                                        <option value="">اختيار الحساب</option>
                                                        @foreach ($employee as $item)
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->role == 'company' ? 'مصاريف نثرية وادارية' : $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 toggle-field" id="partnerField">
                                                    <select name="partner_id" class="form-control">
                                                        <option value="">اختيار حساب الشريك</option>
                                                        @foreach ($partner as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="created_at">تاريخ الإنشاء:</label>
                                                    <input class="form-control" type="date" id="created_at"
                                                        name="created_at">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- مودال لعرض اوامر النقل -->
                            <div class="modal fade" id="ordersModal" tabindex="-1" role="dialog"
                                aria-labelledby="ordersModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ordersModalLabel">أوامر النقل</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped">
                                                <thead class="bg-light">
                                                    <tr class="text-center">
                                                        <th scope="col">سعر امر النقل</th>
                                                        <th scope="col">اسم العميل</th>
                                                        <th scope="col">اسم المكتب</th>
                                                        <th scope="col">رقم الحاوية</th>
                                                        <th scope="col">البيان</th>
                                                        <th scope="col">التاريخ</th>
                                                        <th scope="col">#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($daily as $item)
                                                        @if ($item->container_id !== null)
                                                            <tr>
                                                                <td>{{ $item->price }}</td>
                                                                <td>{{ $item?->container?->customs?->subclient_id }}</td>
                                                                <td>{{ $item->client?->name }}</td>
                                                                <td>{{ $item->container?->number }}</td>
                                                                <td>{{ $item?->container?->customs?->statement_number }}
                                                                </td>
                                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                                                </td>
                                                                <td>{{ $loop->iteration }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">إغلاق</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <table class="table">
                                <thead style="background-color: #f8f9fa;">
                                    <tr class=" text-white text-center">
                                        <th scope="col">العمليات</th>
                                        <th scope="col">الوارد</th>
                                        <th scope="col">المنصرف</th>
                                        <th scope="col">البيان</th>
                                        <th scope="col">التاريخ</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daily->whereNull('container_id') as $item)
                                        <tr>
                                            <td class="text-center">
                                                @if ($item->created_at != $item->updated_at)
                                                    معدل
                                                @else
                                                    {{ null }}
                                                @endif
                                                @if (!auth()->user()?->userinfo?->job_title == 'administrative')
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#confirmationModal{{ $item->id }}"
                                                        class="btn btn-danger btn-sm m-1">مسح</button>
                                                    <div class="modal fade" id="confirmationModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">تأكيد
                                                                        العملية</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center"
                                                                    style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-weight: bold;">
                                                                    <p>هل تريد الغاء ذلك البيان</p>
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
                                                @endif
                                                @if ($item->container_id == null)
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $item->id }}"
                                                        class="btn btn-success btn-sm m-1">تعديل</button>
                                                    <div class="modal fade" id="editModal{{ $item->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel">تعديل</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form
                                                                        action="{{ route('updateDailyData', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <div class="mb-3">
                                                                            <input type="text" name="description"
                                                                                required class="form-control"
                                                                                value="{{ $item->description }}"
                                                                                placeholder="الوصف">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <input type="text" name="price" required
                                                                                class="form-control"
                                                                                value="{{ $item->price }}"
                                                                                placeholder="المبلغ">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <select name="type" required
                                                                                class="form-control">
                                                                                <option value="">نوع الحركة</option>
                                                                                <option value="deposit">وارد</option>
                                                                                <option value="withdraw">منصرف</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <select name="car_id" class="form-control">
                                                                                <option value="">اختيار السيارة
                                                                                </option>
                                                                                @foreach ($cars as $items)
                                                                                    <option value="{{ $items->id }}">
                                                                                        {{ $items->number }} -
                                                                                        {{ $items->driver?->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <select name="client_id" class="form-control">
                                                                                <option value="">اختيار حساب العميل
                                                                                </option>
                                                                                @foreach ($client as $items)
                                                                                    <option value="{{ $items->id }}">
                                                                                        {{ $items->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <select name="employee_id"
                                                                                class="form-control">
                                                                                <option value="">اختيار الحساب
                                                                                </option>
                                                                                @foreach ($employee as $items)
                                                                                    <option value="{{ $items->id }}">
                                                                                        {{ $items->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">عدل</button>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">اغلاق</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->type == 'deposit' ? $item->price : null }}</td>
                                            <td>{{ $item->type == 'withdraw' ? $item->price : null }}{{ $item->type == 'partner_withdraw' ? $item->price : null }}
                                            </td>
                                            <td>
                                                @if ($item->car_id !== null)
                                                    {{ $item->description }} - {{ $item?->car?->number }}
                                                @elseif ($item->client_id !== null)
                                                    {{ $item?->description }} - {{ $item?->client?->name }} -
                                                @elseif ($item->employee_id !== null)
                                                    {{ $item->description }} - {{ $item?->emplyee?->name }}
                                                @else
                                                    {{ $item->description }}
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                            <td>{{ $loop->iteration }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

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

                                            const fields = [carField, employeeField, partnerField];
                                            fields.forEach(field => {
                                                field.querySelector('select').disabled = false;
                                                field.querySelector('select').addEventListener('change', function() {
                                                    fields.forEach(otherField => {
                                                        if (otherField !== field) {
                                                            otherField.querySelector('select').disabled = true;
                                                            otherField.querySelector('select').value = '';
                                                        }
                                                    });
                                                });
                                            });

                                        } else if (selectedType === 'deposit') {
                                            clientField.style.display = 'block';
                                            carField.style.display = 'none';
                                            employeeField.style.display = 'none';
                                            partnerField.style.display = 'none';
                                            carField.querySelector('select').disabled = true;
                                            employeeField.querySelector('select').disabled = true;
                                            partnerField.querySelector('select').disabled = true;
                                        } else {
                                            carField.style.display = 'none';
                                            clientField.style.display = 'none';
                                            employeeField.style.display = 'none';
                                            partnerField.style.display = 'none';
                                            carField.querySelector('select').disabled = true;
                                            employeeField.querySelector('select').disabled = true;
                                            partnerField.querySelector('select').disabled = true;
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
