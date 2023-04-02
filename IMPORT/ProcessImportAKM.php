<?php

//LIST FUNCTION NO : 70-74
//ERROR CODE : 730 - 738

include('../init.php');

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$id_semester = $data['id_semester'];
$nim = $data['nim'];
$nama_mahasiswa = $data['nama_mahasiswa'];
//$sks_semester = $data['sks_semester']; //karena perubahan sistem Feeder 2.1, jumlah sks KRS mahasiswa di MENU MAHASISWA harus sama dengan sks semester import AKM
//Filter SKS semester
$filter_request = "nim ilike '".$nim."' and id_periode ilike '".$id_semester."'";
$data_request = array('act'=>'GetKRSMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$sks_semester=0;
$i=0;
foreach($result['data'] as $row){
	$sks_semester=$sks_semester+$row['sks_mata_kuliah'];
	$i=i+1;
}

$total_sks = $data['total_sks'];
$ips = $data['ips'];
$ipk = $data['ipk'];
$id_status_mahasiswa = $data['id_status_mahasiswa'];
$kode_program_studi = $data['kode_program_studi'];

$path_directory_LOG = BASE_URL.'/LOG/'.$id_semester.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_semester."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG


//Filter Mahasiswa PT 
$filter_request = "nim ilike '%".$nim."%'";
$data = array('act'=>'GetListRiwayatPendidikanMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_registrasi_mahasiswa = $result['data'][0]['id_registrasi_mahasiswa'];					
	
//Filter Kuliah Mahasiswa
$filter_request = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."' and id_semester = '".$id_semester."'" ;
$data = array('act'=>'GetDetailPerkuliahanMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$akm_id_registrasi_mahasiswa = $result['data'][0]['id_registrasi_mahasiswa'];					
$akm_id_semester = $result['data'][0]['id_semester'];					
		
$record['id_status_mahasiswa'] = $id_status_mahasiswa;
$record['ips'] = $ips;
$record['sks_semester'] = $sks_semester;
$record['ipk'] = $ipk;
$record['total_sks'] = $total_sks;								
					
$data_akm = $record;//data AKM

$update_akm ='';
$insert_akm = '';
					
$error_update_akm ='';
$error_insert_akm = '';

if($akm_id_registrasi_mahasiswa!='' && $akm_id_semester!=''){
	$key['id_semester'] = $akm_id_semester; 
	$key['id_registrasi_mahasiswa'] = $akm_id_registrasi_mahasiswa;
	$data = array('act'=>'UpdatePerkuliahanMahasiswa', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$update_akm = json_decode($result_string, true);

	$error_update_akm =$update_akm['error_desc'];
	$action = "UPDATE";
					
}else{
	$record['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa;
	$record['id_semester'] = $id_semester;
	
	$data = array('act'=>'InsertPerkuliahanMahasiswa', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$insert_akm = json_decode($result_string, true);
	
	$error_insert_akm =$insert_akm['error_desc'];
	$action = "INSERT";						
						
}


if($error_insert_akm==NULL AND $error_update_akm==NULL){
	$LOG=$LOG."[SUCCESS]\tData AKM (".$id_semester."-".$nim.") berhasil di-".$action." <br>";	
	$REPORT="[SUCCESS]\tData AKM (".$id_semester."-".$nim.") berhasil di-".$action." <br>";	
}
else{
	$LOG=$LOG."[Error]\tpada data AKM (".$id_semester."-".$nim.") \t DESC INSERT AKM : ".$error_insert_akm." \t DESC UPDATE AKM : ".$error_update_akm." <br>------------------------------------<br> ";	
	$REPORT="[Error]\tpada data AKM (".$id_semester."-".$nim.") \t DESC INSERT AKM : ".$error_insert_akm." \t DESC UPDATE AKM : ".$error_update_akm." <br>------------------------------------<br> ";
}


$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;


?>

