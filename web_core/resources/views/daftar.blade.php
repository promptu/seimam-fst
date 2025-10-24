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
			/* background-image: url("{{ url('assets/bo/img/1521581.webp') }}") !important; */
			/* background-repeat: no-repeat !important; */
			/* background-size: cover !important; */
			background-color: #42A3A7;
		}
		.login-row{ max-width:400px; margin: auto; }
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
		.bg-main{ background-color: #0A525A; color:white; }

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
				<div class="login-row p-4">
					<div class="rounded bg-main">
							<div class="p-4">
								<div class="text-center">
									<img src="{{url('assets/bo/img/logo.png')}}" alt="" width="80px" class="mb-0">
									<h4 class="mb-4">Daftar Akun Mahasiswa</h4>
								</div>
								<hr class="mb-4 bg-white">
                <div id="alert"></div>
								<form id="fdaftar" action="#" method="post" autocomplete="off">
									<label for="in1" class="mb-1">NIM :</label>
									<div class="input-group mb-3">
										<input type="text" id="in1" class="form-control form-control-sm" placeholder="NIM" autofocus>
										<div class="input-group-append"><div class="input-group-text bg-warning"><span class="fas fa-user"></span></div></div>
									</div>
									<label for="in2" class="mb-1">Email :</label>
									<div class="input-group">
										<input type="text" id="in2" class="form-control form-control-sm" placeholder="Email">
										<div class="input-group-append"><div class="input-group-text bg-warning"><span class="fas fa-at"></span></div></div>
									</div>
									<small>Gunakan E-mail yang terdaftar di SIAKAD.</small><br><br>
									<label for="in3" class="mb-1">Buat Password :</label>
									<div class="input-group mb-3">
										<input type="password" id="in3" class="form-control form-control-sm" placeholder="Password">
										<div class="input-group-append"><a href="#" class="toggle-input input-group-text bg-warning" data-input="#in3" data-type="password"><span class="fas fa-eye-slash"></span></a></div>
									</div>
									<label for="in4" class="mb-1">Konfirmasi Password :</label>
									<div class="input-group mb-4">
										<input type="password" id="in4" class="form-control form-control-sm" placeholder="Konfirmasi Password">
										<div class="input-group-append"><a href="#" class="toggle-input input-group-text bg-warning" data-input="#in4" data-type="password"><span class="fas fa-eye-slash"></span></a></div>
									</div>
									<div class="row mt-4">
										<div class="col-12 mb-2">
											<button type="submit" id="fdaftar-btn" class="btn btn-warning btn-block"><i class="fas fa-paper-plane"></i> Buat Akun</button>
										</div>
										<div class="col-12"><p>Sudah punya akun ? <a href="{{ url('/') }}"><b>Sign-in Disini</b></a><br> atau <a href="{{ url('/lupa-password') }}"><b>Lupa Password ?</b></a></p></div>
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
		function toasterr(datas) { const err = datas.responseJSON; toast('error',err.message); }

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

		$('#fdaftar').submit(function(e){
			e.preventDefault();
			const in1 = $('#in1').val();
			const in2 = $('#in2').val();
			const in3 = $('#in3').val();
			const in4 = $('#in4').val();
			const uri = $(this).attr('action');
			const bid = $('#fdaftar-btn');
			const bval = bid.html();
			$.ajax({
				url: "{{url($ctr['link'].'/proses')}}", type: 'post', dataType: 'json', data: {'_token':token,'in1':in1,'in2':in2,'in3':in3,'in4':in4},
				beforeSend: function(){
					bid.html(loading).attr('disabled',true);
				}, success: function(d){
          $('#alert').html('<div class="callout callout-info text-info">'+d.statusText+'</div>');
					if (d.status == 'success') {
            bid.html(bval);
					} else {            
            bid.html(bval).attr('disabled', false);
          }
				}, error: function(d){ bid.html(bval).attr('disabled',false); toasterr(d); }
			});
		});

	});
	</script>
</body>
</html>
