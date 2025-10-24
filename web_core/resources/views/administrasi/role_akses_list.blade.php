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
							<label for="f1" class="col-lg-4 label-fr">Aplikasi :</label>
							<div class="col-lg-7">
								<select name="f1" id="f1" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['aplikasi'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f1']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f2" class="col-lg-4 label-fr">Role :</label>
							<div class="col-lg-7">
								<select name="f2" id="f2" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['role'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f2']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
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
								<th>Menu</th>
								<th width="100px">View ?</th>
								<th width="100px">Create ?</th>
								<th width="100px">Update ?</th>
								<th width="100px">Delete ?</th>
								<th width="100px">Verif 1 ?</th>
								<th width="100px">Verif 2 ?</th>
								<th width="100px">E-Sign ?</th>
							</tr>
						</thead>
						<tbody>
							@php $no = 0; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $mylib::tree_view($r['nama'], $r['level']) }}</td>
								<td>@if($r['fr_view'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'view-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="view" {{ ($r['is_view'] == 'Y') ? 'checked' : '' }}><label for="{{ 'view-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
								<td>@if($r['fr_create'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'create-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="create" {{ ($r['is_create'] == 'Y') ? 'checked' : '' }}><label for="{{ 'create-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
								<td>@if($r['fr_update'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'update-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="update" {{ ($r['is_update'] == 'Y') ? 'checked' : '' }}><label for="{{ 'update-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
								<td>@if($r['fr_delete'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'delete-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="delete" {{ ($r['is_delete'] == 'Y') ? 'checked' : '' }}><label for="{{ 'delete-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
								<td>@if($r['fr_verif_1'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'verif1-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="verif_1" {{ ($r['is_verif1'] == 'Y') ? 'checked' : '' }}><label for="{{ 'verif1-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
								<td>@if($r['fr_verif_2'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'verif2-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="verif_2" {{ ($r['is_verif2'] == 'Y') ? 'checked' : '' }}><label for="{{ 'verif2-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
								<td>@if($r['fr_sign'] == 'Y') <div class="icheck-primary"><input type="checkbox" id="{{ 'sign-'.$r['role_akses_id'] }}" class="chact" value="{{ $r['role_akses_id'] }}" data-act="sign" {{ ($r['is_sign'] == 'Y') ? 'checked' : '' }}><label for="{{ 'sign-'.$r['role_akses_id'] }}">&nbsp;</label></div> @endif</td>
							</tr>
							@endforeach
							@if ($no == 0)
							<tr><td colspan="9" align="center">Tidak ada data ditemukan!</td></tr>
							@endif
						</tbody>
					</table>
				</div>
				<div class="card-footer p-2">
					<div class="row">
						<div class="col-lg-3 mb-1">
							<div class="callout callout-info m-0 py-1 px-2">{{ 'Menampilkan. '.$no.' data' }}</div>
						</div>
					</div>
				</div>
			</div>
		</form>
  </div>
</div>

<div class="modal fade" id="mdadd" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title font-weight-bold"><i class="fas fa-server"></i> <span id="mdadd-txt">TAMBAH</span> {{ $page_title['bread'] }}</div>
			</div>
			<div class="modal-body">
				<label for="mdadd-in1" class="mb-1">Nama Role :</label>
				<input type="text" name="mdadd-in1" id="mdadd-in1" class="form-control">
			</div>
			<div class="modal-footer justify-content-between">
				<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
				<button type="button" id="mdadd-btn" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Simpan</button>
			</div>
		</div>
	</div>
</div>

@if ($var['f2'])
<div class="modal fade" id="mdedit" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title font-weight-bold"><i class="fas fa-server"></i> <span>EDIT</span> {{ $page_title['bread'] }}</div>
			</div>
			<div class="modal-body">
				<label for="mdedit-in1" class="mb-1">Nama Role :</label>
				<input type="text" name="mdedit-in1" id="mdedit-in1" class="form-control">
			</div>
			<div class="modal-footer justify-content-between">
				<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
				<button type="button" id="mdedit-btn" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Update</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="mddel" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title font-weight-bold"><i class="fas fa-server"></i> <span>HAPUS</span> {{ $page_title['bread'] }}</div>
			</div>
			<div class="modal-body">
				<p class="mb-1">Anda akan menghapus role <b id="mddel-txt"></b>, lanjutkan ?</p>
				<i>Perhatian : Operasi ini tidak bisa dipulihkan.</i>
			</div>
			<div class="modal-footer justify-content-between">
				<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
				<button type="button" id="mddel-btn" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
			</div>
		</div>
	</div>
</div>
@endif


@section('addonjs')
<script>
	$('.chform').change(function(e){
		e.preventDefault();
		$('#fr0').submit();
	});

	$('.chact').change(function(e){
		e.preventDefault();
		const curelm = $(this);
		const id = curelm.val();
		const act = curelm.data('act');
		const state = (curelm.is(':checked')) ? 'Y' : 'T';
		$.ajax({
			url:"{{ url($ctr_path.'/update') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id, 'act':act, 'state':state},
			success: function(d){
				if (d.status != 'success') { toast(d.status, d.statusText); curelm.prop('checked', (state == 'Y') ? false : true); }
			}, error: function(d){ toasterr(d); curelm.prop('checked', (state == 'Y') ? false : true); }
		});
	});
</script>
@endsection
@include('administrasi._footer')