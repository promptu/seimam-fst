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
                <div class="card card-outline card-warning">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover table-striped table-sm">
                            <thead class="bg-dark">
                                <tr>
                                    <td>#</td>
                                    <td>Nama Syarat Ujian</td>
                                    <td width="180px">Berkas</td>
                                    <td width="150px">Status Validasi</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp
                                @foreach ($syarat_ujian as $r)
                                    @php
                                        $detail = \App\Models\TaDataSyaratUjianUploadMdl::where(
                                            'ta_data_id',
                                            $id_proposal,
                                        )
                                            ->where('ta_data_syarat_ujian_id', $r['id'])
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $r['nama'] }}</td>
                                        <td>
                                            @if ($r['berkas'])
                                                <a href="#" class="bview btn btn-info"
                                                    data-nm="{{ $r['nama'] }}"
                                                    data-url="{{ url($r['berkas']) }}"><i class="fas fa-eye"></i>
                                                    Tampilkan</a>
                                            @else
                                                <span class="badge badge-info"><i
                                                        class="fas fa-exclamation-triangle"></i> Belum diunggah</span>
                                            @endif
                                        </td>
                                        <td>

                                            @if ($get['status_berkas'] == 'PENGAJUAN')
                                                <select class="form-control optval" name="optval-{{ $r['id'] }}"
                                                    id="optval-{{ $r['id'] }}">
                                                    <option value="">- Pilih -</option>
                                                    @foreach ($mylib::is_valid() as $c)
                                                        <option value="{{ $c['id'] }}">{{ $c['val'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {!! $mylib::is_valid($detail ? $detail->is_valid : '', 'lbl') !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($no == 0)
                                    <tr>
                                        <td colspan="5" align="center">Syarat ujian belum ditetapkan untuk program
                                            studi <b>"{{ $get['prodi_nama'] }}"</b>, Jenis Ta.
                                            <b>"{{ $get['ta_jenis_nama'] }}"</b></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer p-2 row">
                        <div class="col-lg-8"><b>Status Pengajuan : </b><br>
                            @if ($get['status_berkas'] == 'PENGAJUAN')
                                Menunggu validasi
                            @elseif($get['status_berkas'] == 'VALID')
                                Berkas Valid
                            @elseif($get['status_berkas'] == 'INVALID')
                                Berkas yang diajukan dinyatakan tidak valid
                            @else
                                Silahkan upload berkas sesuai persyaratan, dan ajukan berkas untuk divalidasi.
                            @endif
                        </div>
                        <div class="col-lg text-right"><a href="#" id="btn-aju"
                                class="btn btn-primary ml-auto {{ $get['status_berkas'] != 'PENGAJUAN' ? 'disabled' : '' }}"><i
                                    class="fas fa-cogs"></i> Proses Validasi</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="mdview" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header p-3">
                <div class="modal-title font-weight-bold"><i class="fas fa-file-pdf"></i> <span id="mdview-nm"></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0"><iframe id="mdview-file" src="" frameborder="0" width="100%"
                    height="600px"></iframe></div>
        </div>
    </div>
</div>

<div id="mdaju" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-3">
                <div class="modal-title font-weight-bold text-primary"><i class="fas fa-question-circle"></i> Konfirmasi
                </div>
            </div>
            <div class="modal-body p-3">
                <p class="mb-2">Kirim hasil validasi, lanjutkan ?</p><i>Perhatian : Cek kesesuaian berkas yang
                    diunggah dan status validasi.</i>
            </div>
            <div class="modal-footer justify-content-between p-2">
                <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><i
                        class="fas fa-times-circle"></i> Tutup</a>
                <button id="mdaju-btn" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim
                    Validasi</button>
            </div>
        </div>
    </div>
</div>



@section('addonjs')
    <script>
        $('.bview').click(function(e) {
            e.preventDefault();
            const nm = $(this).data('nm');
            const url = $(this).data('url');
            $('#mdview-file').attr('src', url);
            $('#mdview-nm').text(nm);
            $('#mdview').modal('show');
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
