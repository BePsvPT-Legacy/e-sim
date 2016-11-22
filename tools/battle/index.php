<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	
	if (!WAR_INFO_QUERY) {
		$query_deny = "You do not have the permission to access the page.";
	} 
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "Battle List", $ico_link, $css_link, $js_link);
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
		if (!file_exists($prefix."tools/battle/battle_data/battle_list")) {
			$data = curl_get("http://www.cscpro.org/secura/battles.json");
			if (!($file = fopen($prefix."tools/battle/battle_data/battle_list","w")) or !chmod($prefix."tools/battle/battle_data/battle_list", 0777)) {
				$error_message = "There is something error when loading the data.";
			} else {
				fwrite($file,$time."\n".$data);
				$last_update_time = $time;
			}
		} else {
			if (!($file = fopen($prefix."tools/battle/battle_data/battle_list","r+"))) {
				$error_message = "There is something error when loading the data.";
			} else {
				if ($last_update_time = fgets($file) and strlen($last_update_time) < 12) {
					if ($time - $last_update_time < 600) {
						$data = fgets($file);
					} else {
						$data = curl_get("http://www.cscpro.org/secura/battles.json");
						$last_update_time = $time;
						fseek($file, 0, SEEK_SET);
						ftruncate($file, 0);
						fwrite($file,$time."\n".$data);
					}
				} else {
					$error_message = "There is something error when loading the data.";
				}
			}
		}
		fclose($file);
		
		if (isset($error_message)) {
			echo <<<EOD
				<div class="heading_center heading_highlight" id="error_message">
					<h3>$error_message</h3>
				</div>\n
EOD;
		} else {
?>
				<div>
					<div class="heading_center heading_title">
						<h1>Battle List</h1>
					</div>
					<div>
						<table class="pure-table">
							<thead>
								<tr>
									<th rowspan="2">Battle ID</th>
									<th rowspan="2">Region</th>
									<th colspan="3">Attacker</th>
									<th colspan="3">Defender</th>
									<th rowspan="2">Total Damage</th>
								</tr>
								<tr>
									<th>Name</th>
									<th>Win Round</th>
									<th>Bar</th>
									<th>Bar</th>
									<th>Win Round</th>
									<th>Name</th>
								</tr>
							</thead>
							<tbody>
<?php
			function _cmp($a, $b) {
				if($a->id == $b->id) {
					return 0 ;
				} 
				return ($a->id > $b->id) ? 1 : -1;
			}
			$data = json_decode($data);
			$data = $data->{"battles"};
			usort($data, "_cmp");
			
			$i=0;
			foreach ($data as $data) {
				if ($i % 2 == 1) {
					echo <<<EOD
								<tr class="pure-table-odd">\n
EOD;
				} else {
					echo <<<EOD
								<tr>\n
EOD;
				}
?>
									<td><a href="http://secura.e-sim.org/battle.html?id=<?php echo $data->{"id"}; ?>" target="_blank"><?php echo $data->{"id"}; ?></a></td>
									<td><?php echo $data->{"region"}; ?></td>
									<td><?php echo $data->{'attacker'}->{'name'}; ?></td>
									<td><?php echo $data->{'attacker'}->{'score'}; ?></td>
									<td><?php echo $data->{'attacker'}->{'bar'}.'%'; ?></td>
									<td><?php echo $data->{'defender'}->{'bar'}.'%'; ?></td>
									<td><?php echo $data->{'defender'}->{'score'}; ?></td>
									<td><?php echo $data->{'defender'}->{'name'}; ?></td>
									<td><?php echo number_format($data->{'damage'}); ?></td>
								</tr>
<?php
				$i++;
			}
?>
							</tbody>
						</table>
						<div class="heading_center">
							<h4>Last Update Time : <?php echo date("Y-m-d H:i:s", $last_update_time); ?> (interval : 10 minutes)</h4>
						</div>
					</div>
				</div>
<?php
		}
?>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
<?php
	}
?>
			</div>
<?php display_footer(); ?>
	</body>
</html>