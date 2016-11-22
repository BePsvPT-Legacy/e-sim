<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_connect.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!($_SESSION["web_admin"] > 3)) {
		header("Location: ".$prefix."index.php");
		exit();
	}
	
	$ico_link = "admin/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "帳號列表", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_title">
						<h1>帳號列表</h1>
					</div>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th>ID</th>
									<th>帳號</th>
									<th>允許登入</th>
									<th>帳號群組</th>
									<th>管理員</th>
									<th>上次登入IP</th>
									<th>上次登入時間</th>
									<th>註冊IP</th>
									<th>註冊時間</th>
								</tr>
							</thead>
							<tbody>
<?php
	$sql = "SELECT `id`, `username`, `web_login`, `web_group`, `web_admin`, `last_login_ip`, `last_login_time_unix`, `register_ip`, `register_time_unix` FROM `".$dbconfig["account"]."` ORDER BY `id` ASC";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->execute();
		$stmt->bind_result($account_data[], $account_data[], $account_data[], $account_data[], $account_data[], $account_data[], $account_data[], $account_data[], $account_data[]);
		$i = 1;
		$manager_type = array("", "Practice Manager", "Manager", "Super Manager", "Practice Admin", "Admin", "Super Admin", "Root");
		while ($stmt->fetch()) {
			$account_data[2] = ($account_data[2]) ? "" : "否";
			$account_data[4] = $manager_type[$account_data[4]];
			$account_data[6] = date('Y-m-d h:i:s',$account_data[6]);
			$account_data[8] = date('Y-m-d h:i:s',$account_data[8]);
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
									<td>$account_data[0]</td>
									<td>$account_data[1]</td>
									<td>$account_data[2]</td>
									<td>$account_data[3]</td>
									<td>$account_data[4]</td>
									<td>$account_data[5]</td>
									<td>$account_data[6]</td>
									<td>$account_data[7]</td>
									<td>$account_data[8]</td>
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