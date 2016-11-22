<?php
	// home
	function language_translation_home_navigation() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'home' => '首頁',
					'trading' => '交易市場',
					'dictionary' => '字典',
					'login' => '登入',
					'register' => '註冊',
					'member' => '個人中心',
					'admin' => '網站管理',
					'logout' => '登出'
				);
			case "chs":
				return array(
					'home' => '首页',
					'trading' => '交易市场',
					'dictionary' => '字典',
					'login' => '登入',
					'register' => '注册',
					'member' => '个人中心',
					'admin' => '网站管理',
					'logout' => '注销'
				);
			case "eng":
			default :
				return array(
					'home' => 'Home',
					'trading' => 'Trading',
					'dictionary' => 'Dictionary',
					'login' => 'Login',
					'register' => 'Register',
					'member' => 'Member',
					'admin' => 'Website Administer',
					'logout' => 'Logout'
				);
		}
	}
	
	function language_translation_home_index() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'language' => '語言',
					'submit' => '更改語言'
				);
			case "chs":
				return array(
					'language' => '语言',
					'submit' => '更改语言'
				);
			case "eng":
			default :
				return array(
					'language' => 'Language',
					'submit' => 'Change'
				);
		}
	}
?>