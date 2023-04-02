<?php
//LIST FUNCTION NO : 9-17
//ERROR CODE : 109 - 224

include '../init.php';

/*
yang BELUM (!!!):
-id_jns_tinggal
-id_alat_transport
-skhun
-no_peserta_ujian
-a_pernah_paud
-a_pernah_tk
-sert_prof
-a_pindah_mhs_asing
-id_pt_asal
-id_prodi_asal
-id_jalur_masuk
-id_pembiayaan

*/

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$kode_program_studi = $data['kode_program_studi']; 
$id_periode_masuk = $data['id_periode_masuk'];

$path_directory_LOG = BASE_URL.'/LOG/'.$id_periode_masuk.'/'.$kode_program_studi;

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$id_periode_masuk."-".$kode_program_studi."-".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG

$nama_mahasiswa= $data['nama_mahasiswa']; 
$tempat_lahir= $data['tempat_lahir'];
$jenis_kelamin= $data['jenis_kelamin']; 
$tanggal_lahir= date('Y-m-d',strtotime($data['tanggal_lahir']));
$id_agama= $data['id_agama']; 
$nama_ibu_kandung= $data['nama_ibu_kandung']; 
$nik= $data['nik'];
$kewarganegaraan= $data['kewarganegaraan'];
$kelurahan= $data['kelurahan']; 
$id_wilayah= $data['id_wilayah']; 
$id_kebutuhan_khusus_ayah= $data['id_kebutuhan_khusus_ayah']; 
$id_kebutuhan_khusus_ibu= $data['id_kebutuhan_khusus_ibu']; 
$id_kebutuhan_khusus_mahasiswa= $data['id_kebutuhan_khusus_mahasiswa']; 
$penerima_kps= $data['penerima_kps']; 

$nim = $data['nim']; 
$id_jenis_daftar =$data['id_jenis_daftar'];
$tanggal_daftar = date('Y-m-d',strtotime($data['tanggal_daftar']));

$sks_diakui= $data['sks_diakui']; 
$nisn= $data['nisn']; 
$jalan= $data['jalan'];
$rt= $data['rt']; 
$rw= $data['rw']; 
$dusun= $data['dusun']; 
$kode_pos= $data['kode_pos']; 
$telepon= $data['telepon']; 
$handphone= $data['handphone']; 
$email = $data['email']; 

$nomor_kps = $data['nomor_kps'];
$npwp = $data['npwp']; 

$nama_ayah= $data['nama_ayah']; 
$nik_ayah= $data['nik_ayah']; 
$tanggal_lahir_ayah= date('Y-m-d',strtotime($data['tanggal_lahir_ayah']));

