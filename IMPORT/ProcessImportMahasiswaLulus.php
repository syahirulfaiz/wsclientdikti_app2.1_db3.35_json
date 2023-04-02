<?php

//LIST FUNCTION NO : 75-79
//ERROR CODE : ???


include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$jumlah_lulusan =  $_REQUEST['jumlah_lulusan'];


$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$nim = $data['nim']; 
$nama_mahasiswa = $data['nama_mahasiswa']; 
if (!empty($data['tanggal_keluar'])){
	$tanggal_keluar = date('Y-m-d',strtotime($data['tanggal_keluar']));	
}
$nomor_sk_yudisium = $data['nomor_sk_yudisium']; 
if (!empty($data['tanggal_sk_yudisium'])){
	$tanggal_sk_yudisium = date('Y-m-d',strtotime($data['tanggal_sk_yudisium']));
}
$ipk = $data['ipk'];		
$jalur_skripsi = $data['jalur_skripsi'];		
$judul_skripsi = $data['judul_skripsi'];		
$nomor_ijazah = $data['nomor_ijazah'];	
$wisuda_ke = $data['wisuda_ke'];
$id_jenis_keluar = $data['id_jenis_keluar'];	

//cari keterangan
$filter_request = "id_jenis_keluar = '".$id_jenis_keluar."' ";
$data_request = array('act'=>'GetJenisKeluar', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$keterangan = $result['data'][0]['jenis_keluar'];

$bulan_awal_bimbingan = date('Y-m-d',strtotime($data['bulan_awal_bimbingan']));
$bulan_akhir_bimbingan = date('Y-m-d',strtotime($data['bulan_akhir_bimbingan']));

$path_directory_LOG = BASE_URL.'/LOG/LULUSAN';

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$wisuda_ke."-".$jumlah_lulusan."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG

//Filter Mahasiswa PT 
$filter_request = "nim ilike '%".$nim."%'";
$data_request = array('act'=>'GetListRiwayatPendidikanMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_registrasi_mahasiswa = $result['data'][0]['id_registrasi_mahasiswa'];

//Cek di Data Mahasiswa Lulus DO
$filter_request = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."'";
$data_request = array('act'=>'GetListMahasiswaLulusDO', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_registrasi_mahasiswa_lulusDO = $result['data'][0]['id_registrasi_mahasiswa'];


//TIDAK BOLEH ADA UPDATE NIM
$record['tanggal_keluar'] = $tanggal_keluar;
$record['keterangan'] = $keterangan;
$record['bulan_awal_bimbingan'] = $bulan_awal_bimbingan;
$record['bulan_akhir_bimbingan'] = $bulan_akhir_bimbingan;
$record['id_jenis_keluar'] = $id_jenis_keluar;
$record['nomor_sk_yudisium'] = $nomor_sk_yudisium;					
$record['tanggal_sk_yudisium'] = $tanggal_sk_yudisium;
$record['jalur_skripsi'] = $jalur_skripsi;
$record['judul_skripsi'] = $judul_skripsi;
$record['ipk'] = $ipk;
$record['nomor_ijazah'] = $nomor_ijazah;

$update_mahasiswa_lulusDO ='';
$insert_mahasiswa_lulusDO = '';
					
$error_update_mahasiswa_lulusDO ='';
$error_insert_mahasiswa_lulusDO = '';

if($id_registrasi_mahasiswa_lulusDO!=''){
	$key['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa_lulusDO; 
	$data_request = array('act'=>'UpdateMahasiswaLulusDO', 
				'token'=>$token,
				'key'=>$key,
				'record'=>$record,
				 );
	$result_string = runWS($data_request, $ctype);
	$update_mahasiswa_lulusDO = json_decode($result_string, true);
	
	$error_update_mahasiswa_lulusDO =$update_mahasiswa_lulusDO['error_desc'];
	$action = "UPDATE";		
}else{
	$record['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa;
	
	$data = array('act'=>'InsertMahasiswaLulusDO', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$insert_mahasiswa_lulusDO = json_decode($result_string, true);
	
	$error_insert_mahasiswa_lulusDO =$insert_mahasiswa_lulusDO['error_desc'];
	$action = "INSERT";
}

if($error_insert_mahasiswa_lulusDO==NULL AND $error_update_mahasiswa_lulusDO==NULL){
	$LOG=$LOG."[SUCCESS]\tData NIM / Nama '".$nim." / ".$nama_mahasiswa."' berhasil di Update dengan status '".$keterangan."' <br>";	
	$REPORT="[SUCCESS]\tData NIM / Nama '".$nim." / ".$nama_mahasiswa."' berhasil di Update dengan status '".$keterangan."' <br>";							
}
else{
	$LOG=$LOG."[Error]\tpada data ( '".$nim." / ".$nama_mahasiswa.") \t DESC INSERT : ".$error_insert_mahasiswa_lulusDO." \t DESC UPDATE : ".$error_update_mahasiswa_lulusDO." <br>------------------------------------<br> ";
	$REPORT="[Error]\tpada data ( '".$nim." / ".$nama_mahasiswa.") \t DESC INSERT : ".$error_insert_mahasiswa_lulusDO." \t DESC UPDATE : ".$error_update_mahasiswa_lulusDO." <br>------------------------------------<br> ";						
}
				

$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	


echo $REPORT;



?>

