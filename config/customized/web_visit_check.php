<?php
	$sql = "SELECT `id` FROM `ip_banned_list` WHERE `ip` = ?";
	if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		$stmt->bind_param('s', $ip);
		$stmt->execute();
		$stmt->store_result();
		if (($stmt->num_rows) != 0) {
			$stmt->close();
			handle_error($web_url, '禁止訪問：IP位址位於封鎖名單中');
			exit();
		}
	}
	
	if (IP_FILTER == 1) {
		handle_error($web_url, '很抱歉，網站目前維護中');
		exit();
	} else if (IP_FILTER == 0) {
		handle_error($web_url, '很抱歉，網站目前暫時無法訪問');
		exit();
	}
?>