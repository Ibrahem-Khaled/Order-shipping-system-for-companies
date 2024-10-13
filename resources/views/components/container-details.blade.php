<style>
    .modal-custom {
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
        transform: translateY(-20px);
        opacity: 0;
        animation: fadeInUp 0.5s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header-custom {
        background-color: #6a1b9a;
        color: #fff;
        padding: 20px;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        font-size: 1.6rem;
        font-weight: bold;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .modal-body-custom {
        padding: 25px;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4a4a4a;
        background-color: #fafafa;
    }

    .modal-footer-custom {
        text-align: right;
        padding: 15px 20px;
        border-top: 1px solid #ddd;
        background-color: #f0f0f0;
    }

    .btn-close {
        background-color: #6a1b9a;
        border: none;
        color: #fff;
        padding: 10px 20px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-close:hover {
        background-color: #4a0072;
    }

    .modal-header-custom h5 {
        margin: 0;
    }

    .modal-header-custom .close-btn {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .modal-body-custom p {
        margin: 10px 0;
    }

    .modal-body-custom strong {
        color: #333;
    }

    .modal-body-custom p:hover {
        color: #6a1b9a;
        transition: color 0.3s ease;
    }
</style>

<div class="modal fade" id="containerModal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="containerModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-custom">
            <div class="modal-header-custom">
                <h5 class="modal-title" id="containerModalLabel{{ $item->id }}">
                    تفاصيل الحاوية</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body-custom">
                <p><strong>مكتب التخليص:</strong> {{ $item->customs->subclient_id }}</p>
                <p><strong>العميل:</strong> {{ $item->client->name }}</p>
                <p><strong>رقم البيان:</strong> {{ $item->customs->statement_number }}</p>
                <p><strong>رقم الحاوية:</strong> {{ $item->number }}</p>
                <p><strong>حجم الحاوية:</strong> {{ $item->size }}</p>
                @if ($item->rent_id == null)
                    <p><strong>السائق:</strong> {{ $item->driver->name ?? 'N/A' }}</p>
                    <p><strong>السيارة:</strong>{{ $item->car->number ?? 'N/A' }}</p>
                    @if ($item->tipsEmpty()->exists())
                        <p><strong>اسم سائق السيارة
                                الفارغ:</strong> {{ $item->tipsEmpty()->first()?->user->name ?? 'N/A' }}
                        </p>
                        <p><strong>رقم السيارة الفارغ:</strong> {{ $item->tipsEmpty()->first()?->car->number ?? 'N/A' }}
                        </p>
                    @endif
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
