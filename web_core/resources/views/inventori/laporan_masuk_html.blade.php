<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>Laporan Barang Masuk</title>
		<style>
			body{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11pt; }
			table{ border-collapse: collapse; }
			th, td { padding: 5px; }
			.border tr, .border td, .border th{ border: 1px solid; }
		</style>
	</head>
	<body>
		<table style="width: 100%">
			<tr><td colspan="{{$col_num}}" align="left"><b>Laporan Barang Masuk</b></td></tr>
			<tr><td colspan="{{$col_num}}" align="left"><b>Unit Kerja : {{$title['f1']}}</b></td></tr>
			<tr><td colspan="{{$col_num}}" align="left"><b>Tanggal Barang Masuk:  {{$title['f4_start']}} - {{$title['f4_end']}}</b></td></tr>

		</table>
		<table class="border" style="width: 100%">
			<thead>
				<tr>
					<th><b>#</b></th>
					@foreach ($col_name as $r)
					<th><b>{{$r}}</b></th>				
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach ($tbl as $r)
				<tr>
					<td>{{++$no}}</td>
					@foreach ($col_key as $k)
					<td>{!!$r[$k]!!}</td>
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>