<?php
$payload = file_get_contents('php://input');
if($payload)
{
	$jsonInput = json_decode($payload,true);
	if($jsonInput['events'][0]['name'] == "channel_vacated"&&is_numeric($jsonInput['events'][0]['channel']))
	{
		cURL("http://wanone-soft.com/api/Leaveapp/".$jsonInput['events'][0]['channel']."/AcrQW41!-*");
	}
		if($jsonInput['events'][1]['name'] == "channel_vacated"&&is_numeric($jsonInput['events'][1]['channel']))
	{
		cURL("http://wanone-soft.com/api/Leaveapp/".$jsonInput['events'][1]['channel']."/AcrQW41!-*");
	}	if($jsonInput['events'][2]['name'] == "channel_vacated"&&is_numeric($jsonInput['events'][2]['channel']))
	{
		cURL("http://wanone-soft.com/api/Leaveapp/".$jsonInput['events'][2]['channel']."/AcrQW41!-*");
	}
	
}

function cURL($url, $timeout = 10)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_exec($ch);
    curl_close($ch);
}
?>