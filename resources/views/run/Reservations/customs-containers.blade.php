@extends('layouts.default')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-white">عرض بيانات البيان الجمركي</h2>
        <div class="row">
            <!-- جدول الحاويات المنتظرة -->
            <div class="col-md-6">
                <h4 class="text-center">الحاويات المنتظرة</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>تاريخ الفارغ</th>
                            <th>الحجم</th>
                            <th>رقم الحاوية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statement->container->where('status', 'wait') as $container)
                            <tr>
                                <td>
                                    <x-countdown-timer :id="$container->id" :transfer_date="$container->dateEmpty" :date_empty="$container->dateEmpty" />
                                </td>
                                <td>{{ $container->size }}</td>
                                <td>{{ $container->number }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">لا توجد حاويات منتظرة</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">
                                إجمالي الحاويات المنتظرة: {{ $statement->container->where('status', 'wait')->count() }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- جدول الحاويات المنقولة -->
            <div class="col-md-6">
                <h4 class="text-center">الحاويات المنقولة</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>الحجم</th>
                            <th>رقم الحاوية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statement->container->where('status', 'transport') as $container)
                            <tr>
                                <td>
                                    <x-countdown-timer :id="$container->id" :transfer_date="$container->dateEmpty" :date_empty="$container->dateEmpty" />
                                </td>
                                <td>{{ $container->size }}</td>
                                <td>{{ $container->number }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">لا توجد حاويات منقولة</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">
                                إجمالي الحاويات المنقولة: {{ $statement->container->where('status', 'transport')->count() }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
