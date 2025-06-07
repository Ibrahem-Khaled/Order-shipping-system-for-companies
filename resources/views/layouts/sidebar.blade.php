<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('CompanyHome') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('CompanyHome') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        الادارات
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('ai-tools') }}">
            <i class="fas fa-fw fa-robot"></i>
            <span>ادوات تحليل الذكاء الاصطناعي</span>
        </a>
    </li>

    <!-- Nav Item - Operations Management -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOperations"
            aria-expanded="true" aria-controls="collapseOperations">
            <i class="fas fa-fw fa-cogs"></i>
            <span>ادارة التشغيل</span>
        </a>
        <div id="collapseOperations" class="collapse" aria-labelledby="headingOperations"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('getOfices') }}">البيان الجمركي</a>
                <a class="collapse-item" href="{{ route('reservations.index') }}">الحجوزات</a>
                <a class="collapse-item" href="{{ route('empty') }}">كشف حساب الفارغ</a>
                <a class="collapse-item" href="{{ route('dates') }}">المواعيد</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Financial Management -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinancial"
            aria-expanded="true" aria-controls="collapseFinancial">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>الادارة المالية</span>
        </a>
        <div id="collapseFinancial" class="collapse" aria-labelledby="headingFinancial" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('dailyManagement') }}">اليومية</a>
                <a class="collapse-item" href="{{ route('FinancialManagement') }}">الماليات</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Employee Management -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('getEmployee') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>ادارة الموظفين</span>
        </a>
    </li>

    <!-- Nav Item - Flatbeds Management -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('flatbeds.index') }}">
            <i class="fas fa-fw fa-truck"></i>
            <span>ادارة السطحات</span>
        </a>
    </li>

    <!-- Nav Item - Thanks God -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('thanks.god') }}" onclick="return checkPassword(event);">
            <i class="fas fa-fw fa-pray"></i>
            <span>الحمد لله</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('car_change_oils.index') }}">
            <i class="fas fa-fw fa-oil-can"></i>
            <span>ادارة غيار الزيت</span>
        </a>
    </li>

    @if (Auth::user()->role == 'superAdmin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('passwords.index') }}">
                <i class="fas fa-fw fa-lock"></i>
                <span>ادارة كلمات المرور</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Script for Password Check -->
    <script>
        function checkPassword(event) {
            event.preventDefault();
            var password = prompt("ادخل كلمة المرور");

            if (password === '1234') {
                window.location.href = event.currentTarget.href;
            } else {
                alert("خطأ في كلمة المرور");
            }
            return false;
        }
    </script>
</ul>
