<?php
	//ini_set("session.cookie_secure", 1);
	ini_set("session.cookie_domain", "crux.coder.tw/freedom/");
	ini_set("session.cookie_httponly", true);
	session_start();
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_config.php";
	require_once $prefix."config/web_config.php";
	require_once $prefix."config/web_view.php";
	
	header("X-XSS-Protection: 1; mode=block");
	header("X-Frame-Options: crux.coder.tw");
	header("Cache-Control: no-cache, must-revalidate");
	date_default_timezone_set("Asia/Taipei");
	
	$mysqli = new mysqli(DATABASE_HOST , DATABASE_USERNAME , DATABASE_PASSWORD, DATABASE_NAME);
	if (mysqli_connect_errno()) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} /*else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}*/ else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		$mysqli->set_charset("utf8");
		$time = time();
		require_once $prefix."config/user_login_check.php";
		
		if (ADMIN_ONLY and !($_SESSION['web_admin'] > 5)) {
			handle_error('The Website is in maintenance mode.');
			exit();
		}
	}
?>