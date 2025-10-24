<div class="bg-light">
	@foreach ($bimbingan_ke as $item)
		@php $encid = $crypt::encryptString($item['id']) @endphp
		<a href="{{ url($ctr_path.'/detail/'.$encid) }}"><div class="callout callout-info p-2 {{ ($item['id'] == $bimbingan['id']) ? 'info-active' : 'info-hover' }} mb-1">Bimbingan ke-{{ $item['bimbingan_ke'] }}</div></a>		
	@endforeach
</div>