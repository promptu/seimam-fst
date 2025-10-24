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
						@if ($state == 'edit')
							@if ($user_ses['grant']['is_update'] == 'Y')
								@if (in_array($get['ta_status_pengajuan_kode'], ['pengajuan','disetujui']))
									<a href="{{ url($ctr_path.'/form/detail/'.$id_page) }}" class="btn btn-warning"><i class="fas fa-sync"></i> Batal</a>
									<button type="submit" id="fr0-btn" class="btn btn-info"><i class="fas fa-edit"></i> Update</button>	
								@endif
							@endif
							@if (($user_ses['grant']['is_verif_1'] == 'Y' || $user_ses['active_role']['is_admin'] == 'Y') && $get['ta_status_pengajuan_kode'] == 'pengajuan')
								<a href="#" id="btolak" class="btn btn-danger ml-3"><i class="fas fa-times"></i> Tolak</a>
								<a href="#" id="bacc" class="btn btn-success"><i class="fas fa-check"></i> Setujui</a>
							@endif
						@elseif ($state == 'add')
						<button type="submit" id="fr0-btn" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Simpan</button>
						@else
							@if ($user_ses['grant']['is_update'] == 'Y')
								@if (in_array($get['ta_status_pengajuan_kode'], ['pengajuan','disetujui']))
									@if(($is_mahasiswa && $get['ta_status_pengajuan_kode'] == 'pengajuan') || !$is_mahasiswa)
									<a href="{{ url($ctr_path.'/form/edit/'.$id_page) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
									@endif
								@endif
							@endif
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				@include('ta.data.menu')
			</div>
			<div class="col-sm">
				<div class="card card-outline card-secondary">
					<div class="card-body">								
						@if ($state == 'detail')					
							@if ($get['ta_status_pengajuan_kode'] == 'ditolak')
							<div class="callout callout-danger bg-danger">
								<b>Maaf, pengajuan proposal ini ditolak. Keterangan : </b><br>{{ $get['ta_status_pengajuan_ket'] }}	
							</div>				
							@endif
						@endif
						@if ($state == 'edit' || $state == 'add')
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
											<input type="date" name="in2" id="in2" class="form-control" value="{{ $fr['in2'] }}" disabled>
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
						@else
							<div class="row">
								<div class="col-lg-6">            
									<div class="row mb-2"><h6><u>Data Mahasiswa :</u></h6></div>
									<div class="row mb-4">
										<label for="in1" class="col-lg-4 label-fr">Mahasiswa :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in1nm'] }}</div>
										</div>
									</div>
									<div class="row mb-2"><h6><u>Detail Proposal :</u></h6></div>
									<div class="row mb-2">
										<label for="in2" class="col-lg-4 label-fr">Tanggal Pengajuan :</label>
										<div class="col-lg-5">
											<div class="box-fr">{{ $fr['in2'] }}</div>
										</div>
									</div>
									<div class="row mb-2">
										<label for="in3" class="col-lg-4 label-fr">Topik :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in3'] }}</div>
										</div>
									</div>
									<div class="row mb-2">
										<label for="in3en" class="col-lg-4 label-fr">Topik (En.) :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in3en'] }}</div>
										</div>
									</div>
									<div class="row mb-4">
										<label for="in3ar" class="col-lg-4 label-fr">Topik (Ar.) :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in3ar'] }}</div>
										</div>
									</div>
									<div class="row mb-2">
										<label for="in4" class="col-lg-4 label-fr">Judul :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in4'] }}</div>
										</div>
									</div>
									<div class="row mb-2">
										<label for="in4en" class="col-lg-4 label-fr">Judul (En.) :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in4en'] }}</div>
										</div>
									</div>
									<div class="row mb-4">
										<label for="in4ar" class="col-lg-4 label-fr">Judul (Ar.) :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $fr['in4ar'] }}</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row mb-2"><h6><u>Status Pengajuan :</u></h6></div>
									<div class="row mb-4">
										<label for="in5" class="col-lg-4 label-fr">Status Pengajuan :</label>
										<div class="col-lg-7">
											<div class="box-fr">{{ $get['ta_status_pengajuan_nama'] }}</div>
										</div>
									</div>
									<div class="row mb-2">
										<label for="in6" class="col-lg-12 mb-1">Abstrak :</label>
										<div class="col-lg-11">
											<div class="box-fr" style="height: 280px">{{ $fr['in6'] }}</div>
										</div>
									</div>
								</div>
							</div>		
						@endif
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
							<div class="callout callout-success text-bold mb-1 p-2 row">
								<div class="col-lg-6 pt-1 mb-0"><i class="fas fa-user"></i> {{ $cur_pembimbing }}</div>
								<div class="col-lg text-right mb-0">									
									@if ($state == 'edit' && $user_ses['active_role']['is_admin'] == 'Y' && $get['ta_status_pengajuan_kode'] == 'disetujui')
										<a href="#" class="bdel btn btn-xs btn-danger text-white ml-1" data-id="{{ $r['id'] }}" data-nm="{{ $cur_pembimbing }}"><i class="fas fa-trash-alt"></i></a>
									@endif
									<a href="{{ url($ctr_path.'/bimbingan/'.$id_page) }}" class="bhistory btn btn-xs btn-primary text-white" data-id="{{ $r['id'] }}"><i class="fas fa-history"></i> Riwayat Bimbingan</a>
								</div>
							</div>
							@php ++$no_pembimbing
						@endphp 
						@endforeach
						@if ($no_pembimbing == 0)						
							<div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i> Belum memiliki dosen pembimbing.</div>
						@endif
						@if ($state == 'edit')						
							@if (($user_ses['grant']['is_verif_1'] == 'Y' || $user_ses['active_role']['is_admin'] == 'Y') && $get['ta_status_pengajuan_kode'] == 'disetujui')
								@if ($no_pembimbing < 2)
									<a href="#" class="btn btn-primary" id="badd-dsn"><i class="fas fa-user-edit"></i> Tambah Pembimbing</a>
								@endif
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>

  </div>
