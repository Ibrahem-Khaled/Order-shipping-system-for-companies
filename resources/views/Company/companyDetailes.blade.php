@extends('layouts.default')

@section('content')
    @php
        $deposit = $container->sum('price');
        $carSum = $cars->sum('price');
        $withdraw = $carSum + $employeeSum + $elbancherSum + $othersSum;
    @endphp

    <section class="lis-bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 text-center">
                    <div class="heading pb-4">
                        <h2>شركة الأمجاد المتعددة</h2>
                    </div>
                </div>
            </div>
            <div class="row" style="display: flex; justify-content: space-around">
                <div class="col-12 col-md-6 col-lg-4 wow fadeInUp text-center d-flex justify-content-center align-items-center"
                    style="visibility: visible; animation-name: fadeInUp;">
                    <div class="price-table w-100">
                        <div class="price-header lis-bg-light lis-rounded-top py-4 border border-bottom-0 lis-brd-light">
                            <h5 class="text-uppercase lis-latter-spacing-2">الشركاء</h5>
                            <h1 class="display-4 lis-font-weight-500">عدد الشركاء {{ $partner->count() }}</h1>
                            <p class="mb-0">هنا تفاصيل الشركاء وارباح الشركاء</p>
                        </div>
                        <div class="border border-top-0 lis-brd-light bg-white py-5 lis-rounded-bottom text-center">
                            <a href="{{ route('partnerHome') }}"
                                class="btn btn-primary btn-md rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="height: 70px; width: 80px; font-size: 100%;" data-abc="true">
                                <i class="fa fa-info-circle pl-2"></i> التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 wow fadeInUp mb-5 mb-lg-0 text-center d-flex justify-content-center align-items-center"
                    style="visibility: visible; animation-name: fadeInUp;">
                    <div class="price-table w-100">
                        <div class="price-header lis-bg-light lis-rounded-top py-4 border border-bottom-0 lis-brd-light">
                            <h5 class="text-uppercase lis-latter-spacing-2">حسابات الشركة</h5>
                            <h1 class="display-4 lis-font-weight-500">{{ strval($deposit) - strval($withdraw) }}$</h1>
                            <p class="mb-0">هنا تفاصيل ارباح الشركة</p>
                        </div>
                        <div class="border border-top-0 lis-brd-light bg-white py-5 lis-rounded-bottom text-center">
                            <a href="{{ route('companyRevExp') }}"
                                class="btn btn-primary btn-md rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="height: 70px; width: 80px; font-size: 100%;" data-abc="true">
                                <i class="fa fa-info-circle pl-2"></i> التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    </section>


@stop
