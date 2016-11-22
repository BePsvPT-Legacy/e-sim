<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	require_once $prefix."config_customized/military_unit_data.php";
	require_once $prefix."config_customized/country_convert.php";
	
	if (!WAR_INFO_QUERY_CITIZEN) {
		$query_deny = "You do not have the permission to access the page.";
	} else {
		if (!isset($_GET["battleid"])) {
			$error_message = "Please waiting for loading the data after submitted.";
		} else {
			$battle_id = (preg_match("/^\d{1,5}$/",$_GET['battleid']) and $_GET['battleid'] > 0) ? $_GET['battleid'] : 9253;
			$battle_id_leading = floor($battle_id/1000);
			if (!file_exists($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id)) {
				if (!($file = fopen($prefix."tools/battle/battle_data/battle_list","r+"))) {
					$error_message = "There is something error when loading the data.";
				} else {
					if (!($last_update_time = fgets($file)) or strlen($last_update_time) != 11) {
						$error_message = "There is something error when loading the data.";
					} else {
						if ($time - $last_update_time < 600) {
							$result = fgets($file);
						} else {
							$result = curl_get("http://www.cscpro.org/secura/battles.json");
							$last_update_time = $time;
							fseek($file, 0, SEEK_SET);
							ftruncate($file, 0);
							fwrite($file,$time."\n".$result);
						}
						fclose($file);
						
						$result = json_decode($result);
						$result = $result->{"battles"};
						usort($result, "compare_battle_id");
						if (!($battle_id < ($result[0]->{"id"}-1)) and !isset($_GET["enforce_update"])) {
							$error_message = "This battle id could not be queried now.";
						} else {
							if (!file_exists($prefix."tools/battle/battle_data_all/".$battle_id_leading)) {
								if (!mkdir($prefix."tools/battle/battle_data_all/".$battle_id_leading, 0777) or !chmod($prefix."tools/battle/battle_data_all/".$battle_id_leading, 0777)) {
									$error_message = "There is something error when loading the data.";
								}
							}
							if (!isset($error_message)) {
								if (!($file = fopen($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id,"w")) or !chmod($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id, 0777)) {
									$error_message = "There is something error when loading the data.";
								} else {
									$order  = array("\r\n", "\n", "\r");
									$i=1;
									while (true) {
										$result = curl_get("http://secura.e-sim.org/apiFights.html?battleId=".$battle_id."&roundId=".$i++."");
										if (stripos($result, "error") !== false and strlen($result) < 64) {
											break;
										} else {
											$result=str_replace($order, "", $result);
											fwrite($file,$result."\r\n");
										}
									}
									unset($result);
									fclose($file);
								}
							}
						}
					}
				}
			}
			if (!isset($error_message)) {
				if (!($file = fopen($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id,"r"))) {
					$error_message = "There is something error when loading the data.";
				} else {
					class battle_damage_data {
						public $id;
						public $citizen;
						public $citizen_name;
						public $citizenship;
						public $military;
						public $defenderside;
						public $damage;
						public $damage_round;
						public $weapon;
						public $clutch_amount;
						public $battle_hero_amount;
					}
					$military_filter = (preg_match("/^[a-zA-Z0-9]{1,12}$/", $_GET["military_filter"])) ? true : false;
					$citizenship_filter = (preg_match("/^\d{1,2}$/", $_GET["citizenship_filter"]) and $_GET["citizenship_filter"] > 0) ? true : false;
					$military_damage = 0;
					$all=0;
					$defender = array("dmg_round" => 0, "dmg_all" => 0, "dmg_win" => 0, "dmg_high" => -1, "dmg_high_id" => -1);
					$attacker = array("dmg_round" => 0, "dmg_all" => 0, "dmg_win" => 0, "dmg_high" => -1, "dmg_high_id" => -1);
					$weapon_all = array(0, 0, 0, 0, 0, 0);
					
					while ($line = fgets($file) and strlen($line) > 5 and $result_data = json_decode($line)) {
						unset($line);
						$defender["dmg_round"] = $attacker["dmg_round"] = 0;
						$defender["dmg_high"] = $attacker["dmg_high"] = -1;
						$defender["dmg_high_id"] = $attacker["dmg_high_id"] = -1;
						
						foreach ($result_data as $result) {
							($result->{"defenderSide"}) ? $defender["dmg_round"]+=$result->{"damage"} : $attacker["dmg_round"]+=$result->{"damage"};
							if ($citizenship_filter and $result->{"citizenship"} != $_GET["citizenship_filter"]) {
								continue;
							}
							if ($military_filter) {
								if (isset($result->{"militaryUnit"})) {
									$military_unit_name = military_unit_id_to_name($result->{"militaryUnit"});
									if (stripos($military_unit_name, $_GET["military_filter"]) === false) {
										continue;
									}
									$military_damage += $result->{"damage"};
								} else {
									continue;
								}
							}
							for ($i=0;$i<$all;$i++) {
								if ($result->{"citizenId"} == $data[$i]->citizen and $result->{"defenderSide"} == $data[$i]->defenderside) {
									$data[$i]->damage += $result->{"damage"};
									$data[$i]->damage_round += $result->{"damage"};
									$data[$i]->weapon[$result->{"weapon"}] += ($result->{"berserk"}) ? 5 : 1;
									$weapon_all[$result->{"weapon"}] += ($result->{"berserk"}) ? 5 : 1;
									break;
								}
							}
							if ($i == $all) {
								$data[$all] = new battle_damage_data();
								$data[$all]->id = $all+1;
								$data[$all]->citizen = $result->{"citizenId"};
								$data[$all]->citizenship = $result->{"citizenship"};
								$data[$all]->military = (isset($result->{"militaryUnit"})) ? $result->{"militaryUnit"} : 0;
								$data[$all]->damage = $data[$all]->damage_round = $result->{"damage"};
								$data[$all]->weapon[$result->{"weapon"}] = ($result->{"berserk"}) ? 5 : 1;
								$weapon_all[$result->{"weapon"}] += ($result->{"berserk"}) ? 5 : 1;
								$data[$all]->defenderside = $result->{"defenderSide"};
								$data[$all]->clutch_amount = $data[$all]->battle_hero_amount = 0;
								$sql = "SELECT `citizen_name` FROM `".$dbconfig["citizen_name"]."` WHERE `id` = ?";
								if (!($stmt = $mysqli->prepare($sql))) {
									handle_database_error($mysqli->error);
									exit();
								} else {
									$stmt->bind_param('i', $result->{"citizenId"});
									$stmt->execute();
									$stmt->store_result();
									if (($stmt->num_rows) > 0) {
										$stmt->bind_result($citizen_name);
										$stmt->fetch();
										$stmt->close();
										$data[$all]->citizen_name = $citizen_name;
									}
								}
								$all++;
							}
						}
						
						$defender["dmg_all"] += $defender["dmg_round"];
						$attacker["dmg_all"] += $attacker["dmg_round"];
						($defender["dmg_round"] >= $attacker["dmg_round"]) ? $defender["dmg_win"]++ : $attacker["dmg_win"]++;
						$dmg_round_gap_abs = abs($dmg_round_gap = $defender["dmg_round"] - $attacker["dmg_round"]);
						for ($i=0;$i<$all;$i++) {
							if ($data[$i]->defenderside) {
								if ($dmg_round_gap >= 0 and $data[$i]->damage_round >= $dmg_round_gap) {
									$data[$i]->clutch_amount++;
								}
								if ($data[$i]->damage_round > $defender["dmg_high"]) {
									$defender["dmg_high"] = $data[$i]->damage_round;
									$defender["dmg_high_id"] = $i;
								}
							} else {
								if ($dmg_round_gap <= 0 and $data[$i]->damage_round >= $dmg_round_gap_abs) {
									$data[$i]->clutch_amount++;
								}
								if ($data[$i]->damage_round > $attacker["dmg_high"]) {
									$attacker["dmg_high"] = $data[$i]->damage_round;
									$attacker["dmg_high_id"] = $i;
								}
							}
							$data[$i]->damage_round = 0;
						}
						$data[$defender["dmg_high_id"]]->battle_hero_amount++;
						$data[$attacker["dmg_high_id"]]->battle_hero_amount++;
					}
					fclose($file);
				}
			}
		}
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "Entire Statistics", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($query_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$query_deny</h3>
				</div>\n
EOD;
	} else {
?>
				<div>
					<form name="battle_query" id="battle_query" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="battleid">Battle ID：</label>
								<input type="text" name="battleid" id="battleid" value="<?php echo $battle_id; ?>" maxlength="5" pattern="^[\d]{1,5}$" autocomplete="off" autofocus required>
							</div>
							<div class="pure-control-group">
								<label for="military_filter">Military Filter：</label>
								<input type="text" name="military_filter" id="military_filter" value="" maxlength="12" pattern="^[a-zA-Z0-9]{0,12}$" autocomplete="off">
							</div>
							<div class="pure-control-group">
								<label for="citizenship_filter">Citizenship Filter：</label>
								<select name="citizenship_filter" id="citizenship_filter">
									<option value="0" selected>ALL</option>
<?php
		$tmp = get_country_id_and_name();
		usort($tmp, "compare_country_name");
		foreach ($tmp as $temp) {
			echo <<<EOD
									<option value="$temp[0]">$temp[1]</option>\n
EOD;
		}
?>
								</select>
							</div>
							<div class="pure-controls">
								<button type="submit" id="submit" class="pure-button pure-button-primary">Submit</button>
							</div>
						</fieldset>
					</form>
				</div>
				<div id="battle_info">
<?php
		if (isset($error_message)) {
			echo <<<EOD
					<div class="heading_center heading_highlight" id="error_message">
						<h3>$error_message</h3>
					</div>
					<div id="loading_img">
					</div>\n
EOD;
		} else {
?>
					<div>
						<div id="loading_img">
						</div>
						<div id="battle_data">
							<div>
								<table class="pure-table pure-table-bordered">
									<thead>
										<th></th>
										<th>Defender</th>
										<th>Attacker</th>
									</thead>
									<tbody>
										<tr class="pure-table-odd">
											<td>Battle Link</td>
											<td colspan="2"><a href="http://secura.e-sim.org/battle.html?id=<?php echo $battle_id; ?>" target="_blank"><?php echo $battle_id; ?></a></td>
										</tr>
										<tr>
											<td>Round Win</td>
											<td><?php echo $defender["dmg_win"]; ?></td>
											<td><?php echo $attacker["dmg_win"]; ?></td>
										</tr>
										<tr class="pure-table-odd">
											<td>Damage</td>
											<td><?php echo number_format($defender["dmg_all"]); ?></td>
											<td><?php echo number_format($attacker["dmg_all"]); ?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>
							<div>
								<table class="pure-table pure-table-bordered">
									<thead>
										<tr>
											<th rowspan=2>#</th>
											<th rowspan=2>Citizen ID</th>
											<th rowspan=2>Citizenship</th>
											<th rowspan=2>Military Unit</th>
											<th rowspan=2>Damage</th>
											<th rowspan=2>Side</th>
											<th colspan=6>Weapon</th>
											<th rowspan=2>Clutches</th>
											<th rowspan=2>Battle Hero</th>
										</tr>
										<tr>
											<th>Q0</th>
											<th>Q1</th>
											<th>Q2</th>
											<th>Q3</th>
											<th>Q4</th>
											<th>Q5</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>0</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td><?php echo number_format($defender["dmg_all"]+$attacker["dmg_all"]); ?></td>
											<td>-</td>
											<td><?php echo number_format($weapon_all[0]); ?></td>
											<td><?php echo number_format($weapon_all[1]); ?></td>
											<td><?php echo number_format($weapon_all[2]); ?></td>
											<td><?php echo number_format($weapon_all[3]); ?></td>
											<td><?php echo number_format($weapon_all[4]); ?></td>
											<td><?php echo number_format($weapon_all[5]); ?></td>
											<td>-</td>
											<td>-</td>
										</tr>
<?php
			usort($data, "compare_damage");
			for ($i=0;$i<$all;$i++) {
?>
										<tr id="battle-data-<?php echo $i+1; ?>" <?php echo ($i%10 >= 5) ? ' class="pure-table-odd"' : ""; ?>>
											<td><?php echo $i+1; ?></td>
											<td><a href="http://secura.e-sim.org/profile.html?id=<?php echo $data[$i]->citizen; ?>" target="_blank"><?php echo (isset($data[$i]->citizen_name)) ? $data[$i]->citizen_name : $data[$i]->citizen; ?></a></td>
											<td><?php echo country_id_to_name($data[$i]->citizenship); ?></td>
											<td><a href="http://secura.e-sim.org/militaryUnit.html?id=<?php echo $data[$i]->military; ?>" target="_blank"><?php echo military_unit_id_to_name($data[$i]->military); ?></a></td>
											<td><?php echo number_format($data[$i]->damage); ?></td>
											<td><?php echo ($data[$i]->defenderside) ? "Defender" : "Attacker"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[0])) ? $data[$i]->weapon[0] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[1])) ? $data[$i]->weapon[1] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[2])) ? $data[$i]->weapon[2] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[3])) ? $data[$i]->weapon[3] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[4])) ? $data[$i]->weapon[4] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[5])) ? $data[$i]->weapon[5] : "0"; ?></td>
											<td><?php echo ($data[$i]->clutch_amount != 0) ? $data[$i]->clutch_amount : ""; ?></td>
											<td><?php echo ($data[$i]->battle_hero_amount != 0 and !$military_filter and !$citizenship_filter) ? $data[$i]->battle_hero_amount : ""; ?></td>
										</tr>
