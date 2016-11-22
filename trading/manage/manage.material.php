<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading_manage.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if (!isset($_POST['materialid']) and !isset($_POST['material-id']) and !isset($_POST['material-delete'])) {
		header("Location: " . $prefix . "trading/material.php");
		exit();
	} else {
		$languages = language_translation_trading_manage_material();
		
		if (isset($_POST['materialid'])) {
			$sql = "SELECT `species`, `mode`, `quantity`, `currency`, `price`, `personallink`, `others` FROM `material` WHERE `id` = ? AND `cid` = ? AND `status` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $_POST['materialid'], $_SESSION['cid'], $value);
				$value = 0;
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$manage_message =  'No Data!';
				} else {
					$stmt->bind_result($result[], $result[], $result[], $result[], $result[], $result[], $result[]);
					$result[] = $stmt->fetch();
					$mode_name = array($languages['mode_sell'], $languages['mode_buy']);
					$species_name = array($languages['species_iron'], $languages['species_grain'], $languages['species_diamond'], $languages['species_oil'], $languages['species_stone'], $languages['species_wood']);
					$stmt->close();
				}
			}
		} else if (isset($_POST['material-id']) and isset($_POST['material-quantity']) and isset($_POST['material-price']) and isset($_POST['material-remark'])) {
			if (!(preg_match("/^[\d]+$/", $_POST['material-id']))) {
				$manage_message = 'Invalid Serial Number.';
			} else if (!preg_match("/^[\d]{1,6}$/", $_POST['material-quantity'])) {
				$manage_message = 'Invalid quantity.';
			} else if (!preg_match("/^[\d\.]{1,7}$/", $_POST['material-price'])) {
				$manage_message = 'Invalid price.';
			} else {
				$_POST['material-remark'] = htmlspecialchars($_POST['material-remark'], ENT_QUOTES);
				$sql = "UPDATE `material` SET `quantity` = ?, `price` = ?, `personallink` = ?, `others` = ?, `ip` = ?, `time_unix` = ? WHERE `id` = ? AND `cid` = ? AND `status` = ?";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('sssssssss', $_POST['material-quantity'], $_POST['material-price'], $_SESSION['idlink'], $_POST['material-remark'], $ip, $current_time_unix, $_POST['material-id'], $_SESSION['cid'], $value);
					$value = 0;
					$stmt->execute();
					$stmt->close();
					$manage_message = 'Success!';
				}
			}
		} else if (isset($_POST['material-delete'])) {
			$sql = "UPDATE `material` SET `status` = ? WHERE `id` = ? AND `cid` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $value, $_POST['material-delete'], $_SESSION['cid']);
				$value = 1;
				$stmt->execute();
				$stmt->close();
				$manage_message = 'Success!';
			}
		}
	}
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div class="page-wrap">
<?php if (isset($manage_message)) { ?>
			<div>
				<h4><?php echo $manage_message; ?></h4>
			</div>
<?php } else { ?>
			<div>
				<h3><?php echo $languages['id_number']; ?>：<?php echo $_POST['materialid']; ?></h3>
				<form name="material" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="material-id"><?php echo $languages['id_number']; ?>：</label>
							<input type="text" name="material-id" id="material-id" value="<?php echo $_POST['materialid'];?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="material-mode"><?php echo $languages['buy_sell']; ?>：</label>
							<input type="text" value="<?php echo $mode_name[$result[1]]; ?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="material-species"><?php echo $languages['type']; ?>：</label>
							<input type="text" value="<?php echo $species_name[$result[0]]; ?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="material-quantity"><?php echo $languages['quantity']; ?>：</label>
							<input type="text" name="material-quantity" id="material-quantity" value="<?php echo $result[2];?>" maxlength="6" placeholder="Quantity" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="material-price"><?php echo $languages['price']; ?>：</label>
							<input type="text" name="material-price" id="material-price" value="<?php echo $result[4];?>" maxlength="7" placeholder="Price" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="material-remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="material-remark" id="material-remark" value="<?php echo $result[6];?>" maxlength="50" placeholder="非必填" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<form name="material-delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return Trade_Close_Check();">
					<fieldset>
						<div class="pure-control-group">
							<input type="text" name="material-delete" id="material-delete" value="<?php echo $_POST['materialid'];?>" autocomplete="off" readonly="true" hidden="true">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['delete']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
			<script language="JavaScript">
				function Trade_Close_Check() {
					if(confirm("<?php echo $languages['check_delete']; ?>") == true){
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