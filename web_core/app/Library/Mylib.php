<?php

namespace App\Library;

class Mylib {

	public static function nama_gelar($gelar_depan, $nama, $gelar_belakang){
		$res = '';
		$gelar_depan = trim($gelar_depan);
		if ($gelar_depan) {
			$res .= (substr($gelar_depan, -1) != '.') ? $gelar_depan.'. ' : $gelar_depan.' ';
		}
		$res .= $nama;
		$res .= ($gelar_belakang) ? ', '.$gelar_belakang : '';
		return $res;
	}


  public static function jenis_kelamin($str){
    $arr = [
      'L'=>'Laki-laki',
      'P'=>'Perempuan',
    ];
    return (isset($arr[$str])) ? $arr[$str] : $str;
  }


	public static function is_aktif($id='',$tipe=''){
    $arr = [
			'Y'=>['id'=>'Y','val'=>'Aktif','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-check-circle"></i> Aktif</span>'],
			'T'=>['id'=>'T','val'=>'Non-Aktif','lbl'=>'<span class="badge badge-danger p-xs"><i class="fas fa-times-circle"></i> Non-Aktif</span>'],
		];
    if ($tipe == '') { 
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}

	public static function is_admin($id='',$tipe=''){
    $arr = [
			'Y'=>['id'=>'Y','val'=>'Ya','lbl'=>'<i class="fas fa-check"></i>'],
			'T'=>['id'=>'T','val'=>'Tidak','lbl'=>'<i class="fas fa-times"></i>'],
		];
    if ($tipe == '') { 
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}

	public static function is_edit($id='',$tipe=''){
    $arr = [
			'Y'=>['id'=>'Y','val'=>'Ya','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-check-circle"></i> Ya</span>'],
			'T'=>['id'=>'T','val'=>'Tidak','lbl'=>'<span class="badge badge-danger p-xs"><i class="fas fa-times-circle"></i> Tidak</span>'],
		];
    if ($tipe == '') { 
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}


	public static function is_default($id='',$tipe=''){
    $arr = [
			'Y'=>['id'=>'Y','val'=>'<i class="fas fa-check text-success"></i>','lbl'=>'<i class="fas fa-check text-success"></i>'],
			'T'=>['id'=>'T','val'=>'<i class="fas fa-times text-danger"></i>','lbl'=>'<i class="fas fa-times text-danger"></i>'],
		];
    if ($tipe == '') { 
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}


	public static function tree_view($str, $level=1){
		return (($level > 1) ? str_repeat('...... ', $level-1) : '').$str;
	}


	public static function is_akademik($id='',$tipe=''){
    $arr = [
			'Y'=>['id'=>'Y','val'=>'Akademik','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-graduation-cap"></i> Akademik</span>'],
			'T'=>['id'=>'T','val'=>'Non-Akademik','lbl'=>'<span class="badge badge-info p-xs"><i class="fas fa-university"></i> Non-Akademik</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}


	public static function is_valid($id='',$tipe=''){
    $arr = [
			'Y'=>['id'=>'Y','val'=>'Valid','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-check-circle"></i> Valid</span>'],
			'T'=>['id'=>'T','val'=>'Tidak Valid','lbl'=>'<span class="badge badge-danger p-xs"><i class="fas fa-times-circle"></i> Tidak Valid</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return ($tipe=='lbl') ? '<span class="badge badge-secondary"><i class="fas fa-hourglass"></i> Belum divalidasi</span>' : 'Belum divalidasi';
      }
    }
	}

	public static function bak_status_pengajuan($id='',$tipe=''){
    $arr = [
			'DRAFT'=>['id'=>'DRAFT','val'=>'Draft','lbl'=>'<span class="badge badge-secondary p-xs"><i class="fas fa-file"></i> Draft</span>'],
			'PENGAJUAN'=>['id'=>'PENGAJUAN','val'=>'Pengajuan','lbl'=>'<span class="badge badge-info p-xs"><i class="fas fa-hourglass-end"></i> Pengajuan</span>'],
			'PROSES'=>['id'=>'PROSES','val'=>'Diproses','lbl'=>'<span class="badge badge-primary p-xs"><i class="far fa-hourglass"></i> Diproses</span>'],
			'SELESAI'=>['id'=>'SELESAI','val'=>'Selesai','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-check-circle"></i> Tidak Valid</span>'],
			'TOLAK'=>['id'=>'TOLAK','val'=>'Ditolak','lbl'=>'<span class="badge badge-danger p-xs"><i class="fas fa-times"></i> Ditolak</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return ($tipe=='lbl') ? '<span class="badge badge-secondary"><i class="fas fa-hourglass"></i> Belum diproses</span>' : 'Belum diproses';
      }
    }
	}


	public static function status_berkas($id='',$tipe=''){
    $arr = [
			'PENGAJUAN'=>['id'=>'PENGAJUAN','val'=>'Pengajuan','lbl'=>'<span class="badge badge-info p-xs"><i class="fas fa-hourglass-end"></i> Pengajuan</span>'],
			'VALID'=>['id'=>'VALID','val'=>'Valid','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-check-circle"></i> Valid</span>'],
			'INVALID'=>['id'=>'INVALID','val'=>'Tidak Valid','lbl'=>'<span class="badge badge-danger p-xs"><i class="fas fa-times"></i> Tidak Valid</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return ($tipe=='lbl') ? '<span class="badge badge-secondary"><i class="fas fa-hourglass"></i> Belum divalidasi</span>' : 'Belum divalidasi';
      }
    }
	}


	public static function status_lulus($id='',$tipe=''){
    $arr = [
			'BLANK'=>['id'=>'BLANK','val'=>'Belum Terjadwal','lbl'=>'<span class="badge badge-secondary p-xs"><i class="fas fa-hourglass-end"></i> Belum Terjadwal</span>'],
			'TERJADWAL'=>['id'=>'TERJADWAL','val'=>'Terjadwal','lbl'=>'<span class="badge badge-info p-xs"><i class="far fa-clock"></i> Terjadwal</span>'],
			'LULUS'=>['id'=>'LULUS','val'=>'Lulus','lbl'=>'<span class="badge badge-success p-xs"><i class="fas fa-check-circle"></i> Lulus</span>'],
			'GAGAL'=>['id'=>'GAGAL','val'=>'TIdak Lulus','lbl'=>'<span class="badge badge-danger p-xs"><i class="fas fa-times-times"></i> TIdak Lulus</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return ($tipe=='lbl') ? '<span class="badge badge-secondary"><i class="fas fa-hourglass"></i> Belum Terjadwal</span>' : 'Belum Terjadwal';
      }
    }
	}


	public static function status_data($id='',$tipe=''){
    $arr = [
			'new'=>['id'=>'new','val'=>'NEW','lbl'=>'<span class="badge badge-info p-1"><i class="fas fa-spinner"></i> NEW</span>'],
			'sent'=>['id'=>'sent','val'=>'SENT','lbl'=>'<span class="badge badge-success p-1"><i class="fas fa-check-circle"></i> SENT</span>'],
			'error'=>['id'=>'error','val'=>'ERROR','lbl'=>'<span class="badge badge-danger p-1"><i class="fas fa-times-circle"></i> ERROR</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}


	public static function periode_bulan($id='',$tipe=''){
    $arr = [
			'01'=>['id'=>'01','val'=>'Januari','lbl'=>'<span class="badge badge-info p-1"></i> Januari</span>'],
			'02'=>['id'=>'02','val'=>'Februari','lbl'=>'<span class="badge badge-info p-1"></i> Februari</span>'],
			'03'=>['id'=>'03','val'=>'Maret','lbl'=>'<span class="badge badge-info p-1"></i> Maret</span>'],
			'04'=>['id'=>'04','val'=>'April','lbl'=>'<span class="badge badge-info p-1"></i> April</span>'],
			'05'=>['id'=>'05','val'=>'Mei','lbl'=>'<span class="badge badge-info p-1"></i> Mei</span>'],
			'06'=>['id'=>'06','val'=>'Juni','lbl'=>'<span class="badge badge-info p-1"></i> Juni</span>'],
			'07'=>['id'=>'07','val'=>'Juli','lbl'=>'<span class="badge badge-info p-1"></i> Juli</span>'],
			'08'=>['id'=>'08','val'=>'Agustus','lbl'=>'<span class="badge badge-info p-1"></i> Agustus</span>'],
			'09'=>['id'=>'09','val'=>'September','lbl'=>'<span class="badge badge-info p-1"></i> September</span>'],
			'10'=>['id'=>'10','val'=>'Oktober','lbl'=>'<span class="badge badge-info p-1"></i> Oktober</span>'],
			'11'=>['id'=>'11','val'=>'November','lbl'=>'<span class="badge badge-info p-1"></i> November</span>'],
			'12'=>['id'=>'12','val'=>'Desember','lbl'=>'<span class="badge badge-info p-1"></i> Desember</span>'],
		];
    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
	}


  public static function indocur($str, $prefix=''){
    return $prefix.number_format($str, 0);
  }


	public static function indotgl($str){
    $bln = [
      '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni',
      '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
    ];
		$exp = explode('-', $str);
		if (count($exp) == 3) {
			return $exp['2'].' '.$bln[$exp['1']].' '.$exp['0'];
		} else {
			return $str;
		}
	}


  public static function switch_tgl($str, $format='long'){
    $bln = [
      '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni',
      '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
    ];
    $exp = explode('-', $str);
    if (count($exp) == 3) {
      return $exp[2].'-'.(($format == 'short') ? $exp[1] : $bln[$exp[1]]).'-'.$exp[0];
    }
    return $str;
  }


  public static function ppg(){
    return [
      ['id'=>10, 'val'=>'10 baris'],
      ['id'=>50, 'val'=>'50 baris'],
      ['id'=>100, 'val'=>'100 baris'],
    ];
  }


	public static function validasi($string){
		$rule_name = [
			'required' => ':attribute harus diisi.',
			'required_if' => ':attribute harus diisi.',
			'alpha' => ':attribute harus berupa huruf.',
			'numeric' => ':attribute harus berupa angka.',
			'alpha_num' => ':attribute hanya huruf dan angka.',
			'captcha' => ':attribute tidak cocok.',
			'string' => ':attribute harus berupa string.',
			'date' => ':attribute harus berupa tanggal.',
			'date_format' => 'Format :attribute tidak sesuai.',
			'file' => 'Silahkan upload :attribute.',
			'digits' => ':attribute harus :digits angka.',
			'email' =>':attribute tidak valid.',
			'regex' => ':attribute harus mengandung :regex.',
			'min' => ':attribute minimal :min karakter.',
		];
		return isset($rule_name[$string])?$rule_name[$string]:'Pesan validasi tidak ditemukan.';
	}


	public static function validasi_import($string){
		$rule_name = [
			'required' => ':attribute harus diisi.',
			'required_if' => ':attribute harus diisi.',
			'alpha' => ':attribute harus berupa huruf.',
			'numeric' => ':attribute harus berupa angka.',
			'alpha_num' => ':attribute hanya huruf dan angka.',
			'captcha' => ':attribute tidak cocok.',
			'string' => ':attribute harus berupa string.',
			'date' => ':attribute harus berupa tanggal.',
			'date_format' => 'Format :attribute tidak sesuai.',
			'file' => 'Silahkan upload :attribute.',
			'digits' => ':attribute harus :digits angka.',
			'email' =>':attribute tidak valid.',
			'regex' => ':attribute harus mengandung :regex.',
			'min' => ':attribute minimal :min karakter.',
		];
		return isset($rule_name[$string])?$rule_name[$string]:'Pesan validasi tidak ditemukan.';
	}


  public static function pesan($grup, $string, $cust_msg = ''){
    $arr = [
      'success'=>[
        'save' => '<b class="text-success">Sukses!</b><br>Data berhasil disimpan.',
        'update' => '<b class="text-success">Sukses!</b><br>Data berhasil diupdate.',
        'get' => '<b class="text-success">Sukses!</b><br>Data berhasil dimuat.',
        'delete' => '<b class="text-success">Sukses!</b><br>Data berhasil dihapus.',
        'reset' => '<b class="text-success">Sukses!</b><br>Password telah direset.',
        'import' => '<b class="text-success">Sukses!</b><br>Data berhasil diimport.',
        'custom' => '<b class="text-success">Sukses!</b><br>'.$cust_msg,
      ],
      'fail'=>[
        'save' => '<b class="text-warning">Perhatian!</b><br>Gagal menyimpan data.',
        'update' => '<b class="text-warning">Perhatian!</b><br>Gagal mengupdate data.',
        'get' => '<b class="text-warning">Perhatian!</b><br>Data tidak ditemukan.',
        'delete' => '<b class="text-warning">Perhatian!</b><br>Gagal menghapus data.',
        'reset' => '<b class="text-warning">Perhatian!</b><br>Gagal reset password.',
        'import' => '<b class="text-warning">Perhatian!</b><br>Gagal import data.',
        'custom' => '<b class="text-warning">Perhatian!</b><br>'.$cust_msg,
      ],
      'duplicate'=>'<b class="text-info">Perhatian!</b><br><b class="text-info">'.$string.'</b> sudah terdaftar.',
    ];
    if ($grup == 'duplicate') {
      return $arr[$grup];
    } else {
      return (isset($arr[$grup][$string])) ? $arr[$grup][$string] : '<b class="text-danger">Kesalahan!</b><br>Pesan tidak ditemukan';
    }
  }


  public static function jenis_unit_kerja($id='', $tipe=''){
		
    $arr = [
      'universitas'=>['id'=>'universitas', 'nama'=>'Universitas', 'val'=>'Universitas'],
      'fakultas'=>['id'=>'fakultas', 'nama'=>'Fakultas', 'val'=>'Fakultas'],
      'prodi'=>['id'=>'prodi', 'nama'=>'Program Studi', 'val'=>'Program Studi'],
    ];

    if ($tipe == '') {
      return $arr;
    } else {
      if (isset($arr[$id])) {
        return (isset($arr[$id][$tipe])) ? $arr[$id][$tipe] : $arr[$id]['val'];
      } else {
        return $id;
      }
    }
  }


	public static function status_proposal($id='', $tipe=''){
		$arr = [
			'pengajuan' => ['id'=>'pengajuan', 'nama'=>'Pengajuan', 'val'=>'Pengajuan'],
			'disetujui' => ['id'=>'disetujui', 'nama'=>'Disetujui', 'val'=>'Disetujui'],
			'ditolak' => ['id'=>'ditolak', 'nama'=>'Ditolak', 'val'=>'Ditolak'],
		];
	}


	public static function status_bimbingan($id='', $tipe=''){
		$arr = [
			'aktif' => ['id'=>'aktif', 'nama'=>'Aktif', 'val'=>'Aktif'],
			'selesai' => ['id'=>'selesai', 'nama'=>'Selesai', 'val'=>'Selesai'],
		];
    return $arr;
	}


  public static function status_disetujui($str){
    $def = '<span class="badge badge-info"><i class="fas fa-clock"></i> Menunggu pembimbing</span>';
    if ($str == '') {
      return $def;
    } else {
      $arr = [
        'setuju'=>['id'=>'setuju', 'val'=>'Setuju/ACC', 'label'=>'<span class="badge badge-success"><i class="fas fa-check-circle"></i> Setuju/ACC</span>'],
        'tolak'=>['id'=>'tolak', 'val'=>'Tolak/TACC', 'label'=>'<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tolak/TACC</span>'],
      ];
      return (isset($arr[$str])) ? $arr[$str]['label'] : $def;
    }
  }

	public static function cmb_status_disetujui($str = ''){
		$arr = [
			'setuju'=>['id'=>'setuju', 'val'=>'Setuju/ACC'],
			'tolak'=>['id'=>'tolak', 'val'=>'Tolak/TACC'],
		];
		if ($str == '') {
			return $arr;
		} else {
			return (isset($arr[$str])) ? $arr[$str]['val'] : $str;
		}
	}

  
}