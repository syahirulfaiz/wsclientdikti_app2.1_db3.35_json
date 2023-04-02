<?php

//LIST FUNCTION NO : 64-69
//ERROR CODE : 800-806
//1.Berfungsi untuk mencari KRS dobel secara otomatis yg diambil oleh seorang mahasiswa dan mendeletenya. 
//2.Menghitung AKM berdasar SKS KRS, lalu mengupdatenya sesuai nilai terakhir di AKM
//ATAU BISA JUGA (CARA TERMUDAH):
//untuk perbaikan sks > 25 sekaligus memfixkan akm
//1. perbaiki di menu kelas kuliah dulu kalau bisa. Ubah sks menjadi dikit
//2. Kemudian hook dengan import ini
//inputan : kode_program_studi, nim, id_semester


include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$kode_program_studi = $data['kode_program_studi'];
$nim  = $data['nim'];
$id_semester  = $data['id_semester'];

$path_directory_LOG = BASE_URL.'/LOG/'.$id_semester.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_semester."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG
$REPORT	="";

//cari prodi
$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
$data_request = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];

//Filter KRS semester
$filter_request = "nim ilike '".$nim."' and id_periode ilike '".$id_semester."'";
$data_request = array('act'=>'GetKRSMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);

$array_mata_kuliah= array();
$duplicates = array();

foreach($result['data'] as $row){
	$array_mata_kuliah[]=$row['kode_mata_kuliah'];
}

foreach(array_count_values($array_mata_kuliah) as $val=>$c){
	if($c > 1){
		$duplicates[]=$val;
	}
}

foreach($duplicates as $row){
	//Filter KRS semester
	$filter_request = "nim ilike '".$nim."' and id_periode ilike '".$id_semester."' and kode_mata_kuliah ilike '".$row."'";
	$data_request = array('act'=>'GetKRSMahasiswa', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data_request, $ctype);
	$result = json_decode($result_string, true);
	$id_kelas_kuliah = $result['data'][0]['id_kelas'];
	$id_registrasi_mahasiswa = $result['data'][0]['id_registrasi_mahasiswa'];

	//echo $id_kelas_kuliah."--".$id_registrasi_mahasiswa;
	
	$key['id_kelas_kuliah'] = $id_kelas_kuliah; 
	$key['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa; 
	$data_request = array('act'=>'DeletePesertaKelasKuliah', 
			'token'=>$token,
			'key'=>$key
			 );
	$result_string = runWS($data_request, $ctype);
	$delete_nilai = json_decode($result_string, true);

	$error_delete_nilai =$delete_nilai['error_desc'];
	$action = "DELETE";	
	
	if($error_delete_nilai==NULL){
		$LOG=$LOG."[SUCCESS]\tData NIM / Kode MataKuliah ".$nim." / ".$row." berhasil di-".$action." <br>";	
		$REPORT=$REPORT."[SUCCESS]\tData NIM / Kode MataKuliah ".$nim." / ".$row." berhasil di-".$action." <br>";	
	}else{
		$LOG=$LOG."[Error]\tData ( ".$nim." / ".$row." \t DESC DELETE : ".$error_delete_nilai." <br>------------------------------------<br> ";	
		$REPORT=$REPORT."[Error]\tData ( ".$nim." / ".$row." \t DESC DELETE : ".$error_delete_nilai." <br>------------------------------------<br> ";
	}
	
}

//RE-hitung KRS after DELETION
//Filter SKS semester
$filter_request = "nim ilike '".$nim."' and id_periode ilike '".$id_semester."'";
$data_request = array('act'=>'GetKRSMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$sks_semester=0;
foreach($result['data'] as $row){
	$sks_semester=$sks_semester+$row['sks_mata_kuliah'];
	$id_registrasi_mahasiswa = $row['id_registrasi_mahasiswa'];
}

$key=null;
$record['sks_semester'] = $sks_semester;
$key['id_semester'] = $id_semester; 
$key['id_registrasi_mahasiswa'] = $id_registrasi_mahasiswa;
$data = array('act'=>'UpdatePerkuliahanMahasiswa', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
$result_string = runWS($data, $ctype);
$update_akm = json_decode($result_string, true);

//echo $key['id_semester'].'---'.$key['id_registrasi_mahasiswa'].'---'.$record['sks_semester'];


$error_update_akm =$update_akm['error_desc'];
$action = "UPDATE";


if($error_update_akm==NULL){
	$LOG=$LOG."[SUCCESS]\tData AKM (".$id_semester."-".$nim.") berhasil di-".$action." <br>";	
	$REPORT=$REPORT."[SUCCESS]\tData AKM (".$id_semester."-".$nim.") berhasil di-".$action." <br>";	
}
else{
	$LOG=$LOG."[Error]\tpada data AKM (".$id_semester."-".$nim.") \t DESC UPDATE AKM : ".$error_update_akm." <br>------------------------------------<br> ";	
	$REPORT=$REPORT."[Error]\tpada data AKM (".$id_semester."-".$nim.") \t DESC UPDATE AKM : ".$error_update_akm." <br>------------------------------------<br> ";
}




/*
$REPORT ='';
foreach($duplicates as $row){
	$REPORT = $REPORT.', '.$row;
}
*/
echo $REPORT;
?>

