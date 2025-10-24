@include('administrasi._header')
@php
	$is_mahasiswa = ($user_ses['active_role']['id'] == '3') ? true : false
@endphp
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-outline card-warning">
					<div class="card-footer p-2 text-right">
						<a href="{{ url($ctr_path) }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
						<a href="{{ url($ctr_path.'/form/detail/'.$id_page) }}" class="btn btn-warning"><i class="fas fa-sync"></i> Batal</a>
						<button type="submit" id="fr0-btn" class="btn btn-info"><i class="fas fa-edit"></i> Update</button>	
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				@include('ta.proposal_menu')
			</div>
			<div class="col-sm">
				<div class="card card-outline card-secondary">
					<div class="card-body">
						<input type="hidden" name="in0" id="in0" class="form-control" value="{{ $fr['in0'] }}">
						<div class="row">
							<div class="col-lg-6">            
								<div class="row mb-2"><h6><u>Data Mahasiswa :</u></h6></div>
								<div class="row mb-4">
									<label for="in1" class="col-lg-4 label-fr">Mahasiswa :</label>
									<div class="col-lg-7">
										<select name="in1" id="in1" class="form-control select2" style="width: 100%" {{ ($is_mahasiswa) ? 'disabled' : '' }}>
											@if ($fr['in1'] != '') <option value="{{ $fr['in1'] }}" selected>{{ $fr['in1nm'] }}</option> @endif
										</select>
									</div>
								</div>
								<div class="row mb-2"><h6><u>Detail Proposal :</u></h6></div>
								<div class="row mb-2">
									<label for="in2" class="col-lg-4 label-fr">Tanggal Pengajuan :</label>
									<div class="col-lg-5">
										<input type="text" name="in2" id="in2" class="form-control" value="{{ $fr['in2'] }}" disabled>
									</div>
								</div>
								<div class="row mb-2">
									<label for="in3" class="col-lg-4 label-fr">Topik :</label>
									<div class="col-lg-7">
										<input type="text" name="in3" id="in3" class="form-control" value="{{ $fr['in3'] }}">
									</div>
								</div>
								<div class="row mb-2">
									<label for="in3en" class="col-lg-4 label-fr">Topik (En.) :</label>
									<div class="col-lg-7">
										<input type="text" name="in3en" id="in3en" class="form-control" value="{{ $fr['in3en'] }}">
									</div>
								</div>
								<div class="row mb-4">
									<label for="in3ar" class="col-lg-4 label-fr">Topik (Ar.) :</label>
									<div class="col-lg-7">
										<input type="text" name="in3ar" id="in3ar" class="form-control" value="{{ $fr['in3ar'] }}">
									</div>
								</div>
								<div class="row mb-2">
									<label for="in4" class="col-lg-4 label-fr">Judul :</label>
									<div class="col-lg-7">
										<textarea name="in4" id="in4" rows="2" class="form-control">{{ $fr['in4'] }}</textarea>
									</div>
								</div>
								<div class="row mb-2">
									<label for="in4en" class="col-lg-4 label-fr">Judul (En.) :</label>
									<div class="col-lg-7">
										<textarea name="in4en" id="in4en" rows="2" class="form-control">{{ $fr['in4en'] }}</textarea>
									</div>
								</div>
								<div class="row mb-4">
									<label for="in4ar" class="col-lg-4 label-fr">Judul (Ar.) :</label>
									<div class="col-lg-7">
										<textarea name="in4ar" id="in4ar" rows="2" class="form-control">{{ $fr['in4ar'] }}</textarea>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="row mb-2"><h6><u>Status Pengajuan :</u></h6></div>
								<div class="row mb-4">
									<label for="in5" class="col-lg-4 label-fr">Status Pengajuan :</label>
									<div class="col-lg-7">
										<select name="in5" id="in5" class="form-control" disabled>
											@foreach ($cmb['status_pengajuan'] as $c)
											<option value="{{ $c['id'] }}" {{ ($c['id'] == $fr['in5']) ? 'selected' : '' }}>{{ $c['val'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row mb-2">
									<label for="in6" class="col-lg-12 mb-1">Abstrak :</label>
									<div class="col-lg-11">
										<textarea name="in6" id="in6" cols="30" rows="15" class="form-control">{{ $fr['in6'] }}</textarea>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</div>
				<div class="card card-outline card-success">
					<div class="card-body">
						<div class="row mb-2"><div class="col-lg"><h6><u>Dosen Pembimbing :</u></h6></div></div>
						@php $no_pembimbing = 0 @endphp
						@foreach ($pembimbing as $r) 
							@php 
								$cur_pembimbing = $r['pegawai_nip'].' - '.$mylib::nama_gelar($r['pegawai_gelar_depan'],$r['pegawai_nama'], $r['pegawai_gelar_belakang']).' (Pembimbing - '.$r['pembimbing_ke'].')'
							@endphp
							<div class="callout callout-success text-bold m-1 p-2 row">
								<div class="col-lg-8 pt-1 mb-0"><i class="fas fa-user"></i> {{ $cur_pembimbing }}</div>
								<div class="col-lg text-right mb-0">									
									@if ($state == 'edit' && $user_ses['active_role']['is_admin'] == 'Y' && $get['ta_status_pengajuan_kode'] == 'pengajuan')
										<a href="#" class="bdel btn btn-xs btn-danger text-white ml-1" data-id="{{ $r['id'] }}" data-nm="{{ $cur_pembimbing }}"><i class="fas fa-trash-alt"></i></a>
									@endif
									@if($get['ta_status_pengajuan_kode' == 'disetujui'])
										<a href="{{ url($ctr_path.'/bimbingan/'.$id_page) }}" class="btn btn-xs btn-primary text-white" data-id="{{ $r['id'] }}"><i class="fas fa-history"></i> Riwayat Bimbingan</a>
									@endif
								</div>
							</div>
							@php ++$no_pembimbing
						@endphp 
						@endforeach
						@if ($no_pembimbing == 0)
							@if ($state == 'add')
							<div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i> Silakan lengkapi detail proposal terlebih dahulu, kemudian klik tombol Simpan untuk menambahkan dosen pembimbing.</div>
							@else
							<div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i> Belum memiliki dosen pembimbing.</div>
							@endif	
						@endif
						@if ($state == 'edit' && $get['ta_status_pengajuan_kode'] == 'pengajuan' && $no_pembimbing <= 4)
							<a href="#" class="btn btn-primary mt-2" id="badd-dsn"><i class="fas fa-user-edit"></i> Tambah Pembimbing</a>
						@endif
					</div>
				</div>
			</div>
		</div>
  </div>
</div>


@section('addonjs')
<script>

	$('textarea').keypress(function(e){ if (e.keyCode == 13) { e.preventDefault(); } });

  $('#in4t').change(function(e){
    e.preventDefault();
    if ($(this).is(':checked') === true) {
      $('#in4').val('').attr('disabled',true);
    } else {
      $('#in4').attr('disabled',false);
    }
  });

	$('#in1').select2({
		ajax:{
			url:"{{ url($ctr_path.'/cmb-mahasiswa') }}",
			dataType:'json',
			delay: 1000,
			data: function(params){ return { q:params.term } },
			processResult: function(data){ return { results: data.items }; }	
		},		
		minimumInputLength: 3,
		placeholder: 'Cari NIM / Nama Mahasiswa',
    allowClear: true
	});

	$('#fr0-btn').click(function(e){
		e.preventDefault();
		const in6 = $('#in6').val();
    const datas = {'_token':token, 'act':"{{ $fr['path'] }}", 'in0':"{{ $fr['in0'] }}", 'in1':$('#in1').val(), 'in2':$('#in2').val(), 'in3':$('#in3').val(), 'in3en':$('#in3en').val(), 'in3ar':$('#in3ar').val(), 'in4':$('#in4').val(), 'in4en':$('#in4en').val(), 'in4ar':$('#in4ar').val(), 'in5':$('#in5').val(), 'in6':$('#in6').val()};
		const bid = $(this);
    const bval = bid.html();
    $.ajax({
      url:"{{ url($ctr_path.'/'.$fr['path']) }}", type:'post', dataType:'json', data:datas,
      beforeSend: function(){ bid.html(loading).attr('disabled',true); },
      success: function(d){
        toast(d.status, d.statusText);
        if (d.status == 'success') {
          setTimeout(() => {
            window.location.replace(d.directto);
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
