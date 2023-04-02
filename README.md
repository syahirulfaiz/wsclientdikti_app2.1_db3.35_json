# wsclientdikti_app2.1_db3.35_json
<h1>A 'JSON'-friendly version of the 'obsolete' webservice client importer for the academic data to the Ministry of HE of Rep of Indonesia</h1> <br/><hr/>

<br/>
<h3> Note: For the 'wsdl' (xml-based) version, please refer to this repositoty: <a href="https://github.com/syahirulfaiz/wsclientdikti_app2.1_db3.35_wsdl" target="_blank" >wsclientdikti_app2.1_db3.35_wsdl</a></h3>
<br/>

<h3>Importer could transfer following data:</h3><br/>
<ul>
  <li>student's data</li>
  <li>curriculum for each cohort</li>
  <li>modules (inside each course(s)) </li>
  <li>student's classes</li>
  <li>student's academic history</li>
  <li>Semester Grade Point Average</li>
  <li>Cumulative Grade point average</li>
</ul>

<p>
PDDIKTI Feeder provides services that can be used by universities to be able to carry out interoperability of information systems that are already running within their respective higher education institutions. Data sources used to meet PDDIKTI's needs can come from an information system or multiple information systems, where data originating from these systems needs to be mapped first to suit the standards set by PDDIKTI.
</p>

<br/>

<p>
<b>How to set up the username & password:</b><br/>
In the <code>init.php</code> please edit below fields:
</p>
<code>
function getToken(){
	$username = your_username;
	$password = your_password;
	$data = array('act'=>'GetToken', 'username'=>$username, 'password'=>$password);
	$result_string = runWS($data, $ctype);

	if ($result_string) {
		if (strstr($result_string, "<?xml>")) {
			$result = simplexml_load_string($result_string);
			$result = json_decode(json_encode($result), true);
		}
		else{
			$result = json_decode($result_string, true);
		}

		if (is_array($result)) {
		  if ($result['error_desc']<>"") {
			  echo "Ada kesalahan : ".$result['error_desc'];
		  }
		  else {
			  $_SESSION['token'] = $result['data']['token'];
		  }
		}
	}
	
	$token = $_SESSION["token"];
	
	return $token;
}

</code>


