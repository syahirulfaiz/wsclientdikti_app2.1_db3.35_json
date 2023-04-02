

<?php
				
				include 'init.php';
				
					$token=$_SESSION['token'];
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
					$result_array = (array) json_decode($result_string);
					//$result = $result_string; // hasilnya {"error_code":"0","error_desc":"","data":[{"nama_mahasiswa":".."
					//$result = json_encode($result_string, true); //hasilnya "{\"error_code\":\"0\",\"error_desc\":\"
					//$result = json_encode(json_encode($result_string)); //"\"{\\\"error_code\\\":\\\"0\\\",\\\"error_desc\\\":\\\"\\\",\\\"data\\\":[{\\\"n
					
					
					//$result = json_encode($result_string,JSON_PRETTY_PRINT);// hasilnya 'Array'					
					 
					//echo intoTables($result['data']);
				?>
				
<hr><pre id="out" ><code> ini : <?=$result_string;?></code></pre>		

<?php

$jsonData = json_encode($result_array, JSON_PRETTY_PRINT);

echo "<h1>Original</h1>";
echo $jsonData;

echo "<h1>&lt;pre&gt;</h1><br>";
echo "<pre>" . $jsonData . "</pre>";

echo "<h1>str_replace()</h1><br>";
echo str_replace("\n", "<br>", $jsonData);

?>
	
</body>
</html>				