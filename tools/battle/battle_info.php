<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	
	if (!WAR_INFO_QUERY) {
		$query_deny = "You do not have the permission to access the page.";
	} else if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "戰況分析儀", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($query_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$query_deny</h3>
				</div>\n
EOD;
	} else {
?>
				<div<?php echo ($_SESSION['web_group'] >= 10) ? ' class="pure-g"' : ''; ?>>
					<div<?php echo ($_SESSION['web_group'] >= 10) ? ' class="pure-u-1-2"' : ''; ?>>
						<form name="battle_query" id="battle_query" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-aligned" onSubmit="return false;">
							<fieldset>
								<div class="pure-control-group">
									<label for="battleid">Battle ID：</label>
									<input type="text" name="battleid" id="battleid" value="<?php echo $battle_id; ?>" maxlength="5" pattern="^[\d]{1,5}$" autocomplete="off" autofocus required>
								</div>
								<!--<div class="pure-control-group">
									<label for="battle_server">Server：</label>
									<select name="battle_server" id="battle_server" required>
										<option value="1">Primera</option>
										<option value="2" selected>Secura</option>
										<option value="3">Suna</option>
									</select>
								</div>-->
								<div class="pure-controls">
									<button type="submit" class="pure-button pure-button-primary">Submit</button>
								</div>
							</fieldset>
						</form>
					</div>
<?php
		if ($_SESSION['web_group'] >= 10) {
?>
					<div class="pure-u-1-2">
						<form name="battle_score_query" id="battle_score_query" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned" onSubmit="return false;">
							<fieldset>
								<div class="pure-control-group">
									<label for="battlescoreid">Battle Score ID：</label>
									<input type="text" name="battlescoreid" id="battlescoreid" value="<?php echo $battle_id; ?>" maxlength="6" pattern="^[\d]{1,6}$" autocomplete="off">
								</div>
								<div class="pure-control-group">
									<label for="score_server">Server：</label>
									<select name="score_server" id="score_server" required>
										<option value="1">Primera</option>
										<option value="2" selected>Secura</option>
										<option value="3">Suna</option>
									</select>
								</div>
								<div class="pure-controls">
									<button type="submit" class="pure-button pure-button-primary">Submit</button>
								</div>
							</fieldset>
						</form>
					</div>
<?php
		}
?>
				</div>
				<div<?php echo ($_SESSION['web_group'] >= 10) ? ' class="pure-g"' : ''; ?>>
					<div id="battle_info"<?php echo ($_SESSION['web_group'] >= 10) ? ' class="pure-u-1-2"' : ''; ?>>
						<div class="heading_center heading_highlight">
							<h3 id="battle_error_message"></h3>
						</div>
						<div>
							<table class="pure-table pure-table-bordered">
								<thead>
									<th></th>
									<th style="width:96px">防守方</th>
									<th style="width:96px">進攻方</th>
								</thead>
								<tbody>
									<tr>
										<td>戰爭類型</td>
										<td colspan="2" id="type"></td>
									</tr>
									<tr>
										<td>起義玩家</td>
										<td colspan="2"><a href="" target="_blank" id="started"></a></td>
									</tr>
									<tr>
										<td>戰爭地點</td>
										<td colspan="2"><a href="" target="_blank" id="region"></a></td>
									</tr>
									<tr>
										<td>戰場編號</td>
										<td colspan="2"><a href="" target="_blank" id="battle_link"></a></td>
									</tr>
									<tr>
										<td>總回合數</td>
										<td colspan="2" id="round"></td>
									</tr>
									<tr>
										<td>戰爭狀態</td>
										<td colspan="2" id='status'></td>
									</tr>
									<tr>
										<td>剩餘時間</td>
										<td colspan="2" id="remaining_time">
											<div id="remaining_time_h" style="display:inline;"></div> 時
											<div id="remaining_time_m" style="display:inline;"></div> 分
											<div id="remaining_time_s" style="display:inline;"></div> 秒
										</td>
									</tr>
									<tr class="pure-table-odd">
										<td>國　　家</td>
										<td id="def_name"></td>
										<td id="att_name"></td>
									</tr>
									<tr>
										<td>總 傷 害</td>
										<td id="def_damage"></td>
										<td id="att_damage"></td>
									</tr>
									<tr class="pure-table-odd">
										<td>勝 利 方</td>
										<td id="def_win"></td>
										<td id="att_win"></td>
									</tr>
									<tr>
										<td>傷害差距</td>
										<td colspan="2" id="dmg_minus"></td>
									</tr>
									<tr class="pure-table-odd">
										<td>百 分 比</td>
										<td id="def_bar"></td>
										<td id="att_bar"></td>
									</tr>
									<tr>
										<td>獲勝場數</td>
										<td id="def_win_round"></td>
										<td id="att_win_round"></td>
									</tr>
								</tbody>
							</table>
							<div class="heading_center">
								<h4 id="update_time"></h4>
							</div>
							<div id="battle_loading_img" class="heading_center">
							</div>
						</div>
					</div>
<?php
		if ($_SESSION['web_group'] >= 10) {
?>
					<div id="battle_score_info" class="pure-u-1-2">
						<div class="heading_center heading_highlight">
							<h2>要記得，你是被信任的使用者</h2>
						</div>
						<div class="heading_center heading_highlight">
							<h3 id="score_error_message"></h3>
						</div>
						<div>
							<table class="pure-table pure-table-bordered">
								<thead>
									<th></th>
									<th style="width:96px">防守方</th>
									<th style="width:96px">進攻方</th>
								</thead>
								<tbody>
									<tr class="pure-table-odd">
										<td>人　　數</td>
										<td id="def_quantity"></td>
										<td id="att_quantity"></td>
									</tr>
									<tr>
										<td>人數分布</td>
										<td id="def_detail"></td>
										<td id="att_detail"></td>
									</tr>
									<tr>
										<td>觀戰人數</td>
										<td colspan="2" id="total_observe"></td>
									</tr>
									<tr>
										<td>觀戰分布</td>
										<td colspan="2" id="total_detail" style="witdh:256px;"></td>
									</tr>
								</tbody>
							</table>
							<div id="score_loading_img" class="heading_center">
							</div>
						</div>
					</div>
<?php
		}
?>
				</div>
<?php
	}
