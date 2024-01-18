<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://cdn-icons-png.flaticon.com/128/1239/1239682.png" alt="NomerGroup Logo" height="30"
                    class="d-inline-block align-top">
                NomerGroup
            </a>
            <a href="{{ url()->previous() }}">Go Back</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">الرئيسية</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                                    <td class="text-center">
                                        <select class="form-select" name="car" required>
                                            <option value="">اختر السيارة</option>
                                            @foreach ($cars as $driverItem)
                                                <option value="{{ $driverItem->id }}">{{ $driverItem->number }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select" name="driver" required>
                                            <option value="">اختر السائق</option>
                                            @foreach ($driver as $driverItem)
                                                <option value="{{ $driverItem->id }}">{{ $driverItem->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">{{ $item->size }}</td>
                                    <td class="text-center">{{ $item->number }}</td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-danger d-inline-block">
                                            @if ($item->status == 'wait')
                                                الانتظار
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
                                <td class="text-center">{{ $item->car->number ?? 0 }}</td>
                                <td class="text-center">{{ $item->driver->name ?? 0 }}</td>
                                <td class="text-center">{{ $item->size }}</td>
                                <td class="text-center">{{ $item->number }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success d-inline-block"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmationModal{{ $item->id }}">
                                        {{ $item->status == 'transport' ? 'محملة' : $item->status }}
                                    </button>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>

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
                                            <input type="text" hidden name="status" value="wait">
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


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
