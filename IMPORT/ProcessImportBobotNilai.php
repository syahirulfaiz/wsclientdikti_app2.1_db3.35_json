<?php
//LIST FUNCTION NO : 83-87
//ERROR CODE : 960

include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$kode_program_studi = $data['kode_program_studi'];
$nilai_huruf  = $data['nilai_huruf'];
$bobot_minimum = $data['bobot_minimum'];
$bobot_maksimum = $data['bobot_maksimum'];
$nilai_indeks = $data['nilai_indeks'];
$tanggal_mulai_efektif  = '1970-01-01';
$tanggal_akhir_efektif  = '2099-12-31';		



$path_directory_LOG = BASE_URL.'/LOG/BobotNilai/';

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG

$record['bobot_minimum'] = $bobot_minimum;
$record['bobot_maksimum'] = $bobot_maksimum;
$record['nilai_indeks'] = $nilai_indeks;
$record['tanggal_mulai_efektif'] = $tanggal_mulai_efektif;
$record['tanggal_akhir_efektif'] = $tanggal_akhir_efektif;

$data_bobot_nilai = $record;//data BOBOT NILAI

//cari prodi
$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
$data = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];

//cari bobot nilai
$filter_request = "id_prodi = '".$id_prodi."' and nilai_huruf = '".$nilai_huruf."'";
$data = array('act'=>'GetListSkalaNilaiProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_bobot_nilai = $result['data'][0]['id_bobot_nilai'];

$update_bobot_nilai ='';
$insert_bobot_nilai = '';
					
$error_update_bobot_nilai ='';
$error_insert_bobot_nilai = '';


if($id_bobot_nilai!=''){
	
	$key['id_bobot_nilai'] = $id_bobot_nilai; 
	$data = array('act'=>'UpdateSkalaNilaiProdi', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$update_bobot_nilai = json_decode($result_string, true);

	$error_update_bobot_nilai =$update_bobot_nilai['error_desc'];
	$action = "UPDATE";				
}else{
	
	$record['id_prodi'] = $id_prodi;
	$record['nilai_huruf'] = $nilai_huruf;
	
	$data = array('act'=>'InsertSkalaNilaiProdi', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$insert_bobot_nilai = json_decode($result_string, true);
	
	$error_insert_bobot_nilai =$insert_bobot_nilai['error_desc'];
	$action = "INSERT";				
}

if($error_insert_bobot_nilai==NULL AND $error_update_bobot_nilai==NULL){
	$LOG=$LOG."[SUCCESS]\tData BOBOT NILAI (".$kode_program_studi."-".$nilai_huruf.") berhasil di-".$action." <br>";	
	$REPORT="[SUCCESS]\tData BOBOT NILAI (".$kode_program_studi."-".$nilai_huruf.") berhasil di-".$action." <br>";	
}
else{
	$LOG=$LOG."[Error]\tpada data BOBOT NILAI (".$kode_program_studi."-".$nilai_huruf.") \t DESC INSERT BOBOT NILAI : ".$error_insert_bobot_nilai." \t DESC UPDATE BOBOT NILAI : ".$error_update_bobot_nilai." <br>------------------------------------<br> ";	
	$REPORT="[Error]\tpada data BOBOT NILAI (".$kode_program_studi."-".$nilai_huruf.") \t DESC INSERT BOBOT NILAI : ".$error_insert_bobot_nilai." \t DESC UPDATE BOBOT NILAI : ".$error_update_bobot_nilai." <br>------------------------------------<br> ";
}


$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;





?>

