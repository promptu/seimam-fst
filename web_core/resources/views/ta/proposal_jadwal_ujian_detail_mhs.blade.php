@include('administrasi._header')
@php
    $is_mahasiswa = $user_ses['active_role']['id'] == '3' ? true : false;

    $tgl_ujian = '';
    $jam_mulai = '';
    $jam_selesai = '';
    if ($get['tgl_ujian_mulai']) {
        $exp = explode(' ', $get['tgl_ujian_mulai']);
        if (count($exp) == 2) {
            $tgl_ujian = $exp[0];
            $exp_jam = explode(':', $exp[1]);
            if (count($exp_jam) == 3) {
                $jam_mulai = $exp_jam[0] . ':' . $exp_jam[1];
            }
        }
    }
    if ($get['tgl_ujian_selesai']) {
        $exp = explode(' ', $get['tgl_ujian_selesai']);
        if (count($exp) == 2) {
            $exp_jam = explode(':', $exp[1]);
            if (count($exp_jam) == 3) {
                $jam_selesai = $exp_jam[0] . ':' . $exp_jam[1];
            }
        }
    }
@endphp

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-outline card-warning">
                    <div class="card-footer p-2 text-right">
                        <a href="{{ url($ctr_path) }}" class="btn btn-default"><i class="fas fa-arrow-left"></i>
                            Kembali</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 mb-3">
                @include('ta.proposal_menu')
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
                <div class="card card-outline card-success">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg">
                                <h6><u>Dosen Pembimbing :</u></h6>
                            </div>
                        </div>
                        @php
                            $no_pembimbing = 0;
                            $pembimbing = \App\Models\TaProposalPembimbingMdl::joinAll($get['id'])->get();
                        @endphp
                        @foreach ($pembimbing as $r)
                            @php
                                $cur_pembimbing =
                                    $r['pegawai_nip'] .
                                    ' - ' .
                                    $mylib::nama_gelar(
                                        $r['pegawai_gelar_depan'],
                                        $r['pegawai_nama'],
                                        $r['pegawai_gelar_belakang'],
                                    ) .
                                    ' (Pembimbing -' .
                                    $r['pembimbing_ke'] .
                                    ')';
                            @endphp
                            <div class="callout callout-success text-bold p-2 mb-1">
                                <i class="fas fa-user-graduate"></i> {{ $cur_pembimbing }}
                            </div>
                            @php ++$no_pembimbing; @endphp
                        @endforeach
                        @if ($no_pembimbing == 0)
                            <div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i>
                                Belum memiliki dosen pembimbing.</div>
                        @endif
                    </div>
                </div>
                <div class="card card-warning card-outline">
                    <div class="card-body pb-2 row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row mb-2">
                                        <label class="col-lg-4 label-fr-dark">Ruang Ujian :</label>
                                        <div class="col-lg">{{ $get['ta_ruang_ujian_nama'] }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row mb-2">
                                        <label class="col-lg-4 label-fr-dark">Jadwal Ujian :</label>
                                        <div class="col-lg">{!! $mylib::indotgl($tgl_ujian) . ' &nbsp; ' . $jam_mulai . ' s/d ' . $jam_selesai !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-outline card-warning">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg">
                                <h6><u>Dosen Penguji :</u></h6>
                            </div>
                        </div>
                        @php
                            $no_penguji = 0;
                            $penguji = \App\Models\TaProposalPengujiMdl::getByProposalId($get['id'])->get();
                        @endphp
                        @foreach ($penguji as $r)
                            @php
                                $cur_penguji =
                                    $r['pegawai_nip'] .
                                    ' - ' .
                                    $mylib::nama_gelar(
                                        $r['pegawai_gelar_depan'],
                                        $r['pegawai_nama'],
                                        $r['pegawai_gelar_belakang'],
                                    ) .
                                    ' (Penguji - ' .
                                    ++$no_penguji .
                                    ')';
                            @endphp
                            <div class="callout callout-warning text-bold p-2 mb-1 row">
                                <div class="col-lg-8"><i class="fas fa-user-graduate"></i> {{ $cur_penguji }}</div>
                            </div>
                        @endforeach
                        @if ($no_penguji == 0)
                            <div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i>
                                Belum memiliki penguji.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('addonjs')
    <script></script>
@endsection
@include('administrasi._footer')
