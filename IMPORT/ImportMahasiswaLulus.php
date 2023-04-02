<?php
include('../init.php');

//TINGGAL CARI BAGAIMANA PASSING URI
$nama_file = basename(__FILE__,'.php'); 
$nama_file_process = "Process".$nama_file.".php";
$nama_file_LOG = $nama_file_process.".html";

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

<h5>Lokasi web service: <a href="<?= $url ?>" target="_blank"><?= $url ?></a></h5>
<h2><?php echo $nama_file; ?></h2><br />

<br>format import hanya XLSX / XLS file
<p><input type="file" name="xlfile" id="xlf" /> <br></p>
<button type="button" name="import" id="ajaxButton" >SYNC TO FEEDER</button>
<a id="a_LOG" style=" -webkit-appearance: button;-moz-appearance: button;appearance: button;text-decoration: none;color: initial;" target="_blank">LOG</a>
<button type="button" onclick="download()">DOWNLOAD REPORT</a></button>
<div id="loader-icon" style="display:none;color:red;font-weight:bold" ><img src="<?php echo TEMPLATE_URL; ?>resource/light-red-flash.gif" style="width:15px;height:15px;"/><span id="percentage"></span>&nbspprogress...don't close browser!!</div>


<hr/>
ISI EXCEL :
<pre id="json_temp" type="hidden"></pre>
<pre id="out" style="height:50px"></pre>
<br />
<!-- uncomment the next line here and in xlsxworker.js for encoding support -->
<!--<script src="dist/cpexcel.js"></script>-->
<script src="<?php echo TEMPLATE_URL; ?>js-xlsx-master/shim.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>js-xlsx-master/jszip.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>js-xlsx-master/xlsx.js"></script>
<!-- uncomment the next line here and in xlsxworker.js for ODS support -->
<script src="<?php echo TEMPLATE_URL; ?>js-xlsx-master/dist/ods.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>js-xlsx-master/bacaXLS.js"></script>

<script src="<?php echo TEMPLATE_URL; ?>jquery/jquery.min.js"></script>



<script>
var wisuda_ke;
var jumlah_lulusan;
var location_of_LOG;

function download(){
    var a = document.body.appendChild(
        document.createElement("a")
    );
	a.download = wisuda_ke+"-"+jumlah_lulusan+"-<?php echo $nama_file_LOG; ?>";
    a.href = "data:text/html," + document.getElementById("out").innerHTML; // Grab the HTML
    a.click(); // Trigger a click on the element
}

function menata_array(){
	var json_temp = document.getElementById('json_temp').value;
	var objectJSON = JSON.parse(json_temp);
	var arrayJSON = [];
	arrayJSON = $.map(objectJSON, function(el) { return el });
	return arrayJSON;
}

var counter=0;
var getData= function(arrayJSON,counter){
		if (counter===arrayJSON.length) {
        $('#out').empty();
        counter = 0;
    }
	
	jumlah_lulusan = arrayJSON.length;
	wisuda_ke = arrayJSON[counter]['wisuda_ke'];

	location_of_LOG="<?php echo TEMPLATE_URL; ?>/LOG/LULUSAN/"+wisuda_ke+"-"+jumlah_lulusan+"-<?php echo $nama_file_LOG; ?>";
	$('#a_LOG').attr('href',location_of_LOG);
	
	$("#loader-icon").show();
	
	var percentage = (counter/arrayJSON.length);
	percentage = (percentage.toFixed(2))*100;
	$("#percentage").html(percentage+"%");
	
	$.ajax({
         url:"<?php echo $nama_file_process; ?>?nama_file_LOG=<?php echo $nama_file_LOG; ?>&row="+counter+"&jumlah_lulusan="+jumlah_lulusan, 
       type : "POST",
	   data : {key : JSON.stringify(arrayJSON[counter])},
	   traditional : true,
        success:function(data){
			$("#loader-icon").hide();
            $('#out').append('<li> eksekusi row : '+counter+' :\t' + data +'</li>');
			counter++;
            if (counter < arrayJSON.length) getData(arrayJSON,counter);
        },
		error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        console.log(msg);
		getData(arrayJSON,counter);
		}
    });
	}
	
$(document).ready(function(){
    $("#ajaxButton").click(function(){	
	
		document.getElementById('out').innerHTML="";
		var arrayJSON = menata_array();
		getData(arrayJSON,counter);
	
	});
});
</script>

</body>
</html>
