<?php
	require_once 'function.php';
	
	if (isset($_GET['companyid']) and isset($_GET['server'])) {
		if (!preg_match("/^\d{1,5}$/", $_GET['companyid'])) {
			$result['error'] = 'Wrong company id';
		} else if (!preg_match("/^[1-3]{1}$/", $_GET['server'])) {
			$result['error'] = 'Wrong server';
		} else {
			$server = ($_GET['server'] == 2) ? 'secura' : (($_GET['server'] == 1) ? 'primera' : 'suna');
			$data = curl_get("http://".$server.".e-sim.org/company.html?id=".$_GET['companyid']);
			if (stripos($data, 'No such company') !== false) {
				$result['error'] = 'Wrong company id';
			} else {
				$data = str_replace('<span class="premiumStar">&#9733;</span> ', '', $data);
				$data = strstr($data, '<h2><span></span>');
				if (($tmp = get_data($data, '</span>Company: ', '</h2>', 16)) !== false) {
					$result['name'] = $tmp;
				}
				$data = strstr($data, 'region.html');
				if (($tmp = get_data($data, 'region.html?id=', '">', 15)) !== false) {
					$result['region']['id'] = $tmp;
				}
				if (($tmp = get_data($data, '">', '</a>', 2)) !== false) {
					$result['region']['name'] = $tmp;
				}
				$data = strstr($data, 'Total employees');
				$data = strstr($data, '<td>');
				if (($tmp = get_data($data, '<b>', '</b>', 3)) !== false) {
					$result['totalstaff'] = $tmp;
				}
				if (stripos($data, 'Resource') !== false) {
					$data = strstr($data, '<img src="');
					if (($tmp = get_data($data, 'productIcons/', '.png"', 13)) !== false) {
						$result['resource'] = $tmp;
					}
					$data = strstr($data, 'title="" />');
				}
				$data = strstr($data, '<img src="');
				if (($tmp = get_data($data, 'productIcons/', '.png"', 13)) !== false) {
					$result['product']['name'] = $tmp;
				}
				$data = strstr($data, 'title="" />');
				if (($tmp = get_data($data, 'productIcons/', '.png"', 13)) !== false) {
					$result['product']['quality'] = $tmp;
				}
				if ($result['totalstaff'] > 0) {
					$data = strstr($data, 'Workers:');
					$data = strstr($data, '<table');
					$data = strstr($data, '</table>', true);
					$i = 0;
					while (stripos($data, 'smallAvatar') !== false) {
						$data = strstr($data, 'profile.html');
						if (($tmp = get_data($data, 'profile.html?id=', '">', 16)) !== false) {
							$result['workers'][$i]['id'] = $tmp;
						}
						if (($tmp = get_data($data, '">', '</a>', 3)) !== false) {
							$result['workers'][$i]['name'] = $tmp;
						}
						if (($tmp = get_data($data, '<B>', '</B>', 3)) !== false) {
							$result['workers'][$i]['skilllevel'] = $tmp;
						}
						$data = strstr($data, 'salary');
						if (($tmp = get_data($data, '<b>', '</b>', 3)) !== false) {
							$result['workers'][$i]['salary'] = $tmp;
						}
						$i++;
					}
				}
			}
		}
	} else {
		$result['error'] = 'Wrong parameter';
	}
	echo json_encode($result);
?>