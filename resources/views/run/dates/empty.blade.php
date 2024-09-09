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
        <div class="d-flex justify-content-around align-items-center bg-primary " style="border-radius: 5px">
            <h3 class="text-white">الحاويات المحملة ({{ $containerPort->total() }})</h3>
            <form action="{{ route('empty') }}" class="d-flex align-items-center" method="GET">
                <input type="text" name="query" class="form-control me-2" placeholder="Search...">
                <button type="submit" class="btn btn-success m-2">Search</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col" class="text-center">ارضية الفارغ</th>
                        <th scope="col" class="text-center">العميل</th>
                        <th scope="col" class="text-center">مكتب التخليص</th>
                        <th scope="col" class="text-center">التاريخ</th>
                        <th scope="col" class="text-center">السيارة</th>
                        <th scope="col" class="text-center">السائق</th>
                        <th scope="col" class="text-center">حجم الحاوية</th>
                        <th scope="col" class="text-center">رقم الحاوية</th>
                        <th scope="col" class="text-center">رقم البيان</th>
                        <th scope="col" class="text-center">الحالة</th>
                        <th scope="col" class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($containerPort as $item)
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
                                                <h5 class="modal-title" id="editDateModalLabel-{{ $item->id }}">تعديل
                                                    تاريخ
                                                    الإفراغ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('updateDateEmpty', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="date-empty-{{ $item->id }}"
                                                            class="form-label">تاريخ
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
                            <td class="text-center">{{ $item->customs->subclient_id }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            <td class="text-center">{{ $item?->transfer_date }}</td>
                            @if ($item->rent_id == null)
                                <td class="text-center">{{ $item->car->number ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->driver->name ?? 'N/A' }}</td>
                            @else
                                <td class="text-center">{{ $item->rent->name }}</td>
                                <td class="text-center">{{ $item->rent->name }}</td>
                            @endif
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                @if ($item->is_rent == 1)
                                    <form action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" class="btn btn-primary">محملة</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#updateModal{{ $item->id }}">محملة</button>
                                    <div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="updateModalLabel{{ $item->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateModalLabel{{ $item->id }}">
                                                        تحديث
                                                        الحالة</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('updateEmpty', $item->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="done">
                                                        <div class="mb-3">
                                                            <label for="user" class="form-label">اختر المستخدم</label>
                                                            <select class="form-select" id="user" name="user_id"
                                                                required>
                                                                @foreach ($driver as $user)
                                                                    <option value="{{ $user->id }}">
                                                                        {{ $user->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="car" class="form-label">اختر السيارة</label>
                                                            <select class="form-select" id="car" name="car_id"
                                                                required>
                                                                @foreach ($cars as $car)
                                                                    <option value="{{ $car->id }}">
                                                                        {{ $car->number }}-{{ $car?->driver?->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="price" class="form-label">السعر</label>
                                                            <input type="number" class="form-control" id="price"
                                                                name="price" value="20" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">إغلاق</button>
                                                            <button type="submit" class="btn btn-primary">تحديث</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $containerPort->links() }}
        </div>
    </div>



    <div class="container mt-5">
        <h3 class="text-center mb-4 text-white">الحاويات التخزين ({{ $storageContainer->total() }})</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col" class="text-center">تاريخ ارضية الفارغ</th>
                        <th scope="col" class="text-center">العميل</th>
                        <th scope="col" class="text-center">مكتب التخليص</th>
                        <th scope="col" class="text-center">سيارة التخزين</th>
                        <th scope="col" class="text-center">سائق التخزين</th>
                        <th scope="col" class="text-center">حجم الحاوية</th>
                        <th scope="col" class="text-center">رقم الحاوية</th>
                        <th scope="col" class="text-center">رقم البيان</th>
                        <th scope="col" class="text-center">الحالة</th>
                        <th scope="col" class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($storageContainer as $item)
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
                                <div class="modal fade" id="editDateModal-{{ $item->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="editDateModalLabel-{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editDateModalLabel-{{ $item->id }}">تعديل
                                                    تاريخ
                                                    الإفراغ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('updateDateEmpty', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="date-empty-{{ $item->id }}"
                                                            class="form-label">تاريخ
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
                            <td class="text-center">{{ $item->customs->subclient_id }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            @if ($item->rent_id == null)
                                <td class="text-center">{{ $item->car->number ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->driver->name ?? 'N/A' }}</td>
                            @else
                                <td class="text-center">{{ $item->rent->name }}</td>
                                <td class="text-center">اسم شركة الايجار</td>
                            @endif
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#confirmationModal{{ $item->id }}">
                                    تحميل
                                </button>

                                <!-- Modal Confirmation -->
                                <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="confirmationModalLabel{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmationModalLabel{{ $item->id }}">
                                                    تأكيد التحميل</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form id="confirmationForm{{ $item->id }}"
                                                action="{{ route('updateContainer', $item->id) }}" method="POST">
                                                @csrf 
                                                <div class="modal-body">
                                                    @if ($item->status == 'rent')
                                                        <select class="form-select" name="rent_id" required>
                                                            <option value="">اختر شركة الاجار</option>
                                                            @foreach ($rents as $items)
                                                                <option value="{{ $items->id }}">{{ $items->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <div class="mb-3">
                                                            <label for="driver-{{ $item->id }}"
                                                                class="form-label">السائق</label>
                                                            <select class="form-select" id="driver-{{ $item->id }}"
                                                                name="driver" required>
                                                                <option value="">اختر السائق</option>
                                                                <!-- اضافة خيارات السائقين -->
                                                                @foreach ($driver as $driverItem)
                                                                    <option value="{{ $driverItem->id }}">
                                                                        {{ $driverItem->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="car-{{ $item->id }}"
                                                                class="form-label">السيارة</label>
                                                            <select class="form-select" id="car-{{ $item->id }}"
                                                                name="car" required>
                                                                <option value="">اختر السيارة</option>
                                                                <!-- اضافة خيارات السيارات -->
                                                                @foreach ($cars as $car)
                                                                    <option value="{{ $car->id }}">
                                                                        {{ $car->number }}-{{ $car->driver?->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="mb-3">
                                                        <label for="transfer_date-{{ $item->id }}"
                                                            class="form-label">تاريخ النقل</label>
                                                        <input type="date" class="form-control"
                                                            id="transfer_date-{{ $item->id }}" name="transfer_date"
                                                            required>
                                                    </div>

                                                    <input type="hidden" name="status"
                                                        value="{{ $item->status == 'rent' ? 'done' : 'transport' }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-warning">تأكيد</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('ContainerRentStatus', $item->id) }}" method="GET">
                                    <input name="status" value="{{ $item->status }}" hidden />
                                    <input name="storage" value="storage" hidden />

                                    <button type="submit" class="btn btn-danger d-inline-block">
                                        @if ($item->status == 'storage')
                                            تاجير حاوية
                                        @elseif($item->status == 'rent')
                                            الغاء تاجير الحاوية
                                        @endif
                                    </button>
                                </form>

                                <form action="{{ route('change.container.status', $item->id) }}" method="POST">
                                    @csrf
                                    <input name="status" value="wait" hidden />
                                    <button type="submit" class="btn btn-warning d-inline-block">
                                        الغاء التخزين
                                    </button>
                                </form>

                            </td>
                            <td class="text-center">{{ $item->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $storageContainer->links() }}
        </div>
    </div>


    <div class="container mt-5">
        <h3 class="text-center mb-4 text-white">الحاويات الفارغة ({{ $done->total() }})</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col" class="text-center">العميل</th>
                        <th scope="col" class="text-center">مكتب التخليص</th>
                        <th scope="col" class="text-center">سيارة المحملة</th>
                        <th scope="col" class="text-center">سائق المحملة</th>
                        <th scope="col" class="text-center">سيارة الفارغ</th>
                        <th scope="col" class="text-center">سائق الفارغ</th>
                        <th scope="col" class="text-center">حجم الحاوية</th>
                        <th scope="col" class="text-center">رقم الحاوية</th>
                        <th scope="col" class="text-center">رقم البيان</th>
                        <th scope="col" class="text-center">الحالة</th>
                        <th scope="col" class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($done as $item)
                        <tr>
                            <td class="text-center">{{ $item->customs->subclient_id }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            @if ($item->rent_id == null)
                                <td class="text-center">{{ $item->car->number ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->driver->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->tipsEmpty()->first()?->car->number ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->tipsEmpty->first()?->user->name ?? 'N/A' }}</td>
                            @else
                                <td class="text-center">{{ $item->rent->name }}</td>
                                <td class="text-center">اسم شركة الايجار</td>
                            @endif
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                <form id="confirmationForm{{ $item->id }}"
                                    action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="transport">
                                    <button type="button" class="btn btn-warning"
                                        onclick="showConfirmation({{ $item->id }})">فارغ</button>
                                </form>
                            </td>
                            <td class="text-center">{{ $item->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showConfirmation(itemId) {
            if (confirm("هل انت متاكد من هذا الطلب?")) {
                document.getElementById("confirmationForm" + itemId).submit();
            } else {
                return false;
            }
        }
    </script>
@stop
