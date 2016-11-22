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
		if (!isset($_GET["citizen"])) {
			$error_message = "Please waiting for loading the data after submitted.";
		} else {
			$_GET["citizen"] = urldecode($_GET["citizen"]);
			if (preg_match("/^\d{1,7}$/", $_GET["citizen"])) {
				$citizen_id = $_GET["citizen"];
				$sql = "SELECT `citizen_name` FROM `".$dbconfig["citizen_name"]."` WHERE `id` = ?";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				} else {
					$stmt->bind_param('i', $citizen_id);
					$stmt->execute();
					$stmt->store_result();
					if (($stmt->num_rows) > 0) {
						$stmt->bind_result($citizen_name);
						$stmt->fetch();
						$stmt->close();
						$citizen_name = $citizen_name;
					}
				}
			} else if (preg_match("/^[\w ]{1,32}$/", $_GET["citizen"])) {
				$citizen_name = $_GET["citizen"];
				$sql = "SELECT `id` FROM `".$dbconfig["citizen_name"]."` WHERE `citizen_name` = ?";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				} else {
					$stmt->bind_param('s', $citizen_name);
					$stmt->execute();
					$stmt->store_result();
					if (($stmt->num_rows) > 0) {
						$stmt->bind_result($citizen_id);
						$stmt->fetch();
						$stmt->close();
						$citizen_id = $citizen_id;
					}
				}
			}
			if (!isset($citizen_id) or !isset($citizen_name)) {
				$error_message = "Wrong citizen name or id.";
			} else if (!($file = fopen($prefix."tools/battle/battle_data/battle_list","r+"))) {
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
					class battle_damage_data {
						public $id;
						public $battle_id;
						public $esim_day;
						public $citizen;
						public $citizenship;
						public $military;
						public $damage;
						public $weapon;
					}
					$battle_id_first = $battle_id = $result[0]->{"id"}-1;
					//$battle_id_first = $battle_id = 12319;
					$all = 0;
					$surplus = 10;
					$battle_query_times = 35;
					while ($surplus-- and $battle_id > 0 and $battle_query_times--) {
						$battle_id_leading = floor($battle_id/1000);
						$battle_having_citizen = false;
						if (!file_exists($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id)) {
							if (!file_exists($prefix."tools/battle/battle_data_all/".$battle_id_leading)) {
								if (!mkdir($prefix."tools/battle/battle_data_all/".$battle_id_leading, 0777) or !chmod($prefix."tools/battle/battle_data_all/".$battle_id_leading, 0777)) {
									$error_message = "There is something error when loading the data.";
									break;
								}
							}
							if (!isset($error_message)) {
								if (!($file = fopen($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id,"w")) or !chmod($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id, 0777)) {
									$error_message = "There is something error when loading the data.";
									break;
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
						if (!isset($error_message)) {
							if (!($file = fopen($prefix."tools/battle/battle_data_all/".$battle_id_leading."/".$battle_id,"r"))) {
								$error_message = "There is something error when loading the data.";
								break;
							} else {
								$first = true;
								while ($line = fgets($file) and strlen($line) > 5) {
									$result_data = json_decode($line);
									foreach ($result_data as $result) {
										if ($result->{"citizenId"} == $citizen_id) {
											if (!$first) {
												$data[$all]->damage += $result->{"damage"};
												$data[$all]->weapon[$result->{"weapon"}] += ($result->{"berserk"}) ? 5 : 1;
											} else {
												$data[$all] = new battle_damage_data();
												$data[$all]->id = $all+1;
												$data[$all]->battle_id = $battle_id;
												$data[$all]->esim_day = ceil((mktime(0, 0, 0, substr($result->{"time"}, 3, 2), substr($result->{"time"}, 0, 2), substr($result->{"time"}, 6, 4))-1345845600)/86400);
												$data[$all]->citizen = $citizen_id;
												$data[$all]->citizenship = $result->{"citizenship"};
												$data[$all]->military = (isset($result->{"militaryUnit"})) ? $result->{"militaryUnit"} : 0;
												$data[$all]->damage = $result->{"damage"};
												$data[$all]->weapon[$result->{"weapon"}] = ($result->{"berserk"}) ? 5 : 1;
												$first = false;
												$battle_having_citizen = true;
											}
										}
									}
								}
								(!$battle_having_citizen) ? $surplus++ : $all++;
								fclose($file);
							}
						}
						$battle_id--;
					}
				}
			}
		}
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "Citizen Statistics", $ico_link, $css_link, $js_link);
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
								<label for="citizen">Citizen：</label>
								<input type="text" name="citizen" id="citizen" maxlength="32" pattern="^[\w ]{1,32}$" autocomplete="off" autofocus required>
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
						<div id="citizen_data">
							<div class="heading_center">
								<h3><a href="http://secura.e-sim.org/profile.html?id=<?php echo $citizen_id; ?>" target="_blank">Citizen Link</a></h3>
							</div>
							<div class="heading_center">
								<h3>Battle range from <?php echo $battle_id+1; ?> to <?php echo $battle_id_first; ?></h3>
							</div>
							<div>
								<table class="pure-table pure-table-bordered">
									<thead>
										<tr>
											<th rowspan=2>#</th>
											<th rowspan=2>Battle ID</th>
											<th rowspan=2>Secura Day</th>
											<th rowspan=2>Citizen ID</th>
											<th rowspan=2>Citizenship</th>
											<th rowspan=2>Military Unit</th>
											<th rowspan=2>Damage</th>
											<th colspan=6>Weapon</th>
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
<?php
			for ($i=0;$i<$all;$i++) {
?>
										<tr>
											<td><?php echo $i+1; ?></td>
											<td><a href="http://secura.e-sim.org/battle.html?id=<?php echo $data[$i]->battle_id; ?>" target="_blank"><?php echo $data[$i]->battle_id; ?></a></td>
											<td><?php echo $data[$i]->esim_day; ?></td>
											<td><a href="http://secura.e-sim.org/profile.html?id=<?php echo $data[$i]->citizen; ?>" target="_blank"><?php echo $citizen_name; ?></a></td>
											<td><?php echo country_id_to_name($data[$i]->citizenship); ?></td>
											<td><a href="http://secura.e-sim.org/militaryUnit.html?id=<?php echo $data[$i]->military; ?>" target="_blank"><?php echo military_unit_id_to_name($data[$i]->military); ?></a></td>
											<td><?php echo number_format($data[$i]->damage); ?></td>
											<td><?php echo (isset($data[$i]->weapon[0])) ? $data[$i]->weapon[0] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[1])) ? $data[$i]->weapon[1] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[2])) ? $data[$i]->weapon[2] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[3])) ? $data[$i]->weapon[3] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[4])) ? $data[$i]->weapon[4] : "0"; ?></td>
											<td><?php echo (isset($data[$i]->weapon[5])) ? $data[$i]->weapon[5] : "0"; ?></td>
										</tr>
<?php
			}
?>
									</tbody>
								</table>
							</div>
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
			$(document).ready(function(){$("#battle_query").submit(function(){var content="<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";var url="<?php echo $_SERVER['PHP_SELF']; ?>?citizen="+encodeURIComponent($("#citizen").val());$("#submit").attr("disabled",true);$("#loading_img").empty();$("#error_message").remove();$("#loading_img").prepend(content);$("#citizen_data").empty();$("#battle_info").load(url+" #battle_info",function(){$("#submit").attr("disabled",false)});return false})});
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
?>
<?php
	/*
	
	jQuery Backup
	
	$(document).ready(function() {
		$("#battle_query").submit(function(){
			var content = "<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";
			var url = "<?php echo $_SERVER['PHP_SELF']; ?>?citizen=" + encodeURIComponent($("#citizen").val());
			$("#submit").attr("disabled",true);
			$("#loading_img").empty();
			$("#error_message").remove();
			$("#loading_img").prepend(content);
			$("#citizen_data").empty();
			$("#battle_info").load(url + " #battle_info", function(){
				$("#submit").attr("disabled",false);
			});
			return false;
		});
	});
	
	*/
?>