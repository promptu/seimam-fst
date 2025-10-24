<?php

namespace App\Library;

class Dropdown {

	public static function level_jabatan(){
		return [
			['sid'=>'0','sval'=>'Pimpinan/Koordinator'],
			['sid'=>'1','sval'=>'Kasub/Subkoordinator'],
			['sid'=>'2','sval'=>'Staf'],
		];
	}

	public static function jekel(){
		return [
			['sid'=>'L','sval'=>'Pria'],
			['sid'=>'P','sval'=>'Wanita'],
		];
	}
	
	public static function fungsi(){
		return [
			['sid'=>'ver','sval'=>'Verifikasi'],
			['sid'=>'disp','sval'=>'Disposisi'],
		];
	}
	
	public static function status_disp(){
		return [
			['sid'=>'disp','sval'=>'Disposisi'],
			['sid'=>'finl','sval'=>'Selesai'],
		];
	}

	public static function status_surat() {
		return [			
			// ['sid'=>'inpt','sval'=>'Input'],
			['sid'=>'ver1','sval'=>'Verifikasi'],
			// ['sid'=>'ver2','sval'=>'Ver. Kabag'],
			['sid'=>'disp','sval'=>'Proses Disposisi'],
			['sid'=>'finl','sval'=>'Selesai'],
			['sid'=>'cncl','sval'=>'Batal',	]	
		];
	}	

	public static function status_surat2() {
		return [			
			['sid'=>'ver','sval'=>'Verifikasi'],
			['sid'=>'disp','sval'=>'Proses Disposisi'],
			['sid'=>'finl','sval'=>'Selesai'],
			['sid'=>'cncl','sval'=>'Batal',	]	
		];
	}	

	public static function lblstsurat($string) {
		$rule_name = [	
		];
		return isset($rule_name[$string])?$rule_name[$string]:$string;
	}

	public static function act(){
		return [
			['sid'=>'Y','sval'=>'Aktif'],
			['sid'=>'T','sval'=>'Non-Aktif'],
		];
	}

	public static function is_verif(){
		return [
			['sid'=>1,'sval'=>'Ya'],
			['sid'=>0,'sval'=>'Tidak'],
		];
	}

	public static function ppg() {
		return [
			['sid'=>1,'sval'=>'Per-page 1'],
			['sid'=>10,'sval'=>'Per-page 10'],
			['sid'=>25,'sval'=>'Per-page 25'],
			['sid'=>50,'sval'=>'Per-page 50'],
			['sid'=>100,'sval'=>'Per-page 100'],
		];
	}
 
}