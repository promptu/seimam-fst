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
							<label for="f1" class="col-lg-4 label-fr">Unit :</label>
							<div class="col-lg-7">
								<input type="text" name="f1" id="f1" class="form-control" value="{{ $var['f1'] }}">
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f2" class="col-lg-4 label-fr">Status Pengajuan :</label>
							<div class="col-lg-4">
								<select name="f2" id="f2" class="chform form-control"><option value="">- Semua -</option>
									@foreach($cmb['kategori'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f2']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer p-2 text-right">
					<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
					<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
          @if ($user_ses['grant']['is_create'] == 'Y')
					<a href="{{ url($ctr_path.'/add') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Buat Pengajuan</a>              
          @endif
        	</div>
			</div>
			<div class="card card-outline card-secondary">
				<div class="card-body p-0 table-responsive">
					<table class="table table-sm table-striped table-hover no-wrap">
						<thead class="bg-secondary">
							<tr>
								<th>#</th>
                                <th>Unit</th>
								<th>Jabatan</th>
								<th>Pegawai</th>
								<th>Status Pengajuan</th>
								<th>Tanggal Ajuan</th>
								<th>Tanggal Verifikasi</th>
								<th width="110px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $r['nama_unit'] }}</td>
								<td>{{ $r['role_pengguna'] }}</td>
								<td>{{ $r['nama_pegawai'] }}</td>
								<td>{!! $r['status_pengajuan_label'] !!}</td>
								<td>{{ $r['tanggal_ajuan'] }}</td>
                                <td>{{ $r['updated_at'] }}</td>
                                    <td>
                                        @if ($r['is_edit'] == 'T')
                                        <a href="#" class="btn btn-xs btn-secondary disabled"><i class="fas fa-lock"></i></a>
                                        @else
                                        <a href="{{ url($ctr_path.'/form/edit/'.$r['id']) }}" class="btn btn-xs btn-primary" data-id="{{ $r['id'] }}"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="bdel btn btn-xs btn-danger" data-id="{{ $r['id'] }}" data-nm="{{ $r['nama'] }}"><i class="fas fa-trash-alt"></i></a>
                                        @endif
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



<div id="mddel" class="modal fade" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
      </div>
      <div class="modal-body">
        <p class="mb-0">Anda akan menghapus {{ $page_title['bread'] }} <b id="mddel-nm"></b>, lanjutkan ?</p>
        <small><i>Perhatian : Operasi ini tidak bisa dipulihkan.</i></small>
      </div>
      <div class="modal-footer justify-content-between p-2">
        <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
        <button id="mddel-btn" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
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
      'url':"{{ url($ctr_path.'/get') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
      success: function(d){
        if (d.status == 'success') {
          openform('edit', d.datalist);
        } else {
          toast(d.status, d.statusText);
        }
      }, error: function(d){ toasterr(d); bid.html(bval).attr('disabled', false); }
    });
  });

  
  $('.bdel').click(function(e){
    e.preventDefault();
    const id = $(this).data('id');
    const nm = $(this).data('nm');
    $('#mddel-nm').text(nm);
    $('#mddel-btn').val(id);
    $('#mddel').modal('show');
  });

  $('#mddel-btn').click(function(e){
    e.preventDefault();
    const id = $(this).val()
    const bid = $(this);
    const bval = bid.html();
    $.ajax({
      url:"{{ url($ctr_path.'/delete') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
      beforeSend: function(){ bid.html(loading).attr('disabled',true); },
      success: function(d){
        toast(d.status, d.statusText);
        if (d.status == 'success') {
          setTimeout(() => { window.location.reload(); }, 500);
        } else {
          bid.html(bval).attr('disabled', false);
        }
      }, error: function(d){ toasterr(d); bid.html(bval).attr('disabled', false); }
    })
  })
</script>
@endsection
@include('administrasi._footer')