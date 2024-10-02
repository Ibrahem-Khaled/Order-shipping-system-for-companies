<style>
    .modal-custom {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 auto;
    }

    .modal-header-custom {
        background-color: #cb0c9f;
        color: #fff;
        padding: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        font-size: 1.5rem;
        text-align: center;
    }

    .modal-body-custom {
        padding: 20px;
        font-size: 1.2rem;
        color: #333;
    }

    .modal-footer-custom {
        text-align: right;
    }

    .btn-close {
        background-color: #cb0c9f;
        border: none;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-close:hover {
        background-color: #b00e88;
    }
</style>

<div class="modal fade" id="containerModal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="containerModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-custom">
            <div class="modal-header-custom">
                <h5 class="modal-title" id="containerModalLabel{{ $item->id }}">
                    تفاصيل الحاوية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">إغلاق</button>
            </div>
            <div class="modal-body-custom">
                <p><strong>مكتب التخليص:</strong> {{ $item->customs->subclient_id }}
                <p><strong>العميل:</strong> {{ $item->client->name }}</p>
                </p>
                <p><strong>رقم البيان:</strong> {{ $item->customs->statement_number }}
                </p>
                <p><strong>رقم الحاوية:</strong> {{ $item->number }}</p>
                <p><strong>حجم الحاوية:</strong> {{ $item->size }}</p>
                @if ($item->rent_id == null)
                    <p><strong>السائق:</strong> {{ $item->driver->name ?? 'N/A' }}</p>
                    <p><strong>السيارة:</strong>{{ $item->car->number ?? 'N/A' }}</p>
                @else
                    <p><strong>اسم شركة الايجار:</strong>{{ $item->rent->name }}</p>
                @endif
                <p><strong>تاريخ التحميل:</strong> {{ $item->transfer_date }}</p>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-close" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
