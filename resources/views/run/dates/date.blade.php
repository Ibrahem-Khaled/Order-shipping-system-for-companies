@extends('layouts.default')

@section('content')

    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3"
                style="max-height: 400px; overflow: auto; position: relative;">
                <h3 class="text-center mb-4"> {{ count($container) }} تحميل الحاويات</h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
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
                                <form action="{{ route('updateContainer', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="text" hidden name="status" value="transport">
                                    <td class="text-center">{{ $item->customs->subclient_id }}</td>
                                    <td class="text-center">{{ $item->client->name }}</td>

                                    @if ($item->status == 'rent')
                                        <td class="text-center">
                                            <select class="form-select" name="rent_id" required>
                                                <option value="">اختر شركة الاجار</option>
                                                @foreach ($rents as $items)
                                                    <option value="{{ $items->id }}">{{ $items->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td></td>
                                    @else
                                        <td class="text-center">
                                            <select class="form-select" name="car" required>
                                                <option value="">اختر السيارة</option>
                                                @foreach ($cars as $driverItem)
                                                    <option value="{{ $driverItem->id }}">
                                                        {{ $driverItem->driver?->name }} -
                                                        {{ $driverItem->number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select" name="driver" required>
                                                <option value="">اختر السائق</option>
                                                @foreach ($driver as $driverItem)
                                                    <option value="{{ $driverItem->id }}">{{ $driverItem->name }}
                                                    </option>
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
        </div>
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3"
                style="max-height: 400px; overflow: auto; position: relative;">
                <h3 class="text-center mb-4" style="position: sticky; top: 0; z-index: 0;"> {{ count($containerPort) }}
                    الحاويات المحملة</h3>
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
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
                                        {{ $item->status == 'transport' ? 'محملة' : $item->status }}
                                    </button>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>

                            <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
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