</div>

@if ($state == 'edit')
	@if (($user_ses['grant']['is_verif_1'] == 'Y' || $user_ses['active_role']['is_admin'] == 'Y') && $get['ta_status_pengajuan_kode'] == 'pengajuan')
		<div class="modal fade" id="mdtolak" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title font-weight-bold text-danger"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
					</div>
					<div class="modal-body">
						<label for="mdtolak-in1" class="mb-0">Keterangan :</label>
						<textarea name="mdtolak-in1" id="mdtolak-in1" class="form-control form-control-sm"></textarea>
						<small><i>Perhatian : Silahkan isi alasan penolakan.</i></small>
					</div>
					<div class="modal-footer p-2 justify-content-between">
						<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
						<button class="btn btn-danger" id="mdtolak-btn"><i class="fas fa-times"></i> Tolak Pengajuan</button>
					</div>
				</div>
			</div>
		</div>  
		<div class="modal fade" id="mdacc" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title font-weight-bold text-success"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
					</div>
					<div class="modal-body">
						<p>Setujui Pengajuan Proposal</p>
						<p><b>"{{ $get['judul'] }}"</b></p>
						<small><i>Perhatian : Silahkan periksa lagi detail proposal sebelum melanjutkan.</i></small>
					</div>
					<div class="modal-footer p-2 justify-content-between">
						<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
						<button class="btn btn-success" id="mdacc-btn"><i class="fas fa-check"></i> Setujui Pengajuan</button>
					</div>
				</div>
			</div>
		</div>      
	@endif		
	@if (($user_ses['grant']['is_verif_1'] == 'Y' || $user_ses['active_role']['is_admin'] == 'Y') && $get['ta_status_pengajuan_kode'] == 'disetujui')
		<div class="modal fade" id="mdadd" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title font-weight-bold text-success"><i class="fas fa-user-graduate"></i> Dosen Pembimbing</div>
					</div>
					<div class="modal-body">
						<label for="mdadd-in1" class="mb-0">Pilih Dosen Pembimbing :</label>
						<select name="mdadd-in1" id="mdadd-in1" class="form-control select2" style="width: 100%"></select>
						<label for="mdadd-in2" class="mt-3 mb-0">Pembimbing ke :</label>
						<input type="number" name="mdadd-in2" id="mdadd-in2" class="form-control" min="1" max="2">
					</div>
					<div class="modal-footer p-2 justify-content-between">
						<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
						<button class="btn btn-success" id="mdadd-btn"><i class="fas fa-plus"></i> Tambahkan Pembimbing</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="mddel" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title font-weight-bold text-danger"><i class="fas fa-user-graduate"></i> Hapus Dosen Pembimbing</div>
					</div>
					<div class="modal-body">
						<p class="mb-0">Yakin hapus pembimbing</p>
						<p><b id="mddel-nm">""</b><br>lanjutkan ?</p>
						<small><i>Peringatan : Operasi ini tidak bisa dipulihkan.</i></small>
					</div>
					<div class="modal-footer p-2 justify-content-between">
						<a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
						<button class="btn btn-danger" id="mddel-btn"><i class="fas fa-trash-alt"></i> Hapus</button>
					</div>
				</div>
			</div>
		</div>
	@endif
