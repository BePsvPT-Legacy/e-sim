<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	}
	
	$languages = language_translation_trading_product();
	$link_go = $languages['link_go'];
	$manage = $languages['manage'];
	
	$mode_name = array($languages['mode_sell'], $languages['mode_buy']);
	$level_name = array("Q1", "Q2", "Q3", "Q4", "Q5");
	$species_name = array($languages['species_weapon'], $languages['species_food'], $languages['species_gift'], $languages['species_ticket'], $languages['species_ds'], $languages['species_house'], $languages['species_estate'], $languages['species_hospital']);
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div class="page-wrap">
			<div>
				<h4>Do Not buy goods here and sell to SeTrade, or all your country citizen's accounts in this website will be banned!</h4>
			</div>
			<form name="Sorting" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-g pure-form-aligned">
				<div class="pure-u-1-6">
					<label for="trading_mode"><?php echo $languages['buy_sell']; ?>：</label>
					<select name="trading_mode" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['trading_mode'] == "0") { echo " selected"; } ?>><?php echo $languages["all"]; ?></option>
						<option value="1"<?php if ($_GET['trading_mode'] == "1") { echo " selected"; } ?>><?php echo $languages['mode_sell']; ?></option>
						<option value="2"<?php if ($_GET['trading_mode'] == "2") { echo " selected"; } ?>><?php echo $languages['mode_buy']; ?></option>
					</select>
				</div>
				<div class="pure-u-1-6">
					<label for="item_name"><?php echo $languages['type']; ?>：</label>
					<select name="item_name" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['item_name'] == "0") { echo " selected"; } ?>><?php echo $languages['all']; ?></option>
						<option value="1"<?php if ($_GET['item_name'] == "1") { echo " selected"; } ?>><?php echo $languages['species_weapon']; ?></option>
						<option value="2"<?php if ($_GET['item_name'] == "2") { echo " selected"; } ?>><?php echo $languages['species_food']; ?></option>
						<option value="3"<?php if ($_GET['item_name'] == "3") { echo " selected"; } ?>><?php echo $languages['species_gift']; ?></option>
						<option value="4"<?php if ($_GET['item_name'] == "4") { echo " selected"; } ?>><?php echo $languages['species_ticket']; ?></option>
						<option value="5"<?php if ($_GET['item_name'] == "5") { echo " selected"; } ?>><?php echo $languages['species_ds']; ?></option>
						<option value="6"<?php if ($_GET['item_name'] == "6") { echo " selected"; } ?>><?php echo $languages['species_house']; ?></option>
						<option value="7"<?php if ($_GET['item_name'] == "7") { echo " selected"; } ?>><?php echo $languages['species_estate']; ?></option>
						<option value="8"<?php if ($_GET['item_name'] == "8") { echo " selected"; } ?>><?php echo $languages['species_hospital']; ?></option>
					</select>
				</div>
				<div class="pure-u-1-6">
					<label for="item_level"><?php echo $languages['level']; ?>：</label>
					<select name="item_level" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['item_level'] == "0") { echo " selected"; } ?>><?php echo $languages['all']; ?></option>
						<option value="1"<?php if ($_GET['item_level'] == "1") { echo " selected"; } ?>>Q1</option>
						<option value="2"<?php if ($_GET['item_level'] == "2") { echo " selected"; } ?>>Q2</option>
						<option value="3"<?php if ($_GET['item_level'] == "3") { echo " selected"; } ?>>Q3</option>
						<option value="4"<?php if ($_GET['item_level'] == "4") { echo " selected"; } ?>>Q4</option>
						<option value="5"<?php if ($_GET['item_level'] == "5") { echo " selected"; } ?>>Q5</option>
					</select>
				</div>
				<div class="pure-u-1-6">
					<label for="item_price"><?php echo $languages['price']; ?>：</label>
					<select name="item_price" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['item_price'] == "0") { echo " selected"; } ?>><?php echo $languages['non_sorting']; ?></option>
						<option value="1"<?php if ($_GET['item_price'] == "1") { echo " selected"; } ?>><?php echo $languages['hightolow']; ?></option>
						<option value="2"<?php if ($_GET['item_price'] == "2") { echo " selected"; } ?>><?php echo $languages['lowtohigh']; ?></option>
					</select>
				</div>
			</form>
			<br />
			<table class="pure-table pure-table-trading">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo $languages['citizen']; ?></th>
						<th><?php echo $languages['buy_sell']; ?></th>
						<th><?php echo $languages['type']; ?></th>
						<th><?php echo $languages['level']; ?></th>
						<th><?php echo $languages['quantity']; ?></th>
						<th><?php echo $languages['price']; ?></th>
						<th><?php echo $languages['link']; ?></th>
						<th><?php echo $languages['remark']; ?></th>
						<th>Last Update Time</th>
						<th><?php echo $languages['manage']; ?></th>
						
					</tr>
				</thead>
				<tbody>
<?php
	$sql = "SELECT `id`, `cid`, `species`, `nickname`, `mode`, `level`, `quantity`, `price`, `personallink`, `others`, `time` FROM `product` WHERE `status` = ?";
	
	if (preg_match("/^[1-2]{1}$/",$_GET['trading_mode'])) {
		$sql .= " AND `mode` = '".($_GET['trading_mode'] - 1)."'";
	}
	if (preg_match("/^[1-8]{1}$/",$_GET['item_name'])) {
		$sql .= " AND `species` = '".($_GET['item_name'] - 1)."'";
	}
	if (preg_match("/^[1-5]{1}$/",$_GET['item_level'])) {
		$sql .= " AND `level` = '".($_GET['item_level'] - 1)."'";
	}
	if (preg_match("/^[1-2]{1}$/",$_GET['item_price'])) {
		$sql .= " ORDER BY `price` ";
		$sql .= ($_GET['item_price'] == 1) ? "DESC" : "ASC";
	} else {
		$sql .= " ORDER BY `id` DESC";
	}
		
	if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
		$stmt->close();
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		$stmt->bind_param('s', $value[0]);
		$value[0] = 0;
		$stmt->execute();
		$stmt->bind_result($id, $cid, $species, $nickname, $mode, $level, $quantity, $price, $personllink, $others, $update_time);
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
						<td>$mode_name[$mode]</td>
						<td>$species_name[$species]</td>
						<td>$level_name[$level]</td>
						<td>$quantity</td>
						<td>$price G</td>
						<td><a href="http://secura.e-sim.org/profile.html?id=$personllink" target="_blank">$link_go</a></td>
						<td>$others</td>
						<td>$update_time</td>\n
EOD;
			if ($cid == $_SESSION['cid']) {
				echo <<<EOD
						<td>
							<form name="manage" action="./manage/manage.product.php" method="POST">
								<div>
									<input type="text" name="productid" value="$id" autocomplete="off" readonly="true" hidden="true">
								</div>
								<div>
									<button type="submit">$manage</button>
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
	echo <<<EOD
				</tbody>
			</table>\n
EOD;
?>
		</div>
<?php
	display_footer();
?>