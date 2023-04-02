<?php

//LIST FUNCTION NO : 37-54
//ERROR CODE : 400 - 636

/*
//$record['id_jenj_didik'] = $id_jenj_didik;
//$record['jml_sem_normal'] = $jml_sem_normal;
//$record['id_jenj_didik'] = $id_jenj_didik; //belum ketemu di fungsi apa
UpdateMataKuliah masih bermasalah
SEMENTARA FUNGSI UpdateMatkulKurikulum BELUM ADA !!!
*/


include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$kode_program_studi = $data['kode_program_studi'];
$id_semester = $data['id_semester'];

$path_directory_LOG = BASE_URL.'/LOG/'.$id_semester.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_semester."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG

//cari prodi
$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
$data_request = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];

//set nama kurikulum
$filter_request = "id_semester = '".$id_semester."'";
$data_request = array('act'=>'GetSemester', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$nama_semester = $result['data'][0]['nama_semester'];
$tanggal_mulai = $result['data'][0]['tanggal_mulai'];
$tanggal_selesai = $result['data'][0]['tanggal_selesai'];

$nama_kurikulum = 'Kurikulum TA '.$nama_semester;

		
//$jml_sem_normal=0; //sementara belum ketemu di fungsi apa
$jumlah_sks_lulus=0;
$jumlah_sks_wajib=0;
$jumlah_sks_pilihan=0;
	
$kode_mata_kuliah=$data['kode_mata_kuliah'];
$nama_mata_kuliah=$data['nama_mata_kuliah'];
$id_jenis_mata_kuliah=$data['id_jenis_mata_kuliah'];
$id_kelompok_mata_kuliah=$data['id_kelompok_mata_kuliah'];
$sks_mata_kuliah=$data['sks_mata_kuliah'];
$sks_tatap_muka=$data['sks_tatap_muka'];
$sks_praktek=$data['sks_praktek'];
$sks_praktek_lapangan=$data['sks_praktek_lapangan'];
$sks_simulasi=$data['sks_simulasi'];
$metode_kuliah=$data['metode_kuliah'];
$ada_sap=$data['ada_sap'];
$ada_silabus=$data['ada_silabus'];
$ada_bahan_ajar=$data['ada_bahan_ajar'];
$ada_acara_praktek=$data['ada_acara_praktek'];
$ada_diktat=$data['ada_diktat'];
$tanggal_mulai_efektif= $tanggal_mulai;
$tanggal_akhir_efektif= $tanggal_selesai;	
	
$semester=$data['semester'];
$apakah_wajib=$data['apakah_wajib'];	
					
$record['id_prodi'] = $id_prodi ;
//$record['id_jenj_didik'] = $id_jenj_didik;
$record['id_semester'] = $id_semester;
$record['nama_kurikulum'] = $nama_kurikulum;
//$record['jml_sem_normal'] = $jml_sem_normal;
$record['jumlah_sks_lulus'] = $jumlah_sks_lulus;
$record['jumlah_sks_wajib'] = $jumlah_sks_wajib;
$record['jumlah_sks_pilihan'] = $jumlah_sks_pilihan;					

$data_kurikulum = $record;//data KURIKULUM

$record=NULL;
$record['id_prodi'] = $id_prodi ;
//$record['id_jenj_didik'] = $id_jenj_didik; //belum ketemu di fungsi apa
$record['kode_mata_kuliah'] = $kode_mata_kuliah; //tidak boleh ada kode_mata_kuliah di update tabel mata_kuliah
$record['nama_mata_kuliah'] = $nama_mata_kuliah; //tidak boleh ada kode_mata_kuliah di update tabel mata_kuliah
$record['id_jenis_mata_kuliah'] = $id_jenis_mata_kuliah;
$record['id_kelompok_mata_kuliah'] = $id_kelompok_mata_kuliah;
$record['sks_mata_kuliah'] = $sks_mata_kuliah;
$record['sks_tatap_muka'] = $sks_tatap_muka;
$record['sks_praktek'] = $sks_praktek;
$record['sks_praktek_lapangan'] = $sks_praktek_lapangan;
$record['sks_simulasi'] = $sks_simulasi;
$record['metode_kuliah'] = $metode_kuliah;
$record['ada_sap'] = $ada_sap;
$record['ada_silabus'] = $ada_silabus;
$record['ada_bahan_ajar'] = $ada_bahan_ajar;
$record['ada_acara_praktek'] = $ada_acara_praktek;
$record['ada_diktat'] = $ada_diktat;
$record['tanggal_mulai_efektif'] = $tanggal_mulai_efektif;
//$record['tanggal_selesai_efektif'] = $tanggal_akhir_efektif; //setelah investigasi, ternyata nama kolomnya tanggal_selesai_efektif 
$record['tanggal_akhir_efektif'] = $tanggal_akhir_efektif; //semena-mena diubah oleh dikti menjadi tanggal_akhir_efektif di 2019
					
$data_mata_kuliah = $record;//data MATA_KULIAH
	
$error_update_kurikulum ='';
$error_insert_kurikulum = '';
					
$error_update_mata_kuliah ='';
$error_insert_mata_kuliah = '';
					
$error_update_mata_kuliah_kurikulum ='';
$error_insert_mata_kuliah_kurikulum = '';			
			
//EXE KURIKULUM
$filter_request = "id_prodi='".$id_prodi."' AND id_semester='".$id_semester."'";
$data_request = array('act'=>'GetDetailKurikulum', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_kurikulum = $result['data'][0]['id_kurikulum'];

if($id_kurikulum!=''){
	$key['id_kurikulum'] = $id_kurikulum; 
	$data = array('act'=>'UpdateKurikulum', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$data_kurikulum,
			 );
	$result_string = runWS($data, $ctype);
	$update = json_decode($result_string, true);
	
	$error_update_kurikulum =$update['error_desc'];
	$action = "UPDATE";		
}else{
	$data = array('act'=>'InsertKurikulum', 
			'token'=>$token,
			'record'=>$data_kurikulum,
			 );
	$result_string = runWS($data, $ctype);
	$insert = json_decode($result_string, true);
	
	$error_insert_kurikulum_kurikulum =$insert['error_desc'];
	$action = "INSERT";
}

//Filter MATA_KULIAH
$filter_request = "kode_mata_kuliah ilike '".$kode_mata_kuliah."' and nama_mata_kuliah ilike '%".substr($nama_mata_kuliah,0,6)."%'";	
$data_request = array('act'=>'GetListMataKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_matkul = $result['data'][0]['id_matkul'];

// SEMENTARA UpdateMataKuliah MASIH BANYAK MASALAH!!
//if($id_matkul!=''){
//	$key['id_matkul'] = $id_matkul; 
//	$data = array('act'=>'UpdateMataKuliah', 
//			'token'=>$token,
//			'key'=>$key,
//			'record'=>$record,
//			 );
//	$result_string = runWS($data, $ctype);
//	$update = json_decode($result_string, true);
//	
//	$error_update_mata_kuliah =$update['error_desc'];
//	$action = "UPDATE";	
//}else{
	$data = array('act'=>'InsertMataKuliah', 
			'token'=>$token,
			'record'=>$data_mata_kuliah,
			 );
	$result_string = runWS($data, $ctype);
	$insert = json_decode($result_string, true);
	
	$error_insert_mata_kuliah =$insert['error_desc'];
	$action = "INSERT";
//}

//EXE MATA_KULIAH_KURIKULUM
$filter_request = "kode_mata_kuliah ilike '".$kode_mata_kuliah."' and nama_mata_kuliah ilike '%".substr($nama_mata_kuliah,0,6)."%'";	
$data_request = array('act'=>'GetListMataKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_matkul = $result['data'][0]['id_matkul'];

$filter_request = "id_prodi='".$id_prodi."' AND id_semester='".$id_semester."'";
$data_request = array('act'=>'GetDetailKurikulum', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_kurikulum = $result['data'][0]['id_kurikulum'];

$filter_request = "id_kurikulum='".$id_kurikulum."' AND id_matkul='".$id_matkul."'";
$data_request = array('act'=>'GetMatkulKurikulum', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_kurikulum_mata_kuliah_kurikulum = $result['data'][0]['id_kurikulum'];
$id_matkul_mata_kuliah_kurikulum = $result['data'][0]['id_matkul'];

$record=NULL;					
$record['semester'] = $semester;
$record['sks_mata_kuliah'] = $sks_mata_kuliah;
$record['sks_tatap_muka'] = $sks_tatap_muka;
$record['sks_praktek'] = $sks_praktek;
$record['sks_praktek_lapangan'] = $sks_praktek_lapangan;
$record['sks_simulasi'] = $sks_simulasi;
$record['apakah_wajib'] = $apakah_wajib;
					
$data_mata_kuliah_kurikulum = $record;//data_mata_kuliah_kurikulum

//if($id_kurikulum_mata_kuliah_kurikulum!='' AND $id_matkul_mata_kuliah_kurikulum!=''){
	//SEMENTARA FUNGSI UpdateMatkulKurikulum BELUM ADA !!!
	//$key['id_matkul'] = $id_matkul; 
	//$key['id_kurikulum'] = $id_kurikulum; 
	//$data = array('act'=>'UpdateMatkulKurikulum', 
	//		'token'=>$token,
	//		'key'=>$key,
	//		'record'=>$data_mata_kuliah_kurikulum,
	//		 );
	//$result_string = runWS($data, $ctype);
	//$update = json_decode($result_string, true);
	
	//$error_update_mata_kuliah_kurikulum =$update['error_desc'];
	//$action = "UPDATE";	
	
//}else{
	$record=NULL;
	$record['semester'] = $semester;
	$record['sks_mata_kuliah'] = $sks_mata_kuliah;
	$record['sks_tatap_muka'] = $sks_tatap_muka;
	$record['sks_praktek'] = $sks_praktek;
	$record['sks_praktek_lapangan'] = $sks_praktek_lapangan;
	$record['sks_simulasi'] = $sks_simulasi;
	$record['apakah_wajib'] = $apakah_wajib;
	$record['id_kurikulum'] = $id_kurikulum;
	$record['id_matkul'] = $id_matkul;
						
	$data_mata_kuliah_kurikulum = $record;//data_mata_kuliah_kurikulum
	
	$data = array('act'=>'InsertMatkulKurikulum', 
			'token'=>$token,
			'record'=>$data_mata_kuliah_kurikulum,
			 );
	$result_string = runWS($data, $ctype);
	$insert = json_decode($result_string, true);
	
	$error_insert_mata_kuliah_kurikulum =$insert['error_desc'];
	$action = "INSERT";
//}

//REPORT
if($error_insert_kurikulum==NULL AND $error_update_kurikulum==NULL AND $error_insert_mata_kuliah==NULL AND $error_update_mata_kuliah==NULL AND $error_insert_mata_kuliah_kurikulum==NULL AND $error_update_mata_kuliah_kurikulum==NULL){
	$LOG = $LOG. "[SUCCESS]\tData ".$nama_kurikulum." - ".$kode_mata_kuliah." - ".$nama_mata_kuliah." berhasil di tambahkan<br>-----------------------------------------------------------<br>";
	$REPORT = "[SUCCESS]\tData ".$nama_kurikulum." - ".$kode_mata_kuliah." - ".$nama_mata_kuliah." berhasil di tambahkan<br>-----------------------------------------------------------<br>";									
}
else{				
	$LOG = $LOG. "[Error]\tpada data ".$nama_kurikulum." - ".$kode_mata_kuliah." - ".$nama_mata_kuliah." <br> DESC INSERT KURIKULUM : ".$error_insert_kurikulum." <br> DESC UPDATE KURIKULUM : ".$error_update_kurikulum." <br> DESC INSERT MATA_KULIAH : ".$error_insert_mata_kuliah."<br> DESC UPDATE MATA_KULIAH : ".$error_update_mata_kuliah." <br> DESC INSERT MATA_KULIAH_KURIKULUM : ".$error_insert_mata_kuliah_kurikulum." <br> DESC UPDATE MATA_KULIAH_KURIKULUM : ".$error_update_mata_kuliah_kurikulum."<br>-----------------------------------------------------------<br>";	
	$REPORT = "[Error]\tpada data ".$nama_kurikulum." - ".$kode_mata_kuliah." - ".$nama_mata_kuliah." <br> DESC INSERT KURIKULUM : ".$error_insert_kurikulum." <br> DESC UPDATE KURIKULUM : ".$error_update_kurikulum." <br> DESC INSERT MATA_KULIAH : ".$error_insert_mata_kuliah."<br> DESC UPDATE MATA_KULIAH : ".$error_update_mata_kuliah." <br> DESC INSERT MATA_KULIAH_KURIKULUM : ".$error_insert_mata_kuliah_kurikulum." <br> DESC UPDATE MATA_KULIAH_KURIKULUM : ".$error_update_mata_kuliah_kurikulum."<br>-----------------------------------------------------------<br>";	
}

$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;



?>

