@extends('layouts.default')

@section('content')

    <style>
        .btn-square {
            width: 150px;
            height: 150px;
            margin: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd;
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            font-size: 22px;
        }

        .btn-square:hover {
            box-shadow: 5px 5px 7px rgba(0, 0, 0, 0.5);
        }

        .modal-dialog {
            max-width: 500px;
        }
    </style>

    <div class="container mt-5 justify-between">
        <div class="row justify-content-center">
            <button type="button" class="btn bg-gradient-primary card btn-square" data-toggle="modal"
                data-target="#expenses">
                المصروفات
            </button>
            <button type="button" class="btn bg-gradient-primary card btn-square" data-toggle="modal"
                data-target="#addEmployeeModal">
                الايرادات
            </button>
            <button type="button" class="btn bg-gradient-primary card btn-square" data-toggle="modal"
                data-target="#rent">
                الايجارات
            </button>
        </div>
    </div>

    <!-- Expenses Modal -->
    <div class="modal fade" id="expenses" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Content Goes Here -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to Add Employee Data -->
                    <div class="list-group">
                        <a href="{{ route('expensesCarsData') }}" class="list-group-item list-group-item-action">كشف حساب
                            السيارات</a>
                        <a href="{{ route('expensesSallaryeEmployee') }}"
                            class="list-group-item list-group-item-action">المرتبات</a>
                        <a href="{{ route('expensesSallaryAlbancher') }}"
                            class="list-group-item list-group-item-action">البنشري</a>
                        @foreach ($users as $item)
                            <a href="{{ route('expensesAlbancherDaily', $item->id) }}"
                                class="list-group-item list-group-item-action">مصروفات ادارية ونثرية </a>
                        @endforeach
                        <a href="{{ route('expensesOthers') }}" class="list-group-item list-group-item-action">كشوف أخرى</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenues Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Content Goes Here -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to Add Employee Data -->
                    <div class="list-group">
                        <a href="{{ route('getRevenuesClient') }}" class="list-group-item list-group-item-action">كشف حساب
                            مكتب تخليص جمركي</a>
                        <a href="{{ route('sell.buy') }}" class="list-group-item list-group-item-action">حركة البيع
                            وشراء</a>
                        <a href="#" class="list-group-item list-group-item-action">أخرى</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rent Modal -->
    <div class="modal fade" id="rent" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Content Goes Here -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to Add Employee Data -->
                    <div class="list-group">
                        <a href="{{ route('getOfficesRent') }}" class="list-group-item list-group-item-action">كشف حساب
                            مكاتب الايجار</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@stop
