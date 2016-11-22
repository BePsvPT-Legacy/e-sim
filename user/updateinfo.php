<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_user.php";
	
	$languages = language_translation_user_updateinfo();
	
	if (!UPDATEINFO_ALLOW) {
		$update_deny = $languages['update_deny'];
	} else if (!isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "user/login.php");
		exit();
	} else {
		require_once $prefix . "user/sources/user_function.php";
		
		if (isset($_POST['old_pw']) and isset($_POST['new_pw']) and isset($_POST['new_pw_check'])) {
			$update_message = user_updateinfo_pw($_POST['old_pw'], $_POST['new_pw'], $_POST['new_pw_check']);
		} else if (isset($_POST['languages'])) {
			$update_message = user_updateinfo_language($_POST['languages']);
		}
		
		$personal_info = user_updateinfo_query();
	}
	
	$ico_link = $prefix . "user/images/icon.ico";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array($prefix . "scripts/js/SHA_512.js", $prefix . "scripts/js/SHA_512_Calc.js");
	display_head($languages['title'], $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "user/sources/navigation.php";
?>
		<div class="page-wrap">
<?php
	if (isset($update_deny)) {
?>
			<div>
				<h4><?php echo $update_deny; ?></h4>
			</div>
<?php
	} else {
		if (isset($update_message)) { ?>
			<div>
				<h4><?php echo $update_message; ?></h4>
			</div>
<?php
		}
?>
			<div>
				<div>
					<h2><?php echo $languages['username']; ?>：<?php echo $personal_info['username']; ?></h2>
				</div>
				<div>
					<h3><?php echo $languages['change_pw']; ?></h3>
					<form name="changepw" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return ChangePWCalcHash();">
						<fieldset>
							<div class="pure-control-group">
								<label for="old_pw"><?php echo $languages['change_pw_old']; ?>：</label>
								<input type="password" name="old_pw" id="old_pw" maxlength="24" placeholder="Old Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="new_pw"><?php echo $languages['change_pw_new']; ?>：</label>
								<input type="password" name="new_pw" id="new_pw" maxlength="24" placeholder="New Password" autocomplete="off" required>
							</div>
							<div class="pure-control-group">
								<label for="new_pw_check"><?php echo $languages['change_pw_new']; ?>：</label>
								<input type="password" name="new_pw_check" id="new_pw_check" maxlength="24" placeholder="New Password" autocomplete="off" required>
							</div>
							<div class="pure-controls">
								<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
								<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<div>
				<h3><?php echo $languages['change_language']; ?></h3>
				<form name="changelanguage" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="Languages"><?php echo $languages['language']; ?>：</label>
							<select name="languages">
								<option value="0">English</option>
								<option value="1">繁體中文</option>
								<option value="2">简体中文</option>
							</select>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
						</div>
					</fieldset>
				</form>
			</div>
<?php
	}
?>
		</div>
<?php
	display_footer();
?>