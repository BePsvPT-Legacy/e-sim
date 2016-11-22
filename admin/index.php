<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	
	if (!isset($_SESSION['cid']) or $_SESSION['web_admin'] < 5) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	}
	
	$ico_link = $prefix . "admin/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head("管理", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "admin/sources/navigation.php";
?>
		<div class="page-wrap">
		</div>
<?php
	display_footer();
?>