<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else if ($_SESSION['web_admin'] == 0 and $_SESSION['country_id'] != 32) {
		$deny_message = "您無法訪問此頁面";
	}
	
	$ico_link = $prefix . "tools/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head("百寶箱", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "tools/sources/navigation.php";
?>
		<div class="page-wrap">
<?php if (isset($deny_message)) { ?>
			<div>
				<h4><?php echo $deny_message; ?></h4>
			</div>
<?php } ?>
		</div>
<?php
	display_footer();
?>