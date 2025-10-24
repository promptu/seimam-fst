@include('administrasi._header')
<div class="content">
	<div class="container">
		<form action="{{ url($ctr_path) }}" method="post" id="fr0" autocomplete="off">
			@csrf
			<input type="hidden" id="filter" name="filter" value="filter">
			<div class="card card-outline card-warning">
				<div class="card-body pb-2 row">
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f1" class="col-lg-4 label-fr">Unit Kerja :</label>
							<div class="col-lg-7">
								<select name="f1" id="f1" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['unit_kerja'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f1']) ? 'selected' : '' }}>{{ $mylib::tree_view($c['val'], $c['level']) }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f1f2" class="col-lg-4 label-fr">Nama Pegawai :</label>
							<div class="col-lg-7">
								<input type="text" name="f2" id="f2" class="form-control" value="{{ $var['f2'] }}">
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f4" class="col-lg-4 label-fr">Jabatan Fungsional :</label>
							<div class="col-lg-7">
								<select name="f4" id="f4" class="chform form-control select2"><option value="">- Semua -</option>
									@foreach($cmb['fungsional'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f4']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f5" class="col-lg-4 label-fr">Jabatan Struktural :</label>
							<div class="col-lg-7">
								<select name="f5" id="f5" class="chform form-control select2"><option value="">- Semua -</option>
									@foreach($cmb['unit_kerja_jabatan'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f5']) ? 'selected' : '' }}>{{ $mylib::tree_view($c['val'], $c['level']) }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
          <div class="col-lg-6">
						<div class="row mb-2">
							<label for="f3" class="col-lg-4 label-fr">Status :</label>
							<div class="col-lg-4">
								<select name="f3" id="f3" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['status_aktif'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f3']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer p-2 text-right">
					<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
					<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
					<div class="btn-group">
						<button type="button" class="btn btn-info"><i class="fas fa-cloud-download-alt"></i> Sinkron ke SIAKAD</button>
						<button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu" role="menu">
							<a class="dropdown-item bsync" href="#" data-for="pegawai"><i class="fas fa-user-tie"></i> Data Pegawai</a>
							<a class="dropdown-item bsync" href="#" data-for="dosen"><i class="fas fa-user-graduate"></i> Data Dosen</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card card-outline card-secondary">
				<div class="card-body p-0 table-responsive">
					<table class="table table-sm table-striped table-hover no-wrap">
						<thead class="bg-secondary">
							<tr>
								<th>#</th>
								<th>NIP</th>
								<th>Nama Pegawai</th>
								<th>Unit Kerja</th>
								<th>Jabatan <br>Struktural</th>
								<th>Jabatan <br>Fungsional</th>
								<th>Status <br>Aktif</th>
								<th width="70px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $r['nip'] }}</td>
								<td>{{ $mylib::nama_gelar($r['gelar_depan'], $r['nama'], $r['gelar_belakang']) }}</td>
								<td>{{ $mylib::tree_view($r['unit_kerja_nama'], $r['unit_kerja_level']) }}</td>
								<td>{{ $r['unit_kerja_jabatan_nama'] }}</td>
								<td>{{ $r['fungsional_nama'] }}</td>
								<td>{!! '<span class="badge badge-info">'.$r['status_aktif_pegawai_nama'].'</span>' !!}</td>
								<td>
									<a href="{{ url($ctr_path.'/detail/'.$r['id']) }}" class="bedit btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
								</td>
							</tr>
							@endforeach
							@if ($no == 0)
							<tr><td colspan="8" align="center">Tidak ada data ditemukan!</td></tr>
							@endif
						</tbody>
					</table>
				</div>
				<div class="card-footer p-2">
					<div class="row">
						<div class="col-lg-3 mb-1">
							<div class="callout callout-info m-0 py-1 px-2">{{ 'Hal. '.$tbl->currentPage().'/'.$tbl->lastPage().' ('.$tbl->total().' data)' }}</div>
						</div>
						<div class="col-lg-2 mb-1">
							<select id="ppg" name="ppg" class="chform form-control ppg">
								@foreach ($cmb['ppg'] as $c)
								<option value="{{ $c['id'] }}" {{ ($c['id'] == $var['ppg']) ? 'selected' : '' }} >{{ $c['val'] }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg mb-1">
							{{ $tbl->links() }}
						</div>
					</div>
				</div>
			</div>
		</form>
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
  $('.select2').select2();

	$('.chform').change(function(e){
		e.preventDefault();
		$('#fr0').submit();
	});

	$('.bsync').click(function(e){
		e.preventDefault();
		const request_for = $(this).data('for');
		if (request_for == 'dosen') {
			$('#mdsync-nm').html('<i class="fas fa-user-graduate"></i> Data Dosen');
		} else {
			$('#mdsync-nm').html('<i class="fas fa-user-tie"></i> Data Pegawai');
		}
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
				url:"{{ url($ctr_path.'/pull-batch') }}", type:'post', dataType:'json', data:{'_token':token, 'request_for': request_for},
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