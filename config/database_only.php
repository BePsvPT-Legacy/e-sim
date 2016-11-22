<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_config.php";
	$mysqli = new mysqli(DATABASE_HOST , DATABASE_USERNAME , DATABASE_PASSWORD, DATABASE_NAME);
	$mysqli->set_charset("utf8");
?>