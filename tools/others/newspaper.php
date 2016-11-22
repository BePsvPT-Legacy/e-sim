<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	
	$article_type = array(0 => 'ALL', 1 => 'Political',2 => 'Military',3 => 'Statistics',4 => 'Event related',5 => 'Fun',6 => 'Economical',7 => 'Trade', 8 => 'Old article');
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "News Search", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($query_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$query_deny</h3>
				</div>\n
EOD;
	} else {
?>
				<div>
					<form name="news_query" id="news_query" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="news_title">Title：</label>
								<input type="text" name="news_title" id="news_title" value="<?php echo isset($_GET["news_title"]) ? $_GET["news_title"] : "";?>" maxlength="32" autocomplete="off" autofocus required>
							</div>
							<div class="pure-control-group">
								<label for="news_author">Author：</label>
								<input type="text" name="news_author" id="news_author" value="<?php echo isset($_GET["news_author"]) ? $_GET["news_author"] : "";?>" maxlength="32" autocomplete="off">
							</div>
							<div class="pure-control-group">
								<label for="news_type">Type：</label>
								<select name="news_type" id="news_type" required>
<?php
		for ($i=0;$i<count($article_type);$i++) {
?>
									<option value="<?php echo $i; ?>"<?php echo ($_GET["news_type"] == $i) ? " selected" : ""?>><?php echo $article_type[$i]; ?></option>
<?php
		}
?>
								</select>
							</div>
							<div class="pure-controls">
								<button type="submit" id="submit" class="pure-button pure-button-primary">Submit</button>
							</div>
						</fieldset>
					</form>
				</div>
				<div id="news_info">
<?php
		if (isset($error_message)) {
			echo <<<EOD
					<div class="heading_center heading_highlight" id="error_message">
						<h3>$error_message</h3>
					</div>
					<div id="loading_img">
					</div>\n
EOD;
		} else {
?>
					<div>
						<div id="loading_img">
						</div>
						<div id="query_result">
							<div>
								<table class="pure-table pure-table-bordered">
									<thead>
										<tr>
											<th>#</th>
											<th>News Title</th>
											<th>News Author</th>
											<th>News Type</th>
										</tr>
									</thead>
									<tbody>
<?php
			if ($_GET["news_title"] != "" and isset($_GET["news_type"])) {
				if (!preg_match("/^(\+| )+$/", $_GET["news_title"])) {
					$search_array = array(' ', '+');
					$replace_array = array('%', '%');
					$_GET["news_title"] = str_replace($search_array, $replace_array, htmlspecialchars($_GET["news_title"], ENT_QUOTES));
					$sql = "SELECT `id`, `news_title`, `news_group`, `news_author` FROM `".$dbconfig["news_data"]."` WHERE `news_title` LIKE ?";
					if ($_GET["news_author"] != "") {
						$sql .= " AND `news_author` LIKE ?";
					}
					if ($_GET["news_type"] > 0) {
						$sql .= " AND `news_group` = ?";
					}
					$sql .= " AND `enable` = ? ORDER BY `id` DESC LIMIT 50";
					
					if (!($stmt = $mysqli->prepare($sql))) {
						handle_database_error($mysqli->error);
						exit();
					} else {
						if ($_GET["news_author"] != "" and $_GET["news_type"] > 0) {
							$stmt->bind_param('sssi', $news_title, $news_author, $news_type, $enable);
						} else if ($_GET["news_author"] != "" and $_GET["news_type"] == 0) {
							$stmt->bind_param('ssi', $news_title, $news_author, $enable);
						} else if ($_GET["news_author"] == "" and $_GET["news_type"] > 0) {
							$stmt->bind_param('ssi', $news_title, $news_type, $enable);
						} else {
							$stmt->bind_param('si', $news_title, $enable);
						}
						$news_title = '%'.$_GET["news_title"].'%';
						$news_author = '%'.$_GET["news_author"].'%';
						$news_type = $article_type[$_GET["news_type"]];
						$enable = true;
						$stmt->execute();
						$stmt->bind_result($id, $result_title, $result_type, $result_author);
						$i = 1;
						while ($stmt->fetch()) {
?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><a href="http://secura.e-sim.org/article.html?id=<?php echo $id; ?>" target="_blank"><?php echo htmlspecialchars_decode($result_title, ENT_QUOTES); ?></a></td>
											<td><?php echo $result_author; ?></td>
											<td><?php echo $result_type; ?></td>
										</tr>
<?php
							$i++;
						}
						$stmt->close();
					}
				}
			}
?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
<?php
		}
	}
?>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>