@extends('layouts.default')

@section('styles')
    <style>
        .user-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left-color: #4e73df;
        }

        .password-strength-meter {
            height: 5px;
            background: #e9ecef;
            border-radius: 3px;
            margin-top: 5px;
        }

        .password-strength-meter::after {
            content: '';
            display: block;
            height: 100%;
            border-radius: 3px;
            width: 0;
            background: #e74a3b;
            transition: width 0.3s ease;
        }

        [data-strength="1"] .password-strength-meter::after {
            width: 25%;
            background: #e74a3b;
        }

        [data-strength="2"] .password-strength-meter::after {
            width: 50%;
            background: #f6c23e;
        }

        [data-strength="3"] .password-strength-meter::after {
            width: 75%;
            background: #1cc88a;
        }

        [data-strength="4"] .password-strength-meter::after {
            width: 100%;
            background: #36b9cc;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-key"></i> إدارة كلمات المرور
            </h1>

            <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#bulkUpdateModal">
                <i class="fas fa-users-cog"></i> تحديث جماعي
            </button>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-user-shield"></i> قائمة المستخدمين
                        </h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#passwordPolicyModal">
                                    <i class="fas fa-info-circle"></i> سياسة كلمات المرور
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('components.alerts')

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="40">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>المستخدم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الدور</th>
                                        <th>آخر تحديث</th>
                                        <th width="150">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr class="user-card">
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input user-checkbox"
                                                        id="user-{{ $user->id }}" value="{{ $user->id }}">
                                                    <label class="custom-control-label"
                                                        for="user-{{ $user->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $user->avatar_url }}" class="rounded-circle mr-3"
                                                        width="40" height="40">
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge badge-{{ $user->role_badge_color }}">
                                                    {{ $user->role_name }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->password_changed_at)
                                                    {{ $user->password_changed_at->diffForHumans() }}
                                                @else
                                                    <span class="text-danger">لم يتم التحديث</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                    data-target="#editPasswordModal" data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}">
                                                    <i class="fas fa-key"></i> تغيير
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">لا يوجد مستخدمون متاحون</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تغيير كلمة المرور الفردي -->
    <div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> تغيير كلمة المرور
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="passwordForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="userName">المستخدم:</label>
                            <input type="text" class="form-control" id="userName" readonly>
                        </div>

                        <div class="form-group">
                            <label for="password">كلمة المرور الجديدة:</label>
                            <input type="password" class="form-control" name="password" id="password" required
                                data-toggle="password" autocomplete="new-password">
                            <div class="password-strength-meter mt-1"></div>
                            <small class="form-text text-muted">
                                يجب أن تحتوي على 8 أحرف على الأقل، حروف كبيرة وصغيرة، أرقام ورموز
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">تأكيد كلمة المرور:</label>
                            <input type="password" class="form-control" name="password_confirmation" required
                                autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="forceChange">
                                <label class="custom-control-label" for="forceChange">
                                    إجبار المستخدم على تغيير كلمة المرور عند الدخول التالي
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal التحديث الجماعي -->
    <div class="modal fade" id="bulkUpdateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-users-cog"></i> تحديث جماعي لكلمات المرور
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('passwords.bulk-update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>المستخدمون المحددون:</label>
                            <div id="selectedUsersList" class="bg-light p-3 mb-3 rounded">
                                <p class="text-muted mb-0">لم يتم تحديد أي مستخدمين</p>
                            </div>
                            <input type="hidden" name="users" id="selectedUsersInput">
                        </div>

                        <div class="form-group">
                            <label for="bulkPassword">كلمة المرور الجديدة:</label>
                            <input type="password" class="form-control" name="password" id="bulkPassword" required
                                data-toggle="password" autocomplete="new-password">
                            <div class="password-strength-meter mt-1"></div>
                        </div>

                        <div class="form-group">
                            <label for="bulkPasswordConfirmation">تأكيد كلمة المرور:</label>
                            <input type="password" class="form-control" name="password_confirmation" required
                                autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> تحديث الكل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal سياسة كلمات المرور -->
    <div class="modal fade" id="passwordPolicyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle"></i> سياسة كلمات المرور
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>متطلبات كلمة المرور:</h6>
                    <ul>
                        <li>يجب أن تحتوي على الأقل على 8 أحرف</li>
                        <li>يجب أن تحتوي على حروف كبيرة وصغيرة (A-Z, a-z)</li>
                        <li>يجب أن تحتوي على الأقل على رقم واحد (0-9)</li>
                        <li>يجب أن تحتوي على الأقل على رمز خاص (!@#$%^&*)</li>
                        <li>لا يمكن استخدام كلمات مرور شائعة أو سهلة التخمين</li>
                    </ul>
                    <hr>
                    <h6>أفضل الممارسات:</h6>
                    <ul>
                        <li>استخدم عبارة مرور بدلاً من كلمة مرور واحدة</li>
                        <li>لا تستخدم معلومات شخصية في كلمة المرور</li>
                        <li>غير كلمة المرور بشكل دوري كل 90 يوم</li>
                        <li>لا تستخدم نفس كلمة المرور لحسابات متعددة</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">حسناً</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // إدارة اختيار جميع المستخدمين
            $('#selectAll').change(function() {
                $('.user-checkbox').prop('checked', this.checked);
                updateSelectedUsers();
            });

            // تحديث حالة "اختيار الكل" عند تغيير الاختيارات الفردية
            $('.user-checkbox').change(function() {
                if (!this.checked) {
                    $('#selectAll').prop('checked', false);
                } else {
                    var allChecked = $('.user-checkbox:not(:checked)').length === 0;
                    $('#selectAll').prop('checked', allChecked);
                }
                updateSelectedUsers();
            });

            // تحديث قائمة المستخدمين المحددين
            function updateSelectedUsers() {
                var selectedIds = $('.user-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                $('#selectedUsersInput').val(selectedIds.join(','));

                if (selectedIds.length > 0) {
                    var names = $('.user-checkbox:checked').map(function() {
                        return $(this).closest('tr').find('h6').text();
                    }).get();

                    $('#selectedUsersList').html(names.map(name =>
                        `<span class="badge badge-primary mr-2 mb-2">${name}</span>`
                    ).join(''));
                } else {
                    $('#selectedUsersList').html('<p class="text-muted mb-0">لم يتم تحديد أي مستخدمين</p>');
                }
            }

            // تهيئة مودال تغيير كلمة المرور
            $('#editPasswordModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var userId = button.data('user-id');
                var userName = button.data('user-name');

                var modal = $(this);
                modal.find('#userName').val(userName);
                modal.find('form').attr('action', '/system/users/' + userId + '/password');
            });

            // التحقق من قوة كلمة المرور
            $('[data-toggle="password"]').on('input', function() {
                var password = $(this).val();
                var strength = 0;

                // طول كلمة المرور
                if (password.length >= 8) strength++;

                // أحرف كبيرة وصغيرة
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength++;

                // أرقام
                if (password.match(/([0-9])/)) strength++;

                // رموز خاصة
                if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength++;

                // تحديث مؤشر القوة
                $(this).parent().attr('data-strength', strength);
            });

            // عرض/إخفاء كلمة المرور
            $('[data-toggle="password"]').parent().append(
                '<span class="fa fa-fw fa-eye field-icon toggle-password"></span>'
            );

            $('.toggle-password').click(function() {
                var input = $(this).siblings('input');
                var icon = $(this);

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endsection
