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

<form action="GetRecordFunction.php" method="get">
  <table>
	<tr>
		<td>
		</td>
		<td>
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
		</td>
	</tr>
	<tr>
		<td>Filter</td>
		<td><input type="text" name="filter" size="70" value="<?= $_REQUEST['filter'] ?>"></td>
	</tr>
	<tr>
		<td>Order</td>
		<td><input type="text" name="order" size="30" value="<?= $_REQUEST['order'] ?>"></td>
	</tr>
	<tr>
		<td>Limit</td>
		<td><input type="text" name="limit" value="<?= $_REQUEST['limit'] ?>"></td>
	</tr>
	<tr>
		<td>Offset</td>
		<td><input type="text" name="offset" value="<?= $_REQUEST['offset'] ?>"></td>
	</tr>
</table>  
  <input type="submit">
 
</form>

<hr>

<h2><?= $_REQUEST['nama_function'] ?></h2>

			<?php
				
				
					# MENDAPATKAN RECORD BERDASAR FUNGSI
					$fungsi = $_GET['nama_function'];
					$data = array('act'=>$fungsi, 
					  'token'=>$token, 
					  'filter'=>$_REQUEST['filter'],
					  'order'=>$_REQUEST['order'],
					  'limit'=>$_REQUEST['limit'],
					  'offset'=>$_REQUEST['offset'],
					  );
					
					$result_string = runWS($data, $ctype);
					$result = json_decode($result_string, true);

					echo intoTables($result['data']);
					
					
				?>		
<script>

$(document).ready(function(){
	$("[value='<?php echo $_REQUEST['nama_function'] ?>']").attr("selected",true);
	
	});
</script>
				
</body>
</html>	