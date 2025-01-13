<div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1"
    role="dialog" aria-labelledby="updateModalLabel{{ $item->id }}"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel{{ $item->id }}">
                    تحديث
                    الحالة</h5>
                <button type="button" class="btn-close" data-dismiss="modal"
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
                            data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>