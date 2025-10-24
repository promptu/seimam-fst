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
                        @if ($action != 'edit' && !$is_mahasiswa)
                            <a href="{{ url($ctr_path . '/nilai-akhir/edit/' . $id_page) }}" class="btn btn-warning"><i
                                    class="fas fa-edit"></i> Edit</a>
                        @endif
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
                            $total_nilai_penguji = 0;
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
                                $nil_angka = $r['nilai_angka'] == null ? 0 : $r['nilai_angka'];
                                $total_nilai_penguji += $nil_angka;
                            @endphp
                            <div class="callout callout-warning p-1 mb-1">
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="text-bold pt-1 pl-2">
                                            <i class="fas fa-user-graduate"></i> {{ $cur_penguji }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        @if ($action == 'edit')
                                            <input type="number" min="0" max="100"
                                                class="form-control nilai-penguji" data-id="{{ $r['id'] }}"
                                                value="{{ $nil_angka }}">
                                        @else
                                            <div class="box-fr">{{ $nil_angka }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($no_penguji == 0)
                            <div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i>
                                Belum memiliki penguji.</div>
                        @endif
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
                            $total_nilai_pembimbing = 0;
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
                                $nil_angka = $r['nilai_angka'] == null ? 0 : $r['nilai_angka'];
                                $total_nilai_pembimbing += $nil_angka;
                            @endphp
                            <div class="callout callout-success p-1 mb-1">
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="text-bold pt-1 pl-2">
                                            <i class="fas fa-user-graduate"></i> {{ $cur_pembimbing }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        @if ($action == 'edit')
                                            <input type="number" min="0" max="100"
                                                class="form-control nilai-pembimbing" data-id="{{ $r['id'] }}"
                                                value="{{ $nil_angka }}">
                                        @else
                                            <div class="box-fr">{{ $nil_angka }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @php ++$no_pembimbing; @endphp
                        @endforeach
                        @if ($no_pembimbing == 0)
                            <div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i>
                                Belum memiliki dosen pembimbing.</div>
                        @endif
                    </div>
                </div>
                @php
                    $bobot_penguji = $get['bobot_penguji'] == null ? 0 : $get['bobot_penguji'];
                    $bobot_pembimbing = $get['bobot_pembimbing'] == null ? 0 : $get['bobot_pembimbing'];

                    $rerata_penguji = $get['nilai_penguji'] == null ? 0 : $get['nilai_penguji'];
                    $rerata_pembimbing = $get['nilai_pembimbing'] == null ? 0 : $get['nilai_pembimbing'];
                    $final_penguji = round(($bobot_penguji / 100) * $rerata_penguji, 2);
                    $final_pembimbing = round(($bobot_pembimbing / 100) * $rerata_pembimbing, 2);
                    $nilai_final = $final_penguji + $final_pembimbing;
                @endphp
                <div class="card card-outline card-navy table-responsive mb-1">
                    <div class="card-body p-0">
                        <table class="table table-striped table-bordered">
                            <thead class="bg-navy">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Dosen</th>
                                    <th class="text-center" width="80px">Bobot (%)</th>
                                    <th class="text-center">Rerata Nilai Dosen</th>
                                    <th class="text-center">Nilai Total<br>(Bobot x Rerata Nilai Dosen)</th>
                                    <th class="text-center">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Penguji</td>
                                    <td class="text-center">
                                        @if ($action == 'edit')
                                            <input type="number" min="0" max="100" name="bobot-penguji"
                                                id="bobot-penguji" class="form-control"
                                                value="{{ $bobot_penguji }}">
                                        @else
                                            <b>{{ $bobot_penguji }}</b>
                                        @endif
                                    </td>
                                    <td class="text-center"><b id="rerata-penguji">{{ $rerata_penguji }}</b></td>
                                    <td class="text-center px-0"><b id="total-penguji">{{ $final_penguji }}</b></td>
                                    <td class="text-center" rowspan="2" class="text-center"><b
                                            style="font-size:24px;" id="nilai-final">{{ $nilai_final }}</b></td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Pembimbing</td>
                                    <td class="text-center">
                                        @if ($action == 'edit')
                                            <input type="number" min="0" max="100"
                                                name="bobot-pembimbing" id="bobot-pembimbing" class="form-control"
                                                value="{{ $bobot_pembimbing }}">
                                        @else
                                            <b>{{ $bobot_pembimbing }}</b>
                                        @endif
                                    </td>
                                    <td class="text-center"><b id="rerata-pembimbing">{{ $rerata_pembimbing }}</b>
                                    </td>
                                    <td class="text-center px-0"><b id="total-pembimbing">{{ $final_pembimbing }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body pb-2 row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row mb-2">
                                        <label class="col-lg-4 label-fr-dark">Status Lulus :</label>
                                        <div class="col-lg-4">
                                            @if ($action == 'edit')
                                                <select name="status-lulus" id="status-lulus" class="form-control">
                                                    <option value="">- Pilih -</option>
                                                    <option value="LULUS"
                                                        {{ $get['status_lulus'] == 'LULUS' ? 'selected' : '' }}>Lulus
                                                    </option>
                                                    <option value="GAGAL"
                                                        {{ $get['status_lulus'] == 'GAGAL' ? 'selected' : '' }}>Tidak
                                                        Lulus</option>
                                                </select>
                                            @else
                                                <div class="box-fr">{{ $get['status_lulus'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($action == 'edit')
                    <div class="card">
                        <div class="card-body p-2 text-right">
                            <a href="{{ url($ctr_path . '/nilai-akhir/detail/' . $id_page) }}"
                                class="btn btn-warning"><i class="fas fa-times-circle"></i> Batal</a>
                            <button type="button" id="bupdate" class="btn btn-primary"><i
                                    class="fas fa-edit"></i> Update</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('addonjs')
    <script>
        $('input[type=number]').focus(function() {
            $(this).select()
        });
        $('input[type=number]').focusin(function() {
            $(this).select()
        });

        const no_penguji = Number({{ $no_penguji }});
        const no_pembimbing = Number({{ $no_pembimbing }});
        let bobot_penguji = Number({{ $bobot_penguji }});
        let bobot_pembimbing = Number({{ $bobot_pembimbing }});
        let rerata_penguji = Number({{ $rerata_penguji }});
        let rerata_pembimbing = Number({{ $rerata_pembimbing }});
        let final_penguji = Number({{ $final_penguji }});
        let final_pembimbing = Number({{ $final_pembimbing }});
        let nilai_final = Number({{ $nilai_final }});

        $('input[type=number]').change(function(e) {
            e.preventDefault();
            if ($(this).val() == '') {
                $(this).val(0);
            }
            if ($(this).val() > 100) {
                $(this).val(0);
            }

            hitung_nilai_final();
        });

        function hitung_nilai_final() {
            let total_nilai_penguji = 0;
            $.each($('.nilai-penguji'), function(elm) {
                const v = $(this).val();
                total_nilai_penguji += Number(v);
            });
            let total_nilai_pembimbing = 0;
            $.each($('.nilai-pembimbing'), function(elm) {
                const v = $(this).val();
                total_nilai_pembimbing += Number(v);
            });
            bobot_penguji = Number($('#bobot-penguji').val());
            bobot_pembimbing = Number($('#bobot-pembimbing').val());

            rerata_penguji = Number(total_nilai_penguji / no_penguji).toFixed(2);
            rerata_pembimbing = Number(total_nilai_pembimbing / no_pembimbing).toFixed(2);
            final_penguji = Number((bobot_penguji / 100) * rerata_penguji).toFixed(2);
            final_pembimbing = Number((bobot_pembimbing / 100) * rerata_pembimbing).toFixed(2);
            nilai_final = Number(final_penguji) + Number(final_pembimbing);
            $('#rerata-penguji').text(rerata_penguji);
            $('#total-penguji').text(final_penguji);
            $('#rerata-pembimbing').text(rerata_pembimbing);
            $('#total-pembimbing').text(final_pembimbing);
            $('#nilai-final').text(nilai_final);
        }

        $('#bupdate').click(function(e) {
            e.preventDefault();
            const penguji = {};
            $.each($('.nilai-penguji'), function(key, elm) {
                const id = $(this).data('id');
                const v = $(this).val();
                penguji[key] = {
                    'id': id,
                    'val': v
                };
            });
            const pembimbing = {};
            $.each($('.nilai-pembimbing'), function(key, elm) {
                const id = $(this).data('id');
                const v = $(this).val();
                pembimbing[key] = {
                    'id': id,
                    'val': v
                };
            });
            const datas = {
                "_token": token,
                "id": "{{ $get['id'] }}",
                "penguji": JSON.stringify(penguji),
                "pembimbing": JSON.stringify(pembimbing),
                "bobot_penguji": $('#bobot-penguji').val(),
                "bobot_pembimbing": $('#bobot-pembimbing').val(),
                "nilai_penguji": rerata_penguji,
                "nilai_pembimbing": rerata_pembimbing,
                "status_lulus": $('#status-lulus').val()
            };
            const bid = $(this);
            const bval = bid.html();
            $.ajax({
                url: "{{ url($ctr_path . '/nilai-akhir/update') }}",
                type: 'post',
                dataType: 'json',
                data: datas,
                beforeSend: function() {
                    bid.html(loading).attr("disabled", true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == 'success') {
                        setTimeout(() => {
                            window.location.replace(
                                "{{ url($ctr_path . '/nilai-akhir/detail/' . $id_page) }}");
                        }, 500);
                    }
                    bid.html(bval).attr("disabled", false);
                },
                error: function(d) {
                    toasterr(d);
                    bid.html(bval).attr("disabled", false);
                }
            });
        });
    </script>
@endsection
@include('administrasi._footer')
