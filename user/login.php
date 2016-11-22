<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/database_connect.php";
	
	if (!LOGIN_ALLOW) {
		$login_deny = "You do not have the permission to access the page.";
	} else if (isset($_SESSION['cid'])) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST['username']) and isset($_POST['password'])) {
			$sql = "SELECT `id`, `web_login`, `web_group`, `web_admin` FROM `".$dbconfig["account"]."` WHERE `username` = ? AND `password` = ?";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->bind_param('ss', $_POST['username'], hash('sha512', $_POST['password']));
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$login_message = "You have entered an invalid username or password.";
				} else {
					$stmt->bind_result($result_account[], $result_account[], $result_account[], $result_account[]);
					$stmt->fetch();
					$stmt->close();
					if ($result_account[1] == false) {
						$login_message = "Login failed, this account was banned.";
					} else {
						$_SESSION['cid'] = $result_account[0];
						$_SESSION['web_group'] = $result_account[2];
						$_SESSION['web_admin'] = $result_account[3];
						
						$sql = "SELECT `citizen_country_id`, `citizen_alive`, `citizen_ban`, `citizen_verify` FROM `".$dbconfig["user_data"]."` WHERE `cid` = ?";
						if (!($stmt = $mysqli->prepare($sql))) {
							handle_database_error($mysqli->error);
							exit();
						} else {
							$stmt->bind_param('i', $_SESSION['cid']);
							$stmt->execute();
							$stmt->bind_result($result_user_data[], $result_user_data[], $result_user_data[], $result_user_data[]);
							$stmt->fetch();
							$stmt->close();
							if ($result_user_data[3] == false) {
								$login_message = "Login failed, this account is not verified, please send a message to <a href=\"http://secura.e-sim.org/composeMessage.html?id=305703\" target=\"_blank\">believedecision</a> to verify the account.";
							} else if ($result_user_data[0] != 28 and $result_user_data[0] != 32) {
								$login_message = "Login failed, you do not have the permission to login.";
							} else if ($result_user_data[2] == true) {
								$login_message = "Login failed, the citizen was banned.";
							} else if ($result_user_data[1] == false) {
								$login_message = "Login failed, the citizen is not alive.";
							} else {
								$_SESSION['citizen_country_id'] = $result_user_data[0];
								
								$sql = "UPDATE `".$dbconfig["account"]."` SET `last_login_ip` = ?, `last_login_time_unix` = ? WHERE `id` = ?";
								if (!($stmt = $mysqli->prepare($sql))) {
									handle_database_error($mysqli->error);
									exit();
								}
								$stmt->bind_param('ssi', $ip, $time, $_SESSION['cid']);
								$stmt->execute();
								$stmt->close();
								
								$sql = "INSERT INTO `".$dbconfig["web_login_log"]."` (`cid`, `ip`, `time_unix`) VALUES (?, ?, ?)";
								if (!($stmt = $mysqli->prepare($sql))) {
									handle_database_error($mysqli->error);
									exit();
								}
								$stmt->bind_param('iss', $_SESSION['cid'], $ip, $time);
								$stmt->execute();
								$stmt->close();
								
								$sql = "UPDATE `".$dbconfig["web_login_remember"]."` SET `time_to` = ? WHERE `cid` = ?";
								if (!($stmt = $mysqli->prepare($sql))) {
									handle_database_error($mysqli->error);
									exit();
								}
								$stmt->bind_param('si', $time, $_SESSION["cid"]);
								$stmt->execute();
								$stmt->close();
								
								$sql = "INSERT INTO `".$dbconfig["web_login_remember"]."` (`cid`, `username`, `hash`, `time_from`, `time_to`) VALUES (?, ?, ?, ?, ?)";
								if (!($stmt = $mysqli->prepare($sql))) {
									handle_database_error($mysqli->error);
									exit();
								}
								$hash_value = hash('sha1', rand());
								$time_end = $time+1209600;
								$stmt->bind_param('issss', $_SESSION['cid'], $_POST['username'], $hash_value, $time, $time_end);
								$stmt->execute();
								$stmt->close();
								setcookie("login_username", $_POST['username'], $time_end, "/");
								setcookie("login_hash", $hash_value, $time_end, "/");
								
								header("Location: ".$prefix."index.php");
								exit();
							}
						}
					}
				}
			}
		}
	}
	
	$ico_link = "user/images/icon.ico";
	$css_link = array();
	$js_link = array("scripts/js/sha-512.js");
	display_head($prefix, "Account Login", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($login_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$login_deny</h3>
				</div>\n
EOD;
	} else {
		echo <<<EOD
				<div class="heading_center heading_title">
					<h1>Account Login</h1>
				</div>\n
EOD;
		if (isset($login_message)) {
			session_unset();
			echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$login_message</h3>
				</div>\n
EOD;
		}
?>
				<div>
					<form name="login" id="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="Username">Username：</label>
								<input type="text" name="username" id="username" maxlength="32" placeholder="Username" pattern="^[a-zA-Z0-9]{5,32}$" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="Password">Password：</label>
								<input type="password" name="password" id="password" maxlength="32" placeholder="Password" autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<input type="submit" value="Login" id="submit" class="pure-button pure-button-primary">
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
			$(document).ready(function(){$("#login").submit(function(){$("#submit").attr("disabled",true);$("#password").val(new jsSHA($("#password").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
		</script>
	</body>
</html>