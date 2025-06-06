@extends('layouts.default')

@section('content')
    <div class="min-vh-100 d-flex align-items-center">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <!-- Animated Header -->
                    <h1 class="display-3 fw-bold text-dark mb-4 animate__animated animate__fadeInDown">
                        <span class="text-info">نظام</span>
                        <span class="text-danger">الجمارك</span>
                        <span class="text-success">الذكي</span>
                    </h1>

                    <!-- Subheading -->
                    <p class="lead text-dark mb-5 animate__animated animate__fadeInUp animate__delay-1s">
                        اكتشف الجيل القادم من الحلول الذكية لتبسيط العمليات الجمركية
                    </p>
                </div>
            </div>

            <!-- Animated Icons Grid -->
            <div class="row g-4 mb-5">
                <!-- Icon 1 - Add Customs Declaration -->
                <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="card bg-white-10 text-dark border-white-20 h-100 hover-border-info transition-all">
                        <div class="card-body text-center p-4">
                            <div class="icon-container position-relative mx-auto mb-4" style="width: 200px; height: 200px;">
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 bg-info bg-opacity-20 rounded-circle blur">
                                </div>
                                <div class="position-relative h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-text fs-1 text-info icon-hover"></i>
                                </div>
                            </div>
                            <h3 class="h2 fw-semibold mb-3">إضافة بيان جمركي</h3>
                            <p class="text-dark">قم بإضافة بيانات جمركية جديدة للنظام بسهولة وسرعة</p>
                        </div>
                    </div>
                </div>

                <!-- Icon 2 - Add Empty Appointment -->
                <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="card bg-white-10 text-dark border-white-20 h-100 hover-border-purple transition-all">
                        <div class="card-body text-center p-4">
                            <div class="icon-container position-relative mx-auto mb-4" style="width: 200px; height: 200px;">
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 bg-purple bg-opacity-20 rounded-circle blur">
                                </div>
                                <div class="position-relative h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar-plus fs-1 text-purple icon-hover"></i>
                                </div>
                            </div>
                            <h3 class="h2 fw-semibold mb-3">إضافة موعد فارغ</h3>
                            <p class="text-dark">إنشاء مواعيد جديدة فارغة للتعامل مع الطلبات المستقبلية</p>
                        </div>
                    </div>
                </div>

                <!-- Icon 3 - Add Loading Appointment -->
                <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="card bg-white-10 text-dark border-white-20 h-100 hover-border-pink transition-all">
                        <div class="card-body text-center p-4">
                            <div class="icon-container position-relative mx-auto mb-4" style="width: 200px; height: 200px;">
                                <div
                                    class="position-absolute top-0 start-0 w-100 h-100 bg-pink bg-opacity-20 rounded-circle blur">
                                </div>
                                <div class="position-relative h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-truck fs-1 text-pink icon-hover"></i>
                                </div>
                            </div>
                            <h3 class="h2 fw-semibold mb-3">إضافة موعد تحميل</h3>
                            <p class="text-dark">تحديد مواعيد جديدة لتحميل البضائع وتنظيم العمليات اللوجستية</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Add these styles to your CSS file or style tag -->
    <style>
        /* Custom background opacity utilities */
        .bg-white-10 {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .border-white-20 {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        .text-dark {
            color: rgba(0, 0, 0, 1);
        }

        /* Hover effects */
        .hover-border-info:hover {
            border-color: rgba(13, 202, 240, 0.5) !important;
        }

        .hover-border-purple:hover {
            border-color: rgba(111, 66, 193, 0.5) !important;
        }

        .hover-border-pink:hover {
            border-color: rgba(214, 51, 132, 0.5) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Icon hover effects */
        .icon-hover {
            transition: transform 0.3s ease;
        }

        .card:hover .icon-hover {
            transform: scale(1.1);
        }

        /* Blur effect */
        .blur {
            filter: blur(8px);
        }

        /* Custom colors */
        .bg-purple {
            background-color: #6f42c1;
        }

        .text-purple {
            color: #9d7af1;
        }

        .text-pink {
            color: #e685b5;
        }

        /* Float animation for cards */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        /* Add delay to nth-child cards */
        .row.g-4 .col-md-4:nth-child(1) {
            animation-delay: 0.5s;
        }

        .row.g-4 .col-md-4:nth-child(2) {
            animation-delay: 1s;
        }

        .row.g-4 .col-md-4:nth-child(3) {
            animation-delay: 1.5s;
        }

        /* RTL direction for Arabic */
        body[dir="rtl"] .bi-arrow-left {
            transform: scaleX(-1);
        }
    </style>

    <!-- Include Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Include Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
@endsection
