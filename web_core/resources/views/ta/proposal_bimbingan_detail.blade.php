@section('addon_header')
    <link rel="stylesheet" href="{{ url('assets/bo/plugins/summernote/summernote-bs4.min.css') }} ">
@endsection
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
                <div class="card card-outline card-warning">
                    <div class="card-body pb-2 row">

                        <div class="col-lg text-right border-bottom pb-3">
                            <a href="{{ url($ctr_path) }}" class="btn btn-warning btn-sm"><i
                                    class="fas fa-arrow-left"></i> Daftar Bimbingan</a>
                        </div>
                    </div>
                    <div class="card-body row pb-0">
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <label class="col-lg-4 label-fr">Bimbingan Ke :</label>
                                <div class="col-lg-3">
                                    <div class="box-fr">{{ $bimbingan['bimbingan_ke'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <label class="col-lg-4 label-fr">Pembimbing :</label>
                                <div class="col-lg">
                                    <div class="box-fr">
                                        {{ $mylib::nama_gelar($bimbingan['peg_gelar_depan'], $bimbingan['peg_nama'], $bimbingan['peg_gelar_belakang']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <label class="col-lg-4 label-fr">Tanggal Bimbingan :</label>
                                <div class="col-lg">
                                    <div class="input-group">
                                        <div class="box-fr">
                                            {{ $mylib::switch_tgl($bimbingan['tgl_bimbingan'], 'short') }}</div>
                                        <span class="input-group-append">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-2">
                                <label class="col-lg-2 label-fr">Topik :</label>
                                <div class="col-lg">
                                    <div class="box-fr">{{ $bimbingan['topik'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-2">
                                <label class="col-lg-2 label-fr">Bahasan :</label>
                                <div class="col-lg">
                                    <div class="box-fr">{!! $bimbingan['bahasan'] !!}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-0">
                                <label class="col-lg-2" style="padding-left: 3px">Lampiran :</label>
                                <div class="col-lg">
                                    <iframe type="application/pdf" id="input-file-preview"
                                        src="{{ $bimbingan['lampiran'] ? url($bimbingan['lampiran']) : '' }}"
                                        width="100%" height="400" class="mt-1"
                                        style="border: solid 1px #ccc; border-radius: 5px; margin-bottom: 0px;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="col-lg-12 pl-0">
                            <h4 class="ml-0 mb-3 border-bottom mt-3">Respon Pembimbing</h4>
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-2">
                                <label class="col-lg-2 label-fr">Disetujui/Tolak :</label>
                                <div class="col-lg">{!! $mylib::status_disetujui($bimbingan['status_disetujui']) !!}</div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="row mb-2">
                                <label class="col-lg-2 label-fr">Catatan Pembimbing :</label>
                                <div class="col-lg">
                                    <div class="box-fr">{!! $bimbingan['catatan_pembimbing'] !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('addonjs')
    <script></script>
@endsection
@section('addon_footer')
    <script src="{{ url('assets/bo/plugins/summernote/summernote-bs4.min.js') }} "></script>
@endsection
@include('administrasi._footer')
