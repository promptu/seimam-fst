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
                            @php++$no_pembimbing;
                            @endphp
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
                                <div class="col-lg">
                                    <a href="#" id="bedit" class="btn btn-primary"><i
                                            class="fas fa-calendar-alt"></i> Edit Jadwal dan Ruang Ujian</a>
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
                                <div class="col-lg-4 text-right">
                                    <a href="#" class="bdel-penguji btn btn-danger text-white float-right"
                                        data-id="{{ $r->id }}" data-nm="{{ $cur_penguji }}"><i
                                            class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                        @endforeach
                        @if ($no_penguji == 0)
                            <div class="callout callout-info bg-info mb-1"><i class="fas fa-exclamation-triangle"></i>
                                Belum memiliki penguji.</div>
                        @endif
                        <a href="#" class="btn btn-primary mt-1" id="badd-penguji"><i
                                class="fas fa-plus-circle"></i> Tambah Penguji</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="mdjadwal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-3">
                <div class="modal-title font-weight-bold text-primary"><i class="fas fa-calendar-alt"></i> Ruang dan
                    Jadwal Ujian</div>
            </div>
            <div class="modal-body p-3">
                <div class="row mb-2">
                    <label class="col-lg-12 mb-0">Ruang Ujian :</label>
                    <div class="col-lg-12">
                        <select name="mdjadwal-in1" id="mdjadwal-in1" class="form-control select2" style="width: 100%">
                            <option value="">- Pilih -</option>
                            @foreach ($cmb['ruang'] as $c)
                                <option value="{{ $c['id'] }}">{!! $c['unit_kerja_nama'] . ' - ' . $c['val'] !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-lg-12 mb-0">Jadwal Ujian :</label>
                    <div class="col-lg-5 mb-1">
                        <div class="input-group">
                            <input type="text" name="mdjadwal-in2" id="mdjadwal-in2"
                                class="form-control datepicker" placeholder="thn-bln-tgl" autocomplete="off">
                            <div class="input-group-append"><span class="input-group-text"><i
                                        class="fas fa-calendar-alt"></i></span></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <input type="time" name="mdjadwal-in3" id="mdjadwal-in3" class="form-control">
                    </div>
                    <div class="col-lg text-center"><span class="badge badge-info">s/d</span></div>
                    <div class="col-lg-3">
                        <input type="time" name="mdjadwal-in4" id="mdjadwal-in4" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i
                        class="fas fa-times-circle"></i> Tutup</a>
                <button id="mdjadwal-btn" class="btn btn-primary"><i class="far fa-calendar-check"></i> Set
                    Jadwal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdpenguji" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-3">
                <div class="modal-title font-weight-bold text-primary"><i class="fas fa-user-graduate"></i> Tambah
                    Penguji</div>
            </div>
            <div class="modal-body p-3">
                <div class="row mb-2">
                    <label class="col-lg-12 mb-0">Penguji :</label>
                    <div class="col-lg-12">
                        <select name="mdpenguji-in1" id="mdpenguji-in1" class="form-control select2"
                            style="width: 100%"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i
                        class="fas fa-times-circle"></i> Tutup</a>
                <button id="mdpenguji-btn" class="btn btn-primary"><i class="far fa-paper-plane"></i> Tambahkan
                    Penguji</button>
            </div>
        </div>
    </div>
</div>

<div id="mdkonf" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-3">
                <div class="modal-title font-weight-bold text-danger"><i class="fas fa-question-circle"></i>
                    Konfirmasi</div>
            </div>
            <div class="modal-body p-3">
                <p class="mb-2">Hapus penguji <b id="mdkonf-nm"></b> ?</p><i>Perhatian : Operasi ini tidak bisa
                    dipulihkan.</i>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i
                        class="fas fa-times-circle"></i> Tutup</a>
                <button id="mdkonf-btn" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>



@section('addonjs')
    <script>
        $('.select2').select2();
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $('#bedit').click(function(e) {
            $('#mdjadwal-in1').val("{{ $get['ta_ruang_ujian_kode'] }}").trigger('change');
            $('#mdjadwal-in2').val("{{ $tgl_ujian }}");
            $('#mdjadwal-in3').val("{{ $jam_mulai }}");
            $('#mdjadwal-in4').val("{{ $jam_selesai }}");
            $('#mdjadwal').modal('show');
        });
        $('#mdjadwal-btn').click(function(e) {
            e.preventDefault();
            const datas = {
                "_token": token,
                "in0": "{{ $get['id'] }}",
                "in1": $('#mdjadwal-in1').val(),
                "in2": $('#mdjadwal-in2').val(),
                "in3": $('#mdjadwal-in3').val(),
                "in4": $('#mdjadwal-in4').val()
            };
            const bid = $(this);
            const bval = bid.html();
            $.ajax({
                url: "{{ url($ctr_path . '/update') }}",
                type: "post",
                dataType: "json",
                data: datas,
                beforeSend: function() {
                    bid.html(loading).attr("disabled", true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == "success") {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        bid.html(bval).attr("disabled", false);
                    }
                },
                error: function(d) {
                    toasterr(d);
                    bid.html(bval).attr("disabled", false);
                }
            });
        });

        $('#badd-penguji').click(function(e) {
            e.preventDefault();
            const tgl_ujian = "{{ $tgl_ujian }}";
            if (tgl_ujian == "") {
                toast("info", "Set jadwal terlebih dahulu.");
                return;
            }
            $('#mdpenguji').modal('show');
        });
        $('#mdpenguji-in1').select2({
            ajax: {
                url: "{{ url('/cmb-item/pegawai') }}",
                dataType: 'json',
                delay: 1000,
                data: function(params) {
                    return {
                        q: params.term
                    }
                },
                processResult: function(data) {
                    return {
                        results: data.items
                    };
                }
            },
            minimumInputLength: 3,
            placeholder: 'Cari NIP / Nama Pegawai',
            allowClear: true
        });
        $('#mdpenguji-btn').click(function(e) {
            e.preventDefault();
            const datas = {
                "_token": token,
                "in0": "{{ $get['id'] }}",
                "in1": $('#mdpenguji-in1').val()
            };
            const bid = $(this);
            const bval = bid.html();
            $.ajax({
                url: "{{ url($ctr_path . '/add-penguji') }}",
                type: "post",
                dataType: "json",
                data: datas,
                beforeSend: function() {
                    bid.html(loading).attr("disabled", true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == "success") {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        bid.html(bval).attr("disabled", false);
                    }
                },
                error: function(d) {
                    toasterr(d);
                    bid.html(bval).attr("disabled", false);
                }
            });
        });

        $('.bdel-penguji').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const nm = $(this).data('nm');
            $('#mdkonf-nm').text(nm);
            $('#mdkonf-btn').val(id);
            $('#mdkonf').modal('show');
        });

        $('.bview').click(function(e) {
            e.preventDefault();
            const nm = $(this).data('nm');
            const url = $(this).data('url');
            $('#mdview-file').attr('src', url);
            $('#mdview-nm').text(nm);
            $('#mdview').modal('show');
        });
        $('#mdkonf-btn').click(function(e) {
            e.preventDefault();
            const bid = $(this);
            const bval = bid.html();
            const datas = {
                "_token": token,
                "id": bid.val()
            };
            $.ajax({
                url: "{{ url($ctr_path . '/delete-penguji') }}",
                type: "post",
                dataType: "json",
                data: datas,
                beforeSend: function() {
                    bid.html(loading).attr("disabled", true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == "success") {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        bid.html(bval).attr("disabled", false);
                    }
                },
                error: function(d) {
                    toasterr(d);
                    bid.html(bval).attr("disabled", false);
                }
            });
        });

        $('#btn-aju').click(function(e) {
            e.preventDefault();
            $('#mdaju').modal('show');
        });
        $('#mdaju-btn').click(function(e) {
            const mid = "{{ $id_proposal }}";
            const bid = $(this);
            const bval = bid.html();

            const cmbdata = {};
            const optval = $('.optval');
            optval.each(function(index) {
                const id = $(this).attr('id');
                const splitid = id.split("-");
                const val = $(this).val();
                cmbdata[splitid[1]] = val;
            });
            const formdata = {
                "_token": token,
                "id": "{{ $id_proposal }}",
                "in": cmbdata
            };
            console.log(formdata);

            $.ajax({
                url: "{{ url($ctr_path . '/save') }}",
                type: 'POST',
                dataType: 'json',
                data: formdata,
                beforeSend: function() {
                    bid.html(loading).attr('disabled', true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == 'success') {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        bid.html(bval).attr('disabled', false);
                    }
                },
                error: function(d) {
                    toasterr(d);
                    bid.html(bval).attr('disabled', false);
                }
            });
        });
    </script>
@endsection
@include('administrasi._footer')
