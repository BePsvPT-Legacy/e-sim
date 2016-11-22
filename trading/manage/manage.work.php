<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_trading_manage.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		$languages = language_translation_trading_manage_work();
		
		if (isset($_POST['work_id']) and isset($_POST['work_type'])) {
			$sql = "SELECT `skill_level`, `daily_salary`, `remark` FROM `release_work` WHERE `id` = ? AND `cid` = ? AND `type` = ? AND `status` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('ssss', $_POST['work_id'], $_SESSION['cid'], $_POST['work_type'], $value);
				$value = 0;
				$stmt->execute();
				$stmt->store_result();
				if (($stmt->num_rows) == 0) {
					$stmt->close();
					$manage_message =  'Not Found.';
				} else {
					$stmt->bind_result($result[], $result[], $result[]);
					$result[] = $stmt->fetch();
					$stmt->close();
				}
			}
		} else if (isset($_POST['work_manage_id']) and isset($_POST['work_manage_type']) and isset($_POST['daily_salary']) and isset($_POST['remark'])) {
			if (!(preg_match("/^[\d]+$/", $_POST['work_manage_id']))) {
				$manage_message = 'Something Error';
			} else if (!(preg_match("/^[0-1]{1}$/", $_POST['work_manage_type']))) {
				$manage_message = 'Something Error';
			} else if (!preg_match("/^[\d\.]{1,8}$/", $_POST['daily_salary'])) {
				$release_message = "Invalid Daily Salary";
			} else {
				$_POST['remark'] = htmlspecialchars($_POST['remark'], ENT_QUOTES);
				if ($_POST['work_manage_type'] == 0) {
					if (!preg_match("/^[\d]{1,2}$/", $_POST['skill_level'])) {
						$release_message = "Invalid Skill Level";
					} else if (!(($_POST['work_region'] > 0 and $_POST['work_region'] <= 50) or $_POST['work_region'] == 55 or $_POST['work_region'] == 57)) {
						$release_message = "Invalid Work Region";
					} else {
						$skill_level = $_POST['skill_level'];
						$work_region = $_POST['work_region'];
					}
				} else {
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
					$skill_level = preg_replace("/economySkill:/", "", $results[9]);
					$work_region = 0;
				}
				
				if (!isset($release_message)) {
					$sql = "UPDATE `release_work` SET `skill_level` = ?, `daily_salary` = ?, `work_region` = ?, `remark` = ?, `ip` = ?, `time_unix` = ? WHERE `id` = ? AND `cid` = ? AND `type` = ? AND `status` = ?";
					if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
						$stmt->close();
						handle_database_error($web_url, $mysqli_object_connecting->error);
						exit();
					} else {
						$stmt->bind_param('ssssssssss', $skill_level, $_POST['daily_salary'], $work_region, $_POST['remark'], $ip, $current_time_unix, $_POST['work_manage_id'], $_SESSION['cid'], $_POST['work_manage_type'], $value);
						$value = 0;
						$stmt->execute();
						$stmt->close();
						$manage_message = 'Update Success!';
					}
				}
			}
		} else if (isset($_POST['work_delete'])) {
			$sql = "UPDATE `release_work` SET `status` = ? WHERE `id` = ? AND `cid` = ?";
			if (!($stmt = $mysqli_object_connecting->prepare($sql))) {
				$stmt->close();
				handle_database_error($web_url, $mysqli_object_connecting->error);
				exit();
			} else {
				$stmt->bind_param('sss', $value, $_POST['work_delete'], $_SESSION['cid']);
				$value = 1;
				$stmt->execute();
				$stmt->close();
				$manage_message = 'Close Success!';
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
<?php if (isset($manage_message)) { ?>
			<div>
				<h4><?php echo $manage_message; ?></h4>
			</div>
<?php } else { ?>
			<div>
				<h3><?php echo $languages['id_number']; ?>：<?php echo $_POST['work_id']; ?></h3>
				<form name="work" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="work_manage_id"><?php echo $languages['id_number']; ?>：</label>
							<input type="text" name="work_manage_id" id="work_manage_id" value="<?php echo $_POST['work_id'];?>" autocomplete="off" readonly="true" required>
						</div>
						<div class="pure-control-group">
							<input type="text" name="work_manage_type" id="work_manage_type" value="<?php echo $_POST['work_type'];?>" autocomplete="off" readonly="true" hidden="true" required>
						</div>
<?php if ($_POST['work_type'] == 0) { ?>
						<div class="pure-control-group">
							<label for="skill_level"><?php echo $languages['skill_level']; ?>：</label>
							<select name="skill_level">
<?php
	for ($i=1;$i<=20;$i++) {
		if ($i == $result[0]) {
			echo <<<EOD
								<option value="$i" selected>$i</option>\n
EOD;
		} else {
			echo <<<EOD
								<option value="$i">$i</option>\n
EOD;
		}
	}
?>
							</select>
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
								<option value="32">Taiwan</option>
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
<?php } ?>
						<div class="pure-control-group">
							<label for="daily_salary"><?php echo $languages['daily_salary']; ?>：</label>
							<input type="text" name="daily_salary" value="<?php echo $result[1]; ?>" maxlength="5" placeholder="Ex:1.75" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="remark"><?php echo $languages['remark']; ?>：</label>
							<input type="text" name="remark" value="<?php echo $result[2]; ?>" maxlength="50" placeholder="Optional" autocomplete="off">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
			<div>
				<form name="work_delete" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return Trade_Close_Check();">
					<fieldset>
						<div class="pure-control-group">
							<input type="text" name="work_delete" id="work_delete" value="<?php echo $_POST['work_id'];?>" autocomplete="off" readonly="true" hidden="true">
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['delete']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
			<script language="JavaScript">
				function Trade_Close_Check() {
					if(confirm("<?php echo $languages['check_delete']; ?>") == true){
						return true;
					} else {
						return false;
					}
				}
			</script>
<?php } ?>
		</div>
<?php
	display_footer();
?>