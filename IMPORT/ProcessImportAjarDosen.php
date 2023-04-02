<?php

//LIST FUNCTION NO : 59-62
//ERROR CODE : 920-931

//SEMENTARA BELUM ADA FUNGSI UNTUK MEMASUKKAN METHODNYA
//$record['sks_tm_subst'] = $sks_tm_subst;
//$record['sks_prak_subst'] = $sks_prak_subst ;
//$record['sks_prak_lap_subst'] = $sks_prak_lap_subst;
//$record['sks_sim_subst'] = $sks_sim_subst;

//SEMENTARA ditambahkan $sks_mata_kuliah untuk penanda kalau ada error tidak masuk krn SKS 0

include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$id_semester = $data['id_semester'];
$nidn =  $data['nidn'];
$kode_mata_kuliah  = $data['kode_mata_kuliah'];
$nama_mata_kuliah  = $data['nama_mata_kuliah'];
$sks_substansi_total =  $data['sks_substansi_total'];
$rencana_tatap_muka =  $data['rencana_tatap_muka'];
$realisasi_tatap_muka = $rencana_tatap_muka;
$kode_program_studi = $data['kode_program_studi'];
$nama_kelas_kuliah = $data['nama_kelas_kuliah'];


$path_directory_LOG = BASE_URL.'/LOG/'.$id_semester.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_semester."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG

//cari prodi
$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
$data = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];

//Filter Dosen
if ($nidn!=''){
	$fildosen = "nidn ilike '%".$nidn."%'";
}else{
	$fildosen = "nidn ilike '%wkwkwkwkwk%'";
}
$data = array('act'=>'GetListPenugasanDosen', 
					  'token'=>$token, 
					  'filter'=>$fildosen
					  );
$result_string = runWS($data, $ctype);
$result = json_decode($result_string, true);
$id_registrasi_dosen = $result['data'][0]['id_registrasi_dosen'];
$nama_dosen = $result['data'][0]['nama_dosen'];


//Filter MATA_KULIAH
$sks_mata_kuliah=null;
$filter_request = "kode_mata_kuliah ilike '".$kode_mata_kuliah."' and nama_mata_kuliah ilike '%".substr($nama_mata_kuliah,0,6)."%'";	
$data_request = array('act'=>'GetListMataKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
if($result['data']){
	$id_matkul = $result['data'][0]['id_matkul'];
	$bahasan=$result['data'][0]['nama_mata_kuliah'];//WARNING!! 
	
}else{
	//jaga-jaga tidak masuk
	$filter_request = "kode_mata_kuliah ilike '".$kode_mata_kuliah."'";	
	$data_request = array('act'=>'GetListMataKuliah', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data_request, $ctype);
	$result = json_decode($result_string, true);
	$id_matkul = $result['data'][0]['id_matkul'];
	$bahasan=$result['data'][0]['nama_mata_kuliah'];//WARNING!! 
	
}

if($id_matkul==''){$sks_mata_kuliah='EMPTY';}


//EXE KELAS_KULIAH
$filter_request = "id_prodi='".$id_prodi."' AND id_semester='".$id_semester."' AND nama_kelas_kuliah ilike '".$nama_kelas_kuliah."' AND id_matkul = '".$id_matkul."'";
$data_request = array('act'=>'GetDetailKelasKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_kelas_kuliah = $result['data'][0]['id_kelas_kuliah'];

$record['id_registrasi_dosen'] = $id_registrasi_dosen;				
$record['id_jenis_evaluasi'] = '1';
$record['id_kelas_kuliah'] = $id_kelas_kuliah;
$record['rencana_tatap_muka'] = $rencana_tatap_muka ;
$record['realisasi_tatap_muka'] = $realisasi_tatap_muka;
$record['sks_substansi_total'] = $sks_substansi_total ;
//SEMENTARA BELUM ADA FUNGSI UNTUK MEMASUKKAN METHODNYA
//$record['sks_tm_subst'] = $sks_tm_subst;
//$record['sks_prak_subst'] = $sks_prak_subst ;
//$record['sks_prak_lap_subst'] = $sks_prak_lap_subst;
//$record['sks_sim_subst'] = $sks_sim_subst;

//CARI ajar_dosen
$filter_request = "id_registrasi_dosen = '".$id_registrasi_dosen."' and id_kelas_kuliah='".$id_kelas_kuliah."'";
$data_request = array('act'=>'GetDosenPengajarKelasKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_aktivitas_mengajar = $result['data'][0]['id_aktivitas_mengajar'];

$update_ajar_dosen ='';
$insert_ajar_dosen = '';
					
$error_update_ajar_dosen ='';
$error_insert_ajar_dosen = '';

if($id_aktivitas_mengajar!=''){
	$key['id_aktivitas_mengajar'] = $id_aktivitas_mengajar; 
	$data = array('act'=>'UpdateDosenPengajarKelasKuliah', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$update_ajar_dosen = json_decode($result_string, true);

	$error_update_ajar_dosen =$update_ajar_dosen['error_desc'];
	$action = "UPDATE";	
		
}else{
	$data = array('act'=>'InsertDosenPengajarKelasKuliah', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$insert_ajar_dosen = json_decode($result_string, true);
	
	$error_insert_ajar_dosen =$insert_ajar_dosen['error_desc'];
	$action = "INSERT";			
}
					
if($error_insert_ajar_dosen==NULL AND $error_update_ajar_dosen==NULL){
	$LOG=$LOG."[SUCCESS]\tData Ajar dosen dengan NIDN : '".$nidn."'/'".$nama_dosen."'/'".$kode_mata_kuliah."'/'".$nama_kelas_kuliah."' berhasil di-".$action." <br>";	
	$REPORT="[SUCCESS]\tData Ajar dosen dengan NIDN : '".$nidn."'/'".$nama_dosen."'/'".$kode_mata_kuliah."'/'".$nama_kelas_kuliah."' berhasil di-".$action." <br>";	
}else{
	$LOG=$LOG."[Error]\tpada data : NIDN/Kode Matkul/Kelas Label '".$nidn."'/'".$nama_dosen."'/'".$kode_mata_kuliah."'/'".$nama_kelas_kuliah."'/'".$sks_mata_kuliah."' \t ERROR INSERT AJAR DOSEN : '".$error_insert_ajar_dosen."' \t ERROR UPDATE AJAR DOSEN : '".$error_update_ajar_dosen."' <br>------------------------------------<br> ";	
	$REPORT="[Error]\tpada data : NIDN/Kode Matkul/Kelas Label '".$nidn."'/'".$nama_dosen."'/'".$kode_mata_kuliah."'/'".$nama_kelas_kuliah."'/'".$sks_mata_kuliah."' \t ERROR INSERT AJAR DOSEN : '".$error_insert_ajar_dosen."' \t ERROR UPDATE AJAR DOSEN : '".$error_update_ajar_dosen."' <br>------------------------------------<br> ";
}

$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;

?>

