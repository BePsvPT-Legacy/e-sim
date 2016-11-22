<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/customized/country_convert.php";
	require_once $prefix . "config/customized/customized.function.php";
	require_once $prefix . "config/customized/military.unit.data.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if ($_SESSION['country_id'] != 32) {
		$deny_message = "您無法訪問此頁面";
	} else {
		if (!isset($_GET['battleid'])) {
			$error_message = "戰役第一次被查詢時，需花較多時間，請耐心等待";
		} else {
			$battle_id = (preg_match("/^\d{1,5}$/",$_GET['battleid'])) ? $_GET['battleid'] : 9253;
			$output = curl_get("http://www.cscpro.org/secura/battle/".$battle_id.".json");
			if (strpos($output,"\"status\":\"active\"") !== false or strpos($output,"Wrong battle ID") !== false) {
				$error_message = "戰役尚未結束或戰役編號錯誤";
			} else {
				$pos_former = strpos($output, "\"round\"");
				$pos_latter = strpos($output, "\"status\"");
				$round_times = substr($output, $pos_former+8, $pos_latter-$pos_former-9);
				if (!file_exists($prefix."tools/battle/battle.data/".$battle_id)) {
					if (!($file = fopen($prefix."tools/battle/battle.data/".$battle_id,"w"))) {
						$error_message = "There is something wrong when reading the data.";
					} else {
						$replace_string = array("[","]","{","}","\"");
						for ($c=1;$c<=$round_times;$c++) {
							$output = curl_get("http://secura.e-sim.org/apiFights.html?battleId=".$battle_id."&roundId=".$c."");
							$process = preg_split("/},{/", $output);
							foreach($process as $tmp){
								$tmp_write = str_replace($replace_string, "", $tmp);
								$tmp_write = str_replace(",", "\t", $tmp_write);
								fwrite($file,$tmp_write."\n");
							}
						}
						fclose($file);
					}
				}
				if (!($file = fopen($prefix."tools/battle/battle.data/".$battle_id,"r"))) {
					$error_message = "There is something wrong when reading the data.";
				} else {
					class battle_mu_damage_data {
						public $id;
						public $citizenid;
						public $citizenship;
						public $militaryUnit;
						public $damage;
					}
					$all=0;
					while ($line = fgets($file)) {
						$i=0;
						$mu_id=0;
						$damage_value=0;
						if (strlen($line) > 5) {
							$handle = preg_split("/\t/", $line);
							foreach ($handle as $tmp) {
								if (stristr($tmp, "citizenId:") !== false) {
									$citizen_id = substr($tmp, 10);
								}
								if (stristr($tmp, "citizenship:") !== false) {
									$citizen_ship = substr($tmp, 12);
								}
								if (stristr($tmp, "militaryUnit:") !== false) {
									$mu_id = substr($tmp, 13);
								}
								if (stristr($tmp, "damage:") !== false) {
									$damage_value = substr($tmp, 7);
									break;
								}
							}
							for ($i=0;$i<$all;$i++) {
								if (strcmp($data[$i]->citizenid,$citizen_id) == 0) {
									$data[$i]->damage += $damage_value;
									break;
								}
							}
							if ($i == $all) {
								$data[$all] = new battle_mu_damage_data();
								$data[$all]->id = $all+1;
								$data[$all]->citizenid = $citizen_id;
								$data[$all]->citizenship = $citizen_ship;
								$data[$all]->militaryUnit = $mu_id;
								$data[$all]->damage = $damage_value;
								$all++;
							}
						}
					}
					fclose($file);
				}
			}
		}
	}
	
	$ico_link = $prefix . "tools/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head("軍團輸出統計", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "tools/sources/navigation.php";
?>
		<div class="page-wrap">
