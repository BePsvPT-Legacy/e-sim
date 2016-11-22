<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_only.php";
	
	for ($i = 1126278; $i < 1150000; $i++) {
		if ($i % 2 == 0) {
			sleep(3);
		}
		$data = curl_get('http://secura.e-sim.org/auction.html?id='.$i);
		
		if (stripos($data, 'Finished') !== false) {
			$data = strstr($data, '<td>Bid</td>');
			$data = str_replace('<span class="premiumStar">&#9733;</span> ', '', $data);
			$nobid = false;
			if (stripos($data, 'No bids') !== false) {
				$nobid = true;
			}
			
			if (($tmp = get_data($data, '?id=', '">', 4)) !== false) {
				$seller_id = $tmp;
			}
			if (($tmp = get_data($data, '">', '</a>', 2)) !== false) {
				$seller_name = $tmp;
			}
			$data = strstr($data, $seller_name);
			$data = strstr($data, '</td>');
			if (!$nobid) {
				if (($tmp = get_data($data, '?id=', '">', 4)) !== false) {
					$bidder_id = $tmp;
				}
				if (($tmp = get_data($data, '">', '</a>', 2)) !== false) {
					$bidder_name = $tmp;
				}
				$data = strstr($data, $bidder_name);
			} else {
				$bidder_id = 'null';
				$bidder_name = 'null';
			}
			if (($tmp = stripos($data, 'specialItems')) !== false) {
				$type = 'specialItems';
			} else if (($tmp = stripos($data, 'company.html')) !== false) {
				$type = 'company';
			} else {
				$type = 'equipment';
			}
			switch ($type) {
				case 'specialItems':
					$data = strstr($data, '"/></div>');
					$item_id = $item_quality = 'null';
					if (($tmp = get_data($data, "</div>", "</td>", 6)) !== false) {
						$tmp = str_replace(array("\t", "\n", "\r"), '', $tmp);
						$item_slot = substr($tmp, 0, strlen($tmp)-1);
					}
					$item_property_1 = $item_property_1_quality = 'null';
					$item_property_2 = $item_property_2_quality = 'null';
					$data = strstr($data, 'class');
					break;
				case 'company':
					if (($tmp = get_data($data, 'company.html?id=', '" target="_blank"', 16)) !== false) {
						$item_id = $tmp;
					}
					$data = strstr($data, '_blank');
					if (($tmp = get_data($data, '">', '</b>', 2)) !== false) {
						$temp = explode(' ', $tmp);
						$item_quality = $temp[0];
						$tmp = array_splice($temp, 1);
						$item_slot = implode(' ', $tmp);
					}
					$data = strstr($data, '</div>');
					if (($tmp = get_data($data, 'region.html?id=', '" target', 15)) !== false) {
						$item_property_1_quality = $tmp;
					}
					$data = strstr($data, '_blank');
					if (($tmp = get_data($data, '">', '</a>', 2)) !== false) {
						$item_property_1 = $tmp;
					}
					$item_property_2 = $item_property_2_quality = 'null';
					break;
				case 'equipment':
					$data = strstr($data, '"/>');
					if (($tmp = get_data($data, '(#', ')', 2)) !== false) {
						$item_id = $tmp;
					}
					$data = strstr($data, $item_id);
					$data = strstr($data, $item_id);
					if (($tmp = get_data($data, '<b>', '</b>', 3)) !== false) {
						$temp = explode(' ', $tmp);
						$item_quality = $temp[0];
						$tmp = array_splice($temp, 1);
						$item_slot = implode(' ', $tmp);
					}
					$data = strstr($data, '*');
					if (($tmp = get_data($data, ' ', '<br/>', 1)) !== false) {
						$temp = explode(' by ', $tmp);
						$item_property_1 = $temp[0];
						$item_property_1_quality = $temp[1]; 
					}
					$data = strstr($data, '<br/>*');
					if (($tmp = get_data($data, ' ', "\n", 1)) !== false) {
						$temp = explode(' by ', $tmp);
						$item_property_2 = $temp[0];
						$item_property_2_quality = $temp[1]; 
					}
					break;
				default :
					break;
			}
			$data = strstr($data, '</div>');
			if (($tmp = get_data($data, '<b>', '</b>', 3)) !== false) {
				$item_price = $tmp;
			}
			$data = strstr($data, '<td>');
			if (($tmp = get_data($data, '<b>', '</b>', 3)) !== false) {
				$bidders = $tmp;
			}
			$data = strstr($data, '<td> <b>');
			if (!$nobid) {
				if (($temp = get_data($data, '<b>', '</b>', 3)) !== false) {
					$tmp = explode(' ', $temp);
					switch ($tmp[1]) {
						case 'Jan': $m = 1; break;
						case 'Feb': $m = 2; break;
						case 'Mar': $m = 3; break;
						case 'Apr': $m = 4; break;
						case 'May': $m = 5; break;
						case 'Jun': $m = 6; break;
						case 'Jul': $m = 7; break;
						case 'Aug': $m = 8; break;
						case 'Sep': $m = 9; break;
						case 'Oct': $m = 10; break;
						case 'Nov': $m = 11; break;
						case 'Dec': $m = 12; break;
					}
					$times = mktime(substr($tmp[3], 0, 2), substr($tmp[3], 3, 2), substr($tmp[3], 6, 2), $m, $tmp[2], $tmp[5]) + 21600;
				}
			} else {
				$times = 'null';
			}
			
			$sql = "INSERT INTO `auction_data` (`action_id`, `type`, `seller_id`, `seller_name`, `bidder_id`, `bidder_name`, `item_id`, `item_quality`, `item_slot`, `item_property_1`, `item_property_1_quality`, `item_property_2`, `item_property_2_quality`, `item_price`, `bidders`, `time`) VALUES ";
			$sql .= "('".$i."', '".$type."', '".$seller_id."', '".$seller_name."', '".$bidder_id."', '".$bidder_name."', '".$item_id."', '".$item_quality."', '".$item_slot."', '".$item_property_1."', '".$item_property_1_quality."', '".$item_property_2."', '".$item_property_2_quality."', '".$item_price."', '".$bidders."', '".$times."')";
			if (!($stmt = $mysqli->prepare($sql))) {
				echo $mysqli->error;
				exit();
			} else {
				$stmt->execute();
				$stmt->close();
			}
		} else {
			echo $i;
			break;
		}
	}
	
	function curl_get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "Google Bot");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function get_data($data, $start, $end, $fix, $prefix) {
		if (isset($prefix)) {
			$pos_start = stripos($data, $start, $prefix);
		} else {
			$pos_start = stripos($data, $start);
		}
		$pos_end = stripos($data, $end, $pos_start);
		if ($pos_start !== false and $pos_end !== false) {
			$pos_start += $fix;
			return substr($data, $pos_start, $pos_end - $pos_start);
		} else {
			return false;
		}
	}
	
	function eol_replace($data) {
		return str_replace(array("\r", "\n"), '', $data);
	}
?>