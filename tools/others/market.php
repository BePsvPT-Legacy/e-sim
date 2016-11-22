<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	require_once $prefix."config_customized/curl_get_function.php";
	require_once $prefix."config_customized/monetary_convert.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "Monetary Market", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div>
					<div class="pure-g">
						<div class="pure-u-3-5">
							<form name="monetary_query" id="monetary_query" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned" onSubmit="return false;">
								<fieldset>
									<div class="pure-control-group">
										<label for="monetary_server">Server：</label>
										<select name="monetary_server" id="monetary_server" required>
											<option value="0">Primera</option>
											<option value="1" selected>Secura</option>
											<option value="2">Suna</option>
										</select>
									</div>
									<div class="pure-controls">
										<button type="submit" id="submit" class="pure-button pure-button-primary">Submit</button>
									</div>
								</fieldset>
							</form>
						</div>
						<div class="pure-u-2-5">
							<form name="market_query" id="market_query" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="pure-form pure-form-aligned" onSubmit="return false;">
								<fieldset>
									<div class="pure-control-group">
										<label for="market_server">Server：</label>
										<select name="market_server" id="market_server" required>
											<option value="0">Primera</option>
											<option value="1" selected>Secura</option>
											<option value="2">Suna</option>
										</select>
									</div>
									<div class="pure-control-group">
										<label for="res">Server：</label>
										<select name="res" id="res" required>
											<option value="weapon">Weapon</option>
											<option value="food">Food</option>
											<option value="gift">Gift</option>
											<option value="ticket">Ticket</option>
											<option value="house">House</option>
											<option value="estate">Estate</option>
											<option value="ds">DS</option>
											<option value="hospital">Hospital</option>
											<option value="iron">Iron</option>
											<option value="grain">Grain</option>
											<option value="diamonds">Diamonds</option>
											<option value="oil">Oil</option>
											<option value="wood">Wood</option>
											<option value="stone">Stone</option>
										</select>
									</div>
									<div class="pure-control-group">
										<label for="quality">Quality：</label>
										<select name="quality" id="quality" required>
											<option value="1">Q1</option>
											<option value="2">Q2</option>
											<option value="3">Q3</option>
											<option value="4">Q4</option>
											<option value="5">Q5</option>
										</select>
									</div>
									<div class="pure-controls">
										<button type="submit" id="submit" class="pure-button pure-button-primary">Submit</button>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
					<div class="pure-g">
						<div class="pure-u-3-5">
							<div class="heading_center heading_highlight">
								<h3 id="currency_error_message"></h3>
							</div>
							<div>
								<table class="pure-table">
									<thead>
										<tr>
											<th rowspan="2">#</th>
											<th rowspan="2">Country</th>
											<th colspan="2">Sell Gold to Buy Currency</th>
											<th colspan="2">Sell Currency to Buy Gold</th>
										</tr>
										<tr>
											<th>Rate</th>
											<th>Amount</th>
											<th>Rate</th>
											<th>Amount</th>
										</tr>
									</thead>
									<tbody id="currency_data">
									</tbody>
								</table>
							</div>
						</div>
						<div class="pure-u-2-5">
							<div class="heading_center heading_highlight">
								<h3 id="market_error_message"></h3>
							</div>
							<div>
								<table class="pure-table">
									<thead>
										<tr>
											<th>#</th>
											<th>Country</th>
											<th>Price</th>
											<th>Stock</th>
											<th>Ratio</th>
										</tr>
									</thead>
									<tbody id="market_data">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
		<script>
			$(document).ready(function(){var server_name=['primera','secura','suna'];var currency_name=[];currency_name[0]=['ARS','AUD','BDT','BYR','BEF','BAM','BRL','BGN','KHR','CAD','CLP','CNY','COP','HRK','CZK','EEK','FIM','FRF','DEM','GRD','HUF','INR','IDR','IRR','IEP','NIS','ITL','JPY','LVL','LTL','MYR','MXN','NLG','NZD','NOK','PKR','PEN','PHP','PLN','PTE','MKD','RON','RUB','RSD','SIT','KRW','ESP','SEK','CHF','TWD','THB','TRY','UAH','GBP','USD','VND'];currency_name[1]=['ALL','ARS','AUD','ATS','BEF','BAM','BRL','BGN','CAD','CLP','CNY','COP','HRK','DKK','EGP','FIM','FRF','DEM','GRD','HUF','INR','IDR','IRR','IEP','NIS','ITL','LVL','LTL','MYR','MXN','MEP','NLG','NOK','PKR','PEN','PLN','PTE','MKD','RON','RUB','RSD','SKK','SIT','KRW','ESP','SEK','CHF','TWD','TRY','UAH','GBP','USD','VEF'];currency_name[2]=['AFN','ALL','DZD','AOA','ARS','AMD','AUD','ATS','AZN','BDT','BYR','BEF','BJ','BOB','BAM','BWP','BRL','BGN','BF','BIF','KHR','CM','CAD','CF','TD','CLP','CNY','COP','CG','CRC','HRK','CUC','CZK','DKK','DJF','DOP','CDF','ECD','EGP','SVD','GQ','ERN','EEK','ETB','FIM','FRF','GA','GEL','DEM','GHS','GRD','GTQ','GNF','GW','GYT','HTG','HNL','HUF','INR','IDR','IRR','IQD','IEP','NIS','ITL','CI','JMD','JPY','JOD','KZT','KES','KGS','LAK','LVL','LBP','LSL','LRD','LYD','LTL','MGA','MWK','MYR','ML','MRO','MXN','MDL','MNT','MEP','MAD','MZN','NAD','NPR','NLG','NZD','NIO','NE','NGN','NOK','OMR','PKR','PAB','PGK','PYG','PEN','PHP','PLN','PTE','PRD','QAR','MKD','RON','RUB','RWF','SAR','SN','RSD','SLL','SKK','SIT','SOS','ZAR','KRW','SSP','ESP','LKR','SDG','SRD','SZL','SEK','CHF','SYP','TWD','TJS','TZS','THB','GMD','TG','TND','TRY','TMT','UGX','UAH','AED','GBP','UYU','USD','UZS','VEF','VND','EH','YER','ZMW','ZWL'];var country_id=[];country_id[0]=['24','35','59','52','40','13','23','10','119','27','45','28','46','12','51','53','37','4','3','14','8','34','29','30','38','33','7','58','20','19','42','25','36','139','43','41','44','54','1','18','15','9','2','11','21','31','5','17','39','32','63','22','16','6','26','60'];country_id[1]=['55','24','35','48','40','13','23','10','27','45','28','46','12','50','57','37','4','3','14','8','34','29','30','38','33','7','20','19','42','25','47','36','43','41','44','1','18','15','9','2','11','49','21','31','5','17','39','32','22','16','6','26','56'];country_id[2]=['130','55','64','65','24','131','35','48','132','59','52','40','85','121','13','84','23','10','86','109','119','66','27','88','92','45','28','46','87','144','12','147','51','50','107','126','89','122','57','152','100','90','53','68','37','4','91','133','3','69','14','127','96','97','140','153','125','8','34','29','30','112','38','33','7','67','151','58','115','128','70','134','135','20','148','102','99','71','19','104','105','42','94','95','25','150','145','47','72','73','101','120','36','139','142','93','74','43','113','41','143','146','123','44','54','1','18','149','114','15','9','2','108','62','75','11','98','49','21','106','76','31','118','5','129','77','141','103','17','39','111','32','136','78','63','117','79','80','22','137','81','16','110','6','124','26','138','56','60','116','61','82','83'];var currency_length=0,market_length=0,m=0,n=1,p=0,q=1;function currency(){$.getJSON('http://www.cscpro.org/'+server_name[$("#monetary_server").val()]+'/exchange/'+currency_name[$("#monetary_server").val()][m]+'-gold.jsonp?callback=?',function(data){var content='<tr><td>'+(n)+'</td><td>'+data.buy.country+'</td>';if(data.offer!=null){content+='<td style="text-align:left;"><a href="http://secura.e-sim.org/monetaryMarket.html?buyerCurrencyId='+country_id[$("#monetary_server").val()][m]+'&sellerCurrencyId=0" target="_blank">1 '+currency_name[$("#monetary_server").val()][m]+' = '+data.offer[0].rate+' Gold</a><div id="rate_'+currency_name[$("#monetary_server").val()][m]+'" style="display:none;">'+data.offer[0].rate+'</div></td>';content+='<td>'+data.offer[0].amount+'</td>'}else{content+='<td>-</td>';content+='<td>-</td>'}$.getJSON('http://www.cscpro.org/'+server_name[$("#monetary_server").val()]+'/exchange/gold-'+currency_name[$("#monetary_server").val()][m]+'.jsonp?callback=?',function(data){if(data.offer!=null){content+='<td style="text-align:left;"><a href="http://secura.e-sim.org/monetaryMarket.html?buyerCurrencyId=0&sellerCurrencyId='+country_id[$("#monetary_server").val()][m]+'" target="_blank">1 Gold = '+data.offer[0].rate+' '+currency_name[$("#monetary_server").val()][m]+'</a></td>';content+='<td>'+data.offer[0].amount+'</td></tr>'}else{content+='<td>-</td>';content+='<td>-</td></tr>'}$("#currency_data").append(content);n++;m++;if(m<currency_length){currency()}})})}function market(){$.getJSON('http://www.cscpro.org/'+server_name[$("#market_server").val()]+'/market/'+$("#res").val()+'-'+country_id[$("#market_server").val()][p]+'-'+$("#quality").val()+'.jsonp?callback=?',function(data){var content='<tr><td>'+(q)+'</td><td>'+data.country.name+'</td>';if(data.offer[0].price!=0){content+='<td>'+data.offer[0].price+' '+currency_name[$("#market_server").val()][p]+'</td>';content+='<td>'+data.offer[0].stock+'</td>';if($("#rate_"+currency_name[$("#market_server").val()][p]+"").html()!=undefined){content+='<td>'+(data.offer[0].price*$("#rate_"+currency_name[$("#market_server").val()][p]+"").html()).toFixed(4)+'</td></tr>'}else{content+='<td>-</td></tr>'}}else{content+='<td>-</td>';content+='<td>-</td>';content+='<td>-</td></tr>'}$("#market_data").append(content);q++;p++;if(p<market_length){market()}})}$("#monetary_query").submit(function(){currency_length=country_id[$("#monetary_server").val()].length;$("#currency_data").empty();m=0;n=1;currency()});$("#market_query").submit(function(){market_length=country_id[$("#market_server").val()].length;$("#market_data").empty();p=0;q=1;market()})});
		</script>
	</body>
