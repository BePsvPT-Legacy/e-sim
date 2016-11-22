<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/database_connect.php";
	require_once $prefix . "config_customized/country_convert.php";
	require_once $prefix . "config_customized/curl_get_function.php";
	
	if (!REGISTER_ALLOW) {
		$register_deny = "You do not have the permission to access the page.";
	} else if (isset($_SESSION['cid'])) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST["username"]) and isset($_POST["pw"]) and isset($_POST["pw_check"]) and isset($_POST["citizen_name"]) and isset($_POST["citizen_link"])) {
			if (!preg_match("/^[a-zA-Z0-9]{5,32}$/", $_POST["username"])) {
				$register_message = "You have entered an invalid username.";
			} else if (!preg_match("/^[a-z0-9]{128}$/", $_POST["pw"]) or !preg_match("/^[a-z0-9]{128}$/", $_POST["pw_check"])) {
				$register_message = "You have entered an invalid password.";
			} else if ($_POST["pw"] != $_POST["pw_check"]) {
				$register_message = "The password do not match.";
			} else if (!preg_match("/^[\w ]{2,32}$/", $_POST["citizen_name"])) {
				$register_message = "You have entered an invalid citizen name.";
			} else if (!preg_match("/^[\d]{1,7}$/", $_POST["citizen_link"])) {
				$register_message = "You have entered an invalid citizen link.";
			} else {
				$temp = user_get_info($_POST["citizen_name"], $_POST["citizen_link"]);
				if (strcmp("success",$temp[0]) != 0) {
					$register_message = $temp[1];
				} else {
					$sql = "SELECT `id` FROM `".$dbconfig["account"]."` WHERE `username` = ? UNION SELECT `id` FROM `".$dbconfig["user_data"]."` WHERE `citizen_name` = ? OR `citizen_link` = ?";
					if (!($stmt = $mysqli->prepare($sql))) {
						handle_database_error($mysqli->error);
						exit();
					} else {
						$stmt->bind_param('sss', $_POST["username"], $_POST["citizen_name"], $_POST["citizen_link"]);
						$stmt->execute();
						$stmt->store_result();
						if (($stmt->num_rows) != 0) {
							$stmt->close();
							$register_message = "The username or the citizen name had been registered.";
						} else {
							$stmt->close();
							$value = array (true, 0, 0 ,false ,false);
							
							$sql = "INSERT INTO `".$dbconfig["account"]."` (`username`, `password`, `web_login`, `web_group`, `web_admin`, `register_ip`, `register_time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?)";
							if (!($stmt = $mysqli->prepare($sql))) {
								handle_database_error($mysqli->error);
								exit();
							}
							$stmt->bind_param('ssiiiss', $_POST["username"], hash('sha512', $_POST["pw"]), $value[0], $value[1], $value[2], $ip, $time);
							$stmt->execute();
							$insert_cid = $stmt->insert_id;
							$stmt->close();
							
							$sql = "INSERT INTO `".$dbconfig["user_data"]."` (`cid`, `citizen_name`, `citizen_link`, `citizen_country_id`, `citizen_age`, `citizen_level`, `citizen_experience`, `citizen_strength`, `citizen_economy_skill`, `citizen_rank_name`, `citizen_rank_damage`, `citizen_organization`, `citizen_alive`, `citizen_ban`, `citizen_verify`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
							if (!($stmt = $mysqli->prepare($sql))) {
								handle_database_error($mysqli->error);
								exit();
							}
							$stmt->bind_param('isssiiiisssiiiis', $insert_cid, $_POST["citizen_name"], $_POST["citizen_link"], country_name_to_id($temp[1]), $temp[2], $temp[3], $temp[4], $temp[5], $temp[8], $temp[6], $temp[7], $value[3], $temp[9], $temp[10], $value[4], $time);
							$stmt->execute();
							$stmt->close();
							$register_message = "Register Success! Please send a message to <a href=\"http://secura.e-sim.org/composeMessage.html?id=305703\" target=\"_blank\">believedecision</a> to verify your account, thank you.";
						}
					}
				}
			}
		}
	}
	
	$ico_link = $prefix . "user/images/icon.ico";
	$css_link = array();
	$js_link = array("scripts/js/sha-512.js");
	display_head($prefix, "Register", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($register_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$register_deny</h3>
				</div>\n
EOD;
	} else {
		echo <<<EOD
				<div class="heading_center heading_title">
					<h1>Register</h1>
				</div>\n
EOD;
		if (isset($register_message)) {
			echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$register_message</h3>
				</div>\n
EOD;
		}
?>
				<div>
					<form name="register" id="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="Username">Username：</label>
								<input type="text" name="username" maxlength="32" placeholder="Username" pattern="^[a-zA-Z0-9]{5,32}$" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="Password">Password：</label>
								<input type="password" name="pw" id="pw" maxlength="32" placeholder="Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="Password">Password：</label>
								<input type="password" name="pw_check" id="pw_check" maxlength="32" placeholder="Password Check" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="CitizenName">Citizen Name：</label>
								<input type="text" name="citizen_name" maxlength="32" placeholder="Citizen Name" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="CitizenLink">Citizen Link：</label>
								<input type="text" name="citizen_link" maxlength="7" placeholder="Ex:305703(Only Number)" autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<input type="submit" value="Register" id="submit" class="pure-button pure-button-primary">
							</div>
						</fieldset>
					</form>
				</div>
<?php
	}
?>
			</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){$("#register").submit(function(){$("#submit").attr("disabled",true);$("#pw").val(new jsSHA($("#pw").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#pw_check").val(new jsSHA($("#pw_check").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
		</script>
	</body>
</html>