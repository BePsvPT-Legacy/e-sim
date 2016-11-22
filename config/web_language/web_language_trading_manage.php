<?php
	// product
	function language_translation_trading_manage_product() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '管理 - 產品',
					'id_number' => '交易編號',
					'buy_sell' => '交易類型',
					'mode_sell' => '賣出',
					'mode_buy' => '買進',
					'type' => '項　　目',
					'species_weapon' => '武器',
					'species_food' => '食物',
					'species_gift' => '禮物',
					'species_ticket' => '機票',
					'species_ds' => '防禦',
					'species_house' => '房屋',
					'species_estate' => '地產',
					'species_hospital' => '醫院',
					'level' => '等　　級',
					'quantity' => '數　　量',
					'price' => '單位價格',
					'remark' => '備　　註',
					'submit' => '更改',
					'reset' => '清除',
					'delete' => '關閉交易',
					'check_delete' => '交易關閉後即無法更改和恢復，確定要關閉此交易?'
				);
			case "chs":
				return array(
					'title' => '管理 - 产品',
					'id_number' => '交易编号',
					'buy_sell' => '交易类型',
					'mode_sell' => '卖出',
					'mode_buy' => '买进',
					'type' => '项　　目',
					'species_weapon' => '武器',
					'species_food' => '食物',
					'species_gift' => '礼物',
					'species_ticket' => '机票',
					'species_ds' => '防御',
					'species_house' => '房屋',
					'species_estate' => '地产',
					'species_hospital' => '医院',
					'level' => '等　　级',
					'quantity' => '数　　量',
					'price' => '单位价格',
					'remark' => '备　　注',
					'submit' => '更改',
					'reset' => '清除',
					'delete' => '关闭交易',
					'check_delete' => '交易关闭后即无法更改和恢复，确定要关闭此交易?'
				);
			case "eng":
			default :
				return array(
					'title' => 'Manage - Product',
					'id_number' => 'Serial Number',
					'buy_sell' => 'Buy / Sell',
					'mode_sell' => 'Sell',
					'mode_buy' => 'Buy',
					'type' => 'Type',
					'species_weapon' => 'Weapon',
					'species_food' => 'Food',
					'species_gift' => 'Gift',
					'species_ticket' => 'Ticket',
					'species_ds' => 'DS',
					'species_house' => 'House',
					'species_estate' => 'Estate',
					'species_hospital' => 'Hospital',
					'level' => 'Level',
					'quantity' => 'Quantity',
					'price' => 'Price',
					'remark' => 'Remark',
					'submit' => 'Change',
					'reset' => 'Reset',
					'delete' => 'Close the Transaction',
					'check_delete' => 'This operation is irreversible, are you sure to do it?'
				);
		}
	}
	
	// material
	function language_translation_trading_manage_material() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '管理 - 原料',
					'id_number' => '交易編號',
					'buy_sell' => '交易類型',
					'mode_sell' => '賣出',
					'mode_buy' => '買進',
					'type' => '項　　目',
					'species_iron' => '鐵',
					'species_grain' => '穀物',
					'species_diamond' => '鑽石',
					'species_oil' => '石油',
					'species_stone' => '石材',
					'species_wood' => '木材',
					'quantity' => '數　　量',
					'price' => '單位價格',
					'remark' => '備　　註',
					'submit' => '更改',
					'reset' => '清除',
					'delete' => '關閉交易',
					'check_delete' => '交易關閉後即無法更改和恢復，確定要關閉此交易?'
				);
			case "chs":
				return array(
					'title' => '管理 - 原料',
					'id_number' => '交易编号',
					'buy_sell' => '交易类型',
					'mode_sell' => '卖出',
					'mode_buy' => '买进',
					'type' => '项　　目',
					'species_iron' => '铁',
					'species_grain' => '谷物',
					'species_diamond' => '钻石',
					'species_oil' => '石油',
					'species_stone' => '石材',
					'species_wood' => '木材',
					'quantity' => '数　　量',
					'price' => '单位价格',
					'remark' => '备　　注',
					'submit' => '更改',
					'reset' => '清除',
					'delete' => '关闭交易',
					'check_delete' => '交易关闭后即无法更改和恢复，确定要关闭此交易?'
				);
			case "eng":
			default :
				return array(
					'title' => 'Manage - Material',
					'id_number' => 'Serial Number',
					'buy_sell' => 'Buy / Sell',
					'mode_sell' => 'Sell',
					'mode_buy' => 'Buy',
					'type' => 'Type',
					'species_iron' => 'Iron',
					'species_grain' => 'Grain',
					'species_diamond' => 'Diamond',
					'species_oil' => 'Oil',
					'species_stone' => 'Stone',
					'species_wood' => 'Wood',
					'quantity' => 'Quantity',
					'price' => 'Price',
					'remark' => 'Remark',
					'submit' => 'Change',
					'reset' => 'Reset',
					'delete' => 'Close the Transaction',
					'check_delete' => 'This operation is irreversible, are you sure to do it?'
				);
		}
	}
	
	// work
	function language_translation_trading_manage_work() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '管理 - 工作',
					'id_number' => '工作編號',
					'skill_level' => '需求技能',
					'daily_salary' => '每日薪資',
					'work_region' => '工作地點',
					'remark' => '備　　註',
					'submit' => '更改',
					'reset' => '清除',
					'delete' => '關閉交易',
					'check_delete' => '交易關閉後即無法更改和恢復，確定要關閉?'
				);
			case "chs":
				return array(
					'title' => '管理 - 工作',
					'id_number' => '工作编号',
					'skill_level' => '需求技能',
					'daily_salary' => '每日薪资',
					'work_region' => '工作地点',
					'remark' => '备　　注',
					'submit' => '更改',
					'reset' => '清除',
					'delete' => '关闭交易',
					'check_delete' => '交易关闭后即无法更改和恢复，确定要关闭?'
				);
			case "eng":
			default :
				return array(
					'title' => 'Manage - Work',
					'id_number' => 'Serial Number',
					'skill_level' => 'Skill Level',
					'daily_salary' => 'Daily Salary',
					'work_region' => 'Work Region',
					'remark' => 'Remark',
					'submit' => 'Change',
					'reset' => 'Reset',
					'delete' => 'Close the Work',
					'check_delete' => 'This operation is irreversible, are you sure to do it?'
				);
		}
	}
?>