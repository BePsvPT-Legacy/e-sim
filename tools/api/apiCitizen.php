<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_only.php";
	
	if ($_GET["newest"] == 1) {
		$sql = "SELECT `id` FROM `".$dbconfig["citizen_name"]."` ORDER BY `id` DESC LIMIT 1";
		if (!($stmt = $mysqli->prepare($sql))) {
			handle_database_error($mysqli->error);
			exit();
		} else {
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($begin);
			$stmt->fetch();
			$stmt->close();
			
			$data = array("newest" => $begin);
		}
	} else if (isset($_GET["citizen"])) {
		if (preg_match("/^\d{1,6}$/", $_GET["citizen"]) and $_GET["citizen"] > 0) {
			$sql = "SELECT `citizen_name` FROM `".$dbconfig["citizen_name"]."` WHERE `id` = ?";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->bind_param('i', $_GET["citizen"]);
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$data = array("error" => "citizen not found");
				} else {
					$stmt->bind_result($citizen_name);
					$stmt->fetch();
					$stmt->close();
					$data = array("id" => $_GET["citizen"], "name" => $citizen_name);
				}
			}
		} else {
			$sql = "SELECT `id` FROM `".$dbconfig["citizen_name"]."` WHERE `citizen_name` = ?";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->bind_param('s', $_GET["citizen"]);
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$data = array("error" => "citizen not found");
				} else {
					$stmt->bind_result($citizen_id);
					$stmt->fetch();
					$stmt->close();
					$data = array("id" => $citizen_id, "name" => $_GET["citizen"]);
				}
			}
		}
	} else {
		$data = array("error" => "Wrong Parameter");
	}
	
	$result = json_encode($data);
	echo $result;
?>