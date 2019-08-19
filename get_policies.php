<?php

$accesstoken = $_GET['accesstoken'];
$bigip_ip = $_GET['bigip_ip'];

$ch = curl_init();

$url = 'https://'.$bigip_ip .'/mgmt/tm/asm/policies?$select=name,id,type';
$header = array();
$header[] = 'Content-type: application/json';
$header[] = 'Authorization: Basic '.$accesstoken;

curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

$info = curl_getinfo($ch);

if($errno = curl_errno($ch))
    $error_message = curl_strerror($errno);
else
    $error_message = "-";

if (!curl_errno($ch)) {
	$data = json_decode($response, true);
	$policies_array = [];
	if (sizeof($data['items'])>0)
	{
		foreach($data['items'] as $key)
		{
			if($key['type']=="parent")
				$policies_array[] = ['name' => $key['name'], 'id' => $key['id'], 'type' => $key['type']];
		}
			$final_array = ['error_message' => $error_message,'http_code' => $info['http_code'],'num_of_policies' => sizeof($data['items']), 'items' => $policies_array];
	}
	else
	{
			$final_array = ['error_message' => $error_message,'http_code' => $info['http_code'],'num_of_policies' => 0, 'items' => array()];
	}
}
else
{
			$final_array = ['error_message' => $error_message,'http_code' => $info['http_code'],'num_of_policies' => 0, 'items' => array()];
}

header('Content-Type: application/json');
echo json_encode($final_array);
curl_close($ch);



?>