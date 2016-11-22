<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading_manage.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if (!isset($_POST['productid']) and !isset($_POST['product-id']) and !isset($_POST['product-delete'])) {
		header("Location: " . $prefix . "trading/product.php");
		exit();
	} else {
		$languages = language_translation_trading_manage_product();
		
		if (isset($_POST['productid'])) {
			$sql = "SELECT `species`, `mode`, `level`, `quantity`, `currency`, `price`, `personallink`, `others` FROM `product` WHERE `id` = ? AND `cid` = ? AND `status` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $_POST['productid'], $_SESSION['cid'], $value);
				$value = 0;
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$manage_message =  'No Data!';
				} else {
					$stmt->bind_result($result[], $result[], $result[], $result[], $result[], $result[], $result[], $result[]);
					$result[] = $stmt->fetch();
					$mode_name = array($languages['mode_sell'], $languages['mode_buy']);
					$level_name = array("Q1", "Q2", "Q3", "Q4", "Q5");
					$species_name = array($languages['species_weapon'], $languages['species_food'], $languages['species_gift'], $languages['species_ticket'], $languages['species_ds'], $languages['species_house'], $languages['species_estate'], $languages['species_hospital']);
					$stmt->close();
				}
			}
		} else if (isset($_POST['product-id']) and isset($_POST['product-quantity']) and isset($_POST['product-price']) and isset($_POST['product-remark'])) {
			if (!(preg_match("/^[\d]+$/", $_POST['product-id']))) {
				$manage_message = 'Invalid Serial Number.';
			} else if (!preg_match("/^[\d]{1,6}$/", $_POST['product-quantity'])) {
				$manage_message = 'Invalid quantity.';
			} else if (!preg_match("/^[\d\.]{1,7}$/", $_POST['product-price'])) {
				$manage_message = 'Invalid price.';
			} else {
				$_POST['product-remark'] = htmlspecialchars($_POST['product-remark'], ENT_QUOTES);
				$sql = "UPDATE `product` SET `quantity` = ?, `price` = ?, `personallink` = ?, `others` = ?, `ip` = ?, `time_unix` = ? WHERE `id` = ? AND `cid` = ? AND `status` = ?";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('sssssssss', $_POST['product-quantity'], $_POST['product-price'], $_SESSION['idlink'], $_POST['product-remark'], $ip, $current_time_unix, $_POST['product-id'], $_SESSION['cid'], $value);
					$value = 0;
					$stmt->execute();
					$stmt->close();
					$manage_message = 'Success!';
				}
			}
		} else if (isset($_POST['product-delete'])) {
			$sql = "UPDATE `product` SET `status` = ? WHERE `id` = ? AND `cid` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $value, $_POST['product-delete'], $_SESSION['cid']);
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
				<h3><?php echo $languages['id_number']; ?>：<?php echo $_POST['productid']; ?></h3>
				<form name="product-update" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="product-id"><?php echo $languages['id_number']; ?>：</label>
							<input type="text" name="product-id" id="product-id" value="<?php echo $_POST['productid'];?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="product-mode"><?php echo $languages['buy_sell']; ?>：</label>
							<input type="text" value="<?php echo $mode_name[$result[1]]; ?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="product-species"><?php echo $languages['type']; ?>：</label>
							<input type="text" value="<?php echo $species_name[$result[0]]; ?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="product-level"><?php echo $languages['level']; ?>：</label>
							<input type="text" value="<?php echo $level_name[$result[2]]; ?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<label for="product-quantity"><?php echo $languages['quantity']; ?>：</label>
							<input type="text" name="product-quantity" id="product-quantity" value="<?php echo $result[3];?>" maxlength="6" placeholder="Quantity" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="product-price"><?php echo $languages['price']; ?>：</label>
							<input type="text" name="product-price" id="product-price" value="<?php echo $result[5];?>" maxlength="7" placeholder="Price" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="product-remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="product-remark" id="product-remark" value="<?php echo $result[7];?>" maxlength="50" placeholder="非必填" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<form name="product-delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return Trade_Close_Check();">
					<fieldset>
						<div class="pure-control-group">
							<input type="text" name="product-delete" id="product-delete" value="<?php echo $_POST['productid'];?>" autocomplete="off" readonly="true" hidden="true">
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