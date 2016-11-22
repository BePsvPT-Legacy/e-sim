<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
?>
		<header>
			<nav>
				<div id="demo-horizontal-menu">
					<ul id="std-menu-items">
						<li><a href="<?php echo $prefix . "index.php"; ?>">首頁</a></li>
<?php
	if (isset($_SESSION['cid'])) {
		if ($_SESSION['country_id'] == 32) {
?>
						<li>
							<a href="#">戰場工具</a>
							<ul>
								<li><a href="<?php echo $prefix . "tools/battle/battle.php"; ?>">即時概覽</a></li>
								<li class="pure-menu-separator"></li>
								<li><a href="<?php echo $prefix . "tools/battle/battle.mu.info.php"; ?>">軍團統計</a></li>
							</ul>
						</li>
<?php
		}
?>
						<li><a href="<?php echo $prefix . "tools/#"; ?>">字典</a></li>
						<li><a href="<?php echo $prefix . "tools/logout.php"; ?>">登出</a></li>
<?php }?>
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
