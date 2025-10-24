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
							<label for="f1" class="col-lg-4 label-fr">Nama Unit:</label>
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
									@foreach($cmb['tanggal'] as $c) <option value="{{ $c['id'] }}" {{ ($c['id'] == $var['f2']) ? 'selected' : '' }}>{{ $c['val'] }}</option> @endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer p-2 text-right">
					<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
					<a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
          @if ($user_ses['grant']['is_create'] == 'Y')
          <a href="#" id="badd" class="btn btn-primary btn-sm" title="Tambah" data-toggle="tooltip"><i class="fas fa-plus-circle"></i> Tambah</a>
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
								<th>Tanggal Input</th>
								<th>Diinput Oleh</th>
								<th width="110px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
								<td>{{ ++$no }}</td>
								<td>{{ $r['nama_unit'] }}</td>
								<td>{{ $r['pengguna_role'] }}</td>
								<td>{{ $r['tanggal_input'] }}</td>
								<td>{{ $r['diinput_oleh'] }}</td>
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

{{-- tambah data --}}
<div id="mdfr1" class="modal fade" data-backdrop="static">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="fr1" action="" method="post" autocomplete="off">
              <input type="hidden" name="in0" id="in0">
              @csrf
              <div class="modal-header">
                  <div class="modal-title"><i class="fas fa-layer-group"></i> <b id="mdfr1-title" >Tambah/Edit Barang</b></div>
              </div>
              <div class="modal-body" >
                  <div class="mb-2">
                      <label for="in1" class="mb-0">Tanggal :</label>
                      <input type="date" id="in1" class="form-control form-control-sm" name="in1" style="width: 100%">
                  </div>
              </div>
              <div class="modal-footer p-2 justify-content-between grad-grey">
                  <a href="#" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class='fas fa-times-circle'></i> Tutup </a>
                  <button id="mdfr1-btn" class="btn btn-sm btn-primary"><i class='fas fa-paper-plane'></i> Simpan </button>
              </div>
          </form>
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

  @if(session()->has('toast'))
        toast("{{ session('toast')['status'] }}", "{{ session('toast')['statusText'] }}");
    @endif

    // Inisialisasi elemen khusus
    bsCustomFileInput.init();
    $('[data-toggle="tooltip"]').tooltip();

    const pdf_url = "{{ url('assets/img/placeholder.jpg') }}";

    // Event handler untuk tombol "Tambah"
    $('#badd').click(function(e) {
        e.preventDefault();
        openform('TAMBAH', []);
    });

    // Event handler untuk tombol "Edit"
    $('.bedit').click(function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            url: "{{ url($ctr_path.'/get') }}",
            type: 'post',
            dataType: 'json',
            data: { '_token': token, 'id': id },
            success: function(d) {
                if (d.status === 'success') {
                    openform('EDIT', d.datalist);
                } else {
                    toast(d.status, d.statusText);
                }
            },
            error: function(d) {
                toasterr(d);
            }
        });
    });

    $('#fr1').submit(function(e) {
    e.preventDefault();
    let formdata = new FormData(this);
    const bid = $('#mdfr1-btn');
    const bval = bid.html();
    const tourl = $(this).attr('action');

    $.ajax({
        url: tourl,
        type: 'post',
        dataType: 'json',
        data: formdata,
        processData: false,
        contentType: false,
        beforeSend: function() { 
            bid.html(loading).attr('disabled', true);
        },
        success: function(response) {
            toast(response.status, response.statusText);
            if (response.status === 'success') {
                // Redirect ke form dengan id yang baru disimpan
                window.location.href = "{{ url($ctr_path . '/form/edit') }}" +"/"+ response.id;
            } else {
                bid.html(bval).attr('disabled', false);
            }
        },
        error: function(d) {
            bid.html(bval).attr('disabled', false);
            toasterr(d);
        }
    });
});

    // Fungsi untuk membuka modal form
    function openform(stat, data) {
        if (stat === 'EDIT') {
            $('#in0').val(data[0]);
            $('#in1').val(data[1]);
            $('#fr1').attr('action', "{{ url($ctr_path.'/update') }}");
        } else {
            $('#in0').val('');
            $('#in1').val('');
            $('#fr1').attr('action', "{{ url($ctr_path.'/add') }}");
        }
        $('#mdfr1-title').text(stat);
        $('#mdfr1').modal('show').on('shown.bs.modal', function() {
            $('#in1').focus();
        });
    }

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