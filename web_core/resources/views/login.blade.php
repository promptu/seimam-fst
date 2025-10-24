<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ env('APP_NAME').' | '.$page_title['bread'] }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ env('APP_FAVICON') }}">
  <link rel="stylesheet" href="{{ url('assets/bo/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/bo/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/bo/dist/css/adminlte.min.css') }}">
  <link href="{{ url('assets/bo/google-font.css') }}" rel="stylesheet">
	<style>
		body {
			background-color: rgba(10, 25, 47, 0.95);
			background-image: url("{{ url('assets/img/bg2.jpg') }}");
			background-repeat: repeat;
			background-size: 100px 100px;
			background-blend-mode: overlay;
		}
		.login-row{ max-width:850px; margin: auto; }
		.login-col-left {
			background-image: url("{{ url('assets/bo/img/login_bg.png') }}") !important;
			background-repeat: no-repeat !important;
			background-size: cover !important;
			min-width: 200px;
			position: relative;
		}
		.login-col-left>.greeting{
			position: absolute;
			bottom: 0;
		}
		.bg-main{ background-color: rgba(10, 25, 47, 0.25); color:white; }

		@media (min-width:577px){
			.login-row{ margin-top:100px; }
			.login-col-left{ border-radius: 8px 0 0 8px; height: 460px; }
			.login-col-right{ border-radius: 0 8px 8px 0; }
		}
		@media (max-width:576px){
			.login-col-left{ border-radius: 8px 8px 0 0; height: 200px;}
			.login-col-right{ border-radius: 0 0 8px 8px; }
		}
	</style>
</head>
<body class="hold-transition text-sm">
	<div class="wrapper">
		<div class="container">
			<div class="content">
				<div class="row login-row p-4">
					<div class="col-md-7 login-col-left text-white">
						<div class="greeting p-3">
							<img src="{{url(env('APP_ICON'))}}" alt="" width="80px"><br>
							<u>Selamat Datang</u>
							<h3>{!! env(('APP_NAME_HTML')) !!}</h3>
						</div>
					</div>
					<div class="col-md-5 login-col-right p-0 bg-main">
							<div class="p-4">
								<h4 class="mb-4 mt-4 text-center">Silahkan Sign-In terlebih dahulu!</h4>
								<hr class="mb-4 bg-white">
								<form id="flogin" action="#" method="post" autocomplete="off">
									<label for="in1">Username :</label>
									<div class="input-group mb-3">
										<input type="text" id="in1" class="form-control form-control-sm" placeholder="Username" autofocus>
										<div class="input-group-append"><div class="input-group-text bg-warning"><span class="fas fa-user"></span></div></div>
									</div>
									<label for="in2">Password :</label>
									<div class="input-group mb-4">
										<input type="password" id="in2" class="form-control form-control-sm" placeholder="Password">
										<div class="input-group-append"><a href="#" class="toggle-input input-group-text bg-warning" data-input="#in2" data-type="password"><span class="fas fa-eye-slash"></span></a></div>
									</div>
									<div class="row mt-4">
										<div class="col-12 mb-2">
											<button type="submit" id="flogin-btn" class="btn btn-warning btn-block"><i class="fas fa-sign-in-alt"></i> Sign In</button>
										</div>
										<div class="col-12"><p>Mahasiswa, belum punya akun ? <a href="{{ url('/daftar') }}"><b>Daftar Disini</b></a></p></div>
									</div>
								</form>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ url('assets/bo/plugins/jquery/jquery.min.js') }}"></script>
	<script src="{{ url('assets/bo/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ url('assets/bo/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
	<script src="{{ url('assets/bo/dist/js/adminlte.min.js') }}"></script>
	<script>
	$(function(){
		let token = "{{ csrf_token() }}";
		const loading = '<i class="fas fa-sync-alt fa-spin"></i> Memproses...';
		const Toast = Swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
		function toast(type, text) { Toast.fire({ icon: type, title: '<p class="text-left m-2">'+text+'</p>' }); }
		function toasterr(datas) { const err = datas.responseJSON; if (typeof err.status != 'undefined') { if (err.status == "invalid_request") { toast('error',err.statusText); setTimeout(() => { window.location.reload(); }, 1000); } else { toast('error', statusText); } } else if (typeof err.message != 'undefined') { toast('error', err.message); } else { toast('error', "Terjadi kesalahan."); } }

		$('.toggle-input').click(function(e){
			e.preventDefault();
			const cur = $(this);
			const cur_type = cur.data('type');
			const cur_input = cur.data('input');
			const new_type = (cur_type == 'password') ? 'text' : 'password';
			const new_icon = (cur_type == 'password') ? '' : '-slash';
			$(cur_input).attr('type', new_type);
			cur.html('<span class="fas fa-eye'+new_icon+'"></span>');
			cur.data('type',new_type);
		});

		$('#flogin').submit(function(e){
			e.preventDefault();
			const in1 = $('#in1').val();
			const in2 = $('#in2').val();
			const uri = $(this).attr('action');
			const bid = $('#flogin-btn');
			const bval = bid.html();
			if (in1 == '') { toast('info','Silahkan isi Username'); $('#in1').focus(); return; }
			if (in2 == '') { toast('info','Silahkan isi Password'); $('#in2').focus(); return; }
			$.ajax({
				url: "{{url($ctr['link'].'/check')}}", type: 'post', dataType: 'json', data: {'_token':token,'in1':in1,'in2':in2},
				beforeSend: function(){
					bid.html(loading).attr('disabled',true);
				}, success: function(d){
					toast(d.status, d.statusText);
					if (d.status=='success') {
						setTimeout(() => {
							window.location.replace(d.toUrl);							
						}, 500);
					} else {
            bid.html(bval).attr('disabled', false);
						$('#in2').val('');
						$('#in1').val('').focus();
					}
				}, error: function(d){ bid.html(bval).attr('disabled',false); toasterr(d); }
			});
		});

	});
	</script>
</body>
</html>
