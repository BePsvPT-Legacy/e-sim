<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	require_once $prefix."config_customized/military_unit_data.php";
	
	if (!WAR_INFO_QUERY_CITIZEN) {
		$query_deny = "You do not have the permission to access the page.";
	} else {
		if (!isset($_GET["battleid"]) or !isset($_GET["battleround"])) {
			$error_message = "Please waiting for loading the data after submitted.";
		} else {
			$battle_id = (preg_match("/^\d{1,5}$/",$_GET['battleid'])) ? $_GET['battleid'] : 9253;
			$battle_round = (preg_match("/^\d{1,2}$/",$_GET['battleround']) and $_GET['battleround'] > 0) ? $_GET['battleround'] : 1;
			if (!file_exists($prefix."tools/battle/battle_data_round/".$battle_id."/".$battle_id."_".$battle_round)) {
				$tmp = curl_get("http://www.cscpro.org/secura/battle/".$battle_id.".json");
				if (strpos($tmp, "error") !== false) {
					$error_message = "The battle id or the battle round is incorrect.";
				} else {
					$result = json_decode($tmp);
					if ($battle_round > $result->{"round"} and !isset($_GET["enforce_update"])) {
						$error_message = "The battle round is incorrect.";
					} else if (strcasecmp($result->{"status"},"active") == 0 and $battle_round == $result->{"round"}) {
						$error_message = "This battle id could not be queried now.";
					} else {
						if (!file_exists($prefix."tools/battle/battle_data_round/".$battle_id)) {
							if (!mkdir($prefix."tools/battle/battle_data_round/".$battle_id, 0777) or !chmod($prefix."tools/battle/battle_data_round/".$battle_id, 0777)) {
								$error_message = "There is something error when loading the data.";
							}
						}
						if (!isset($error_message)) {
							if (!($file = fopen($prefix."tools/battle/battle_data_round/".$battle_id."/".$battle_id."_".$battle_round,"w")) or !chmod($prefix."tools/battle/battle_data_round/".$battle_id."/".$battle_id."_".$battle_round, 0777)) {
								$error_message = "There is something error when loading the data.";
							} else {
								$output = curl_get("http://secura.e-sim.org/apiFights.html?battleId=".$battle_id."&roundId=".$battle_round);
								fwrite($file,$output);
								fclose($file);
							}
						}
					}
				}
			}
			if (!isset($error_message)) {
				if (!($file = fopen($prefix."tools/battle/battle_data_round/".$battle_id."/".$battle_id."_".$battle_round,"r"))) {
					$error_message = "There is something error when loading the data.";
				} else {
					class battle_citizen_damage_data {
						public $id;
						public $citizen;
						public $citizen_name;
						public $military;
						public $damage;
						public $weapon;
						public $defenderside;
					}
					$all=0;
					$damage_gap = 0;
					$damage_defender_true = 0;
					$damage_defender_false = 0;
					while ($line = fgets($file)) {
						$i=0;
						if (strlen($line) > 5) {
							$result = json_decode($line);
							foreach ($result as $result) {
								for ($i=0;$i<$all;$i++) {
									if ($result->{"citizenId"} == $data[$i]->citizen and $result->{"defenderSide"} == $data[$i]->defenderside) {
										$data[$i]->damage += $result->{"damage"};
										$data[$i]->weapon[$result->{"weapon"}] += ($result->{"berserk"}) ? 5 : 1;
										if ($data[$i]->defenderside) {
											$damage_defender_true += $result->{"damage"};
										} else {
											$damage_defender_false += $result->{"damage"};
										}
										break;
									}
								}
								if ($i == $all) {
									$data[$all] = new battle_citizen_damage_data();
									$data[$all]->id = $all+1;
									$data[$all]->citizen = $result->{"citizenId"};
									$data[$all]->military = (isset($result->{"militaryUnit"})) ? $result->{"militaryUnit"} : 0;
									$data[$all]->damage = $result->{"damage"};
									$data[$all]->weapon[$result->{"weapon"}] = ($result->{"berserk"}) ? 5 : 1;
									$data[$all]->defenderside = $result->{"defenderSide"};
									if ($result->{"defenderSide"}) {
										$damage_defender_true += $result->{"damage"};
									} else {
										$damage_defender_false += $result->{"damage"};
									}
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
						}
					}
					fclose($file);
					$damage_gap = $damage_defender_true - $damage_defender_false;
				}
			}
		}
	}
	
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "Round Statistics", $ico_link, $css_link, $js_link);
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
								<input type="text" name="battleid" id="battleid" value="<?php echo $battle_id; ?>" maxlength="5" pattern="^[\d]{1,5}$" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="battleround">Battle Round：</label>
								<input type="text" name="battleround" id="battleround" value="<?php echo $battle_round; ?>" maxlength="2" pattern="^[\d]{1,2}$" autocomplete="off" required>
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
			function compare_damage($a, $b) {
				if($a->damage == $b->damage) {
					return 0 ;
				} 
				return ($a->damage < $b->damage) ? 1 : -1;
			}
			usort($data, 'compare_damage');
?>
					<div id="loading_img">
					</div>
					<div class="pure-g">
						<div class="pure-u-1-2">
							<div class="heading_center heading_highlight">
								<h3>Defender</h3>
							</div>
							<table class="pure-table pure-table-bordered">
								<thead>
									<tr>
										<th rowspan=2>#</th>
										<th rowspan=2>Citizen ID</th>
										<th rowspan=2>Military Unit</th>
										<th rowspan=2>Damage</th>
										<th rowspan=2>Clutches</th>
									</tr>
								</thead>
								<tbody>
<?php
			$count = 1;
			for ($i=0;$i<$all;$i++) {
				if ($data[$i]->defenderside) {
?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td><a href="http://secura.e-sim.org/profile.html?id=<?php echo $data[$i]->citizen; ?>" target="_blank"><?php echo $data[$i]->citizen_name; ?></a></td>
										<td><a href="http://secura.e-sim.org/militaryUnit.html?id=<?php echo $data[$i]->military; ?>" target="_blank"><?php echo military_unit_id_to_name($data[$i]->military); ?></a></td>
										<td><?php echo number_format($data[$i]->damage); ?></td>
										<td><?php
												if ($damage_gap == 0 and $data[$i]->damage > 0) {
													echo "★";
												} else if ($damage_gap > 0 and $data[$i]->damage > $damage_gap) {
													echo "★";
												}
											?></td>
									</tr>
<?php
				}
			}
?>
								</tbody>
							</table>
						</div>
						<div class="pure-u-1-2">
							<div class="heading_center heading_highlight">
								<h3>Attacker</h3>
							</div>
							<table class="pure-table pure-table-bordered">
								<thead>
									<tr>
										<th rowspan=2>#</th>
										<th rowspan=2>Citizen ID</th>
										<th rowspan=2>Military Unit</th>
										<th rowspan=2>Damage</th>
										<th rowspan=2>Clutches</th>
									</tr>
								</thead>
								<tbody>
<?php
			$count = 1;
			for ($i=0;$i<$all;$i++) {
				if (!($data[$i]->defenderside)) {
?>
									<tr>
										<td><?php echo $count++; ?></td>
										<td><a href="http://secura.e-sim.org/profile.html?id=<?php echo $data[$i]->citizen; ?>" target="_blank"><?php echo $data[$i]->citizen_name; ?></a></td>
										<td><a href="http://secura.e-sim.org/militaryUnit.html?id=<?php echo $data[$i]->military; ?>" target="_blank"><?php echo military_unit_id_to_name($data[$i]->military); ?></a></td>
										<td><?php echo number_format($data[$i]->damage); ?></td>
										<td><?php
												if ($damage_gap == 0 and $data[$i]->damage > 0) {
													echo "★";
												} else if ($damage_gap < 0 and $data[$i]->damage > (-$damage_gap)) {
													echo "★";
												}
											?></td>
									</tr>
<?php
				}
			}
?>
								</tbody>
							</table>
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
			$(document).ready(function(){$("#battle_query").submit(function(){var content="<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";var url="<?php echo $_SERVER['PHP_SELF']; ?>?battleid="+$("#battleid").val()+"&battleround="+$("#battleround").val();$("#submit").attr("disabled",true);$("#loading_img").empty();$("#error_message").remove();$("#loading_img").prepend(content);$("#battle_info").load(url+" #battle_info",function(){$("#submit").attr("disabled",false)});return false})});
		</script>
	</body>
</html>
<?php
	/*
	
	jQuery Backup
	
	$(document).ready(function() {
		$("#battle_query").submit(function(){
			var content = "<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";
			var url = "<?php echo $_SERVER['PHP_SELF']; ?>?battleid=" + $("#battleid").val() + "&battleround=" + $("#battleround").val();
			$("#submit").attr("disabled",true);
			$("#loading_img").empty();
			$("#error_message").remove();
			$("#loading_img").prepend(content);
			$("#battle_info").load(url + " #battle_info", function(){
				$("#submit").attr("disabled",false);
			});
			return false;
		});
	});
	
	*/
?>