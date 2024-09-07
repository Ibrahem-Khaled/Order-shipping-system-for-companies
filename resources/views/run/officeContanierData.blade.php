@extends('layouts.default')
<style>
    .countdown-timer {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.9rem;
        color: #fff;
        background: #cb0c9f;
        border-radius: 5px;
        padding: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .countdown-timer .time-unit {
        margin: 0 5px;
        text-align: center;
        position: relative;
        height: 60px;
    }

    .countdown-timer .time-unit span {
        font-size: 1.2rem;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .finished {
        font-size: 1.2rem;
        color: #ff4d4d;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #cb0c9f;
        width: 20px;
        height: 20px;
        animation: spin 2s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .d-none {
        display: none;
    }
</style>

@section('content')
    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">تاريخ ارضية الفارغ</th>
                    <th scope="col">التاريخ</th>
                    <th scope="col">البيان الجمركي</th>
                    <th scope="col">رقم الحاوية</th>
                    <th scope="col">حالة الحاوية</th>
                    <th scope="col">اسم العميل</th>
                    <th scope="col">حجم الحاوية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users->container->where('status', '!=', 'done') as $item)
                    <tr>
                        <td class="text-center">
                            <div id="loading-{{ $item->id }}" class="loading-spinner"></div>
                            @if ($item->date_empty == null)
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editDateModal-{{ $item->id }}">
                                    اضافة تاريخ ارضية الفارغ
                                </button>
                            @else
                                <div id="countdown-{{ $item->id }}" class="countdown-timer d-none"
                                    data-bs-toggle="modal" data-bs-target="#editDateModal-{{ $item->id }}"></div>
                            @endif

                            <!-- Modal for Editing Date -->
                            <div class="modal fade" id="editDateModal-{{ $item->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="editDateModalLabel-{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editDateModalLabel-{{ $item->id }}">تعديل تاريخ
                                                الإفراغ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('updateDateEmpty', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="date-empty-{{ $item->id }}" class="form-label">تاريخ
                                                        الإفراغ الجديد</label>
                                                    <input type="date" class="form-control"
                                                        id="date-empty-{{ $item->id }}" name="date_empty"
                                                        value="{{ $item->date_empty }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    var createdAt = new Date("{{ $item->transfer_date }}").getTime();
                                    var dateEmpty = new Date("{{ $item->date_empty }}");

                                    // تحديد نهاية اليوم للـdateEmpty
                                    dateEmpty.setHours(23, 59, 59, 999);
                                    dateEmpty = dateEmpty.getTime();

                                    var countdownElement = document.getElementById("countdown-{{ $item->id }}");
                                    var loadingElement = document.getElementById("loading-{{ $item->id }}");

                                    // Simulate loading time
                                    setTimeout(function() {
                                        loadingElement.classList.add('d-none'); // Hide loading spinner
                                        countdownElement.classList.remove('d-none'); // Show countdown timer
                                    }, 1000); // 1 second loading time

                                    // Update the count down every 1 second
                                    var countdownInterval = setInterval(function() {
                                        var now = new Date().getTime();
                                        var distance = dateEmpty - now;

                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                        countdownElement.innerHTML =
                                            "<div class='time-unit'><span>" + seconds + "</span><br>ثانية</div>" +
                                            "<div class='time-unit'><span>" + minutes + "</span><br>دقيقة</div>" +
                                            "<div class='time-unit'><span>" + hours + "</span><br>ساعة</div>" +
                                            "<div class='time-unit'><span>" + days + "</span><br>يوم</div>";

                                        if (distance < 0) {
                                            clearInterval(countdownInterval);
                                            countdownElement.innerHTML = "<div class='finished'>انتهى الوقت</div>";
                                        }
                                    }, 1000);
                                });
                            </script>
                        </td>
                        <th scope="row">{{ $item->id }}</th>
                        <th scope="row">{{ $item->created_at }}</th>
                        <td style="font-weight: bold">{{ $item->customs->statement_number }}</td>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->status == 'wait' ? 'انتظار' : 'ايجار' }}</td>
                        <td>{{ $item->customs->subclient_id }}</td>
                        <td>{{ $item->size }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
