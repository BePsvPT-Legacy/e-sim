<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/database_connect.php";
	
	if (isset($_SESSION["cid"])) {
		$sql = "UPDATE `".$dbconfig["web_login_remember"]."` SET `time_to` = ? WHERE `cid` = ? AND `hash` = ?";
		if (!($stmt = $mysqli->prepare($sql))) {
			handle_database_error($mysqli->error);
			exit();
		}
		$stmt->bind_param('sis', $time, $_SESSION["cid"], $_COOKIE["login_hash"]);
		$stmt->execute();
		$stmt->close();
	}
	
	session_unset();
	setcookie("login_username", "", $time-3600, "/");
	setcookie("login_hash", "", $time-3600, "/");
	
	header("Location: ".$prefix."index.php");
	exit();
?>