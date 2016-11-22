<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	
	if (isset($_POST["hash"])) {
		$sql = "SELECT `id` FROM `".$dbconfig["battle_chat"]."` WHERE `hash` = ?";
		if (!($stmt = $mysqli->prepare($sql))) {
			handle_database_error($mysqli->error);
			exit();
		} else {
			$stmt->bind_param('s', $_POST["hash"]);
			$stmt->execute();
			$stmt->store_result();
			if (($stmt->num_rows) == 0) {
				$stmt->close();
			} else {
				$stmt->bind_result($chat_id);
				$stmt->fetch();
				$stmt->close();
				
				$sql = "SELECT `hash`, `citizen_name`, `chat_content`, `time` FROM `".$dbconfig["battle_chat"]."` WHERE `id` > ? ORDER BY `id` ASC";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				} else {
					$stmt->bind_param('i', $chat_id);
					$stmt->execute();
					$stmt->bind_result($chat_result[], $chat_result[], $chat_result[], $chat_result[]);
					$i=0;
					while ($stmt->fetch()) {
						$data[$i++] = array("hash" => $chat_result[0], "citizen_name" => $chat_result[1], "chat_content" => $chat_result[2], "time" => $chat_result[3]);
					}
					$stmt->close();
					if ($i == 0) {
						$data[] = array("error" => "no_new");
						echo json_encode($data, JSON_UNESCAPED_UNICODE);
					} else {
						echo json_encode($data, JSON_UNESCAPED_UNICODE);
					}
				}
			}
		}
	} else {
		$data[] = array("error" => "no_new");
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
?>