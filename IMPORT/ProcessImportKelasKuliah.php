<?php
//LIST FUNCTION NO : 54-58
//ERROR CODE : 700-712

//untuk sementara belum ketemu lewat fungsi apa memasukkan ini
//$record['sks_mk'] = $sks_mk;
//$record['sks_tm'] = $sks_tm;
//$record['sks_prak'] = $sks_prak;
//$record['sks_prak_lap'] = $sks_prak_lap;
//$record['sks_sim'] = $sks_sim;

//SEMENTARA ditambahkan $sks_mata_kuliah untuk penanda kalau ada error tidak masuk krn SKS 0


include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$id_semester = $data['id_semester'];
$kode_mata_kuliah = $data['kode_mata_kuliah'];
$nama_mata_kuliah = $data['nama_mata_kuliah'];
$nama_kelas_kuliah = $data['nama_kelas_kuliah'];
$kode_program_studi = $data['kode_program_studi'];

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

//set periode kelas kuliah
$filter_request = "id_semester = '".$id_semester."'";
$data_request = array('act'=>'GetSemester', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$tanggal_mulai_efektif = $result['data'][0]['tanggal_mulai'];
$tanggal_akhir_efektif = $result['data'][0]['tanggal_selesai'];

//EXE KELAS_KULIAH
$filter_request = "id_prodi='".$id_prodi."' AND id_semester='".$id_semester."' AND nama_kelas_kuliah ilike '".$nama_kelas_kuliah."' AND id_matkul = '".$id_matkul."'";
$data_request = array('act'=>'GetDetailKelasKuliah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_kelas_kuliah = $result['data'][0]['id_kelas_kuliah'];


$record['id_prodi'] = $id_prodi ;
$record['id_semester'] = $id_semester;
$record['nama_kelas_kuliah'] = $nama_kelas_kuliah;
$record['id_matkul'] = $id_matkul;
$record['bahasan'] = $bahasan;
$record['tanggal_mulai_efektif'] = $tanggal_mulai_efektif;
$record['tanggal_akhir_efektif'] = $tanggal_akhir_efektif;

//untuk sementara belum ketemu lewat fungsi apa memasukkan ini
//$record['sks_mk'] = $sks_mk;
//$record['sks_tm'] = $sks_tm;
//$record['sks_prak'] = $sks_prak;
//$record['sks_prak_lap'] = $sks_prak_lap;
//$record['sks_sim'] = $sks_sim;

$update_kelas_kuliah ='';
$insert_kelas_kuliah = '';
					
$error_update_kelas_kuliah ='';
$error_insert_kelas_kuliah = '';

if($id_kelas_kuliah !=''){
	$key['id_kelas_kuliah'] = $id_kelas_kuliah; 
	$data = array('act'=>'UpdateKelasKuliah', 
			'token'=>$token,
			'key'=>$key,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$update_kelas_kuliah = json_decode($result_string, true);

	$error_update_kelas_kuliah =$update_kelas_kuliah['error_desc'];
	$action = "UPDATE";		
}else{
	$data = array('act'=>'InsertKelasKuliah', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data, $ctype);
	$insert_kelas_kuliah = json_decode($result_string, true);
	
	$error_insert_kelas_kuliah =$insert_kelas_kuliah['error_desc'];
	$action = "INSERT";		
}


if($error_insert_kelas_kuliah==NULL AND $error_update_kelas_kuliah==NULL){
	$LOG=$LOG."[SUCCESS]\tData Kelas dengan Kode Matakuliah/Kelas '".$kode_mata_kuliah."'/'".$nama_kelas_kuliah."' berhasil di-".$action." <br>";	
	$REPORT="[SUCCESS]\tData Kelas dengan Kode Matakuliah/Kelas '".$kode_mata_kuliah."'/'".$nama_kelas_kuliah."' berhasil di-".$action." <br>";	
}else{
	$LOG=$LOG."[Error]\tData Kelas dengan Kode Matakuliah/Kelas/SKS ".$kode_mata_kuliah."/".$nama_kelas_kuliah."/".$sks_mata_kuliah." \t DESC INSERT KELAS KULIAH : ".$error_insert_kelas_kuliah." \t DESC UPDATE KELAS KULIAH : ".$error_update_kelas_kuliah." <br>------------------------------------<br> ";	
	$REPORT="[Error]\tData Kelas dengan Kode Matakuliah/Kelas/SKS ".$kode_mata_kuliah."/".$nama_kelas_kuliah."/".$sks_mata_kuliah." \t DESC INSERT KELAS KULIAH : ".$error_insert_kelas_kuliah." \t DESC UPDATE KELAS KULIAH : ".$error_update_kelas_kuliah." <br>------------------------------------<br> ";
}

$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;



?>

