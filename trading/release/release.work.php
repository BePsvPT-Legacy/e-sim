<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading_release.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		$languages = language_translation_trading_release_work();
		
		if (isset($_POST['skill_level']) and isset($_POST['daily_salary']) and isset($_POST['work_region']) and isset($_POST['remark'])) {
			if (!preg_match("/^[\d]{1,2}$/", $_POST['skill_level'])) {
				$release_message = "Invalid Skill Level";
			} else if (!preg_match("/^[\d\.]{1,8}$/", $_POST['daily_salary'])) {
				$release_message = "Invalid Daily Salary";
			} else if (!(($_POST['work_region'] > 0 and $_POST['work_region'] <= 50) or $_POST['work_region'] == 55 or $_POST['work_region'] == 57)) {
				$release_message = "Invalid Work Region";
			} else {
				$_POST['remark'] = htmlspecialchars($_POST['remark'], ENT_QUOTES);
				$sql = "INSERT INTO `release_work` (`cid`, `country_id`, `nickname`, `type`, `skill_level`, `daily_salary`, `work_region`, `link`, `remark`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('sssssssssss', $_SESSION['cid'], $_SESSION['country_id'], $_SESSION['nickname'], $type_boss, $_POST['skill_level'], $_POST['daily_salary'], $_POST['work_region'], $_SESSION['idlink'], $_POST['remark'], $ip, $current_time_unix);
					$type_boss = 0;
					$stmt->execute();
					$stmt->close();
					$release_message = "Success!";
				}
			}
		} else if (isset($_POST['staff_daily_salary']) and isset($_POST['staff_remark'])) {
			if (!preg_match("/^[\d\.]{1,8}$/", $_POST['staff_daily_salary'])) {
				$release_message = "Invalid Daily Salary";
			} else {
				$_POST['staff_remark'] = htmlspecialchars($_POST['staff_remark'], ENT_QUOTES);
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, "http://secura.e-sim.org/apiCitizenById.html?id=".$_SESSION['idlink']."");
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
				$output = curl_exec($ch);
				curl_close($ch);
				$output = preg_replace("/({|}|\")/", "", $output);
				$results = preg_split("/,/", $output);
				$staff_skill_level = preg_replace("/economySkill:/", "", $results[9]);
				
				$sql = "INSERT INTO `release_work` (`cid`, `country_id`, `nickname`, `type`, `skill_level`, `daily_salary`, `work_region`, `link`, `remark`, `ip`, `time_unix`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
					$stmt->close();
					handle_database_error($web_url, $mysqli_object_connecting->error);
					exit();
				} else {
					$stmt->bind_param('sssssssssss', $_SESSION['cid'], $_SESSION['country_id'], $_SESSION['nickname'], $type_staff, $staff_skill_level, $_POST['staff_daily_salary'], $staff_work_region, $_SESSION['idlink'], $_POST['staff_remark'], $ip, $current_time_unix);
					$type_staff = 1;
					$staff_work_region = 0;
					$stmt->execute();
					$stmt->close();
					$release_message = "Success!";
				}
			}
		}
	}
	
	$ico_link = $prefix . "trading/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/yui-min.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "trading/sources/navigation.php";
?>
		<div class="page-wrap">
<?php if (isset($release_message)) { ?>
			<div>
				<h4><?php echo $release_message; ?></h4>
			</div>
<?php } ?>
			<div>
				<h4>Notice : Currency is Gold</h4>
			</div>
			<div>
				<h3><?php echo $languages['heading']; ?></h3>
				<form name="Work_Boss" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="skill_level"><?php echo $languages['skill_level']; ?>：</label>
							<select name="skill_level">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="daily_salary"><?php echo $languages['daily_salary']; ?>：</label>
							<input type="text" name="daily_salary" maxlength="8" placeholder="Ex:1.75" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="work_region"><?php echo $languages['work_region']; ?>：</label>
							<select name="work_region">
								<option value="1">Poland</option>
								<option value="2">Russia</option>
								<option value="3">Germany</option>
								<option value="4">France</option>
								<option value="5">Spain</option>
								<option value="6">United Kingdom</option>
								<option value="7">Italy</option>
								<option value="8">Hungary</option>
								<option value="9">Romania</option>
								<option value="10">Bulgaria</option>
								<option value="11">Serbia</option>
								<option value="12">Croatia</option>
								<option value="13">Bosnia and Herzegovina</option>
								<option value="14">Greece</option>
								<option value="15">Republic of Macedonia</option>
								<option value="16">Ukraine</option>
								<option value="17">Sweden</option>
								<option value="18">Portugal</option>
								<option value="19">Lithuania</option>
								<option value="20">Latvia</option>
								<option value="21">Slovenia</option>
								<option value="22">Turkey</option>
								<option value="23">Brazil</option>
								<option value="24">Argentina</option>
								<option value="25">Mexico</option>
								<option value="26">USA</option>
								<option value="27">Canada</option>
								<option value="28">China</option>
								<option value="29">Indonesia</option>
								<option value="30">Iran</option>
								<option value="31">South Korea</option>
								<option value="32" selected>Taiwan</option>
								<option value="33">Israel</option>
								<option value="34">India</option>
								<option value="35">Australia</option>
								<option value="36">Netherlands</option>
								<option value="37">Finland</option>
								<option value="38">Ireland</option>
								<option value="39">Switzerland</option>
								<option value="40">Belgium</option>
								<option value="41">Pakistan</option>
								<option value="42">Malaysia</option>
								<option value="43">Norway</option>
								<option value="44">Peru</option>
								<option value="45">Chile</option>
								<option value="46">Colombia</option>
								<option value="47">Montenegro</option>
								<option value="48">Austria</option>
								<option value="49">Slovakia</option>
								<option value="50">Denmark</option>
								<option value="55">Albania</option>
								<option value="57">Egypt</option>
							</select>
						</div>
						<div class="pure-control-group">
							<label for="remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="remark" maxlength="50" placeholder="Optional" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<h3><?php echo $languages['heading_staff']; ?></h3>
				<form name="Work_Staff" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="staff_daily_salary"><?php echo $languages['daily_salary']; ?>：</label>
							<input type="text" name="staff_daily_salary" maxlength="8" placeholder="Ex:1.75" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="staff_remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="staff_remark" maxlength="50" placeholder="Optional" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
<?php
	display_footer();
?>