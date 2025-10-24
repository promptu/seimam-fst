@include('administrasi._header')
<div class="content">
	<div class="container">
    <div class="row">
      <div class="col-lg-4">        
        <div class="card card-outline card-warning">
          <div class="card-body pb-2">
            <div class="row mb-2">
              <label class="col-lg-12 label-fr">Data Mahasiswa :</label>
              <div class="col-lg-7">{!! $pengajuan['mahasiswa_nim'].' - '.$mylib::nama_gelar($pengajuan['mahasiswa_gelar_depan'], $pengajuan['mahasiswa_nama'], $pengajuan['mahasiswa_gelar_belakang']) !!}</div>
            </div>
            <div class="row mb-2">
              <label class="col-lg-12 label-fr">Jenis Surat :</label>
              <div class="col-lg-7">{{ $pengajuan['bak_template_nama'] }}</div>
            </div>
            <div class="row mb-2">
              <label class="col-lg-12 label-fr">Keperluan Untuk :</label>
              <div class="col-lg-7">{{ $pengajuan['keperluan'] }}</div>
            </div>
            <div class="row mb-2">
              <label class="col-lg-12 label-fr">Tanggal Pengajuan :</label>
              <div class="col-lg-7">{{ ($pengajuan['tgl_pengajuan']) ? $mylib::indotgl($pengajuan['tgl_pengajuan']) : '-' }}</div>
            </div>
            <div class="row mb-2">
              <label class="col-lg-12 label-fr">Status Pengajuan :</label>
              <div class="col-lg-7">{!! $mylib::bak_status_pengajuan($pengajuan['status'], 'lbl') !!}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg">
        @if ($pengajuan['status'] == 'TOLAK')
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle"></i> Maaf, pengajuan ditolak
          <p class="mb-0">{{ $pengajuan['keterangan_status'] }}</p>
        </div>
        @else
        <div class="alert alert-info">
          <i class="fas fa-exclamation-triangle"></i> Perhatian
          @if ($pengajuan['status'] == 'DRAFT')
          <p class="mb-0"></p>
          @endif
        </div>
        @endif
        <div class="card card-outline card-secondary">
          <div class="card-body p-0 table-responsive">
            <table class="table table-sm table-striped table-hover no-wrap">
              <thead class="bg-secondary">
                <tr>
                  <th>#</th>
                  <th>Syarat Pengajuan</th>
                  <th width="150px">Berkas</th>
                </tr>
              </thead>
              <tbody>
                @php $no = 0; $is_pass_pengajuan = 0; @endphp
                @foreach ($arr_syarat as $r)
                <tr>
                  <td>{{ ++$no }}</td>
                  <td>{{ $r['nama'] }}</td>
                  <td>
                    @if ($r['berkas'])
                    <a href="#" class="bview btn btn-info" data-nm="{{ $r['nama'] }}" data-url="{{ url($r['berkas']) }}"><i class="fas fa-eye"></i> Tampilkan</a>
                    @if ($pengajuan['status'] == 'DRAFT')
                      <a href="#" class="bdel btn btn-danger" data-id="{{ $r['bak_pengajuan_syarat_id'] }}" data-nm="{{ $r['nama'] }}"><i class="fas fa-trash-alt"></i></a>
                    @endif
                    @else
                    <div class="pdf-upload-frame" style="max-width:160px">	
                      <label class="drop-container">
                        <span><i class="fas fa-file-pdf"></i> Unggah Berkas</span>
                        <input type="file" class="drop-file" data-id="{{$r['bak_pengajuan_syarat_id']}}" accept=".pdf">
                      </label>
                    </div>
                    @endif
                  </td>
                </tr>
                @php $is_pass_pengajuan = ($r['berkas']) ? ++$is_pass_pengajuan : $is_pass_pengajuan; @endphp
                @endforeach
                @if ($no == 0)
                <tr><td colspan="3" align="center">Tidak ada data ditemukan!</td></tr>
                @endif
              </tbody>
            </table>
          </div>
          <div class="card-footer p-2 text-right">
            <a href="{{ url($back_url) }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
            @if ($pengajuan['status'] == 'DRAFT')
            <a href="#" id="btn-aju" class="btn btn-primary {{ ($is_pass_pengajuan != $no) ? 'disabled' : '' }}"><i class="fas fa-paper-plane"></i> Kirim Pengajuan</a>
            @endif
          </div>
        </div>
      </div>
    </div>    
  </div>
