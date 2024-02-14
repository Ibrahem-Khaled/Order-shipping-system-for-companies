@extends('layouts.default')

@section('content')


    <div class="row">
        <button type="button" class="btn bg-gradient-primary card" data-bs-toggle="modal" data-bs-target="#expenses">
            المصروفات
        </button>
        <div class="modal fade" id="expenses" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Content Goes Here -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add Employee Data -->
                        <div class="list-group">
                            <a href="{{ route('expensesCarsData') }}" class="list-group-item list-group-item-action">كشف
                                حساب السيارات</a>
                            <a href="{{ route('expensesSallaryeEmployee') }}"
                                class="list-group-item list-group-item-action">المرتبات</a>
                            <a href="{{ route('expensesSallaryAlbancher') }}"
                                class="list-group-item list-group-item-action">البنشري</a>
                            <a href="{{ route('expensesOthers') }}" class="list-group-item list-group-item-action">كشوف
                                أخرى</a>
                            <a href="#" class="list-group-item list-group-item-action">مصروفات الشركة</a>
                        </div>
                        <!-- Include your form elements for adding employee data here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Include your "Add" button here to submit the form -->
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn bg-gradient-primary card" data-bs-toggle="modal"
            data-bs-target="#addEmployeeModal">
            الايرادات
        </button>
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Content Goes Here -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add Employee Data -->
                        <div class="list-group">
                            <a href="{{ route('getRevenuesClient') }}" class="list-group-item list-group-item-action">كشف
                                حساب مكتب تخليص جمركي</a>
                            <a href="#" class="list-group-item list-group-item-action">حركة البيع وشراء</a>
                            <a href="#" class="list-group-item list-group-item-action">أخرى</a>
                        </div>
                        <!-- Include your form elements for adding employee data here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Include your "Add" button here to submit the form -->
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn bg-gradient-primary card" data-bs-toggle="modal" data-bs-target="#rent">
            الايجارات
        </button>
        <div class="modal fade" id="rent" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Content Goes Here -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">اختر نوع الطلب:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add Employee Data -->
                        <div class="list-group">
                            <a href="{{ route('getOfficesRent') }}" class="list-group-item list-group-item-action">كشف
                                حساب مكاتب الايجار</a>
                            <!-- Include your form elements for adding employee data here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- Include your "Add" button here to submit the form -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



@stop
