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
						public $military;
						public $damage;
						public $weapon;
					}
					$all=0;
					
					while ($line = fgets($file) and strlen($line) > 5 and $result_data = json_decode($line)) {
						unset($line);
						foreach ($result_data as $result) {
							if (isset($result->{"militaryUnit"})) {
								for ($i=0;$i<$all;$i++) {
									if ($result->{"militaryUnit"} == $data[$i]->military) {
										$data[$i]->damage += $result->{"damage"};
										$data[$i]->weapon[$result->{"weapon"}] += ($result->{"berserk"}) ? 5 : 1;
										break;
									}
								}
								if ($i == $all) {
									$data[$all] = new battle_damage_data();
									$data[$all]->id = $all+1;
									$data[$all]->military = $result->{"militaryUnit"};
									$data[$all]->damage = $result->{"damage"};
									$data[$all]->weapon[$result->{"weapon"}] = ($result->{"berserk"}) ? 5 : 1;
									$all++;
								}
							}
						}
					}
					fclose($file);
				}
			}
		}
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "MU Statistics", $ico_link, $css_link, $js_link);
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
										<tr>
											<th rowspan=2>#</th>
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
			usort($data, "compare_damage");
			for ($i=0;$i<$all;$i++) {
?>
										<tr id="battle-data-<?php echo $i+1; ?>" <?php echo ($i%10 >= 5) ? ' class="pure-table-odd"' : ""; ?>>
											<td><?php echo $i+1; ?></td>
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
			$(document).ready(function(){$("#battle_query").submit(function(){var content="<img src=\"<?php echo $prefix."images/loading.gif" ?>\">";var url="<?php echo $_SERVER['PHP_SELF']; ?>?battleid="+$("#battleid").val();$("#submit").attr("disabled",true);$("#loading_img").empty();$("#error_message").remove();$("#loading_img").prepend(content);$("#battle_data").empty();$("#battle_info").load(url+" #battle_info",function(){$("#submit").attr("disabled",false)});return false})});
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
			var url = "<?php echo $_SERVER['PHP_SELF']; ?>?battleid=" + $("#battleid").val();
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