<?php
			}
?>
									</tbody>
								</table>
							</div>
<?php
			if ($military_filter) {
?>
							<div class="heading_center heading_title" id="military_damage">
								<h4>Military Unit Total Damage : <?php echo number_format($military_damage); ?></h4>
							</div>
<?php
			}
?>
						</div>
					</div>
				</div>
<?php
		}
		echo <<<EOD
				</div>\n
EOD;
	}
?>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){$("#battle_query").submit(function(){var content="<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";var url="<?php echo $_SERVER['PHP_SELF']; ?>?battleid="+$("#battleid").val()+"&military_filter="+$("#military_filter").val()+"&citizenship_filter="+$("#citizenship_filter").val();$("#submit").attr("disabled",true);$("#loading_img").empty();$("#error_message").remove();$("#loading_img").prepend(content);$("#battle_data").empty();$("#battle_info").load(url+" #battle_info",function(){$("#submit").attr("disabled",false)});return false})});
		</script>
	</body>
</html>
<?php
	function compare_battle_id($a, $b) {
		if($a->id == $b->id) {
			return 0 ;
		} 
		return ($a->id > $b->id) ? 1 : -1;
	}
	
	function compare_country_name($a, $b) {
		return strcmp($a[1], $b[1]);
	}
	
	function compare_damage($a, $b) {
		if($a->damage == $b->damage) {
			return 0 ;
		} 
		return ($a->damage < $b->damage) ? 1 : -1;
	}
?>
<?php
	/*
	
	jQuery Backup
	
	$(document).ready(function() {
		$("#battle_query").submit(function(){
			var content = "<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";
			var url = "<?php echo $_SERVER['PHP_SELF']; ?>?battleid=" + $("#battleid").val() + "&military_filter=" + $("#military_filter").val() + "&citizenship_filter=" + $("#citizenship_filter").val();
			$("#submit").attr("disabled",true);
			$("#loading_img").empty();
			$("#error_message").remove();
			$("#loading_img").prepend(content);
			$("#battle_data").empty();
			$("#battle_info").load(url + " #battle_info", function(){
				$("#submit").attr("disabled",false);
			});
			return false;
		});
	});
	
	*/
?>