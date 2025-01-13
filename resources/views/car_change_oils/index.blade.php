@extends('layouts.default')

@section('content')
    <div class="container text-right">
        <h1>تفاصيل تغيير الزيت للسيارات</h1>

        <!-- زر لفتح مودال إضافة البيانات -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addOilChangeModal">
            إضافة بيانات تغيير الزيت
        </button>
        <button class="btn btn-warning mb-3" data-toggle="modal" data-target="#addOilRiteModal">
            اضافة القراءات
        </button>

        @foreach ($cars as $car)
            <div class="card my-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>السيارة: {{ $car->type_car }} - الرقم: {{ $car->number }}</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#detailsModal{{ $car->id }}">
                        عرض التفاصيل
                    </button>
                </div>
            </div>

            <!-- مودال التفاصيل -->
            <div class="modal fade" id="detailsModal{{ $car->id }}" tabindex="-1" role="dialog"
                aria-labelledby="detailsModalLabel{{ $car->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel{{ $car->id }}">
                                تفاصيل تغيير الزيت للسيارة: {{ $car->type_car }} - {{ $car->number }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>الباقي كيلومترات علي غيار الزيت</th>
                                        <th>عدد الكيلومترات للزيت</th>
                                        <th>تاريخ اخر غيار زيت</th>
                                        <th>عداد الكيلومترات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $lastOilChange = $car->oilChanges->last(); // جلب آخر تغيير زيت
                                        $KilometersLeftUntilOilChange = $lastOilChange
                                            ? $car->oil_change_number - $lastOilChange->km_after
                                            : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $KilometersLeftUntilOilChange }}

                                            @if ($KilometersLeftUntilOilChange == 0)
                                            <span class="text-success">(تم تغيير الزيت)</span>
                                            @elseif ($KilometersLeftUntilOilChange <= 500)
                                            <span class="text-warning">(اقترب موعد تغيير الزيت)</span>
                                            @elseif($KilometersLeftUntilOilChange <= 1)
                                            <span class="text-danger">(يجب تغيير الزيت فورًا)</span>
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

        <!-- مودال إضافة البيانات -->
        <div class="modal fade" id="addOilChangeModal" tabindex="-1" role="dialog"
            aria-labelledby="addOilChangeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @include('car_change_oils.form_add_oil', ['cars' => $cars, 'date' => date('Y-m-d')])
                </div>
            </div>
        </div>
        <div class="modal fade" id="addOilRiteModal" tabindex="-1" role="dialog" aria-labelledby="addOilRiteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @include('car_change_oils.form_add_oil', ['cars' => $cars, 'date' => null])
                </div>
            </div>
        </div>
    </div>
@endsection