?>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){var battleid_first_time=1,timer_loading_img=0,timer_time_left=0;function reload_battle(){var content="<img src=\"<?php echo $prefix."images/loading.gif"; ?>\">";if(timer_time_left){clearTimeout(timer_time_left);timer_time_left=0}$("#battle_error_message").empty();$("#battle_loading_img").empty();$("#battle_loading_img").html(content);$.get('http://crux.coder.tw/freedom/tools/api/Battle.php',{battleid:$("#battleid").val()},function(a){if(typeof a.error!='undefined'){$("#battle_error_message").html(a.error)}else{if(battleid_first_time==1){$("#type").html(a.type);if(typeof a.started!='undefined'){$("#started").html(a.started.name);$("#started").attr('href','http://secura.e-sim.org/profile.html?id='+a.started.id)}else{$("#started").empty()}if(typeof a.location!='undefined'){$("#region").html(a.location.name);$("#region").attr('href','http://secura.e-sim.org/region.html?id='+a.location.id)}else{$("#region").empty()}$("#battle_link").html($("#battleid").val());$("#battle_link").attr('href','http://secura.e-sim.org/battle.html?id='+$("#battleid").val());$("#def_name").html(a.defender.name);$("#att_name").html(a.attacker.name);battleid_first_time=0}$("#round").html(a.round);$("#status").html(a.status);$("#def_win_round").html(a.defender.roundwin);$("#att_win_round").html(a.attacker.roundwin);$("#update_time").html('最後更新時間 :'+a.currenttime);if(a.status=='In progress'){var minus=a.defender.damage-a.attacker.damage;a.defender.damage=a.defender.damage.toString();a.attacker.damage=a.attacker.damage.toString();for(var length=a.defender.damage.length-3;length>0;length-=3){a.defender.damage=a.defender.damage.slice(0,length)+','+a.defender.damage.slice(length)}for(var length=a.attacker.damage.length-3;length>0;length-=3){a.attacker.damage=a.attacker.damage.slice(0,length)+','+a.attacker.damage.slice(length)}$("#remaining_time_h").html(Math.floor(a.remainingtime/3600));$("#remaining_time_m").html(Math.floor((a.remainingtime-(Math.floor(a.remainingtime/3600)*3600))/60));$("#remaining_time_s").html(a.remainingtime%60);if(a.remainingtime<=300){$("#remaining_time").attr('class','heading_highlight')}$("#def_damage").html(a.defender.damage);$("#att_damage").html(a.attacker.damage);$("#def_bar").html(a.defender.bar+'%');$("#att_bar").html(a.attacker.bar+'%');if(parseInt(minus)>0){$("#def_win").html('Win');$("#att_win").html('')}else{$("#att_win").html('Win');$("#def_win").html('');minus*=-1}minus=minus.toString();for(var length=minus.length-3;length>0;length-=3){minus=minus.slice(0,length)+','+minus.slice(length)}$("#dmg_minus").html(minus);timing();timer_loading_img=setTimeout(reload_battle,10000)}else{$("#remaining_time_h").empty();$("#remaining_time_m").empty();$("#remaining_time_s").empty();$("#def_damage").empty();$("#att_damage").empty();$("#def_bar").empty();$("#att_bar").empty();$("#att_win").empty();$("#def_win").empty();$("#dmg_minus").empty()}}$("#battle_loading_img").empty()},'json')}$("#battle_query").submit(function(){if(battleid_first_time==0){battleid_first_time=1}if(timer_loading_img){clearTimeout(timer_loading_img);timer_loading_img=0}reload_battle()});function timing(){var time_h=parseInt($('#remaining_time_h').html());var time_m=parseInt($('#remaining_time_m').html());var time_s=parseInt($('#remaining_time_s').html());if(time_s>0){time_s--;$('#remaining_time_s').html(time_s);timer_time_left=setTimeout(timing,1000)}else if(time_m>0){time_m--;$('#remaining_time_m').html(time_m);$('#remaining_time_s').html("59");timer_time_left=setTimeout(timing,1000)}else if(time_h>0){time_h--;$('#remaining_time_h').html(time_h);$('#remaining_time_m').html("59");$('#remaining_time_s').html("59");timer_time_left=setTimeout(timing,1000)}}});
		</script>
