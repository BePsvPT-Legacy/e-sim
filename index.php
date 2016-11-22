<?php
	if (!isset($prefix)) {
		$prefix = "./";
	}
	require_once $prefix."config/database_connect.php";
	
	$ico_link = "images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix,"E-Sim Tools",$ico_link,$css_link,$js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div id="web_announcement">
					<div class="heading_center heading_title">
						<h1>Web Announcement</h1>
					</div>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Type</th>
									<th>Content</th>
									<th>Time</th>
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
		$announcement_type = array("Update", "Optimization", "Remove");
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
									<td>$post_time</td>
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