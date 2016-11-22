<?php
	function curl_get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
?>