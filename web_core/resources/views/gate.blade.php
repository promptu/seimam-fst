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
		.bg-main{ background-color: rgba(10, 25, 47, 0.25); color:white; border-radius: 6px; }
		.ahover {
			margin: auto;
			transition: all 0.3s ease-in-out;
			box-shadow: 0 0 0 rgba(0, 0, 0, 0);
			
		}
		.ahover:hover {
			transform: translateY(-1px);
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
			border-radius: 6px;
		}
		.bchoose {
			margin: auto;
			transition: all 0.3s ease-in-out;
			box-shadow: 0 0 0 rgba(0, 0, 0, 0);
			border-radius: 7px;
		}
		.bchoose:hover {
			transform: translateY(-5px);
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
		}
    .border-main { border: #0A525A solid 1px; }
		@media (min-width:577px){
			.login-row{ margin-top:100px; }
		}
	</style>
</head>
<body class="hold-transition text-sm">
	<div class="wrapper">
		<div class="container">
			<div class="content">
				<div class="login-row" style="border-radius: 7px 7px 6px 6px;">
					<nav class="row navbar navbar-expand-md navbar-light bg-main m-0">						
						<div class="col-md-8 p-3">
							<img src="{{url(env('APP_ICON'))}}" alt="" width="70px" class="float-left mr-2">	
							<div class="float-left">
								<h3 class="mb-0">{!! env(('APP_NAME_HTML')) !!}</h3>
								<h6 class=""><b>{!! env(('APP_NAME_DESC')) !!}</b></h6>
							</div>
							<button class="navbar-toggler order-1 float-right bg-white p-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
						</div>
						<div class="col-md d-flext justify-content-end collapse navbar-collapse order-3" id="navbarCollapse">
							<a href="" class="btn btn-default mr-2 text-right"><i class="fas fa-user-edit"></i> Profil</a>
							<a href="{{ url('/sign-out') }}" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Sign-out</a>
						</div>
					</nav>
					<div class="rounded-bottom bg-light rounded pt-4">
						<h4 class="mx-4 mt-4"><b>Daftar Modul</b></h4>
						<div class="row px-4 pb-4">
              @foreach ($user_ses['roles'] as $r)
              @php $link = ($r['app_link']) ? (($r['app_is_external_link']) ? url($r['app_link']) : '') : ''; @endphp
              <div class="col-md-4 text-center p-3">
								<a href="{{ $link }}"
									class="bchoose btn btn-default btn-block pt-4"
									data-id="{{ $r['app_id'] }}"
									data-nm="{{ $r['app_nama'] }}">
									<img src="{{ ($r['app_pict']) ? url($r['app_pict']) : '#' }}" alt="{{ $r['app_nama'] }}" style="width: 60px">
									<p class="text-bold mt-2">{{ $r['app_nama'] }}</p>
								</a>
							</div>
              @endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="mdch" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-light">
					<h5 class="modal-title">
						<b>Modul : </b><span id="mdch-txt"></span>
					</h5>
				</div>
				<div class="modal-body">
					<h5 class="text-secondary"><b>Login Sebagai :</b></h5>
					<input type="hidden" name="mdch-in" id="mdch-in" value="">
          @foreach ($user_ses['roles'] as $r)
          <div id="app-{{ $r['app_id'] }}" class="role-box">
            @foreach ($r['app_roles'] as $ar)
            <a href="#" class="bch-role" data-rid="{{ $ar['role_id'] }}" style="color:black; font-weight: bold; font-style: none;">
              <div class="alert alert-default border mb-2 ahover">
                <p class="mb-0">{{ $ar['role_nama'] }}</p>
              </div>
            </a>
            @endforeach
          </div>
          @endforeach
          <div>&nbsp;</div>
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
		function toasterr(datas) {
			const err = datas.responseJSON;
			if (typeof err.status == 'undefined') {
				toast('error', "Terjadi kesalahan.");
			} else {
				if (err.status == "invalid_request") {
					toast('error',err.statusText);
					setTimeout(() => { window.location.reload(); }, 1000);
				}
			}
		}

		$('.bchoose').click(function(e){
			e.preventDefault();
			const id = $(this).data('id');
			const nm = $(this).data('nm');
      $('.role-box').hide();
      $('#app-'+id).show();
			$('#mdch-in').val(id);
			$('#mdch-txt').text(nm);
			$('#mdch').modal('show');
		});

		$('.bch-role').click(function(e){
			e.preventDefault();
			const aid = $('#mdch-in').val();
			const rid = $(this).data('rid');
			$.ajax({
				url:"{{ url($ctr['link'].'/choose') }}", type:'post', dataType:'json', data:{'_token':token, 'aid':aid, 'rid':rid},
				success: function(d){
					if (d.status == 'success') {
						window.location.replace(d.toUrl);
					} else {
						toast(d.status, d.statusText);
					}
				}, error: function(d){ toasterr(d); }
			});
		});

	});
	</script>
</body>
</html>
