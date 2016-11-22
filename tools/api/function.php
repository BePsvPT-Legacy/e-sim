<?php
	function curl_level5_get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "level5_data/cookie");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "level5_data/cookie");
		curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
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
	
	function get_data($data, $start, $end, $fix, $prefix) {
		if ($prefix != null) {
			$pos_start = stripos($data, $start, $prefix);
		} else {
			$pos_start = stripos($data, $start);
		}
		$pos_end = stripos($data, $end, $pos_start);
		if ($pos_start !== false and $pos_end !== false) {
			$pos_start += $fix;
			return substr($data, $pos_start, $pos_end - $pos_start);
		} else {
			return false;
		}
	}
?>