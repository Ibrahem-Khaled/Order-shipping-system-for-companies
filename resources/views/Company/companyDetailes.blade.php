@extends('layouts.default')

@section('content')
    @php
        $deposit = $container->sum('price');
        $carSum = $cars->sum('price');
        $employeeTip = $employeeTips->sum('tips');
        $withdraw = $carSum + $employeeSum + $employeeTip + $elbancherSum + $othersSum;
    @endphp

    <section class="lis-bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 text-center">
                    <div class="heading pb-4">
                        <h2>اختر العملية</h2>
                        <h5 class="font-weight-normal lis-light">استكشف &amp; connect with top-rated local businesses</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 wow fadeInUp text-center"
                    style="visibility: visible; animation-name: fadeInUp;">
                    <div class="price-table">
                        <div class="price-header lis-bg-light lis-rounded-top py-4 border border-bottom-0 lis-brd-light">
                            <h5 class="text-uppercase lis-latter-spacing-2">الشركاء</h5>
                            <h1 class="display-4 lis-font-weight-500">عدد الشركاء {{ $partner->count() }}</h1>
                            <p class="mb-0">Basic User Membership</p>
                        </div>
                        <div class="border border-top-0 lis-brd-light bg-white py-5 lis-rounded-bottom">

                            <a href="{{ route('partnerHome') }}"
                                class="btn btn-primary-outline btn-md lis-rounded-circle-50" data-abc="true"><i
                                    class="fa fa  fa-info-circle pl-2"></i> التفاصيل </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 wow fadeInUp mb-5 mb-lg-0 text-center"
                    style="visibility: visible; animation-name: fadeInUp;">
                    <div class="price-table">
                        <div class="price-header lis-bg-light lis-rounded-top py-4 border border-bottom-0 lis-brd-light">
                            <h5 class="text-uppercase lis-latter-spacing-2">حسابات الشركة</h5>
                            <h1 class="display-4 lis-font-weight-500">{{ strval($deposit) - strval($withdraw) }}$</h1>
                            <p class="mb-0">Basic User Membership</p>
                        </div>
                        <div class="border border-top-0 lis-brd-light bg-white py-5 lis-rounded-bottom">
                            <a href="{{ route('companyRevExp') }}"
                                class="btn btn-primary-outline btn-md lis-rounded-circle-50" data-abc="true"><i
                                    class="fa  fa-info-circle pl-2"></i> التفاصيل </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


@stop