<?php
	if ($_SESSION['web_group'] >= 10) {
?>
		<script>
			$(document).ready(function(){var e=0;function reload_score(){var d="<img src=\"<?php echo $prefix."images/loading.gif"; ?>\">";$("#score_error_message").empty();$("#score_loading_img").empty();$("#score_loading_img").html(d);$.post('battle_info_query.php',{battlescoreid:$("#battlescoreid").val(),score_server:$("#score_server").val()},function(a){if(typeof a.error!='undefined'){$("#score_error_message").html(a.error)}else{var b='';$("#def_quantity").html(a.def_online);$("#att_quantity").html(a.att_online);$("#total_observe").html(a.spe_online);for(var c in a.def_countries){if(a.def_countries.hasOwnProperty(c)){b+='<div><div style="display:inline;text-align:right;">'+a.def_countries[c].country+'：</div>';b+='<div style="display:inline;text-align:left;">'+a.def_countries[c].id+'</div></div>'}}$("#def_detail").html(b);b='';for(var c in a.att_countries){if(a.att_countries.hasOwnProperty(c)){b+='<div><div style="display:inline;text-align:right;">'+a.att_countries[c].country+'：</div>';b+='<div style="display:inline;text-align:left;">'+a.att_countries[c].id+'</div></div>'}}$("#att_detail").html(b);b='';for(var c in a.spe_countries){if(a.spe_countries.hasOwnProperty(c)){b+='<div><div style="display:inline;text-align:right;">'+a.spe_countries[c].country+'：</div>';b+='<div style="display:inline;text-align:left;">'+a.spe_countries[c].id+'</div></div>'}}$("#total_detail").html(b);if(a.time>0){e=setTimeout(reload_score,20000)}}$("#score_loading_img").empty()},'json')}$("#battle_score_query").submit(function(){if(e){clearTimeout(e);e=0}reload_score()})});
		</script>
<?php
	}
