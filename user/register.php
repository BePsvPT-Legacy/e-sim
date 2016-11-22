<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_user.php";
	
	$languages = language_translation_user_register();
	
	if (!REGISTER_ALLOW) {
		$register_deny = $languages['register_deny'];
	} else if (isset($_SESSION['cid'])) {
		header("Location: " . $prefix . "index.php");
		exit();
	} else {
		require_once $prefix . "user/sources/user_function.php";
		
		if (isset($_POST['username']) and isset($_POST['pw']) and isset($_POST['pw_check']) and isset($_POST['citizenname']) and isset($_POST['idlink']) and isset($_POST['languages'])) {
			$register_message = user_register($_POST['username'], $_POST['pw'], $_POST['pw_check'], $_POST['citizenname'], $_POST['idlink'], $_POST['languages']);
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
	if (isset($register_deny)) {
?>
			<div>
				<h4><?php echo $register_deny; ?></h4>
			</div>
<?php
	} else {
		if (isset($register_message)) {
?>
			<div>
				<h4><?php echo $register_message; ?></h4>
			</div>
<?php
		}
?>
			<div>
				<form name="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="RegisterCalcHash()">
					<fieldset>
						<div class="pure-control-group">
							<label for="Username"><?php echo $languages['username']; ?>：</label>
							<input type="text" name="username" maxlength="20" placeholder="Username" pattern="^[a-zA-Z0-9]{6,20}$" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Password"><?php echo $languages['password']; ?>：</label>
							<input type="password" name="pw" id="pw" maxlength="24" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Password"><?php echo $languages['password']; ?>：</label>
							<input type="password" name="pw_check" id="pw_check" maxlength="24" placeholder="Password" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="CitizenName"><?php echo $languages['citizen_id']; ?>：</label>
							<input type="text" name="citizenname" maxlength="20" placeholder="CitizenName" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="IDLink"><?php echo $languages['citizen_link']; ?>：</label>
							<input type="text" name="idlink" maxlength="7" placeholder="Ex:305703 (<?php echo $languages['only_number']; ?>)" autocomplete="off" required>
						</div>
						<div class="pure-control-group">
							<label for="Languages"><?php echo $languages['language']; ?>：</label>
							<select name="languages">
								<option value="0">English</option>
								<option value="1">繁體中文</option>
								<option value="2">简体中文</option>
							</select>
						</div>
						<div>
							<h4>Notice : Before you register, please read the <a href="http://secura.e-sim.org/article.html?id=39662" target="_blank">article</a> carefully.</h4>
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