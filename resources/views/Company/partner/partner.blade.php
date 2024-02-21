@extends('layouts.default')

@section('content')

    {{-- @php
        $deposit = $container->sum('price');
        $carSum = $cars->sum('price');
        $employeeSum = $employee->sum('sallary');
        $employeeTip = $employeeTips->sum('tips');
        $withdraw = $carSum + $employeeSum + $employeeTip + $elbancherSum + $othersSum;
        $totalPrice = strval($deposit) - strval($withdraw);
    @endphp --}}

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> {{ count($partner) }} الموظفين </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button type="button" style="margin: 5px" class="btn btn-success d-inline-block"
                                data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                إضافة شريك
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
                                            <form action="{{ route('partnerStore') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <input type="text" name="name" required class="form-control"
                                                            placeholder="الاسم" />
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <input type="number" name="money" class="form-control"
                                                            placeholder="راس مال الشريك" />
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <input type="number" name="number_residence" class="form-control"
                                                            placeholder="رقم الاقامة" />
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <input type="tel" required name="phone" class="form-control"
                                                            placeholder="رقم الجوال" />
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
                                                            <option value="partner">شريك</option>
                                                            @if ($partner->where('role', 'company')->count() == 0)
                                                                <option value="company">الشركة</option>
                                                            @endif
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
                    </ul>
                    <thead class="bg-gray" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">action</th>
                            <th scope="col" class="text-center">الصورة الشخصية</th>
                            <th scope="col" class="text-center">نسبة الارباح</th>
                            <th scope="col" class="text-center">نسبة الشريك</th>
                            <th scope="col" class="text-center">راس المال</th>
                            <th scope="col" class="text-center">الدور</th>
                            <th scope="col" class="text-center">الاسم</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($partner as $item)
                            @php
                                $container = \App\Models\Container::whereDate('created_at', '>=', $item->updated_at)->get();
                                $employee = \App\Models\User::whereIn('role', ['driver', 'administrative'])->get();
                                $employeeTips = \App\Models\Container::whereDate('created_at', '>=', $item->updated_at)
                                    ->whereNotNull('driver_id')
                                    ->get();

                                $uniqueEmployeeIds = \App\Models\Daily::whereDate('created_at', '>=', $item->updated_at)
                                    ->select('employee_id')
                                    ->whereNotNull('employee_id')
                                    ->distinct()
                                    ->pluck('employee_id');

                                $elbancherSum = 0;
                                foreach ($uniqueEmployeeIds as $value) {
                                    $user = \App\Models\User::find($value);
                                    if (Str::contains($user->name, 'بنشري')) {
                                        $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                                        $elbancherSum = $elbancherSum + $sum;
                                    }
                                }
                                $others = \App\Models\User::whereDate('created_at', '>=', $item->updated_at)
                                    ->where('role', 'driver')
                                    ->Where(function ($query) {
                                        $query->whereNull('sallary');
                                    })
                                    ->whereRaw('name NOT LIKE "%بنشري%"')
                                    ->get();

                                $othersSum = 0;
                                foreach ($others as $other) {
                                    $user = \App\Models\User::find($other->id);
                                    $sum = $user?->employeedaily->where('type', 'withdraw')->sum('price');
                                    $othersSum = $othersSum + $sum;
                                }

                                $cars = \App\Models\Daily::whereDate('created_at', '>=', $item->updated_at)
                                    ->whereNotNull('car_id')
                                    ->where('type', 'withdraw')
                                    ->get();
                                $daily = \App\Models\Daily::whereDate('created_at', '>=', $item->updated_at)
                                    ->whereNotNull('client_id')
                                    ->where('type', 'deposit')
                                    ->get();

                                $deposit = $container->sum('price');
                                $carSum = $cars->sum('price');
                                $employeeSum = $employee->sum('sallary');
                                $employeeTip = $employeeTips->sum('tips');
                                $withdraw = $carSum + $employeeSum + $employeeTip + $elbancherSum + $othersSum;
                                $totalPrice = strval($deposit) - strval($withdraw);

                                $partnerSum = 0;

                                if ($item->is_active == 1) {
                                    $partnerSum = (($item->partnerInfo?->money == 0 ? 1 : $item->partnerInfo?->money) / $sums) * 100;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <button class="btn btn-secondary">
                                        تعديل
                                    </button>
                                    @if ($item->role == 'partner')
                                        <form action="{{ route('partnerinActive', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-{{ $item->is_active == 1 ? 'success' : 'danger' }}">
                                                {{ $item->is_active == 1 ? 'مفعل' : 'غير مفعل' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <img src="{{ $item?->userinfo?->image == null ? 'https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg' : asset('storage/' . $item->userinfo->image) }}"
                                        alt="{{ $item->name }}" class="img-thumbnail"
                                        style="max-width: 100px; max-height: 100px;">
                                </td>

                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ ($totalPrice * $partnerSum) / 100 }}</td>

                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $partnerSum }}%</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    ر.س{{ $item->partnerInfo?->money }}</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'company' ? 'الشركة' : 'شريك' }}
                                </td>
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

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
@stop
