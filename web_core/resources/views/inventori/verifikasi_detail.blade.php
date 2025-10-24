@include('administrasi._header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="content">
	<div class="container">
        <form action="{{ url($ctr_path) }}" method="post" id="fr0" autocomplete="off">
          @csrf
          <input type="hidden" id="filter" name="filter" value="filter">
                <div class="card card-outline card-warning">
				{{-- <div class="card-body pb-2 row">
					<div class="col-lg-6">
						<div class="row mb-2">
							<label for="f1" class="col-lg-4 label-fr">Nama Barang :</label>
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
				</div> --}}
      </form>
	<div class="card-footer p-2">

        <div class="d-flex justify-content-between align-items-center">
            <!-- Tombol disetujui dan ditolak di sebelah kiri dalam form -->
            <form id="verif-form" method="post" action="{{ url($ctr_path.'/verif') }}">
                @csrf
                <div class="btn-group">
                    <button type="submit" name="action[]" value="approve" class="btn btn-primary btn-action">Disetujui</button>
                    <button type="submit" name="action[]" value="reject" class="btn btn-danger btn-action">Ditolak</button>
                </div>
            </form>
            
            <!-- Tombol lainnya di sebelah kanan -->
            <div class="btn-group">
                <a href="{{ url('/inventori/verifikasi') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-search"></i></button>
                <a href="{{ url($ctr_path).'?act=reset' }}" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></a>
            </div>
        </div>
    </div>
          <div class="card card-outline card-secondary">
              <div class="card-body p-0 table-responsive">
                  <table class="table table-sm table-striped table-hover no-wrap">
                      <thead class="bg-secondary">
                          <tr>
                              <th><input type="checkbox" id="select-all"> Pilih</th>
                              <th>#</th>
                              <th>Nama Barang</th>
                              <th>Jumlah</th>
                              <th>Jumlah Disetujui</th>
                              <th>Status</th>
                              <th>Tanggal Verifikasi</th>
                              <th width="110px"><i class="fas fa-cogs"></i></th>
                          </tr>
                      </thead>
                      <tbody>
                          @php $no = $var['lastno']; @endphp
                          @foreach ($tbl as $r)
                              <tr>
                                  <td>
                                      @if ($r['status'] == 'diajukan')
                                          <input type="checkbox" name="selected_items[]" class="item-tabel" value="{{ $r->id }}">
                                      @else
                                          <input type="checkbox" disabled title="Status tidak memungkinkan untuk diverifikasi">
                                      @endif
                                  </td>
                                  <td>{{ ++$no }}</td>
                                  <td>{{ $r['nama_barang'] }}</td>
                                  <td>{!! $r['jumlah'].' '.$r['satuan'] !!}</td>
                                  <td>
                                      @if(is_null($r['jumlah_disetujui']))
                                        {!! $r['jumlah_disetujui'] !!}
                                      @elseif($r['jumlah_disetujui'] == 0)
                                        {!! '-' !!}
                                      @else
                                        {!! $r['jumlah_disetujui'].' '.$r['satuan'] !!}
                                      @endif
                                  </td>                                  
                                  <td>{!! $r['status_pengajuan_label'] !!}</td>
                                  <td>{{ $r['tanggal_verifikasi'] }}</td>
                                  <td>
                                    @if ($r['is_edit'] == 'T')
                                    <a href="#" class="btn btn-xs btn-secondary disabled"><i class="fas fa-lock"></i></a>
                                @else
                                    @if ($r['status'] === 'diajukan')
                                        <a href="#" class="bedit btn btn-info btn-xs" data-id="{{$r->id}}" title="Edit"><i class='fas fa-edit'></i></a>
                                    @elseif ($r['status'] != 'draft')
                                    <a href="#" class="bcancel btn btn-xs btn-danger" data-id="{{ $r['id'] }}" data-nm="{{ $r['nama_barang'] }}">Batalkan</i></a>
                                    @endif
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

                {{-- <form action="{{ url($ctr_path) }}" method="post" id="fr0" autocomplete="off">
                    @csrf
                    <input type="hidden" id="filter" name="filter" value="filter">
              @if(session('status'))
              <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'info' }} alert-dismissible fade show" role="alert" style="position: fixed; top: 10px; right: 10px; z-index: 9999;">
                  {{ session('statusText') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
          @endif --}}
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
  </div>
</div>
{{-- edit data --}}
<div id="mdfr1" class="modal fade" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <form id="fr1" action="" method="post" autocomplete="off">
              <input type="hidden" name="in0" id="in0">
              @csrf
              <div class="modal-header px-3">
                  <div class="modal-title"><i class="fas fa-layer-group"></i> <b id="mdfr1-title" >Tambah/Edit Barang</b></div>
              </div>
              <div class="modal-body px-3">
                <div class="mb-2">
                    <label for="in1" class="mb-0">Barang:</label>
                    <!-- Nama Barang tidak bisa diubah -->
                    <input type="text" id="in1" class="form-control form-control-sm" name="in1" readonly>
                </div>
                <div class="mb-2">
                    <label for="in2" class="mb-0">Jumlah Stok Tersedia:</label>
                    <!-- Jumlah Stock tidak bisa diubah -->
                    <input type="number" id="in2" class="form-control form-control-sm" name="in2" readonly>
                </div>
                <div class="mb-2">
                    <label for="in3" class="mb-0">Jumlah Pengajuan:</label>
                    <!-- Jumlah Pengajuan tidak bisa diubah -->
                    <input type="number" id="in3" class="form-control form-control-sm" name="in3" readonly>
                </div>
                <div class="mb-2">
                    <label for="in4" class="mb-0">Jumlah Disetujui:</label>
                    <!-- Jumlah Disetujui bisa diubah -->
                    <input type="number" id="in4" class="form-control form-control-sm" name="in4" required>
                </div>
            </div>
            <div class="modal-footer p-2 justify-content-between grad-grey">
                <a href="#" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
                <button id="mdfr1-btn" class="btn btn-sm btn-primary"><i class="fas fa-paper-plane"></i> Simpan</button>
            </div>
          </form>
      </div>
  </div>
</div>
<div id="mdcancel" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Konfirmasi</div>
        </div>
        <div class="modal-body">
          <p class="mb-0">Anda akan membatalkan {{ $page_title['bread'] }} <b id="mdcancel-nm"></b>, lanjutkan ?</p>
          <small><i>Perhatian : Operasi ini tidak bisa dipulihkan.</i></small>
        </div>
        <div class="modal-footer justify-content-between p-2">
          <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fas fa-times-circle"></i> Tutup</a>
          <button id="mdcancel-btn" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
        </div>
      </div>
    </div>
  </div>

<style>

.btn-group {
    display: flex;
    gap: 0.25rem; /* Jarak antar tombol */
}

.btn-action {
    margin: 0 0.25rem; /* Jarak horizontal antar tombol */
}
</style>
@section('addonjs')

<script>
$(document).ready(function() {
    // Handle form submission via AJAX

    $('.btn-action').on('click', function(e){
      e.preventDefault();
      const act = $(this).val();
      do_verify(act);
    });

    function do_verify(act){
    // $('#verif-form').on('submit', function(event) {
        // event.preventDefault(); // Prevent default form submission

        // Get form data
        // var formData = $(this).serialize();

        let items = [];
        $('.item-tabel:checked').each(function(){
          items.push($(this).val());
        });
        let formData = {"_token":token,'action': act,"selected_items": items};
        



        // Make AJAX request
        $.ajax({
            url: $("#verif-form").attr('action'), // URL dari form action
            type: 'POST', // Metode request
            data: formData, // Data yang akan dikirim
            success: function(response) {
                // Handle success response
                toast(response.status,response.statusText); // Notifikasi sukses
                if (response.status === 'success') {
                  setTimeout(() => {
                    location.reload(); // Refresh halaman
                  }, 500);
                }
            },
            error: function(xhr) {
                // Handle error response
                toasterr(xhr);
            }
        });
    // });

    }



    // Select/Deselect all checkboxes
    $('#select-all').on('change', function() {
        $('input[name="selected_items[]"]').prop('checked', this.checked);
    });
});


	$('.chform').change(function(e){
		e.preventDefault();
		$('#fr0').submit();
	});

  @if(session()->has('toast'))
  toast("{{ session('toast')['status'] }}", "{{ session('toast')['statusText'] }}");
@endif
  
  bsCustomFileInput.init();

  $('[data-toggle="tooltip"]').tooltip();

	const pdf_url = "{{url('assets/img/placeholder.jpg')}}";
	
    
  // Menangani klik tombol edit
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

// Menangani submit form
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
                setTimeout(() => { 
                    window.location.reload();
                }, 500);
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




// Fungsi untuk membuka modal form dalam mode Edit
function openform(stat, data) {
    if (stat === 'EDIT') {
        $('#in0').val(data[0]);  // Set ID detail
        $('#in1').val(data[1]);  // Set nama barang (readonly)
        $('#in2').val(data[2]);  // Set jumlah stok (readonly)
        $('#in3').val(data[3]);  // Set jumlah pengajuan (readonly)
        $('#in4').val(data[4]);  // Set jumlah disetujui (editable)
        $('#fr1').attr('action', "{{ url($ctr_path.'/update') }}");  // URL untuk update
    } else {
        $('#in0').val('');
        $('#in1').val(''); 
        $('#in2').val('');
        $('#in3').val('');
        $('#in4').val('');
        $('#fr1').attr('action', "{{ url($ctr_path.'/save') }}");  // URL untuk save (jika dibutuhkan)
    }
    $('#mdfr1-title').text(stat);
    $('#mdfr1').modal('show').on('shown.bs.modal', function() {
        $('#in4').focus();  // Fokus pada jumlah disetujui
    });
}


$('.bcancel').click(function(e){
    e.preventDefault();
    const id = $(this).data('id');
    const nm = $(this).data('nm');
    $('#mdcancel-nm').text(nm);
    $('#mdcancel-btn').val(id);
    $('#mdcancel').modal('show');
  });

  $('#mdcancel-btn').click(function(e){
    e.preventDefault();
    const id = $(this).val()
    const bid = $(this);
    const bval = bid.html();
    $.ajax({
      url: "{{ url($ctr_path.'/cancel') }}",
      type:'post', dataType:'json', data:{'_token':token, 'id':id},
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

  document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');

        selectAllCheckbox.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = selectAllCheckbox.checked;
                }
            });
        });
    });




</script>
@endsection
@include('administrasi._footer')