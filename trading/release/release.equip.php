<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION['cid']) or $_SESSION['web_admin'] == 0) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		if (isset($_POST['equip_type']) and isset($_POST['equip_level']) and isset($_POST['equip_effect_1_name']) and isset($_POST['equip_effect_1_value']) and isset($_POST['equip_effect_2_name']) and isset($_POST['equip_effect_2_value']) and isset($_POST['sail_price']) and isset($_POST['currency']) and isset($_POST['citizen_link']) and isset($_POST['remark'])) {
			if (!preg_match("/^[0-4]{1}$/", $_POST['equip_type'])) {
				$release_message = "裝備類型錯誤";
			} else if (!preg_match("/^[0-5]{1}$/", $_POST['equip_level'])) {
				$release_message = "裝備等級錯誤";
			} else if (!preg_match("/^[0-4]{1}$/", $_POST['equip_effect_1_name'])) {
				$release_message = "裝備性能錯誤";
			} else if (!preg_match("/^[\d\.]{1,5}$/", $_POST['equip_effect_1_value'])) {
				$release_message = "裝備性能錯誤";
			} else if (!preg_match("/^[0-4]{1}$/", $_POST['equip_effect_2_name'])) {
				$release_message = "裝備性能錯誤";
			} else if (!preg_match("/^[\d\.]{1,5}$/", $_POST['equip_effect_2_value'])) {
				$release_message = "裝備性能錯誤";
			} else if (!preg_match("/^[\d\.]{1,6}$/", $_POST['sail_price'])) {
				$release_message = "裝備售價錯誤";
			} else if (!preg_match("/^[0-1]{1}$/", $_POST['currency'])) {
				$release_message = "貨幣類型錯誤";
			} else if (!preg_match("/^[\d]{1,10}$/", $_POST['citizen_link'])) {
				$release_message = "個人連結錯誤";
			} else {
				$_POST['remark'] = htmlspecialchars($_POST['remark'], ENT_QUOTES);
				$sql = "INSERT INTO `release_equip` (`cid`, `nickname`, `equip_type`, `equip_level`, `equip_effect_1_name`, `equip_effect_1_value`, `equip_effect_2_name`, `equip_effect_2_value`, `sail_price`, `currency`, `citizen_link`, `remark`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('ssssssssssssss', $_SESSION['cid'], $_SESSION['nickname'], $_POST['equip_type'], $_POST['equip_level'], $_POST['equip_effect_1_name'], $_POST['equip_effect_1_value'], $_POST['equip_effect_2_name'], $_POST['equip_effect_2_value'], $_POST['sail_price'], $_POST['currency'], $_POST['citizen_link'], $_POST['remark'], $ip, $current_time_unix);
					$stmt->execute();
					$stmt->close();
					$release_message = "新增成功";
				}
			}
		}
	}
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head("Release - Equip", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div>
<?php if (isset($release_message)) { ?>
			<div>
				<h4><?php echo $release_message; ?></h4>
			</div>
<?php } ?>
			<div>
				<h3>Equip</h3>
				<form name="Equip" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="equip_type">裝備類型：</label>
							<select name="equip_type">
								<option value="0">輔助裝備</option>
								<option value="1">頭　　盔</option>
								<option value="2">盔　　甲</option>
								<option value="3">護 目 鏡</option>
								<option value="4">武器配件</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="equip_level">裝備等級：</label>
							<select name="equip_level">
								<option value="0">Q1</option>
								<option value="1">Q2</option>
								<option value="2">Q3</option>
								<option value="3">Q4</option>
								<option value="4">Q5</option>
								<option value="5">Q6</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="equip_effect_1">性能-1：</label>
							<select name="equip_effect_1_name">
								<option value="0">降低失誤機率</option>
								<option value="1">增加爆擊機率</option>
								<option value="2">增加最大傷害比率</option>
								<option value="3">增加傷害輸出</option>
								<option value="4">降低體力耗損機率</option>
							</select>
							<input type="text" name="equip_effect_1_value" maxlength="5" placeholder="Ex: 12.34" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="equip_effect_2">性能-2：</label>
							<select name="equip_effect_2_name">
								<option value="0">降低失誤機率</option>
								<option value="1">增加爆擊機率</option>
								<option value="2">增加最大傷害比率</option>
								<option value="3">增加傷害輸出</option>
								<option value="4">降低體力耗損機率</option>
							</select>
							<input type="text" name="equip_effect_2_value" maxlength="5" placeholder="Ex: 34.21" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="sail_price">出售價格：</label>
							<input type="text" name="sail_price" maxlength="6" placeholder="Price" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="currency">貨幣類型：</label>
							<select name="currency">
								<option value="0">Gold</option>
								<option value="1">TWD</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="citizen_link">賣家連結：</label>
							<input type="text" name="citizen_link" maxlength="10" placeholder="Citizen Link like:1234" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="remark">備　　註：</label>
							<input type="text" name="remark" maxlength="50" placeholder="Remark (Optional)" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">提交</button>
							<button type="reset" class="pure-button pure-button-primary">清除</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
<?php
	display_footer();
?>