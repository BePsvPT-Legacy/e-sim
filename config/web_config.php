<?php
	function handle_database_error($error_message) {
		if (DEBUG_MODE) {
			$error_message = htmlspecialchars("Database Error : ".mysqli_connect_errno().mysqli_connect_error().$error_message, ENT_QUOTES);
		} else {
			$error_message = "Database Connecting Error.";
		}
		header("Location: ".WEB_ERROR_PAGE."?message=".$error_message);
		exit();
	}
	
	function handle_error($error_message) {
		header("Location: ".WEB_ERROR_PAGE."?message=".htmlspecialchars($error_message, ENT_QUOTES));
		exit();
	}
?>