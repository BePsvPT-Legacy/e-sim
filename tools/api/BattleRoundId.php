<?php
	require_once 'function.php';
	
	if (isset($_GET['battleid'])) {
		if (!preg_match("/^\d{1,5}$/", $_GET['battleid'])) {
			$result['error'] = 'Wrong battle id';
		} else {
			$data = curl_level5_get("http://secura.e-sim.org/battle.html?id=".$_GET['battleid']);
			if (stripos($data, 'No battle with given id') !== false) {
				$result['error'] = 'Wrong battle id';
			} else {
				if (($tmp = get_data($data, 'name="battleRoundId" value="', '" />', 28)) !== false) {
					$result['battleRoundId'] = $tmp;
				} else {
					$result['error'] = 'There is something wrong when loading the data.';
				}
			}
		}
	} else {
		$result['error'] = 'Wrong parameter';
	}
	echo json_encode($result);
?>