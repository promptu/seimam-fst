<?php

namespace App\Library;

class Teks {
	
	public static function level_jabatan($string) {
		$rule_name = [
			'0' => 'Pimpinan/Koordinator',
			'1' => 'Kasub/Subkoordinator',
			'2' => 'Staf',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function jekel($string) {
		$rule_name = [
			'L' => 'Pria',
			'P' => 'Wanita',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function sebutan($string) {
		$rule_name = [
			'L' => 'Bpk. ',
			'P' => 'Ibu ',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function act($string) {
		$rule_name = [
			'Y' => 'Aktif',
			'T' => 'Non-Aktif',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function lblingkup($string) {
		$txt = ($string == 'rektorat') ? 'Rektorat' : (($string == 'unit') ? 'Unit' : '-');
		return '<span class="badge badge-info">'.$txt.'</span>';
	}

	public static function lbisverif($string) {
		$rule_name = [
			1 => '<span class="badge badge-success">Ya</span>',
			0 => '<span class="badge badge-danger">Tidak</span>',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function lblact($string) {
		$rule_name = [
			'Y' => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>',
			'T' => '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Non-Aktif</span>',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function lblsuratkeluar($string) {
		$rule_name = [
			'VER'=>'<span class="badge badge-info"></i> Verifikasi</span>',
			'REV'=>'<span class="badge badge-warning"></i> Revisi</span>',
			'FIN'=>'<span class="badge badge-success"></i> Selesai</span>',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function lblstsurat($string) {
		$rule_name = [
			'inpt'=>'<span class="badge badge-secondary"></i> Proses Sortir</span>',
			'ver1'=>'<span class="badge badge-info"></i> Menunggu Verifikasi</span>',
			'ver2'=>'<span class="badge badge-warning"></i> Menunggu Verifikasi</span>',
			'dist'=>'<span class="badge badge-secondary"></i> Distribusi</span>',
			'disp'=>'<span class="badge badge-primary"></i> Proses Disposisi</span>',
			'proc'=>'<span class="badge badge-warning"></i> Sedang Diproses</span>',
			'finl'=>'<span class="badge badge-success"></i> Selesai</span>',
			'cncl'=>'<span class="badge badge-danger"></i> Ditolak</span>',
			'inpcncl'=>'<span class="badge badge-danger"></i> Dibatalkan</span>',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function lblfungsi($string) {
		$rule_name = [
			'ver'=>'<span class="badge badge-warning"></i> Verifikasi</span>',
			'disp'=>'<span class="badge badge-primary"></i> Disposisi</span>',
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function indotgl($str) {
		$bulan = [
			'01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember'
		];
		$res = '';
		$exp = explode(' ', $str);
		$strdate = (count($exp) == 2) ? $exp[0] : $str;
		$strend = (count($exp) == 2) ? ' '.$exp[1] : '';
		$expdate = explode('-', $strdate);
		if (count($expdate) == 3) {
			$res = $expdate[2].' '.$bulan[$expdate[1]].' '.$expdate[0];
			$res .= $strend;
		} else {
			$res = $str;
		}
		return $res;
	}

}
