@extends('layouts.default')

@section('content')
    <style>
        .heading h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 1rem;
        }

        .card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #f8f9fa;
            border-radius: 15px 15px 0 0;
            padding: 2rem;
        }

        .card-header h5 {
            font-size: 1.25rem;
            letter-spacing: 0.1rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .card-header h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-header p {
            font-size: 1rem;
            color: #6c757d;
        }

        .btn {
            position: relative;
            display: inline-block;
            padding: 10px 20px;
            font-size: 1.5rem;
            color: #fff;
            text-transform: uppercase;
            text-decoration: none;
            background-color: #007bff;
            border-radius: 50px;
            transition: all 0.4s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            box-shadow: 0 0 10px #007bff, 0 0 20px #007bff, 0 0 30px #007bff;
        }

        .btn::before,
        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            transition: all 0.4s ease;
            transform: translate(-50%, -50%) scale(0.5);
            opacity: 0;
            z-index: -1;
        }

        .btn:hover::before,
        .btn:hover::after {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .btn i {
            margin-right: 0.5rem;
        }
    </style>

    <section class="py-5" style="border-radius: 10px">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-md-10 text-center">
                    <div class="heading pb-4">
                        <h2 class="text-white">شركة الأمجاد المتعددة</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header bg-light py-4 border-bottom">
                            <h5 class="text-uppercase letter-spacing-2">الشركاء</h5>
                            <h1 class="display-4 font-weight-bold text-primary">عدد الشركاء {{ $partner->count() }}</h1>
                            <p class="mb-0">هنا تفاصيل الشركاء وارباح الشركاء</p>
                        </div>
                        <div class="card-body py-5">
                            <a href="{{ route('partnerHome') }}"
                                class="btn btn-primary btn-lg rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="height: 70px; width: 80px; font-size: 1.5rem;">
                                <i class="fa fa-info-circle pl-2"></i> التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card text-center shadow-sm">
                        <div class="card-header bg-light py-4 border-bottom">
                            <h5 class="text-uppercase letter-spacing-2">حسابات الشركة</h5>
                            <h1 class="display-4 font-weight-bold">{{ strval($deposit) - strval($withdraw) }}$</h1>
                            <p class="mb-0">هنا تفاصيل ارباح الشركة</p>
                        </div>
                        <div class="card-body py-5">
                            <a href="{{ route('companyRevExp') }}"
                                class="btn btn-primary btn-lg rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="height: 70px; width: 80px; font-size: 1.5rem;">
                                <i class="fa fa-info-circle pl-2"></i> التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
