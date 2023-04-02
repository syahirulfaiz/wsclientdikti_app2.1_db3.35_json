<?php

//LIST FUNCTION NO : 88-92
//ERROR CODE : 940 - 950

/*
yang belum :
//$record['jml_mgu_kul'] = $jml_mgu_kul;
//$record['metode_kul'] = $metode_kul;
//$record['metode_kul_eks'] = $metode_kul_eks;	

*/



//PENTING INIT BUKAN INITSOAP!!
include('../init.php');

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$id_semester = $data['id_semester'];
$jumlah_target_mahasiswa_baru = $data['jumlah_target_mahasiswa_baru'];
$jumlah_pendaftar_ikut_seleksi = $data['jumlah_pendaftar_ikut_seleksi'];
$jumlah_pendaftar_lulus_seleksi = $data['jumlah_pendaftar_lulus_seleksi'];
$jumlah_daftar_ulang = $data['jumlah_daftar_ulang'];
$jumlah_mengundurkan_diri = $data['jumlah_mengundurkan_diri'];
$tanggal_awal_perkuliahan = $data['tanggal_awal_perkuliahan'];
$tanggal_akhir_perkuliahan = $data['tanggal_akhir_perkuliahan'];
//$jml_mgu_kul = $data['jml_mgu_kul']; 
//$metode_kul = $data['metode_kul']; 
//$metode_kul_eks = $data['metode_kul_eks']; 
$kode_program_studi = $data['kode_program_studi'];



$path_directory_LOG = BASE_URL.'/LOG/'.$id_semester.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_semester."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG
	


//filter cari prodi
$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
$data = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];


//Filter semester 
$filter_request = "id_semester ilike '%".$id_semester."%'";
$data = array('act'=>'GetSemester', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$tanggal_awal_perkuliahan = $result['data'][0]['tanggal_mulai'];
$tanggal_akhir_perkuliahan = $result['data'][0]['tanggal_selesai'];

// MASIH UNKNOWN BELUM KETEMU FUNGSINYA DI JSON
//Count Jumlah Minggu
//$startDate = new DateTime($tgl_awal_kul);
//$endDate = new DateTime($tgl_akhir_kul);
//$interval = $startDate->diff($endDate);
//$jml_mgu_kul =  (int)(($interval->days) / 7);

//echo " id_prodi : ".$id_prodi." , id_smt : ".$id_semester." , tgl_awal_kul : ".$tanggal_awal_perkuliahan.", tgl_akhir_kul : ".$tanggal_akhir_perkuliahan."<br/>";


//Filter Daya Tampung 
$filter_request = "id_semester ilike '%".$id_semester."%' and id_prodi='".$id_prodi."'";
$data = array('act'=>'GetDetailPeriodePerkuliahan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$daya_tampung_id_semester = $result['data'][0]['id_semester'];
$daya_tampung_id_prodi = $result['data'][0]['id_prodi'];

$record['jumlah_target_mahasiswa_baru'] = $jumlah_target_mahasiswa_baru;
$record['jumlah_pendaftar_ikut_seleksi'] = $jumlah_pendaftar_ikut_seleksi;
$record['jumlah_pendaftar_lulus_seleksi'] = $jumlah_pendaftar_lulus_seleksi;
$record['jumlah_daftar_ulang'] = $jumlah_daftar_ulang;
$record['jumlah_mengundurkan_diri'] = $jumlah_mengundurkan_diri;			
$record['tanggal_awal_perkuliahan'] = $tanggal_awal_perkuliahan;
$record['tanggal_akhir_perkuliahan'] = $tanggal_akhir_perkuliahan;	
//$record['jml_mgu_kul'] = $jml_mgu_kul;
//$record['metode_kul'] = $metode_kul;
//$record['metode_kul_eks'] = $metode_kul_eks;				

					
$data_daya_tampung = $record;//data Daya Tampung

$update_daya_tampung ='';
$insert_daya_tampung = '';
					
$error_update_daya_tampung ='';
$error_insert_daya_tampung = '';

if($daya_tampung_id_prodi!='' && $daya_tampung_id_semester!=''){
	
	$key['id_semester'] = $daya_tampung_id_semester; 
	$key['id_prodi'] = $daya_tampung_id_prodi;
	$data = array('act'=>'UpdatePeriodePerkuliahan', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$update_daya_tampung = json_decode($result_string, true);

	$error_update_daya_tampung =$update_daya_tampung['error_desc'];
	$action = "UPDATE";	
			
	
}else{
	
	$record['id_prodi'] = $id_prodi;
	$record['id_semester'] = $id_semester;
	
	$data = array('act'=>'InsertPeriodePerkuliahan', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$insert_daya_tampung = json_decode($result_string, true);
	
	$error_insert_daya_tampung =$insert_daya_tampung['error_desc'];
	$action = "INSERT";						
	
	
}


if($error_insert_daya_tampung==NULL AND $error_update_daya_tampung==NULL){
	$LOG=$LOG."[SUCCESS]\tData DAYA TAMPUNG (".$id_semester."-".$kode_program_studi.") berhasil di-".$action." <br>";	
	$REPORT="[SUCCESS]\tData DAYA TAMPUNG (".$id_semester."-".$kode_program_studi.") berhasil di-".$action." <br>";		
}
else{
	$LOG=$LOG."[Error]\tpada data DAYA TAMPUNG (".$id_semester."-".$kode_program_studi.") \t DESC INSERT DAYA TAMPUNG : ".$error_insert_daya_tampung." \t DESC UPDATE DAYA TAMPUNG : ".$error_update_daya_tampung." <br>------------------------------------<br> ";	
	$REPORT="[Error]\tpada data DAYA TAMPUNG  (".$id_semester."-".$kode_program_studi.") \t DESC INSERT DAYA TAMPUNG : ".$error_insert_daya_tampung." \t DESC UPDATE DAYA TAMPUNG : ".$error_update_daya_tampung." <br>------------------------------------<br> ";
}


$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;

?>

