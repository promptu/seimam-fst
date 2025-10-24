@section('addon_header')
<link rel="stylesheet" href="{{ url('assets/bo/plugins/summernote/summernote-bs4.min.css')}} ">
@endsection
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
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				@include('ta.proposal_bimbingan_menu_bydosen')
			</div>
			<div class="col-sm">
				<div class="card card-outline card-warning">
					<div class="card-body pb-2 row">
						<div class="col-lg-12">
							<div class="alert alert-warning row">
								<div class="col-lg-6">
									<div class="row mb-2">
										<label class="col-lg-4 label-fr-dark">NIM :</label>
										<div class="col-lg">{{ $get['mahasiswa_nim'] }}</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row mb-2">
										<label class="col-lg-4 label-fr-dark">Nama Mahasiswa :</label>
										<div class="col-lg">{{ $mylib::nama_gelar($get['mahasiswa_gelar_depan'], $get['mahasiswa_nama'], $get['mahasiswa_gelar_belakang']) }}</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row mb-2">
										<label class="col-lg-4 label-fr-dark">Program Studi :</label>
										<div class="col-lg">{{ $get['prodi_nama'] }}</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row mb-2">
										<label class="col-lg-4 label-fr-dark">Jenis TA. :</label>
										<div class="col-lg">{{ $get['ta_jenis_nama'] }}</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row mb-2">
										<label class="col-lg-4 label-fr-dark">SKS Lulus :</label>
										<div class="col-lg">{{ $get['mahasiswa_sks_lulus'] }}</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="row mb-2">
										<label class="col-lg-4 label-fr-dark">Tanggal Pengajuan :</label>
										<div class="col-lg">{{ $mylib::indotgl($get['tanggal']) }}</div>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="row mb-2">
										<label class="col-lg-2 	 label-fr-dark">Judul :</label>
										<div class="col-lg">{{ $get['judul'] }}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body row pb-0">
						<div class="col-lg-6">
							<div class="row mb-2">
								<label class="col-lg-4 label-fr">Bimbingan Ke :</label>
								<div class="col-lg-3"><div class="box-fr">{{ $bimbingan['bimbingan_ke'] }}</div></div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row mb-2">
								<label class="col-lg-4 label-fr">Pembimbing :</label>
								<div class="col-lg"><div class="box-fr">{{ $mylib::nama_gelar($bimbingan['peg_gelar_depan'], $bimbingan['peg_nama'], $bimbingan['peg_gelar_belakang']) }}</div></div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row mb-2">
								<label class="col-lg-4 label-fr">Tanggal Bimbingan :</label>
								<div class="col-lg">
									<div class="input-group">
										<div class="box-fr">{{ $mylib::switch_tgl($bimbingan['tgl_bimbingan'], 'short') }}</div>
										<span class="input-group-append">
											<div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row mb-2">
								<label class="col-lg-2 label-fr">Topik :</label>
								<div class="col-lg"><div class="box-fr">{{ $bimbingan['topik'] }}</div></div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row mb-2">
								<label class="col-lg-2 label-fr">Bahasan :</label>
								<div class="col-lg"><div class="box-fr">{!! $bimbingan['bahasan'] !!}</div></div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row mb-0">
								<label class="col-lg-2" style="padding-left: 3px">Lampiran :</label>
								<div class="col-lg">
									<iframe type="application/pdf" id="input-file-preview" src="{{ ($bimbingan['lampiran']) ? url($bimbingan['lampiran']) : "" }}" width="100%" height="400" class="mt-1" style="border: solid 1px #ccc; border-radius: 5px; margin-bottom: 0px;"></iframe>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body row">
						<div class="col-lg-12 pl-0"><h4 class="ml-0 mb-3 border-bottom mt-3">Respon Pembimbing</h4></div>
						@if ($bimbingan['status_bimbingan'] == 'aktif')
						<div class="col-lg-12">
							<div class="row mb-2">
								<label class="col-lg-2 label-fr">Disetujui/Tolak :</label>
								<div class="col-lg-4">
									<select name="in1" id="in1" class="form-control"><option value="">- Pilih -</option>
									@foreach ($cmb['status_disetujui'] as $c)
										<option value="{{ $c['id'] }}">{{ $c['val'] }}</option>
									@endforeach
									</select>
								</div>
							</div>
							<div class="row mb-2">
								<label class="col-lg-2 pl-1">Komentar :</label>
								<div class="col-lg">
									<div class="col-lg px-0"><textarea name="in2" id="in2" cols="30" rows="10" class="m-0"></textarea></div>
								</div>
							</div>
							<div class="text-right">
								<button id="bsubmit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Simpan</button>
							</div>
						</div>						
						@else
						<div class="col-lg-12">
							<div class="row mb-2">
								<label class="col-lg-2 label-fr">Disetujui/Tolak :</label>
								<div class="col-lg">{!! $mylib::status_disetujui($bimbingan['status_disetujui']) !!}</div>
							</div>
						</div>
						<div class="col-lg-12 mb-3">
							<div class="row mb-2">
								<label class="col-lg-2 label-fr">Catatan Pembimbing :</label>
								<div class="col-lg"><div class="box-fr">{!! $bimbingan['catatan_pembimbing'] !!}</div></div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
  </div>
</div>

@section('addonjs')
<script>

	$('#in2').summernote({
		height: 120,
    toolbar: [
        // Specify only the buttons you need, excluding image upload
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']]
    ],
    callbacks: {
			onInit: function(){
				$('.note-editor').css('margin-bottom', '0px');
			},
			onImageUpload: function(files) {
				if (files && files.length > 0) {
					toast('info', 'Maaf,<br>Tidak bisa melampirkan gambar.');
				}
			},
			onPaste: function(e) {
				const clipboardData = (e.originalEvent || e).clipboardData;
				if (clipboardData && clipboardData.items) {
					for (let i = 0; i < clipboardData.items.length; i++) {
						if (clipboardData.items[i].type.indexOf('image') !== -1) {
							toast('info', 'Maaf,<br>Tidak bisa melampirkan gambar.');
							return;
						}
					}
				}
			}
		}
	});
	
	@if ($bimbingan['status_bimbingan'] == 'aktif')
	$('#bsubmit').click(function(e){
		e.preventDefault();
		const datas = { '_token':token,'id':"{{ $bimbingan['id'] }}", 'in1': $('#in1').val(), 'in2': $('#in2').val(), };
		const bid = $(this);
		const bval = bid.html();
		$.ajax({
			url:"{{ url($ctr_path.'/save') }}", type:'post', dataType:'json', data:datas, 
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

</script>
@endsection
@section('addon_footer')
<script src="{{ url('assets/bo/plugins/summernote/summernote-bs4.min.js')}} "></script>	
@endsection
@include('administrasi._footer')
