<?php
	define("DATABASE_HOST" ,  "127.0.0.1");
	define("DATABASE_USERNAME" ,  "");
	define("DATABASE_PASSWORD" ,  "");
	define("DATABASE_NAME" ,  "esimtw");
	
	$dbconfig["account"] = "account";
	$dbconfig["citizen_name"] = "game_citizen_name";
	$dbconfig["user_data"] = "user_data";
	$dbconfig["news_data"] = "news_data";
	$dbconfig["web_announcement"] = "web_announcement";
	$dbconfig["web_login_log"] = "web_login_log";
	$dbconfig["web_login_remember"] = "web_login_remember";
	$dbconfig["battle_chat"] = "tools_battle_info_chat";
	
	define("DEBUG_MODE", false);
	define("ADMIN_ONLY", false);
	
	define("REGISTER_ALLOW", true);
	define("LOGIN_ALLOW", true);
	define("UPDATEINFO_ALLOW", true);
	define("WAR_INFO_QUERY", true);
	define("WAR_INFO_QUERY_CITIZEN", true);
	
	define("WEB_ERROR_PAGE", "http://crux.coder.tw/freedom/error.php");
?>
