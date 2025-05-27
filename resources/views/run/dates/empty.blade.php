@extends('layouts.default')
<style>
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
        <div class="d-flex justify-content-around align-items-center bg-primary p-3" style="border-radius: 5px">
            <h3 class="text-center mb-0 text-white">الحاويات المحملة ({{ $containerPort->total() }})</h3>
            <x-search-form />
        </div>

        <div class="table-responsive mt-4">
            <x-table class="table-striped">
                <x-slot name="header">
                    <th scope="col" class="text-center">موقع الحاوية</th>
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
                                {{ $item->direction ?? 'غير محدد' }}
                            </td>
                            <td class="text-center">
                                <x-countdown-timer :id="$item->id" :transfer_date="$item->transfer_date" :date_empty="$item->date_empty" />
                                <x-edit-modal :id="$item->id" :date_empty="$item->date_empty" />
                            </td>
                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}" class="text-primary">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                @if ($item->is_rent == 1)
                                    <form action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" class="btn btn-primary btn-sm">محملة</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#updateModal{{ $item->id }}">محملة</button>
                                    <x-update-container-status :item="$item" :driver="$driver" :cars="$cars"
                                        :rents="$rents" />

                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
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

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $containerPort->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>

    <!-- جدول الحاويات التخزين -->
    <div class="container mt-5">
        <div class="d-flex justify-content-around align-items-center bg-primary p-3" style="border-radius: 5px">
            <h3 class="text-center mb-0 text-white">الحاويات التخزين ({{ $storageContainer->total() }})</h3>
        </div>

        <div class="table-responsive mt-4">
            <x-table class="table-striped">
                <x-slot name="header">
                    <th scope="col" class="text-center">موقع الحاوية</th>
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
                                {{ $item->direction ?? 'غير محدد' }}
                            </td>
                            <td class="text-center">
                                <x-countdown-timer :id="$item->id" :transfer_date="$item->transfer_date" :date_empty="$item->date_empty" />
                                <x-edit-modal :id="$item->id" :date_empty="$item->date_empty" />
                            </td>
                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}" class="text-primary">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#confirmationModal{{ $item->id }}">
                                    تحميل
                                </button>

                                <!-- Modal Confirmation -->
                                <x-confirmation-modal :item="$item" :driver="$driver" :cars="$cars"
                                    :rents="$rents" />

                                <form action="{{ route('ContainerRentStatus', $item->id) }}" method="GET"
                                    class="d-inline">
                                    <input name="status" value="{{ $item->status }}" hidden />
                                    <input name="storage" value="storage" hidden />

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        @if ($item->status == 'storage')
                                            تاجير حاوية
                                        @elseif($item->status == 'rent')
                                            الغاء تاجير الحاوية
                                        @endif
                                    </button>
                                </form>

                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                    data-target="#containerModal{{ $item->id }}">عرض التفاصيل</button>
                                <!-- مودال عرض التفاصيل -->
                                <x-container-details :item="$item" />

                                <form action="{{ route('change.container.status', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <input name="status" value="wait" hidden />
                                    <button type="submit" class="btn btn-warning btn-sm">
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

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $storageContainer->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>
    </div>

    <!-- جدول الحاويات الفارغة -->
    <div class="container mt-5">
        <div class="d-flex justify-content-around align-items-center bg-primary p-3" style="border-radius: 5px">
            <h3 class="text-center mb-0 text-white">الحاويات الفارغة</h3>
        </div>

        <div class="table-responsive mt-4">
            <x-table class="table-striped">
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
                            <td class="text-center">{{ $item->customs->importer_name }}</td>
                            <td class="text-center">{{ $item->client->name }}</td>
                            <td class="text-center">{{ $item->size }}</td>
                            <td class="text-center">{{ $item->number }}</td>
                            <td class="text-center">
                                <a href="{{ route('showContainer', $item->customs->id) }}" class="text-primary">
                                    {{ $item->customs->statement_number }}
                                </a>
                            </td>
                            <td class="text-center">
                                <form id="confirmationForm{{ $item->id }}"
                                    action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="transport">
                                    <button type="button" class="btn btn-warning btn-sm"
                                        onclick="showConfirmation({{ $item->id }})">فارغ</button>
                                </form>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
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
