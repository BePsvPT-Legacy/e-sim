<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/web_language/web_language_admin.php";
	
	$language_navigation = language_translation_admin_navigation();
?>
		<header>
			<nav>
				<div id="demo-horizontal-menu">
					<ul id="std-menu-items">
						<li><a href="<?php echo $prefix . "index.php"; ?>"><?php echo $language_navigation['home']; ?></a></li>
<?php if (isset($_SESSION['cid']) and $_SESSION['web_admin'] > 0) { ?>
						<li>
							<a href="#"><?php echo $language_navigation['account']; ?></a>
							<ul>
								<li><a href="<?php echo $prefix . "admin/account/account.list.php"; ?>"><?php echo $language_navigation['account_list']; ?></a></li>
								<li class="pure-menu-separator"></li>
								<li><a href="<?php echo $prefix . "admin/account/account.verify.php"; ?>"><?php echo $language_navigation['account_verify']; ?></a></li>
								<li class="pure-menu-separator"></li>
								<li><a href="<?php echo $prefix . "admin/account/account.ban.php"; ?>"><?php echo $language_navigation['account_ban']; ?></a></li>
							</ul>
						</li>
						<li><a href="<?php echo $prefix . "user/logout.php"; ?>"><?php echo $language_navigation['logout']; ?></a></li>
<?php } ?>
					</ul>
				</div>
			</nav>
		</header>
		<script>
			YUI({
				classNamePrefix: 'pure'
			}).use('gallery-sm-menu', function (Y) {

				var horizontalMenu = new Y.Menu({
					container         : '#demo-horizontal-menu',
					sourceNode        : '#std-menu-items',
					orientation       : 'horizontal',
					hideOnOutsideClick: false,
					hideOnClick       : false
				});

				horizontalMenu.render();
				horizontalMenu.show();

			});
		</script>
		<hr>
