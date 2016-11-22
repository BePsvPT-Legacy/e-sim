<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/customized/country_convert.php";
	require_once $prefix . "config/customized/customized.function.php";
	
	//if (!isset($_SESSION['cid']) or $_SESSION['country_id'] != 32) {
	if (0) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		if (!isset($_GET['battleid']) or !isset($_GET['refreshtime'])) {
			$error_message = "尚未輸入戰役編號或自動更新時間";
		} else {
			$battle_id = (preg_match("/^\d{1,5}$/",$_GET['battleid'])) ? $_GET['battleid'] : 9253;
			$refresh_time = (preg_match("/^\d{1,2}$/",$_GET['refreshtime'])) ? $_GET['refreshtime']*1000 : 10000;
			$output = curl_get("http://www.cscpro.org/secura/battle/".$battle_id.".json");
			if (strpos($output,"Wrong battle ID") !== false) {
				$error_message = "戰役編號錯誤";
			} else {
				$output = preg_replace("/<span class=\\\\\"premiumStar\\\\\">&amp;#9733;<\\\\\/span> /", "", $output);
				$split = array("\"type\"", "\"region\"", "\"defender\"", "\"attacker\"", "\"round\"", "\"status\"", "\"load\"");
				$split_length = count($split);
				$replace_string = array("type:", "started_by:", "id:", "name:", "damage:", "bar:", "roundwin:", "hero:", "round:", "hour:", "minute:", "second:", "status:");
				$replace_string_length = count($replace_string);;
				$i=0;
				for ($i=0;$i<$split_length;$i++) {
					$pos[$i] = strpos($output, $split[$i]);
				}
				$pos[$i] = strlen($output);
				for ($i=0;$i<$split_length;$i++) {
					$process[$i] = substr($output,$pos[$i],$pos[$i+1]-$pos[$i]);
					$process[$i] = preg_replace("/{/", ",", $process[$i]);
					$process[$i] = preg_replace("/(}|\")/", "", $process[$i]);
					$result[$i] = preg_split("/,/", $process[$i]);
					$c = 0;
					foreach ($result[$i] as $tmp_result) {
						$result[$i][$c] = str_replace($replace_string, "", $tmp_result);
						$c++;
					}
				//	foreach ($result[$i] as $tmp)
				//		echo $tmp."</br>\n";
				}
				//for($i=0;$i<count($result);$i++)
				//	echo count($result[$i])."<br>\n";
				// $result[] 0:type:2|5  1:region:4  2:defender:7  3:attacker:7  4:round:2|6  5:status:2  6:load:1
				$type = (strcmp($result[0][0], "Direct War") == 0) ? 0 : 1 ; // 0 Direct War | 1 Resistance War
				$region = ($result[1][1] == 0) ? 0 : 1 ; // 0 Activity | 1 Normal
				$status = (strcmp($result[5][0], "active") == 0) ? 0 : 1 ; // 0 Active | 1 End
				$damage_minus = $result[2][2]-$result[3][2];
			}
		}
	}
	
	$ico_link = "";
	$body = (isset($refresh_time)) ? " onLoad=setTimeout('refresh()',$refresh_time);" : "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array();
	display_head("戰況表", $ico_link, $body, $css_link, $js_link);
?>
		<div>
			<br/>
			<div>
				<form name="id" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="battleid">戰爭編號(id)：</label>
							<input type="text" name="battleid" id="battleid"<?php echo (isset($battle_id)) ? " value='".$battle_id."'" : ""; ?> maxlength="5" pattern="^[\d]{1,5}$" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="refreshtime">自動刷新(秒)：</label>
							<input type="text" name="refreshtime" id="refreshtime"<?php echo (isset($refresh_time)) ? " value='".($refresh_time/1000)."'" : ""; ?> maxlength="2" pattern="^[\d]{1,2}$" autocomplete="off" required>
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
						<th></th>
						<th>防守方</th>
						<th>進攻方</th>
					</thead>
					<tbody>
						<tr>
							<td>戰爭類型</td>
							<td colspan="2"><?php echo $result[0][0]; ?></td>
						</tr>
<?php if ($type == 1) { ?>
						<tr>
							<td>起義玩家</td>
							<td colspan="2"><a href="http://secura.e-sim.org/profile.html?id=<?php echo $result[0][2]; ?>" target="_blank"><?php echo $result[0][3]; ?></a></td>
						</tr>
<?php } ?>
						<tr>
							<td>戰爭地點</td>
							<td colspan="2"><a href="http://secura.e-sim.org/battle.html?id=<?php echo $battle_id; ?>" target="_blank"><?php echo ($region == 0) ? "World" : $result[1][2]; ?></a></td>
						</tr>
						<tr>
							<td>總回合數</td>
							<td colspan="2"><?php echo $result[4][0]; ?></td>
						</tr>
						<tr>
							<td>戰爭狀態</td>
							<td colspan="2"><?php echo ($status == 0) ? "進行中" : "已結束"; ?></td>
						</tr>
<?php if ($status == 0) { ?>
						<tr>
							<td>剩餘時間</td>
							<td colspan="2"><?php echo $result[4][2]."小時 ".$result[4][3]."分 ".$result[4][4]."秒"; ?></td>
						</tr>
<?php } ?>
						<tr class="pure-table-odd">
							<td>國　　家</td>
							<td><?php echo $result[2][1]; ?></td>
							<td><?php echo $result[3][1]; ?></td>
						</tr>
						<tr>
							<td>總 傷 害</td>
							<td><?php echo $result[2][2]; ?></td>
							<td><?php echo $result[3][2]; ?></td>
						</tr>
						<tr class="pure-table-odd">
							<td>傷害差距</td>
							<td><?php echo ($damage_minus > 0) ? "勝" : ""; ?></td>
							<td><?php echo ($damage_minus > 0) ? "" : "勝"; ?></td>
						</tr>
						<tr>
							<td>傷害差距</td>
							<td colspan="2"><?php echo ($damage_minus > 0) ? $damage_minus : ((-1)*$damage_minus); ?></td>
						</tr>
						<tr class="pure-table-odd">
							<td>百 分 比</td>
							<td><?php echo $result[2][3]; ?>%</td>
							<td><?php echo $result[3][3]; ?>%</td>
						</tr>
						<tr>
							<td>勝 場 數</td>
							<td><?php echo $result[2][4]; ?></td>
							<td><?php echo $result[3][4]; ?></td>
						</tr>
						<tr class="pure-table-odd">
							<td>加成地區</td>
							<td>
<?php
	if ($type == 0) {
		$country_id = region_id_find_neighbour($result[1][1]);
		foreach ($country_id as $search_id) {
			$region_name = region_id_to_name($search_id);
			echo <<<EOD
								<div>$region_name</div>\n
EOD;
		}
	} else {
		echo "\t\t\t\t\t\t\t\t<div>".$result[1][2]."</div>\n";
	}
?>
							</td>
							<td>
<?php
	if ($type == 1) {
		echo "\t\t\t\t\t\t\t\t<div>".$result[1][2]."</div>\n";
	}
?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<script language="JavaScript">
				function refresh() {
					window.location.reload();
				}
				setTimeout('refresh();',<?php echo $refresh_time; ?>);
			</script>
<?php } ?>
			<form name="backhome" action="http://crux.coder.tw/freedom/tools/index.php" method="POST" class="pure-form pure-form-aligned">
				<fieldset>
					<div class="pure-controls">
						<button type="submit" class="pure-button pure-button-primary">回首頁</button>
					</div>
				</fieldset>
			</form>
		</div>
	</body>
</html>