</div>

<div id="mdview" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header p-3"><div class="modal-title font-weight-bold"><i class="fas fa-file-pdf"></i> <span id="mdview-nm"></span></div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-0"><iframe id="mdview-file" src="" frameborder="0" width="100%" height="600px"></iframe></div>
		</div>
	</div>
</div>

<div id="mddel" class="modal fade" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
      </div>
      <div class="modal-body">
        <p class="mb-0">Anda akan menghapus <b id="mddel-nm"></b>, lanjutkan ?</p>
        <small><i>Perhatian : Operasi ini tidak bisa dipulihkan.</i></small>
      </div>
      <div class="modal-footer justify-content-between p-2">
        <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
        <button id="mddel-btn" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
      </div>
    </div>
  </div>
</div>

<div id="mdaju" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header p-3"><div class="modal-title font-weight-bold text-primary"><i class="fas fa-question-circle"></i> Konfirmasi</div></div>
			<div class="modal-body p-3"><p class="mb-2">Anda akan mengajukan berkas untuk divalidasi, lanjutkan ?</p><i>Perhatian : Upload persyaratan yang diminta, cek terlebih dahulu berkas yang telah diupload sebelum kirim pengajuan.</i></div>
      <div class="modal-footer justify-content-between p-2">
        <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
        <button id="mdaju-btn" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Pengajuan</button>
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

	$('.bview').click(function(e){
		e.preventDefault();
		const nm = $(this).data('nm');
		const url = $(this).data('url');
		$('#mdview-file').attr('src', url);
		$('#mdview-nm').text(nm);
		$('#mdview').modal('show');
	});

	$('.drop-file').change(function(e){
		e.preventDefault();
		const elm = $(this);
		const size = this.files[0].size;
		if (this.files[0].size > 1048576) {
			toast('info','Perhatian!<br>Ukuran file maximal 1MB');
			elm.val('');
			return false;
		}
		const id = $(this).data('id');
		let formData = new FormData();
		formData.append('_token', token);
		formData.append('id', id);
		formData.append('mid', "{{ $pengajuan['id'] }}");
		formData.append('file', elm[0].files[0]);
		$.ajax({
			url:"{{ url($main_url.'/upload') }}", type:'POST', dataType:'json', data:formData,
			processData: false, contentType: false,
			success : function(d) {
				toast(d.status, d.statusText);
				if (d.status == 'success') { setTimeout(() => { window.location.reload(); }, 500); } else { elm.val(''); }
			}, error: function(d){ toasterr(d); }
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
      url:"{{ url($main_url.'/delete-berkas') }}", type:'post', dataType:'json', data:{'_token':token, 'id':id},
      beforeSend: function(){ bid.html(loading).attr('disabled',true); },
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

	$('#btn-aju').click(function(e){
		e.preventDefault();
		$('#mdaju').modal('show');
	});
	$('#mdaju-btn').click(function(e){
    e.preventDefault();
		const mid = "{{ $pengajuan['id'] }}";
		const bid = $(this);
		const bval = bid.html();
		$.ajax({
			url:"{{ url($main_url.'/pengajuan-validasi') }}", type:'POST', dataType:'json', data:{'_token':token, 'mid':mid},
			beforeSend: function(){ bid.html(loading).attr('disabled', true); },
			success : function(d) {
				toast(d.status, d.statusText);
				if (d.status == 'success') { setTimeout(() => { window.location.reload(); }, 500); } else { bid.html(bval).attr('disabled', false); }
			}, error: function(d){ toasterr(d); bid.html(bval).attr('disabled', false); }
		});
	});
</script>
@endsection
@include('administrasi._footer')