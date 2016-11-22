<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	
	if (WAR_INFO_QUERY and isset($_SESSION['cid'])) {
		$battle_server_name = array('primera', 'secura', 'suna');
		if (preg_match("/^\d{1,5}$/", $_POST["battleid"]) and preg_match("/^[1-3]{1}$/", $_POST["battle_server"])) {
			$battle_id = $_POST['battleid'];
			$battle_server = $_POST["battle_server"];
			if (!file_exists($prefix."tools/battle/battle_data/battle_info/".$battle_server_name[$battle_server-1]."/".$battle_id)) {
				$tmp = curl_get("http://www.cscpro.org/".$battle_server_name[$battle_server-1]."/battle/".$battle_id.".json");
				if (stripos($tmp, "error") !== false) {
					$error_message = '戰役編號錯誤';
				} else {
					if (!($file = fopen($prefix."tools/battle/battle_data/battle_info/".$battle_server_name[$battle_server-1]."/".$battle_id,"w")) or !chmod($prefix."tools/battle/battle_data/battle_info/".$battle_server_name[$battle_server-1]."/".$battle_id, 0777)) {
						$error_message = '讀取資料時發生錯誤，請稍候再嘗試1';
					} else {
						$result = json_decode($tmp);
						$damage_minus = $result->{"defender"}->{"damage"} - $result->{"attacker"}->{"damage"};
						fwrite($file,$time."\n".(new_line_replace($tmp)));
						$last_update_time = $time;
					}
				}
			} else {
				if (!($file = fopen($prefix."tools/battle/battle_data/battle_info/".$battle_server_name[$battle_server-1]."/".$battle_id,"r+"))) {
					$error_message = '讀取資料時發生錯誤，請稍候再嘗試2';
				} else {
					if ($last_update_time = fgets($file) and strlen($last_update_time) < 12) {
						if ($time - $last_update_time < 10) {
							$result = json_decode(fgets($file));
						} else {
							$tmp = curl_get("http://www.cscpro.org/".$battle_server_name[$battle_server-1]."/battle/".$battle_id.".json");
							$result = json_decode($tmp);
							$tmp = new_line_replace($tmp);
							$last_update_time = $time;
							fclose($file);
							if (!($file = fopen($prefix."tools/battle/battle_data/battle_info/".$battle_server_name[$battle_server-1]."/".$battle_id,"w+"))) {
								$error_message = '讀取資料時發生錯誤，請稍候再嘗試3';
							} else {
								fwrite($file,$last_update_time."\n".$tmp);
							}
						}
						$damage_minus = $result->{"defender"}->{"damage"} - $result->{"attacker"}->{"damage"};
					} else {
						$error_message = '讀取資料時發生錯誤，請稍候再嘗試4';
					}
				}
			}
			fclose($file);
			if (isset($error_message)) {
				$data = array(
					'error' => $error_message
				);
			} else {
				$h = $m = $s = 0;
				if (($active = strcasecmp($result->{"status"}, "Active")) == 0) {
					$h = $result->{"time"}->{"hour"};
					$m = $result->{"time"}->{"minute"};
					$s = $result->{"time"}->{"second"};
				}
				$started = (isset($result->{"started_by"})) ? str_replace('<span class=\"premiumStar\">&#9733;<\/span> ', '', $result->{"started_by"}->{"name"}) : null;
				$startedid = (isset($result->{"started_by"})) ? $result->{"started_by"}->{"name"} : null;
				$data = array(
					'battleid' => $battle_id,
					'type' => $result->{'type'},
					'started' => $started,
					'startedid' => $startedid,
					'region' => $result->{'region'}->{'name'},
					'round' => $result->{'round'},
					'status' => $result->{'status'},
					'time' => array(
						'h' => $h,
						'm' => $m,
						's' => $s
					),
					'minus' => $damage_minus,
					'defender' => array(
						'name' => $result->{"defender"}->{"name"},
						'damage' => $result->{"defender"}->{"damage"},
						'bar' => $result->{"defender"}->{"bar"},
						'roundwin' => $result->{"defender"}->{"roundwin"}
					),
					'attacker' => array(
						'name' => $result->{"attacker"}->{"name"},
						'damage' => $result->{"attacker"}->{"damage"},
						'bar' => $result->{"attacker"}->{"bar"},
						'roundwin' => $result->{"attacker"}->{"roundwin"}
					),
					'update' => date("H:i:s", $last_update_time)
				);
			}
		} else if (isset($_POST["battlescoreid"]) and preg_match("/^[1-3]{1}$/", $_POST["score_server"]) and $_SESSION['web_group'] >= 10) {
			$battle_score_id = $_POST["battlescoreid"];
			$score_server = $_POST["score_server"];
			$user_list = array(
				array(61,1),
				array(2564,21),
				array(3970,22),
				array(92300,6),
				array(19975,19)
			);
			$rand_num = rand(0,4);
			$tmp = curl_get("http://".$battle_server_name[$score_server-1].".e-sim.org/battleScore.html?id=".$battle_score_id."&at=".$user_list[$rand_num][0]."&ci=".$user_list[$rand_num][1]."&premium=1");
			if (stripos($tmp, "error") !== false) {
				$data = array(
					'error' => '編號錯誤'
				);
			} else {
				$result = json_decode($tmp);
				$data = array(
					'def_online' => $result->{"defendersOnline"},
					'att_online' => $result->{"attackersOnline"},
					'spe_online' => $result->{"spectatorsOnline"},
					'def_countries' => $result->{"defendersByCountries"},
					'att_countries' => $result->{"attackersByCountries"},
					'spe_countries' => $result->{"spectatorsByCountries"},
					'time' => $result->{"remainingTimeInSeconds"}
				);
				$data['spe_countries'] = replace_patterns($data['spe_countries']);
				$data['att_countries'] = replace_patterns($data['att_countries']);
				$data['def_countries'] = replace_patterns($data['def_countries']);
			}
		} else {
			$data = array(
				'error' => 'wrong argument'
			);
		}
		echo json_encode($data);
	}
	
	function new_line_replace ($data) {
		$new_line_search = array("\r", "\n");
		$new_line_replace = array('', '');
		return str_replace($new_line_search, $new_line_replace, $data);
	}
	
	function replace_patterns ($data) {
		$result = array();
		if (stripos($data, 'No one') === false) {
			$search = array("\r", "\n", ' ');
			$replace = array('', '', '');
			while (($pos_start = stripos($data, '<div')) !== false) {
				$pos_end = stripos($data, '</div>', $pos_start) + 5;
				$tmp = substr($data, 0, $pos_start);
				$tmp .= substr($data, $pos_end+1);
				$data = $tmp;
			}
			unset($tmp);
			$data = str_replace($search, $replace, $data);
			$tmp = explode('<br/>', $data);
			foreach ($tmp as $temp) {
				if (strlen($temp) > 0 and $temp != 'Noone') {
					$str = explode('-', $temp);
					array_push($result, array('id'=>$str[0],'country'=>$str[1]));
				}
			}
		}
		return $result;
	}
?>