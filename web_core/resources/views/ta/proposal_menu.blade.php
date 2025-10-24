<div class="bg-light">
	<a href="{{ url($app_path.'/proposal/form/detail/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'detail') ? 'info-active' : 'info-hover' }} mb-1">Detail Proposal</div></a>
	@if ($proposal_status == 'disetujui')
	<a href="{{ url($app_path.'/proposal/bimbingan/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'bimbingan') ? 'info-active' : 'info-hover' }} mb-1">Bimbingan</div></a>
	<a href="{{ url($app_path.'/proposal/syarat-ujian/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'syarat_ujian') ? 'info-active' : 'info-hover' }} mb-1">Syarat Ujian Proposal</div></a>
	<a href="{{ url($app_path.'/proposal/jadwal-ujian/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'jadwal_ujian') ? 'info-active' : 'info-hover' }} mb-1">Jadwal Ujian Proposal</div></a>
	<a href="{{ url($app_path.'/proposal/nilai-akhir/detail/'.$id_page) }}"><div class="callout callout-info p-2 {{ ($segment_page == 'nilai_akhir') ? 'info-active' : 'info-hover' }} mb-1">Nilai Akhir</div></a>
	@endif
</div>