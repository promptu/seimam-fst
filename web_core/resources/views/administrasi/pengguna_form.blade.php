@include('administrasi._header')
<div class="content">
    <div class="container">
        @if ($state == 'edit' || $state == '')
            <form action="#" method="post" id="fr0" autocomplete="off">
                <div class="card card-outline card-warning">
                    <div class="card-footer p-2 text-right">
                        <a href="{{ url($ctr_path) }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i>
                            Kembali</a>
                        @if ($state == 'edit')
                            <a href="#" id="bdel" class="btn btn-danger btn-sm"><i
                                    class="fas fa-trash-alt"></i>
                                Hapus</a>
                            <button type="submit" id="fr0-btn" class="btn btn-primary btn-sm"><i
                                    class="fas fa-edit"></i>
                                Update</button>
                        @else
                            <button type="submit" id="fr0-btn" class="btn btn-primary btn-sm"><i
                                    class="fas fa-paper-plane"></i> Simpan</button>
                        @endif
                    </div>
                </div>
                <div class="card card-outline card-secondary">
                    <div class="card-body">
                        <input type="hidden" name="in0" id="in0" class="form-control"
                            value="{{ $fr['in0'] }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-2">
                                    <h6><u>Data Pengguna :</u></h6>
                                </div>
                                <div class="row mb-2">
                                    <label for="in1" class="col-lg-4 label-fr">Username :</label>
                                    <div class="col-lg-5">
                                        <input type="text" name="in1" id="in1" class="form-control"
                                            value="{{ $fr['in1'] }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="in2" class="col-lg-4 label-fr">Nama Lengkap :</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="in2" id="in2" class="form-control"
                                            value="{{ $fr['in2'] }}">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="in3" class="col-lg-4 label-fr">Email :</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="in3" id="in3" class="form-control"
                                            value="{{ $fr['in3'] }}">
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <label for="in4" class="col-lg-4 label-fr">Default Password :</label>
                                    <div class="col-lg-4">
                                        <input type="text" name="in4" id="in4" class="form-control"
                                            value="{{ $fr['in4'] }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-7 offset-lg-4">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="in4t" class="" value="">
                                            <label for="in4t">&nbsp;Generate Default Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-2">Catatan :</div>
                                    <div class="col-lg-9">Username, Nama Lengkap dan Email akan terisi otomatis jika
                                        dikaitkan dengan <b>Data Pengguna / Data Mahasiswa</b></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-2">
                                    <h6><u>Kaitkan dengan :</u></h6>
                                </div>
                                <div class="row mb-2">
                                    <label for="in5" class="col-lg-4 label-fr">Pegawai :</label>
                                    <div class="col-lg-7">
                                        <select name="in6" id="in6" class=" select2" style="width: 100%">
                                            @if ($fr['in6'] != '')
                                                <option value="{{ $fr['in6'] }}" selected>{{ $fr['in6nm'] }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="in7" class="col-lg-4 label-fr">Mahasiswa :</label>
                                    <div class="col-lg-7">
                                        <select name="in7" id="in7" class=" select2" style="width: 100%">
                                            @if ($fr['in7'] != '')
                                                <option value="{{ $fr['in7'] }}" selected>{{ $fr['in7nm'] }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-7 offset-lg-4">
                                        <a href="#" id="blink" class="btn btn-primary btn-sm"><i
                                                class="fas fa-link"></i> Kaitkan Akun</a>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-7 offset-lg-4">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="in5" class=""
                                                value="{{ $fr['in5'] }}" {{ $fr['in5'] == 'Y' ? 'checked' : '' }}>
                                            <label for="in5">&nbsp;Status Aktif</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <h6><u>Informasi Login :</u></h6>
                                </div>
                                <div class="row mb-2">
                                    <label for="in8" class="col-lg-4 label-fr">Login Terakhir :</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="in8" id="in8" class="form-control"
                                            value="{{ $fr['in8'] }}" disabled>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <label for="in9" class="col-lg-4 label-fr">IP Login Terakhir :</label>
                                    <div class="col-lg-7">
                                        <input type="text" name="in9" id="in9" class="form-control"
                                            value="{{ $fr['in9'] }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
        @if ($state == 'edit')
            @php $no = 0; @endphp
            <div class="card card-outline card-secondary">
                <div class="card-body">
                    <div class="row mb-2">
                        <h6><u>Role Pengguna :</u></h6>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 p-0">
                            <table class="table table-striped table-hover border">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th width="50px">#</th>
                                        <th>Nama Role</th>
                                        <th>Unit Kerja</th>
                                        <th width="70px"><i class="fas fa-cogs"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <select name="role-in1" id="role-in1" class="form-control">
                                                <option value="">- Pilih -</option>
                                                @foreach ($cmb['role'] as $c)
                                                    <option value="{{ $c['id'] }}">{{ $c['val'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="role-in2" id="role-in2" class="form-control">
                                                <option value="">- Pilih -</option>
                                                @foreach ($cmb['unit_kerja'] as $c)
                                                    <option value="{{ $c['id'] }}">
                                                        {{ $mylib::tree_view($c['val'], $c['level']) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button id="role-btn" class="btn btn-primary"><i
                                                    class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    @foreach ($roles as $r)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $r['role_nama'] }}</td>
                                            <td>{{ $mylib::tree_view($r['unit_kerja_nama'], $r['unit_kerja_level']) }}
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-danger bdel-role"
                                                    data-id="{{ $r['id'] }}" data-nm="{{ $r['role_nama'] }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="mddel" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title font-weight-bold text-danger"><i class="fas fa-exclamation-triangle"></i>
                    Konfirmasi</div>
            </div>
            <div class="modal-body">
                <p class="mb-0">Anda akan menghapus Data Pengguna, lanjutkan ?</p>
                <small><i>Perhatian : Operasi ini tidak bisa dipulihkan.</i></small>
            </div>
            <div class="modal-footer p-2 justify-content-between">
                <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>
                    Tutup</a>
                <button class="btn btn-danger" id="mddel-btn"><i class="fas fa-trash-alt"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mddr" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title font-weight-bold text-danger"><i class="fas fa-exclamation-triangle"></i>
                    Konfirmasi</div>
            </div>
            <div class="modal-body">
                <p class="mb-0">Anda akan menghapus Role <b id="mddr-nm"></b>, lanjutkan ?</p>
                <small><i>Perhatian : Operasi ini tidak bisa dipulihkan.</i></small>
            </div>
            <div class="modal-footer p-2 justify-content-between">
                <a href="#" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle"></i>
                    Tutup</a>
                <button class="btn btn-danger" id="mddr-btn"><i class="fas fa-trash-alt"></i> Hapus</button>
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

        $('#in4t').change(function(e) {
            e.preventDefault();
            if ($(this).is(':checked') === true) {
                $('#in4').val('').attr('disabled', true);
            } else {
                $('#in4').attr('disabled', false);
            }
        });

        $('#in6').select2({
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

        $('#in7').select2({
            ajax: {
                url: "{{ url('/cmb-item/mahasiswa') }}",
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
            placeholder: 'Cari NIM / Nama Mahasiswa',
            allowClear: true
        });

        $('#blink').click(function(e) {
            e.preventDefault();
            const id_peg = $('#in6').val();
            const id_mhs = $('#in7').val();
            const bid = $(this);
            $.ajax({
                url: "{{ url($ctr_path . '/get') }}",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'id_peg': id_peg,
                    'id_mhs': id_mhs
                },
                beforeSend: function() {
                    bid.attr('disabled', true);
                },
                success: function(d) {
                    if (d.status == 'success') {
                        $('#in1').val(d.datalist[0]).attr('disabled', true);
                        $('#in2').val(d.datalist[1]).attr('disabled', true);
                        $('#in3').val(d.datalist[2]).attr('disabled', true);
                        $('#in6').attr('disabled', true);
                        $('#in7').attr('disabled', true);
                    } else {
                        bid.attr('disabled', false);
                        toast(d.status, d.statusText);
                    }
                },
                error: function(d) {
                    bid.attr('disabed', false);
                    toasterr(d);
                }
            });
        });

        $('#fr0').submit(function(e) {
            e.preventDefault();
            const in6 = $('#in6').val();
            const datas = {
                '_token': token,
                'act': "{{ $fr['path'] }}",
                'in0': "{{ $fr['in0'] }}",
                'in1': $('#in1').val(),
                'in2': $('#in2').val(),
                'in3': $('#in3').val(),
                'in4': $('#in4').val(),
                'in4t': ($('#in4t').is(':checked')) ? 'Y' : 'T',
                'in5': ($('#in5').is(':checked')) ? 'Y' : 'T',
                'in6': $('#in6').val(),
                'in7': $('#in7').val()
            };
            const bid = $('#fr0-btn');
            const bval = bid.html();
            $.ajax({
                url: "{{ url($ctr_path . '/' . $fr['path']) }}",
                type: 'post',
                dataType: 'json',
                data: datas,
                beforeSend: function() {
                    bid.html(loading).attr('disabled', true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == 'success') {
                        setTimeout(() => {
                            window.location.replace(d.directto);
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

        $('#role-btn').click(function(e) {
            e.preventDefault();
            const in1 = $('#role-in1').val();
            const in2 = $('#role-in2').val();
            const bid = $(this);
            $.ajax({
                url: "{{ url($ctr_path . '/add-role') }}",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'in0': "{{ $fr['in0'] }}",
                    'in1': in1,
                    'in2': in2
                },
                beforeSend: function() {
                    bid.attr('disabled', true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == 'success') {
                        window.location.reload();
                    } else {
                        bid.attr('disabled', false);
                    }
                },
                error: function(d) {
                    toasterr(d);
                    bid.attr('disabled', false);
                }
            });
        });

        $('.bdel-role').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const nm = $(this).data('nm');
            $('#mddr-btn').val(id);
            $('#mddr-nm').text(nm);
            $('#mddr').modal('show');
        });

        $('#mddr-btn').click(function(e) {
            e.preventDefault();
            const id = $(this).val();
            const bid = $(this);
            const bval = bid.html();
            $.ajax({
                url: "{{ url($ctr_path . '/delete-role') }}",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'id': id
                },
                beforeSend: function() {
                    bid.html(loading).attr('disabled', true);
                },
                success: function(d) {
                    toast(d.status, d.statusText);
                    if (d.status == 'success') {
                        window.location.reload();
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

        $('#bdel').click(function(e) {
            e.preventDefault();
            $('#mddel').modal('show');
        });

        $('#mddel-btn').click(function(e) {
            const bid = $(this);
            const bval = bid.html();
            $.ajax({
                url: "{{ url($ctr_path . '/delete') }}",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'id': "{{ $fr['in0'] }}"
                },
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
                    bid.html(bval).attr('disabled', false);
                    toasterr(d);
                }
            });
        });
    </script>
@endsection
@include('administrasi._footer')
