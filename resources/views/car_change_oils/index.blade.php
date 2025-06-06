@extends('layouts.default')

@section('content')
    <div class="container text-right">
        <h1 class="text-center my-4">نظام متابعة تغيير الزيت للسيارات</h1>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5>إجمالي السيارات</h5>
                        <h3>{{ $cars->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h5>السيارات التي تحتاج تغيير زيت قريباً</h5>
                        <h3>{{ $cars->filter(fn($car) => optional($car->oilChanges->last())->km_after <= 500 && optional($car->oilChanges->last())->km_after > 0)->count() }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h5>السيارات التي تجاوزت الحد المسموح</h5>
                        <h3>{{ $cars->filter(fn($car) => optional($car->oilChanges->last())->km_after < 0)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($cars as $car)
            @php
                $lastOilChange = $car->oilChanges->last();
                $KilometersLeftUntilOilChange = $lastOilChange ? $lastOilChange->km_after : 0;
            @endphp

            <div class="card my-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h6>السيارة: {{ $car->driver?->name }} - الرقم: {{ $car->number }}</h6>
                    <span class="text-muted">الباقي كيلومترات: {{ $KilometersLeftUntilOilChange }}</span>
                    <div>
                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#detailsModal{{ $car->id }}">التفاصيل</button>
                        <button class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#addOilChangeModal{{ $car->id }}">إضافة تغيير زيت</button>
                        <button class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#addOilReadingModal{{ $car->id }}">إضافة قراءة</button>
                    </div>
                </div>
            </div>

            <!-- مودال إضافة تغيير الزيت -->
            <div class="modal fade" id="addOilChangeModal{{ $car->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        @include('car_change_oils.form_add_oil', [
                            'car_id' => $car->id,
                            'date' => date('Y-m-d'),
                        ])
                    </div>
                </div>
            </div>

            <!-- مودال إضافة قراءة -->
            <div class="modal fade" id="addOilReadingModal{{ $car->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        @include('car_change_oils.form_add_oil', ['car_id' => $car->id, 'date' => null])
                    </div>
                </div>
            </div>

            <!-- مودال التفاصيل -->
            <div class="modal fade" id="detailsModal{{ $car->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">تفاصيل السيارة: {{ $car->type_car }} - {{ $car->number }}</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>الباقي كيلومترات</th>
                                        <th>عدد الكيلومترات للزيت</th>
                                        <th>تاريخ اخر غيار زيت</th>
                                        <th>عداد الكيلومترات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{ $KilometersLeftUntilOilChange }}
                                            @if ($KilometersLeftUntilOilChange == 0)
                                                <span class="text-success">(تم تغيير الزيت)</span>
                                            @elseif ($KilometersLeftUntilOilChange <= 500 && $KilometersLeftUntilOilChange > 0)
                                                <span class="text-warning">(اقترب موعد تغيير الزيت)</span>
                                            @elseif ($KilometersLeftUntilOilChange < 0)
                                                <span class="text-danger">(يجب التغيير فوراً)</span>
                                            @endif
                                        </td>
                                        <td>{{ $car->oil_change_number }}</td>
                                        <td>{{ $lastOilChange ? $lastOilChange->date : 'لا يوجد بيانات' }}</td>
                                        <td>{{ $lastOilChange ? $lastOilChange->km_before : 0 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
