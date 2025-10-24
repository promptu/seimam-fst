@include('administrasi._header')
@php
	$is_mahasiswa = ($user_ses['active_role']['id'] == '3') ? true : false
@endphp
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-outline card-warning">
					<form action="{{ url($ctr_path) }}" id="fr0" method="post">@csrf <input type="hidden" name="filter" id="filter" value="filter">
						<div class="card-body row">
							<div class="col-lg-6">
								<div class="row">
									<label for="f1" class="col-lg-4">Mahasiswa Bimbingan :</label>
									<div class="col-lg-7">
											<select name="f1" id="f1" class="form-control select2 chform"><option value="">- Pilih Mahasiswa -</option>
												@foreach ($cmb['mahasiswa_bimbingan'] as $c)\
												<option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f1']) ? 'selected' : '' }}>{{ $mylib::nama_gelar($c['gelar_depan'], $c['val'], $c['gelar_belakang']) }}</option>
												@endforeach
											</select>
									</div>
								</div>
							</div>
							<div class="col-lg 6">
								<div class="row">
									<label for="f2" class="col-lg-4">Status Bimbingan :</label>
									<div class="col-lg-4">
											<select name="f2" id="f2" class="form-control chform"><option value="">- Semua -</option>
												@foreach ($cmb['status_bimbingan'] as $c)\
												<option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f2']) ? 'selected' : '' }}>{{ $c['val'] }}</option>
												@endforeach
											</select>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="card-footer p-2 text-right">
						<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i> Reset</a>
					</div>
				</div>
			</div>
			<div class="col-sm">
				<div class="card card-outline card-secondary">
					<div class="card-body p-0 table-reponsive">
						<table class="table table-hover table-striped table-sm">
							<thead class="bg-secondary">
								<tr>
									<td>#</td>
									<td>Mahasiswa</td>
									<td>Tanggal Bimbingan</td>
									<td>Topik</td>
									<td>Disetujui/ <br>Tolak</td>
									<td>Status</td>
									<td width="90px"><i class="fas fa-cogs"></i></td>
								</tr>
							</thead>
							<tbody>
								@php $no = 0; @endphp
								@foreach ($tbl as $r)
								<tr>
									<td>{{ ++$no }}</td>
									<td>{!! $mylib::nama_gelar($r['mahasiswa_gelar_depan'], $r['mahasiswa_nama'], $r['mahasiswa_gelar_belakang']).'<br><span class="badge badge-success">Pembimbing '.$r['pembimbing_ke'].'</span>' !!}</td>
									<td>{!! 'Bimbingan Ke.'.$r['bimbingan_ke'].'<br>'.$mylib::indotgl($r['tgl_bimbingan']) !!}</td>
									<td>{{ $r['topik'] }}</td>
									<td>{!! $mylib::status_disetujui($r['status_disetujui']) !!}</td>
									<td>{{ $r['status_bimbingan'] }}</td>
									<td>
										@php $encid = $crypt::encryptString($r['id']) @endphp
										<a href="{{ url($ctr_path.'/detail/'.$encid) }}" class="btn btn-xs btn-primary"><i class="fas fa-eye"></i> Detail</a>
									</td>
								</tr>
								@endforeach
								@if ($no == 0)
								<tr><td colspan="7" align="center"><b>Silahkan pilih mahasiswa bimbingan.</b></td></tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>	
			</div>
		</div>
  </div>
</div>

@section('addonjs')
<script>
	$('.select2').select2();
	$('.chform').change(function(e){ e.preventDefault(); $('#fr0').submit(); });
</script>
@endsection
@include('administrasi._footer')
