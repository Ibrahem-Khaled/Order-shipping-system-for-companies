@extends('layouts.default')

@section('content')
    <div class="container-fluid py-5 accounting-dashboard">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="dashboard-header text-center p-4 bg-gradient-primary rounded shadow">
                    <h1 class="display-4 text-white mb-3">
                        <i class="fas fa-calculator me-2"></i> نظام المحاسبة المالية
                    </h1>
                    <p class="lead text-white-50 mb-0">إدارة كافة العمليات المالية والمصروفات والإيرادات في مكان واحد</p>
                </div>
            </div>
        </div>

        <!-- Accounting Cards Section -->
        <div class="row justify-content-center">
            <!-- Expenses Card -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card accounting-card bg-gradient-danger h-100" data-toggle="modal"
                    data-target="#expensesModal">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-money-bill-wave fa-4x text-white"></i>
                        </div>
                        <h3 class="card-title text-white mb-3">المصروفات</h3>
                        <div class="stats-badge bg-white text-danger rounded-pill px-3 py-1">
                            <i class="fas fa-chart-line me-2"></i> 5 أقسام
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <span class="text-muted">انقر لعرض الخيارات</span>
                    </div>
                </div>
            </div>

            <!-- Revenues Card -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card accounting-card bg-gradient-success h-100" data-toggle="modal"
                    data-target="#revenuesModal">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-hand-holding-usd fa-4x text-white"></i>
                        </div>
                        <h3 class="card-title text-white mb-3">الإيرادات</h3>
                        <div class="stats-badge bg-white text-success rounded-pill px-3 py-1">
                            <i class="fas fa-chart-pie me-2"></i> 3 أقسام
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <span class="text-muted">انقر لعرض الخيارات</span>
                    </div>
                </div>
            </div>

            <!-- Rent Card -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card accounting-card bg-gradient-info h-100" data-toggle="modal" data-target="#rentModal">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-building fa-4x text-white"></i>
                        </div>
                        <h3 class="card-title text-white mb-3">الإيجارات</h3>
                        <div class="stats-badge bg-white text-info rounded-pill px-3 py-1">
                            <i class="fas fa-home me-2"></i> قسم واحد
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <span class="text-muted">انقر لعرض الخيارات</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-history me-2"></i> النشاط الأخير</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>النوع</th>
                                        <th>الوصف</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><span class="badge bg-danger">مصروف</span></td>
                                        <td>صيانة السيارة</td>
                                        <td class="text-danger">1,250 ر.س</td>
                                        <td>2023-10-15</td>
                                        <td><span class="badge bg-success">مكتمل</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><span class="badge bg-success">إيراد</span></td>
                                        <td>دفعة من العميل</td>
                                        <td class="text-success">5,750 ر.س</td>
                                        <td>2023-10-14</td>
                                        <td><span class="badge bg-success">مكتمل</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><span class="badge bg-info">إيجار</span></td>
                                        <td>إيجار المكتب</td>
                                        <td class="text-info">3,000 ر.س</td>
                                        <td>2023-10-10</td>
                                        <td><span class="badge bg-warning">قيد الانتظار</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Modal -->
    <div class="modal fade" id="expensesModal" tabindex="-1" aria-labelledby="expensesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-danger text-white">
                    <h5 class="modal-title" id="expensesModalLabel">
                        <i class="fas fa-money-bill-wave me-2"></i> إدارة المصروفات
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('expensesCarsData') }}" class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-car fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">كشف حساب السيارات</h5>
                                    <p class="card-text text-muted">عرض مصروفات الوقود والصيانة</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('expensesSallaryeEmployee') }}"
                                class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">المرتبات</h5>
                                    <p class="card-text text-muted">إدارة رواتب الموظفين</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('expensesSallaryAlbancher') }}"
                                class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-tie fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">البنشري</h5>
                                    <p class="card-text text-muted">إدارة رواتب البنشري</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('expensesOthers') }}" class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">كشوف أخرى</h5>
                                    <p class="card-text text-muted">المصروفات الإدارية والنثرية</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-2"></i> إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenues Modal -->
    <div class="modal fade" id="revenuesModal" tabindex="-1" aria-labelledby="revenuesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-success text-white">
                    <h5 class="modal-title" id="revenuesModalLabel">
                        <i class="fas fa-hand-holding-usd me-2"></i> إدارة الإيرادات
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('getRevenuesClient') }}"
                                class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-building fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">مكتب تخليص جمركي</h5>
                                    <p class="card-text text-muted">كشف حساب العملاء</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('sell.buy') }}" class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-exchange-alt fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">حركة البيع وشراء</h5>
                                    <p class="card-text text-muted">إدارة عمليات البيع والشراء</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="#" class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-ellipsis-h fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">أخرى</h5>
                                    <p class="card-text text-muted">إيرادات متنوعة</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-2"></i> إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rent Modal -->
    <div class="modal fade" id="rentModal" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-info text-white">
                    <h5 class="modal-title" id="rentModalLabel">
                        <i class="fas fa-building me-2"></i> إدارة الإيجارات
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="{{ route('getOfficesRent') }}" class="card option-card h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-home fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">مكاتب الإيجار</h5>
                                    <p class="card-text text-muted">كشف حساب المكاتب المستأجرة</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-2"></i> إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .accounting-dashboard {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border-radius: 15px;
        }

        .accounting-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .accounting-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .accounting-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
            z-index: 1;
        }

        .accounting-card .icon-wrapper {
            transition: all 0.3s ease;
        }

        .accounting-card:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .stats-badge {
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .option-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .option-card i {
            transition: all 0.3s ease;
        }

        .option-card:hover i {
            transform: scale(1.1);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        }

        @media (max-width: 768px) {
            .dashboard-header h1 {
                font-size: 2rem;
            }

            .accounting-card {
                margin-bottom: 20px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Add animation to cards when modal opens
            $('.modal').on('show.modal', function() {
                $(this).find('.option-card').each(function(index) {
                    $(this).css({
                        'opacity': 0,
                        'transform': 'translateY(20px)'
                    }).delay(100 * index).animate({
                        'opacity': 1,
                        'transform': 'translateY(0)'
                    }, 300);
                });
            });
        });
    </script>
@endsection
