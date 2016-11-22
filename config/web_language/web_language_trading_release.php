<?php
	// work
	function language_translation_trading_release_work() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '發佈 - 工作',
					'heading' => '招募',
					'heading_staff' => '求職',
					'skill_level' => '需求技能',
					'daily_salary' => '每日薪資',
					'work_region' => '工作地點',
					'remark' => '備　　註',
					'submit' => '發佈',
					'reset' => '清除'
				);
			case "chs":
				return array(
					'title' => '发布 - 工作',
					'heading' => '招募',
					'heading_staff' => '求职',
					'skill_level' => '需求技能',
					'daily_salary' => '每日薪资',
					'work_region' => '工作地点',
					'remark' => '备　　注',
					'submit' => '发布',
					'reset' => '清除'
				);
			case "eng":
			default :
				return array(
					'title' => 'Release - Work',
					'heading' => 'Job Recruit',
					'heading_staff' => 'Job Wanted',
					'skill_level' => 'Skill Level',
					'daily_salary' => 'Daily Salary',
					'work_region' => 'Work Region',
					'remark' => 'Remark',
					'submit' => 'Submit',
					'reset' => 'Reset'
				);
		}
	}
	
	// product
	function language_translation_trading_release_product() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '發佈 - 產品',
					'heading' => '產品',
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
					'submit' => '發佈',
					'reset' => '清除'
				);
			case "chs":
				return array(
					'title' => '发布 - 产品',
					'heading' => '产品',
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
					'submit' => '发布',
					'reset' => '清除'
				);
			case "eng":
			default :
				return array(
					'title' => 'Release - Product',
					'heading' => 'Product',
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
					'submit' => 'Submit',
					'reset' => 'Reset'
				);
		}
	}
	
	// material
	function language_translation_trading_release_material() {
		switch($_SESSION['language']) {
			case "cht":
				return array(
					'title' => '發佈 - 原料',
					'heading' => '原料',
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
					'submit' => '發佈',
					'reset' => '清除'
				);
			case "chs":
				return array(
					'title' => '发布 - 原料',
					'heading' => '原料',
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
					'submit' => '发布',
					'reset' => '清除'
				);
			case "eng":
			default :
				return array(
					'title' => 'Release - Material',
					'heading' => 'Material',
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
					'submit' => 'Submit',
					'reset' => 'Reset'
				);
		}
	}
?>