<?php if (isset($deny_message)) { ?>
			<div>
				<h4><?php echo $deny_message; ?></h4>
			</div>
<?php } else { ?>
			<div>
				<form name="id" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="battleid">戰役編號(id)：</label>
							<input type="text" name="battleid" id="battleid" value="<?php echo $battle_id; ?>" maxlength="5" pattern="^[\d]{1,5}$" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="country_name">國家篩選：</label>
							<select name="country_name">
								<option value="0" selected>All</option>
								<option value="1">Poland</option>
								<option value="2">Russia</option>
								<option value="3">Germany</option>
								<option value="4">France</option>
								<option value="5">Spain</option>
								<option value="6">United Kingdom</option>
								<option value="7">Italy</option>
								<option value="8">Hungary</option>
								<option value="9">Romania</option>
								<option value="10">Bulgaria</option>
								<option value="11">Serbia</option>
								<option value="12">Croatia</option>
								<option value="13">Bosnia and Herzegovina</option>
								<option value="14">Greece</option>
								<option value="15">Republic of Macedonia</option>
								<option value="16">Ukraine</option>
								<option value="17">Sweden</option>
								<option value="18">Portugal</option>
								<option value="19">Lithuania</option>
								<option value="20">Latvia</option>
								<option value="21">Slovenia</option>
								<option value="22">Turkey</option>
								<option value="23">Brazil</option>
								<option value="24">Argentina</option>
								<option value="25">Mexico</option>
								<option value="26">USA</option>
								<option value="27">Canada</option>
								<option value="28">China</option>
								<option value="29">Indonesia</option>
								<option value="30">Iran</option>
								<option value="31">South Korea</option>
								<option value="32">Taiwan</option>
								<option value="33">Israel</option>
								<option value="34">India</option>
								<option value="35">Australia</option>
								<option value="36">Netherlands</option>
								<option value="37">Finland</option>
								<option value="38">Ireland</option>
								<option value="39">Switzerland</option>
								<option value="40">Belgium</option>
								<option value="41">Pakistan</option>
								<option value="42">Malaysia</option>
								<option value="43">Norway</option>
								<option value="44">Peru</option>
								<option value="45">Chile</option>
								<option value="46">Colombia</option>
								<option value="47">Montenegro</option>
								<option value="48">Austria</option>
								<option value="49">Slovakia</option>
								<option value="50">Denmark</option>
								<option value="55">Albania</option>
								<option value="57">Egypt</option>
							</select>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">查詢</button>
						</div>
					</fieldset>
				</form>
			</div>
<?php if (isset($error_message)) { ?>
			<div>
				<h4><?php echo $error_message; ?></h4>
			</div>
<?php } else { ?>
			<div>
				<table class="pure-table pure-table-bordered pure-table-user-index">
					<thead>
						<th>#</th>
						<th>玩家 ID</th>
						<th>所屬國家</th>
						<th>所屬軍團</th>
						<th>輸出傷害</th>
					</thead>
					<tbody>
<?php
	function compare_damage($a, $b) { 
		if($a->damage == $b->damage) {
			return 0 ;
		} 
		return ($a->damage < $b->damage) ? 1 : -1;
	}
	usort($data, 'compare_damage');
	// 玩家ID瓶頸
	for ($i=0;$i<$all;$i++) {
		$mu_name = preg_split("/,/", $Military_Unit_Data[$data[$i]->militaryUnit]);
		if (isset($_GET['country_name'])) {
			if (($_GET['country_name'] > 0 and $_GET['country_name'] <= 50) or $_GET['country_name'] == 55 or $_GET['country_name'] == 57) {
				if (strcmp($mu_name[1],count_id_to_name($_GET['country_name'])) != 0) {
					continue;
				}
			}
		}
?>
						<tr>
							<td><?php echo $i+1; ?></td>
							<td><a href="http://secura.e-sim.org/militaryUnit.html?id=<?php echo $data[$i]->militaryUnit; ?>" target="_blank"><?php echo $mu_name[0]; ?></a></td>
							<td><?php echo $mu_name[1]; ?></td>
							<td><?php echo $data[$i]->damage; ?></td>
						</tr>
<?php
	}
?>
					</tbody>
				</table>
			</div>
<?php } } ?>
		</div>
<?php
	display_footer();
?>