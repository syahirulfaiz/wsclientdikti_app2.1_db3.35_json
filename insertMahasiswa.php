<? if($_GET[id]<>'') { 
			$mhs=mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * from mhs a inner join prodi b 
					on a.kode_prodi=b.kode_prodi
					where a.nim='$_GET[id]'"));

			# MENAMBAH DATA BIODATA MAHASISWA
			$record['nama_mahasiswa'] = $mhs[nama];
			$record['tempat_lahir'] = $mhs[tmp_lahir];
			$record['tanggal_lahir'] = $mhs[tgl_lahir];
			$record['jenis_kelamin'] = $mhs[jenis_kel];
			$record['id_agama'] = '1';
			$record['nik'] = $mhs[nik];
			$record['nisn'] = $mhs[nisn];
			$record['npwp'] = '';
			$record['jalan'] = $mhs[alamat_lengkap];
			$record['dusun'] = '';
			$record['rt'] = '';
			$record['rw'] = '';
			$record['kelurahan'] = '-';
			$record['id_wilayah'] = '000000';
			$record['kode_pos'] = '';
			$record['id_jenis_tinggal'] = '1';
			$record['telepon'] = $mhs[telp];
			$record['handphone'] = $mhs[hp];
			$record['id_negara'] = 'ID';
			$record['email'] = $mhs[email];
			$record['penerima_kps'] = '0';
			$record['nomor_kps'] = '0';
			$record['id_kebutuhan_khusus_ayah'] = '0';
			$record['nama_ibu'] = $mhs[nama_ibu];
			$record['id_kebutuhan_khusus_ibu'] = '0';
			$record['id_kebutuhan_khusus_mahasiswa'] = '0';

			$data = array('act'=>'InsertBiodataMahasiswa', 
			  'token'=>$token, 
			  'record'=>$record,
			  );
			$result_string = runWS($data, $ctype);
			$result = json_decode($result_string, true);
		?>
			<div class="callout callout-danger fade in">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h5>Upload Data <?= $_GET[id] ?></h5>
				<p>Nama : <?= $mhs[nama] ?></p>
				<?
					if($result['error_desc']<>'') {
						echo "<p><b>Error</b> : ".$result['error_desc']."</p>";

						if($result[error_code] == 200){
							$filter="nama_mahasiswa='$mhs[nama]' and tanggal_lahir='$mhs[tgl_lahir]' and nama_ibu='$mhs[nama_ibu]'";
							$data = array('act'=>'GetBiodataMahasiswa', 
							  'token'=>$token, 
							  'filter'=>$filter,
							  'order'=>'nama_mahasiswa',
							  'limit'=>'1',
							  'offset'=>'0',
							  );
							$result_string = runWS($data, $ctype);
							$result = json_decode($result_string, true);
							foreach ($result as $row) {
			                	if (is_array($row)){
			                		foreach($row as $k=>$v){
					                	$id_mahasiswa=$v[id_mahasiswa];
					                	/*$v[nama_mahasiswa]."</td>
					                			<td>".$v[nim]."</td>
					                			<td>".$v[jenis_kelamin]."</td>
					                			<td>".$v[nama_agama]."</td>
					                			<td>".$v[tanggal_lahir]."</td>
					                			<td>".$v[nama_program_studi]."</td>
					                			<td>".$v[nama_status_mahasiswa]."</td>
					                			<td>".substr($v[id_periode],0,4)."</td>
					                			<td>".$v[jenis_kel]."</td>
					                		  </tr>";*/
					                }
					            }
			                }
							# MENAMBAH DATA RIWAYAT MAHASISWA
							$record2['id_mahasiswa'] = $id_mahasiswa;
							$record2['nim'] = $mhs[nim];
							$record2['id_jenis_daftar'] = '1';
							$record2['id_jalur_daftar'] = '5';
							$record2['tanggal_daftar'] = $mhs[tgl_daftar]=="0000-00-00" ? "$mhs[ta]-07-01" : $mhs[tgl_daftar];
							$record2['id_perguruan_tinggi'] = $_SESSION[id_pt];
							$record2['id_prodi'] = $mhs[kodesp];
							$record2['id_periode_masuk'] = $mhs[ta]."1";
							$record2['sks_diakui'] = '0';
							$record2['id_perguruan_tinggi_asal'] = '';
							$record2['id_prodi_asal'] = '';
							print_r($record2);
							$data = array('act'=>'InsertRiwayatPendidikanMahasiswa', 
							  'token'=>$token, 
							  'record'=>$record2,
							  );
							$result_string = runWS($data, $ctype);
							$result = json_decode($result_string, true);
						}
					}
					
				?>