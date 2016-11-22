<?php
	// trading
	function language_translation_trading_navigation() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'home' => '主選單',
					'trading_center' => '貿易中心',
					'others' => '其他',
					'work' => '工作',
					'equip' => '裝備',
					'material' => '原料',
					'product' => '產品',
					'login' => '登入',
					'register' => '註冊',
					'release' => '發佈',
					'logout' => '登出'
				);
			case "chs":
				return array(
					'home' => '主选单',
					'trading_center' => '贸易中心',
					'others' => '其他',
					'work' => '工作',
					'equip' => '装备',
					'material' => '原料',
					'product' => '产品',
					'login' => '登入',
					'register' => '注册',
					'release' => '发布',
					'logout' => '注销'
				);
			case "eng":
			default :
				return array(
					'home' => 'Home',
					'trading_center' => 'Trading Center',
					'others' => 'Others',
					'work' => 'Work',
					'equip' => 'Equip',
					'material' => 'Material',
					'product' => 'Product',
					'login' => 'Login',
					'register' => 'Register',
					'release' => 'Release',
					'logout' => 'Logout'
				);
		}
	}
	
	function language_translation_trading_index() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '貿易中心',
					'heading' => '貿易中心概況',
					'not_login' => '請先登入',
					'activity_account' => '活躍帳戶',
					'product_quantity' => '產品交易',
					'material_quantity' => '原料交易',
					'work_quantity' => '原料交易'
				);
			case "chs":
				return array(
					'title' => '贸易中心',
					'heading' => '贸易中心概况',
					'not_login' => '请先登入',
					'activity_account' => '活跃账户',
					'product_quantity' => '产品交易',
					'material_quantity' => '原料交易',
					'work_quantity' => '原料交易'
				);
			case "eng":
			default :
				return array(
					'title' => 'Trading Market',
					'heading' => 'Trading Market Status',
					'not_login' => 'Please Login First',
					'activity_account' => 'Activity Accounts',
					'product_quantity' => 'Product',
					'material_quantity' => 'Material',
					'work_quantity' => 'Work'
				);
		}
	}
	
	function language_translation_trading_product() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '產品',
					'all' => '全部',
					'mode_sell' => '賣出',
					'mode_buy' => '買進',
					'species_weapon' => '武器',
					'species_food' => '食物',
					'species_gift' => '禮物',
					'species_ticket' => '機票',
					'species_ds' => '防禦',
					'species_house' => '房屋',
					'species_estate' => '地產',
					'species_hospital' => '醫院',
					'citizen' => '玩家 ID',
					'buy_sell' => '交易類型',
					'type' => '項目',
					'level' => '等級',
					'quantity' => '數量',
					'price' => '單位價格',
					'currency' => '貨幣類型',
					'link' => '個人連結',
					'link_go' => '點我前往',
					'remark' => '備註',
					'manage' => '管理',
					'non_sorting' => '不排序',
					'hightolow' => '高至低',
					'lowtohigh' => '低至高'
				);
			case "chs":
				return array(
					'title' => '产品',
					'all' => '全部',
					'mode_sell' => '卖出',
					'mode_buy' => '买进',
					'species_weapon' => '武器',
					'species_food' => '食物',
					'species_gift' => '礼物',
					'species_ticket' => '机票',
					'species_ds' => '防御',
					'species_house' => '房屋',
					'species_estate' => '地产',
					'species_hospital' => '医院',
					'citizen' => '玩家 ID',
					'buy_sell' => '交易类型',
					'type' => '项目',
					'level' => '等级',
					'quantity' => '数量',
					'price' => '单位价格',
					'currency' => '货币类型',
					'link' => '个人连结',
					'link_go' => '点我前往',
					'remark' => '备注',
					'manage' => '管理',
					'non_sorting' => '不排序',
					'hightolow' => '高至低',
					'lowtohigh' => '低至高'
				);
			case "eng":
			default :
				return array(
					'title' => 'Product',
					'all' => 'All',
					'mode_sell' => 'Sell',
					'mode_buy' => 'Buy',
					'species_weapon' => 'Weapon',
					'species_food' => 'Food',
					'species_gift' => 'Gift',
					'species_ticket' => 'Ticket',
					'species_ds' => 'DS',
					'species_house' => 'House',
					'species_estate' => 'Estate',
					'species_hospital' => 'Hospital',
					'citizen' => 'Citizen',
					'buy_sell' => 'Buy / Sell',
					'type' => 'Type',
					'level' => 'Level',
					'quantity' => 'Quantity',
					'price' => 'Price',
					'currency' => 'Currency',
					'link' => 'Link',
					'link_go' => 'Go',
					'remark' => 'Remark',
					'manage' => 'Manage',
					'non_sorting' => 'No Sorting',
					'hightolow' => 'high to low',
					'lowtohigh' => 'low to high'
				);
		}
	}
	
	function language_translation_trading_material() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '原料',
					'all' => '全部',
					'mode_sell' => '賣出',
					'mode_buy' => '買進',
					'species_iron' => '鐵',
					'species_grain' => '穀物',
					'species_diamond' => '鑽石',
					'species_oil' => '石油',
					'species_stone' => '石材',
					'species_wood' => '木材',
					'citizen' => '玩家 ID',
					'buy_sell' => '交易類型',
					'type' => '項目',
					'quantity' => '數量',
					'price' => '單位價格',
					'currency' => '貨幣類型',
					'link' => '個人連結',
					'link_go' => '點我前往',
					'remark' => '備註',
					'manage' => '管理',
					'non_sorting' => '不排序',
					'hightolow' => '高至低',
					'lowtohigh' => '低至高'
				);
			case "chs":
				return array(
					'title' => '原料',
					'all' => '全部',
					'mode_sell' => '卖出',
					'mode_buy' => '买进',
					'species_iron' => '铁',
					'species_grain' => '谷物',
					'species_diamond' => '钻石',
					'species_oil' => '石油',
					'species_stone' => '石材',
					'species_wood' => '木材',
					'citizen' => '玩家 ID',
					'buy_sell' => '交易类型',
					'type' => '项目',
					'quantity' => '数量',
					'price' => '单位价格',
					'currency' => '货币类型',
					'link' => '个人连结',
					'link_go' => '点我前往',
					'remark' => '备注',
					'manage' => '管理',
					'non_sorting' => '不排序',
					'hightolow' => '高至低',
					'lowtohigh' => '低至高'
				);
			case "eng":
			default :
				return array(
					'title' => 'Material',
					'all' => 'All',
					'mode_sell' => 'Sell',
					'mode_buy' => 'Buy',
					'species_iron' => 'Iron',
					'species_grain' => 'Grain',
					'species_diamond' => 'Diamond',
					'species_oil' => 'Oil',
					'species_stone' => 'Stone',
					'species_wood' => 'Wood',
					'citizen' => 'Citizen',
					'buy_sell' => 'Buy / Sell',
					'type' => 'Type',
					'quantity' => 'Quantity',
					'price' => 'Price',
					'currency' => 'Currency',
					'link' => 'Link',
					'link_go' => 'Go',
					'remark' => 'Remark',
					'manage' => 'Manage',
					'non_sorting' => 'No Sorting',
					'hightolow' => 'high to low',
					'lowtohigh' => 'low to high'
				);
		}
	}
	
	function language_translation_trading_work() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '人才中心',
					'all' => '全部',
					'citizen_id' => '玩家 ID',
					'type' => '招募 / 求職',
					'skill_level' => '工作技能',
					'daily_salary' => '每日薪資',
					'work_region' => '工作地點',
					'link' => '連結',
					'remark' => '備註',
					'manage' => '管理',
					'link_go' => '前往',
					'job_recruit' => '招募',
					'job_wanted' => '求職',
					'non_sorting' => '不排序',
					'hightolow' => '高至低',
					'lowtohigh' => '低至高'
				);
			case "chs":
				return array(
					'title' => '人才中心',
					'all' => '全部',
					'citizen_id' => '玩家 ID',
					'type' => '招募 / 求职',
					'skill_level' => '工作技能',
					'daily_salary' => '每日薪资',
					'work_region' => '工作地点',
					'link' => '连结',
					'remark' => '备注',
					'manage' => '管理',
					'link_go' => '前往',
					'job_recruit' => '招募',
					'job_wanted' => '求职',
					'non_sorting' => '不排序',
					'hightolow' => '高至低',
					'lowtohigh' => '低至高'
				);
			case "eng":
			default :
				return array(
					'title' => 'Work',
					'all' => 'All',
					'citizen_id' => 'Citizen',
					'type' => 'Recruit / Wanted',
					'skill_level' => 'Skill Level',
					'daily_salary' => 'Daily Salary',
					'work_region' => 'Work Region',
					'link' => 'Link',
					'remark' => 'Remark',
					'manage' => 'Manage',
					'link_go' => 'Go',
					'job_recruit' => 'Job Recruit',
					'job_wanted' => 'Job Wanted',
					'non_sorting' => 'No Sorting',
					'hightolow' => 'high to low',
					'lowtohigh' => 'low to high'
				);
		}
	}
?>