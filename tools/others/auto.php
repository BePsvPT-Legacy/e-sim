<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	
	if (!isset($_SESSION['cid'])) {
		header("Location: ".$prefix."user/login.php");
		exit();
	}
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "輔助工具", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
<?php
	if (isset($query_deny)) {
		echo <<<EOD
				<div class="heading_center heading_highlight">
					<h3>$query_deny</h3>
				</div>\n
EOD;
	} else {
?>
				<div>
					<script src="//google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
					<div>
						<div class="heading_center">
							緊握不放 成就
						</div>
						<div style="width:85%; margin:0 auto">
							<pre class="prettyprint">
var country_name = [&#039;.Afghanistan&#039;, &#039;.Albania&#039;, &#039;.Algeria&#039;, &#039;.Angola&#039;, &#039;.Argentina&#039;, &#039;.Armenia&#039;, &#039;.Australia&#039;, &#039;.Austria&#039;, &#039;.Azerbaijan&#039;, &#039;.Bangladesh&#039;, &#039;.Belarus&#039;, &#039;.Belgium&#039;, &#039;.Benin&#039;, &#039;.Bolivia&#039;, &#039;.Bosnia-and-Herzegovina&#039;, &#039;.Botswana&#039;, &#039;.Brazil&#039;, &#039;.Bulgaria&#039;, &#039;.Burkina-Faso&#039;, &#039;.Burundi&#039;, &#039;.Cambodia&#039;, &#039;.Cameroon&#039;, &#039;.Canada&#039;, &#039;.Central-African-Republic&#039;, &#039;.Chad&#039;, &#039;.Chile&#039;, &#039;.China&#039;, &#039;.Colombia&#039;, &#039;.Congo&#039;, &#039;.Costa-Rica&#039;, &#039;.Croatia&#039;, &#039;.Cuba&#039;, &#039;.Czech-Republic&#039;, &#039;.Denmark&#039;, &#039;.Djibouti&#039;, &#039;.Dominican-Republic&#039;, &#039;.DR-of-the-Congo&#039;, &#039;.Ecuador&#039;, &#039;.Egypt&#039;, &#039;.El-Salvador&#039;, &#039;.Equatorial-Guinea&#039;, &#039;.Eritrea&#039;, &#039;.Estonia&#039;, &#039;.Ethiopia&#039;, &#039;.Finland&#039;, &#039;.France&#039;, &#039;.Gabon&#039;, &#039;.Georgia&#039;, &#039;.Germany&#039;, &#039;.Ghana&#039;, &#039;.Greece&#039;, &#039;.Guatemala&#039;, &#039;.Guinea&#039;, &#039;.Guinea-Bissau&#039;, &#039;.Guyana&#039;, &#039;.Haiti&#039;, &#039;.Honduras&#039;, &#039;.Hungary&#039;, &#039;.India&#039;, &#039;.Indonesia&#039;, &#039;.Iran&#039;, &#039;.Iraq&#039;, &#039;.Ireland&#039;, &#039;.Israel&#039;, &#039;.Italy&#039;, &#039;.Ivory-Coast&#039;, &#039;.Jamaica&#039;, &#039;.Japan&#039;, &#039;.Jordan&#039;, &#039;.Kazakhstan&#039;, &#039;.Kenya&#039;, &#039;.Kyrgyzstan&#039;, &#039;.Laos&#039;, &#039;.Latvia&#039;, &#039;.Lebanon&#039;, &#039;.Lesotho&#039;, &#039;.Liberia&#039;, &#039;.Libya&#039;, &#039;.Lithuania&#039;, &#039;.Madagascar&#039;, &#039;.Malawi&#039;, &#039;.Malaysia&#039;, &#039;.Mali&#039;, &#039;.Mauritania&#039;, &#039;.Mexico&#039;, &#039;.Moldova&#039;, &#039;.Mongolia&#039;, &#039;.Montenegro&#039;, &#039;.Morocco&#039;, &#039;.Mozambique&#039;, &#039;.Namibia&#039;, &#039;.Nepal&#039;, &#039;.Netherlands&#039;, &#039;.New-Zealand&#039;, &#039;.Nicaragua&#039;, &#039;.Niger&#039;, &#039;.Nigeria&#039;, &#039;.Norway&#039;, &#039;.Oman&#039;, &#039;.Pakistan&#039;, &#039;.Panama&#039;, &#039;.Papua-New-Guinea&#039;, &#039;.Paraguay&#039;, &#039;.Peru&#039;, &#039;.Philippines&#039;, &#039;.Poland&#039;, &#039;.Portugal&#039;, &#039;.Puerto-Rico&#039;, &#039;.Qatar&#039;, &#039;.Republic-of-Macedonia&#039;, &#039;.Romania&#039;, &#039;.Russia&#039;, &#039;.Rwanda&#039;, &#039;.Saudi-Arabia&#039;, &#039;.Senegal&#039;, &#039;.Serbia&#039;, &#039;.Sierra-Leone&#039;, &#039;.Slovakia&#039;, &#039;.Slovenia&#039;, &#039;.Somalia&#039;, &#039;.South-Africa&#039;, &#039;.South-Korea&#039;, &#039;.South-Sudan&#039;, &#039;.Spain&#039;, &#039;.Sri-Lanka&#039;, &#039;.Sudan&#039;, &#039;.Suriname&#039;, &#039;.Swaziland&#039;, &#039;.Sweden&#039;, &#039;.Switzerland&#039;, &#039;.Syria&#039;, &#039;.Taiwan&#039;, &#039;.Tajikistan&#039;, &#039;.Tanzania&#039;, &#039;.Thailand&#039;, &#039;.The-Gambia&#039;, &#039;.Togo&#039;, &#039;.Tunisia&#039;, &#039;.Turkey&#039;, &#039;.Turkmenistan&#039;, &#039;.Uganda&#039;, &#039;.Ukraine&#039;, &#039;.United-Arab-Emirates&#039;, &#039;.United-Kingdom&#039;, &#039;.Uruguay&#039;, &#039;.USA&#039;, &#039;.Uzbekistan&#039;, &#039;.Venezuela&#039;, &#039;.Vietnam&#039;, &#039;.Western-Sahara&#039;, &#039;.Yemen&#039;, &#039;.Zambia&#039;, &#039;.Zimbabwe&#039;];
var country_id = [&#039;130&#039;, &#039;55&#039;, &#039;64&#039;, &#039;65&#039;, &#039;24&#039;, &#039;131&#039;, &#039;35&#039;, &#039;48&#039;, &#039;132&#039;, &#039;59&#039;, &#039;52&#039;, &#039;40&#039;, &#039;85&#039;, &#039;121&#039;, &#039;13&#039;, &#039;84&#039;, &#039;23&#039;, &#039;10&#039;, &#039;86&#039;, &#039;109&#039;, &#039;119&#039;, &#039;66&#039;, &#039;27&#039;, &#039;88&#039;, &#039;92&#039;, &#039;45&#039;, &#039;28&#039;, &#039;46&#039;, &#039;87&#039;, &#039;144&#039;, &#039;12&#039;, &#039;147&#039;, &#039;51&#039;, &#039;50&#039;, &#039;107&#039;, &#039;126&#039;, &#039;89&#039;, &#039;122&#039;, &#039;57&#039;, &#039;152&#039;, &#039;100&#039;, &#039;90&#039;, &#039;53&#039;, &#039;68&#039;, &#039;37&#039;, &#039;4&#039;, &#039;91&#039;, &#039;133&#039;, &#039;3&#039;, &#039;69&#039;, &#039;14&#039;, &#039;127&#039;, &#039;96&#039;, &#039;97&#039;, &#039;140&#039;, &#039;153&#039;, &#039;125&#039;, &#039;8&#039;, &#039;34&#039;, &#039;29&#039;, &#039;30&#039;, &#039;112&#039;, &#039;38&#039;, &#039;33&#039;, &#039;7&#039;, &#039;67&#039;, &#039;151&#039;, &#039;58&#039;, &#039;115&#039;, &#039;128&#039;, &#039;70&#039;, &#039;134&#039;, &#039;135&#039;, &#039;20&#039;, &#039;148&#039;, &#039;102&#039;, &#039;99&#039;, &#039;71&#039;, &#039;19&#039;, &#039;104&#039;, &#039;105&#039;, &#039;42&#039;, &#039;94&#039;, &#039;95&#039;, &#039;25&#039;, &#039;150&#039;, &#039;145&#039;, &#039;47&#039;, &#039;72&#039;, &#039;73&#039;, &#039;101&#039;, &#039;120&#039;, &#039;36&#039;, &#039;139&#039;, &#039;142&#039;, &#039;93&#039;, &#039;74&#039;, &#039;43&#039;, &#039;113&#039;, &#039;41&#039;, &#039;143&#039;, &#039;146&#039;, &#039;123&#039;, &#039;44&#039;, &#039;54&#039;, &#039;1&#039;, &#039;18&#039;, &#039;149&#039;, &#039;114&#039;, &#039;15&#039;, &#039;9&#039;, &#039;2&#039;, &#039;108&#039;, &#039;62&#039;, &#039;75&#039;, &#039;11&#039;, &#039;98&#039;, &#039;49&#039;, &#039;21&#039;, &#039;106&#039;, &#039;76&#039;, &#039;31&#039;, &#039;118&#039;, &#039;5&#039;, &#039;129&#039;, &#039;77&#039;, &#039;141&#039;, &#039;103&#039;, &#039;17&#039;, &#039;39&#039;, &#039;111&#039;, &#039;32&#039;, &#039;136&#039;, &#039;78&#039;, &#039;63&#039;, &#039;117&#039;, &#039;79&#039;, &#039;80&#039;, &#039;22&#039;, &#039;137&#039;, &#039;81&#039;, &#039;16&#039;, &#039;110&#039;, &#039;6&#039;, &#039;124&#039;, &#039;26&#039;, &#039;138&#039;, &#039;56&#039;, &#039;60&#039;, &#039;116&#039;, &#039;61&#039;, &#039;82&#039;, &#039;83&#039;];
var length = country_name.length;

for (var i = 0; i &lt; length; i++) {
	if ($(country_name[i]).html() != undefined) {
		$(&quot;[value=&#039;&quot;+country_id[i]+&quot;&#039;]&quot;).remove();
	}
}

$(&#039;#buy &gt; [value=&quot;0&quot;]&#039;).remove();
$(&#039;#monetaryMarketView&#039;).submit();
							</pre>
						</div>
					</div>
					<div>
						<div class="heading_center">
							自動輸出
						</div>
						<div style="width:85%; margin:0 auto">
							<pre class="prettyprint">
var wep = &quot;1&quot;; // 武器等級
var is_food = true; // true:麵包 false:禮物
var quality = &quot;5&quot;; // 麵包或禮物的等級
var def_side = true; // true:防守方 false:進攻方
var is_berserk = true; // true:berserk false:not
var food = (is_food) ? &quot;eat.html&quot; : &quot;gift.html&quot;;
var side = ($(&quot;#fightButton2&quot;).val() == undefined) ? &quot;side&quot; : (def_side) ? &quot;defender&quot; : &quot;attacker&quot;;
var berserk = (is_berserk) ? &quot;Berserk&quot; : &quot;undefined&quot;;
var hit_quantity = (is_berserk) ? 50 : 10;
var wep_stock = $(&#039;#Q&#039;+wep+&#039;WeaponStock&#039;).html();
var next_time = 0, time = 1500, actualWellness = parseInt($(&quot;#actualHealth&quot;).html()), battleRoundId = $(&quot;#battleRoundId&quot;).val();

function eat() {
	if (actualWellness &gt;= hit_quantity) {
		next_time = setTimeout(fight, time);
	} else {
		$.post(
			food,
			{quality:quality},
			function (a) {
				actualWellness = parseInt(a.wellness);
				if (parseInt(a.wellness) &gt;= hit_quantity) {
					next_time = setTimeout(fight, time);
				} else {
					if (is_food) {
						var tmp = &quot;q&quot;+quality+&quot;FoodStorage&quot;;
						var check = (parseInt(a.foodLimit) &gt; 0 &amp;&amp;  parseInt(a.tmp) &gt; 0) ? true : false;
					} else {
						var tmp = &quot;q&quot;+quality+&quot;GiftStorage&quot;;
						var check = (parseInt(a.giftLimit) &gt; 0 &amp;&amp;  parseInt(a.tmp) &gt; 0) ? true : false;
					}
					if (check) {
						next_time = setTimeout(eat, time);
					} else {
						tmp = (is_food) ? &quot;麵包&quot; : &quot;禮物&quot;;
						alert(&#039;輸出結束，額度耗盡或&#039;+tmp+&#039;耗盡&#039;);
					}
				}
			}, &quot;json&quot;
		);
	}
}

function fight() {
	$.post(
		&quot;fight.html&quot;,
		{weaponQuality:wep,battleRoundId:battleRoundId,side:side,value:berserk},
		function (b) {
			if (b.indexOf(&quot;You can&#039;t use weapons&quot;) == -1) {
				wep_stock -= (hit_quantity / 10);
				if (wep_stock &lt; hit_quantity / 10) {
					alert(&#039;輸出結束，武器耗盡&#039;);
				} else {
					if (b.indexOf(&#039;Your armor absorbed the damage&#039;) == -1) {
						actualWellness -= hit_quantity;
					}
					next_time = setTimeout(eat, time);
				}
			} else {
				alert(&quot;You can&#039;t use weapons now.&quot;);
			}
		}
	);
}
eat();
							</pre>
						</div>
					</div>
					<div>
						<div class="heading_center">
							自動激勵
						</div>
						<div style="width:85%; margin:0 auto">
							<pre class="prettyprint">
var citizen_id = &#039;&#039;, next_time = 0, food_end = parseInt($(&quot;#foodLimit&quot;).text()) + 5, limit_times = 25;

function init() {
	var tmp = null;
	while (!tmp) {
		tmp = prompt(&quot;請輸入起始公民ID&quot;);
	}
	citizen_id = tmp;
	motivate();
}

function motivate() {
	$.get(
		&#039;motivateCitizen.html?id=&#039;+citizen_id,
		function (a) {
			var randtime = Math.floor((Math.random() * 1000) + 2501);
			if (a.indexOf(&#039;name=&quot;type&quot; value=&quot;1&quot;&#039;) != -1) {
				$.post(
					&quot;motivateCitizen.html?id=&quot;+citizen_id,
					{type:&quot;1&quot;,id:citizen_id},
					function (b) {
						var pos_start = b.indexOf(&#039;&lt;b id=&quot;foodLimit&quot;&gt;&#039;) + 18;
						var pos_end = b.indexOf(&#039;&lt;/b&gt;&#039;, pos_start);
						if (pos_start != -1 &amp;&amp; pos_end != -1) {
							var food_limit = parseInt(b.substr(pos_start, pos_end - pos_start));
							if (food_limit &lt; food_end) {
								citizen_id--;
								next_time = setTimeout(motivate, randtime);
							} else {
								alert(&#039;激勵完成，激勵前額度：&#039; + (food_end - 5) + &#039;；激勵後額度：&#039; + food_end);
							}
						} else {
							alert(&#039;未知的錯誤，請改用手動激勵&#039;);
						}
					}
				);
			} else {
				if (limit_times &gt; 0) {
					limit_times--;
					citizen_id--;
					next_time = setTimeout(motivate, randtime);
				} else {
					alert(&#039;已達嘗試次數上限，請改用手動激勵&#039;);
				}
			}
		}
	);
}

init();
							</pre>
						</div>
					</div>
					<div>
						<div class="heading_center">
							外匯監視
						</div>
						<div style="width:85%; margin:0 auto">
							<pre class="prettyprint">
var buyer = 32; // 買入
var seller = 0; // 售出
var amount = 0.01; //數量
var lower_than = 0.053; 
var next_time = 0;

function getData() {
	$.get(
		&quot;monetaryMarket.html&quot;,
		{buyerCurrencyId:buyer,sellerCurrencyId:seller},
		function (a) {
			var position_start = a.indexOf(&#039;1 TWD = &lt;b&gt;&#039;) + 11;
			var position_end = a.indexOf(&#039;&lt;/b&gt; Gold&#039;, position_start);
			var id_start = a.indexOf(&#039;&quot;id&quot;&#039;, position_end) + 12;
			var id_end = a.indexOf(&#039;&quot;/&gt;&#039;, id_start);
			var id = a.substr(id_start, id_end - id_start);
			if (position_start != -1 &amp;&amp; position_end != -1) {
				var lowest_price = parseFloat(a.substr(position_start, position_end - position_start));
				if (lowest_price &lt; lower_than) {
					$.post(
						&quot;monetaryMarket.html?buyerCurrencyId=&quot;+buyer+&quot;&amp;sellerCurrencyId=&quot;+seller+&quot;&quot;,
						{action:&quot;buy&quot;,id:id,ammount:amount},
						function (b) {
							alert(&#039;OK~&#039;);
						}
					);
				}
				next_time = setTimeout(getData, 10000);
			}
		}
	);
}
getData();
							</pre>
						</div>
					</div>
				</div>
<?php
	}
?>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
	</body>
</html>
<?php
	// 緊握不放
	
	/*
	var country_name = ['.Afghanistan', '.Albania', '.Algeria', '.Angola', '.Argentina', '.Armenia', '.Australia', '.Austria', '.Azerbaijan', '.Bangladesh', '.Belarus', '.Belgium', '.Benin', '.Bolivia', '.Bosnia-and-Herzegovina', '.Botswana', '.Brazil', '.Bulgaria', '.Burkina-Faso', '.Burundi', '.Cambodia', '.Cameroon', '.Canada', '.Central-African-Republic', '.Chad', '.Chile', '.China', '.Colombia', '.Congo', '.Costa-Rica', '.Croatia', '.Cuba', '.Czech-Republic', '.Denmark', '.Djibouti', '.Dominican-Republic', '.DR-of-the-Congo', '.Ecuador', '.Egypt', '.El-Salvador', '.Equatorial-Guinea', '.Eritrea', '.Estonia', '.Ethiopia', '.Finland', '.France', '.Gabon', '.Georgia', '.Germany', '.Ghana', '.Greece', '.Guatemala', '.Guinea', '.Guinea-Bissau', '.Guyana', '.Haiti', '.Honduras', '.Hungary', '.India', '.Indonesia', '.Iran', '.Iraq', '.Ireland', '.Israel', '.Italy', '.Ivory-Coast', '.Jamaica', '.Japan', '.Jordan', '.Kazakhstan', '.Kenya', '.Kyrgyzstan', '.Laos', '.Latvia', '.Lebanon', '.Lesotho', '.Liberia', '.Libya', '.Lithuania', '.Madagascar', '.Malawi', '.Malaysia', '.Mali', '.Mauritania', '.Mexico', '.Moldova', '.Mongolia', '.Montenegro', '.Morocco', '.Mozambique', '.Namibia', '.Nepal', '.Netherlands', '.New-Zealand', '.Nicaragua', '.Niger', '.Nigeria', '.Norway', '.Oman', '.Pakistan', '.Panama', '.Papua-New-Guinea', '.Paraguay', '.Peru', '.Philippines', '.Poland', '.Portugal', '.Puerto-Rico', '.Qatar', '.Republic-of-Macedonia', '.Romania', '.Russia', '.Rwanda', '.Saudi-Arabia', '.Senegal', '.Serbia', '.Sierra-Leone', '.Slovakia', '.Slovenia', '.Somalia', '.South-Africa', '.South-Korea', '.South-Sudan', '.Spain', '.Sri-Lanka', '.Sudan', '.Suriname', '.Swaziland', '.Sweden', '.Switzerland', '.Syria', '.Taiwan', '.Tajikistan', '.Tanzania', '.Thailand', '.The-Gambia', '.Togo', '.Tunisia', '.Turkey', '.Turkmenistan', '.Uganda', '.Ukraine', '.United-Arab-Emirates', '.United-Kingdom', '.Uruguay', '.USA', '.Uzbekistan', '.Venezuela', '.Vietnam', '.Western-Sahara', '.Yemen', '.Zambia', '.Zimbabwe'];
	var country_id = ['130', '55', '64', '65', '24', '131', '35', '48', '132', '59', '52', '40', '85', '121', '13', '84', '23', '10', '86', '109', '119', '66', '27', '88', '92', '45', '28', '46', '87', '144', '12', '147', '51', '50', '107', '126', '89', '122', '57', '152', '100', '90', '53', '68', '37', '4', '91', '133', '3', '69', '14', '127', '96', '97', '140', '153', '125', '8', '34', '29', '30', '112', '38', '33', '7', '67', '151', '58', '115', '128', '70', '134', '135', '20', '148', '102', '99', '71', '19', '104', '105', '42', '94', '95', '25', '150', '145', '47', '72', '73', '101', '120', '36', '139', '142', '93', '74', '43', '113', '41', '143', '146', '123', '44', '54', '1', '18', '149', '114', '15', '9', '2', '108', '62', '75', '11', '98', '49', '21', '106', '76', '31', '118', '5', '129', '77', '141', '103', '17', '39', '111', '32', '136', '78', '63', '117', '79', '80', '22', '137', '81', '16', '110', '6', '124', '26', '138', '56', '60', '116', '61', '82', '83'];
	var length = country_name.length;

	for (var i = 0; i < length; i++) {
		if ($(country_name[i]).html() != undefined) {
			$("[value='"+country_id[i]+"']").remove();
		}
	}

	$('#buy > [value="0"]').remove();
	$('#monetaryMarketView').submit();
	*/
	
	
	// Fight
	
	/*
	var wep = "1"; // 武器等級
	var is_food = true; // true:麵包 false:禮物
	var quality = "5"; // 麵包或禮物的等級
	var def_side = true; // true:防守方 false:進攻方
	var is_berserk = true; // true:berserk false:not
	var food = (is_food) ? "eat.html" : "gift.html";
	var side = ($("#fightButton2").val() == undefined) ? "side" : (def_side) ? "defender" : "attacker";
	var berserk = (is_berserk) ? "Berserk" : "undefined";
	var hit_quantity = (is_berserk) ? 50 : 10;
	var wep_stock = $('#Q'+wep+'WeaponStock').html();
	var next_time = 0, time = 1500, actualWellness = parseInt($("#actualHealth").html()), battleRoundId = $("#battleRoundId").val();

	function eat() {
		if (actualWellness >= hit_quantity) {
			next_time = setTimeout(fight, time);
		} else {
			$.post(
				food,
				{quality:quality},
				function (a) {
					actualWellness = parseInt(a.wellness);
					if (parseInt(a.wellness) >= hit_quantity) {
						next_time = setTimeout(fight, time);
					} else {
						if (is_food) {
							var tmp = "q"+quality+"FoodStorage";
							var check = (parseInt(a.foodLimit) > 0 &&  parseInt(a.tmp) > 0) ? true : false;
						} else {
							var tmp = "q"+quality+"GiftStorage";
							var check = (parseInt(a.giftLimit) > 0 &&  parseInt(a.tmp) > 0) ? true : false;
						}
						if (check) {
							next_time = setTimeout(eat, time);
						} else {
							tmp = (is_food) ? "麵包" : "禮物";
							alert('輸出結束，額度耗盡或'+tmp+'耗盡');
						}
					}
				}, "json"
			);
		}
	}

	function fight() {
		$.post(
			"fight.html",
			{weaponQuality:wep,battleRoundId:battleRoundId,side:side,value:berserk},
			function (b) {
				if (b.indexOf("You can't use weapons") == -1) {
					wep_stock -= (hit_quantity / 10);
					if (wep_stock < hit_quantity / 10) {
						alert('輸出結束，武器耗盡');
					} else {
						if (b.indexOf('Your armor absorbed the damage') == -1) {
							actualWellness -= hit_quantity;
						}
						next_time = setTimeout(eat, time);
					}
				} else {
					alert("You can't use weapons now.");
				}
			}
		);
	}
	eat();
	*/
	
	// Motivate
	
	/*
	var citizen_id = '', next_time = 0, food_end = parseInt($("#foodLimit").text()) + 5, limit_times = 25;

	function init() {
		var tmp = null;
		while (!tmp) {
			tmp = prompt("請輸入起始公民ID");
		}
		citizen_id = tmp;
		motivate();
	}

	function motivate() {
		$.get(
			'motivateCitizen.html?id='+citizen_id,
			function (a) {
				var randtime = Math.floor((Math.random() * 1000) + 2501);
				if (a.indexOf('name="type" value="1"') != -1) {
					$.post(
						"motivateCitizen.html?id="+citizen_id,
						{type:"1",id:citizen_id},
						function (b) {
							var pos_start = b.indexOf('<b id="foodLimit">') + 18;
							var pos_end = b.indexOf('</b>', pos_start);
							if (pos_start != -1 && pos_end != -1) {
								var food_limit = parseInt(b.substr(pos_start, pos_end - pos_start));
								if (food_limit < food_end) {
									citizen_id--;
									next_time = setTimeout(motivate, randtime);
								} else {
									alert('激勵完成，激勵前額度：' + (food_end - 5) + '；激勵後額度：' + food_end);
								}
							} else {
								alert('未知的錯誤，請改用手動激勵');
							}
						}
					);
				} else {
					if (limit_times > 0) {
						limit_times--;
						citizen_id--;
						next_time = setTimeout(motivate, randtime);
					} else {
						alert('已達嘗試次數上限，請改用手動激勵');
					}
				}
			}
		);
	}

	init();
	*/
	
	// monetaryMarket
	
	/*
	var buyer = 32; // 買入
	var seller = 0; // 售出
	var amount = 0.01; //數量
	var lower_than = 0.053; 
	var next_time = 0;

	function getData() {
		$.get(
			"monetaryMarket.html",
			{buyerCurrencyId:buyer,sellerCurrencyId:seller},
			function (a) {
				var position_start = a.indexOf('1 TWD = <b>') + 11;
				var position_end = a.indexOf('</b> Gold', position_start);
				var id_start = a.indexOf('"id"', position_end) + 12;
				var id_end = a.indexOf('"/>', id_start);
				var id = a.substr(id_start, id_end - id_start);
				if (position_start != -1 && position_end != -1) {
					var lowest_price = parseFloat(a.substr(position_start, position_end - position_start));
					if (lowest_price < lower_than) {
						$.post(
							"monetaryMarket.html?buyerCurrencyId="+buyer+"&sellerCurrencyId="+seller+"",
							{action:"buy",id:id,ammount:amount},
							function (b) {
								alert('OK~');
							}
						);
					}
					next_time = setTimeout(getData, 10000);
				}
			}
		);
	}
	getData();
	*/
?>