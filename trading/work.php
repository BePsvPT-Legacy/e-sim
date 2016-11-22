<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/customized/country_convert.php";
	require_once $prefix . "config/web_language/web_language_trading.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	}
	
	$languages = language_translation_trading_work();
	$link_go = $languages['link_go'];
	$manage = $languages['manage'];
	
	$type_name = array($languages['job_recruit'], $languages['job_wanted']);
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div class="page-wrap">
			<form name="Sorting" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-g pure-form-aligned">
				<div class="pure-u-1-6">
					<label for="job_type"><?php echo $languages['type']; ?>：</label>
					<select name="job_type" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['job_type'] == "0") { echo " selected"; } ?>><?php echo $languages["all"]; ?></option>
						<option value="1"<?php if ($_GET['job_type'] == "1") { echo " selected"; } ?>><?php echo $languages['job_recruit']; ?></option>
						<option value="2"<?php if ($_GET['job_type'] == "2") { echo " selected"; } ?>><?php echo $languages['job_wanted']; ?></option>
					</select>
				</div>
				<div class="pure-u-1-6">
					<label for="job_skill_level"><?php echo $languages['skill_level']; ?>：</label>
					<select name="job_skill_level" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['job_skill_level'] == "0") { echo " selected"; } ?>><?php echo $languages['non_sorting']; ?></option>
						<option value="1"<?php if ($_GET['job_skill_level'] == "1") { echo " selected"; } ?>><?php echo $languages['hightolow']; ?></option>
						<option value="2"<?php if ($_GET['job_skill_level'] == "2") { echo " selected"; } ?>><?php echo $languages['lowtohigh']; ?></option>
					</select>
				</div>
				<div class="pure-u-1-6">
					<label for="job_daily_salary"><?php echo $languages['daily_salary']; ?>：</label>
					<select name="job_daily_salary" onChange="this.form.submit()">
						<option value="0"<?php if ($_GET['job_daily_salary'] == "0") { echo " selected"; } ?>><?php echo $languages['non_sorting']; ?></option>
						<option value="1"<?php if ($_GET['job_daily_salary'] == "1") { echo " selected"; } ?>><?php echo $languages['hightolow']; ?></option>
						<option value="2"<?php if ($_GET['job_daily_salary'] == "2") { echo " selected"; } ?>><?php echo $languages['lowtohigh']; ?></option>
					</select>
				</div>
			</form>
			<br />
			<div>
				<table class="pure-table pure-table-trading">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo $languages['citizen_id']; ?></th>
							<th><?php echo $languages['type']; ?></th>
							<th><?php echo $languages['skill_level']; ?></th>
							<th><?php echo $languages['daily_salary']; ?></th>
							<th><?php echo $languages['work_region']; ?></th>
							<th><?php echo $languages['link']; ?></th>
							<th><?php echo $languages['remark']; ?></th>
							<th><?php echo $languages['manage']; ?></th>
						</tr>
					</thead>
					<tbody>
<?php
	$sql = "SELECT `id`, `cid`, `nickname`, `type`, `skill_level`, `daily_salary`, `work_region`, `link`, `remark` FROM `release_work` WHERE `status` = ?";
	
	if (preg_match("/^[1-2]{1}$/",$_GET['job_type'])) {
		$sql .= " AND `type` = '".($_GET['job_type'] - 1)."'";
	}
	$sql .= " ORDER BY";
	if (preg_match("/^[1-2]{1}$/",$_GET['job_skill_level']) or preg_match("/^[1-2]{1}$/",$_GET['job_daily_salary'])) {
		if (preg_match("/^[1-2]{1}$/",$_GET['job_skill_level']) and preg_match("/^[1-2]{1}$/",$_GET['job_daily_salary'])) {
			$sql .= ($_GET['job_skill_level'] == 1) ? " `skill_level` DESC" : " `skill_level` ASC";
			$sql .= ($_GET['job_daily_salary'] == 1) ? ", `daily_salary` DESC" : ", `daily_salary` ASC";
		} else if (preg_match("/^[1-2]{1}$/",$_GET['job_skill_level'])) {
			$sql .= ($_GET['job_skill_level'] == 1) ? " `skill_level` DESC" : " `skill_level` ASC";
		} else {
			$sql .= ($_GET['job_daily_salary'] == 1) ? " `daily_salary` DESC" : " `daily_salary` ASC";
		}
	} else {
		$sql .= " `id` DESC";
	}
	
	if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
		$stmt->close();
		handle_database_error($web_url, $mysqli_object_connecting->error);
		exit();
	} else {
		$stmt->bind_param('s', $value);
		$value = 0;
		$stmt->execute();
		$stmt->bind_result($id, $cid, $nickname, $type, $skill_level, $daily_salary, $work_region, $link, $remark);
		$i = 1;
		while ($stmt->fetch()) {
			$work_region_name = ($work_region != 0) ? count_id_to_name($work_region) : "";
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
							<td>$nickname</td>
							<td>$type_name[$type]</td>
							<td>$skill_level</td>
							<td>$daily_salary</td>
							<td>$work_region_name</td>
							<td><a href="http://secura.e-sim.org/profile.html?id=$link" target="_blank">$link_go</a></td>
							<td>$remark</td>\n
EOD;
			if ($cid == $_SESSION['cid']) {
				echo <<<EOD
							<td>
								<form name="manage" action="./manage/manage.work.php" method="POST">
									<div>
										<input type="text" name="work_id" value="$id" autocomplete="off" readonly="true" hidden="true">
										<input type="text" name="work_type" value="$type" autocomplete="off" readonly="true" hidden="true">
									</div>
									<div>
										<button type="submit">$manage</button>
									</div>
								</form>
							</td>\n
EOD;
			} else {
				echo <<<EOD
							<td></td>\n
EOD;
			}
			echo <<<EOD
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
<?php
	display_footer();
?>