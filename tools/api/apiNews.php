<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_only.php";
	
	$article_type = array(0 => 'ALL', 1 => 'Political',2 => 'Military',3 => 'Statistics',4 => 'Event related',5 => 'Fun',6 => 'Economical',7 => 'Trade', 8 => 'Old article');
	if ($_GET["news_title"] != "" and isset($_GET["news_type"])) {
		if (!preg_match("/^(\+| )+$/", $_GET["news_title"])) {
			$search_array = array(' ', '+');
			$replace_array = array('%', '%');
			$_GET["news_title"] = str_replace($search_array, $replace_array, htmlspecialchars($_GET["news_title"], ENT_QUOTES));
			$sql = "SELECT `id`, `news_title`, `news_group`, `news_author` FROM `".$dbconfig["news_data"]."` WHERE `news_title` LIKE ?";
			if ($_GET["news_author"] != "") {
				$sql .= " AND `news_author` LIKE ?";
			}
			if ($_GET["news_type"] > 0) {
				$sql .= " AND `news_group` = ?";
			}
			$sql .= " AND `enable` = ? ORDER BY `id` DESC LIMIT 3";
			
			if (!($stmt = $mysqli->prepare($sql))) {
				handle_database_error($mysqli->error);
				exit();
			} else {
				if ($_GET["news_author"] != "" and $_GET["news_type"] > 0) {
					$stmt->bind_param('sssi', $news_title, $news_author, $news_type, $enable);
				} else if ($_GET["news_author"] != "" and $_GET["news_type"] == 0) {
					$stmt->bind_param('ssi', $news_title, $news_author, $enable);
				} else if ($_GET["news_author"] == "" and $_GET["news_type"] > 0) {
					$stmt->bind_param('ssi', $news_title, $news_type, $enable);
				} else {
					$stmt->bind_param('si', $news_title, $enable);
				}
				$news_title = '%'.$_GET["news_title"].'%';
				$news_author = '%'.$_GET["news_author"].'%';
				$news_type = $article_type[$_GET["news_type"]];
				$enable = true;
				$stmt->execute();
				$stmt->bind_result($id, $result_title, $result_type, $result_author);
				$news_data = array();
				$i = 0;
				while ($stmt->fetch()) {
					$news_data[$i] = array(
						"number" => $i+1,
						"id" => $id,
						"title" => $result_title,
						"author" => $result_author,
						"type" => $result_type
					);
					$i++;
				}
				$stmt->close();
				
				$news_data_json = json_encode($news_data);
			}
		} else {
			$error = array(
				"error" => "wrong title or type"
			);
			
			$news_data_json = json_encode($error);
		}
	} else {
		$error = array(
			"error" => "wrong title or type"
		);
		
		$news_data_json = json_encode($error);
	}
	echo $news_data_json;
?>