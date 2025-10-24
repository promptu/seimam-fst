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
                <form action="#" method="post" id="fr0" autocomplete="off">
                    <div class="card card-outline card-warning">
                        <div class="card-body pb-2 row">

                            <div class="col-lg text-right border-bottom pb-3">
                                <a href="{{ url($ctr_path) }}" class="btn btn-default btn-sm"><i
                                        class="fas fa-times-circle"></i> Batal</a>
                                <a href="#" id="badd" class="btn btn-primary"><i
                                        class="fas fa-paper-plane"></i>
                                    {{ $form['act'] == 'edit' ? 'Update Data' : 'Simpan Data' }}</a>
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col-lg-6">
                                <div class="row mb-2">
                                    <label class="col-lg-4 label-fr">Bimbingan Ke :</label>
                                    <div class="col-lg-5">
                                        <div class="box-fr">{{ $form['in6'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-2">
                                    <label class="col-lg-4 label-fr">Pembimbing :</label>
                                    <div class="col-lg">
                                        <select name="in1" id="in1" class="form-control"
                                            {{ $form['act'] == 'edit' ? 'disabled' : '' }}>
                                            <option value="">- Pilih Pembimbing -</option>
                                            @foreach ($cmb['dosen_pembimbing'] as $c)
                                                <option value="{{ $c['id'] }}"
                                                    {{ $c['id'] == $form['in1'] ? 'selected' : '' }}>
                                                    {{ $mylib::nama_gelar($c['pegawai_gelar_depan'], $c['val'], $c['pegawai_gelar_belakang']) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-2">
                                    <label class="col-lg-4 label-fr">Tanggal Bimbingan :</label>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <input type="text" name="in2" id="in2"
                                                class="form-control datepicker" value="{{ $form['in2'] }}">
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
                                        <input type="text" name="in3" id="in3" class="form-control"
                                            value="{{ $form['in3'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row mb-2">
                                    <label class="col-lg-2" style="padding-left: 2px;">Bahasan :</label>
                                    <div class="col-lg">
                                        <textarea name="in4" id="in4" class="summernote">{{ $form['in4'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row mb-2">
                                    <label class="col-lg-2" style="padding-left: 3px">Lampiran :</label>
                                    <div class="col-lg">
                                        <input type="file" name="in5" id="in5"
                                            class="btn btn-primary input-file">
                                        <span class="text-info">Silahkan lampirkan file PDF (Maksimal 5MB)</span><br>
                                        <iframe type="application/pdf" id="input-file-preview"
                                            src="{{ $form['in5'] }}" width="100%" height="400" class="mt-1"
                                            style="border: solid 1px #ccc; border-radius: 5px;"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('addonjs')
    <script>
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
        });

        let default_pdf_url = "{{ $form['in5'] }}";

        $('#in5').on('change', function(event) {
            const file = this.files[0];
            const maxsize = 5 * 1024 * 1024;
            if (file) {
                if (file.type !== 'application/pdf') {
                    toast('info', 'Perhatian!<br>Silahkan pilih file PDF (Maksimal 5MB)');
                    return;
                }
                if (file.size > maxsize) {
                    toast('info', 'Perhatian!<br>Berkas terlalu besar (Maksimal 5MB)');
                    return;
                }
                const fileUrl = URL.createObjectURL(file);
                $('#input-file-preview').attr('src', fileUrl);
            } else {
                $('#input-file-preview').attr('src', default_pdf_url);
            }
        });

        $('#in4').summernote({
            height: 120,
            toolbar: [
                // Specify only the buttons you need, excluding image upload
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
                onInit: function() {
                    $('.note-editor').css('margin-bottom', '0px');
                },
                onImageUpload: function(files) {
                    if (files && files.length > 0) {
                        toast('info', 'Maaf,<br>Tidak bisa melampirkan gambar.');
                    }
                },
                onPaste: function(e) {
                    const clipboardData = (e.originalEvent || e).clipboardData;
                    if (clipboardData && clipboardData.items) {
                        for (let i = 0; i < clipboardData.items.length; i++) {
                            if (clipboardData.items[i].type.indexOf('image') !== -1) {
                                toast('info', 'Maaf,<br>Tidak bisa melampirkan gambar.');
                                return;
                            }
                        }
                    }
                }
            }
        });

        $('#badd').click(function(e) {
            e.preventDefault();
            const bid = $(this);
            const bval = bid.html();
            let formData = new FormData();
            formData.append('_token', token);
            formData.append('act', "{{ $form['act'] }}");
            formData.append('in0', "{{ $form['in0'] }}");
            formData.append('in1', $('#in1').val());
            formData.append('in2', convertdate($('#in2').val()));
            formData.append('in3', $('#in3').val());
            formData.append('in4', $('#in4').val());
            formData.append('in5', $('#in5')[0].files[0]);
            $.ajax({
                url: "{{ url($ctr_path . ($form['act'] == 'edit' ? '/update' : '/save')) }}",
                type: 'post',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    bid.html(loading).attr('disabled', true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == 'success') {
                        setTimeout(() => {
                            window.location.replace("{{ url($ctr_path) }}");
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

        function convertdate(str) {
            const [one, two, three] = str.split('-');
            return `${three}-${two}-${one}`;
        }
    </script>
@endsection
@section('addon_footer')
    <script src="{{ url('assets/bo/plugins/summernote/summernote-bs4.min.js') }} "></script>
@endsection
@include('administrasi._footer')
