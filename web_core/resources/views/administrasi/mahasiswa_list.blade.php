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
							<label for="f1" class="col-lg-4 label-fr">Program Studi :</label>
							<div class="col-lg-7">
								<select name="f1" id="f1" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['unit_kerja'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f1']) ? 'selected' : '' }}>{{ $mylib::tree_view($c['val'], $c['level']) }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f1f2" class="col-lg-4 label-fr">NIM / Nama Mahasiswa :</label>
							<div class="col-lg-7">
								<input type="text" name="f2" id="f2" class="form-control" value="{{ $var['f2'] }}">
							</div>
						</div>
					</div>
          <div class="col-lg-6">
						<div class="row mb-2">
							<label for="f3" class="col-lg-4 label-fr">Status :</label>
							<div class="col-lg-6">
								<select name="f3" id="f3" class="chform form-control select2"><option value="">- Semua -</option>
									@foreach($cmb['status_aktif'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f3']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer p-2 text-right">
					<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
					<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
				</div>
			</div>
			<div class="card card-outline card-secondary">
				<div class="card-body p-0 table-responsive">
					<table class="table table-sm table-striped table-hover no-wrap">
						<thead class="bg-secondary">
							<tr>
								<th>#</th>
								<th>NIM</th>
								<th>Nama Mahasiswa</th>
								<th>Program Studi</th>
								<th>Periode Awal</th>
								<th>Status Aktif</th>
								<th width="70px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $r['nim'] }}</td>
								<td>{{ $mylib::nama_gelar($r['gelar_depan'], $r['nama'], $r['gelar_belakang']) }}</td>
								<td>{{ $r['program_studi_nama'] }}</td>
								<td>{{ $r['periode_id'] }}</td>
								<td>{!! '<span class="badge badge-info">'.$r['status_mahasiswa_nama'].'</span>' !!}</td>
								<td>
									<a href="{{ url($ctr_path.'/detail/'.$r['nim']) }}" class="bedit btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
								</td>
							</tr>
							@endforeach
							@if ($no == 0)
							<tr><td colspan="7" align="center">Tidak ada data ditemukan!</td></tr>
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
</script>
@endsection
@include('administrasi._footer')