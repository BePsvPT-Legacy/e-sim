<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/customized/country_convert.php";
	require_once $prefix . "config/web_language/web_language_admin.php";
	
	if (!isset($_SESSION['cid']) or $_SESSION['web_admin'] < 5) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		$languages = language_translation_admin_account_verify();
		$manage = $languages['manage'];
		
		if (isset($_POST['account_id'])) {
			$sql = "UPDATE `accounts` SET `login_deny` = ? WHERE `id` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('ss', $login_deny_value, $_POST['account_id']);
				$login_deny_value = 0;
				$stmt->execute();
				$stmt->close();
				$manage_message = 'Success!';
			}
		}
	}
	
	$ico_link = $prefix . "admin/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "admin/sources/navigation.php";
?>
		<div class="page-wrap">
<?php if (isset($manage_message)) { ?>
			<div>
				<h4><?php echo $manage_message; ?></h4>
			</div>
<?php } ?>
			<h3><?php echo $languages['heading']; ?></h3>
			<h4>Argentina, Brazil, China, Indonesia, Iran, Netherlands, Taiwan, USA, Poland, United Kingdom</h4>
			<table class="pure-table pure-table-bordered pure-table-user-index">
				<thead>
					<tr>
						<th><?php echo $languages['account_id']; ?></th>
						<th><?php echo $languages['account_username']; ?></th>
						<th><?php echo $languages['account_nickname']; ?></th>
						<th><?php echo $languages['account_idlink']; ?></th>
						<th><?php echo $languages['account_countryid']; ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
<?php
	$sql = "SELECT `id`, `username`, `nickname`, `idlink`, `countryid` FROM `accounts` WHERE `login_deny` = ? ORDER BY `id` DESC";
	if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
		$stmt->close();
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		$stmt->bind_param('s', $value[0]);
		$value[0] = 1;
		$stmt->execute();
		$stmt->bind_result($id, $username, $nickname, $idlink, $countryid);
		$i = 1;
		while ($stmt->fetch()) {
			$country_name = count_id_to_name($countryid);
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
						<td>$id</td>
						<td>$username</td>
						<td>$nickname</td>
						<td><a href="http://secura.e-sim.org/profile.html?id=$idlink" target="_blank">$idlink</a></td>
						<td>$country_name</td>
						<td>
							<form name="manage" action="./account.verify.php" method="POST" onSubmit="return Permit_Check();">
								<div>
									<input type="text" name="account_id" value="$id" autocomplete="off" readonly="true" hidden="true">
								</div>
								<div>
									<button type="submit">$manage</button>
								</div>
							</form>
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
			<script language="JavaScript">
				function Permit_Check() {
					if(confirm("<?php echo $languages['check_permit']; ?>") == true){
						return true;
					} else {
						return false;
					}
				}
			</script>
		</div>
<?php
	display_footer();
?>