<div class="bg-light">
	<a href="{{ url($app_path.'/data-ta/form/detail/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'detail') ? 'info-active' : 'info-hover' }} mb-1">Detail TA.</div></a>
	@if ($ta_status == 'disetujui')
	<a href="{{ url($app_path.'/data-ta/bimbingan/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'bimbingan') ? 'info-active' : 'info-hover' }} mb-1">Bimbingan</div></a>
	<a href="{{ url($app_path.'/data-ta/syarat-ujian/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'syarat_ujian') ? 'info-active' : 'info-hover' }} mb-1">Syarat Ujian TA.</div></a>
	<a href="{{ url($app_path.'/data-ta/jadwal-ujian-ta/mhs/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'jadwal_ujian') ? 'info-active' : 'info-hover' }} mb-1">Jadwal Ujian TA.</div></a>
	<a href="{{ url($app_path.'/data-ta/nilai-akhir/detail/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'nilai_akhir') ? 'info-active' : 'info-hover' }} mb-1">Nilai Akhir</div></a>
	@endif
</div>