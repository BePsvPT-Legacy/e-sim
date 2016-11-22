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
				$result = array();
				// get battle type
				if (stripos($data, 'National Tournament') !== false) {
					$result['type'] = 'National Tournament';
				} else if (stripos($data, 'Match') !== false) {
					$result['type'] = 'World War or Team National Cup';
				} else  if (stripos($data, 'Resistance war started by') !== false) {
					$result['type'] = 'Resistance War';
					$temp = stripos($data, get_data($data, 'Resistance war started by ', '">', 74)) + 60;
					$tmp = get_data($data, 'profile.html', '">', 16, $temp);
					$result['started']['id'] = $tmp;
					$tmp = get_data($data, '">', '</a>', 2, $temp);
					$result['started']['name'] = str_replace('<span class="premiumStar">&#9733;</span> ', '', $tmp);
					unset($temp);
					// get location id
					if (($tmp = get_data($data, '<a class="fightFont"', '">', 42)) !== false) {
						$result['location']['id'] = $tmp;
						$str = $tmp;
					}
					// get location name
					if (isset($str) and ($tmp = get_data($data, 'region.html?id='.$str, '</a>', (17 + strlen($str)))) !== false) {
						$result['location']['name'] = $tmp;
						unset($str);
					}
				} else {
					$result['type'] = 'Direct War';
					// get location id
					if (($tmp = get_data($data, '<a class="fightFont"', '">', 42)) !== false) {
						$result['location']['id'] = $tmp;
						$str = $tmp;
					}
					// get location name
					if (isset($str) and ($tmp = get_data($data, 'region.html?id='.$str, '</a>', (17 + strlen($str)))) !== false) {
						$result['location']['name'] = $tmp;
						unset($str);
					}
				}
				// get defender name
				if (($temp = stripos($data, 'alliesList leftList fightFont')) !== false) {
					if (($tmp = get_data($data, '</i>', "\n", 4, $temp))) {
						$result['defender']['name'] = $tmp;
					}
					unset($temp);
				}
				// get attacker name
				if (($tmp = get_data($data, 'alliesList rightList fightFont', '<i', 33)) !== false) {
					$tmp = str_replace('  ', '', $tmp);
					$tmp = str_replace(PHP_EOL, '', $tmp);
					$result['attacker']['name'] = $tmp;
				}
				// get battle round id
				if (($battleRoundId = get_data($data, 'name="battleRoundId" value="', '" />', 28)) !== false) {
					$temp = curl_get("http://secura.e-sim.org/battleScore.html?id=".$battleRoundId."&at=458397&ci=32");
					$temp = json_decode($temp);
					// get defender damage
					$result['defender']['damage'] = str_replace(',', '', $temp->{'defenderScore'});
					// get attacker damage
					$result['attacker']['damage'] = str_replace(',', '', $temp->{'attackerScore'});
					// get defender bar
					$result['defender']['bar'] = 100 - $temp->{'percentAttackers'};
					// get attacker bar
					$result['attacker']['bar'] = $temp->{'percentAttackers'};
					// get defender top damage // BUG!
					if (($tmp = get_data($temp->{'topDefenders'}[0]->{'htmlCode'}, 'profile.html?id=', '">', 16)) !== false) {
						$result['defender']['hero']['1']['id'] = $tmp;
						$result['defender']['hero']['1']['name'] = $temp->{'topDefenders'}[0]->{'playerName'};
						$result['defender']['hero']['1']['damage'] = str_replace(',', '', $temp->{'topDefenders'}[0]->{'influence'});
					}
					if (($tmp = get_data($temp->{'topDefenders'}[1]->{'htmlCode'}, 'profile.html?id=', '">', 16)) !== false) {
						$result['defender']['hero']['2']['id'] = $tmp;
						$result['defender']['hero']['2']['name'] = $temp->{'topDefenders'}[1]->{'playerName'};
						$result['defender']['hero']['2']['damage'] = str_replace(',', '', $temp->{'topDefenders'}[1]->{'influence'});
					}
					if (($tmp = get_data($temp->{'topDefenders'}[2]->{'htmlCode'}, 'profile.html?id=', '">', 16)) !== false) {
						$result['defender']['hero']['3']['id'] = $tmp;
						$result['defender']['hero']['3']['name'] = $temp->{'topDefenders'}[2]->{'playerName'};
						$result['defender']['hero']['3']['damage'] = str_replace(',', '', $temp->{'topDefenders'}[2]->{'influence'});
					}
					// get attacker top damage
					if (($tmp = get_data($temp->{'topAttackers'}[0]->{'htmlCode'}, 'profile.html?id=', '">', 16)) !== false) {
						$result['attacker']['hero']['1']['id'] = $tmp;
						$result['attacker']['hero']['1']['name'] = $temp->{'topAttackers'}[0]->{'playerName'};
						$result['attacker']['hero']['1']['damage'] = str_replace(',', '', $temp->{'topAttackers'}[0]->{'influence'});
					}
					if (($tmp = get_data($temp->{'topAttackers'}[1]->{'htmlCode'}, 'profile.html?id=', '">', 16)) !== false) {
						$result['attacker']['hero']['2']['id'] = $tmp;
						$result['attacker']['hero']['2']['name'] = $temp->{'topAttackers'}[1]->{'playerName'};
						$result['attacker']['hero']['2']['damage'] = str_replace(',', '', $temp->{'topAttackers'}[1]->{'influence'});
					}
					if (($tmp = get_data($temp->{'topAttackers'}[2]->{'htmlCode'}, 'profile.html?id=', '">', 16)) !== false) {
						$result['attacker']['hero']['3']['id'] = $tmp;
						$result['attacker']['hero']['3']['name'] = $temp->{'topAttackers'}[2]->{'playerName'};
						$result['attacker']['hero']['3']['damage'] = str_replace(',', '', $temp->{'topAttackers'}[2]->{'influence'});
					}
				}
				// get defender round win
				if (($tmp = substr_count($data, 'blue_ball.png')) >= 0) {
					$result['defender']['roundwin'] = $tmp;
					if ($tmp == 8) {
						$result['status'] = $result['defender']['name'].' won the battle';
					}
				}
				// get attacker round win
				if (($tmp = substr_count($data, 'red_ball.png')) >= 0) {
					$result['attacker']['roundwin'] = $tmp;
					if ($tmp == 8) {
						$result['status'] = $result['attacker']['name'].' won the battle';
					}
				}
				// get total round
				if (isset($result['defender']['roundwin']) and isset($result['attacker']['roundwin'])) {
					$result['round'] = $result['defender']['roundwin'] + $result['attacker']['roundwin'];
					if (!isset($result['status'])) {
						$result['round']++;
					}
				}
				// get battle status
				if (!isset($result['status'])) {
					if (stripos($data, 'This round was won by') !== false) {
						if (($tmp = get_data($data, 'fightFlag flags-medium', '"', 23)) !== false) {
							$result['status'] = $tmp.' won this round';
						}
					} else {
						$result['status'] = 'In progress';
						$result['remainingtime'] = $temp->{'remainingTimeInSeconds'};
					}
				}
				// get battle round id
				if (isset($battleRoundId)) {
					$result['battleroundid'] = $battleRoundId;
				}
				// get current time
				$result['currenttime'] = date("Y-m-d H:i:s");
			}
		}
	} else {
		$result['error'] = 'Wrong parameter';
	}
	echo json_encode($result);
?>