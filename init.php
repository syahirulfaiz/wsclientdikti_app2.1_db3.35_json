<?php
//[1] array_to_xml : Output array ke xml
//[2] stringXML : Output ke xml
//[3] runWS : Fungsi execute WS feeder	: RETURN JSON dalam bentuk STRING (bukan JSON Murni)
//[4] intoTables : Output ke tables
//[5] MENDAPATKAN TOKEN


	session_start();
	//error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);

	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

	

	
	
//[1] array_to_xml : Output array ke xml
	function array_to_xml( $data, &$xml_data ) {
	    foreach( $data as $key => $value ) {
	        if( is_array($value) ) {
	            $subnode = $xml_data->addChild($key);
	            array_to_xml($value, $subnode);
	        } else {
	            //$xml_data->addChild("$key",htmlspecialchars("$value"));
	            $xml_data->addChild("$key",$value);
	        }
	     }
	}
	
//[2] stringXML : Output ke xml	
	function stringXML($data) {
		$xml = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
		array_to_xml($data, $xml);
		return $xml->asXML();
	}
	
	
//[3] runWS : Fungsi execute WS feeder	: RETURN JSON dalam bentuk STRING (bukan JSON Murni)

	$url = 'http://localhost:8082/ws/live2.php';

	function runWS($data, $type='json') {
	 	global $url;

	    $ch = curl_init();

	 	curl_setopt($ch, CURLOPT_POST, 1);
	 	$headers = array();
	 	if ($type == 'xml')
	  		$headers[] = 'Content-Type: application/xml';
	 	else
	  		$headers[] = 'Content-Type: application/json';

	  	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    	if ($data) {
     		if ($type == 'xml') {
			   /* contoh xml:
			   <?xml
					version="1.0"?><data><act>GetToken</act><username>agus</username><passwo
					rd>abcdef</password></data>
			   */
   			   $data = stringXML($data);
     		}
     		else {
			   /* contoh json:
			   {"act":"GetToken","username":"agus","password":"abcdef"}
			   */      
			    $data = json_encode($data);
			
			}

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	}
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$result = curl_exec($ch);
    	curl_close($ch);

    	return $result;
	}

	
//[4] intoTables : Output ke tables
	function intoTables($rows) {
		$i=0;
		$str = '<div class="datatable">
				   <table class="data_grid">';
		foreach ($rows as $row) {
		  if (!$i) {
		   	$str .= '<tr>';
		   	$str .= '<th>No</th>';
		   	foreach(array_keys($row) as $k=>$v){ 
		   		$str .= '<th>';
			    $str .= $v;
			    $str .= '</th>';
		    }
   			$str .= '</tr>';
  		}
  		$str .= '<tr>';
  		$i++;

	    $style='';
	    foreach($row as $k=>$v){
	    	if (strtolower($k) == 'soft_delete' && $v == '1') {
	    		$style='style="text-decoration:line-through"';
	   		}
	  	}

  		$str .= "<td $style >$i.</td>";
		  foreach($row as $k=>$v){
		   $str .= "<td $style>";
		   if (!is_array($v))
		    $str .= $v;
		   $str .= '&nbsp;</td>';
		  }
		  $str .= '</tr>';    
		 }
		 $str .= '</table></div>'; 

		return $str;
	}

//[5]getToken : MENDAPATKAN TOKEN

function getToken(){
	$username = '202006';
	$password = '11890';
	$data = array('act'=>'GetToken', 'username'=>$username, 'password'=>$password);
	$result_string = runWS($data, $ctype);

	if ($result_string) {
		if (strstr($result_string, '<?xml')) {
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


$token = getToken();

define("BASE_URL",$_SERVER['DOCUMENT_ROOT'].'/wsclientdikti_json');//this is for PHP file's include
define("TEMPLATE_URL","/wsclientdikti_json/");//this is for html
define("NAMA_APLIKASI","WS IAIN Surakarta JSON (APP 2.1 DB 3.35) ");



?>