?>
	</body>
</html>
<?php
	
	// jQuery backup
	/*
	$(document).ready(function() {
		var battleid_first_time = 1, timer_loading_img = 0,timer_time_left = 0;
		
		function reload_battle() {
			var content = "<img src=\"<?php echo $prefix."images/loading.gif"; ?>\">";
			if (timer_time_left) {
				clearTimeout(timer_time_left);
				timer_time_left = 0;
			}
			$("#battle_error_message").empty();
			$("#battle_loading_img").empty();
			$("#battle_loading_img").html(content);
			$.get(
				'http://crux.coder.tw/freedom/tools/api/Battle.php',
				{battleid:$("#battleid").val()},
				function (a) {
					if (typeof a.error != 'undefined') {
						$("#battle_error_message").html(a.error);
					} else {
						if (battleid_first_time == 1) {
							$("#type").html(a.type);
							if (typeof a.started != 'undefined') {
								$("#started").html(a.started.name);
								$("#started").attr('href', 'http://secura.e-sim.org/profile.html?id='+a.started.id);
							} else {
								$("#started").empty();
							}
							if (typeof a.location != 'undefined') {
								$("#region").html(a.location.name);
								$("#region").attr('href', 'http://secura.e-sim.org/region.html?id='+a.location.id);
							} else {
								$("#region").empty();
							}
							$("#battle_link").html($("#battleid").val());
							$("#battle_link").attr('href', 'http://secura.e-sim.org/battle.html?id='+$("#battleid").val());
							$("#def_name").html(a.defender.name);
							$("#att_name").html(a.attacker.name);
							battleid_first_time = 0;
						}
						$("#round").html(a.round);
						$("#status").html(a.status);
						$("#def_win_round").html(a.defender.roundwin);
						$("#att_win_round").html(a.attacker.roundwin);
						$("#update_time").html('最後更新時間 :'+a.currenttime);
						if (a.status == 'In progress') {
							var minus = a.defender.damage - a.attacker.damage;
							a.defender.damage = a.defender.damage.toString();
							a.attacker.damage = a.attacker.damage.toString();
							for (var length=a.defender.damage.length-3;length > 0;length-=3) {
								a.defender.damage = a.defender.damage.slice(0, length) + ',' + a.defender.damage.slice(length);
							}
							for (var length=a.attacker.damage.length-3;length > 0;length-=3) {
								a.attacker.damage = a.attacker.damage.slice(0, length) + ',' + a.attacker.damage.slice(length);
							}
							$("#remaining_time_h").html(Math.floor( a.remainingtime / 3600));
							$("#remaining_time_m").html(Math.floor(a.remainingtime % 3600));
							$("#remaining_time_s").html(a.remainingtime % 60);
							if (a.remainingtime <= 300) {
								$("#remaining_time").attr('class', 'heading_highlight');
							}
							$("#def_damage").html(a.defender.damage);
							$("#att_damage").html(a.attacker.damage);
							$("#def_bar").html(a.defender.bar+'%');
							$("#att_bar").html(a.attacker.bar+'%');
							if (parseInt(minus) > 0) {
								$("#def_win").html('Win');
								$("#att_win").html('');
							} else {
								$("#att_win").html('Win');
								$("#def_win").html('');
								minus *= -1;
							}
							minus = minus.toString();
							for (var length=minus.length-3;length > 0;length-=3) {
								minus = minus.slice(0, length) + ',' + minus.slice(length);
							}
							$("#dmg_minus").html(minus);
							timing();
							timer_loading_img = setTimeout(reload_battle, 10000);
						} else {
							$("#remaining_time_h").empty();
							$("#remaining_time_m").empty();
							$("#remaining_time_s").empty();
							$("#def_damage").empty();
							$("#att_damage").empty();
							$("#def_bar").empty();
							$("#att_bar").empty();
							$("#att_win").empty();
							$("#def_win").empty();
							$("#dmg_minus").empty();
						}
					}
					$("#battle_loading_img").empty();
				}, 'json'
			);
		}
		
		$("#battle_query").submit(function(){
			if (battleid_first_time == 0) {
				battleid_first_time = 1;
			}
			if (timer_loading_img) {
				clearTimeout(timer_loading_img);
				timer_loading_img = 0;
			}
			reload_battle();
		});
		
		function timing() {
			var time_h = parseInt($('#remaining_time_h').html());
			var time_m = parseInt($('#remaining_time_m').html());
			var time_s = parseInt($('#remaining_time_s').html());
			
			if (time_s > 0) {
				time_s--;
				$('#remaining_time_s').html(time_s);
				timer_time_left = setTimeout(timing, 1000);
			} else if (time_m > 0) {
				time_m--;
				$('#remaining_time_m').html(time_m);
				$('#remaining_time_s').html("59");
				timer_time_left = setTimeout(timing, 1000);
			} else if (time_h > 0) {
				time_h--;
				$('#remaining_time_h').html(time_h);
				$('#remaining_time_m').html("59");
				$('#remaining_time_s').html("59");
				timer_time_left = setTimeout(timing, 1000);
			}
		}
	});
	*/
	
	/* Warning !!!
	$(document).ready(function() {
		var timer_loading_img = 0;
		
		function reload_score() {
			var content = "<img src=\"<?php echo $prefix."images/loading.gif"; ?>\">";
			$("#score_error_message").empty();
			$("#score_loading_img").empty();
			$("#score_loading_img").html(content);
			$.post(
				'battle_info_query.php',
				{battlescoreid:$("#battlescoreid").val(),score_server:$("#score_server").val()},
				function (a) {
					if (typeof a.error != 'undefined') {
						$("#score_error_message").html(a.error);
					} else {
						var c_data = '';
						$("#def_quantity").html(a.def_online);
						$("#att_quantity").html(a.att_online);
						$("#total_observe").html(a.spe_online);
						for (var key in a.def_countries) {
							if (a.def_countries.hasOwnProperty(key)) {
								c_data += '<div><div style="display:inline;text-align:right;">'+a.def_countries[key].country+'：</div>';
								c_data += '<div style="display:inline;text-align:left;">'+a.def_countries[key].id+'</div></div>';
							}
						}
						$("#def_detail").html(c_data);
						c_data = '';
						for (var key in a.att_countries) {
							if (a.att_countries.hasOwnProperty(key)) {
								c_data += '<div><div style="display:inline;text-align:right;">'+a.att_countries[key].country+'：</div>';
								c_data += '<div style="display:inline;text-align:left;">'+a.att_countries[key].id+'</div></div>';
							}
						}
						$("#att_detail").html(c_data);
						c_data = '';
						for (var key in a.spe_countries) {
							if (a.spe_countries.hasOwnProperty(key)) {
								c_data += '<div><div style="display:inline;text-align:right;">'+a.spe_countries[key].country+'：</div>';
								c_data += '<div style="display:inline;text-align:left;">'+a.spe_countries[key].id+'</div></div>';
							}
						}
						$("#total_detail").html(c_data);
						if (a.time > 0) {
							timer_loading_img = setTimeout(reload_score, 20000);
						}
					}
					$("#score_loading_img").empty();
				}, 'json'
			);
		}
		
		$("#battle_score_query").submit(function(){
			if (timer_loading_img) {
				clearTimeout(timer_loading_img);
				timer_loading_img = 0;
			}
			reload_score();
		});
	});
	*/
?>