<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_connect.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!($_SESSION["web_admin"] > 4)) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST["account_cid"]) and isset($_POST["web_login"]) and isset($_POST["web_group"]) and isset($_POST["web_admin"])) {
			$sql = "UPDATE `".$dbconfig["account"]."` SET `web_login` = ?, `web_group` = ?, `web_admin` = ? WHERE `id` = ? AND `web_admin` <= ?";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			}
			$stmt->bind_param('iiiii', $web_login, $_POST["web_group"], $_POST["web_admin"], $_POST["account_cid"], $_SESSION["web_admin"]);
			$web_login = ($_POST["web_login"]) ? true : false ;
			$stmt->execute();
			$stmt->close();
		}
	}
	
	$ico_link = "admin/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "帳號管理", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_title">
						<h1>帳號管理</h1>
					</div>
					<div>
<?php
	$sql = "SELECT `id`, `username`, `web_login`, `web_group`, `web_admin` FROM `".$dbconfig["account"]."` ORDER BY `username` ASC";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->execute();
		$stmt->bind_result($id, $username, $web_login, $web_group, $web_admin);
		while ($stmt->fetch()) {
?>
						<div>
							<form name="account_manage" id="account_manage" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
								<fieldset>
									<div class="pure-control-group">
										<label for="username">Username：</label>
										<input type="text" name="username" id="username" value="<?php echo $username; ?>" autocomplete="off" readonly>
										<input type="text" name="account_cid" id="account_cid" value="<?php echo $id; ?>" autocomplete="off" readonly hidden required>
										<label for="web_login">Web Login：</label>
										<select name="web_login" id="web_login">
											<option value="0"<?php echo (!$web_login) ? "selected": ""; ?>>False</option>
											<option value="1"<?php echo ($web_login) ? "selected": ""; ?>>True</option>
										</select>
										<label for="web_group">Web Group：</label>
										<input type="text" name="web_group" id="web_group" value="<?php echo $web_group; ?>" style="text-align:center; width:50px;" maxlength="2" pattern="^\d{1,2}$" autocomplete="off" required>
										<label for="web_admin">Web Admin：</label>
										<select name="web_admin" id="web_admin">
											<option value="0"<?php echo ($web_admin == 0) ? "selected": ""; ?>></option>
											<option value="1"<?php echo ($web_admin == 1) ? "selected": ""; ?>>Practice Manager</option>
											<option value="2"<?php echo ($web_admin == 2) ? "selected": ""; ?>>Manager</option>
											<option value="3"<?php echo ($web_admin == 3) ? "selected": ""; ?>>Super Manager</option>
											<option value="4"<?php echo ($web_admin == 4) ? "selected": ""; ?>>Practice Admin</option>
											<option value="5"<?php echo ($web_admin == 5) ? "selected": ""; ?>>Admin</option>
											<option value="6"<?php echo ($web_admin == 6) ? "selected": ""; ?>>Super Admin</option>
											<option value="7"<?php echo ($web_admin == 7) ? "selected": ""; ?>>Root</option>
										</select>
										<button type="submit" id="submit" class="pure-button pure-button-primary" style="margin: 0 0 0 24px;">Submit</button>
									</div>
								</fieldset>
							</form>
						</div>
<?php
		}
		$stmt->close();
	}
?>
								
					</div>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>