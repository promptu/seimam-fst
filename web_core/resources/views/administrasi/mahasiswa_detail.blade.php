@include('administrasi._header')
<div class="content">
	<div class="container">
		<div class="card card-outline card-warning">
			<div class="card-footer p-2 text-right">
				<a href="{{ url($back_path) }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
				<div class="btn-group">
					<button type="button" class="btn btn-info"><i class="fas fa-cloud-download-alt"></i> Sinkron ke SIAKAD</button>
					<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
					<span class="sr-only">Toggle Dropdown</span>
					</button>
					<div class="dropdown-menu" role="menu">
						<a class="dropdown-item" href="#" id="bsync"><i class="fas fa-cog"></i> Proses Sinkron</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card card-outline card-secondary">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-6">
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">NIM :</label>
							<div class="col-lg-6">
								<div class="box-fr">{{ $get['nim'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Nama :</label>
							<div class="col-lg-7">
								<div class="box-fr">{{ $get['nama'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Gelar Depan :</label>
							<div class="col-lg-3">
								<div class="box-fr">{{ $get['gelar_depan'] }}</div>
							</div>
						</div>
						<div class="row mb-4">
							<label class="col-lg-4 label-fr">Gelar Belakang :</label>
							<div class="col-lg-3">
								<div class="box-fr">{{ $get['gelar_belakang'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">NISN :</label>
							<div class="col-lg-5">
								<div class="box-fr">{{ $get['nisn'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">NPSN :</label>
							<div class="col-lg-5">
								<div class="box-fr">{{ $get['npsn'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Tempat Lahir :</label>
							<div class="col-lg-7">
								<div class="box-fr">{{ $get['tempat_lahir'] }}</div>
							</div>
						</div>
						<div class="row mb-4">
							<label class="col-lg-4 label-fr">Tanggal Lahir :</label>
							<div class="col-lg-4">
								<div class="box-fr">{{ $mylib::indotgl($get['tanggal_lahir']) }}</div>
							</div>
						</div>
						<div class="row mb-4">
							<label class="col-lg-4 label-fr">Jenis Kelamin :</label>
							<div class="col-lg-4">
								<div class="box-fr">{{ $mylib::jenis_kelamin($get['jenis_kelamin']) }}</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Alamat :</label>
							<div class="col-lg-7">
								<div class="box-fr">{{ $get['alamat'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Email :</label>
							<div class="col-lg-5">
								<div class="box-fr">{{ $get['email'] }}</div>
							</div>
						</div>
						<div class="row mb-4">
							<label class="col-lg-4 label-fr">Email Kampus :</label>
							<div class="col-lg-5">
								<div class="box-fr">{{ $get['email_kampus'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Status Aktif :</label>
							<div class="col-lg-7">
								<div class="box-fr">{{ $get['status_mahasiswa_nama'] }}</div>
							</div>
						</div>
						<div class="row mb-4">
							<label class="col-lg-4 label-fr">Jenjang :</label>
							<div class="col-lg-7">
								<div class="box-fr">{{ $get['jenjang_nama'].' ('.$get['jenjang_kode'].')' }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Program Studi :</label>
							<div class="col-lg-6">
								<div class="box-fr">{{ $get['program_studi_nama'] }}</div>
							</div>
						</div>
						<div class="row mb-2">
							<label class="col-lg-4 label-fr">Periode Masuk :</label>
							<div class="col-lg-6">
								<div class="box-fr">{{ $get['periode_id'] }}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>
</div>

<div class="modal fade" id="mdsync" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h5 class="text-info" id="mdsync-nm"></h5>
				<div id="mdsync-proc">
					<div class="progress progress-xs mb-2">
						<div id="mdsync-progress" class="progress-bar bg-info progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%"><span class="sr-only"></span></div>
					</div>
					<i class="fas fa-sync-alt fa-spin"></i> Sedang menyinkronkan ke SIAKAD ...
				</div>
			</div>
		</div>
	</div>
</div>


@section('addonjs')
<script>
	$('#bsync').click(function(e){
		e.preventDefault();
		$('#mdsync-nm').html('<i class="fas fa-user-graduate"></i> Data Mahasiswa');
		$('#mdsync').modal('show').on('shown.bs.modal', function(){
			let prog = 0;
			const dsnint = setInterval(() => {
				prog = prog + 10;
				if (prog <= 60) {
					$('#mdsync-progress').css('width', prog+'%');					
				} else {
					clearInterval(dsnint);
				}
			}, 500);
			$.ajax({
				url:"{{ url($ctr_path.'/pull') }}", type:'post', dataType:'json', data:{'_token':token, 'id':"{{ $get['nim'] }}"},
				success: function(d){
					clearInterval(dsnint);
					if (d.status == 'success') {
						$('#mdsync-progress').css('width', '100%');
					}
					setTimeout(() => {
						$('#mdsync-proc').html('<div class="alert alert-info">'+d.statusText+'<br><a href="#" onclick="window.location.reload(); return false;"><i class="fas fa-sync-alt"></i> Muat Ulang</a></div>');
					}, 300);
				},
				error: function(d){
					clearInterval(dsnint);
					$('#mdsync-proc').html('<div class="alert alert-warning mb-1"><b><i class="fas fa-exclamation"></i> Ooops</b><br>Terjadi Kesalahan saat menghubungkan ke server.<br><a href="#" onClick="window.location.reload(); return false;"><i class="fas fa-sync-alt"></i> Muat Ulang</div>');
				}
			})
		});
	});
</script>
@endsection
@include('administrasi._footer')