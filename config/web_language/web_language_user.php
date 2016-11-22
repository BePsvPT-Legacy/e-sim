<?php
	// user
	function language_translation_user_navigation() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'home' => '主選單',
					'login' => '登入',
					'register' => '註冊',
					'member_center' => '會員中心',
					'change_info' => '更改資料',
					'logout' => '登出'
				);
			case "chs":
				return array(
					'home' => '主选单',
					'login' => '登入',
					'register' => '注册',
					'member_center' => '会员中心',
					'change_info' => '更改数据',
					'logout' => '注销'
				);
			case "eng":
			default :
				return array(
					'home' => 'Home',
					'login' => 'Login',
					'register' => 'Register',
					'member_center' => 'Information',
					'change_info' => 'Change Information',
					'logout' => 'Logout'
				);
		}
	}
	
	function language_translation_user_index() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '會員中心',
					'account_info' => '帳號資料',
					'username' => '帳號',
					'citizen_id' => '遊戲 ID',
					'citizen_link' => '個人連結',
					'login_log' => '帳號登入紀錄',
					'login_ip' => 'IP來源',
					'login_time' => '登入時間'
				);
			case "chs":
				return array(
					'title' => '会员中心',
					'account_info' => '账号数据',
					'username' => '账号',
					'citizen_id' => '游戏 ID',
					'citizen_link' => '个人连结',
					'login_log' => '账号登入纪录',
					'login_ip' => 'IP来源',
					'login_time' => '登入时间'
				);
			case "eng":
			default :
				return array(
					'title' => 'Information',
					'account_info' => 'Account Information',
					'username' => 'Username',
					'citizen_id' => 'Citizen ID',
					'citizen_link' => 'Citizen Link',
					'login_log' => 'Login Log',
					'login_ip' => 'Login IP',
					'login_time' => 'Login Time'
				);
		}
	}
	
	function language_translation_user_updateinfo() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '資料更改',
					'update_deny' => '很抱歉，目前修改資料頁面關閉中',
					'username' => '會員帳號',
					'change_pw' => '密碼修改',
					'change_pw_old' => '舊密碼',
					'change_pw_new' => '新密碼',
					'change_pw_new_check' => '新密碼確認',
					'submit' => '更改',
					'reset' => '清除',
					'change_language' => '語言修改',
					'language' => '語言'
				);
			case "chs":
				return array(
					'title' => '数据更改',
					'update_deny' => '很抱歉，目前修改资料页面关闭中',
					'username' => '会员账号',
					'change_pw' => '密码修改',
					'change_pw_old' => '旧密码',
					'change_pw_new' => '新密码',
					'change_pw_new_check' => '新密码确认',
					'submit' => '更改',
					'reset' => '清除',
					'change_language' => '语言修改',
					'language' => '语言'
				);
			case "eng":
			default :
				return array(
					'title' => 'Change Information',
					'update_deny' => 'The page does not allow to visit.',
					'username' => 'Username',
					'change_pw' => 'Change Password',
					'change_pw_old' => 'Old Password',
					'change_pw_new' => 'New Password',
					'change_pw_new_check' => 'New Password Check',
					'submit' => 'Change',
					'reset' => 'Reset',
					'change_language' => 'Change Language',
					'language' => 'language'
				);
		}
	}
	
	function language_translation_user_login() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '登入',
					'login_deny' => '很抱歉，目前登入頁面關閉中',
					'username' => '帳號',
					'password' => '密碼',
					'submit' => '登入',
					'reset' => '清除'
				);
			case "chs":
				return array(
					'title' => '登入',
					'login_deny' => '很抱歉，目前登入页面关闭中',
					'username' => '账号',
					'password' => '密码',
					'submit' => '登入',
					'reset' => '清除'
				);
			case "eng":
			default :
				return array(
					'title' => 'Login',
					'login_deny' => 'The page does not allow to visit.',
					'username' => 'Username',
					'password' => 'Password',
					'submit' => 'Login',
					'reset' => 'Reset'
				);
		}
	}
	
	function language_translation_user_register() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '註冊',
					'register_deny' => '很抱歉，目前註冊頁面關閉中',
					'username' => '帳　　號',
					'password' => '密　　碼',
					'citizen_id' => '遊戲　ID',
					'citizen_link' => '個人連結',
					'language' => '語言',
					'only_number' => '只需數字部分',
					'submit' => '註冊'
				);
			case "chs":
				return array(
					'title' => '注册',
					'register_deny' => '很抱歉，目前注册页面关闭中',
					'username' => '帐　　号',
					'password' => '密　　码',
					'citizen_id' => '游戏　ID',
					'citizen_link' => '个人连结',
					'language' => '语言',
					'only_number' => '只需数字部分',
					'submit' => '注册'
				);
			case "eng":
			default :
				return array(
					'title' => 'Register',
					'register_deny' => 'The page does not allow to visit.',
					'username' => 'Username',
					'password' => 'Password',
					'citizen_id' => 'Citizen ID',
					'citizen_link' => 'Citizen Link',
					'language' => 'Language',
					'only_number' => 'only number',
					'submit' => 'Register'
				);
		}
	}
?>