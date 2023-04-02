<?php
include('../init.php');

//TINGGAL CARI BAGAIMANA PASSING URI
$nama_file = basename(__FILE__,'.php'); 

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



<h2><?php echo $nama_file; ?></h2>
<hr>

<form action="GetDictionary.php" method="get">
  <select name="nama_function">
		<?php
			$myfile = fopen("../nama_function.txt", "r") or die("Unable to open file!");
			$i=1;
			while(!feof($myfile)) {
				$nama_function=fgets($myfile);
				echo '<option value="'.trim($nama_function).'">'.$i.' - '.$nama_function.'</option>';
				$i++;
			}
			fclose($myfile);
		?>
  </select>
  <br><br>
 
  <input type="submit">
</form>

<hr>

 <h2><?= $_REQUEST['nama_function'] ?></h2>

			<?php
				
				
					# MENDAPATKAN PROFIL PERGURUAN TINGGI
					$fungsi = $_GET['nama_function'];
					$data = array('act'=>'GetDictionary', 
					  'token'=>$token, 
					  'fungsi'=>$fungsi
					  );
					  				  
					$result_string = runWS($data, $ctype);
					
					$result_array = (array) json_decode($result_string);
					$jsonData = json_encode($result_array, JSON_PRETTY_PRINT);

					echo "<pre>" . $jsonData . "</pre>";

					
				?>
				

<script>

$(document).ready(function(){
	$("[value='<?php echo $_REQUEST['nama_function'] ?>']").attr("selected",true);
	});
</script>
				
</body>
</html>	