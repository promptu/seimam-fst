@include('inventori._header')
<div class="content">
	<div class="container">
<div class="row mb-2">
	<div class="col-sm-8"> <h5>Laporan</h5> </div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="card card-outline card-maroon">
			<form action="#" method="post" id="fr-filter">				
			<div class="card-body">
				<h5 class="text-muted border-bottom mb-4">Filter data :</h5>
				<div class="row mb-3">
					<label for="f1" class="col-sm-3">Unit Kerja :</label>
					<div class="col-sm">
						<select name="f1" id="f1" class="form-control"><option value="">-  Semua Unit -</option>
							@foreach ($cmb['unit_kerja'] as $c)
							<option value="{{$c['id']}}">{{$c['val']}}</option>
							@endforeach						
						</select>
					</div>
				</div>
		
				<div class="row mb-3">
                    <label for="f4_start" class="col-sm-3">Tanggal Barang Masuk (Mulai):</label>
                    <div class="col-sm">
                        <input type="date" name="f4_start" id="f4_start" class="form-control datepicker" placeholder="Tanggal Mulai" autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="f4_end" class="col-sm-3">Tanggal Barang Masuk (Selesai):</label>
                    <div class="col-sm">
                        <input type="date" name="f4_end" id="f4_end" class="form-control datepicker" placeholder="Tanggal Selesai" autocomplete="off">
                    </div>
                </div>
				{{-- <div class="row mb-4">
					<label for="f4" class="col-sm-3">Jenis :</label>
					<div class="col-sm-4">
						<select name="f4" id="f4" class="form-control">
							<option value="">-  Semua -</option>
							<option value="YA">Verifikasi KIP</option>
							<option value="TIDAK">Non KIP</option>
						</select>
					</div>
				</div> --}}
				<hr>
				<div class="row mb-3">
					<label class="col-sm-3">Export Format :</label>
					<div class="col-sm">
                        <a href="{{url('/inventori/laporan/barang-masuk/html')}}" class="bexport btn btn-primary mr-3"><i class="fab fa-html5"></i> HTML</a>						
						{{-- <a href="{{url('/inventori/laporan/barang-masuk/excel')}}" class="bexport btn btn-info mr-3"><i class="far fa-file-excel"></i> Excel</a></div> --}}
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
@section('addonjs')
<script>

    $(document).ready(function() {
        // Inisialisasi datepicker untuk kedua input
        $('#f4_start, #f4_end').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });

	$('.bexport').click(function(e){
		e.preventDefault();
		const tolink = $(this).attr('href');
		const f1 = $('#f1').val();
        const f4_start = $('#f4_start').val();  // Tanggal Mulai
        const f4_end = $('#f4_end').val();      // Tanggal Selesai
		const bid = $(this);
		$.ajax({url:"{{url('/inventori/laporan/barang-masuk/set-filter')}}", type:'post', dataType:'json', data:{'_token':token,'f1':f1,'f4_start':f4_start,'f4_end':f4_end},
			beforeSend:function(){ bid.attr('disabled', true); },
			success:function(d){
				if (d.status == 'success') { window.open(tolink, '_blank'); } else { toast(d.status, d.statusText); bid.attr('disabled', false); }
			}, error: function(e){ toasterr(e); bid.attr('disabled', false); }
		});
	});
</script>
@endsection
@include('inventori._footer')
