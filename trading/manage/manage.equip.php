<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if (!isset($_POST['equipid']) and !isset($_POST['equip_id']) and !isset($_POST['equip_delete'])) {
		header("Location: " . $prefix . "trading/equip.php");
		exit();
	} else {
		if (isset($_POST['equipid'])) {
			$sql = "SELECT `equip_type`, `equip_level`, `equip_effect_1_value`, `equip_effect_2_value`, `sail_price`, `citizen_link`, `remark` FROM `release_equip` WHERE `id` = ? AND `cid` = ? AND `status` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $_POST['equipid'], $_SESSION['cid'], $value);
				$value = 0;
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$manage_message =  '查無資料';
				} else {
					$stmt->bind_result($result[], $result[], $result[], $result[], $result[], $result[], $result[]);
					$result[] = $stmt->fetch();
					$type_name = array("輔助裝備", "頭盔", "盔甲", "護目鏡", "武器配件");
					$level_name = array("Q1", "Q2", "Q3", "Q4", "Q5", "Q6");
					$effect_name = array("降低失誤機率", "增加爆擊機率", "增加最大傷害比率", "增加傷害輸出", "降低體力耗損機率");
					$stmt->close();
				}
			}
		} else if (isset($_POST['equip_id']) and isset($_POST['equip_effect_1_name']) and isset($_POST['equip_effect_1_value']) and isset($_POST['equip_effect_2_name']) and isset($_POST['equip_effect_2_value']) and isset($_POST['sail_price']) and isset($_POST['currency']) and isset($_POST['citizen_link']) and isset($_POST['remark'])) {
			if (!(preg_match("/^[\d]+$/", $_POST['equip_id']))) {
				$manage_message = '編號錯誤';
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
				$sql = "UPDATE `release_equip` SET `equip_effect_1_name` = ?, `equip_effect_1_value` = ?, `equip_effect_2_name` = ?, `equip_effect_2_value` = ?, `sail_price` = ?, `currency` = ?, `citizen_link` = ?, `remark` = ?, `ip` = ?, `time_unix` = ? WHERE `id` = ? AND `cid` = ? AND `status` = ?";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('sssssssssssss', $_POST['equip_effect_1_name'], $_POST['equip_effect_1_value'], $_POST['equip_effect_2_name'], $_POST['equip_effect_2_value'], $_POST['sail_price'], $_POST['currency'], $_POST['citizen_link'], $_POST['remark'], $ip, $current_time_unix, $_POST['equip_id'], $_SESSION['cid'], $value);
					$value = 0;
					$stmt->execute();
					$stmt->close();
					$manage_message = '更新成功';
				}
			}
		} else if (isset($_POST['equip_delete'])) {
			$sql = "UPDATE `release_equip` SET `status` = ? WHERE `id` = ? AND `cid` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $value, $_POST['equip_delete'], $_SESSION['cid']);
				$value = 1;
				$stmt->execute();
				$stmt->close();
				$manage_message = '已成功關閉交易';
			}
		}
	}
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head("管理", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div>
<?php if (isset($manage_message)) { ?>
			<div>
				<h4><?php echo $manage_message; ?></h4>
			</div>
<?php } else { ?>
			<div>
				<h3>裝備編號：<?php echo $_POST['equipid']; ?></h3>
				<form name="equip" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="equip_id">編號：</label>
							<input type="text" name="equip_id" id="equip_id" value="<?php echo $_POST['equipid'];?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="equip_type">裝備類型：</label>
							<input type="text" name="equip_type" id="equip_type" value="<?php echo $type_name[$result[0]];?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="equip_level">裝備等級：</label>
							<input type="text" name="equip_level" id="equip_level" value="<?php echo $level_name[$result[1]];?>" autocomplete="off" readonly="true" required>
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
							<input type="text" name="equip_effect_1_value" value="<?php echo $result[2];?>" maxlength="5" placeholder="Ex: 12.34" autocomplete="off" required>
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
							<input type="text" name="equip_effect_2_value" value="<?php echo $result[3];?>" maxlength="5" placeholder="Ex: 34.21" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="sail_price">出售價格：</label>
							<input type="text" name="sail_price" value="<?php echo $result[4];?>" maxlength="6" placeholder="Price" autocomplete="off" required>
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
							<input type="text" name="citizen_link" value="<?php echo $result[5];?>" maxlength="10" placeholder="Citizen Link like:1234" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="remark">備　　註：</label>
							<input type="text" name="remark" value="<?php echo $result[6]; ?>" maxlength="50" placeholder="Remark (Optional)" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">更改</button>
							<button type="reset" class="pure-button pure-button-primary">清除</button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<form name="equip_delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return Trade_Close_Check();">
					<fieldset>
						<div class="pure-control-group">
							<input type="text" name="equip_delete" id="equip_delete" value="<?php echo $_POST['equipid'];?>" autocomplete="off" readonly="true" hidden="true">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">關閉交易</button>
						</div>
					</fieldset>
				</form>
			</div>
			<script language="JavaScript">
				function Trade_Close_Check() {
					if(confirm("交易關閉後即無法更改和恢復，確定要關閉此交易？") == true){
						return true;
					} else {
						return false;
					}
				}
			</script>
<?php } ?>
		</div>
<?php
	display_footer();
?>