<?php
	if (!isset($prefix)) {
		$prefix = "../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	
	$sql = "SELECT `id` FROM `".$dbconfig["news_data"]."` ORDER BY `id` DESC LIMIT 1";
	if (!($stmt = $mysqli->prepare($sql))) {
		handle_database_error($mysqli->error);
		exit();
	} else {
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($begin_id);
		$stmt->fetch();
		$stmt->close();
		
		$begin_id++;
		$end_id = $begin_id + 1000;
		
		if ($argc == 2 and $argv[1] == "level5") {
			$data = "INSERT INTO `news_data` (`id`, `news_title`, `news_group`, `news_author`, `enable`) VALUES";
			$error = false;
			$double_error = false;
			
			for ($i=$begin_id;$i<=$end_id;$i++) {
				
				if ($i % 2 == 0) {
					sleep(3);
				}
				
				$result = curl_get("http://secura.e-sim.org/article.html?id=".$i);
				if (stripos($result, 'No such article') === false) {
					$result = stristr($result, 'bigArticleTab');
					if ($result === false) {
						$error = true;
					} else {
						$result = str_replace('<span class="premiumStar">&#9733;</span> ', "", $result);
						$begin = stripos($result, 'articleTitle')+14;
						$end = stripos($result, '</a>', $begin);
						$title = substr($result, $begin, $end-$begin);
						$pos = strripos($title, ' (');
						$news_title = substr($title, 0, $pos);
						$news_group = substr($title, $pos+2, -1);
						$begin_2 = stripos($result, 'href', $end);
						$begin_2 = stripos($result, '>', $begin_2)+1;
						$end = stripos($result, '</a>', $begin_2);
						$news_auth = substr($result, $begin_2, $end-$begin_2);
						
						$news_title = addslashes(htmlspecialchars($news_title, ENT_QUOTES));
						$news_group = addslashes(htmlspecialchars($news_group, ENT_QUOTES));
						$news_auth = addslashes(htmlspecialchars($news_auth, ENT_QUOTES));
						
						$data .= ' (NULL, \''.$news_title.'\', \''.$news_group.'\', \''.$news_auth.'\', \'1\'),';
					}
				} else {
					$tmp = curl_get("http://secura.e-sim.org/article.html?id=".($i+1));
					if (stripos($tmp, 'bigArticleTab') === false) {
						$tmp = curl_get("http://secura.e-sim.org/article.html?id=".($i+2));
						if (stripos($tmp, 'bigArticleTab') === false) {
							$error = true;
						} else {
							$data .= " (NULL, 'No Such Article', 'Unknown Group', 'Unknown Author', '0'),";
						}
					} else {
						$data .= " (NULL, 'No Such Article', 'Unknown Group', 'Unknown Author', '0'),";
					}
				}
				
				if ($i % 100 == 0 or $i == $end_id or $error) {
					if (strlen($data) > 90) {
						$data[strlen($data)-1] = ";";
						if (!($stmt = $mysqli->prepare($data))) {
							handle_database_error($mysqli->error);
							exit();
						} else {
							$stmt->execute();
							$stmt->close();
							$data = "INSERT INTO `news_data` (`id`, `news_title`, `news_group`, `news_author`, `enable`) VALUES";
						}
					}
					if ($error) {
						break;
					}
				}
			}
		}
	}
?>