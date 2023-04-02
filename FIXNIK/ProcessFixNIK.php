<?php

//LIST FUNCTION NO : 64-69
//ERROR CODE : 800-806
//berfungsi untuk memperbaiki NIK untuk data master mahasiswa
//inputan : nim dan NIK yg benar. Pastikan semester pelaporan terbuka


include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$nim  = $data['nim'];
$nik  = $data['nik'];

$path_directory_LOG = BASE_URL.'/LOG/FIXNIK';

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG
$REPORT	="";

//Filter Mahasiswa_pt 
$filter_request = "nim ilike '".$nim."'";
$data_request = array('act'=>'GetListRiwayatPendidikanMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_mahasiswa = $result['data'][0]['id_mahasiswa'];
$nama_mahasiswa = $result['data'][0]['nama_mahasiswa'];

$update_mahasiswa='';
$error_update_mahasiswa ='';

$record['nik'] = $nik;

//Update Mahasiswa
$key['id_mahasiswa'] = $id_mahasiswa; 
$data_request = array('act'=>'UpdateBiodataMahasiswa', 
				'token'=>$token,
				'key'=>$key,
				'record'=>$record,
				 );
$result_string = runWS($data_request, $ctype);
$update_mahasiswa = json_decode($result_string, true);
	
$error_update_mahasiswa =$update_mahasiswa['error_desc'];
$action = "UPDATE";		

if($error_update_mahasiswa==NULL){
	$LOG=$LOG."[SUCCESS]\tData NIM / Nama '".$nim." / ".$nama_mahasiswa."' berhasil di Update NIK nya dengan '".$nik."' <br>";	
	$REPORT="[SUCCESS]\tData NIM / Nama '".$nim." / ".$nama_mahasiswa."' berhasil di Update NIK nya status '".$nik."' <br>";							
}
else{
	$LOG=$LOG."[Error]\tpada data ( '".$nim." / ".$nama_mahasiswa.") \t DESC UPDATE NIK : ".$error_update_mahasiswa." <br>------------------------------------<br> ";
	$REPORT="[Error]\tpada data ( '".$nim." / ".$nama_mahasiswa.") \t DESC UPDATE NIK: ".$error_update_mahasiswa." <br>------------------------------------<br> ";						
}
				

$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	



echo $REPORT;


?>

