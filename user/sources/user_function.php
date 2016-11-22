<?php
	function user_register_idlink_check($link, $name) {
		$name = "login:" . $name;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, "http://secura.e-sim.org/apiCitizenById.html?id=".$link."");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		$output = curl_exec($ch);
		curl_close($ch);
		$output = preg_replace("/({|}|\")/", "", $output);
		$results = preg_split("/,/", $output);
		if (strcmp($results[8], $name) == 0) {
			return preg_replace("/citizenship:/", "", $results[5]);
		} else {
			return false;
		}
	}
	
	function count_id_to_name($country_id) {
		switch ($country_id) {
			case "Poland": return 1;
			case "Russia": return 2;
			case "Germany": return 3;
			case "France": return 4;
			case "Spain": return 5;
			case "United Kingdom": return 6;
			case "Italy": return 7;
			case "Hungary": return 8;
			case "Romania": return 9;
			case "Bulgaria": return 10;
			case "Serbia": return 11;
			case "Croatia": return 12;
			case "Bosnia and Herzegovina": return 13;
			case "Greece": return 14;
			case "Republic of Macedonia": return 15;
			case "Ukraine": return 16;
			case "Sweden": return 17;
			case "Portugal": return 18;
			case "Lithuania": return 19;
			case "Latvia": return 20;
			case "Slovenia": return 21;
			case "Turkey": return 22;
			case "Brazil": return 23;
			case "Argentina": return 24;
			case "Mexico": return 25;
			case "USA": return 26;
			case "Canada": return 27;
			case "China": return 28;
			case "Indonesia": return 29;
			case "Iran": return 30;
			case "South Korea": return 31;
			case "Taiwan": return 32;
			case "Israel": return 33;
			case "India": return 34;
			case "Australia": return 35;
			case "Netherlands": return 36;
			case "Finland": return 37;
			case "Ireland": return 38;
			case "Switzerland": return 39;
			case "Belgium": return 40;
			case "Pakistan": return 41;
			case "Malaysia": return 42;
			case "Norway": return 43;
			case "Peru": return 44;
			case "Chile": return 45;
			case "Colombia": return 46;
			case "Montenegro": return 47;
			case "Austria": return 48;
			case "Slovakia": return 49;
			case "Denmark": return 50;
			case "Albania": return 55;
			case "Egypt": return 57;
			default : return 0;
		}
	}
	
	// register.php
	function user_register($username, $pw, $pw_check, $nickname, $idlink, $languages) {
		if (!preg_match("/^[a-zA-Z0-9]{6,20}$/", $username)) {
			return 'Invalid username.';
		} else if (!preg_match("/^[a-z0-9]{128}$/", $pw) or !preg_match("/^[a-z0-9]{128}$/", $pw_check)) {
			return 'Invalid password.';
		} else if ($pw != $pw_check) {
			return 'The passwords do not match.';
		} else if (!preg_match("/^[\w ]{2,20}$/", $nickname)) {
			return 'Invalid Citizen ID.';
		} else if (!preg_match("/^[\d]{1,7}$/", $idlink)) {
			return 'Invalid Citizen link.';
		} else if (!preg_match("/^[0-2]{1}$/", $languages)) {
			return 'Invalid language.';
		} else if (!($countryid = user_register_idlink_check($idlink, $nickname))) {
			return 'The Citizen ID and the Citizen Link do not match.';
		} else {
			$sql = "SELECT `id` FROM `accounts` WHERE `username` = ?  OR `idlink` = ? OR `nickname` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('sss', $username, $idlink, $nickname);
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) != 0) {
					$stmt->close();
					return 'The username or the citizen ID or the citizen link has been used.';
				} else {
					$stmt->close();
					$sql = "INSERT INTO `accounts` (`username`, `password`, `nickname`, `idlink`, countryid, `language`, `web_admin`, `login_deny`, `register_ip`, `register_time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
						$stmt->close();
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						$language_name = array("eng", "cht", "chs");
						$stmt->bind_param('ssssssssss', $username, hash('sha512', $pw), $nickname, $idlink, count_id_to_name($countryid), $language_name[$languages], $web_admin_value, $login_deny_value, $GLOBALS['ip'], $GLOBALS['current_time_unix']);
						$web_admin_value = 0;
						$login_deny_value = 1;
						$stmt->execute();
						$stmt->close();
						return 'Success! Please remember to send <a href="http://secura.e-sim.org/profile.html?id=305703">believedecision</a> a message to verify account.';
					}
				}
			}
		}
	}
	
	// login.php
	function user_login($username, $password) {
		if ($username == NULL or $password == NULL) {
			return 'Invalid username or password.';
		} else {
			$sql = "SELECT `id`, `nickname`, `idlink`, `countryid`, `language`,  `web_admin`, `login_deny` FROM `accounts` WHERE `username` = ? AND `password` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('ss', $username, hash('sha512', $password));
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					return 'Invalid username or password.';
				} else {
					$stmt->bind_result($result[], $result[], $result[], $result[], $result[], $result[], $result[]);
					$result[] = $stmt->fetch();
					$stmt->close();
					if ($result[6] != 0) {
						return 'The username was banned or does not verify by admin.';
					} else if ($result[3] != 1 and $result[3] != 6 and $result[3] != 23 and $result[3] != 24 and $result[3] != 26 and $result[3] != 28 and $result[3] != 29 and $result[3] != 30 and $result[3] != 32 and $result[3] != 36 and $result[3] != 57) {
						return 'Permission deny. You are not Trinity Citizen.';
					} else {
						$_SESSION['cid'] = $result[0];
						$_SESSION['nickname'] = $result[1];
						$_SESSION['idlink'] = $result[2];
						$_SESSION['country_id'] = $result[3];
						$_SESSION['language'] = $result[4];
						$_SESSION['web_admin'] = $result[5];
						$sql = "UPDATE `accounts` SET `last_login_ip` = ?, `last_login_time_unix` = ? WHERE `username` = ?";
						if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
							$stmt->close();
							handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
							exit();
						} else {
							$stmt->bind_param('sss', $GLOBALS['ip'], $GLOBALS['current_time_unix'], $username);
							$stmt->execute();
							$stmt->close();
							$sql = "INSERT INTO `web_login_log` (`cid`, `ip`, `time_unix`) VALUES (?, ?, ?)";
							if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
								$stmt->close();
								handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
								exit();
							} else {
								$stmt->bind_param('sss', $_SESSION['cid'], $GLOBALS['ip'], $GLOBALS['current_time_unix']);
								$stmt->execute();
								$stmt->close();
								header("Location: " . $GLOBALS['prefix'] . "index.php");
								exit();
							}
						}
					}
				}
			}
		}
	}
	
	// updateinfo.php
	function user_updateinfo_pw($old_pw, $new_pw, $new_pw_check) {
		if (!preg_match("/^[a-z0-9]{128}$/", $old_pw) or !preg_match("/^[a-z0-9]{128}$/", $new_pw) or !preg_match("/^[a-z0-9]{128}$/", $new_pw_check)) {
			return 'Invalid password.';
		} else if ($new_pw != $new_pw_check) {
			return 'The passwords do not match.';
		} else {
			$sql = "SELECT `id` FROM `accounts` WHERE `id` = ? AND `password` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$stmt->bind_param('ss', $_SESSION['cid'], hash('sha512', $old_pw));
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					return 'The old password is wrong.';
				} else {
					$stmt->close();
					$sql = "UPDATE `accounts` SET `password` = ? WHERE `id` = ?";
					if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
						$stmt->close();
						handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
						exit();
					} else {
						$stmt->bind_param('ss', hash('sha512', $new_pw), $_SESSION['cid']);
						$stmt->execute();
						$stmt->close();
						return 'Success!';
					}
				}
			}
		}
	}
	
	function user_updateinfo_language($languages) {
		if (!preg_match("/^[0-2]{1}$/", $languages)) {
			return 'Invalid language.';
		} else {
			$sql = "UPDATE `accounts` SET `language` = ? WHERE `id` = ?";
			if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
				$stmt->close();
				handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
				exit();
			} else {
				$language_name = array("eng", "cht", "chs");
				$stmt->bind_param('ss', $language_name[$languages], $_SESSION['cid']);
				$stmt->execute();
				$stmt->close();
				$_SESSION['language'] = $language_name[$languages];
				return 'Success!';
			}
		}
	}
	
	function user_updateinfo_query() {
		$sql = "SELECT `username` FROM `accounts` WHERE `id` = ?";
		if (!($stmt = $GLOBALS['mysqli_object_connecting']->prepare($sql))) {
			$stmt->close();
			handle_database_error($GLOBALS['web_url'], $GLOBALS['mysqli_object_connecting']->error);
			exit();
		} else {
			$stmt->bind_param('s', $_SESSION['cid']);
			$stmt->execute();
			$stmt->bind_result($result[]);
			$result[] = $stmt->fetch();
			$stmt->close();
			return array (
				'username' => $result[0]
			);
		}
	}
?>