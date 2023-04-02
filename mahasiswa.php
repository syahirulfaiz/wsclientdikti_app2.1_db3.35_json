<h5>Daftar Mahasiswa</h5>
				<?php
				
				include 'init.php';
				
					# MENDAPATKAN PROFIL PERGURUAN TINGGI
					$filter = '';
					$order = 'nama_mahasiswa';
					$limit = 20;
					$offset = 0;
					$data = array('act'=>'GetBiodataMahasiswa', 
					  'token'=>$token, 
					  'filter'=>$filter,
					  'order'=>$order,
					  'limit'=>$limit,
					  'offset'=>$offset,
					  );
					$result_string = runWS($data, $ctype);
					$result = json_decode($result_string, true);

					echo intoTables($result['data']);
					
				?>