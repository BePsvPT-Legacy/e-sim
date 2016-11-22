<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_user.php";
	
	$languages = language_translation_user_login();
	
	if (!LOGIN_ALLOW) {
		$login_deny = $languages['login_deny'];
	} else if (isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "index.php");
		exit();
	} else {
		require_once $prefix . "user/sources/user_function.php";
		
		if (isset($_POST['username']) and isset($_POST['password'])) {
			$login_message = user_login($_POST['username'], $_POST['password']);
		}
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
	if (isset($login_deny)) {
?>
			<div>
				<h4><?php echo $login_deny; ?></h4>
			</div>
<?php
	} else {
		if (isset($login_message)) { ?>
			<div>
				<h4><?php echo $login_message; ?></h4>
			</div>
<?php
		}
?>
			<div>
				<form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="LoginCalcHash()">
					<fieldset>
						<div class="pure-control-group">
							<label for="Username"><?php echo $languages['username']; ?>：</label>
							<input type="text" name="username" id="username" maxlength="20" placeholder="Username" pattern="^[a-zA-Z0-9]{6,20}$" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Password"><?php echo $languages['password']; ?>：</label>
							<input type="password" name="password" id="password" maxlength="24" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary"><?php echo $languages['submit']; ?></button>
							<button type="reset" class="pure-button pure-button-primary"><?php echo $languages['reset']; ?></button>
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