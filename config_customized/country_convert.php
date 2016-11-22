<?php
	function country_id_to_name($country_id) {
		switch ($country_id) {
			case 1: return "Poland";
			case 2: return "Russia";
			case 3: return "Germany";
			case 4: return "France";
			case 5: return "Spain";
			case 6: return "United Kingdom";
			case 7: return "Italy";
			case 8: return "Hungary";
			case 9: return "Romania";
			case 10: return "Bulgaria";
			case 11: return "Serbia";
			case 12: return "Croatia";
			case 13: return "Bosnia and Herzegovina";
			case 14: return "Greece";
			case 15: return "Republic of Macedonia";
			case 16: return "Ukraine";
			case 17: return "Sweden";
			case 18: return "Portugal";
			case 19: return "Lithuania";
			case 20: return "Latvia";
			case 21: return "Slovenia";
			case 22: return "Turkey";
			case 23: return "Brazil";
			case 24: return "Argentina";
			case 25: return "Mexico";
			case 26: return "USA";
			case 27: return "Canada";
			case 28: return "China";
			case 29: return "Indonesia";
			case 30: return "Iran";
			case 31: return "South Korea";
			case 32: return "Taiwan";
			case 33: return "Israel";
			case 34: return "India";
			case 35: return "Australia";
			case 36: return "Netherlands";
			case 37: return "Finland";
			case 38: return "Ireland";
			case 39: return "Switzerland";
			case 40: return "Belgium";
			case 41: return "Pakistan";
			case 42: return "Malaysia";
			case 43: return "Norway";
			case 44: return "Peru";
			case 45: return "Chile";
			case 46: return "Colombia";
			case 47: return "Montenegro";
			case 48: return "Austria";
			case 49: return "Slovakia";
			case 50: return "Denmark";
			case 55: return "Albania";
			case 56: return "Venezuela";
			case 57: return "Egypt";
			default : return "Unknown";
		}
	}
	
	function country_name_to_id($country_name) {
		switch ($country_name) {
			case "Poland": return 1;
			case "Russia": return 2;
			case "Germany": return 3;
			case "France": return 4;
			case "Spain": return 5;
			case "United Kingdom": return 6;
			case "Italy": return 7;
			case "Hungary": return 8;
			case "Romania": return 9;
			case "Bulgaria": return 10;
			case "Serbia": return 11;
			case "Croatia": return 12;
			case "Bosnia and Herzegovina": return 13;
			case "Greece": return 14;
			case "Republic of Macedonia": return 15;
			case "Ukraine": return 16;
			case "Sweden": return 17;
			case "Portugal": return 18;
			case "Lithuania": return 19;
			case "Latvia": return 20;
			case "Slovenia": return 21;
			case "Turkey": return 22;
			case "Brazil": return 23;
			case "Argentina": return 24;
			case "Mexico": return 25;
			case "USA": return 26;
			case "Canada": return 27;
			case "China": return 28;
			case "Indonesia": return 29;
			case "Iran": return 30;
			case "South Korea": return 31;
			case "Taiwan": return 32;
			case "Israel": return 33;
			case "India": return 34;
			case "Australia": return 35;
			case "Netherlands": return 36;
			case "Finland": return 37;
			case "Ireland": return 38;
			case "Switzerland": return 39;
			case "Belgium": return 40;
			case "Pakistan": return 41;
			case "Malaysia": return 42;
			case "Norway": return 43;
			case "Peru": return 44;
			case "Chile": return 45;
			case "Colombia": return 46;
			case "Montenegro": return 47;
			case "Austria": return 48;
			case "Slovakia": return 49;
			case "Denmark": return 50;
			case "Albania": return 55;
			case "Venezuela": return 56;
			case "Egypt": return 57;
			default : return 0;
		}
	}
	
	function get_country_id_and_name() {
		return array(
			array(1,"Poland"),
			array(2,"Russia"),
			array(3,"Germany"),
			array(4,"France"),
			array(5,"Spain"),
			array(6,"United Kingdom"),
			array(7,"Italy"),
			array(8,"Hungary"),
			array(9,"Romania"),
			array(10,"Bulgaria"),
			array(11,"Serbia"),
			array(12,"Croatia"),
			array(13,"Bosnia and Herzegovina"),
			array(14,"Greece"),
			array(15,"Republic of Macedonia"),
			array(16,"Ukraine"),
			array(17,"Sweden"),
			array(18,"Portugal"),
			array(19,"Lithuania"),
			array(20,"Latvia"),
			array(21,"Slovenia"),
			array(22,"Turkey"),
			array(23,"Brazil"),
			array(24,"Argentina"),
			array(25,"Mexico"),
			array(26,"USA"),
			array(27,"Canada"),
			array(28,"China"),
			array(29,"Indonesia"),
			array(30,"Iran"),
			array(31,"South Korea"),
			array(32,"Taiwan"),
			array(33,"Israel"),
			array(34,"India"),
			array(35,"Australia"),
			array(36,"Netherlands"),
			array(37,"Finland"),
			array(38,"Ireland"),
			array(39,"Switzerland"),
			array(40,"Belgium"),
			array(41,"Pakistan"),
			array(42,"Malaysia"),
			array(43,"Norway"),
			array(44,"Peru"),
			array(45,"Chile"),
			array(46,"Colombia"),
			array(47,"Montenegro"),
			array(48,"Austria"),
			array(49,"Slovakia"),
			array(50,"Denmark"),
			array(55,"Albania"),
			array(56,"Venezuela"),
			array(57,"Egypt")
		);
	}
?>