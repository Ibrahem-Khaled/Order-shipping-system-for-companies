<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفارغ</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Data Table CSS -->
    <link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>
    <!-- Font Awesome CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css'>

    <style>
        .wrapper {
            margin-top: 5vh;
        }

        .dataTables_filter {
            float: right;
        }

        .table-hover>tbody>tr:hover {
            background-color: #ccffff;
        }

        @media only screen and (min-width: 768px) {
            .table {
                table-layout: fixed;
                max-width: 100% !important;
            }
        }

        thead {
            background: #ddd;
        }

        .table td:nth-child(2) {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .highlight {
            background: #ffff99;
        }

        @media only screen and (max-width: 767px) {

            /* Force table to not be like tables anymore */
            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr,
            tfoot tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50% !important;
            }

            td:before {
                /* Now like a table header */
                position: absolute;
                /* Top/left values mimic padding */
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }

            .table td:nth-child(1) {
                background: #ccc;
                height: 100%;
                top: 0;
                left: 0;
                font-weight: bold;
            }



        }
    </style>

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
            <div class="table-container overflow-auto mt-4 p-3" style=" overflow: auto; position: relative;">
                <h3 class="text-center mb-4" style="position: sticky; top: 0; z-index: 0;"> {{ count($containerPort) }}
                    الحاويات المحملة</h3>
                <table id="example" class="table table-striped" style="width:100%">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
                            <th scope="col" class="text-center">السيارة</th>
                            <th scope="col" class="text-center">السائق</th>
                            <th scope="col" class="text-center">حجم الحاوية</th>
                            <th scope="col" class="text-center">رقم الحاوية</th>
                            <th scope="col" class="text-center">رقم البيان</th>
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
                                    <td class="text-center">{{ $item->rent->name }}</td>
                                @endif
                                <td class="text-center">{{ $item->size }}</td>
                                <td class="text-center">{{ $item->number }}</td>
                                <td class="text-center">{{ $item->customs->statement_number }}</td>
                                <td class="text-center">
                                    <form id="confirmationForm{{ $item->id }}"
                                        action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="status" value="done">
                                        <button type="button" class="btn btn-success"
                                            onclick="showConfirmation({{ $item->id }})">محملة</button>
                                    </form>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="table-container overflow-auto mt-4 p-3" style=" overflow: auto; position: relative;">
                <h3 class="text-center mb-4" style="position: sticky; top: 0; z-index: 0;"> {{ count($done) }}
                    الحاويات المحملة</h3>
                <table id="example2" class="table table-striped" style="width:100%">
                    <thead class="bg-aqua text-white" style="position: sticky; top: 0; z-index: 0;">
                        <tr>
                            <th scope="col" class="text-center">العميل</th>
                            <th scope="col" class="text-center">مكتب التخليص</th>
                            <th scope="col" class="text-center">السيارة</th>
                            <th scope="col" class="text-center">السائق</th>
                            <th scope="col" class="text-center">حجم الحاوية</th>
                            <th scope="col" class="text-center">رقم الحاوية</th>
                            <th scope="col" class="text-center">رقم البيان</th>
                            <th scope="col" class="text-center">الحالة</th>
                            <th scope="col" class="text-center">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($done as $item)
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
                                <td class="text-center">{{ $item->customs->statement_number }}</td>
                                <td class="text-center">
                                    <form id="confirmationForm{{ $item->id }}"
                                        action="{{ route('updateEmpty', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="transport">
                                        <button type="button" class="btn btn-warning"
                                            onclick="showConfirmation({{ $item->id }})">فارغ</button>
                                    </form>
                                </td>
                                <td class="text-center">{{ $item->id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src='https://code.jquery.com/jquery-3.7.0.js'></script>
    <!-- Data Table JS -->
    <script src='https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js'></script>
    <script src='https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js'></script>
    <script src='https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js'></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                //disable sorting on last column
                "columnDefs": [{
                    "orderable": false,
                    "targets": 5
                }],
                language: {
                    //customize pagination prev and next buttons: use arrows instead of words
                    'paginate': {
                        'previous': '<span class="fa fa-chevron-left"></span>',
                        'next': '<span class="fa fa-chevron-right"></span>'
                    },
                    //customize number of elements to be displayed
                    "lengthMenu": 'Display <select class="form-control input-sm">' +
                        '<option value="10">10</option>' +
                        '<option value="20">20</option>' +
                        '<option value="30">30</option>' +
                        '<option value="40">40</option>' +
                        '<option value="50">50</option>' +
                        '<option value="-1">All</option>' +
                        '</select> results'
                }
            })
        });
        $(document).ready(function() {
            $('#example2').DataTable({
                //disable sorting on last column
                "columnDefs": [{
                    "orderable": false,
                    "targets": 5
                }],
                language: {
                    //customize pagination prev and next buttons: use arrows instead of words
                    'paginate': {
                        'previous': '<span class="fa fa-chevron-left"></span>',
                        'next': '<span class="fa fa-chevron-right"></span>'
                    },
                    //customize number of elements to be displayed
                    "lengthMenu": 'Display <select class="form-control input-sm">' +
                        '<option value="10">10</option>' +
                        '<option value="20">20</option>' +
                        '<option value="30">30</option>' +
                        '<option value="40">40</option>' +
                        '<option value="50">50</option>' +
                        '<option value="-1">All</option>' +
                        '</select> results'
                }
            })
        });
    </script>

    <script>
        function showConfirmation(itemId) {
            if (confirm('هل أنت متأكد أنك تريد المتابعة؟')) {
                document.getElementById('confirmationForm' + itemId).submit();
            }
        }
    </script>
</body>

</html>
