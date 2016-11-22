<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/database_connect.php";
	
	if (!UPDATEINFO_ALLOW) {
		$update_deny = "You do not have the permission to access the page.";
	} else if (!(isset($_SESSION['cid']))) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST['old_pw']) and isset($_POST['new_pw']) and isset($_POST['new_pw_check'])) {
			$_POST['new_pw'] = hash('sha512', $_POST['new_pw']);
			$_POST['new_pw_check'] = hash('sha512', $_POST['new_pw_check']);
			if (strcmp($_POST['new_pw'], $_POST['new_pw_check']) != 0) {
				$update_message = "New password do not match.";
			} else {
				$sql = "SELECT `password` FROM `".$dbconfig["account"]."` WHERE `id` = ?";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				} else {
					$stmt->bind_param('i', $_SESSION['cid']);
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($old_pw);
					$stmt->fetch();
					if (($stmt->num_rows) == 0 or strcmp(hash('sha512', $_POST['old_pw']), $old_pw) != 0) {
						$stmt->close();
						$update_message = "The old password is wrong.";
					} else {
						$stmt->close();
						$sql = "UPDATE `".$dbconfig["account"]."` SET `password` = ? WHERE `id` = ?";
						if (!($stmt = $mysqli->prepare($sql))) {
							handle_database_error($mysqli->error);
							exit();
						} else {
							$stmt->bind_param('si', $_POST['new_pw'], $_SESSION['cid']);
							$stmt->execute();
							$stmt->close();
							$update_message = "Update Success!";
						}
					}
				}
			}
		}
	}
	
	$ico_link = "user/images/icon.ico";
	$css_link = array();
	$js_link = array("scripts/js/sha-512.js");
	display_head($prefix, "Update Information", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($update_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$update_deny</h3>
				</div>\n
EOD;
	} else {
		echo <<<EOD
				<div class="heading_center heading_title">
					<h1>Change Password</h1>
				</div>\n
EOD;
		if (isset($update_message)) {
			session_unset();
			echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$update_message</h3>
				</div>\n
EOD;
		}
?>
				<div>
					<form name="update_pw" id="update_pw" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="old_pw">Old Password：</label>
								<input type="password" name="old_pw" id="old_pw" maxlength="32" placeholder="Old Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="new_pw">New Password：</label>
								<input type="password" name="new_pw" id="new_pw" maxlength="32" placeholder="New Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="new_pw_check">New Password：</label>
								<input type="password" name="new_pw_check" id="new_pw_check" maxlength="32" placeholder="New Password Check" autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<input type="submit" value="Submit" id="submit" class="pure-button pure-button-primary">
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
			$(document).ready(function(){$("#update_pw").submit(function(){$("#submit").attr("disabled",true);$("#old_pw").val(new jsSHA($("#old_pw").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#new_pw").val(new jsSHA($("#new_pw").val(),"TEXT").getHash("SHA-512","HEX",2048));$("#new_pw_check").val(new jsSHA($("#new_pw_check").val(),"TEXT").getHash("SHA-512","HEX",2048));});});
		</script>
	</body>
</html>