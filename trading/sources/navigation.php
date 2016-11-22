<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix . "config/web_language/web_language_trading.php";
	
	$language_navigation = language_translation_trading_navigation();
?>
		<header>
			<nav>
				<div id="demo-horizontal-menu">
					<ul id="std-menu-items">
						<li><a href="<?php echo $prefix . "index.php"; ?>"><?php echo $language_navigation['home']; ?></a></li>
						<li><a href="<?php echo $prefix . "trading/index.php"; ?>"><?php echo $language_navigation['trading_center']; ?></a></li>
<?php if (!isset($_SESSION['cid'])) { ?>
						<li><a href="<?php echo $prefix . "user/login.php"; ?>"><?php echo $language_navigation['login']; ?></a></li>
						<li><a href="<?php echo $prefix . "user/register.php"; ?>"><?php echo $language_navigation['register']; ?></a></li>
<?php } else { ?>
						<li><a href="<?php echo $prefix . "trading/product.php"; ?>"><?php echo $language_navigation['product']; ?></a></li>
						<li><a href="<?php echo $prefix . "trading/material.php"; ?>"><?php echo $language_navigation['material']; ?></a></li>
						<li>
							<a href="#"><?php echo $language_navigation['others']; ?></a>
							<ul>
								<li><a href="<?php echo $prefix . "trading/work.php"; ?>"><?php echo $language_navigation['work']; ?></a></li>
								<!--<li class="pure-menu-separator"></li>-->
							</ul>
						</li>
						<li>
							<a href="#"><?php echo $language_navigation['release']; ?></a>
							<ul>
								<li><a href="<?php echo $prefix . "trading/release/release.material.php"; ?>"><?php echo $language_navigation['material']; ?></a></li>
								<li class="pure-menu-separator"></li>
								<li><a href="<?php echo $prefix . "trading/release/release.product.php"; ?>"><?php echo $language_navigation['product']; ?></a></li>
								<li class="pure-menu-separator"></li>
								<li><a href="<?php echo $prefix . "trading/release/release.work.php"; ?>"><?php echo $language_navigation['work']; ?></a></li>
								<!--<li class="pure-menu-separator"></li>
								<li><a href="<?php //echo $prefix . "trading/release/release.equip.php"; ?>"><?php //echo $language_navigation['equip']; ?></a></li>-->
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
