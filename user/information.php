<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/database_connect.php";
	require_once $prefix . "config_customized/country_convert.php";
	require_once $prefix . "config_customized/curl_get_function.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		if (isset($_POST["update_info"]) and isset($_POST["citizen_name"]) and isset($_POST["citizen_link"])) {
			$user_info_update = user_info_update($_POST["citizen_name"], $_POST["citizen_link"]);
			if (strcmp("success",$user_info_update[0]) == 0) {
				$sql = "UPDATE `".$dbconfig["user_data"]."` SET `citizen_country_id` = ?, `citizen_age` = ?, `citizen_level` = ?, `citizen_experience` = ?, `citizen_strength` = ?, `citizen_economy_skill` = ?, `citizen_rank_name` = ?, `citizen_rank_damage` = ?, `citizen_organization` = ?, `citizen_alive` = ?, `citizen_ban` = ?, `time_unix` = ? WHERE `cid` = ?";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				}
				$stmt->bind_param('siiiisssiiisi', country_name_to_id($user_info_update[1]), $user_info_update[2], $user_info_update[3], $user_info_update[4], $user_info_update[5], $user_info_update[8], $user_info_update[6], $user_info_update[7], $user_info_update[9], $user_info_update[10], $user_info_update[11], $time, $_SESSION['cid']);
				$stmt->execute();
				$stmt->close();
			}
		}
	}
	
	$ico_link = "user/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "Account Information", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_title">
						<h1>Citizen Information</h1>
					</div>
					<div>
						<table class="pure-table pure-table-bordered">
							<tbody>
<?php
	$sql = "SELECT `citizen_name`, `citizen_link`, `citizen_age`, `citizen_level`, `citizen_experience`, `citizen_strength`, `citizen_economy_skill`, `citizen_rank_name`, `citizen_rank_damage`, `time` FROM `".$dbconfig["user_data"]."` WHERE `cid` = ?";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->bind_param('i', $_SESSION['cid']);
		$stmt->execute();
		$stmt->bind_result($account_info[], $account_info[], $account_info[], $account_info[], $account_info[], $account_info[], $account_info[], $account_info[], $account_info[], $account_info[]);
		$stmt->fetch();
		$stmt->close();
		$i = 0;
		$account_link = $account_info[1];
		$account_info[1] = "<a href=\"http://secura.e-sim.org/profile.html?id=".$account_info[1]."\" target=\"_blank\">".$account_info[1]."</a>";
		$account_info[8] = number_format($account_info[8]);
		$item = array("Citizen Name：", "Citizen Link：", "Age：", "Level：", "Experience：", "Strength：", "Skill Level：", "Rank：", "Damage：", "Last Update：");
		foreach($item as $item) {
			echo <<<EOD
								<tr>
									<td>$item</td>
									<td>$account_info[$i]</td>
								</tr>\n
EOD;
			$i++;
		}
	}
?>
							</tbody>
						</table>
					</div>
					<div>
						<form name="update_info" id="update_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div class="pure-control-group">
									<input type="text" value="<?php echo $account_info[0]; ?>" name="citizen_name" autocomplete="off" hidden readonly required>
								</div>
								<div class="pure-control-group">
									<input type="text" value="<?php echo $account_link; ?>" name="citizen_link" autocomplete="off" hidden readonly required>
								</div>
								<div class="pure-controls">
									<input type="submit" value="Update" name="update_info" class="pure-button pure-button-primary">
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div>
					<div class="heading_center heading_title">
						<h1>Account Login Log</h1>
					</div>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th>#</th>
									<th>IP</th>
									<th>Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$sql = "SELECT `ip`, `time` FROM `".$dbconfig["web_login_log"]."` WHERE `cid` = ? ORDER BY `id` DESC LIMIT 5";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->bind_param('i', $_SESSION['cid']);
		$stmt->execute();
		$stmt->bind_result($login_info[], $login_info[]);
		$i = 1;
		while ($stmt->fetch()) {
			if ($i % 2 == 1) {
				echo <<<EOD
								<tr class="pure-table-odd">\n
EOD;
			} else {
				echo <<<EOD
								<tr>\n
EOD;
			}
			echo <<<EOD
									<td>$i</td>
									<td>$login_info[0]</td>
									<td>$login_info[1]</td>
								</tr>\n
EOD;
			$i++;
		}
		$stmt->close();
	}
?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>