@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    @php
                        $deposit = $container->sum('price');
                    @endphp
                    <thead>
                        <tr>
                            <th scope="col">كشوف حسابات الواردة</th>
                            <th scope="col">اجمالي الوارد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>اجمالي كشوف حساب العملاء</td>
                            <td>{{ $deposit }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    @php
                        $carSum = $cars->sum('price');
                        $employeeSum = $employee->sum('sallary');
                        $employeeTip = $employeeTips->sum('tips');
                        $withdraw = $carSum + $employeeSum + $employeeTip + $elbancherSum + $othersSum;
                    @endphp
                    <thead>
                        <tr>
                            <th scope="col">كشوف حسابات المصروفات</th>
                            <th scope="col">اجمالي المنصرف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>اجمالي مصروفات السيارات</td>
                            <td>{{ $carSum }}</td>
                        </tr>
                        <tr>
                            <td>اجمالي المرتبات</td>
                            <td>{{ $employeeSum + $employeeTip }}</td>
                        </tr>
                        <tr>
                            <td>اجمالي مصروفات البنشري</td>
                            <td>{{ $elbancherSum }}</td>
                        </tr>
                        <tr>
                            <td>اجمالي مصروفات الكشوف الاخري</td>
                            <td>{{ $othersSum }}</td>
                        </tr>
                    </tbody>
                </table>
                <h3> اجمالي ايرادات الشركة {{ strval($deposit) - strval($withdraw) }}</h3>

            </div>
        </div>
    </div>



@stop
