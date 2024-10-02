@props(['id', 'date_empty'])

<div class="modal fade" id="editDateModal-{{ $id }}" tabindex="-1" role="dialog"
    aria-labelledby="editDateModalLabel-{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDateModalLabel-{{ $id }}">تعديل تاريخ الإفراغ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('updateDateEmpty', $id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="date-empty-{{ $id }}" class="form-label">تاريخ الإفراغ الجديد</label>
                        <input type="date" class="form-control" id="date-empty-{{ $id }}" name="date_empty"
                            value="{{ $date_empty }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
