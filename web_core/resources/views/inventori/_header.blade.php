<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ url('assets/bo/img/favicon.png') }}">
  <title>{{ $user_ses['active_app']['nama'].' | '.$page_title['bread'] }}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ url('assets/bo/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/bo/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}} ">
  <link rel="stylesheet" href="{{ url('assets/bo/plugins/daterangepicker/daterangepicker.css')}} ">
  <link rel="stylesheet" href="{{ url('assets/bo/plugins/select2/css/select2.min.css')}} ">
	<link rel="stylesheet" href="{{ url('assets/bo/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}} ">
	@yield('addon_header')
  <link rel="stylesheet" href="{{ url('assets/bo/dist/css/adminlte.min.css')}} ">
	<style>		
		.bg-main{ background-color: #0A525A; color:white; }
		.card-main.card-outline{ border-top:2px solid #0A525A !important; }
		.main-nav.navbar-nav > .nav-item { padding: 0px; }
		.main-nav.navbar-nav > .nav-item > .nav-link { border: solid 1px #0A525A; border-radius: 5px; color:white; height: 33px; }
		.main-nav.navbar-nav > .nav-item > .nav-link:hover { background-color: #42A3A7; border: solid 1px #0A525A; color:white; }
		.main-nav.navbar-nav > .nav-item.dropdown > ul{ margin-top: 0px; }
		.dropdown > .dropdown-menu > li > .dropdown-item:hover { background-color:#42A3A7; color:white; }
		.dropdown-submenu > .dropdown-menu > li > .dropdown-item:hover { background-color:#42A3A7; color:white; }
		.notif-icon.nav-item.dropdown > .nav-link{ background-color:#3EB489; opacity:0.7; border-radius:50%; height:41px; width:41px; padding: 11px;}
		.form-control { height: calc(1.8125rem + 2px); padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; border-radius: 0.2rem; color:black !important; }
		
		select.form-control ~ .select2-container--default { font-size: 0.875rem; }
		.text-sm .select2-container--default .select2-selection--single, select.form-control ~ .select2-container--default .select2-selection--single { height: calc(1.8125rem + 2px); }
		.text-sm .select2-container--default .select2-selection--single .select2-selection__rendered, select.form-control ~ .select2-container--default .select2-selection--single .select2-selection__rendered { margin-top: -.4rem; }
		.text-sm .select2-container--default .select2-selection--single .select2-selection__arrow, select.form-control ~ .select2-container--default .select2-selection--single .select2-selection__arrow { top: -.12rem; }
		.text-sm .select2-container--default .select2-selection--multiple, select.form-control ~ .select2-container--default .select2-selection--multiple { min-height: calc(1.8125rem + 2px); }
		.text-sm .select2-container--default .select2-selection--multiple .select2-selection__rendered, select.form-control ~ .select2-container--default .select2-selection--multiple .select2-selection__rendered { padding: 0 0.25rem 0.25rem; margin-top: -0.1rem; }
		.text-sm .select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline, select.form-control ~ .select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline { margin-left: 0.25rem; }
		.text-sm .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-search.select2-search--inline .select2-search__field, select.form-control ~ .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-search.select2-search--inline .select2-search__field { margin-top: 6px; }
		
		.table th, .table td { padding: 0.3rem; }
		.btn, .btn-group > .btn { padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; border-radius: 0.2rem; }
		.btn + .dropdown-toggle-split, .btn-group > .btn + .dropdown-toggle-split { padding-right: 0.375rem; padding-left: 0.375rem; }
		.table td .btn { padding: 1px 3px 1px 3px; min-width:25px; }
		.drop-container { position: relative; display: flex; gap: 0px; flex-direction: column; justify-content: center; align-items: center; /* height: 201px; */ padding: 1px; border-radius: 5px; border: 2px dashed grey; color: #444; cursor: pointer; transition: background .2s ease-in-out, border .2s ease-in-out; overflow:hidden; }
		.drop-container
		.drop-container:hover { background: #eee; border-color: #111; }
		.drop-container:hover .drop-title { color: #222; }
		.drop-title { color: #444; font-size: 20px; font-weight: bold; text-align: center; transition: color .2s ease-in-out; }
		.drop-file{ visibility: hidden; height: 0px; }
		.note-toolbar{ padding: 5px; }
		.note-modal .modal-dialog .modal-content .modal-header{ padding: 5px 15px; }
		.note-modal .modal-dialog .modal-content .modal-footer{ padding: 5px; }
		
		/* .dropdown:hover>.dropdown-menu { display: block; }
		.dropdown-item:hover>.dropdown-menu { display: block; } */
		/* ul li{ list-style-type:none; display: inline; } */
		/* .navbar-nav .nav-link{display:inline-block;}			 */
		.ml-auto {display:inline-block!important;}
		/* .dropdown>.dropdown-toggle:active { pointer-events: none; } */
		.alert-footer{ background: #cccccc; }
		.label-fr{ margin-bottom: 3px; }
		.box-fr{ border: 1px solid #ccc; color: #000000; min-height: 32px; border-radius: 3px; padding: 5px 10px; }
		@media screen and (min-width: 992px) {
			.label-fr{ padding-bottom: 1px; border-bottom: dotted 1px #ccc; padding-left: 3px; }
			.ppg{ max-width: 100px;}
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
						<h1>{!! '<i class="'.$page_title['icon'].'"></i> '.$page_title['bread'] !!}</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><i class="fas fa-link"></i></li>
							@foreach ($page_title['links'] as $r)
							<li class="breadcrumb-item {{$r['active']}}">{!!$r['title']!!}</li>							
							@endforeach
						</ol>
					</div>
				</div>
			</div>
		</div>
		