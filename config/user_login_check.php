<?php
	if (isset($_COOKIE["login_username"]) and isset($_COOKIE["login_hash"]) and !isset($_SESSION['cid'])) {
		$sql = "SELECT `cid` FROM ".$dbconfig["web_login_remember"]." WHERE `username` = ? AND `hash` = ? AND `time_to` > ? ORDER BY `id` DESC LIMIT 1";
		if (!($stmt = $mysqli->prepare($sql))) {
			handle_database_error($mysqli->error);
			exit();
		} else {
			$stmt->bind_param('sss', $_COOKIE["login_username"], $_COOKIE["login_hash"], $time);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows == 0) {
				setcookie("login_username", "", $time-3600, "/");
				setcookie("login_hash", "", $time-3600, "/");
			} else {
				$stmt->bind_result($result_cid);
				$stmt->fetch();
				$stmt->close();
				$_SESSION['cid'] = $result_cid;
				
				$sql = "SELECT `web_group`, `web_admin` FROM ".$dbconfig["account"]." WHERE `id` = ?";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				} else {
					$stmt->bind_param('i', $_SESSION['cid']);
					$stmt->execute();
					$stmt->store_result();
					if (($stmt->num_rows) != 0) {
						$stmt->bind_result($result_account[], $result_account[]);
						$stmt->fetch();
						$stmt->close();
						$_SESSION['web_group'] = $result_account[0];
						$_SESSION['web_admin'] = $result_account[1];
					}
				}
			}
		}
	}
?>