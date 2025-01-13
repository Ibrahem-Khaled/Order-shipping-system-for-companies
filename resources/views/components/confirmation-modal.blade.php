<div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="confirmationModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel{{ $item->id }}">
                    تأكيد التحميل</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="confirmationForm{{ $item->id }}" action="{{ route('updateContainer', $item->id) }}"
                method="POST">
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
                            <label for="driver-{{ $item->id }}" class="form-label">السائق</label>
                            <select class="form-select" id="driver-{{ $item->id }}" name="driver" required>
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
                            <label for="car-{{ $item->id }}" class="form-label">السيارة</label>
                            <select class="form-select" id="car-{{ $item->id }}" name="car" required>
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
                        <label for="transfer_date-{{ $item->id }}" class="form-label">تاريخ النقل</label>
                        <input type="date" class="form-control" id="transfer_date-{{ $item->id }}"
                            name="transfer_date" required>
                    </div>

                    <input type="hidden" name="status" value="{{ $item->status == 'rent' ? 'done' : 'transport' }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تأكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>
