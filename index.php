<?php 
error_reporting(0); 
include 'init.php';
?>

<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo NAMA_APLIKASI; ?></title>
		<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>print.css" type="text/css" />
		<script src="<?php echo TEMPLATE_URL; ?>jquery/jquery.min.js"></script>
    </head>
    <body>	
	<?php echo "Token saat ini : ". $token; ?>

	
	
	
        <h1><?php echo NAMA_APLIKASI; ?></h1>
        
		<h3>
		Lokasi web service: <br/><a href="<?= $url ?>" target="_blank"><?= $url ?></a>
		<br/>
		<?php
		$url_arr = explode('?', $url);
		$url2 = $url_arr[0];
		?>
		</h3>
		
		<h3>
		Lokasi template & converter: <br/><a href="resource/SYNC_2_WSIAIN.accdb" target="_blank">download</a>
		</h3>
		
		<h3>
		<b>* Pastikan setiap semester selalu cek, apakah jumlah fungsi di <a href='FUNCTION/GetDictionary.php' target='_blank'>Dictionary</a> dan di <a href="<?= $url ?>" target="_blank">ws</a> SAMA JUMLAHNYA!!!</b> 
		</h3>
		
        <table class="data_grid">
            <tr>
                <th colspan="3">
				Fungsi yang disediakan<br/>
				<? echo '<a href="'.$url2 .'" target="_blank">'.$url2 . '</a>'; ?>
				</th>
            </tr>
			<tr>
				<th>Fungsi</th>
				<th>Keterangan</th>
				<th>Parameter</th>
			</tr>
			<tr>
                <td><a href="IMPORT/ImportBobotNilai.php" target="blank">IMPORT BOBOT NILAI</a></td>
				<td><b>[MASTER]<b> DIIMPORT 1 kali</td>
				<td>
					<b>(v)</b>				
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportMahasiswaBaru.php" target="blank">IMPORT MAHASISWA BARU</a></td>
				<td><b>[MASTER]<b> DIIMPORT 1 kali</td>
				<td>
				<b>(v)</b>
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportMahasiswaLulus.php" target="blank">IMPORT MAHASISWA LULUS</a></td>
				<td><b>[MASTER]<b> DIIMPORT 1 kali</td>
				<td>
				<b>(v)</b>
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportNilaiTransfer.php" target="blank">IMPORT Nilai Transfer</a></td>
				<td><b>[MASTER]<b> DIIMPORT 1 kali</td>
				<td>
				<b>(v)</b>
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportMataKuliah" target="blank">IMPORT MATA KULIAH</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AWAL SEMESTER</td>
				<td>
				<b>(v)</b>
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportKelasKuliah" target="blank">IMPORT KELAS KULIAH</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AWAL SEMESTER</td>
				<td>
				<b>(v)</b>
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportAjarDosen" target="blank">IMPORT AJAR DOSEN</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AWAL SEMESTER</td>
				<td>
				1.<br/>
				2.
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportNilai" target="blank">IMPORT KRS</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AWAL SEMESTER</td>
				<td>
				1.<br/>
				2.
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportNilai" target="blank">IMPORT Nilai</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AKHIR SEMESTER</td>
				<td>
				1.<br/>
				2.
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportAKM" target="blank">IMPORT AKM</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AKHIR SEMESTER</td>
				<td>
				menunggu tes import lain
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportDayaTampung" target="blank">IMPORT Daya Tampung</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AKHIR SEMESTER</td>
				<td>
				<b>jml minggu belum</b>
				</td>
            </tr>
			<tr>
                <td><a href="IMPORT/ImportTransaksional.php?act=ImportPerbaikanKRS" target="blank">IMPORT Perbaikan SKS KRS > 25</a></td>
				<td><b>[TRANSAKSIONAL]<b> DIIMPORT TIAP AKHIR SEMESTER</td>
				<td>
				<b>untuk perbaikan sks > 25 sekaligus memfixkan akm</b><br/>
				<b>1. perbaiki di menu kelas kuliah dulu kalau bisa. Ubah sks menjadi dikit</b><br/>
				<b>2. Kemudian hook dengan import ini</b><br/>
				</td>
            </tr>
            <tr>
                <td><a href="FUNCTION/GetDictionary.php" target="blank">Get Dictionary</a></td>
				<td>Mendapatkan struktur data dari suatu tabel</td>
				<td>
					<b>(v)</b>
				</td>
            </tr>
            <tr>
                <td><a href="FUNCTION/GetRecordFunction.php" target="blank">Get Record Function</a></td>
				<td>Mendapatkan 1 record dari sebuah tabel</td>
				<td>
					<b>(v)</b>
				</td>
            </tr>
          
        </table>
        
		<br/><br/>
		</body>
</html>
