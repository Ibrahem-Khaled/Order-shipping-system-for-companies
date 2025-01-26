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

    /* تنسيقات الباجينيشن */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        color: #cb0c9f;
        border: 1px solid #cb0c9f;
        border-radius: 5px;
        padding: 8px 16px;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: #cb0c9f;
        color: #fff;
        border-color: #cb0c9f;
    }

    .pagination .page-item.active .page-link {
        background-color: #cb0c9f;
        color: #fff;
        border-color: #cb0c9f;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        border-color: #dee2e6;
    }
</style>
@section('content')

    <!-- جدول الحاويات المحملة -->
    <div class="container mt-5">
        <div class="d-flex justify-content-around align-items-center bg-primary" style="border-radius: 5px">
            <h3 class="text-primary">الحاويات المحملة ({{ $containerPort->total() }})</h3>
            <x-search-form />
        </div>

        <div class="table-responsive">
            <x-table>
                <x-slot name="header">
                    <th scope="col" class="text-center">ارضية الفارغ</th>
                    <th scope="col" class="text-center">العميل</th>
                    <th scope="col" class="text-center">مكتب التخليص</th>
                    <th scope="col" class="text-center">حجم الحاوية</th>
                    <th scope="col" class="text-center">رقم الحاوية</th>
                    <th scope="col" class="text-center">رقم البيان</th>
                    <th scope="col" class="text-center">الحالة</th>
                    <th scope="col" class="text-center">#</th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($containerPort as $item)
                        <tr>
                            <td class="text-center">
                                <x-countdown-timer :id="$item->id" :transfer_date="$item->transfer_date" :date_empty="$item->date_empty" />
                                <x-edit-modal :id="$item->id" :date_empty="$item->date_empty" />
                            </td>
                            <td class="text-center">{{ $item->customs->subclient_id }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
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
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#updateModal{{ $item->id }}">محملة</button>
                                    <x-update-container-status :item="$item" :driver="$driver" :cars="$cars"
                                        :rents="$rents" />

                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#containerModal{{ $item->id }}">عرض التفاصيل</button>
                                    <!-- مودال عرض التفاصيل -->
                                    <x-container-details :item="$item" />
                                @endif
                            </td>
                            <td class="text-center">{{ $item->id }}</td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $containerPort->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>

    <!-- جدول الحاويات التخزين -->
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-primary">الحاويات التخزين ({{ $storageContainer->total() }})</h3>
        <div class="table-responsive">
            <x-table>
                <x-slot name="header">
                    <th scope="col" class="text-center">تاريخ ارضية الفارغ</th>
                    <th scope="col" class="text-center">العميل</th>
                    <th scope="col" class="text-center">مكتب التخليص</th>
                    <th scope="col" class="text-center">حجم الحاوية</th>
                    <th scope="col" class="text-center">رقم الحاوية</th>
                    <th scope="col" class="text-center">رقم البيان</th>
                    <th scope="col" class="text-center">الحالة</th>
                    <th scope="col" class="text-center">#</th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($storageContainer as $item)
                        <tr>
                            <td class="text-center">
                                <x-countdown-timer :id="$item->id" :transfer_date="$item->transfer_date" :date_empty="$item->date_empty" />
                                <x-edit-modal :id="$item->id" :date_empty="$item->date_empty" />
                            </td>
                            <td class="text-center">{{ $item->customs->subclient_id }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#confirmationModal{{ $item->id }}">
                                    تحميل
                                </button>

                                <!-- Modal Confirmation -->
                                <x-confirmation-modal :item="$item" :driver="$driver" :cars="$cars"
                                    :rents="$rents" />

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

                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#containerModal{{ $item->id }}">عرض التفاصيل</button>
                                <!-- مودال عرض التفاصيل -->
                                <x-container-details :item="$item" />

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
                </x-slot>
            </x-table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $storageContainer->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>

    <!-- جدول الحاويات الفارغة -->
    <div class="container mt-5">
        <h3 class="text-center mb-4 text-primary">الحاويات الفارغة </h3>
        <div class="table-responsive">
            <x-table>
                <x-slot name="header">
                    <th scope="col" class="text-center">العميل</th>
                    <th scope="col" class="text-center">مكتب التخليص</th>
                    <th scope="col" class="text-center">حجم الحاوية</th>
                    <th scope="col" class="text-center">رقم الحاوية</th>
                    <th scope="col" class="text-center">رقم البيان</th>
                    <th scope="col" class="text-center">الحالة</th>
                    <th scope="col" class="text-center">#</th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($done as $item)
                        <tr>
                            <td class="text-center">{{ $item->customs->subclient_id }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
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
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#containerModal{{ $item->id }}">عرض التفاصيل</button>
                                <!-- مودال عرض التفاصيل -->
                                <x-container-details :item="$item" />
                            </td>
                            <td class="text-center">{{ $item->id }}</td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
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
