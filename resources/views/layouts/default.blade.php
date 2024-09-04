<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('logo.jpeg') }}">
    <title>
        SamerNomer System
    </title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- CSS Files --> 
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />

    <style>
        body {
            font-family: 'Cairo', sans-serif !important;
            background-image: url('{{ asset('logo.jpeg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .btn:hover {
            background-color: #0056b3;
            box-shadow: 0 0 10px #007bff, 0 0 20px #007bff, 0 0 30px #007bff;
        }

        .btn::before,
        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            transition: all 0.4s ease;
            transform: translate(-50%, -50%) scale(0.5);
            opacity: 0;
            z-index: -1;
        }

        .btn:hover::before,
        .btn:hover::after {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .custom-form-control {
            display: block;
            width: 50%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 10px;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .custom-form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #f480ff;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(205, 35, 248, 0.25);
        }


        @media print {

            .btn,
            .footer,
            .input-group-text,
            .form-control {
                display: none;
            }

            .custom-form-control {
                display: block;
                width: 100%;
                height: 100%;
                vertical-align: middle;
                font-size: 12px;
                line-height: 1.5;
                color: #495057;
                background-color: transparent;
                background-clip: padding-box;
                border: 0px solid #fff;
                text-align: center;
                font-weight: bold;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            .table {
                width: 100%;
                font-size: 12px;
                border-collapse: collapse;
                margin-bottom: 1rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.5rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .table tbody+tbody {
                border-top: 2px solid #dee2e6;
            }

            .table .table {
                background-color: #fff;
            }

            .table-sm th,
            .table-sm td {
                padding: 0.3rem;
            }

            .table-bordered {
                border: 1px solid #dee2e6;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #dee2e6;
            }

            .table-bordered thead th,
            .table-bordered thead td {
                border-bottom-width: 2px;
            }

            .table-borderless th,
            .table-borderless td,
            .table-borderless thead th,
            .table-borderless tbody+tbody {
                border: 0;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.05);
            }

            .table-hover tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table-primary,
            .table-primary>th,
            .table-primary>td {
                background-color: #b8daff;
            }

            .table-hover .table-primary:hover {
                background-color: #9fcdff;
            }

            .table-hover .table-primary:hover>td,
            .table-hover .table-primary:hover>th {
                background-color: #9fcdff;
            }

            .table-secondary,
            .table-secondary>th,
            .table-secondary>td {
                background-color: #d6d8db;
            }

            .table-hover .table-secondary:hover {
                background-color: #c8cbcf;
            }

            .table-hover .table-secondary:hover>td,
            .table-hover .table-secondary:hover>th {
                background-color: #c8cbcf;
            }

            .table-success,
            .table-success>th,
            .table-success>td {
                background-color: #c3e6cb;
            }

            .table-hover .table-success:hover {
                background-color: #b1dfbb;
            }

            .table-hover .table-success:hover>td,
            .table-hover .table-success:hover>th {
                background-color: #b1dfbb;
            }

            .table-info,
            .table-info>th,
            .table-info>td {
                background-color: #bee5eb;
            }

            .table-hover .table-info:hover {
                background-color: #abdde5;
            }

            .table-hover .table-info:hover>td,
            .table-hover .table-info:hover>th {
                background-color: #abdde5;
            }

            .table-warning,
            .table-warning>th,
            .table-warning>td {
                background-color: #ffeeba;
            }

            .table-hover .table-warning:hover {
                background-color: #ffe8a1;
            }

            .table-hover .table-warning:hover>td,
            .table-hover .table-warning:hover>th {
                background-color: #ffe8a1;
            }

            .table-danger,
            .table-danger>th,
            .table-danger>td {
                background-color: #f5c6cb;
            }

            .table-hover .table-danger:hover {
                background-color: #f1b0b7;
            }

            .table-hover .table-danger:hover>td,
            .table-hover .table-danger:hover>th {
                background-color: #f1b0b7;
            }

            .table-light,
            .table-light>th,
            .table-light>td {
                background-color: #fdfdfe;
            }

            .table-hover .table-light:hover {
                background-color: #ececf6;
            }

            .table-hover .table-light:hover>td,
            .table-hover .table-light:hover>th {
                background-color: #ececf6;
            }

            .table-dark,
            .table-dark>th,
            .table-dark>td {
                background-color: #c6c8ca;
            }

            .table-hover .table-dark:hover {
                background-color: #b9bbbe;
            }

            .table-hover .table-dark:hover>td,
            .table-hover .table-dark:hover>th {
                background-color: #b9bbbe;
            }

            .table-active,
            .table-active>th,
            .table-active>td {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table-hover .table-active:hover {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table-hover .table-active:hover>td,
            .table-hover .table-active:hover>th {
                background-color: rgba(0, 0, 0, 0.075);
            }

            .table .thead-dark th {
                color: #fff;
                background-color: #343a40;
                border-color: #454d55;
            }

            .table .thead-light th {
                color: #495057;
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            .table-dark {
                color: #fff;
                background-color: #343a40;
            }

            .table-dark th,
            .table-dark td,
            .table-dark thead th {
                border-color: #454d55;
            }

            .table-dark.table-bordered {
                border: 0;
            }

            .table-dark.table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(255, 255, 255, 0.05);
            }

            .table-dark.table-hover tbody tr:hover {
                background-color: rgba(255, 255, 255, 0.075);
            }
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    @if (Auth::user()->role !== 'driver')
        <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
            id="sidenav-main">
            @include('layouts.sidebar')
        </aside>
    @endif
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        @include('layouts.nav')
        <!-- End Navbar -->
        <div id="pageContentPdf">
            @include('layouts.body') 
        </div>

        @include('layouts.footer')

        <div class="dropup position-fixed bottom-0 end-0 rounded-circle m-5">
            <button onclick="printDiv('pageContentPdf')" type="button" class="btn btn-danger">
                PDF
            </button>
        </div>
    </main> 

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#fff",
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        },
                    },
                },
            },
        });

        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                        label: "Mobile apps",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                        maxBarThickness: 6

                    },
                    {
                        label: "Websites",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

</body>

</html>