@endif


@section('addonjs')
<script>

	$('textarea').keypress(function(e){ if (e.keyCode == 13) { e.preventDefault(); } });

	@if ($state == 'edit')
		@if (($user_ses['grant']['is_verif_1'] == 'Y' || $user_ses['active_role']['is_admin'] == 'Y') && $get['ta_status_pengajuan_kode'] == 'pengajuan')
			$('#btolak').click(function(e){
				e.preventDefault();
				$('#mdtolak-in1').val('');
				$('#mdtolak').modal('show');
			});
			$('#mdtolak-btn').click(function(e){
				e.preventDefault();
				const bid = $(this);
				const bval = bid.html();
				const id = "{{ $fr['in0'] }}";
				const ket = $('#mdtolak-in1').val();
				$.ajax({
					url:"{{ url($ctr_path.'/tolak') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id, 'ket':ket},
					beforeSend: function(){ bid.html(loading).attr('disabled',true); },
					success: function(d){
						toast(d.status, d.statusText);
						if (d.status == 'success') {
							setTimeout(() => { window.location.replace(d.directto); }, 500);
						} else {
							bid.html(bval).attr('disabled', false);
						}
					}, error: function(d){ bid.html(bval).attr('disabled', false); toasterr(d); }
				});
			});

			$('#bacc').click(function(e){
				e.preventDefault();
				$('#mdacc').modal('show');
			});
			$('#mdacc-btn').click(function(e){
				e.preventDefault();
				const bid = $(this);
				const bval = bid.html();
				const id = "{{ $fr['in0'] }}";
				$.ajax({
					url:"{{ url($ctr_path.'/acc') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
					beforeSend: function(){ bid.html(loading).attr('disabled',true); },
					success: function(d){
						toast(d.status, d.statusText);
						if (d.status == 'success') {
							setTimeout(() => { window.location.replace(d.directto); }, 500);
						} else {
							bid.html(bval).attr('disabled', false);
						}
					}, error: function(d){ bid.html(bval).attr('disabled', false); toasterr(d); }
				});
			});
		@endif
		@if (($user_ses['grant']['is_verif_1'] == 'Y' || $user_ses['active_role']['is_admin'] == 'Y') && $get['ta_status_pengajuan_kode'] == 'disetujui')	
			$('#mdadd-in1').select2({
				ajax:{
					url:"{{ url($ctr_path.'/cmb-dosen') }}",
					dataType:'json',
					delay: 1000,
					data: function(params){ return { q:params.term } },
					processResult: function(data){ return { results: data.items }; }	
				},		
				minimumInputLength: 3,
				placeholder: 'Cari Dosen',
				allowClear: true
			});

			$('#badd-dsn').click(function(e){
				e.preventDefault();
				$('#mdadd-in1').val('').trigger('change');
				$('#mdadd-in2').val('');
				$('#mdadd').modal('show');
			});

			$('#mdadd-btn').click(function(e){
				e.preventDefault();
				const id = "{{ $fr['in0'] }}";
				const dosen = $('#mdadd-in1').val();
				const dosen_ke = $('#mdadd-in2').val();
				const bid = $(this);
				const bval = bid.html();
				$.ajax({
					url:"{{ url($ctr_path.'/add-pembimbing') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id, 'dosen':dosen, 'dosen_ke':dosen_ke},
					beforeSend: function(){ bid.html(loading).attr('disabled', true); },
					success: function(d){
						toast(d.status, d.statusText);
						if (d.status == 'success') {
							setTimeout(() => { window.location.reload(); }, 500);
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
				$('#mddel-nm').html(nm);
				$('#mddel-btn').val(id);
				$('#mddel').modal('show');
				// alert(id);
			});

			$('#mddel-btn').click(function(e){
				e.preventDefault();
				const id = $(this).val();
				const bid = $(this);
				const bval = bid.html();
				$.ajax({
					url:"{{ url($ctr_path.'/delete-pembimbing') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
					beforeSend: function(){ bid.html(loading).attr('disabled', true); },
					success: function(d){
						toast(d.status, d.statusText);
						if (d.status == 'success') {
							setTimeout(() => { window.location.reload(); }, 500);
						} else {
							bid.html(bval).attr('disabled', false);
						}
					}, error: function(d){ toasterr(d); bid.html(bval).attr('disabled', false); }
				});
			});
		@endif

	@endif
		

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
