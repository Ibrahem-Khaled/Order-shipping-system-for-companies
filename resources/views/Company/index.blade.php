@extends('layouts.default')

@section('content')

    <div class="container-fluid py-4">
        @if (auth()->user()->role == 'superAdmin')
            <div class="row">
                <x-stat-card :count="$clintPriceMinesContainer" :title="'باقي حساب العملاء'" :color="'info'" :icon="'ni ni-money-coins'" />
                <x-stat-card :count="number_format($canCashWithdraw, 2)" :title="'النقدية بنك او كاش'" :color="'primary'" :icon="'ni ni-world'" />
                <x-stat-card :count="$container->whereIn('status', ['wait', 'rent'])->count()" :title="'حاويات الانتظار '" :color="'warning'" :icon="'ni ni-world'" />
                <x-stat-card :count="strval($deposits) - strval($withdraws)" :title="'اجمالي الايرادات'" :color="'success'" :icon="'ni ni-world'" />
            </div>
        @endif

        <div class="row mt-4">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <p class="mb-1 pt-2 text-bold">الادارة العامة</p>
                                    <h5 class="font-weight-bolder">الادارة العامة للشركة</h5>
                                    <p class="mb-5 fw-bold">الإدارة العامة للشركة هي القسم الذي يتحمل مسؤولية تنظيم وإدارة
                                        العمليات اليومية والوظائف الإدارية الأساسية للشركة بشكل عام. تشمل مهام الإدارة
                                        العامة عدة جوانب من الإدارة والتخطيط والتنظيم والرقابة، بهدف تحقيق أهداف الشركة
                                        وضمان سير العمل بفاعلية وكفاءة.</p>
                                    <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto"
                                        href="javascript:;">
                                        المزيد
                                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('companyDetailes') }}" class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                                <div class="bg-gradient-primary border-radius-lg h-50 m-5">
                                    <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                        <h4 class="text-white">شركة الأمجاد المتعددة</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 p-3">
                    <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100"
                        style="background-image: url('../assets/img/ivancik.jpg');">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-xxs font-weight-bolder text-end">نوع السطحة
                                    </th>
                                    <th class="text-uppercase text-xxs font-weight-bolder text-end">20</th>
                                    <th class="text-uppercase text-xxs font-weight-bolder text-end">40</th>
                                    <th class="text-uppercase text-xxs font-weight-bolder text-end">إجمالي</th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- إجمالي السطحات الفارغة -->
                                <tr>
                                    <td class="align-middle text-end text-sm">إجمالي السطحات الفارغة</td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">
                                            {{ $flatbeds->where('status', 1)->where('type', '20')->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">
                                            {{ $flatbeds->where('status', 1)->where('type', '40')->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">{{ $flatbeds->where('status', 1)->count() }}</span>
                                    </td>
                                </tr>

                                <!-- إجمالي السطحات المحملة -->
                                <tr>
                                    <td class="align-middle text-end text-sm">إجمالي السطحات المحملة</td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">
                                            {{ $flatbeds->where('status', 0)->where('type', '20')->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">
                                            {{ $flatbeds->where('status', 0)->where('type', '40')->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">{{ $flatbeds->where('status', 0)->count() }}</span>
                                    </td>
                                </tr>

                                <!-- إجمالي السطحات الكلية -->
                                <tr>
                                    <td class="align-middle text-end text-sm">إجمالي السطحات الكلية</td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">
                                            {{ $flatbeds->where('type', '20')->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">
                                            {{ $flatbeds->where('type', '40')->count() }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end text-lg">
                                        <span class="text-xxl fw-bold">{{ $flatbeds->count() }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="row mt-4">

            <div class="col-lg-7">
                <div class="card z-index-2">
                    <div class="card-header pb-0"
                        style="display: flex; justify-content: space-around; align-items: center;">
                        @php
                            $sumContainerInvaildPrice = 0;
                            foreach ($allCustoms as $key => $value) {
                                $sumContainerInvaildPrice += $value->container->where('price', '<=', 0)->count();
                            }
                        @endphp
                        <h6></h6>
                        <h6></h6>
                        <h6></h6>
                        <h6></h6>
                        <p><strong>{{ $sumContainerInvaildPrice }}</strong></p>
                        <h6>عدد الحاويات الغير مسعرة</h6>
                    </div>

                    <div class="card-body p-3">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>اسم المكتب</th>
                                        <th>عدد الحاويات غير مسعرة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allCustoms as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->container->where('price', '<=', 0)->count() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Keeping the chart below the table -->
                        <div class="chart mt-4">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @stop
