<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/web_language/web_language_home.php";
	
	$language_navigation = language_translation_home_navigation();
?>
		<header>
			<nav class="pure-menu pure-menu-open pure-menu-horizontal">
				<ul>
					<li><a href="<?php echo $prefix . "index.php"; ?>"><?php echo $language_navigation['home']; ?></a></li>
					<li><a href="<?php echo $prefix . "trading/index.php"; ?>"><?php echo $language_navigation['trading']; ?></a>
<?php if (!isset($_SESSION['cid'])) { ?>
					<li><a href="<?php echo $prefix . "user/login.php"; ?>"><?php echo $language_navigation['login']; ?></a></li>
					<li><a href="<?php echo $prefix . "user/register.php"; ?>"><?php echo $language_navigation['register']; ?></a></li>
<?php } else { ?>
					<li><a href="<?php echo $prefix . "user/index.php"; ?>"><?php echo $language_navigation['member']; ?></a>
<?php if ($_SESSION['web_admin'] != 0 or $_SESSION['country_id'] == 32) { ?>
					<li><a href="<?php echo $prefix . "tools/index.php"; ?>">小工具</a>
<?php } ?>
<?php if ($_SESSION['web_admin'] > 4) { ?>
					<li><a href="<?php echo $prefix . "admin/index.php"; ?>"><?php echo $language_navigation['admin']; ?></a></li>
<?php } ?>
					<li><a href="<?php echo $prefix . "user/logout.php"; ?>"><?php echo $language_navigation['logout']; ?></a></li>
<?php } ?>
				</ul>
			</nav>
		</header>
		<hr>
