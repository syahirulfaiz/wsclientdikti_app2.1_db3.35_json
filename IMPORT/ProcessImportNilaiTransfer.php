<?php

//LIST FUNCTION NO : 18-21
//ERROR CODE : 810 - 815

include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$nim = $data['nim'];
$kode_matkul_diakui = $data['kode_matkul_diakui'];
$nama_mata_kuliah_diakui = $data['nama_mata_kuliah_diakui'];	
$sks_mata_kuliah_diakui = $data['sks_mata_kuliah_diakui'];
$nilai_angka_diakui = $data['nilai_angka_diakui'];
$nilai_huruf_diakui = $data['nilai_huruf_diakui'];
$kode_mata_kuliah_asal = $data['kode_mata_kuliah_asal'];
$nama_mata_kuliah_asal = $data['nama_mata_kuliah_asal'];
$sks_mata_kuliah_asal = $data['sks_mata_kuliah_asal'];
$nilai_huruf_asal = $data['nilai_huruf_asal'];

$path_directory_LOG = BASE_URL.'/LOG/NilaiTransfer/';

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$nim."-".$nama_file_LOG;

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


//Filter MATA_KULIAH
$filter_request = "kode_mata_kuliah ilike '".$kode_matkul_diakui."' and nama_mata_kuliah ilike '%".substr($nama_mata_kuliah_diakui,0,6)."%'";
$data = array('act'=>'GetListMataKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
if($result['data']){
	$id_matkul = $result['data'][0]['id_matkul'];	
	
}else{
	//jaga-jaga tidak masuk
	
	$filter_request = "kode_mata_kuliah ilike '".$kode_matkul_diakui."'";
	$data = array('act'=>'GetListMataKuliah', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data, $ctype);
	$result = json_decode($result_string, true);
	$id_matkul = $result['data'][0]['id_matkul'];
	
}



//$record['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa;
//$record['id_matkul'] = $id_matkul;
$record['kode_mata_kuliah_asal'] = $kode_mata_kuliah_asal;
$record['nama_mata_kuliah_asal'] = $nama_mata_kuliah_asal;
$record['sks_mata_kuliah_asal'] = $sks_mata_kuliah_asal;
$record['sks_mata_kuliah_diakui'] = $sks_mata_kuliah_diakui;
$record['nilai_huruf_asal'] = $nilai_huruf_asal;
$record['nilai_huruf_diakui'] = $nilai_huruf_diakui;
$record['nilai_angka_diakui'] = $nilai_angka_diakui;

$data_nilai_transfer = $record;//data NILAI transfer


//CARI nilai transfer
$filter_request = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."' and id_matkul = '".$id_matkul."'";
$data = array('act'=>'GetNilaiTransferPendidikanMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_transfer = $result['data'][0]['id_transfer'];

$update_nilai_transfer = '';
$insert_nilai_transfer = '';
					
$error_update_nilai_transfer ='';
$error_insert_nilai_transfer = '';

if($id_transfer!=''){
	
	$key['id_transfer'] = $id_transfer; 
	$data = array('act'=>'UpdateNilaiTransferPendidikanMahasiswa', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$update_nilai_transfer = json_decode($result_string, true);

	$error_update_nilai_transfer =$update_nilai_transfer['error_desc'];
	$action = "UPDATE";	
					
}else{
	
	$record['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa;
	$record['id_matkul'] = $id_matkul;
	
	$data = array('act'=>'InsertNilaiTransferPendidikanMahasiswa', 
			'token'=>$token,
			'record'=>$record
			 );
	$result_string = runWS($data, $ctype);
	$insert_nilai_transfer = json_decode($result_string, true);
	
	$error_insert_nilai_transfer =$insert_nilai_transfer['error_desc'];
	$action = "INSERT";	
						
}

if($error_insert_nilai_transfer==NULL AND $error_update_nilai_transfer==NULL){
	$LOG=$LOG."[SUCCESS]\tData NILAI TRANSFER (".$nim."-".$kode_matkul_diakui." dari kode asal : ".$kode_mata_kuliah_asal.") berhasil di-".$action." <br>";	
	$REPORT="[SUCCESS]\tData NILAI TRANSFER (".$nim."-".$kode_matkul_diakui." dari kode asal : ".$kode_mata_kuliah_asal.") berhasil di-".$action." <br>";	
}
else{
	$LOG=$LOG."[Error]\tpada data NILAI TRANSFER (".$nim."-".$kode_matkul_diakui.") \t DESC INSERT BOBOT NILAI : ".$error_insert_nilai_transfer." \t DESC UPDATE BOBOT NILAI : ".$error_update_nilai_transfer." <br>------------------------------------<br> ";	
	$REPORT="[Error]\tpada data NILAI TRANSFER (".$nim."-".$kode_matkul_diakui.") \t DESC INSERT BOBOT NILAI : ".$error_insert_nilai_transfer." \t DESC UPDATE BOBOT NILAI : ".$error_update_nilai_transfer." <br>------------------------------------<br> ";
}

$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;




?>