//Jenjang Pendidikan Ayah
$id_pendidikan_ayah= $data['id_pendidikan_ayah'];
$filter_request =  "nama_jenjang_didik ilike '%".trim($id_pendidikan_ayah)."%' ";
$data_request = array('act'=>'GetJenjangPendidikan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_pendidikan_ayah = $result['data'][0]['id_jenjang_didik'];

//Pekerjaan Ayah
$id_pekerjaan_ayah= $data['id_pekerjaan_ayah']; 
$filter_request = "nama_pekerjaan ilike '%".trim($id_pekerjaan_ayah)."%' ";
$data_request = array('act'=>'GetPekerjaan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_pekerjaan_ayah = $result['data'][0]['id_pekerjaan'];

//Penghasilan Ayah
//terpaksa bikin hardcode

$id_penghasilan_ayah= $data['id_penghasilan_ayah']; 
if($id_penghasilan_ayah==''||$id_penghasilan_ayah=='0'){
	$id_penghasilan_ayah = '0';
}elseif($id_penghasilan_ayah < 500000){
	$id_penghasilan_ayah = '11';
}elseif (500000 <= $id_penghasilan_ayah && $id_penghasilan_ayah <=999999) {
	$id_penghasilan_ayah = '12';
}elseif (1000000 <= $id_penghasilan_ayah && $id_penghasilan_ayah <=1999999) {
	$id_penghasilan_ayah = '13';
}elseif (2000000 <= $id_penghasilan_ayah && $id_penghasilan_ayah <=4999999) {
	$id_penghasilan_ayah = '14';
}elseif (5000000 <= $id_penghasilan_ayah && $id_penghasilan_ayah <=20000000) {
	$id_penghasilan_ayah = '15';
}elseif (20000000 < $id_penghasilan_ayah) {
	$id_penghasilan_ayah = '16';
}else{
	$id_penghasilan_ayah = '0';
}	

$nik_ibu= $data['nik_ibu']; 
$tanggal_lahir_ibu= date('Y-m-d',strtotime($data['tanggal_lahir_ibu']));

//Jenjang Pendidikan Ibu
$id_pendidikan_ibu= $data['id_pendidikan_ibu'];
$filter_request =  "nama_jenjang_didik ilike '%".trim($id_pendidikan_ibu)."%' ";
$data_request = array('act'=>'GetJenjangPendidikan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_pendidikan_ibu = $result['data'][0]['id_jenjang_didik'];

//Pekerjaan Ibu
$id_pekerjaan_ibu= $data['id_pekerjaan_ibu']; 
$filter_request = "nama_pekerjaan ilike '%".trim($id_pekerjaan_ibu)."%' ";
$data_request = array('act'=>'GetPekerjaan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_pekerjaan_ibu = $result['data'][0]['id_pekerjaan'];

//Penghasilan Ibu
$id_penghasilan_ibu= $data['id_penghasilan_ibu'];
if($id_penghasilan_ibu==''||$id_penghasilan_ibu=='0'){
	$id_penghasilan_ibu = '0';
}elseif($id_penghasilan_ibu < 500000){
	$id_penghasilan_ibu = '11';
}elseif (500000 <= $id_penghasilan_ibu && $id_penghasilan_ibu <=999999) {
	$id_penghasilan_ibu = '12';
}elseif (1000000 <= $id_penghasilan_ibu && $id_penghasilan_ibu <=1999999) {
	$id_penghasilan_ibu = '13';
}elseif (2000000 <= $id_penghasilan_ibu && $id_penghasilan_ibu <=4999999) {
	$id_penghasilan_ibu = '14';
}elseif (5000000 <= $id_penghasilan_ibu && $id_penghasilan_ibu <=20000000) {
	$id_penghasilan_ibu = '15';
}elseif (20000000 < $id_penghasilan_ibu) {
	$id_penghasilan_ibu = '16';
}else{
	$id_penghasilan_ibu = '0';
}	

//Data Wali
$nama_wali= $data['nama_wali']; 
$tanggal_lahir_wali= date('Y-m-d',strtotime($data['tanggal_lahir_wali']));

//Pendidikan Wali
$id_pendidikan_wali= $data['id_pendidikan_wali'];
$filter_request =  "nama_jenjang_didik ilike '%".trim($id_pendidikan_wali)."%' ";
$data_request = array('act'=>'GetJenjangPendidikan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_pendidikan_wali = $result['data'][0]['id_jenjang_didik'];


//Pekerjaan Wali
$id_pekerjaan_wali= $data['id_pekerjaan_wali']; 
$filter_request = "nama_pekerjaan ilike '%".trim($id_pekerjaan_wali)."%' ";
$data_request = array('act'=>'GetPekerjaan', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_pekerjaan_wali = $result['data'][0]['id_pekerjaan'];


//Penghasilan Wali
$id_penghasilan_wali=  $data['id_penghasilan_wali']; 
if($id_penghasilan_wali==''||$id_penghasilan_wali=='0'){
	$id_penghasilan_wali = '0';
}elseif($id_penghasilan_wali < 500000){
	$id_penghasilan_wali = '11';
}elseif (500000 <= $id_penghasilan_wali && $id_penghasilan_wali <=999999) {
	$id_penghasilan_wali = '12';
}elseif (1000000 <= $id_penghasilan_wali && $id_penghasilan_wali <=1999999) {
	$id_penghasilan_wali = '13';
}elseif (2000000 <= $id_penghasilan_wali && $id_penghasilan_wali <=4999999) {
	$id_penghasilan_wali = '14';
}elseif (5000000 <= $id_penghasilan_wali && $id_penghasilan_wali <=20000000) {
	$id_penghasilan_wali = '15';
}elseif (20000000 < $id_penghasilan_wali) {
	$id_penghasilan_wali = '16';
}else{
	$id_penghasilan_wali = '0';
}	

//id_wilayah
//id_wilayah mempunyai format 6 digit '000000'
//2 digit depan : propinsi
//2 digit tengah : kabupaten
//2 digit belakang : kecamatan

$array_wilayah = explode(',', $data['id_wilayah']);
//propinsi
$filter_request = "nama_wilayah ilike '%".trim($array_wilayah[2])."%' ";
$data_request = array('act'=>'GetWilayah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_propinsi = $result['data'][0]['id_wilayah'];


//kabupaten
$filter_request = "nama_wilayah ilike '%".trim($array_wilayah[1])."%' and substr(id_wilayah,1,2) = substr('".$id_propinsi."',1,2) " ;
$data_request = array('act'=>'GetWilayah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_kabupaten = $result['data'][0]['id_wilayah'];

//kecamatan
$filter_request = "nama_wilayah ilike '%".trim($array_wilayah[0])."%' and substr(id_wilayah,1,4) = substr('".$id_kabupaten."',1,4) " ;
$data_request = array('act'=>'GetWilayah', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_wilayah = $result['data'][0]['id_wilayah'];
if($result['data'][0]['id_wilayah']){
		$id_wilayah = $result['data'][0]['id_wilayah'];	
	}else{
		$id_wilayah = '000000';
	}
	
$record['nama_mahasiswa']= $nama_mahasiswa; 
$record['tempat_lahir']= $tempat_lahir; 
$record['jenis_kelamin']= $jenis_kelamin; 
$record['tanggal_lahir']= $tanggal_lahir; 
$record['id_agama']= $id_agama; 
$record['nama_ibu_kandung']= $nama_ibu_kandung;  
$record['nik']= $nik; 
$record['kewarganegaraan']= $kewarganegaraan; 
$record['kelurahan']= $kelurahan; 
$record['id_wilayah']= $id_wilayah;
$record['id_kebutuhan_khusus_ayah']= $id_kebutuhan_khusus_ayah; 
$record['id_kebutuhan_khusus_ibu']= $id_kebutuhan_khusus_ibu; 
$record['id_kebutuhan_khusus_mahasiswa']= $id_kebutuhan_khusus_mahasiswa; 
$record['penerima_kps']= $penerima_kps; 
$record['nisn']= $nisn; 
$record['jalan']= $jalan; 
$record['rt']= $rt; 
$record['rw']= $rw; 
$record['dusun']= $dusun; 
$record['kode_pos']= $kode_pos; 
$record['telepon']= $telepon;
$record['handphone']= $handphone;
$record['email'] = $email;

$record['nomor_kps'] = $nomor_kps;
$record['npwp'] = $npwp;

$record['nama_ayah']= $nama_ayah; 
$record['nik_ayah']= $nik_ayah; 
$record['tanggal_lahir_ayah']= $tanggal_lahir_ayah; 
$record['id_pendidikan_ayah']= $id_pendidikan_ayah;
$record['id_pekerjaan_ayah']= $id_pekerjaan_ayah;
$record['id_penghasilan_ayah']= $id_penghasilan_ayah;

$record['nik_ibu']= $nik_ibu; 
$record['tanggal_lahir_ibu']= $tanggal_lahir_ibu; 
$record['id_pendidikan_ibu']= $id_pendidikan_ibu;
$record['id_pekerjaan_ibu']= $id_pekerjaan_ibu;
$record['id_penghasilan_ibu']= $id_penghasilan_ibu;

if($nama_wali!='-' && $nama_wali!='' ){
	$record['nama_wali']= $nama_wali; 
	$record['tanggal_lahir_wali']= $tanggal_lahir_wali; 
	$record['id_pendidikan_wali']= $id_pendidikan_wali;
	$record['id_pekerjaan_wali']= $id_pekerjaan_wali;
	$record['id_penghasilan_wali']= $id_penghasilan_wali; //on progress. tidak bisa handle string kosong
}


//Mahasiswa dengan nama, tempat, tanggal lahir dan ibu kandung yang sama sudah ada
//[WARNING] kolom nama ibu di Get,Insert dan UpdateBiodata berbeda beda!!	
//cek data id_mahasiswa ada tidak, kalau ada -> insert id_reg_pd, kalau tidak ada -> insert id_mahasiswa, id_reg_pd	
$filter_request = "nama_mahasiswa ilike '%".$nama_mahasiswa."%' and tempat_lahir ilike '%".$tempat_lahir."%' and tanggal_lahir = '".$tanggal_lahir."' and nama_ibu ilike '%".$nama_ibu_kandung."%'";
$data_request = array('act'=>'GetBiodataMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_mahasiswa = $result['data'][0]['id_mahasiswa'];

$record_pt['id_mahasiswa'] = $id_mahasiswa;

//cari prodi
$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
$data_request = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];

$record_pt['id_prodi'] = $id_prodi;

//cari profil PT
$filter_request = "	kode_perguruan_tinggi ilike '%202006%'";
$data_request = array('act'=>'GetProfilPT', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_perguruan_tinggi = $result['data'][0]['id_perguruan_tinggi'];

$record_pt['id_perguruan_tinggi'] = $id_perguruan_tinggi;

$record_pt['id_jenis_daftar'] = $id_jenis_daftar;
$record_pt['nim'] = $nim;
$record_pt['tanggal_daftar'] = $tanggal_daftar;
//$record_pt['a_pernah_tk'] = 1;
//$record_pt['a_pernah_paud'] = 1;
$record_pt['id_periode_masuk'] = $id_periode_masuk;
$record_pt['sks_diakui'] = $sks_diakui;

if($id_mahasiswa!=''){
	
	$data_request = array('act'=>'InsertRiwayatPendidikanMahasiswa', 
			'token'=>$token,
			'record'=>$record_pt,
			 );
	$result_string = runWS($data_request, $ctype);
	$insert_mahasiswa_pt = json_decode($result_string, true);
	$action = "INSERT";	
	
	if($insert_mahasiswa_pt['error_desc']==NULL){
		$LOG=$LOG."[SUCCESS]\tInput Mahasiswa Lama. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." berhasil di ".$action." <br>";
		$REPORT = "[SUCCESS]\tInput Mahasiswa Lama. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." berhasil di ".$action." <br>";
	}
	else{
		$LOG=$LOG."[Error]\tInput Mahasiswa Lama. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." - ".$insert_mahasiswa_pt['error_desc']."<br>";	
		$REPORT="[Error]\tInput Mahasiswa Lama. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." - ".$insert_mahasiswa_pt['error_desc']."<br>";	
	}
	
	//echo "ada";
}else{
	
	$data_request = array('act'=>'InsertBiodataMahasiswa', 
			'token'=>$token,
			'record'=>$record,
			 );
	$result_string = runWS($data_request, $ctype);
	$insert_mahasiswa = json_decode($result_string, true);
	$action = "INSERT";	
	if($insert_mahasiswa['error_desc']==NULL){
		$LOG=$LOG."[SUCCESS]\tInput Mahasiswa Baru. input data mahasiswa. Data ".$nama_mahasiswa." / ".$nim." berhasil di ".$action." <br>";
		$REPORT = "[SUCCESS]\tInput Mahasiswa Baru. input data mahasiswa. Data ".$nama_mahasiswa." / ".$nim." berhasil di ".$action." <br>";
	}else{
		$LOG=$LOG."[Error]\tInput Mahasiswa Baru. input data mahasiswa. Data ".$nama_mahasiswa." / ".$nim." - ".$insert_mahasiswa['error_desc']."<br>";	
		$REPORT="[Error]\tInput Mahasiswa Baru. input data mahasiswa. Data ".$nama_mahasiswa." / ".$nim." - ".$insert_mahasiswa['error_desc']."<br>";			
	}
	
	
	//cari id_mahasiswa yang baru saja masuk
	$filter_request = "nama_mahasiswa ilike '%".$nama_mahasiswa."%' and tempat_lahir ilike '%".$tempat_lahir."%' and tanggal_lahir = '".$tanggal_lahir."' and nama_ibu ilike '%".$nama_ibu_kandung."%'";
	$data_request = array('act'=>'GetBiodataMahasiswa', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data_request, $ctype);
	$result = json_decode($result_string, true);
	$id_mahasiswa = $result['data'][0]['id_mahasiswa'];

	$record_pt['id_mahasiswa'] = $id_mahasiswa;
	
	//cari id_prodi yang baru saja masuk
	$filter_request = "kode_program_studi ilike '%".$kode_program_studi."%'";
	$data_request = array('act'=>'GetProdi', 
						  'token'=>$token, 
						  'filter'=>$filter_request
						  );
	$result_string = runWS($data_request, $ctype);
	$result = json_decode($result_string, true);
	$id_prodi = $result['data'][0]['id_prodi'];

	$record_pt['id_prodi'] = $id_prodi;
	
	$data_request = array('act'=>'InsertRiwayatPendidikanMahasiswa', 
			'token'=>$token,
			'record'=>$record_pt,
			 );
	$result_string = runWS($data_request, $ctype);
	$insert_mahasiswa_pt = json_decode($result_string, true);
	$action = "INSERT";	
	
	if($insert_mahasiswa_pt['error_desc']==NULL){
		$LOG=$LOG."[SUCCESS]\tInput Mahasiswa Baru. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." berhasil di ".$action." <br>";
		$REPORT="[SUCCESS]\tInput Mahasiswa Baru. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." berhasil di ".$action." <br>";
		}
	else{
		$LOG=$LOG."[Error]\tInput Mahasiswa Baru. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." - ".$insert_mahasiswa_pt['error_desc']."<br>";	
		$REPORT="[Error]\tInput Mahasiswa Baru. input data mahasiswa_pt. Data ".$nama_mahasiswa." / ".$nim." - ".$insert_mahasiswa_pt['error_desc']."<br>";	
	}
	
	//echo "tidak ada";	
}




$LOG = $row." : ".$LOG;

$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	

echo $REPORT;



?>

