@extends('layouts.default')

@section('content')
    <div class="container-fluid py-4" dir="rtl">
        <!-- Header Section -->
        <x-statement-header company-name="شركة الأمجاد المتعددة" company-address="الرياض، المملكة العربية السعودية"
            company-phone="+966 547777121" company-email="alamjad.multi@gmail.com" title="كشف حساب"
            client-name="{{ $user->name }}" month-name="{{ $monthName }}" />
        <!-- Main Content Section -->
        @include('components.alerts')

        <div class="card-body">
            <form action="{{ route('updateContainerPrice') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">رقم البيان</th>
                                <th scope="col" class="text-center">العميل</th>
                                <th scope="col" class="text-center">عدد الحاويات</th>
                                <th scope="col" class="text-center">سعر امر النقل</th>
                                <th scope="col" class="text-center">سعر النقل للحاوية</th>
                                <th scope="col" class="text-center">اجمالي سعر النقل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPrice = 0;
                                $totalWithdrawPrice = 0;
                            @endphp
                            @foreach ($customs as $index => $custom)
                                @php
                                    $transportContainers = $custom->container;
                                @endphp
                                @if ($transportContainers->isNotEmpty())
                                    {{-- <input type="hidden" value="{{ $custom->id }}" name="id[]" /> --}}
                                    <tr>
                                        @php
                                            $containerPrice = $transportContainers->sum('price');
                                            $withdrawPrice = $custom->container
                                                ->flatMap(fn($c) => $c->daily)
                                                ->where('type', 'withdraw')
                                                ->sum('price');
                                            $totalPrice += $containerPrice;
                                            $totalWithdrawPrice += $withdrawPrice;
                                        @endphp


                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('showContainer', $custom->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                {{ $custom->statement_number }}
                                            </a>
                                        </td>
                                        <td class="text-center align-middle">{{ $custom->importer_name }}</td>
                                        <td class="text-center align-middle">{{ $transportContainers->count() }}</td>
                                        <td class="text-center align-middle font-weight-bold text-info">
                                            {{ number_format($withdrawPrice, 2) }} ر.س</td>
                                        <td>
                                            <div class="input-group flex-column">
                                                @foreach ($transportContainers->groupBy('price') as $price => $group)
                                                    <div class="d-flex mb-1">
                                                        <span class="ml-2 font-weight-bold">{{ $group->count() }} ×</span>
                                                        @if (auth()->user()?->userinfo?->job_title == 'administrative')
                                                            <input type="text" value="{{ $price }}"
                                                                class="form-control text-center" disabled>
                                                        @else
                                                            <input type="number"
                                                                name="price_grouped[{{ $custom->id }}][]"
                                                                value="{{ $price }}" placeholder="سعر الحاوية"
                                                                min="0" step="0.01"
                                                                class="form-control text-center">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center align-middle font-weight-bold text-success">
                                            {{ number_format($containerPrice, 0) }} ر.س</td>
                                    </tr>
                                @endif
                            @endforeach
                            {{-- <tr class="font-weight-bold bg-dark text-white">
                                <td colspan="6" class="text-right">المجموع الكلي للحاويات:</td>
                                <td class="text-center">{{ $customs->flatMap->container->count() }}</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
                @if (auth()->user()?->userinfo?->job_title != 'administrative')
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check-circle ml-2"></i>تأكيد الأسعار
                        </button>
                    </div>
                @endif
            </form>
        </div>


        <!-- Summary Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0 text-center">ملخص الفاتورة</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr class="bg-light">
                                        <td class="font-weight-bold text-primary text-right">مجموع سعر الحاويات:</td>
                                        <td class="text-right font-weight-bold">{{ number_format($totalPrice, 2) }} ر.س
                                        </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td class="font-weight-bold text-primary text-right">مجموع أوامر النقل:</td>
                                        <td class="text-right font-weight-bold">{{ number_format($totalWithdrawPrice, 2) }}
                                            ر.س</td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td class="font-weight-bold text-primary text-right">الإجمالي قبل الضريبة:</td>
                                        <td class="text-right font-weight-bold">
                                            {{ number_format($totalWithdrawPrice + $totalPrice, 2) }} ر.س</td>
                                    </tr>
                                    {{-- <tr id="vatRow">
                                        <td class="font-weight-bold text-success text-right">القيمة المضافة (15%):</td>
                                        <td class="text-right font-weight-bold" id="vatValue">
                                            {{ number_format($totalPrice * 0.15, 2) }} ر.س
                                        </td>
                                    </tr>
                                    <tr id="totalWithVatRow" class="bg-success text-white">
                                        <td class="font-weight-bold text-right">الإجمالي شامل الضريبة:</td>
                                        <td class="text-right font-weight-bold" id="totalWithVat">
                                            {{ number_format($totalWithdrawPrice + $totalPrice * 1.15, 2) }} ر.س
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="text-center mt-4">
                            <button id="toggleVatButton" class="btn btn-outline-secondary" onclick="toggleVat()">
                                <i class="fas fa-eye-slash mr-2"></i>إخفاء القيمة المضافة
                            </button>
                            <button class="btn btn-success ml-3" onclick="window.print()">
                                <i class="fas fa-print mr-2"></i>طباعة الفاتورة
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleVat() {
            var vatRow = document.getElementById('vatRow');
            var totalWithVatRow = document.getElementById('totalWithVatRow');
            var toggleButton = document.getElementById('toggleVatButton');

            if (vatRow.style.display === 'none') {
                vatRow.style.display = 'table-row';
                totalWithVatRow.style.display = 'table-row';
                toggleButton.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>إخفاء القيمة المضافة';
            } else {
                vatRow.style.display = 'none';
                totalWithVatRow.style.display = 'none';
                toggleButton.innerHTML = '<i class="fas fa-eye mr-2"></i>إظهار القيمة المضافة';
            }
        }

        // Initialize elements
        document.addEventListener("DOMContentLoaded", function() {
            var totalWithVatRow = document.getElementById('totalWithVatRow');
        });
    </script>

    <style>
        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            font-size: 1.2rem;
        }

        .table th {
            font-weight: bold;
        }

        .invoice-company-info {
            padding: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white;
                font-weight: bold;
            }

            .card {
                border: none;
                box-shadow: none;
            }
        }
    </style>
@endsection
