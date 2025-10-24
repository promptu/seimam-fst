@include('administrasi._header')
@php
    $is_mahasiswa = $user_ses['active_role']['id'] == '3' ? true : false;
@endphp
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-footer p-2 text-right">
                        <a href="{{ url($app_path . '/proposal') }}" class="btn btn-default"><i
                                class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mb-3">
                @include('ta.data.menu')
            </div>
            <div class="col-sm">
                <div class="row">
                    <div class="col-lg-12 px-3">
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
                                    <div class="col-lg">
                                        {{ $mylib::nama_gelar($get['mahasiswa_gelar_depan'], $get['mahasiswa_nama'], $get['mahasiswa_gelar_belakang']) }}
                                    </div>
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
                <div class="card card-outline card-warning">
                    <div class="card-body pb-2 row">
                        <div class="col-lg-6">
                            <form action="{{ url($ctr_path) }}" method="post" id="fr0" autocomplete="off">@csrf
                                <input type="hidden" name="filter" id="filter" value="filter">
                                <div class="row mb-2">
                                    <label for="f1" class="col-lg-4 label-fr">Dosen Pembimbing :</label>
                                    <div class="col-lg-7">
                                        <select name="f1" id="f1" class="chform form-control">
                                            <option value="">- Semua -</option>
                                            @foreach ($cmb['dosen_pembimbing'] as $c)
                                                <option value="{{ $c['id'] }}"
                                                    {{ $c['id'] == $var['f1'] ? 'selected' : '' }}>
                                                    {{ $mylib::nama_gelar($c['pegawai_gelar_depan'], $c['val'], $c['pegawai_gelar_belakang']) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg text-right">
                            @if ($user_ses['active_role']['id'] == '3' || $user_ses['active_role']['is_admin'] == 'Y')
                                <a href="{{ url($ctr_path . '/form/add') }}" id="badd" class="btn btn-primary"><i
                                        class="fas fa-plus-circle"></i> Bimbingan</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-striped table-sm">
                            <thead class="bg-dark">
                                <tr>
                                    <td>#</td>
                                    <td>Pembimbing</td>
                                    <td>Tanggal Bimbingan</td>
                                    <td>Topik</td>
                                    <td>Disetujui/ <br>Tolak</td>
                                    <td>Status</td>
                                    <td width="70px"><i class="fas fa-cogs"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp
                                @foreach ($tbl as $r)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{!! $mylib::nama_gelar($r['peg_gelar_depan'], $r['peg_nama'], $r['peg_gelar_belakang']) .
                                            '<br><span class="badge badge-success">Pembimbing ' .
                                            $r['pembimbing_ke'] .
                                            '</span>' !!}</td>
                                        <td>{!! 'Bimbingan Ke.' . $r['bimbingan_ke'] . '<br>' . $mylib::indotgl($r['tgl_bimbingan']) !!}</td>
                                        <td>{{ $r['topik'] }}</td>
                                        <td>{!! $mylib::status_disetujui($r['status_disetujui']) !!}</td>
                                        <td>{{ $r['status_bimbingan'] }}</td>
                                        <td>
                                            @if ($r['status_bimbingan'] == 'aktif')
                                                <a href="{{ url($ctr_path . '/form/edit/' . $r['id']) }}"
                                                    class="bedit btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                                            @else
                                                <a href="{{ url($ctr_path . '/detail/' . $r['id']) }}"
                                                    class="btn btn-xs btn-primary"><i class="fas fa-eye"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($no == 0)
                                    <tr>
                                        <td colspan="7" align="center">Tidak ada data ditemukan!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('addonjs')
    <script>
        $('.chform').change(function(e) {
            e.preventDefault();
            $('#fr0').submit();
        });
    </script>
@endsection
@include('administrasi._footer')
