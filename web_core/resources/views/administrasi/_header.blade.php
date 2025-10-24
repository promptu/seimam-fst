<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ url(env('APP_FAVICON')) }}">
    <title>{{ $user_ses['active_app']['nama'] . ' | ' . $page_title['bread'] }}</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('assets/bo/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/bo/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }} ">
    <link rel="stylesheet" href="{{ url('assets/bo/plugins/daterangepicker/daterangepicker.css') }} ">
    <link rel="stylesheet" href="{{ url('assets/bo/plugins/select2/css/select2.min.css') }} ">
    <link rel="stylesheet" href="{{ url('assets/bo/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }} ">
    @yield('addon_header')
    <link rel="stylesheet" href="{{ url('assets/bo/dist/css/adminlte.min.css') }} ">
    <style>
        .bg-main {
            background-color: rgba(10, 25, 47, 0.95);
            color: white;
        }

        .card-main.card-outline {
            border-top: 2px solid rgba(10, 25, 47, 0.95) !important;
        }

        .main-nav.navbar-nav>.nav-item {
            padding: 0px;
        }

        .main-nav.navbar-nav>.nav-item>.nav-link {
            background: solid 1px rgba(29, 81, 158, 0.65);
            border-radius: 5px;
            color: white;
            height: 33px;
        }

        .main-nav.navbar-nav>.nav-item>.nav-link:hover {
            background-color: rgba(29, 81, 158, 0.65);
            color: white;
        }

        .main-nav.navbar-nav>.nav-item.dropdown>ul {
            margin-top: 0px;
        }

        .dropdown>.dropdown-menu>li>.dropdown-item:hover {
            background-color: rgba(29, 81, 158, 0.65);
            color: white;
        }

        .dropdown-submenu>.dropdown-menu>li>.dropdown-item:hover {
            background-color: rgba(29, 81, 158, 0.65);
            color: white;
        }

        .notif-icon.nav-item.dropdown>.nav-link {
            background-color: rgba(29, 81, 158, 0.65);
            opacity: 0.7;
            border-radius: 50%;
            height: 41px;
            width: 41px;
            padding: 11px;
        }

        .form-control {
            height: calc(1.8125rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            color: black !important;
        }

        select.form-control~.select2-container--default {
            font-size: 0.875rem;
        }

        .text-sm .select2-container--default .select2-selection--single,
        select.form-control~.select2-container--default .select2-selection--single {
            height: calc(1.8125rem + 2px);
        }

        .text-sm .select2-container--default .select2-selection--single .select2-selection__rendered,
        select.form-control~.select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -.4rem;
        }

        .text-sm .select2-container--default .select2-selection--single .select2-selection__arrow,
        select.form-control~.select2-container--default .select2-selection--single .select2-selection__arrow {
            top: -.12rem;
        }

        .text-sm .select2-container--default .select2-selection--multiple,
        select.form-control~.select2-container--default .select2-selection--multiple {
            min-height: calc(1.8125rem + 2px);
        }

        .text-sm .select2-container--default .select2-selection--multiple .select2-selection__rendered,
        select.form-control~.select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0 0.25rem 0.25rem;
            margin-top: -0.1rem;
        }

        .text-sm .select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline,
        select.form-control~.select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline {
            margin-left: 0.25rem;
        }

        .text-sm .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-search.select2-search--inline .select2-search__field,
        select.form-control~.select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-search.select2-search--inline .select2-search__field {
            margin-top: 6px;
        }

        .table th,
        .table td {
            padding: 0.3rem;
        }

        .btn,
        .btn-group>.btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .btn+.dropdown-toggle-split,
        .btn-group>.btn+.dropdown-toggle-split {
            padding-right: 0.375rem;
            padding-left: 0.375rem;
        }

        .table td .btn {
            padding: 1px 3px 1px 3px;
            min-width: 25px;
        }

        .drop-container {
            position: relative;
            display: flex;
            gap: 0px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* height: 201px; */
            padding: 1px;
            border-radius: 5px;
            border: 2px dashed grey;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
            overflow: hidden;
        }

        .drop-container .drop-container:hover {
            background: #eee;
            border-color: #111;
        }

        .drop-container:hover .drop-title {
            color: #222;
        }

        .drop-title {
            color: #444;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            transition: color .2s ease-in-out;
        }

        .drop-file {
            visibility: hidden;
            height: 0px;
        }

        .note-toolbar {
            padding: 5px;
        }

        .note-modal .modal-dialog .modal-content .modal-header {
            padding: 5px 15px;
        }

        .note-modal .modal-dialog .modal-content .modal-footer {
            padding: 5px;
        }

        .ml-auto {
            display: inline-block !important;
        }

        .alert-footer {
            background: #cccccc;
        }

        .label-fr {
            margin-bottom: 3px;
        }

        .label-fr-dark {
            margin-bottom: 3px;
        }

        .box-fr {
            border: 1px solid #ccc;
            color: #000000;
            min-height: 32px;
            border-radius: 3px;
            padding: 5px 10px;
        }

        @media screen and (min-width: 992px) {
            .label-fr {
                padding-bottom: 1px;
                border-bottom: dotted 1px #ccc;
                padding-left: 3px;
            }

            .label-fr-dark {
                padding-bottom: 1px;
                border-bottom: dotted 1px #000000;
                padding-left: 3px;
            }

            .ppg {
                max-width: 100px;
            }
        }

        .info-hover>a {
            text-decoration: none;
        }

        .info-hover:hover {
            background-color: #93c6cf;
            color: white;
        }

        .info-active>a {
            text-decoration: none;
            color: white;
        }

        .info-active {
            background-color: #598991;
            color: white;
        }

        .note-editor .note-editable p {
            line-height: 1.5;
            margin: 0px 0px;
        }

        .box-fr p {
            line-height: 1.5;
            margin: 0px 0px;
        }

        .navbar-custom-bg {
            color: white;
            background-color: rgba(10, 25, 47, 0.95);
            background-image: url("{{ url('assets/img/bg2.jpg') }}");
            background-repeat: repeat;
            background-size: 100px 100px;
            background-blend-mode: overlay;
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1340px;
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav text-sm layout-footer-fixed sidebar-collapse">
    <div class="wrapper">
        @include('administrasi._navbar')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{!! '<i class="' . $page_title['icon'] . '"></i> ' . $page_title['bread'] !!}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><i class="fas fa-link"></i></li>
                                @foreach ($page_title['links'] as $r)
                                    <li class="breadcrumb-item {{ $r['active'] }}">{!! $r['title'] !!}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
