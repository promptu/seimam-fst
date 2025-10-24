<?php

namespace App\Library;

class Pesan {

	public static function success($string){
		$rule_name = [
			'save' => 'Data berhasil disimpan!',
			'update' => 'Data berhasil diupdate!',
			'get' => 'Data berhasil dimuat!',
			'delete' => 'Data berhasil dihapus!',
			'reset' => 'Password telah direset!',
		];
		return isset($rule_name[$string])?$rule_name[$string]:'Tidak diketahui!';
	}

	public static function fail($string){
		$rule_name = [
			'save' => 'Gagal menyimpan data!',
			'update' => 'Gagal mengupdate data!',
			'get' => 'Data tidak ditemukan!',
			'delete' => 'Gagal menghapus data!',
			'reset' => 'Gagal reset password!',
		];
		return isset($rule_name[$string])?$rule_name[$string]:'Tidak diketahui!';
	}


}
