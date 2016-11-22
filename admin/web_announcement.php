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
		if (isset($_POST["announcement_type"]) and isset($_POST["announcement_content"])) {
			$sql = "INSERT INTO `".$dbconfig["web_announcement"]."` (`type`, `content`, `display`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?)";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->bind_param('isiss', $_POST["announcement_type"], $_POST["announcement_content"], $display, $ip, $time);
				$display = true;
				$stmt->execute();
				$stmt->close();
			}
		}
		if (isset($_POST["delete_id"])) {
			$sql = "UPDATE `".$dbconfig["web_announcement"]."` SET `display` = ? WHERE `id` = ?";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->bind_param('ii', $display, $_POST['delete_id']);
				$display = false;
				$stmt->execute();
				$stmt->close();
			}
		}
	}
	
	$ico_link = "admin/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "公告管理", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div id="web_announcement">
					<div class="heading_center heading_title">
						<h1>網頁公告</h1>
					</div>
					<div>
						<form name="new_announcement" id="new_announcement" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div class="pure-control-group">
									<label for="announcement_type">類型：</label>
									<select name="announcement_type" id="announcement_type">
										<option value="0">更新</option>
										<option value="1">優化</option>
										<option value="2">移除</option>
									</select>
								</div>
								<div class="pure-control-group">
									<label for="announcement_content">內容：</label>
									<input type="text" name="announcement_content" id="announcement_content" maxlength="256" autocomplete="off" required>
								</div>
								<div class="pure-controls">
									<button type="submit" class="pure-button pure-button-primary">新增</button>
								</div>
							</fieldset>
						</form>
					</div>
					<br>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th>#</th>
									<th>類型</th>
									<th>內容</th>
									<th>時間</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
<?php
	$sql = "SELECT `id`, `type`, `content`, `time_unix` FROM `".$dbconfig["web_announcement"]."` WHERE `display` = ? ORDER BY `id` DESC";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->bind_param('i', $display);
		$display = true;
		$stmt->execute();
		$stmt->bind_result($id, $type, $content, $post_time);
		$announcement_type = array("更新", "優化", "移除");
		while ($stmt->fetch()) {
			$post_time = date("Y-m-d", $post_time);
			if ($id % 2 == 1) {
				echo <<<EOD
								<tr class="pure-table-odd">\n
EOD;
			} else {
				echo <<<EOD
								<tr>\n
EOD;
			}
			echo <<<EOD
									<td>$id</td>
									<td>$announcement_type[$type]</td>
									<td>$content</td>
									<td>$post_time</td>\n
EOD;
?>
									<td>
										<form name="delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onSubmit="return Delete_Check();">
											<div>
												<input type="text" name="delete_id" value="<?php echo $id; ?>" autocomplete="off" readonly="true" hidden="true">
											</div>
											<div class="pure-controls">
												<button type="submit" class="pure-button pure-button-primary">刪除</button>
											</div>
										</form>
									</td>
								</tr>
<?php
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
			function Delete_Check() {
				if(confirm("是否要刪除？") == true){
					return true;
				} else {
					return false;
				}
			}
		</script>
	</body>
</html>