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
		$languages = language_translation_trading_release_material();
		
		if (isset($_POST['material_mode']) and isset($_POST['material_species']) and isset($_POST['material_quantity']) and isset($_POST['material_price']) and isset($_POST['material_remark'])) {
			if (!preg_match("/^[0-1]$/", $_POST['material_mode'])) {
				$release_message = "Invalid Buy/Sell.";
			} else if (!preg_match("/^[0-5]$/", $_POST['material_species'])) {
				$release_message = "Invalid type.";
			} else if (!preg_match("/^[\d]{1,6}$/", $_POST['material_quantity'])) {
				$release_message = "Invalid quantity.";
			} else if (!preg_match("/^[\d\.]{1,7}$/", $_POST['material_price'])) {
				$release_message = "Invalid price.";
			} else {
				$_POST['material_remark'] = htmlspecialchars($_POST['material_remark'], ENT_QUOTES);
				$sql = "INSERT INTO `material` (`cid`, `country_id`, `nickname`, `mode`, `species`, `quantity`, `price`, `personallink`, `others`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('sssssssssss', $_SESSION['cid'], $_SESSION['country_id'], $_SESSION['nickname'], $_POST['material_mode'], $_POST['material_species'], $_POST['material_quantity'], $_POST['material_price'], $_SESSION['idlink'], $_POST['material_remark'], $ip, $current_time_unix);
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
				<form name="material" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="material_mode"><?php echo $languages['buy_sell']; ?>：</label>
							<select name="material_mode">
								<option value="0"><?php echo $languages['mode_sell']; ?></option>
								<option value="1"><?php echo $languages['mode_buy']; ?></option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="material_species"><?php echo $languages['type']; ?>：</label>
							<select name="material_species">
								<option value="0"><?php echo $languages['species_iron']; ?></option>
								<option value="1"><?php echo $languages['species_grain']; ?></option>
								<option value="2"><?php echo $languages['species_diamond']; ?></option>
								<option value="3"><?php echo $languages['species_oil']; ?></option>
								<option value="4"><?php echo $languages['species_stone']; ?></option>
								<option value="5"><?php echo $languages['species_wood']; ?></option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="material_quantity"><?php echo $languages['quantity']; ?>：</label>
							<input type="text" name="material_quantity" maxlength="6" placeholder="Quantity" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="material_price"><?php echo $languages['price']; ?>：</label>
							<input type="text" name="material_price" maxlength="7" placeholder="Gold" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="material_remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="material_remark" maxlength="50" placeholder="Not Required" autocomplete="off">
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