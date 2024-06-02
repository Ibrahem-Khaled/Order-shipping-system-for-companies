@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style="position: relative;">
                <h3 class="text-center mb-4"> {{ count($partner) }} الشركاء </h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <button type="button" style="margin: 5px" class="btn btn-success d-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addEmployeeModal">
                        إضافة شريك
                    </button>
                    <!-- Add Employee Modal -->
                    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
                        aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Content Goes Here -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addEmployeeModalLabel">اضافة شريك</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form to Add Employee Data -->
                                    <form action="{{ route('partnerStore') }}" method="POST" enctype="multipart/form-data">
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <!-- Include your "Add" button here to submit the form -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" style="margin: 5px" class="btn btn-success d-inline-block" data-bs-toggle="modal"
                        data-bs-target="#addHeadMoney">
                        إضافة رأس مال
                    </button>
                    <!-- Add Employee Modal -->
                    <div class="modal fade" id="addHeadMoney" tabindex="-1" role="dialog"
                        aria-labelledby="addHeadMoneylLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Content Goes Here -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addHeadMoneylLabel">اضافة رأس مال</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form to Add Employee Data -->
                                    <form action="{{ route('updateHeadMoney') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="mb-3 col-md-6">
                                                <input type="number" name="money" class="form-control"
                                                    placeholder="راس مال" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <select class="form-control" name="id">
                                                    @foreach ($partner as $item)
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <!-- Include your "Add" button here to submit the form -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <thead class="bg-gray" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">action</th>
                            <th scope="col" class="text-center">الصورة الشخصية</th>
                            <th scope="col" class="text-center">ما يمكن سحبه</th>
                            <th scope="col" class="text-center">نسبة الارباح</th>
                            <th scope="col" class="text-center">نسبة الشريك</th>
                            <th scope="col" class="text-center">راس المال</th>
                            <th scope="col" class="text-center">الدور</th>
                            <th scope="col" class="text-center">الاسم</th>
                            <th scope="col" class="text-center">كشف حساب سنوي</th>
                            <th scope="col" class="text-center">كشف حساب شهري</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($partner as $item)
                            @php
                                $totalPrice = $deposit - $withdraw;

                                $prtnerPriceSum = $item->partnerInfo?->sum('money');
                                if ($item->is_active == 1) {
                                    $partnerPriceRate = ($prtnerPriceSum / $sumCompany) * 100;
                                }

                                $partnerWithdraw = $item->partnerdaily->where('type', 'partner_withdraw')->sum('price');

                                $partnerCashCanWithdraw = (($depositCash - $withdrawCash) * $partnerPriceRate) / 100;

                                $rateBuy = ($buyCash->sum('price') * $partnerPriceRate) / 100;
                                $rateSellFromHeadMony = ($sellFromHeadMony->sum('price') * $partnerPriceRate) / 100;
                                $rateSell = ($sellCash->sum('price') * $partnerPriceRate) / 100;

                                $Profits_from_buying_and_selling = $rateSell - $rateBuy;

                                $calculatedValue =
                                    $partnerCashCanWithdraw -
                                    $partnerWithdraw +
                                    $rateSellFromHeadMony +
                                    $Profits_from_buying_and_selling;

                                $companyHeadMoney =
                                    $prtnerPriceSum - $rateSellFromHeadMony - $Profits_from_buying_and_selling;

                            @endphp
                            <tr>
                                <td class="text-center">
                                    <button class="btn btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $item->id }}">
                                        تعديل
                                    </button>
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Content Goes Here -->
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addEmployeeModalLabel">تعديل بيانات الشريك
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to Add Employee Data -->
                                                    <form action="{{ route('partnerUpdate', $item->id) }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="mb-3 col-md-6">
                                                                <input type="text" name="name"
                                                                    value="{{ $item->name }}" class="form-control"
                                                                    placeholder="الاسم" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="number"
                                                                    value="{{ $item->partnerInfo->sum('money') }}"
                                                                    name="money" class="form-control"
                                                                    placeholder="راس مال الشريك" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="number"
                                                                    value="{{ $item->userinfo->number_residence }}"
                                                                    name="number_residence" class="form-control"
                                                                    placeholder="رقم الاقامة" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="tel" value="{{ $item->phone }}"
                                                                    name="phone" class="form-control"
                                                                    placeholder="رقم الجوال" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <label class="form-label">ارفع الصورة</label>
                                                                <input type="file" name="image" class="form-control"
                                                                    accept="image/*" />
                                                            </div>
                                                            <div class="mb-3 col-md-6">
                                                                <input type="number" name="password"
                                                                    class="form-control" placeholder="كلمة السر" />
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
                                    {{ $calculatedValue <= 0 ? 0 : $calculatedValue }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ round($item->is_active == 1 ? ($totalPrice * $partnerPriceRate) / 100 : 0, 2) }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->is_active == 1 ? $partnerPriceRate : 0 }}%</td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    ر.س{{ $companyHeadMoney }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    {{ $item->role == 'company' ? 'الشركة' : 'شريك' }}
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    <a href="{{ route('profileSettings', $item->id) }}">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    <a href="{{ route('partnerYearStatement', $item->id) }}">
                                        كشف سنوي
                                    </a>
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 18px;">
                                    <a href="{{ route('partnerStatement', $item->id) }}">
                                        كشف شهري
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
