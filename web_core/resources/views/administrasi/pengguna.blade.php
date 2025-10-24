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
							<label for="f1" class="col-lg-5 label-fr">Username / Nama Lengkap :</label>
							<div class="col-lg-6">
								<input type="text" name="f1" id="f1" class="form-control" value="{{ $var['f1'] }}">
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f2" class="col-lg-4 label-fr">Status :</label>
							<div class="col-lg-4">
								<select name="f2" id="f2" class="chform form-control"><option value="">- Semua -</option>
								@foreach ($cmb['is_aktif'] as $c)
									<option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f2']) ? 'selected' : '' }}>{{ $c['val'] }}</option>
								@endforeach	
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer p-2 text-right">
					<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
					<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
					<a href="{{ url($ctr_path).'/form' }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Tambah</a>
				</div>
			</div>
			<div class="card card-outline card-secondary">
				<div class="card-body p-0 table-responsive">
					<table class="table table-sm table-striped table-hover no-wrap">
						<thead class="bg-secondary">
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Nama Lengkap</th>
								<th>Email</th>
								<th>Aktif ?</th>
								<th>Login Terakhir</th>
								<th>IP Login Terakhir</th>
								<th>Password <br>Default ?</th>
								<th width="150px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $r['username'] }}</td>
								<td>{{ $r['nama'] }}</td>
								<td>{{ $r['email'] }}</td>
								<td>{!! $mylib::is_aktif($r['is_aktif'],'lbl') !!}</td>
								<td>{{ $r['last_login_time'] }}</td>
								<td>{{ $r['last_login_ip'] }}</td>
								<td>{!! $mylib::is_default($r['is_def_password'],'val') !!}</td>
								<td>
                  <a href="#" class="breset btn btn-xs btn-warning" data-id="{{ $r['id'] }}" data-nm="{{ $r['nama'] }}"><i class="fas fa-key"></i></a>
									<a href="{{ url($ctr_path.'/form/edit/'.$r['id']) }}" class="bdetail btn btn-xs btn-info" data-id="{{ $r['id'] }}"><i class="fas fa-edit"></i></a>
									@if ($user_ses['active_role']['is_super_admin'] == 'Y')
									<a href="#" class="breset-attempt btn btn-success btn-xs" data-id="{{ $r['username'] }}" data-nm="{{ $r['nama'] }}"><i class="fas fa-broom"></i></a>
									<a href="#" class="bloginas btn btn-secondary btn-xs" data-id="{{ $r['id'] }}" data-nm="{{ $r['nama'] }}"><i class="fas fa-address-card"></i></a>
									@endif
								</td>
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

<div class="modal fade" id="mdreset" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title font-weight-bold text-warning"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
      </div>
      <div class="modal-body">
        <p class="mb-0">Anda akan mereset password pengguna <b id="mdreset-nm"></b>, lanjutkan ?</p>
      </div>      
      <div class="modal-footer p-2 justify-content-between">
        <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
        <button class="btn btn-warning" id="mdreset-btn"><i class="fas fa-key"></i> Reset Password</button>
      </div>
    </div>
  </div>
</div>
@if ($user_ses['active_role']['is_super_admin'] == 'Y')
<div class="modal fade" id="mdreset-attempt" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title font-weight-bold text-success"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
      </div>
      <div class="modal-body">
        <p class="mb-0">Anda akan membersihkan log perobaan login untuk pengguna <b id="mdreset-attempt-nm"></b>, lanjutkan ?</p>
      </div>      
      <div class="modal-footer p-2 justify-content-between">
        <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
        <button class="btn btn-success" id="mdreset-attempt-btn"><i class="fas fa-broom"></i> Bersihkan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mdloginas" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title font-weight-bold text-secondary"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
      </div>
      <div class="modal-body">
        <p class="mb-0">Login sebagai <b id="mdloginas-nm"></b>, lanjutkan ?</p>
      </div>      
      <div class="modal-footer p-2 justify-content-between">
        <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
        <button class="btn btn-secondary" id="mdloginas-btn"><i class="fas fa-sign-in-alt"></i> Lanjutkan</button>
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
	
	@if ($user_ses['active_role']['is_super_admin'] == 'Y')
	$('.breset-attempt').click(function(e){
		e.preventDefault();
    const id = $(this).data('id');
    const nm = $(this).data('nm');
    $('#mdreset-attempt-nm').text(nm);
    $('#mdreset-attempt-btn').val(id);
    $('#mdreset-attempt').modal('show');
	});
	$('#mdreset-attempt-btn').click(function(e){
		e.preventDefault();
		const id = $(this).val();
		const bid = $(this);
		const bval = bid.html();
		$.ajax({
			url:"{{ url($ctr_path.'/clear-attempt') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
			beforeSend: function(){ bid.html(loading).attr('disabled', true); },
			success: function(d){
				toast(d.status, d.statusText);
				if (d.status == 'success') {
					$("#mdreset-attempt").modal("hide");
				} else {
					bid.html(bval).attr('disabled', false);
				}
			}, error: function(d){ bid.html(bval).attr('disabled', false); toasterr(d); }
		});
	});

	$('.bloginas').click(function(e){
		e.preventDefault();
    const id = $(this).data('id');
    const nm = $(this).data('nm');
    $('#mdloginas-nm').text(nm);
    $('#mdloginas-btn').val(id);
    $('#mdloginas').modal('show');
	});
	$('#mdloginas-btn').click(function(e){
		e.preventDefault();
		const id = $(this).val();
		const bid = $(this);
		const bval = bid.html();
		$.ajax({
			url:"{{ url($ctr_path.'/loginas') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
			beforeSend: function(){ bid.html(loading).attr('disabled', true); },
			success: function(d){
				toast(d.status, d.statusText);
				if (d.status == 'success') {
					setTimeout(() => { window.location.replace(d.toUrl); }, 500);
				} else {
					bid.html(bval).attr('disabled', false);
				}
			}, error: function(d){ bid.html(bval).attr('disabled', false); toasterr(d); }
		});
	});
	@endif

  $('.breset').click(function(e){
    e.preventDefault();
    const id = $(this).data('id');
    const nm = $(this).data('nm');
    $('#mdreset-nm').text(nm);
    $('#mdreset-btn').val(id);
    $('#mdreset').modal('show');
  });

  $('#mdreset-btn').click(function(e){
    e.preventDefault();
    const id = $(this).val();
    const bid = $(this);
    const bval = bid.html();
    $.ajax({
      url:"{{ url($ctr_path.'/reset') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
      beforeSend: function(){ bid.html(loading).attr('disabled', true); },
      success: function(d){
        toast(d.status, d.statusText);
        bid.html(bval).attr('disabled', false);
        if (d.status == 'success') {
          $('#mdreset').modal('hide');
        }
      }, error: function(d){
        toasterr(d); bid.html(bval).attr('disabled', false);
      }
    });
  });
</script>
@endsection
@include('administrasi._footer')