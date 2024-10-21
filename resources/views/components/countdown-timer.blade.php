@props(['id', 'transfer_date', 'date_empty'])

<div id="loading-{{ $id }}" class="loading-spinner"></div>
@if ($date_empty == null)
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDateModal-{{ $id }}">
        اضافة تاريخ ارضية الفارغ
    </button>
@else
    <div id="countdown-{{ $id }}" class="countdown-timer d-none" data-bs-toggle="modal"
        data-bs-target="#editDateModal-{{ $id }}"></div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var createdAt = new Date("{{ $transfer_date }}").getTime();
        
        // ضبط نهاية اليوم للوقت الفارغ إلى الساعة 23:59:59
        var dateEmpty = new Date("{{ $date_empty }}");
        dateEmpty.setHours(23, 59, 59, 999);  // تعيين نهاية اليوم

        var countdownElement = document.getElementById("countdown-{{ $id }}");
        var loadingElement = document.getElementById("loading-{{ $id }}");

        setTimeout(function() {
            loadingElement.classList.add('d-none');
            countdownElement.classList.remove('d-none');
        }, 1000);

        var countdownInterval = setInterval(function() {
            var now = new Date().getTime();
            var distance = dateEmpty.getTime() - now;

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
