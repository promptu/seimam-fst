@include('administrasi._header')
<div class="content">
    <div class="container">
        @if ($user_ses['active_role']['is_admin'] == 'Y')
        @endif
        <div class="row">
            <div class="col-lg-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <div class="inline">
                            <img src="{{ url('assets/img/logo_fst.png') }}" alt="" class="m-1"
                                style="width: 60px">
                            <h5 class="mb-0">Selamat Datang,
                                {{ $user_ses['nama'] }}<br><small>{{ $dash_data['ket_pengguna'] }}</small></h5>

                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-sign-language"></i>
                    </div>
                </div>
            </div>
            @if ($dash_data['tipe'] == 'mahasiswa')
                <div class="col-lg-8">
                    <div class="small-box bg-primary">
                        <div class="icon">
                            <i class="fas fa-sign-language"></i>
                        </div>
                        <div class="inner">
                            <div class="inline">
                                <div class="row">
                                    <div class="col-lg-4 p-3">
                                        <div class="icon">
                                            <i class="fas fa-map"></i>
                                        </div>
                                        <h5 class="mb-0">Ikuti Tahapan <br> Tugas Akhir</h5>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="info-box bg-warning mb-0">
                                            <span class="info-box-icon"><i class="far fa-map"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $dash_data['step1']['judul'] }}</span>
                                                <span
                                                    class="info-box-number">{{ $dash_data['step1']['progress'] }}</span>
                                                <div class="progress">
                                                    <div class="progress-bar"
                                                        style="width: {{ $dash_data['step1']['persentase'] }}%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    <a href="{{ url($dash_data['step1']['link']) }}"
                                                        class="text-dark"><i class="fas fa-angle-right"></i>
                                                        <b>{{ $dash_data['step1']['link_text'] }}</b></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="info-box bg-warning mb-0">
                                            <span class="info-box-icon"><i class="fas fa-layer-group"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $dash_data['step2']['judul'] }}</span>
                                                <span
                                                    class="info-box-number">{{ $dash_data['step2']['progress'] }}</span>
                                                <div class="progress">
                                                    <div class="progress-bar"
                                                        style="width: {{ $dash_data['step2']['persentase'] }}%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    <a href="{{ url($dash_data['step2']['link']) }}"
                                                        class="text-dark"><i class="fas fa-angle-right"></i>
                                                        <b>{{ $dash_data['step2']['link_text'] }}</b></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($dash_data['tipe'] == 'dosen')
                <div class="col-lg-4">
                    <div class="small-box bg-info" style="opacity: 0.75">
                        <div class="inner">
                            <div class="inline">
                                <h6 class="mb-0">Anda memiliki</h6>
                                <h3>{{ $dash_data['bimbingan_proposal'] }}</h3>
                                <span><i class="fas fa-map"></i> Mahasiswa Bimbingan Proposal</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small-box bg-warning" style="opacity: 0.75">
                        <div class="inner">
                            <div class="inline">
                                <h6 class="mb-0">Anda memiliki</h6>
                                <h3>{{ $dash_data['bimbingan_ta'] }}</h3>
                                <span><i class="fas fa-layer-group"></i> Mahasiswa Bimbingan Tugas Akhir</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Bimbingan Proposal</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $no = 0;
                                foreach ($dash_data['bimbingan_proposal_aktif'] as $r) {
                                    ++$no;
                                    echo '<div class="info-box mb-3 bg-info" style="opacity: 0.75">
                                        <span class="info-box-icon"><i class="fas fa-map"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><b>' .
                                        $r['nim'] .
                                        ' - ' .
                                        $r['nama'] .
                                        '</b></span>
                                        <span>Bimbingan Ke :' .
                                        $r['bimbingan_ke'] .
                                        '</span><span>Topik : ' .
                                        $r['topik'] .
                                        '</span>
                                        </div>
                                    </div>';
                                }
                            @endphp
                            @if ($no == 0)
                                <p class="text-center my-4 py-4">
                                    <i class="fas fa-map fa-4x"></i>
                                    <br>Tidak ada bimbingan proposal aktif
                                </p>
                            @else
                                <a href="{{ url('ta/proposal/bimbingan/dosen') }}"><b><i
                                            class="fas fa-arrow-right"></i> Selengkapnya</b></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Bimbingan Proposal</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $no = 0;
                                foreach ($dash_data['bimbingan_ta_aktif'] as $r) {
                                    ++$no;
                                    echo '<div class="info-box mb-3 bg-warning" style="opacity: 0.75">
                                        <span class="info-box-icon"><i class="fas fa-map"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><b>' .
                                        $r['nim'] .
                                        ' - ' .
                                        $r['nama'] .
                                        '</b></span>
                                        <span>Bimbingan Ke :' .
                                        $r['bimbingan_ke'] .
                                        '</span><span>Topik : ' .
                                        $r['topik'] .
                                        '</span>
                                        </div>
                                    </div>';
                                }
                            @endphp
                            @if ($no == 0)
                                <p class="text-center my-4 py-4">
                                    <i class="fas fa-layer-group fa-4x"></i>
                                    <br>Tidak ada bimbingan aktif
                                </p>
                            @else
                                <a href="{{ url('ta/proposal/bimbingan/dosen') }}"><b><i
                                            class="fas fa-arrow-right"></i> Selengkapnya</b></a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-4">
                    <div class="small-box bg-info" style="opacity: 0.75">
                        <div class="inner">
                            <div class="inline">
                                <h6 class="mb-0">Terdapat</h6>
                                <h3>{{ $dash_data['pengajuan_proposal'] }}</h3>
                                <span><i class="fas fa-map"></i> Pengajuan Proposal</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-map"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small-box bg-warning" style="opacity: 0.75">
                        <div class="inner">
                            <div class="inline">
                                <h6 class="mb-0">Terdapat</h6>
                                <h3>{{ $dash_data['pengajuan_ta'] }}</h3>
                                <span><i class="fas fa-layer-group"></i> Pengajuan Tugas Akhir</span>
                            </div>
                        </div>
                        <div class="icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@include('administrasi._footer')
