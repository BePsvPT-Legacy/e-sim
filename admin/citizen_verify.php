<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/country_convert.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if (!($_SESSION["web_admin"] > 2)) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST['citizen_cid'])) {
			$sql = "UPDATE `".$dbconfig["user_data"]."` SET `citizen_verify` = ? WHERE `cid` = ?";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->bind_param('ii', $update_verify, $_POST['citizen_cid']);
				$update_verify = true;
				$stmt->execute();
				$stmt->close();
			}
		}
	}
	
	$ico_link = "admin/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "公民審核", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_title">
						<h1>公民審核</h1>
					</div>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th>帳號 I D</th>
									<th>公民名稱</th>
									<th>公民連結</th>
									<th>國　　家</th>
									<th>組 織 號</th>
									<th>非 活 躍</th>
									<th>封　　鎖</th>
									<th>註冊時間</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
<?php
	$sql = "SELECT `cid`, `citizen_name`, `citizen_link`, `citizen_country_id`, `citizen_organization`, `citizen_alive`, `citizen_ban`, `time` FROM `".$dbconfig["user_data"]."` WHERE `citizen_verify` = ? AND `time_unix` >= ? ORDER BY `id` DESC";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->bind_param('ii', $sql_value[0], $sql_value[1]);
		$sql_value[0] = false;
		$sql_value[1] = ($time - 604800);
		$stmt->execute();
		$stmt->bind_result($user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[]);
		$i = 1;
		while ($stmt->fetch()) {
			$user_data[3] = country_id_to_name($user_data[3]);
			$user_data[4] = ($user_data[4]) ? "組織號" : "";
			$user_data[5] = ($user_data[5]) ? "" : "非活躍";
			$user_data[6] = ($user_data[6]) ? "封鎖" : "";
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
									<td>$user_data[0]</td>
									<td>$user_data[1]</td>
									<td><a href="http://secura.e-sim.org/profile.html?id=$user_data[2]" target="_balnk">$user_data[2]</a></td>
									<td>$user_data[3]</td>
									<td>$user_data[4]</td>
									<td>$user_data[5]</td>
									<td>$user_data[6]</td>
									<td>$user_data[7]</td>
									<td>\n
EOD;
?>
										<form name="manage" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onSubmit="return Permit_Check();">
											<div>
												<input type="text" name="citizen_cid" value="<?php echo $user_data[0]; ?>" autocomplete="off" readonly="true" hidden="true">
											</div>
											<div class="pure-controls">
												<button type="submit" class="pure-button pure-button-primary">通過</button>
											</div>
										</form>
<?php
			echo <<<EOD
									</td>
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
		<script>
			function Permit_Check() {
				if(confirm("是否通過審核？") == true){
					return true;
				} else {
					return false;
				}
			}
		</script>
	</body>
</html>