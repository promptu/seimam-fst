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
							<label for="f1f2" class="col-lg-4 label-fr">Nama Jabatan :</label>
							<div class="col-lg-7">
								<input type="text" name="f2" id="f2" class="form-control" value="{{ $var['f2'] }}">
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f3" class="col-lg-4 label-fr">Status :</label>
							<div class="col-lg-4">
								<select name="f3" id="f3" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['is_aktif'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f3']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer p-2 text-right">
					<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
					<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
					<a href="#" id="badd" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Tambah</a>
				</div>
			</div>
			<div class="card card-outline card-secondary">
				<div class="card-body p-0 table-responsive">
					<table class="table table-sm table-striped table-hover no-wrap">
						<thead class="bg-secondary">
							<tr>
								<th>#</th>
								<th>Unit Kerja</th>
								<th>Nama Jabatan</th>
								<th>Parent</th>
								<th>Urutan</th>
								<th>Aktif ?</th>
								<th width="100px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $mylib::tree_view($r['unit_kerja_nama'], $r['unit_kerja_level']) }}</td>
								<td>{{ $mylib::tree_view($r['nama'], $r['level']) }}</td>
								<td>{{ $r['parent_nama'] }}</td>
								<td>{{ $r['urutan'] }}</td>
								<td>{!! $mylib::is_aktif($r['is_aktif'],'lbl') !!}</td>
								<td>
									<a href="#" class="bedit btn btn-xs btn-info" data-id="{{ $r['id'] }}"><i class="fas fa-edit"></i></a>
									<a href="#" class="bdel btn btn-xs btn-danger" data-id="{{ $r['id'] }}" data-nm="{{ $r['nama'] }}"><i class="fas fa-trash-alt"></i></a>
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

<div class="modal fade" id="mdform" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="mdform-fr" action="#" method="post" autocomplete="off">
				<input type="hidden" name="mdform-act" id="mdform-act">
				<input type="hidden" name="mdform-in0" id="mdform-in0">
				<div class="modal-header">
					<div class="modal-title font-weight-bold text-primary"><i class="fas fa-database"></i> <span id="mdform-txt"></span> {{ $page_title['bread'] }}</div>
				</div>
				<div class="modal-body">
					<div class="row mb-2">
						<label for="mdform-in1" class="col-lg-4 label-fr">Nama Jabatan :</label>
						<div class="col-lg"><input type="text" name="mdform-in1" id="mdform-in1" class="form-control"></div>
					</div>
					<div class="row mb-2">
						<label for="mdform-in2" class="col-lg-4 label-fr">Nama Singkat :</label>
						<div class="col-lg-5"><input type="text" name="mdform-in2" id="mdform-in2" class="form-control"></div>
					</div>
					<div class="row mb-2">
						<label for="mdform-in3" class="col-lg-4 label-fr">Unit Kerja :</label>
						<div class="col-lg">
							<select name="mdform-in3" id="mdform-in3" class="form-control" data-in4="">
								<option value="">- Unit Kerja -</option>
								@foreach ($cmb['unit_kerja'] as $c) <option value="{{ $c['id'] }}">{{ $mylib::tree_view($c['val'], $c['level']) }}</option> @endforeach
							</select>
						</div>
					</div>
					<div class="row mb-2">
						<label for="mdform-in4" class="col-lg-4 label-fr">Parent Jabatan :</label>
						<div class="col-lg">
							<select name="mdform-in4" id="mdform-in4" class="form-control">
								<option value="">- Parent -</option>
							</select>
						</div>
					</div>
					<div class="row mb-2">
						<label for="mdform-in5" class="col-lg-4 label-fr">Urutan :</label>
						<div class="col-lg-3"><input type="number" name="mdform-in5" id="mdform-in5" class="form-control"></div>
					</div>
					<div class="row mb-2">
						<label for="mdform-in6" class="col-lg-4 label-fr">Level :</label>
						<div class="col-lg-3">
							<select name="mdform-in6" id="mdform-in6" class="form-control">
								@for($i = 1; $i <= 3; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
							</select>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-lg offset-lg-4">
							<div class="icheck-secondary d-inline"><input type="checkbox" id="mdform-in7"><label for="mdform-in7">Aktif ?</label></div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
					<button type="submit" id="mdform-btn" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane"></i> Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" data-backdrop="static" id="mddel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title font-weight-bold text-danger"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
			</div>
			<div class="modal-body">
				<p class="mb-1">Anda akan menghapus {{ $page_title['bread'] }} <b id="mddel-txt"></b>, <br><u>lanjutkan ?</u></p>
				<i>Perhatian : Operasi ini tidak bisa dipulihkan.</i>
			</div>
			<div class="modal-footer justify-content-between">
				<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
				<button type="button" id="mddel-btn" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
			</div>
		</div>
	</div>
</div>

@section('addonjs')
<script>
	$('.chform').change(function(e){
		e.preventDefault();
		$('#fr0').submit();
	});

	$('#badd').click(function(e){
		e.preventDefault();
		openform('add', []);
	});

	$('.bedit').click(function(e){
		e.preventDefault();
		const id = $(this).data('id');
		$.ajax({
			url:"{{ url($ctr_path.'/get') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
			success: function(d){
				if (d.status == 'success') {
					openform('edit', d.datalist);
				} else {
					toast(d.status, d.statusText);
				}
			}, error: function(d){ toasterr(d); }
		});
	});

	function openform(act, datas){
		if (act == 'edit') {
			$('#mdform-txt').html('EDIT');
			$('#mdform-act').val('update');
			$('#mdform-in0').val(datas[0]);
			$('#mdform-in1').val(datas[1]);
			$('#mdform-in2').val(datas[2]);
			$('#mdform-in3').val(datas[3]).data('in4', datas[4]).trigger('change');
			$('#mdform-in5').val(datas[5]);
			$('#mdform-in6').val(datas[6]);
			$('#mdform-in7').prop('checked', (datas[7] == 'Y') ? true : false);
		} else {
			$('#mdform-txt').html('TAMBAH');
			$('#mdform-act').val('save');
			$('#mdform-in0').val('');
			$('#mdform-in1').val('');
			$('#mdform-in2').val('');
			$('#mdform-in3').val('').data('in4', '').trigger('change');
			$('#mdform-in5').val('');
			$('#mdform-in6').val('');
			$('#mdform-in7').prop('checked', true);
		}
		$('#mdform').modal('show').on('shown.bs.modal', function(){
			$('#mdform-in1').focus();
		});
	}

	$('#mdform-in3').change(function(e){
		e.preventDefault();
		const id = $(this).val();
		const sel_id = $(this).data('in4');
		const tgt_elm = $('#mdform-in4');
		tgt_elm.find('option').not(':first').remove();
		if (id != '') {
			$.ajax({
				url:"{{ url($ctr_path.'/cmb-jabatan') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
				success: function(d){
					if (d.status == 'success') {
						$.each(d.datalist, function(key, val){
							tgt_elm.append('<option value="'+val['id']+'">'+val['val']+'</option>');
						});
						tgt_elm.val(sel_id).trigger('change');
					} else {
						toast(d.status, d.statusText);
					}
				}, error: function(d){ toasterr(d); }
			});
		}
	});

	$('#mdform-fr').submit(function(e){
		e.preventDefault();
		const act = $('#mdform-act').val();
		const datas = {'_token':token, 'act':act, 'in0':$('#mdform-in0').val(), 'in1':$('#mdform-in1').val(), 'in2':$('#mdform-in2').val(), 'in3':$('#mdform-in3').val(), 'in4':$('#mdform-in4').val(), 'in5':$('#mdform-in5').val(), 'in6':$('#mdform-in6').val(), 'in7':($('#mdform-in7').is(':checked')) ? 'Y' : 'T',
		};
		const bid = $('#mdform-btn');
		const bval = bid.html();
		$.ajax({
			url:"{{ url($ctr_path) }}" + '/' + act, type:'post', dataType:'json', data:datas,
			beforeSend: function(){ bid.html(loading).attr('disabled', true); },
			success: function(d){
				toast(d.status, d.statusText);
				if (d.status == 'success') {
					setTimeout(() => {
						window.location.reload();
					}, 500);
				} else {
					bid.html(bval).attr('disabled', false);
				}
			}, error: function(d){ toasterr(d); bid.html(bval).attr('disabled', false); } 
		});
	});

	$('.bdel').click(function(e){
		e.preventDefault();
		const id = $(this).data('id');
		const nm = $(this).data('nm');
		$('#mddel-txt').html(nm);
		$('#mddel-btn').val(id);
		$('#mddel').modal('show');
	});

	$('#mddel-btn').click(function(e){
		e.preventDefault();
		const id = $(this).val();
		const bid = $(this);
		const bval = bid.html();
		$.ajax({
			url:"{{ url($ctr_path.'/delete') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
			beforeSend: function(){ bid.html(loading).attr('disabled', true); },
			success: function(d){
				toast(d.status, d.statusText);
				if (d.status == 'success') {
					setTimeout(() => {
						window.location.reload();
					}, 500);
				} else {
					bid.html(bval).attr('disabled', false);
				}
			}, error: function(d){ toasterr(d); bid.html(bval).attr('disabled', false); }
		});
	});

</script>
@endsection
@include('administrasi._footer')