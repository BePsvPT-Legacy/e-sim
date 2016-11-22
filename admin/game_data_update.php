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
		if (isset($_POST['citizen_name'])) {
			$outputFile = '/dev/null';
			$command = 'citizen_name_processing.php level5';
			shell_exec(sprintf('php5 %s > %s 2>&1 & echo $!', $command, $outputFile));
		}
		if (isset($_POST['newspaper_news'])) {
			$outputFile = '/dev/null';
			$command = 'newspaper_news_processing.php level5';
			shell_exec(sprintf('php5 %s > %s 2>&1 & echo $!', $command, $outputFile));
		}
		if (isset($_POST['newspaper_is_news'])) {
			$sql = "INSERT INTO `news_data` (`id`, `news_title`, `news_group`, `news_author`, `enable`) VALUES  (NULL, 'No Such Article', 'Unknown Group', 'Unknown Author', '0')";
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				$stmt->execute();
				$stmt->close();
			}
		}
	}
	
	$ico_link = "admin/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "遊戲資料更新", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="heading_center heading_title">
						<h1>公民名稱更新</h1>
					</div>
					<div>
						<form name="citizen_name" id="citizen_name" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div class="pure-controls">
									<input type="submit" value="Update" name="citizen_name" id="citizen_name" class="pure-button pure-button-primary">
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<br>
				<div>
					<div class="heading_center heading_title">
						<h1>報紙資料更新</h1>
					</div>
					<div>
						<form name="newspaper_news" id="newspaper_news" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div class="pure-controls">
									<input type="submit" value="Update" name="newspaper_news" id="newspaper_news" class="pure-button pure-button-primary">
								</div>
							</fieldset>
						</form>
					</div>
					<div>
						<form name="newspaper_is_news" id="newspaper_is_news" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
							<fieldset>
								<div class="pure-controls">
									<input type="submit" value="Add Delete" name="newspaper_is_news" id="newspaper_is_news" class="pure-button pure-button-primary">
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>