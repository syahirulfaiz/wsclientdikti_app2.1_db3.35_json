<?php

//LIST FUNCTION NO : 64-69
//ERROR CODE : 800-806

//SEMENTARA ditambahkan $sks_mata_kuliah untuk penanda kalau ada error tidak masuk krn SKS 0

include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$kode_program_studi = $data['kode_program_studi'];
$kode_mata_kuliah = $data['kode_mata_kuliah'];
$nama_mata_kuliah = $data['nama_mata_kuliah'];
$nama_kelas_kuliah = $data['nama_kelas_kuliah'];
$nim  = $data['nim'];
$id_semester  = $data['id_semester'];

$nilai_huruf = $data['nilai_huruf'];								
$nilai_indeks = $data['nilai_indeks'];
$nilai_angka = $data['nilai_angka'];

$path_directory_LOG = BASE_URL.'/LOG/'.$id_semester.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_semester."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG
		
if ($kode_mata_kuliah=='[000]'){
	//Filter KRS semester
	$filter_request = "nim ilike '".$nim."' and id_periode ilike '".$id_semester."'";
	$data_request = array('act'=>'GetKRSMahasiswa', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data_request, $ctype);
	$result = json_decode($result_string, true);

	$array_mata_kuliah= array();

	foreach($result['data'] as $temp1){
		$array_mata_kuliah[]=$temp1['kode_mata_kuliah'];
	}

	//DELETE KRS
	foreach($array_mata_kuliah as $temp2){
		//Filter KRS semester
		$filter_request = "nim ilike '".$nim."' and id_periode ilike '".$id_semester."' and kode_mata_kuliah ilike '".$temp2."'";
		
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
			$LOG=$LOG."[SUCCESS]\tData NIM / Kode MataKuliah ".$nim." / ".$temp2." berhasil di-".$action." <br>";	
			$REPORT=$REPORT."[SUCCESS]\tData NIM / Kode MataKuliah ".$nim." / ".$temp2." berhasil di-".$action." <br>";	
		}else{
			$LOG=$LOG."[Error]\tData ( ".$nim." / ".$temp2." \t DESC DELETE : ".$error_delete_nilai." <br>------------------------------------<br> ";	
			$REPORT=$REPORT."[Error]\tData ( ".$nim." / ".$temp2." \t DESC DELETE : ".$error_delete_nilai." <br>------------------------------------<br> ";
		}
	}
}else{
	
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


	//Filter Kelas KUliah
	$filter_request = "id_prodi='".$id_prodi."' AND id_semester='".$id_semester."' AND nama_kelas_kuliah ilike '".$nama_kelas_kuliah."' AND id_matkul = '".$id_matkul."'";
	$data_request = array('act'=>'GetDetailKelasKuliah', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data_request, $ctype);
	$result = json_decode($result_string, true);
	$id_kelas_kuliah = $result['data'][0]['id_kelas_kuliah'];

	//Filter Mahasiswa PT 
	$filter_request = "nim ilike '%".$nim."%'";
	$data = array('act'=>'GetListRiwayatPendidikanMahasiswa', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data, $ctype);
	$result = json_decode($result_string, true);
	$id_registrasi_mahasiswa = $result['data'][0]['id_registrasi_mahasiswa'];
	$nama_mahasiswa = $result['data'][0]['nama_mahasiswa'];
	
	//cari data nilai
	$filter_request = "id_registrasi_mahasiswa='".$id_registrasi_mahasiswa."' AND id_kelas_kuliah='".$id_kelas_kuliah."'";
	$data = array('act'=>'GetPesertaKelasKuliah', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data, $ctype);
	$result = json_decode($result_string, true);
	$id_registrasi_mahasiswa_Nilai = $result['data'][0]['id_registrasi_mahasiswa'];
	$id_kelas_kuliah_Nilai = $result['data'][0]['id_kelas_kuliah'];

	$record['nilai_angka'] = $nilai_angka;
	$record['nilai_huruf'] = $nilai_huruf ;
	$record['nilai_indeks'] = $nilai_indeks;
						
	$insert_nilai='';
	
	$error_insert_nilai='';	
		
	
		$record['id_kelas_kuliah']= $id_kelas_kuliah;
		$record['id_registrasi_mahasiswa']= $id_registrasi_mahasiswa;
		$data = array('act'=>'InsertPesertaKelasKuliah', 
				'token'=>$token,
				'record'=>$record,
				 );
		$result_string = runWS($data, $ctype);
		$insert_nilai = json_decode($result_string, true);
		
		$error_insert_nilai =$insert_nilai['error_desc'];
		$action = "INSERT";		
	
					
	if($error_insert_nilai==NULL ){
		$LOG=$LOG."[SUCCESS]\tData NIM / Nama ".$nim." / ".$nama_mahasiswa." / ".$kode_mata_kuliah." / ".$nama_kelas_kuliah." berhasil di-".$action." <br>";	
		$REPORT="[SUCCESS]\tData NIM / Nama ".$nim." / ".$nama_mahasiswa." / ".$kode_mata_kuliah." / ".$nama_kelas_kuliah." berhasil di-".$action." <br>";	
	}else{
		$LOG=$LOG."[Error]\tData ( ".$nim." / ".$kode_mata_kuliah." / ".$nama_kelas_kuliah." / ".$sks_mata_kuliah." ) \t DESC INSERT : ".$error_insert_nilai." <br>------------------------------------<br> ";	
		$REPORT="[Error]\tData ( ".$nim." / ".$kode_mata_kuliah." / ".$nama_kelas_kuliah." / ".$sks_mata_kuliah." ) \t DESC INSERT : ".$error_insert_nilai." <br>------------------------------------<br> ";
	}
}
				
$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;


?>

