<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	
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
		
		$begin++;
		$end = $begin + 1000;
		
		if ($argc == 2 and $argv[1] == "level5") {
			$data = "INSERT INTO `game_citizen_name` (`id`, `citizen_name`) VALUES";
			$error = false;
			
			for ($i=$begin;$i<=$end;$i++) {
				if ($i % 2 == 0) {
					sleep(3);
				}
				
				$result = curl_get("http://secura.e-sim.org/apiCitizenById.html?id=".$i);
				$result = json_decode($result);
				
				if (isset($result->{"error"})) {
					//$data .= ' (NULL, \'Unknown Citizen\'),';
					$error = true;
				} else if (!isset($result->{"login"})) {
					//$data .= ' (NULL, \'Error Citizen\'),';
					$error = true;
				} else {
					$data .= ' (NULL, \''.$result->{"login"}.'\'),';
				}
				
				if ($i % 100 == 0 or $i == $end or $error) {
					if (strlen($data) > 70) {
						$data[strlen($data)-1] = ";";
						if (!($stmt = $mysqli->prepare($data))) {
							handle_database_error($mysqli->error);
							exit();
						} else {
							$stmt->execute();
							$stmt->close();
							$data = "INSERT INTO `game_citizen_name` (`id`, `citizen_name`) VALUES";
						}
					}
					if ($error) {
						break;
					}
				}
			}
		}
	}
?>