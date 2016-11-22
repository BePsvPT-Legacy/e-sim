<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading_release.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		$languages = language_translation_trading_release_product();
		
		if (isset($_POST['product_mode']) and isset($_POST['product_species']) and isset($_POST['product_level']) and isset($_POST['product_quantity']) and isset($_POST['product_price']) and isset($_POST['product_remark'])) {
			if (!preg_match("/^[0-1]$/", $_POST['product_mode'])) {
				$release_message = "Invalid Buy/Sell.";
			} else if (!preg_match("/^[0-7]$/", $_POST['product_species'])) {
				$release_message = "Invalid type.";
			} else if (!preg_match("/^[0-4]$/", $_POST['product_level'])) {
				$release_message = "Invalid level.";
			} else if (!preg_match("/^[\d]{1,6}$/", $_POST['product_quantity'])) {
				$release_message = "Invalid quantity.";
			} else if (!preg_match("/^[\d\.]{1,7}$/", $_POST['product_price'])) {
				$release_message = "Invalid price.";
			} else {
				$_POST['product_remark'] = htmlspecialchars($_POST['product_remark'], ENT_QUOTES);
				$sql = "INSERT INTO `product` (`cid`, `country_id`, `nickname`, `mode`, `level`, `species`, `quantity`, `price`, `personallink`, `others`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('ssssssssssss', $_SESSION['cid'], $_SESSION['country_id'], $_SESSION['nickname'], $_POST['product_mode'], $_POST['product_level'], $_POST['product_species'], $_POST['product_quantity'], $_POST['product_price'], $_SESSION['idlink'], $_POST['product_remark'], $ip, $current_time_unix);
					$stmt->execute();
					$stmt->close();
					$release_message = "Success!";
				}
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
<?php if (isset($release_message)) { ?>
			<div>
				<h4><?php echo $release_message; ?></h4>
			</div>
<?php } ?>
			<div>
				<h3><?php echo $languages['heading']; ?></h3>
				<form name="product" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="product_mode"><?php echo $languages['buy_sell']; ?>：</label>
							<select name="product_mode">
								<option value="0"><?php echo $languages['mode_sell']; ?></option>
								<option value="1"><?php echo $languages['mode_buy']; ?></option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="product_species"><?php echo $languages['type']; ?>：</label>
							<select name="product_species">
								<option value="0"><?php echo $languages['species_weapon']; ?></option>
								<option value="1"><?php echo $languages['species_food']; ?></option>
								<option value="2"><?php echo $languages['species_gift']; ?></option>
								<option value="3"><?php echo $languages['species_ticket']; ?></option>
								<option value="4"><?php echo $languages['species_ds']; ?></option>
								<option value="5"><?php echo $languages['species_house']; ?></option>
								<option value="6"><?php echo $languages['species_estate']; ?></option>
								<option value="7"><?php echo $languages['species_hospital']; ?></option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="product_level"><?php echo $languages['level']; ?>：</label>
							<select name="product_level">
								<option value="0">Q1</option>
								<option value="1">Q2</option>
								<option value="2">Q3</option>
								<option value="3">Q4</option>
								<option value="4">Q5</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="product_quantity"><?php echo $languages['quantity']; ?>：</label>
							<input type="text" name="product_quantity" maxlength="6" placeholder="Quantity" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="product_price"><?php echo $languages['price']; ?>：</label>
							<input type="text" name="product_price" maxlength="7" placeholder="Gold" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="product_remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="product_remark" maxlength="50" placeholder="Not Required" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
<?php
	display_footer();
?>