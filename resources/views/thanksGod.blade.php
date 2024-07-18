@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="input-group input-group-sm mb-3 justify-content-center">
            <input type="number" id="priceMultiplier" class="form-control" placeholder="أدخل السعر لكل حاوية"
                style="height: 35px; width: 150px;">
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm" type="button" onclick="storeMultiplier()">تخزين السعر</button>
            </div>
        </div>
        <div class="input-group input-group-sm mb-3 justify-content-center">
            <input type="number" id="expense" class="form-control" placeholder="أدخل المنصرف"
                style="height: 35px; width: 150px;">
            <div class="input-group-append">
                <button class="btn btn-primary btn-sm" type="button" onclick="storeExpense()">تخزين المنصرف</button>
            </div>
        </div>
        <div class="mt-5">
            <h3 class="text-center text-primary">عدد الحاويات لكل شهر عبر جميع السنين</h3>
            @php
                $containersPerMonth = [];

                foreach ($containers as $item) {
                    $month = $item->created_at->format('F');
                    if (!isset($containersPerMonth[$month])) {
                        $containersPerMonth[$month] = 0;
                    }
                    $containersPerMonth[$month]++;
                }
            @endphp

            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">الشهر</th>
                        <th scope="col">عدد الحاويات</th>
                        <th scope="col">السعر الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                        <tr>
                            <td><strong>{{ $month }}</strong></td>
                            @php
                                $count = $containersPerMonth[$month] ?? 0;
                            @endphp
                            <td>{{ $count }}</td>
                            <td class="multiplied-value" data-count="{{ $count }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            <h3 class="text-center text-primary">تاريخ المصاريف</h3>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">التاريخ</th>
                        <th scope="col">المنصرف</th>
                    </tr>
                </thead>
                <tbody id="expense-history-body">
                    <!-- سيتم تعبئة هذا الجدول بالمصاريف المخزنة -->
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            <h3 class="text-center text-primary">إجمالي السعر وعدد الحاويات لكل السنوات</h3>
            @php
                $totalContainers = array_sum($containersPerMonth);
            @endphp

            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">إجمالي عدد الحاويات</th>
                        <th scope="col">إجمالي السعر</th>
                        <th scope="col">المنصرف</th>
                        <th scope="col">الباقي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalContainers }}</td>
                        <td class="total-multiplied-value" data-count="{{ $totalContainers }}"></td>
                        <td id="expense-value"></td>
                        <td id="remaining-value"></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function storeMultiplier() {
            const multiplier = document.getElementById('priceMultiplier').value;
            if (multiplier) {
                localStorage.setItem('priceMultiplier', multiplier);
                updateMultipliedValues();
            } else {
                alert('يرجى إدخال رقم صحيح');
            }
        }

        function storeExpense() {
            const expense = document.getElementById('expense').value;
            if (expense) {
                const expenses = getExpenses();
                const date = new Date().toLocaleString();
                expenses.push({
                    date,
                    amount: parseFloat(expense)
                });
                localStorage.setItem('expenses', JSON.stringify(expenses));
                updateMultipliedValues();
                updateExpenseHistory();
            } else {
                alert('يرجى إدخال رقم صحيح');
            }
        }

        function getMultiplier() {
            const multiplier = localStorage.getItem('priceMultiplier');
            return multiplier ? parseFloat(multiplier) : 1;
        }

        function getExpenses() {
            const expenses = localStorage.getItem('expenses');
            return expenses ? JSON.parse(expenses) : [];
        }

        function getExpenseTotal() {
            const expenses = getExpenses();
            return expenses.reduce((total, expense) => total + expense.amount, 0);
        }

        function updateMultipliedValues() {
            const multiplier = getMultiplier();
            const expenseTotal = getExpenseTotal();

            document.querySelectorAll('.multiplied-value').forEach(function(element) {
                const count = element.getAttribute('data-count');
                element.textContent = count > 0 ? `(${(count * multiplier).toFixed(2)}$)` : '';
            });

            document.querySelectorAll('.total-multiplied-value').forEach(function(element) {
                const count = element.getAttribute('data-count');
                const totalValue = (count * multiplier).toFixed(2);
                element.textContent = totalValue + '$';
                const remainingValue = (totalValue - expenseTotal).toFixed(2);
                document.getElementById('expense-value').textContent = expenseTotal.toFixed(2) + '$';
                document.getElementById('remaining-value').textContent = remainingValue + '$';
            });
        }

        function updateExpenseHistory() {
            const expenses = getExpenses();
            const tbody = document.getElementById('expense-history-body');
            tbody.innerHTML = '';
            expenses.forEach(expense => {
                const row = document.createElement('tr');
                const dateCell = document.createElement('td');
                dateCell.textContent = expense.date;
                const amountCell = document.createElement('td');
                amountCell.textContent = expense.amount.toFixed(2) + '$';
                row.appendChild(dateCell);
                row.appendChild(amountCell);
                tbody.appendChild(row);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateMultipliedValues();
            updateExpenseHistory();
            document.getElementById('priceMultiplier').value = getMultiplier();
        });
    </script>

    <style>
        .header-title {
            font-size: 0.85em;
            font-weight: bold;
        }

        .input-group {
            width: 40%;
            margin: auto;
        }
    </style>

@stop
