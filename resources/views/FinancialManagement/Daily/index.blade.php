@extends('layouts.default')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap');

        * {
            font-family: 'Cairo', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
            border-radius: 20px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .hero-title {
            color: white;
            font-weight: 700;
            font-size: 2.5rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        .action-buttons {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modern-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 12px 24px;
            color: white;
            font-weight: 600;
            margin: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .modern-btn:hover::before {
            left: 100%;
        }

        .modern-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        .btn-success-modern {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
        }

        .btn-danger-modern {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
        }

        .btn-info-modern {
            background: linear-gradient(45deg, #2196F3, #21CBF3);
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 25px;
            padding: 5px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            margin: 0.5rem;
        }

        .search-input {
            border: none;
            background: transparent;
            padding: 10px 20px;
            border-radius: 20px;
            outline: none;
            flex: 1;
            font-size: 1rem;
        }

        .search-btn {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            color: white;
            margin-left: 5px;
        }

        .data-table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 2rem;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modern-table th {
            padding: 1.5rem 1rem;
            font-weight: 600;
            text-align: center;
            border: none;
            position: relative;
        }

        .modern-table th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 2px;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modern-table td {
            padding: 1.2rem 1rem;
            text-align: center;
            vertical-align: middle;
            border: none;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 2rem;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
            background: #f8f9fa;
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        .action-btn {
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            border: none;
            margin: 2px;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            color: white;
        }

        .status-badge {
            background: linear-gradient(45deg, #ffd89b, #19547b);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .amount-positive {
            color: #11998e;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .amount-negative {
            color: #ff416c;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .floating-action {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .floating-action:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .orders-modal-table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-close {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: white;
            font-size: 1.2rem;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
            background: rgba(255, 255, 255, 0.3);
        }
    </style>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Hero Section -->
                <div class="hero-section fade-in-up">
                    <div class="text-center">
                        <h1 class="hero-title">الحركات اليومية</h1>
                        <p class="text-white" style="font-size: 1.2rem; opacity: 0.9; position: relative; z-index: 2;">إدارة
                            شاملة للمعاملات المالية اليومية</p>
                    </div>
                </div>

                <!-- Action Buttons Section -->
                <div class="action-buttons fade-in-up">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="d-flex flex-wrap justify-content-center">
                                <button type="button" class="modern-btn btn-success-modern" data-toggle="modal"
                                    data-target="#editTips">
                                    <i class="fas fa-edit me-2"></i>تعديل سعر الترب للحاوية
                                </button>
                                <button type="button" class="modern-btn btn-danger-modern" data-toggle="modal"
                                    data-target="#priceTransfer">
                                    <i class="fas fa-plus-circle me-2"></i>اضافة سعر امر النقل
                                </button>
                                <button type="button" class="modern-btn btn-success-modern" data-toggle="modal"
                                    data-target="#addEmployeeModal">
                                    <i class="fas fa-user-plus me-2"></i>إضافة كشف حساب جديد
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="d-flex flex-wrap justify-content-center align-items-center">
                                <button type="button" class="modern-btn" data-toggle="modal" data-target="#addDailyModal">
                                    <i class="fas fa-coins me-2"></i>اضافة حركة مالية
                                </button>
                                <button type="button" class="modern-btn btn-info-modern" data-toggle="modal"
                                    data-target="#ordersModal">
                                    <i class="fas fa-list-alt me-2"></i>عرض أوامر النقل
                                </button>
                                <form action="{{ route('dailyManagement') }}" method="GET" class="search-container">
                                    <input type="text" name="query" class="search-input"
                                        placeholder="البحث في المعاملات...">
                                    <button type="submit" class="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="data-table-container fade-in-up">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th scope="col"><i class="fas fa-cogs me-2"></i>العمليات</th>
                                <th scope="col"><i class="fas fa-arrow-down me-2" style="color: #11998e;"></i>الوارد</th>
                                <th scope="col"><i class="fas fa-arrow-up me-2" style="color: #ff416c;"></i>المنصرف</th>
                                <th scope="col"><i class="fas fa-file-alt me-2"></i>البيان</th>
                                <th scope="col"><i class="fas fa-calendar me-2"></i>التاريخ</th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daily->whereNull('container_id') as $item)
                                <tr>
                                    <td>
                                        @if ($item->created_at != $item->updated_at)
                                            <span class="status-badge">معدل</span>
                                        @endif
                                        @if (!auth()->user()?->userinfo?->job_title == 'administrative')
                                            <button type="button" data-toggle="modal"
                                                data-target="#confirmationModal{{ $item->id }}"
                                                class="action-btn btn-delete">
                                                <i class="fas fa-trash"></i> مسح
                                            </button>
                                        @endif
                                        @if ($item->container_id == null)
                                            <button type="button" data-toggle="modal"
                                                data-target="#editModal{{ $item->id }}" class="action-btn btn-edit">
                                                <i class="fas fa-edit"></i> تعديل
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->type == 'deposit')
                                            <span class="amount-positive">{{ number_format($item->price) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->type == 'withdraw' || $item->type == 'partner_withdraw')
                                            <span class="amount-negative">{{ number_format($item->price) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="text-align: right;">
                                            @if ($item->car_id !== null)
                                                <strong>{{ $item->description }}</strong><br>
                                                <small class="text-muted">{{ $item?->car?->number }}</small>
                                            @elseif ($item->client_id !== null)
                                                <strong>{{ $item?->description }}</strong><br>
                                                <small class="text-muted">{{ $item?->client?->name }}</small>
                                            @elseif ($item->employee_id !== null)
                                                <strong>{{ $item->description }}</strong><br>
                                                <small class="text-muted">{{ $item?->emplyee?->name }}</small>
                                            @else
                                                <strong>{{ $item->description }}</strong>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            style="font-weight: 600;">{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $loop->iteration }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Floating Action Button -->
                <div class="floating-action" data-toggle="modal" data-target="#addDailyModal">
                    <i class="fas fa-plus"></i>
                </div>

                <!-- All Modals (keeping original structure with updated styling) -->

                <!-- Modal for Edit Tips -->
                <div class="modal fade" id="editTips" tabindex="-1" role="dialog" aria-labelledby="editTipsLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTipsLabel">تعديل سعر الترب</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('editContanierTips') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="containerNumber" class="form-label">رقم الحاوية:</label>
                                        <input type="text" id="containerNumber" name="number" required
                                            class="form-control" placeholder="رقم الحاوية">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tarbPrice" class="form-label">سعر الترب:</label>
                                        <input type="text" id="tarbPrice" name="tips" required
                                            class="form-control" placeholder="سعر الترب">
                                    </div>
                                    <button type="submit" class="modern-btn">حفظ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Price Transfer -->
                <div class="modal fade" id="priceTransfer" tabindex="-1" role="dialog"
                    aria-labelledby="priceTransferLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="priceTransferLabel">اضافة سعر امر النقل</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('addContanierPriceTransfer') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="containerNumber" class="form-label">رقم الحاوية:</label>
                                        <input type="text" id="containerNumber" name="number" required
                                            class="form-control" placeholder="رقم الحاوية">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tarbPrice" class="form-label">سعر:</label>
                                        <input type="text" id="tarbPrice" name="price" required
                                            class="form-control" placeholder="سعر">
                                    </div>
                                    <button type="submit" class="modern-btn">حفظ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Add Employee -->
                <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog"
                    aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addEmployeeModalLabel">اضافة كشف حساب</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('addOtherStateMent') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" name="name" required class="form-control"
                                            placeholder="الاسم">
                                    </div>
                                    <button type="submit" class="modern-btn">حفظ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Add Daily -->
                <div class="modal fade" id="addDailyModal" tabindex="-1" role="dialog"
                    aria-labelledby="addDailyModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addDailyModalLabel">اضافة حركة مالية جديد</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('postDailyData') }}" id="myForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" name="description" required class="form-control"
                                            placeholder="الوصف">
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="price" required class="form-control"
                                            placeholder="المبلغ">
                                    </div>
                                    <div class="mb-3">
                                        <select name="type" id="type" required class="form-control">
                                            <option value="">نوع الحركة</option>
                                            <option value="deposit">وارد</option>
                                            <option value="withdraw">منصرف</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 toggle-field" id="carField">
                                        <select name="car_id" class="form-control">
                                            <option value="">اختيار السيارة</option>
                                            @foreach ($cars as $item)
                                                <option value="{{ $item->id }}">{{ $item->number }} -
                                                    {{ $item->driver?->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 toggle-field" id="clientField">
                                        <select name="client_id" class="form-control">
                                            <option value="">اختيار حساب العميل</option>
                                            @foreach ($client as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 toggle-field" id="employeeField">
                                        <select name="employee_id" class="form-control">
                                            <option value="">اختيار الحساب</option>
                                            @foreach ($employee as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->role == 'company' ? 'مصاريف نثرية وادارية' : $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 toggle-field" id="partnerField">
                                        <select name="partner_id" class="form-control">
                                            <option value="">اختيار حساب الشريك</option>
                                            @foreach ($partner as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="created_at">تاريخ الإنشاء:</label>
                                        <input class="form-control" type="date" id="created_at" name="created_at">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">إغلاق</button>
                                        <button type="submit" class="modern-btn">حفظ</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مودال لعرض اوامر النقل -->
                <div class="modal fade" id="ordersModal" tabindex="-1" role="dialog"
                    aria-labelledby="ordersModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ordersModalLabel">أوامر النقل</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="orders-modal-table">
                                    <table class="table table-striped modern-table">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">سعر امر النقل</th>
                                                <th scope="col">اسم العميل</th>
                                                <th scope="col">اسم المكتب</th>
                                                <th scope="col">رقم الحاوية</th>
                                                <th scope="col">البيان</th>
                                                <th scope="col">التاريخ</th>
                                                <th scope="col">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($daily as $item)
                                                @if ($item->container_id !== null)
                                                    <tr>
                                                        <td>{{ number_format($item->price) }}</td>
                                                        <td>{{ $item?->container?->customs?->importer_name }}</td>
                                                        <td>{{ $item->client?->name }}</td>
                                                        <td>{{ $item->container?->number }}</td>
                                                        <td>{{ $item?->container?->customs?->statement_number }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                                        </td>
                                                        <td>{{ $loop->iteration }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation and Edit Modals for each item -->
                @foreach ($daily->whereNull('container_id') as $item)
                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">تأكيد العملية</h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body text-center"
                                    style="font-family: 'Cairo', sans-serif; font-weight: bold;">
                                    <p>هل تريد الغاء ذلك البيان</p>
                                </div>
                                <form method="POST" action="{{ route('deleteDailyData', $item->id) }}">
                                    @csrf
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-danger">تأكيد</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    @if ($item->container_id == null)
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">تعديل</h5>
                                        <button type="button" class="btn-close" data-dismiss="modal"
                                            aria-label="Close">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('updateDailyData', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="text" name="description" required class="form-control"
                                                    value="{{ $item->description }}" placeholder="الوصف">
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" name="price" required class="form-control"
                                                    value="{{ $item->price }}" placeholder="المبلغ">
                                            </div>
                                            <div class="mb-3">
                                                <select name="type" required class="form-control">
                                                    <option value="">نوع الحركة</option>
                                                    <option value="deposit"
                                                        {{ $item->type == 'deposit' ? 'selected' : '' }}>وارد</option>
                                                    <option value="withdraw"
                                                        {{ $item->type == 'withdraw' ? 'selected' : '' }}>منصرف</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <select name="car_id" class="form-control">
                                                    <option value="">اختيار السيارة</option>
                                                    @foreach ($cars as $items)
                                                        <option value="{{ $items->id }}"
                                                            {{ $item->car_id == $items->id ? 'selected' : '' }}>
                                                            {{ $items->number }} - {{ $items->driver?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <select name="client_id" class="form-control">
                                                    <option value="">اختيار حساب العميل</option>
                                                    @foreach ($client as $items)
                                                        <option value="{{ $items->id }}"
                                                            {{ $item->client_id == $items->id ? 'selected' : '' }}>
                                                            {{ $items->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <select name="employee_id" class="form-control">
                                                    <option value="">اختيار الحساب</option>
                                                    @foreach ($employee as $items)
                                                        <option value="{{ $items->id }}"
                                                            {{ $item->employee_id == $items->id ? 'selected' : '' }}>
                                                            {{ $items->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="modern-btn">عدل</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">اغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const carField = document.getElementById('carField');
            const clientField = document.getElementById('clientField');
            const employeeField = document.getElementById('employeeField');
            const partnerField = document.getElementById('partnerField');

            function toggleFields() {
                const selectedType = typeSelect.value;

                if (selectedType === 'withdraw') {
                    carField.style.display = 'block';
                    employeeField.style.display = 'block';
                    partnerField.style.display = 'block';
                    clientField.style.display = 'none';

                    const fields = [carField, employeeField, partnerField];
                    fields.forEach(field => {
                        field.querySelector('select').disabled = false;
                        field.querySelector('select').addEventListener('change', function() {
                            fields.forEach(otherField => {
                                if (otherField !== field) {
                                    otherField.querySelector('select').disabled = true;
                                    otherField.querySelector('select').value = '';
                                }
                            });
                        });
                    });

                } else if (selectedType === 'deposit') {
                    clientField.style.display = 'block';
                    carField.style.display = 'none';
                    employeeField.style.display = 'none';
                    partnerField.style.display = 'none';
                    carField.querySelector('select').disabled = true;
                    employeeField.querySelector('select').disabled = true;
                    partnerField.querySelector('select').disabled = true;
                } else {
                    carField.style.display = 'none';
                    clientField.style.display = 'none';
                    employeeField.style.display = 'none';
                    partnerField.style.display = 'none';
                    carField.querySelector('select').disabled = true;
                    employeeField.querySelector('select').disabled = true;
                    partnerField.querySelector('select').disabled = true;
                }
            }

            // Initialize the fields visibility based on the current selected type
            toggleFields();

            // Add event listener to handle changes in the select element
            typeSelect.addEventListener('change', toggleFields);
        });

        // Add smooth scrolling and enhanced interactions
        document.querySelectorAll('.modern-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.02)';
            });

            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Enhanced table row interactions
        document.querySelectorAll('.modern-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });

            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Floating action button rotation
        document.querySelector('.floating-action').addEventListener('click', function() {
            this.style.transform = 'scale(1.2) rotate(180deg)';
            setTimeout(() => {
                this.style.transform = 'scale(1) rotate(0deg)';
            }, 200);
        });

        // Add loading animation to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Enhanced modal animations
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                this.querySelector('.modal-content').style.transform = 'scale(0.8)';
                this.querySelector('.modal-content').style.opacity = '0';
                setTimeout(() => {
                    this.querySelector('.modal-content').style.transform = 'scale(1)';
                    this.querySelector('.modal-content').style.opacity = '1';
                    this.querySelector('.modal-content').style.transition =
                        'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                }, 50);
            });
        });

        // Add number formatting for amounts
        document.querySelectorAll('input[name="price"]').forEach(input => {
            input.addEventListener('input', function() {
                let value = this.value.replace(/,/g, '');
                if (!isNaN(value) && value !== '') {
                    this.value = Number(value).toLocaleString();
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

@stop
