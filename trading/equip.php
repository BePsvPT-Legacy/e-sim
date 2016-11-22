<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if ($_SESSION['web_admin'] == 0) {
		header("Location: " . $prefix . "trading/index.php");
		exit();
	}
	
	$type_name = array("輔助裝備", "頭盔", "盔甲", "護目鏡", "武器配件");
	$level_name = array("Q1", "Q2", "Q3", "Q4", "Q5", "Q6");
	$effect_name = array("降低失誤機率", "增加爆擊機率", "增加最大傷害比率", "增加傷害輸出", "降低體力耗損機率");
	$currency_name = array("Gold", "TWD");
	$status_name = array("販售中", "已賣出");
	$species_value = $_GET['species'];
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head("Equip", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div>
<?php if (isset($_GET['species']) and !preg_match("/^[0-4]{1}$/", $_GET['species'])) { ?>
			<h4>似乎發生了一點小錯誤呦，在檢查一下網址是否正確吧~</h4>
<?php } else { ?>
			<h3><?php echo (isset($_GET['species'])) ? $type_name[$species_value] : "新鮮上架 熱騰騰的呦 ^_^"; ?></h3>
<?php
	echo <<<EOD
			<table class="pure-table pure-table-trading">
				<thead>
					<tr>
						<th>#</th>
						<th>賣家 I D</th>
						<th>裝備類型</th>
						<th>裝備等級</th>
						<th>性 能 一</th>
						<th>性 能 二</th>
						<th>販售價格</th>
						<th>貨幣類型</th>
						<th>賣家連結</th>
						<th>交易狀況</th>
						<th>備　　註</th>
						<th>管　　理</th>
					</tr>
				</thead>
				<tbody>\n
EOD;
	if (!isset($_GET['species'])) {
		$sql = "SELECT `id`, `cid`, `nickname`, `equip_type`, `equip_level`, `equip_effect_1_name`, `equip_effect_1_value`, `equip_effect_2_name`, `equip_effect_2_value`, `sail_price`, `currency`, `citizen_link`, `status`, `remark` FROM `release_equip` WHERE `status` = ? ORDER BY `equip_type` ASC, `equip_level` DESC";
		if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
			$stmt->close();
			handle_database_error($web_url, $mysqli_object_connecting->error);
			exit();
		} else {
			$stmt->bind_param('s', $value);
			$value = 0;
			$stmt->execute();
			$stmt->bind_result($id, $cid, $nickname, $equip_type, $equip_level, $equip_effect_1_name, $equip_effect_1_value, $equip_effect_2_name, $equip_effect_2_value, $sail_price, $currency, $citizen_link, $status, $remark);
			$i = 1;
			while ($stmt->fetch()) {
				if ($i % 2 == 1) {
					echo <<<EOD
					<tr class="pure-table-odd">\n
EOD;
				} else {
					echo <<<EOD
					<tr>\n
EOD;
				}
				echo <<<EOD
						<td>$id</td>
						<td>$nickname</td>
						<td>$type_name[$equip_type]</td>
						<td>$level_name[$equip_level]</td>
						<td>$effect_name[$equip_effect_1_name] $equip_effect_1_value %</td>
						<td>$effect_name[$equip_effect_2_name] $equip_effect_2_value %</td>
						<td>$sail_price</td>
						<td>$currency_name[$currency]</td>
						<td><a href="http://secura.e-sim.org/profile.html?id=$citizen_link" target="_blank">點我前往</a></td>
						<td>$status_name[$status]</td>
						<td>$remark</td>\n
EOD;
				if ($cid == $_SESSION['cid']) {
					echo <<<EOD
						<td>
							<form name="manage" action="./manage/manage.equip.php" method="POST">
								<div>
									<input type="text" name="equipid" value="$id" autocomplete="off" readonly="true" hidden="true">
								</div>
								<div>
									<button type="submit">管理</button>
								</div>
							</form>
						</td>\n
EOD;
				} else {
					echo <<<EOD
						<td></td>\n
EOD;
				}
				echo <<<EOD
					</tr>\n
EOD;
				$i++;
			}
			$stmt->close();
		}
	} else if (preg_match("/^[0-4]{1}$/", $_GET['species'])) {
		$sql = "SELECT `id`, `cid`, `nickname`, `equip_type`, `equip_level`, `equip_effect_1_name`, `equip_effect_1_value`, `equip_effect_2_name`, `equip_effect_2_value`, `sail_price`, `currency`, `citizen_link`, `status`, `remark` FROM `release_equip` WHERE `equip_type` = ? AND `status` = ? ORDER BY `equip_type` ASC, `equip_level` DESC";
		if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
			$stmt->close();
			handle_database_error($web_url, $mysqli_object_connecting->error);
			exit();
		} else {
			$stmt->bind_param('ss', $value[0], $value[1]);
			$value[0] = $species_value;
			$value[1] = 0;
			$stmt->execute();
			$stmt->bind_result($id, $cid, $nickname, $equip_type, $equip_level, $equip_effect_1_name, $equip_effect_1_value, $equip_effect_2_name, $equip_effect_2_value, $sail_price, $currency, $citizen_link, $status, $remark);
			$i = 1;
			while ($stmt->fetch()) {
				if ($i % 2 == 1) {
					echo <<<EOD
					<tr class="pure-table-odd">\n
EOD;
				} else {
					echo <<<EOD
					<tr>\n
EOD;
				}
				echo <<<EOD
						<td>$id</td>
						<td>$nickname</td>
						<td>$type_name[$equip_type]</td>
						<td>$level_name[$equip_level]</td>
						<td>$effect_name[$equip_effect_1_name] $equip_effect_1_value %</td>
						<td>$effect_name[$equip_effect_2_name] $equip_effect_2_value %</td>
						<td>$sail_price</td>
						<td>$currency_name[$currency]</td>
						<td><a href="http://secura.e-sim.org/profile.html?id=$citizen_link" target="_blank">點我前往</a></td>
						<td>$status_name[$status]</td>
						<td>$remark</td>\n
EOD;
				if ($cid == $_SESSION['cid']) {
					echo <<<EOD
						<td>
							<form name="manage" action="./manage/manage.equip.php" method="POST">
								<div>
									<input type="text" name="equipid" value="$id" autocomplete="off" readonly="true" hidden="true">
								</div>
								<div>
									<button type="submit">管理</button>
								</div>
							</form>
						</td>\n
EOD;
				} else {
					echo <<<EOD
						<td></td>\n
EOD;
				}
				echo <<<EOD
					</tr>\n
EOD;
				$i++;
			}
			$stmt->close();
		}
	}
	echo <<<EOD
				</tbody>
			</table>\n
EOD;
?>
<?php } ?>
		</div>
<?php
	display_footer();
?>