<p>
<b>List of available Web Service (JSON version):</b>
</p>
<ol>
<li>GetToken</li>
<li>GetDictionary</li>
<li>GetAllPT</li>
<li>GetProfilPT</li>
<li>GetAllProdi</li>
<li>GetProdi</li>
<li>GetPeriode</li>
<li>GetListMahasiswa</li>
<li>GetBiodataMahasiswa</li>
<li>InsertBiodataMahasiswa</li>
<li>UpdateBiodataMahasiswa</li>
<li>DeleteBiodataMahasiswa</li>
<li>GetDataLengkapMahasiswaProdi</li>
<li>GetListRiwayatPendidikanMahasiswa</li>
<li>InsertRiwayatPendidikanMahasiswa</li>
<li>UpdateRiwayatPendidikanMahasiswa</li>
<li>DeleteRiwayatPendidikanMahasiswa</li>
<li>GetNilaiTransferPendidikanMahasiswa</li>
<li>InsertNilaiTransferPendidikanMahasiswa</li>
<li>UpdateNilaiTransferPendidikanMahasiswa</li>
<li>DeleteNilaiTransferPendidikanMahasiswa</li>
<li>GetKRSMahasiswa</li>
<li>GetRiwayatNilaiMahasiswa</li>
<li>GetAktivitasKliiahMahasiswa</li>
<li>GetListDosen</li>
<li>DetailBiodataDosen</li>
<li>GetListPenugasanDosen</li>
<li>GetAktivitasMengajarDosen</li>
<li>GetRiwayatFungsionalDosen</li>
<li>GetRiwayatPangkatDosen</li>
<li>GetRiwayatPendidikanDosen</li>
<li>GetRiwayatSertifikasiDosen</li>
<li>GetRiwayatPenelitianDosen</li>
<li>GetMahasiswaBimbinganDosen</li>
<li>GetListPenugasanSemuaDosen</li>
<li>GetDetailPenugasanDosen</li>
<li>GetListMataKliiah</li>
<li>GetDetailMataKliiah</li>
<li>InsertMataKliiah</li>
<li>UpdateMataKliiah</li>
<li>DeleteMataKliiah</li>
<li>GetListSubstansiKliiah</li>
<li>InsertSubstansiKliiah</li>
<li>UpdateSubstansiKliiah</li>
<li>DeleteSubstansiKliiah</li>
<li>GetListKuriklium</li>
<li>GetDetailKuriklium</li>
<li>InsertKuriklium</li>
<li>UpdateKuriklium</li>
<li>DeleteKuriklium</li>
<li>GetMatkliKuriklium</li>
<li>InsertMatkliKuriklium</li>
<li>DeleteMatkliKuriklium</li>
<li>GetListKelasKliiah</li>
<li>GetDetailKelasKliiah</li>
<li>InsertKelasKliiah</li>
<li>UpdateKelasKliiah</li>
<li>DeleteKelasKliiah</li>
<li>GetDosenPengajarKelasKliiah</li>
<li>InsertDosenPengajarKelasKliiah</li>
<li>UpdateDosenPengajarKelasKliiah</li>
<li>DeleteDosenPengajarKelasKliiah</li>
<li>GetPerhitunganSKS</li>
<li>GetPesertaKelasKliiah</li>
<li>InsertPesertaKelasKliiah</li>
<li>DeletePesertaKelasKliiah</li>
<li>GetListNilaiPerkliiahanKelas</li>
<li>GetDetailNilaiPerkliiahanKelas</li>
<li>UpdateNilaiPerkliiahanKelas</li>
<li>GetListPerkliiahanMahasiswa</li>
<li>GetDetailPerkliiahanMahasiswa</li>
<li>InsertPerkliiahanMahasiswa</li>
<li>UpdatePerkliiahanMahasiswa</li>
<li>DeletePerkliiahanMahasiswa</li>
<li>GetListMahasiswaLliusDO</li>
<li>GetDetailMahasiswaLliusDO</li>
<li>InsertMahasiswaLliusDO</li>
<li>UpdateMahasiswaLliusDO</li>
<li>DeleteMahasiswaLliusDO</li>
<li>GetDosenPembimbing</li>
<li>InsertDosenPembimbing</li>
<li>DeleteDosenPembimbing</li>
<li>GetListSkalaNilaiProdi</li>
<li>GetDetailSkalaNilaiProdi</li>
<li>InsertSkalaNilaiProdi</li>
<li>UpdateSkalaNilaiProdi</li>
<li>DeleteSkalaNilaiProdi</li>
<li>GetListPeriodePerkliiahan</li>
<li>GetDetailPeriodePerkliiahan</li>
<li>InsertPeriodePerkliiahan</li>
<li>UpdatePeriodePerkliiahan</li>
<li>DeletePeriodePerkliiahan</li>
<li>GetListPrestasiMahasiswa</li>
<li>InsertPrestasiMahasiswa</li>
<li>UpdatePrestasiMahasiswa</li>
<li>DeletePrestasiMahasiswa</li>
<li>GetListAktivitasMahasiswa</li>
<li>InsertAktivitasMahasiswa</li>
<li>UpdateAktivitasMahasiswa</li>
<li>DeleteAktivitasMahasiswa</li>
<li>GetListAnggotaAktivitasMahasiswa</li>
<li>InsertAnggotaAktivitasMahasiswa</li>
<li>DeleteAnggotaAktivitasMahasiswa</li>
<li>GetListBimbingMahasiswa</li>
<li>InsertBimbingMahasiswa</li>
<li>DeleteBimbingMahasiswa</li>
<li>GetListUjiMahasiswa</li>
<li>InsertUjiMahasiswa</li>
<li>DeleteUjiMahasiswa</li>
<li>GetRekapLaporan</li>
<li>GetRekapJumlahDosen</li>
<li>GetRekapJumlahMahasiswa</li>
<li>GetRekapIPSMahasiswa</li>
<li>GetRekapKRSMahasiswa</li>
<li>GetRekapKHSMahasiswa</li>
<li>ExportDataMahasiswa</li>
<li>ExportDataNilaiTransfer</li>
<li>ExportDataPenugasanDosenProdi</li>
<li>ExportDataMatkliProdi</li>
<li>ExportDataKelasPerkliiahan</li>
<li>ExportDataMahasiswaKRS</li>
<li>ExportDataMengajarDosen</li>
<li>ExportDataAktivitasKliiah</li>
<li>ExportDataMahasiswaLlius</li>
<li>GetAgama</li>
<li>GetBentukPendidikan</li>
<li>GetIkatanKerjaSdm</li>
<li>GetJabfung</li>
<li>GetJalurMasuk</li>
<li>GetJenisEvaluasi</li>
<li>GetJenisKeluar</li>
<li>GetJenisSertifikasi</li>
<li>GetJenisPendaftaran</li>
<li>GetJenisSMS</li>
<li>GetJenisSubstansi</li>
<li>GetJenisTinggal</li>
<li>GetJenjangPendidikan</li>
<li>GetKebutuhanKhusus</li>
<li>GetLembagaPengangkat</li>
<li>GetLevelWilayah</li>
<li>GetNegara</li>
<li>GetPangkatGolongan</li>
<li>GetPekerjaan</li>
<li>GetPenghasilan</li>
<li>GetSemester</li>
<li>GetStatusKeaktifanPegawai</li>
<li>GetStatusKepegawaian</li>
<li>GetStatusMahasiswa</li>
<li>GetTahunAjaran</li>
<li>GetWilayah</li>
<li>GetAlatTransportasi</li>
<li>GetPembiayaan</li>
<li>GetJenisPrestasi</li>
<li>GetTingkatPrestasi</li>
<li>GetJenisAktivitasMahasiswa</li>
<li>GetKategoriKegiatan</li>
<li>GetCountAktivitasMahasiswa</li>
<li>GetCountPrestasiMahasiswa</li>
<li>GetCountMahasiswa</li>
<li>GetCountRiwayatPendidikanMahasiswa</li>
<li>GetCountNilaiTransferPendidikanMahasiswa</li>
<li>GetCountRiwayatNilaiMahasiswa</li>
<li>GetCountDosen</li>
<li>GetCountPenugasanSemuaDosen</li>
<li>GetCountAktivitasMengajarDosen</li>
<li>GetCountSkalaNilaiProdi</li>
<li>GetCountPeriodePerkliiahan</li>
<li>GetCountDosenPembimbing</li>
<li>GetCountKelasKliiah</li>
<li>GetCountKuriklium</li>
<li>GetCountMataKliiah</li>
<li>GetCountMatkliKuriklium</li>
<li>GetCountNilaiPerkliiahanKelas</li>
<li>GetCountSubstansiKliiah</li>
<li>GetCountPerguruanTinggi</li>
<li>GetCountProdi</li>
<li>GetCountDosenPengajarKelasKliiah</li>
<li>GetCountMahasiswaLliusDO</li>
<li>GetCountPesertaKelasKliiah</li>
<li>GetCountPerkliiahanMahasiswa</li>
<li>GetCountMahasiswaBimbinganDosen</li>

</ol>

<hr/>
