<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/web_language/web_language_user.php";
	
	$language_navigation = language_translation_user_navigation();
?>
		<header>
			<nav class="pure-menu pure-menu-open pure-menu-horizontal">
				<ul>
					<li><a href="<?php echo $prefix . "index.php"; ?>"><?php echo $language_navigation['home']; ?></a></li>
<?php if (!isset($_SESSION['cid'])) { ?>
					<li><a href="<?php echo $prefix . "user/login.php"; ?>"><?php echo $language_navigation['login']; ?></a></li>
					<li><a href="<?php echo $prefix . "user/register.php"; ?>"><?php echo $language_navigation['register']; ?></a></li>
<?php } else { ?>
					<li><a href="<?php echo $prefix . "user/index.php"; ?>"><?php echo $language_navigation['member_center']; ?></a></li>
					<li><a href="<?php echo $prefix . "user/updateinfo.php"; ?>"><?php echo $language_navigation['change_info']; ?></a></li>
					<li><a href="<?php echo $prefix . "user/logout.php"; ?>"><?php echo $language_navigation['logout']; ?></a></li>
<?php } ?>
				</ul>
			</nav>
		</header>
		<hr>
