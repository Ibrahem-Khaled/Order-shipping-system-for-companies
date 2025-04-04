@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <!-- مدخلات السعر والمنصرف -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-4 text-center">
                <div class="input-group input-group-sm mb-3">
                    <input type="number" id="priceMultiplier" class="form-control" placeholder="أدخل السعر لكل حاوية">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="button" onclick="storeMultiplier()">تخزين
                            السعر</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <div class="input-group input-group-sm mb-3">
                    <input type="number" id="expense" class="form-control" placeholder="أدخل المنصرف">
                    <input type="text" id="description" class="form-control" placeholder="أدخل الوصف">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="button" onclick="storeExpense()">تخزين
                            المنصرف</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل الحاويات لكل سنة -->
        <div class="mt-5">
            <h3 class="text-center text-primary mb-4">تفاصيل الحاويات لكل سنة</h3>
            @php
                $containersPerYear = [];
                foreach ($containers as $item) {
                    $year = $item->created_at->format('Y');
                    $month = $item->created_at->format('F');
                    if (!isset($containersPerYear[$year])) {
                        $containersPerYear[$year] = [];
                    }
                    if (!isset($containersPerYear[$year][$month])) {
                        $containersPerYear[$year][$month] = 0;
                    }
                    $containersPerYear[$year][$month]++;
                }
            @endphp

            @foreach ($containersPerYear as $year => $months)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">سنة {{ $year }}</h5>
                    </div>
                    <div class="card-body">
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
                                            $count = $months[$month] ?? 0;
                                        @endphp
                                        <td>{{ $count }}</td>
                                        <td class="multiplied-value" data-count="{{ $count }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- تاريخ المصاريف -->
        <div class="mt-5">
            <h3 class="text-center text-primary mb-4">تاريخ المصاريف</h3>
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">التاريخ</th>
                        <th scope="col">المنصرف</th>
                        <th scope="col">الوصف</th>
                        <th scope="col">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="expense-history-body">
                    <!-- سيتم تعبئة هذا الجدول بالمصاريف المخزنة -->
                </tbody>
            </table>
        </div>

        <!-- الإجماليات -->
        <div class="mt-5">
            <h3 class="text-center text-primary mb-4">الإجماليات</h3>
            @php
                $totalContainers = 0;
                foreach ($containersPerYear as $year => $months) {
                    $totalContainers += array_sum($months);
                }
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
            const description = document.getElementById('description').value;
            if (expense && description) {
                const expenses = getExpenses();
                const date = new Date().toLocaleDateString();
                expenses.push({
                    date,
                    amount: parseFloat(expense),
                    description: description
                });
                localStorage.setItem('expenses', JSON.stringify(expenses));
                updateMultipliedValues();
                updateExpenseHistory();
            } else {
                alert('يرجى إدخال رقم صحيح ووصف');
            }
        }

        function editExpense(index) {
            const expenses = getExpenses();
            const expense = expenses[index];
            const newAmount = prompt("أدخل القيمة الجديدة للمنصرف:", expense.amount);
            const newDescription = prompt("أدخل الوصف الجديد للمنصرف:", expense.description);
            if (newAmount !== null && newDescription !== null) {
                expenses[index].amount = parseFloat(newAmount);
                expenses[index].description = newDescription;
                localStorage.setItem('expenses', JSON.stringify(expenses));
                updateMultipliedValues();
                updateExpenseHistory();
            }
        }

        function deleteExpense(index) {
            const expenses = getExpenses();
            expenses.splice(index, 1);
            localStorage.setItem('expenses', JSON.stringify(expenses));
            updateMultipliedValues();
            updateExpenseHistory();
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
                element.textContent = count > 0 ? `(${(count * multiplier).toFixed(0)}$)` : '';
            });

            document.querySelectorAll('.total-multiplied-value').forEach(function(element) {
                const count = element.getAttribute('data-count');
                const totalValue = (count * multiplier).toFixed(0);
                element.textContent = totalValue + '$';
                const remainingValue = (totalValue - expenseTotal).toFixed(2);
                document.getElementById('expense-value').textContent = expenseTotal.toFixed(0) + '$';
                document.getElementById('remaining-value').textContent = remainingValue + '$';
            });
        }

        function updateExpenseHistory() {
            const expenses = getExpenses();
            const tbody = document.getElementById('expense-history-body');
            tbody.innerHTML = '';
            expenses.forEach((expense, index) => {
                const row = document.createElement('tr');
                const dateCell = document.createElement('td');
                dateCell.textContent = expense.date;
                const amountCell = document.createElement('td');
                amountCell.textContent = expense.amount.toFixed(0) + '$';
                const descriptionCell = document.createElement('td');
                descriptionCell.textContent = expense.description;
                const actionCell = document.createElement('td');
                const editButton = document.createElement('button');
                editButton.textContent = "تعديل";
                editButton.className = "btn btn-sm btn-warning";
                editButton.onclick = () => editExpense(index);
                const deleteButton = document.createElement('button');
                deleteButton.textContent = "مسح";
                deleteButton.className = "btn btn-sm btn-danger";
                deleteButton.onclick = () => deleteExpense(index);
                actionCell.appendChild(editButton);
                actionCell.appendChild(deleteButton);
                row.appendChild(dateCell);
                row.appendChild(amountCell);
                row.appendChild(descriptionCell);
                row.appendChild(actionCell);
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
        .input-group {
            margin-bottom: 10px;
        }

        .card {
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            text-align: center;
        }
    </style>
@stop