</html>
<?php
	/*
	$(document).ready(function() {
		var server_name = ['primera', 'secura', 'suna'];
		var currency_name = [];
		currency_name[0] = ['ARS','AUD','BDT','BYR','BEF','BAM','BRL','BGN','KHR','CAD','CLP','CNY','COP','HRK','CZK','EEK','FIM','FRF','DEM','GRD','HUF','INR','IDR','IRR','IEP','NIS','ITL','JPY','LVL','LTL','MYR','MXN','NLG','NZD','NOK','PKR','PEN','PHP','PLN','PTE','MKD','RON','RUB','RSD','SIT','KRW','ESP','SEK','CHF','TWD','THB','TRY','UAH','GBP','USD','VND'];
		currency_name[1] = ['ALL','ARS','AUD','ATS','BEF','BAM','BRL','BGN','CAD','CLP','CNY','COP','HRK','DKK','EGP','FIM','FRF','DEM','GRD','HUF','INR','IDR','IRR','IEP','NIS','ITL','LVL','LTL','MYR','MXN','MEP','NLG','NOK','PKR','PEN','PLN','PTE','MKD','RON','RUB','RSD','SKK','SIT','KRW','ESP','SEK','CHF','TWD','TRY','UAH','GBP','USD','VEF'];
		currency_name[2] = ['AFN', 'ALL', 'DZD', 'AOA', 'ARS', 'AMD', 'AUD', 'ATS', 'AZN', 'BDT', 'BYR', 'BEF', 'BJ', 'BOB', 'BAM', 'BWP', 'BRL', 'BGN', 'BF', 'BIF', 'KHR', 'CM', 'CAD', 'CF', 'TD', 'CLP', 'CNY', 'COP', 'CG', 'CRC', 'HRK', 'CUC', 'CZK', 'DKK', 'DJF', 'DOP', 'CDF', 'ECD', 'EGP', 'SVD', 'GQ', 'ERN', 'EEK', 'ETB', 'FIM', 'FRF', 'GA', 'GEL', 'DEM', 'GHS', 'GRD', 'GTQ', 'GNF', 'GW', 'GYT', 'HTG', 'HNL', 'HUF', 'INR', 'IDR', 'IRR', 'IQD', 'IEP', 'NIS', 'ITL', 'CI', 'JMD', 'JPY', 'JOD', 'KZT', 'KES', 'KGS', 'LAK', 'LVL', 'LBP', 'LSL', 'LRD', 'LYD', 'LTL', 'MGA', 'MWK', 'MYR', 'ML', 'MRO', 'MXN', 'MDL', 'MNT', 'MEP', 'MAD', 'MZN', 'NAD', 'NPR', 'NLG', 'NZD', 'NIO', 'NE', 'NGN', 'NOK', 'OMR', 'PKR', 'PAB', 'PGK', 'PYG', 'PEN', 'PHP', 'PLN', 'PTE', 'PRD', 'QAR', 'MKD', 'RON', 'RUB', 'RWF', 'SAR', 'SN', 'RSD', 'SLL', 'SKK', 'SIT', 'SOS', 'ZAR', 'KRW', 'SSP', 'ESP', 'LKR', 'SDG', 'SRD', 'SZL', 'SEK', 'CHF', 'SYP', 'TWD', 'TJS', 'TZS', 'THB', 'GMD', 'TG', 'TND', 'TRY', 'TMT', 'UGX', 'UAH', 'AED', 'GBP', 'UYU', 'USD', 'UZS', 'VEF', 'VND', 'EH', 'YER', 'ZMW', 'ZWL'];
		var country_id = [];
		country_id[0] = ['24','35','59','52','40','13','23','10','119','27','45','28','46','12','51','53','37','4','3','14','8','34','29','30','38','33','7','58','20','19','42','25','36','139','43','41','44','54','1','18','15','9','2','11','21','31','5','17','39','32','63','22','16','6','26','60'];
		country_id[1] = ['55','24','35','48','40','13','23','10','27','45','28','46','12','50','57','37','4','3','14','8','34','29','30','38','33','7','20','19','42','25','47','36','43','41','44','1','18','15','9','2','11','49','21','31','5','17','39','32','22','16','6','26','56'];
		country_id[2] = ['130', '55', '64', '65', '24', '131', '35', '48', '132', '59', '52', '40', '85', '121', '13', '84', '23', '10', '86', '109', '119', '66', '27', '88', '92', '45', '28', '46', '87', '144', '12', '147', '51', '50', '107', '126', '89', '122', '57', '152', '100', '90', '53', '68', '37', '4', '91', '133', '3', '69', '14', '127', '96', '97', '140', '153', '125', '8', '34', '29', '30', '112', '38', '33', '7', '67', '151', '58', '115', '128', '70', '134', '135', '20', '148', '102', '99', '71', '19', '104', '105', '42', '94', '95', '25', '150', '145', '47', '72', '73', '101', '120', '36', '139', '142', '93', '74', '43', '113', '41', '143', '146', '123', '44', '54', '1', '18', '149', '114', '15', '9', '2', '108', '62', '75', '11', '98', '49', '21', '106', '76', '31', '118', '5', '129', '77', '141', '103', '17', '39', '111', '32', '136', '78', '63', '117', '79', '80', '22', '137', '81', '16', '110', '6', '124', '26', '138', '56', '60', '116', '61', '82', '83'];
		var currency_length = 0, market_length = 0, m = 0, n = 1, p = 0, q = 1;
		
		function currency() {
			$.getJSON(
				'http://www.cscpro.org/' + server_name[$("#monetary_server").val()] + '/exchange/' + currency_name[$("#monetary_server").val()][m] + '-gold.jsonp?callback=?',
				function (data) {
					var content = '<tr><td>' + (n) + '</td><td>' + data.buy.country + '</td>';
					if (data.offer != null) {
						content += '<td style="text-align:left;"><a href="http://secura.e-sim.org/monetaryMarket.html?buyerCurrencyId=' + country_id[$("#monetary_server").val()][m] + '&sellerCurrencyId=0" target="_blank">1 ' + currency_name[$("#monetary_server").val()][m] + ' = ' + data.offer[0].rate + ' Gold</a><div id="rate_' + currency_name[$("#monetary_server").val()][m] + '" style="display:none;">' + data.offer[0].rate + '</div></td>';
						content += '<td>' + data.offer[0].amount + '</td>';
					} else {
						content += '<td>-</td>';
						content += '<td>-</td>';
					}
					$.getJSON(
						'http://www.cscpro.org/' + server_name[$("#monetary_server").val()] + '/exchange/gold-' + currency_name[$("#monetary_server").val()][m] + '.jsonp?callback=?',
						function (data) {
							if (data.offer != null) {
								content += '<td style="text-align:left;"><a href="http://secura.e-sim.org/monetaryMarket.html?buyerCurrencyId=0&sellerCurrencyId=' + country_id[$("#monetary_server").val()][m] + '" target="_blank">1 Gold = ' + data.offer[0].rate + ' ' + currency_name[$("#monetary_server").val()][m] + '</a></td>';
								content += '<td>' + data.offer[0].amount + '</td></tr>';
							} else {
								content += '<td>-</td>';
								content += '<td>-</td></tr>';
							}
							$("#currency_data").append(content);
							n++;
							m++;
							if (m < currency_length) {
								currency();
							}
						}
					);
				}
			);
		}
		
		function market() {
			$.getJSON(
				'http://www.cscpro.org/' + server_name[$("#market_server").val()] + '/market/' + $("#res").val() + '-' + country_id[$("#market_server").val()][p] + '-' + $("#quality").val() + '.jsonp?callback=?',
				function (data) {
					var content = '<tr><td>' + (q) + '</td><td>' + data.country.name + '</td>';
					if (data.offer[0].price != 0) {
						content += '<td>' + data.offer[0].price + ' ' + currency_name[$("#market_server").val()][p] + '</td>';
						content += '<td>' + data.offer[0].stock + '</td>';
						if ($("#rate_" + currency_name[$("#market_server").val()][p] + "").html() != undefined) {
							content += '<td>' + (data.offer[0].price * $("#rate_" + currency_name[$("#market_server").val()][p] + "").html()).toFixed(4) + '</td></tr>';
						} else {
							content += '<td>-</td></tr>';
						}
					} else {
						content += '<td>-</td>';
						content += '<td>-</td>';
						content += '<td>-</td></tr>';
					}
					$("#market_data").append(content);
					q++;
					p++;
					if (p < market_length) {
						market();
					}
				}
			);
		}
		
		$("#monetary_query").submit(function(){
			currency_length = country_id[$("#monetary_server").val()].length;
			$("#currency_data").empty();
			m = 0;
			n = 1;
			currency();
		});
		
		$("#market_query").submit(function(){
			market_length = country_id[$("#market_server").val()].length;
			$("#market_data").empty();
			p = 0;
			q = 1;
			market();
		});
	});
	*/
?>