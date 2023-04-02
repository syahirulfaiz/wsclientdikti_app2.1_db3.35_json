<?php

//LIST FUNCTION NO : 64-69
//ERROR CODE : 800-806
//berfungsi untuk memperlihatkan total SKS KRS yang diambil mahasiswa
//inputan hanya nim

//copy ini sebagai header di excel : $nama_jenjang_pendidikan.";".$nama_program_studi.";".$nim.";".$nama_mahasiswa.";".$nama_status_mahasiswa.";".$angkatan.";".$sks_semester.";".$jumlahKRS.";".$sks_total_AKM.";".$status."

include '../init.php';

$nama_file_LOG = $_REQUEST['nama_file_LOG'];

$row = $_REQUEST['row'];

$data = json_decode(stripslashes($_POST['key']),true); // Pakai TRUE agar return array bersih

$nim  = $data['nim'];

$path_directory_LOG = BASE_URL.'/LOG/REKAPKRS';

if (!file_exists($path_directory_LOG)) {
    mkdir($path_directory_LOG, 0777, true);
}

$location_of_LOG=$path_directory_LOG."/".$nama_file_LOG;

if ($row==0) $file_LOG = fopen($location_of_LOG, "w") or die("Unable to open file!");

$LOG =""; // start of LOG
$REPORT	="";

//Filter Mahasiswa
$filter_request = "nim ilike '".trim($nim)."'";
$data_request = array('act'=>'GetListMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$id_prodi = $result['data'][0]['id_prodi'];
$angkatan = $result['data'][0]['id_periode'];
$nama_mahasiswa = $result['data'][0]['nama_mahasiswa'];
$nama_status_mahasiswa = $result['data'][0]['nama_status_mahasiswa'];


//Hitung KRS 
$filter_request = "nim ilike '".trim($nim)."'";
$data_request = array('act'=>'GetRekapKHSMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$sks_semester=0;
$jumlahKRS=0;
$isSudahAmbilSkripsi=false;
foreach($result['data'] as $jumlah_krs){
	$sks_semester=$sks_semester+$jumlah_krs['sks_mata_kuliah'];
	$jumlahKRS=$jumlahKRS+1;
if(strtolower($jumlah_krs['nama_mata_kuliah'])=='skripsi'||strtolower($jumlah_krs['nama_mata_kuliah'])=='thesis'){
		$isSudahAmbilSkripsi=true;
	}
}

//Hitung SKS total AKM 
$filter_request = "nim ilike '".trim($nim)."'";
$order_request = "id_semester desc";
$data_request = array('act'=>'GetAktivitasKuliahMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter_request,
					  'order'=>$order_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$sks_total_AKM = $result['data'][0]['sks_total'];

//cari prodi
$filter_request = "id_prodi = '".$id_prodi."'";
$data_request = array('act'=>'GetProdi', 
					  'token'=>$token, 
					  'filter'=>$filter_request
					  );
$result_string = runWS($data_request, $ctype);
$result = json_decode($result_string, true);
$nama_jenjang_pendidikan = $result['data'][0]['nama_jenjang_pendidikan'];
$nama_program_studi = $result['data'][0]['nama_program_studi'];

$status='sementara normal';
if(($isSudahAmbilSkripsi==true||strtolower($nama_status_mahasiswa)=='lulus') && (($nama_jenjang_pendidikan=='S1' && $sks_semester<144) || ($nama_jenjang_pendidikan=='S2' && $sks_semester<36))){
	$status='SKS KURANG';
}


$LOG = $LOG.$nama_jenjang_pendidikan.";".$nama_program_studi.";".$nim.";".$nama_mahasiswa.";".$nama_status_mahasiswa.";".$angkatan.";".$sks_semester.";".$jumlahKRS.";".$sks_total_AKM.";".$status."<br/>";
$REPORT = $nama_jenjang_pendidikan.";".$nama_program_studi.";".$nim.";".$nama_mahasiswa.";".$nama_status_mahasiswa.";".$angkatan.";".$sks_semester.";".$jumlahKRS.";".$sks_total_AKM.";".$status."<br/>";
$LOG = $row.";".$LOG;



$file_LOG = fopen($location_of_LOG, "a");					
fwrite($file_LOG, $LOG);	


echo $REPORT;
?>

