<?php
	function monetary_id_to_name($monetary_id) {
		switch ($monetary_id) {
			case 0: return "Gold";
			case 1: return "PLN";
			case 2: return "RUB";
			case 3: return "DEM";
			case 4: return "FRF";
			case 5: return "ESP";
			case 6: return "GBP";
			case 7: return "ITL";
			case 8: return "HUF";
			case 9: return "RON";
			case 10: return "BGN";
			case 11: return "RSD";
			case 12: return "HRK";
			case 13: return "BAM";
			case 14: return "GRD";
			case 15: return "MKD";
			case 16: return "UAH";
			case 17: return "SEK";
			case 18: return "PTE";
			case 19: return "LTL";
			case 20: return "LVL";
			case 21: return "SIT";
			case 22: return "TRY";
			case 23: return "BRL";
			case 24: return "ARS";
			case 25: return "MXN";
			case 26: return "USD";
			case 27: return "CAD";
			case 28: return "CNY";
			case 29: return "IDR";
			case 30: return "IRR";
			case 31: return "KRW";
			case 32: return "TWD";
			case 33: return "NIS";
			case 34: return "INR";
			case 35: return "AUD";
			case 36: return "NLG";
			case 37: return "FIM";
			case 38: return "IEP";
			case 39: return "CHF";
			case 40: return "BEF";
			case 41: return "PKR";
			case 42: return "MYR";
			case 43: return "NOK";
			case 44: return "PEN";
			case 45: return "CLP";
			case 46: return "COP";
			case 47: return "MEP";
			case 48: return "ATS";
			case 49: return "SKK";
			case 50: return "DKK";
			case 55: return "ALL";
			case 56: return "VEF";
			case 57: return "EGP";
			default : return "Unknown";
		}
	}
	
	function monetary_name_to_id($monetary_name) {
		switch ($monetary_name) {
			case "Gold": return 0;
			case "ALL": return 55;
			case "ARS": return 24;
			case "AUD": return 35;
			case "ATS": return 48;
			case "BEF": return 40;
			case "BAM": return 13;
			case "BRL": return 23;
			case "BGN": return 10;
			case "CAD": return 27;
			case "CLP": return 45;
			case "CNY": return 28;
			case "COP": return 46;
			case "HRK": return 12;
			case "DKK": return 50;
			case "EGP": return 57;
			case "FIM": return 37;
			case "FRF": return 4;
			case "DEM": return 3;
			case "GRD": return 14;
			case "HUF": return 8;
			case "INR": return 34;
			case "IDR": return 29;
			case "IRR": return 30;
			case "IEP": return 38;
			case "NIS": return 33;
			case "ITL": return 7;
			case "LVL": return 20;
			case "LTL": return 19;
			case "MYR": return 42;
			case "MXN": return 25;
			case "MEP": return 47;
			case "NLG": return 36;
			case "NOK": return 43;
			case "PKR": return 41;
			case "PEN": return 44;
			case "PLN": return 1;
			case "PTE": return 18;
			case "MKD": return 15;
			case "RON": return 9;
			case "RUB": return 2;
			case "RSD": return 11;
			case "SKK": return 49;
			case "SIT": return 21;
			case "KRw": return 31;
			case "ESP": return 5;
			case "SEK": return 17;
			case "CHF": return 39;
			case "TWD": return 32;
			case "TRY": return 22;
			case "UAH": return 16;
			case "GBP": return 6;
			case "USD": return 26;
			case "VEF": return 56;
			default : return -1;
		}
	}
	
	function get_monetary_name_and_id() {
		return array(
			array("Gold",0),
			array("ALL",55),
			array("ARS",24),
			array("AUD",35),
			array("ATS",48),
			array("BEF",40),
			array("BAM",13),
			array("BRL",23),
			array("BGN",10),
			array("CAD",27),
			array("CLP",45),
			array("CNY",28),
			array("COP",46),
			array("HRK",12),
			array("DKK",50),
			array("EGP",57),
			array("FIM",37),
			array("FRF",4),
			array("DEM",3),
			array("GRD",14),
			array("HUF",8),
			array("INR",34),
			array("IDR",29),
			array("IRR",30),
			array("IEP",38),
			array("NIS",33),
			array("ITL",7),
			array("LVL",20),
			array("LTL",19),
			array("MYR",42),
			array("MXN",25),
			array("MEP",47),
			array("NLG",36),
			array("NOK",43),
			array("PKR",41),
			array("PEN",44),
			array("PLN",1),
			array("PTE",18),
			array("MKD",15),
			array("RON",9),
			array("RUB",2),
			array("RSD",11),
			array("SKK",49),
			array("SIT",21),
			array("KRw",31),
			array("ESP",5),
			array("SEK",17),
			array("CHF",39),
			array("TWD",32),
			array("TRY",22),
			array("UAH",16),
			array("GBP",6),
			array("USD",26),
			array("VEF",56)
		);
	}
?>