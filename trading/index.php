<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading.php";
	
	$languages = language_translation_trading_index();
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div class="page-wrap">
<?php if (!isset($_SESSION['cid'])) { ?>
			<div>
				<h4><?php echo $languages['not_login']; ?></h4>
			</div>
<?php } else { ?>
			<h3><?php echo $languages['heading']; ?></h3>
			<table class="pure-table pure-table-bordered pure-table-user-index">
				<tbody>
<?php
	$quantity = array();
	$i = 0;
	$sql = "SELECT `id` FROM `accounts` WHERE `login_deny` = 0 AND `last_login_time_unix` > ".($current_time_unix - 604800).";";
	$sql .= "SELECT `id` FROM `product` WHERE `status` = 0;";
	$sql .= "SELECT `id` FROM `material` WHERE `status` = 0;";
	$sql .= "SELECT `id` FROM `release_work` WHERE `status` = 0";
	
	if (!($mysqli_object_connecting->multi_query($sql))) {
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		do {
			if (!($result = $mysqli_object_connecting->store_result())) {
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$quantity[$i++] = $result->num_rows;
				$result->free();
			}
		} while ($mysqli_object_connecting->next_result());
	}
	$i = 0;
	$item = array($languages['activity_account']."：", $languages['product_quantity']."：", $languages['material_quantity']."：", $languages['work_quantity']."：");
	foreach($item as $item) {
		echo <<<EOD
					<tr>
						<td>$item</td>
						<td>$quantity[$i]</td>
					</tr>\n
EOD;
		$i++;
	}
?>
				</tbody>
			</table>
<?php } ?>
		</div>
<?php
	display_footer();
?>