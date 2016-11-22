<?php
	// admin
	function language_translation_admin_navigation() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'home' => '首頁',
					'account' => '帳號管理',
					'account_list' => '帳號列表',
					'account_verify' => '審核帳號',
					'account_ban' => '封鎖帳號',
					'logout' => '登出'
				);
			case "eng":
			default :
				return array(
					'home' => 'Home',
					'account' => 'Account',
					'account_list' => 'List',
					'account_verify' => 'Verify',
					'account_ban' => 'Ban',
					'logout' => 'Logout'
				);
		}
	}
	
	function language_translation_admin_account_verify() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '帳號審核',
					'heading' => '帳號審核',
					'account_id' => '編號',
					'account_username' => '帳號',
					'account_nickname' => '遊戲 ID',
					'account_idlink' => '連結',
					'account_countryid' => '國家',
					'manage' => '通過',
					'check_permit' => '確定要通過?'
				);
			case "eng":
			default :
				return array(
					'title' => 'Account Verify',
					'heading' => 'Account Verify',
					'account_id' => 'Serial Number',
					'account_username' => 'Username',
					'account_nickname' => 'Citizen ID',
					'account_idlink' => 'Citizen Link',
					'account_countryid' => 'Country',
					'manage' => 'Permit',
					'check_permit' => 'Are you sure?'
				);
		}
	}
?>