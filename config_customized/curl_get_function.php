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
	
	function user_get_info($name,$link) {
		$result = json_decode(curl_get("http://secura.e-sim.org/apiCitizenById.html?id=".$link));
		if (isset($result->{"error"})) {
			return array("error","資料錯誤");
		} else if ($result->{"organization"} != false) {
			return array("error","不允許註冊的公民");
		} else if (strcmp($result->{"citizenship"},"Taiwan") != 0 and strcmp($result->{"citizenship"},"China") != 0) {
			return array("error","不允許註冊的公民");
		} else {
			$temp[0] = "success";
			$temp[1] = $result->{"citizenship"};
			
			$result = json_decode(curl_get("http://www.cscpro.org/secura/citizen/".$link.".json"));
			if (strcmp($result->{"name"},$name) != 0) {
				return array("error","身份驗證失敗");
			} else if ($result->{"alive"} != true) {
				return array("error","不允許註冊的公民");
			} else if ($result->{"ban"} != false) {
				return array("error","不允許註冊的公民");
			} else {
				$temp[2] = $result->{"age"};
				$temp[3] = $result->{"level"};
				$temp[4] = $result->{"experience"};
				$temp[5] = $result->{"strength"};
				$temp[6] = $result->{"rank"}->{"name"};
				$temp[7] = $result->{"rank"}->{"damage"};
				$temp[8] = $result->{"economy_skill"};
				$temp[9] = $result->{"alive"};
				$temp[10] = $result->{"ban"};
				return $temp;
			}
		}
	}
	
	function user_info_update($name,$link) {
		$result = json_decode(curl_get("http://secura.e-sim.org/apiCitizenById.html?id=".$link));
		if (isset($result->{"error"})) {
			return array("error","資料錯誤");
		} else {
			$temp[0] = "success";
			$temp[1] = $result->{"citizenship"};
			$temp[9] = $result->{"organization"};
			
			$result = json_decode(curl_get("http://www.cscpro.org/secura/citizen/".$link.".json"));
			if (strcmp($result->{"name"},$name) != 0) {
				return array("error","身份驗證失敗");
			} else {
				$temp[2] = $result->{"age"};
				$temp[3] = $result->{"level"};
				$temp[4] = $result->{"experience"};
				$temp[5] = $result->{"strength"};
				$temp[6] = $result->{"rank"}->{"name"};
				$temp[7] = $result->{"rank"}->{"damage"};
				$temp[8] = $result->{"economy_skill"};
				$temp[10] = $result->{"alive"};
				$temp[11] = $result->{"ban"};
				return $temp;
			}
		}
	}
?>