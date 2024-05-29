@extends('layouts.default')

@section('content')

    <div class="col-md-12">
        <h1 class="text-success text-end">كشف حساب</h1>
    </div>

    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#transactionModal">
        اضافة حركة بيع وشراء
    </button>

    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel">اضافة حركة بيع وشراء</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="transactionForm" action="{{ route('transactions.store') }}" method="POST"
                        data-cash-withdraw="{{ $canCashWithdraw }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="title" class="form-control" placeholder="اسم الحركة" required>
                        </div>
                        <div class="mb-3">
                            <select name="type" id="transactionType" class="form-select" required>
                                <option value="">اختر نوع الحركة</option>
                                <option value="sell">بيع</option>
                                <option value="sell_from_head_mony">بيع من راس مال الشركة</option>
                                <option value="buy">شراء</option>
                            </select>
                        </div>
                        <div class="mb-3" id="parentSelect" style="display: none;">
                            <select name="parent_id" class="form-select">
                                <option value="">اختر العنصر</option>
                                @foreach ($buyTransactions as $buyTransaction)
                                    <option value="{{ $buyTransaction->id }}">{{ $buyTransaction->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="number" name="price" class="form-control" placeholder="السعر" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-success">اضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table mt-5">
        <thead>
            <tr>
                <th scope="col">التفاصيل</th>
                <th scope="col">المبلغ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <h6 class="text-dark">{{ $canCashWithdraw }}</h6>
                </td>
                <td>
                    <h6 class="text-primary">ما يمكن سحبه</h6>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 class="text-dark">{{ $sell = $sellTransactions->sum('price') }}</h6>
                </td>
                <td>
                    <h6 class="text-primary">الاجمالي المبيعات</h6>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 class="text-dark">{{ $buy = $buyTransactions->sum('price') }}</h6>
                </td>
                <td>
                    <h6 class="text-dark">الاجمالي المشتريات</h6>
                </td>
            </tr>
            <tr>
                <td>
                    <h6 class="text-dark">{{ $sell - $buy }}</h6>
                </td>
                <td>
                    <h6 class="text-dark">{{ $sell - $buy > 0 ? 'الربح الاجمالي' : 'اجمالي الخسائر' }}</h6>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">سعر حركة البيع</th>
                    <th scope="col">سعر حركة الشراء</th>
                    <th scope="col">اسم العنصر</th>
                    <th scope="col">تاريخ</th>
                    <th scope="col">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->type == 'sell' ? $transaction->price : '' }}{{ $transaction->type == 'sell_from_head_mony' ? $transaction->price : '' }}
                        </td>
                        <td>{{ $transaction->type == 'buy' ? $transaction->price : '' }}</td>
                        <td>{{ $transaction->title }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editTransactionModal{{ $transaction->id }}">تعديل</button>

                            <div class="modal fade" id="editTransactionModal{{ $transaction->id }}" tabindex="-1"
                                aria-labelledby="editTransactionModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTransactionModalLabel">تعديل حركة البيع والشراء
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('transactions.update', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <input type="text" name="title" class="form-control"
                                                        value="{{ $transaction->title }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="number" name="price" class="form-control"
                                                        value="{{ $transaction->price }}" required>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">الغاء</button>
                                                    <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var transactionType = document.getElementById('transactionType');
            var parentSelect = document.getElementById('parentSelect');
            var form = document.getElementById('transactionForm');
            var priceInput = form.querySelector('input[name="price"]');
            var canCashWithdraw = parseFloat(form.getAttribute('data-cash-withdraw'));

            transactionType.addEventListener('change', function() {
                if (this.value === 'sell') {
                    parentSelect.style.display = 'block';
                } else {
                    parentSelect.style.display = 'none';
                }
            });

            form.addEventListener('submit', function(event) {
                if (transactionType.value === 'buy') {
                    var enteredPrice = parseFloat(priceInput.value);
                    if (enteredPrice > canCashWithdraw) {
                        alert('لا يمكن تنفيذ العملية. المبلغ المدخل أكبر من المتاح للسحب.');
                        event.preventDefault();
                    }
                }
            });
        });
    </script>
@stop
