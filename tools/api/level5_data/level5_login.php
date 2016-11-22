<?php
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "http://secura.e-sim.org/login.html");
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie");
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("login"=>"Level5", "password"=>"esimtwbotlevel5", "remember"=>"true", "facebookAdId"=>""))); 
	curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	$result = curl_exec($ch);
	curl_close($ch);
?>