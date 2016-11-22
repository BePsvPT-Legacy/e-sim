<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
?>
				<header>
					<nav id="demo-horizontal-menu">
						<ul id="std-menu-items">
							<li><a href="<?php echo $prefix."index.php"; ?>">Home</a></li>
							<li>
								<a href="<?php echo $prefix."tools/battle/index.php"; ?>">Battle</a>
								<ul>
<?php if (isset($_SESSION["cid"])) { ?>
									<li><a href="<?php echo $prefix."tools/battle/battle_info.php"; ?>">Battle Statistics</a></li>
									<li class="pure-menu-separator"></li>
<?php } ?>
									<li><a href="<?php echo $prefix."tools/battle/battle_info_all.php"; ?>">Entire Statistics</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/battle/battle_info_round.php"; ?>">Round Statistics</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/battle/battle_info_citizen.php"; ?>">Citizen Statistics</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/battle/battle_info_mu.php"; ?>">MU Statistics</a></li>
								</ul>
							</li>

							<li>
								<a href="#">Others</a>
								<ul>
									<li><a href="<?php echo $prefix."tools/others/newspaper.php"; ?>">Newspaper</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/others/profitCalc.php"; ?>">利潤計算機</a></li>
<?php if (isset($_SESSION["cid"])) { ?>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/others/market.php"; ?>">市場資訊</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/others/auto.php"; ?>">輔助工具</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."tools/others/intelligence.php"; ?>">情 報 網</a></li>
<?php } ?>
								</ul>
							</li>
<?php if (isset($_SESSION["cid"])) { ?>
							<li>
								<a href="#">帳號</a>
								<ul>
									<li><a href="<?php echo $prefix."user/information.php"; ?>">帳號資訊</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."user/update_info.php"; ?>">密碼更改</a></li>
								</ul>
							</li>
<?php if ($_SESSION["web_admin"] > 0) { ?>
							<li>
								<a href="#">Management</a>
								<ul>
<?php
	if ($_SESSION["web_admin"] > 4) {
?>
									<li><a href="<?php echo $prefix."admin/web_announcement.php"; ?>">Web Announcement</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."admin/game_data_update.php"; ?>">Game Data Update</a></li>
									<li class="pure-menu-separator"></li>
									<li><a href="<?php echo $prefix."admin/account_manage.php"; ?>">Account Manage</a></li>
									<li class="pure-menu-separator"></li>
<?php
	}
	if ($_SESSION["web_admin"] > 3) {
?>
									<li><a href="<?php echo $prefix."admin/account_list.php"; ?>">Account List</a></li>
									<li class="pure-menu-separator"></li>
<?php
	}
	if ($_SESSION["web_admin"] > 2) {
?>
									<li><a href="<?php echo $prefix."admin/citizen_verify.php"; ?>">Citizen Verify</a></li>
									<li class="pure-menu-separator"></li>
<?php
	}
	if ($_SESSION["web_admin"] > 1) {
?>
									<li><a href="<?php echo $prefix."admin/citizen_list.php"; ?>">Citizen List</a></li>
<?php
	}
?>
								</ul>
							</li>
<?php } ?>
							<li><a href="<?php echo $prefix."user/logout.php"; ?>">Logout</a></li>
<?php } else { ?>
							<li><a href="<?php echo $prefix."user/login.php"; ?>">Login</a></li>
							<li><a href="<?php echo $prefix."user/register.php"; ?>">Register</a></li>
<?php } ?>
						</ul>
					</nav>
					<script>
						YUI({classNamePrefix:"pure"}).use("gallery-sm-menu",function(a){var b=new a.Menu({container:"#demo-horizontal-menu",sourceNode:"#std-menu-items",orientation:"horizontal",hideOnOutsideClick:false,hideOnClick:false});b.render();b.show();});
					</script>
				</header>
