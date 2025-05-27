@extends('layouts.default')

@section('content')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .modern-section {
            position: relative;
            overflow: hidden;
            padding: 5rem 0;
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .company-title {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #fff, #d1d1d1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
        }

        .company-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--accent-color), transparent);
            border-radius: 2px;
        }

        .modern-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modern-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
        }

        .card-title {
            font-size: 1.2rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .card-value {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 1rem 0;
            background: linear-gradient(to right, #fff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-desc {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .modern-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            background: white;
            border-radius: 50px;
            text-decoration: none;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 1;
            border: none;
        }

        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, var(--accent-color), var(--primary-color));
            z-index: -1;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .modern-btn:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        }

        .modern-btn:hover::before {
            opacity: 1;
        }

        .modern-btn i {
            margin-left: 8px;
            transition: all 0.3s ease;
        }

        .modern-btn:hover i {
            transform: translateX(5px);
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            filter: blur(30px);
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--accent-color);
            top: -100px;
            right: -100px;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: white;
            bottom: -50px;
            left: -50px;
        }

        @media (max-width: 768px) {
            .company-title {
                font-size: 2.5rem;
            }

            .card-value {
                font-size: 2.5rem;
            }
        }
    </style>

    <section class="modern-section gradient-bg">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>

        <div class="container position-relative">
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center">
                    <h1 class="company-title mb-4">شركة الأمجاد المتعددة</h1>
                    <p class="text-white-50 mb-0" style="font-size: 1.2rem;">نحو مستقبل مالي أكثر ازدهاراً</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="card-header">
                            <h5 class="card-title">الشركاء</h5>
                            <h1 class="card-value">{{ $partner->count() }}</h1>
                            <p class="card-desc">إدارة شؤون الشركاء والأرباح</p>
                        </div>
                        <div class="card-body py-4">
                            <a href="{{ route('partnerHome') }}" class="modern-btn">
                                عرض التفاصيل <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="card-header">
                            <h5 class="card-title">حسابات الشركة</h5>
                            <h1 class="card-value">{{ strval($deposit) - strval($withdraw) }}$</h1>
                            <p class="card-desc">إيرادات ومصروفات الشركة</p>
                        </div>
                        <div class="card-body py-4">
                            <a href="{{ route('companyRevExp') }}" class="modern-btn">
                                عرض التفاصيل <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- يمكن إضافة تأثيرات الجسيمات باستخدام مكتبة مثل particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // يمكنك تفعيل تأثير الجسيمات إذا أردت

        particlesJS('particles', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle"
                },
                "opacity": {
                    "value": 0.3,
                    "random": true
                },
                "size": {
                    "value": 3,
                    "random": true
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.2,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out"
                }
            },
            "interactivity": {
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    }
                }
            }
        });
    </script>
@stop
