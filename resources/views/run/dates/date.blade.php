@extends('layouts.default')

@section('content')

    <form action="{{ route('dates') }}" class="row align-items-center" method="GET">
        <div class="col">
            <input type="text" name="query" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>

    <div class="container mt-5">
        <div class="table-container overflow-auto mt-4 p-3" style="max-height: 700px; overflow: auto; position: relative;">
            <h3 class="text-center mb-4 text-white"> {{ count($container) }} تحميل الحاويات</h3>
            <table class="table table-striped table-bordered table-hover table-sm">
                <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                    <tr>
                        <th scope="col" class="text-center">حالة الحاوية</th>
                        <th scope="col" class="text-center">العميل</th>
                        <th scope="col" class="text-center">مكتب التخليص</th>
                        <th scope="col" class="text-center">التاريخ</th>
                        <th scope="col" class="text-center">السيارة</th>
                        <th scope="col" class="text-center">السائق</th>
                        <th scope="col" class="text-center">حجم الحاوية</th>
                        <th scope="col" class="text-center">رقم الحاوية</th>
                        <th scope="col" class="text-center">الحالة</th>
                        <th scope="col" class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($container as $item)
                        <tr>
                            <td class="text-center">
                                <form action="{{ route('ContainerRentStatus', $item->id) }}" method="GET">
                                    <input name="status" value="{{ $item->status }}" hidden />
                                    <button type="submit" class="btn btn-danger d-inline-block">
                                        @if ($item->status == 'wait')
                                            تاجير حاوية
                                        @elseif($item->status == 'rent')
                                            الغاء تاجير الحاوية
                                        @endif
                                    </button>
                                </form>

                                <button type="button" class="btn btn-success d-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#storageContainer{{ $item->id }}">
                                    تخزين
                                </button>

                                <!-- Storage Confirmation Modal -->
                                <div class="modal fade" id="storageContainer{{ $item->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="storageContainerLabel{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="storageContainerLabel{{ $item->id }}">تأكيد
                                                    التخزين</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('container.storage', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
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
                                                                    {{ $car->driver?->name }}-{{ $car->number }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="tips-{{ $item->id }}"
                                                            class="form-label">الحوافز</label>
                                                        <input type="number" class="form-control"
                                                            id="tips-{{ $item->id }}" name="tips"
                                                            placeholder="ادخل مبلغ الحوافز" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="notes-{{ $item->id }}"
                                                            class="form-label">التاريخ</label>
                                                        <input type="date" class="form-control"
                                                            id="notes-{{ $item->id }}" name="transfer_date">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">إلغاء</button>
                                                    <button type="submit" class="btn btn-success">تأكيد</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @if (!auth()->user()?->userinfo?->job_title == 'operator')
                                    <button type="button" class="btn btn-danger d-inline-block" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $item->id }}">
                                        حذف
                                    </button>
                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel{{ $item->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">
                                                        تأكيد
                                                        الحذف</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p>هل أنت متأكد أنك تريد حذف الحاوية رقم {{ $item->number }}؟</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">إلغاء</button>
                                                    <form action="{{ route('deleteContainer', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">حذف</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <form action="{{ route('updateContainer', $item->id) }}" method="POST">
                                @csrf
                                <input type="text" hidden name="status" value="transport">
                                <td class="text-center">{{ $item->customs->subclient_id }}</td>
                                <td class="text-center">{{ $item->client->name }}</td>

                                @if ($item->status == 'rent')
                                    <td class="text-center">
                                        <input class="form-select" required type="date" name="created_at" />
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select" name="rent_id" required>
                                            <option value="">اختر شركة الاجار</option>
                                            @foreach ($rents as $items)
                                                <option value="{{ $items->id }}">{{ $items->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                @else
                                    <td class="text-center">
                                        <input class="form-select" required type="date" name="transfer_date" />
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select w-100" name="car" required>
                                            <option value="">اختر السيارة</option>
                                            @foreach ($cars as $driverItem)
                                                <option value="{{ $driverItem->id }}">
                                                    {{ $driverItem->driver?->name }} - {{ $driverItem->number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select w-100" name="driver" required>
                                            <option value="">اختر السائق</option>
                                            @foreach ($driver as $driverItem)
                                                <option value="{{ $driverItem->id }}">{{ $driverItem->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                                <td class="text-center">{{ $item->size }}</td>
                                <td class="text-center">{{ $item->number }}</td>
                                <td class="text-center">
                                    <button type="submit" class="btn btn-danger d-inline-block">
                                        @if ($item->status == 'wait')
                                            الانتظار
                                        @elseif($item->status == 'rent')
                                            ايجار
                                        @endif
                                    </button>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3"
                style="max-height: 700px; overflow: auto; position: relative;">
                <h3 class="text-center mb-4 text-white" style="position: sticky; top: 0; z-index: 0;">
                    {{ count($containerPort) }} الحاويات المحملة</h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
                            <th scope="col" class="text-center">التاريخ</th>
                            <th scope="col" class="text-center">السيارة</th>
                            <th scope="col" class="text-center">السائق</th>
                            <th scope="col" class="text-center">حجم الحاوية</th>
                            <th scope="col" class="text-center">رقم الحاوية</th>
                            <th scope="col" class="text-center">الحالة</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($containerPort as $item)
                            <tr>
                                <td class="text-center">{{ $item->customs->subclient_id }}</td>
                                <td class="text-center">{{ $item->client->name }}</td>
                                <td class="text-center">{{ $item->transfer_date }}</td>
                                @if ($item->rent_id == null)
                                    <td class="text-center">{{ $item->car->number ?? 0 }}</td>
                                    <td class="text-center">{{ $item->driver->name ?? 0 }}</td>
                                @else
                                    <td class="text-center">{{ $item->rent->name }}</td>
                                    <td class="text-center">اسم شركة الايجار</td>
                                @endif
                                <td class="text-center">{{ $item->size }}</td>
                                <td class="text-center">{{ $item->number }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success d-inline-block" data-bs-toggle="modal"
                                        data-bs-target="#confirmationModal{{ $item->id }}">
                                        {{ $item->status == 'transport' ? 'الغاء التحميل' : $item->status }}
                                    </button>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>

                            <!-- Status Confirmation Modal -->
                            <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">تأكيد العملية</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center"
                                            style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-weight: bold;">
                                            <?php $container = \App\Models\Container::find($item->id); ?>
                                            <p>
                                                هل تريد الغاء تحميل حاوية {{ $container->number }} للعميل
                                                {{ $container->customs->subclient_id }}
                                            </p>
                                        </div>
                                        <form action="{{ route('updateContainer', $item->id) }}" method="POST">
                                            @csrf
                                            @if ($container->rent_id == null)
                                                <input type="text" hidden name="status" value="wait">
                                            @else
                                                <input type="text" hidden name="status" value="rent">
                                            @endif
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary">تأكيد</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop
