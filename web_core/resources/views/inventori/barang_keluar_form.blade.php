@include('administrasi._header')
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
        
        <div class="card-footer p-2">
          <div class="d-flex justify-content-between align-items-center">
        <form action="" method="post" id="" autocomplete="off">
          <div class="btn-group">
          @csrf  
        <!-- Cek status_ajuan -->
          @if ($status_ajuan === 'draft')
          <!-- Tombol Ajukan -->
          <button type="button" class="btn btn-success" id="btn-show-ajukan">Ajukan</button>
          <!-- Tombol Tambah -->
      <a href="#" id="badd" class="btn btn-primary btn-sm" title="Tambah" data-toggle="tooltip"><i class="fas fa-plus-circle"></i> Tambah</a>
            @else
            <button type="button" class="btn btn-danger" id="btn-show-batal-ajukan">Batalkan Ajuan</button>
      @endif
        </form>
          </div>
        <div class="btn-group">
          <a href="{{ url($ctr_back) }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Kembali</a>
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
                <th>#</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                @if ($inventoriBarangKeluar->status_ajuan == 'verified')
                <th>Jumlah Disetujui</th>
                @endif                
                <th>Status</th>
                @if ($inventoriBarangKeluar->status_ajuan == 'verified')
                <th>Tanggal Verifikasi</th>
                @endif
            		<th width="110px"><i class="fas fa-cogs"></i></th>
							</tr>
						</thead>
						<tbody>
							@php $no = $var['lastno']; @endphp
							@foreach ($tbl as $r)
							<tr>
                <td>{{ ++$no }}</td>
                <td>{{ $r['nama_barang'] }}</td>
                <td>{!! $r['jumlah'].' '.$r['satuan'] !!}</td>
                @if ($inventoriBarangKeluar->status_ajuan == 'verified')
                    <td>
                        @if (is_null($r['jumlah_disetujui']))
                            {!! $r['jumlah_disetujui'] !!}
                        @elseif ($r['jumlah_disetujui'] == 0)
                            {!! '-' !!}
                        @else
                            {!! $r['jumlah_disetujui'].' '.$r['satuan'] !!}
                        @endif
                    </td>
                @endif
                <td>{!! $r['status_pengajuan_label'] !!}</td>
                @if ($inventoriBarangKeluar->status_ajuan == 'verified')
                <td>{{ $r['tanggal_verifikasi'] }}</td>
                @endif                     
                  <td>
                        @if ($r['is_edit'] == 'T')
                        <a href="#" class="btn btn-xs btn-secondary disabled"><i class="fas fa-lock"></i></a>
                    @else
                        @if ($r['status'] === 'draft')
                            <a href="#" class="bedit btn btn-info btn-xs" data-id="{{$r->id}}" title="Edit"><i class='fas fa-edit'></i></a>
                            <a href="#" class="bdel btn btn-xs btn-danger" data-id="{{ $r['id'] }}" data-nm="{{ $r['nama_barang'] }}"><i class="fas fa-trash-alt"></i></a>
                        @elseif ($r['status'] != 'draft')
                        <a href="#" class="btn btn-xs btn-secondary disabled"><i class="fas fa-lock"></i></a>
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
      </form>

			</div>
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
                <div class="row mb-2">
                  <label for="in1" class="col-lg-4 label-fr">Barang :</label>
                      <div class="col-lg-6">
                          <select name="in1" id="in1" class="form-control select2" style="width: 100%"></select>
                      </div>
                  </div>
                  <div class="row mb-2">
                    <label for="in2" class="col-lg-4 label-fr">Jumlah :</label>
                      <input type="number" id="in2" class="form-control form-control-sm" name="in2" style="width: 50%">
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



{{-- ajukan data --}}
<div class="modal fade" id="modal-ajukan" tabindex="-1" role="dialog" aria-labelledby="modal-ajukanLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-ajukanLabel">Konfirmasi Pengajuan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin mengajukan semua barang? Barang tidak bisa diedit atau ditambah".
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-ajukan">Ajukan</button>
      </div>
    </div>
  </div>
</div>

{{-- batal ajukan data --}}
<div class="modal fade" id="modal-batal-ajukan" tabindex="-1" role="dialog" aria-labelledby="modal-batal-ajukanLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-batal-ajukanLabel">Konfirmasi Pembatalan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin membatalkan pengajuan barang?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-batal-ajukan">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>

{{-- hapus data --}}
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

$('.chform').change(function(e){
		e.preventDefault();
		$('#fr0').submit();
	});

  $('#badd').click(function(e){
		e.preventDefault();		
		openform('TAMBAH', []);
	});


	   // Inisialisasi Select2
     $('#in1').select2({
        ajax: {
            url: "{{ url($ctr_path.'/cmb-barang') }}",
            dataType: 'json',
            delay: 1000,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            }
        },
        templateResult: formatResult,  // Format hasil pencarian
        templateSelection: formatSelection,  // Format item yang dipilih
        minimumInputLength: 3,
        placeholder: 'Cari Nama Barang',
        allowClear: true
    });

 // Fungsi untuk menampilkan nama barang, jumlah stok, dan satuan pada hasil pencarian
function formatResult(item) {
    if (!item.id) {
        return item.text;
    }
    // Membuat format hasil pencarian
    var displayText = item.text + ' - Stok: ' + item.jumlah_stock + ' ' + item.satuan;
    return $('<span>' + displayText + '</span>');
}

// Fungsi untuk menampilkan item yang dipilih
function formatSelection(item) {
    if (!item.id) {
        return item.text;
    }
    
    // Jika item.text sudah mengandung informasi stok, kembalikan langsung tanpa format tambahan
    if (item.text.includes('Stok')) {
        return item.text;
    }
    
    // Jika tidak, tambahkan stok dan satuan seperti sebelumnya
    return item.text + ' - Stok: ' + item.jumlah_stock + ' ' + item.satuan;
}
    
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
            $('#in0').val(data[0]);  // Set ID
            // Set nilai untuk select2 dan trigger 'change'
            var option = new Option(data[3] + ' - Stok: ' + data[5] + ' ' + data[6], true, true);
            $('#in1').append(option).trigger('change');
            $('#in2').val(data[4]);
            $('#fr1').attr('action', "{{ url($ctr_path.'/update') }}");
        } else {
            $('#in0').val('');
            $('#in1').val('').trigger('change'); // Reset select2
            $('#in2').val('');
            $('#fr1').attr('action', "{{ url($ctr_path.'/save') }}");
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
      url: "{{ url($ctr_path.'/delete') }}",
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

  $(document).ready(function() {
  // Ketika tombol Ajukan diklik
  $('#btn-ajukan').click(function() {
    $.ajax({
      url: "{{ url($ctr_path.'/ajukan') }}", // Sesuaikan URL jika diperlukan
      type: 'post',
      dataType: 'json',
      data: { '_token': token }, // Pastikan token csrf ditambahkan
      beforeSend: function() {
        // Anda bisa menambahkan indikator loading jika diperlukan
      },
      success: function(response) {
        toast(response.status, response.statusText);
        if (response.status === 'success') {
          setTimeout(() => { window.location.reload(); }, 500);
        }
      },
      error: function(response) {
        toasterr(response);
      }
    });
  });


  // Menampilkan modal konfirmasi saat tombol Ajukan diklik
  $('#btn-show-ajukan').click(function() {
        // Cek apakah ada barang untuk diajukan
        let itemCount = {{ $tbl->count() }}; // Asumsi $tbl berisi detail barang keluar
        if (itemCount === 0) {
            toast('error', 'Tidak ada data barang yang bisa diajukan.');
            return;
        }
        $('#modal-ajukan').modal('show');
    });
});
 

  $(document).ready(function() {
  // Ketika tombol Ajukan diklik
  $('#btn-batal-ajukan').click(function() {
    $.ajax({
      url: "{{ url($ctr_path.'/batal_ajukan') }}", // Sesuaikan URL jika diperlukan
      type: 'post',
      dataType: 'json',
      data: { '_token': token }, // Pastikan token csrf ditambahkan
      beforeSend: function() {
        // Anda bisa menambahkan indikator loading jika diperlukan
      },
      success: function(response) {
        toast(response.status, response.statusText);
        if (response.status === 'success') {
          setTimeout(() => { window.location.reload(); }, 500);
        }
      },
      error: function(response) {
        toasterr(response);
      }
    });
  });

   $('#btn-show-batal-ajukan').click(function() {
        let itemCount = {{ $tbl->count() }}; 
        if (itemCount === 0) {
            toast('error', 'Tidak ada data barang yang bisa dibatalkan.');
            return;
        }
        $('#modal-batal-ajukan').modal('show');
    });
});

  
</script>
@endsection
@include('administrasi._footer')