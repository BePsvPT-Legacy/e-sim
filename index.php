<?php
	if (!isset($prefix)) {
		$prefix = "./";
	}
	require_once $prefix . "config/visitor_check.php";
	require_once $prefix . "config/web_language/web_language_home.php";
	
	if (isset($_POST['languages'])) {
		if (preg_match("/^[0-2]{1}$/", $_POST['languages'])) {
			$language_name = array("eng", "cht", "chs");
			$_SESSION['language'] = $language_name[$_POST['languages']];
		}
	}
	$languages = language_translation_home_index();
	
	$ico_link = "";
	$body = "";
	$css_link = array($prefix . "scripts/css/pure-min.css");
	$js_link = array();
	display_head("Secura e-sim", $ico_link, $body, $css_link, $js_link);
	
	require_once $prefix . "main/sources/navigation.php";
?>
		<div class="page-wrap">
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
	display_footer();
?>