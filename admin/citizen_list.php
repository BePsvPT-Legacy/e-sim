<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/country_convert.php";
	require_once $prefix."config_customized/curl_get_function.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!($_SESSION["web_admin"] > 1)) {
		header("Location: ".$prefix."index.php");
		exit();
	} else {
		if (isset($_POST["update_info"]) and isset($_POST["citizen_cid"]) and isset($_POST["citizen_name"]) and isset($_POST["citizen_link"])) {
			$user_info_update = user_info_update($_POST["citizen_name"], $_POST["citizen_link"]);
			if (strcmp("success",$user_info_update[0]) == 0) {
				$sql = "UPDATE `".$dbconfig["user_data"]."` SET `citizen_country_id` = ?, `citizen_age` = ?, `citizen_level` = ?, `citizen_experience` = ?, `citizen_strength` = ?, `citizen_economy_skill` = ?, `citizen_rank_name` = ?, `citizen_rank_damage` = ?, `citizen_organization` = ?, `citizen_alive` = ?, `citizen_ban` = ?, `time_unix` = ? WHERE `cid` = ? AND `citizen_verify` = ?";
				if (!($stmt = $mysqli->prepare($sql))) {
					handle_database_error($mysqli->error);
					exit();
				}
				$stmt->bind_param('siiiisssiiisii', country_name_to_id($user_info_update[1]), $user_info_update[2], $user_info_update[3], $user_info_update[4], $user_info_update[5], $user_info_update[8], $user_info_update[6], $user_info_update[7], $user_info_update[9], $user_info_update[10], $user_info_update[11], $time, $_POST["citizen_cid"], $citizen_verify);
				$citizen_verify = true;
				$stmt->execute();
				$stmt->close();
			}
		}
	}
	
	$ico_link = "admin/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "公民列表", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_title">
						<h1>公民列表</h1>
					</div>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th>帳號ID</th>
									<th>公民名稱</th>
									<th>公民連結</th>
									<th>國　　家</th>
									<th>年齡</th>
									<th>等級</th>
									<th>經驗值</th>
									<th>力量</th>
									<th>工作技能</th>
									<th>軍　　階</th>
									<th>總 傷 害</th>
									<th>組 織 號</th>
									<th>非 活 躍</th>
									<th>封　　鎖</th>
									<th>審核</th>
									<th>最後更新</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
<?php
	$sql = "SELECT `cid`, `citizen_name`, `citizen_link`, `citizen_country_id`, `citizen_age`, `citizen_level`, `citizen_experience`, `citizen_strength`, `citizen_economy_skill`, `citizen_rank_name`, `citizen_rank_damage`, `citizen_organization`, `citizen_alive`, `citizen_ban`, `citizen_verify`, `time` FROM `".$dbconfig["user_data"]."` ORDER BY `id` ASC";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->execute();
		$stmt->bind_result($user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[], $user_data[]);
		$i = 1;
		while ($stmt->fetch()) {
			$user_data[3] = country_id_to_name($user_data[3]);
			$user_data[10] = number_format($user_data[10]);
			$user_data[11] = ($user_data[11]) ? "組織號" : "";
			$user_data[12] = ($user_data[12]) ? "" : "非活躍";
			$user_data[13] = ($user_data[13]) ? "封鎖" : "";
			$user_data[14] = ($user_data[14]) ? "" : "未審核";
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
									<td>$user_data[8]</td>
									<td>$user_data[9]</td>
									<td>$user_data[10]</td>
									<td>$user_data[11]</td>
									<td>$user_data[12]</td>
									<td>$user_data[13]</td>
									<td>$user_data[14]</td>
									<td>$user_data[15]</td>
									<td>\n
EOD;
			if (strcmp($user_data[14], "") == 0) {
?>
										<form name="update_info" id="update_info" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
											<div>
												<input type="text" value="<?php echo $user_data[0]; ?>" name="citizen_cid" autocomplete="off" hidden readonly required>
											</div>
											<div>
												<input type="text" value="<?php echo $user_data[1]; ?>" name="citizen_name" autocomplete="off" hidden readonly required>
											</div>
											<div>
												<input type="text" value="<?php echo $user_data[2]; ?>" name="citizen_link" autocomplete="off" hidden readonly required>
											</div>
											<div class="pure-controls">
												<input type="submit" value="更新資訊" name="update_info" class="pure-button pure-button-primary">
											</div>
										</form>
<?php
			}
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
	</body>
</html>