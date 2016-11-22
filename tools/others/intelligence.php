<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	} else if (!($_SESSION['web_group'] >= 7)) {
		$query_deny = '權限不足';
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "情報網", $ico_link, $css_link, $js_link);
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
					<div>
						<div class="heading_center heading_title">
							<h2>目前可公開情報</h2>
						</div>
						<div style="width:85%; margin:0 auto">
							<table class="pure-table">
								<thead>
									<tr>
										<th>#</th>
										<th>機密等級</th>
										<th>情報</th>
										<th>詳細內容</th>
										<th>解密時間</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>中</td>
										<td>ORG高帳存在BUG</td>
										<td>尚未達解密時間</td>
										<td>2014/08/10</td>
									</tr>
									<tr>
										<td>2</td>
										<td>中</td>
										<td>總統選舉存在BUG</td>
										<td>尚未達解密時間</td>
										<td>2014/08/24</td>
									</tr>
									<tr>
										<td>3</td>
										<td>高</td>
										<td>戰場頁面存在BUG</td>
										<td>尚未達解密時間</td>
										<td>2014/09/07</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
<?php
	}
?>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>