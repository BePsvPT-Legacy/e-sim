<?php
	if (!isset($prefix)) {
		$prefix = "../../";
	}
	require_once $prefix."config/database_connect.php";
	
	$ico_link = "tools/images/icon.ico";
	$css_link = array();
	$js_link = array();
	display_head($prefix, "利潤計算機", $ico_link, $css_link, $js_link);
?>
	<body>
		<div id="id_wrapper">
			<div id="id_header">
<?php require_once $prefix."config_customized/navigation.php"; ?>
			</div>
			<div id="id_content">
				<div class="pure-g">
					<div class="pure-u-3-5">
						<div class="heading_center heading_highlight">
							<h3 id="error_message"></h3>
						</div>
						<div>
							<table class="pure-table pure-table-bordered">
								<thead>
									<th>#</th>
									<th>技能等級</th>
									<th>薪資</th>
									<th>生產力</th>
									<th>產值</th>
									<th>利潤</th>
								</thead>
								<tbody id="profit_data">
								</tbody>
							</table>
						</div>
					</div>
					<div class="pure-u-2-5">
						<div style="width:312px;margin:0 auto;">
							<div>
								<h4>自動化匯入</h4>
							</div>
							<form name="company_data" id="company_data" action="#" method="POST" class="pure-form pure-form-aligned" onChange="company_data_change();" onSubmit="return false;">
								<fieldset>
									<div class="pure-control-group" style="text-align:left;">
										<label for="server" style="width:80px;text-align:left;">伺 服 器：</label>
										<select name="server" id="server" style="width:196px;">
											<option value="1">Primera</option>
											<option value="2" selected>Secura</option>
											<option value="3">Suna</option>
										</select>
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<label for="company_id" style="width:80px;text-align:left;">工廠 I D：</label>
										<input type="text" name="company_id" id="company_id" style="width:196px;">
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<input type="checkbox" name="ImportStaffSkill" id="ImportStaffSkill" style="width:18px;height:18px">
										<label for="ImportStaffSkill" style="width:192px;text-align:left;">匯入工作技能等級</label>
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<input type="checkbox" name="ImportStaffSalary" id="ImportStaffSalary" style="width:18px;height:18px">
										<label for="ImportStaffSalary" style="width:192px;text-align:left;">匯入員工薪資</label>
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<label for="currency_rate" style="width:80px;text-align:left;">匯　　率：</label>
										<input type="text" name="currency_rate" id="currency_rate" style="width:196px;">
									</div>
									<div class="pure-controls">
										<button type="submit" id="submit" class="pure-button pure-button-primary">送出</button>
									</div>
								</fieldset>
							</form>
							<hr>
							<form name="company_info" id="company_info" action="#" method="POST" class="pure-form pure-form-aligned" onChange="update();">
								<fieldset>
									<div class="pure-control-group" style="text-align:left;">
										<label for="company_type" style="width:80px;text-align:left;">工廠類型：</label>
										<select name="company_type" id="company_type" style="width:196px;">
											<optgroup label="原料廠" id="company_type_material">
												<option value="1">　 鐵 　</option>
												<option value="2">穀　　物</option>
												<option value="3">石　　油</option>
												<option value="4">石　　材</option>
												<option value="5">木　　材</option>
												<option value="6">鑽　　石</option>
											</optgroup>
											<optgroup label="加工廠" id="company_type_manufacture">
												<option value="7">武　　器</option>
												<option value="8">房　　屋</option>
												<option value="9">禮　　物</option>
												<option value="10">食　　物</option>
												<option value="11">機　　票</option>
												<option value="12">防禦系統</option>
												<option value="13">醫　　院</option>
												<option value="14">地　　產</option>
											</optgroup>
										</select>
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<label for="company_quality" style="width:80px;text-align:left;">工廠等級：</label>
										<select name="company_quality" id="company_quality" style="width:196px;">
											<option value="1">Q1</option>
											<option value="2">Q2</option>
											<option value="3">Q3</option>
											<option value="4">Q4</option>
											<option value="5">Q5</option>
										</select>
									</div>
									<hr>
									<div class="pure-control-group" style="text-align:left;">
										<input type="checkbox" name="ControlCapital" id="ControlCapital" style="width:18px;height:18px" value="true">
										<label for="ControlCapital" style="width:192px;text-align:left;">國家首都未被佔領</label>
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<input type="checkbox" name="ControlHighMaterial" id="ControlHighMaterial" style="width:18px;height:18px" value="true">
										<label for="ControlHighMaterial" style="width:192px;text-align:left;">國家擁有相對應的高資源地</label>
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<input type="checkbox" name="inHighResource" id="inHighResource" style="width:18px;height:18px" value="true">
										<label for="inHighResource" style="width:192px;text-align:left;">工廠位於高資源地</label>
									</div>
									<hr>
									<div class="pure-control-group" style="text-align:left;">
										<label for="MaterialPrice" style="width:80px;text-align:left;">原料成本：</label>
										<input type="text" name="MaterialPrice" id="MaterialPrice" style="width:196px;">
									</div>
									<div class="pure-control-group" style="text-align:left;">
										<label for="ProductPrice" style="width:80px;text-align:left;">產品售價：</label>
										<input type="text" name="ProductPrice" id="ProductPrice" style="width:196px;">
									</div>
									<hr>
									<div class="pure-control-group">
										<table>
											<thead>
												<th style="width:16px;">#</th>
												<th>技能等級</th>
												<th>薪資</th>
												<th></th>
											</thead>
											<tbody id="staff_data">
											</tbody>
										</table>
										<br>
										<div>
											<button type="button" class="pure-button pure-button-primary" onClick="add_staff();">新增</button>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
				<div id="go_to_top">
					<img src="<?php echo $prefix; ?>images/go_to_top.png">
				</div>
			</div>
<?php display_footer(); ?>
		<script>
			var company_type = $('#company_type').val(), company_quality = $('#company_quality').val(), total_staff = 0;
			var material_price = 0, product_price = 0, controlcapital = 0.75, controlhighmaterial = 1, inhighresource = 0.75; 
			var currency_rate = 0, material_need = [[2, 4, 6, 8, 10], [300, 600, 900, 1200, 1500], [2, 4, 6, 8, 10], [1, 2, 3, 4, 5], [4, 8, 12, 16, 20], [300, 600, 900, 1200, 1500], [300, 600, 900, 1200, 1500], [600, 1200, 1800, 2400, 3000]];
			
			function update() {
				company_type = parseInt($('#company_type').val());
				company_quality = $('#company_quality').val();
				company_change();
				controlcapital = ($('#ControlCapital').is(":checked")) ? 1 : 0.75;
				controlhighmaterial = ($('#ControlHighMaterial').is(":checked")) ? 1.25 : 1;
				inhighresource = ($('#inHighResource').is(":checked")) ? 1 : 0.75;
				material_price = ($('#MaterialPrice').val()) ? $('#MaterialPrice').val() : 0;
				product_price = ($('#ProductPrice').val()) ? $('#ProductPrice').val() : 0;
				calc();
			}
			
			function calc() {
				$('#profit_data').empty();
				var actually = 0, total = [];
				total['salary'] = total['p'] = total['goods'] = total['profit'] = 0;
				for (var i = 1; i <= total_staff; i++) {
					if ($('#staff_skill_'+i).val() && $('#staff_salary_'+i).val()) {
						var N = 0.5;
						if (actually <= 10) {
							N = 1.0 + (10 - actually) * 0.05;
						} else if (actually <= 20) {
							N = 1.0 - (actually - 10) * 0.03;
						} else if (actually <= 30) {
							N = 0.7 - (actually - 20) * 0.02;
						}
						var p = 10 * (4 + parseFloat($('#staff_skill_'+i).val())) * N * controlcapital;
						if (company_type >= 1 && company_type <= 6) {
							p = p * (1 + (parseInt(company_quality) - 1) * 0.2) * inhighresource;
						} else {
							p *= controlhighmaterial;
						}
						var goods = 0, profit = 0;
						if (company_type >= 1 && company_type <= 6) {
							goods = p;
							profit = (currency_rate != 0 && $('#ImportStaffSalary').is(":checked")) ? (product_price * goods - parseFloat($('#staff_salary_'+i).val()) * currency_rate) : (product_price * goods - parseFloat($('#staff_salary_'+i).val()));
						} else {
							var material_quantity = material_need[company_type-7][parseInt(company_quality)-1];
							goods = p / material_quantity;
							profit = (currency_rate != 0 && $('#ImportStaffSalary').is(":checked")) ? (product_price * goods - parseFloat($('#staff_salary_'+i).val()) * currency_rate - material_price * material_quantity * goods) : (product_price * goods - parseFloat($('#staff_salary_'+i).val()) - material_price * material_quantity * goods);
						}
						var content = '<tr><td>'+i+'</td>';
						content += '<td>'+$('#staff_skill_'+i).val()+'</td>';
						content += '<td>'+$('#staff_salary_'+i).val()+'</td>';
						content += '<td>'+p.toFixed(2)+'</td>';
						content += '<td>'+goods.toFixed(3)+'</td>';
						content += '<td>'+profit.toFixed(4)+'</td>';
						$(content).appendTo("#profit_data");
						total['salary'] += parseFloat($('#staff_salary_'+i).val());
						total['p'] += p;
						total['goods'] += goods;
						total['profit'] += profit;
						actually++;
					}
				}
				var content = '<tr><td>-</td>';
				content += '<td>-</td>';
				content += '<td>'+total['salary']+'</td>';
				content += '<td>'+total['p'].toFixed(2)+'</td>';
				content += '<td>'+total['goods'].toFixed(3)+'</td>';
				content += '<td>'+total['profit'].toFixed(4)+'</td>';
				$(content).appendTo("#profit_data");
			}
			
			function company_change() {
				if (company_type >= 1 && company_type <= 6) {
					$('#ControlHighMaterial').prop("disabled", true);
					$('#inHighResource').prop("disabled", false);
					$('#MaterialPrice').prop("disabled", true);
				} else {
					$('#ControlHighMaterial').prop("disabled", false);
					$('#inHighResource').prop("disabled", true);
					$('#MaterialPrice').prop("disabled", false);
				}
			}
			
			function add_staff() {
				total_staff++;
				var content = '<tr id="staff_'+total_staff+'"><td id="staff_number_'+total_staff+'">'+total_staff+'</td>';
				content += '<td><input type="text" id="staff_skill_'+total_staff+'" style="width:112px;"></td>';
				content += '<td><input type="text" id="staff_salary_'+total_staff+'" style="width:112px;"></td>';
				content += '<td><button type="button" id="staff_remove_'+total_staff+'" class="pure-button pure-button-primary" onClick="delete_staff('+total_staff+');">移除</button></td></tr>';
				$(content).appendTo("#staff_data");
				check_staff();
			}
			
			function delete_staff(remove_id) {
				if (total_staff > 1) {
					var next = remove_id + 1;
					$('#staff_'+remove_id).remove();
					while (remove_id < total_staff) {
						$("#staff_number_"+next).text(remove_id);
						$("#staff_remove_"+next).attr("onClick", 'delete_staff('+remove_id+');');
						$("#staff_"+next).attr("id", "staff_"+remove_id);
						$("#staff_number_"+next).attr("id", "staff_number_"+remove_id);
						$("#staff_skill_"+next).attr("id", "staff_skill_"+remove_id);
						$("#staff_salary_"+next).attr("id", "staff_salary_"+remove_id);
						$("#staff_remove_"+next).attr("id", "staff_remove_"+remove_id);
						remove_id++;
						next++;
					}
					total_staff--;
					check_staff();
				}
			}
			
			function check_staff() {
				if (total_staff == 1) {
					$('#staff_remove_1').prop("disabled", true);
				} else {
					$('#staff_remove_1').prop("disabled", false);
				}
			}
			
			function company_data_change() {
				var check = new RegExp(/^\d{1,5}$/);
				if (check.test($('#company_id').val())) {
					company_data_on();
				} else {
					company_data_off();
				}
			}
			
			function company_data_on() {
				$('#ImportStaffSkill').prop("disabled", false);
				$('#ImportStaffSalary').prop("disabled", false);
				if (!$('#ImportStaffSalary').is(":checked")) {
					$('#currency_rate').prop("disabled", true);
				} else {
					$('#currency_rate').prop("disabled", false);
				}
			}
			
			function company_data_off() {
				$('#ImportStaffSkill').prop("disabled", true);
				$('#ImportStaffSalary').prop("disabled", true);
				$('#currency_rate').prop("disabled", true);
			}
			
			$(document).ready(function() {
				company_data_off();
				company_data_change();
				company_change();
				add_staff();
				
				$("#company_data").submit(function(){
					$('#error_message').empty();
					$('#profit_data').empty();
					$('#staff_skill_1').val('');
					$('#staff_salary_1').val('');
					
					var check_company_id = new RegExp(/^\d{1,5}$/);
					if (check_company_id.test($('#company_id').val())) {
						$.get(
							'http://crux.coder.tw/freedom/tools/api/Company.php',
							{companyid:$('#company_id').val(),server:$('#server').val()},
							function (data) {
								if (typeof data.error != 'undefined') {
									$('#error_message').text(data.error);
								} else {
									var totalstaff = parseInt(data.totalstaff);
									if (totalstaff == 0) {
										$('#error_message').text('There is no worker in the company');
									} else {
										var skill = salary = false;
										while (total_staff > 1) {
											delete_staff(total_staff);
										}
										currency_rate = ($('#currency_rate').val()) ? $('#currency_rate').val() : 0;
										if ($('#ImportStaffSkill').is(":checked")) {
											skill = true;
										}
										if ($('#ImportStaffSalary').is(":checked")) {
											salary = true;
										}
										var i = 1;
										for (var key in data.workers) {
											if (data.workers.hasOwnProperty(key)) {
												var server = ($('#server').val() == 2) ? 'secura' : (($('#server').val() == 1) ? 'primera': 'suna');
												var content = '<tr><td>'+(i)+'</td>';
												if (skill) {
													$('#staff_skill_'+total_staff).val(data.workers[key].skilllevel);
													content += '<td>'+data.workers[key].skilllevel+'</td>';
												} else {
													content += '<td></td>';
												}
												if (salary) {
													$('#staff_salary_'+total_staff).val(data.workers[key].salary);
													content += '<td>'+data.workers[key].salary+'</td>';
												} else {
													content += '<td></td>';
												}
												content += '<td></td>';
												content += '<td></td>';
												content += '<td></td></tr>';
												if (skill || salary) {
													add_staff();
													i++
												}
												$(content).appendTo("#profit_data");
											}
										}
									}
								}
							}, "json"
						);
					} else {
						$('#error_message').text('Wrong company id');
					}
					return false;
				});
			});
		</script>
	</body>
</html>
<?php
	
	// jQuery backup
	/*
		var company_type = $('#company_type').val(), company_quality = $('#company_quality').val(), total_staff = 0;
		var material_price = 0, product_price = 0, controlcapital = 0.75, controlhighmaterial = 1, inhighresource = 0.75; 
		var currency_rate = 0;
		
		function update() {
			company_type = $('#company_type').val();
			company_quality = $('#company_quality').val();
			company_change();
			controlcapital = ($('#ControlCapital').is(":checked")) ? 1 : 0.75;
			controlhighmaterial = ($('#ControlHighMaterial').is(":checked")) ? 1.25 : 1;
			inhighresource = ($('#inHighResource').is(":checked")) ? 1 : 0.75;
			material_price = ($('#MaterialPrice').val()) ? $('#MaterialPrice').val() : 0;
			product_price = ($('#ProductPrice').val()) ? $('#ProductPrice').val() : 0;
			calc();
		}
		
		function calc() {
			$('#profit_data').empty();
			var actually = 0, total = [];
			total['salary'] = total['p'] = total['goods'] = total['profit'] = 0;
			for (var i = 1; i <= total_staff; i++) {
				if ($('#staff_skill_'+i).val() && $('#staff_salary_'+i).val()) {
					var N = 0.5;
					if (actually <= 10) {
						N = 1.0 + (10 - actually) * 0.05;
					} else if (actually <= 20) {
						N = 1.0 - (actually - 10) * 0.03;
					} else if (actually <= 30) {
						N = 0.7 - (actually - 20) * 0.02;
					}
					var p = 10 * (4 + parseFloat($('#staff_skill_'+i).val())) * N * controlcapital;
					if (company_type >= 1 && company_type <= 5) {
						p = p * (1 + (parseInt(company_quality) - 1) * 0.2) * inhighresource;
					} else {
						p *= controlhighmaterial;
					}
					var goods = 0, profit = 0;
					if (company_type >= 1 && company_type <= 5) {
						goods = p;
						profit = (currency_rate != 0 && $('#ImportStaffSalary').is(":checked")) ? (product_price * goods - parseFloat($('#staff_salary_'+i).val()) * currency_rate) : (product_price * goods - parseFloat($('#staff_salary_'+i).val()));
					} else {
						goods = p / (parseInt(company_quality) * 2);
						profit = (currency_rate != 0 && $('#ImportStaffSalary').is(":checked")) ? (product_price * goods - parseFloat($('#staff_salary_'+i).val()) * currency_rate - material_price * parseInt(company_quality) * 2 * goods) : (product_price * goods - parseFloat($('#staff_salary_'+i).val()) - material_price * parseInt(company_quality) * 2 * goods);
					}
					var content = '<tr><td>'+i+'</td>';
					content += '<td>'+$('#staff_skill_'+i).val()+'</td>';
					content += '<td>'+$('#staff_salary_'+i).val()+'</td>';
					content += '<td>'+p.toFixed(2)+'</td>';
					content += '<td>'+goods.toFixed(3)+'</td>';
					content += '<td>'+profit.toFixed(4)+'</td>';
					$(content).appendTo("#profit_data");
					total['salary'] += parseFloat($('#staff_salary_'+i).val());
					total['p'] += p;
					total['goods'] += goods;
					total['profit'] += profit;
					actually++;
				}
			}
			var content = '<tr><td>-</td>';
			content += '<td>-</td>';
			content += '<td>'+total['salary']+'</td>';
			content += '<td>'+total['p'].toFixed(2)+'</td>';
			content += '<td>'+total['goods'].toFixed(3)+'</td>';
			content += '<td>'+total['profit'].toFixed(4)+'</td>';
			$(content).appendTo("#profit_data");
		}
		
		function company_change() {
			if (company_type >= 1 && company_type <= 5) {
				$('#ControlHighMaterial').prop("disabled", true);
				$('#inHighResource').prop("disabled", false);
				$('#MaterialPrice').prop("disabled", true);
			} else {
				$('#ControlHighMaterial').prop("disabled", false);
				$('#inHighResource').prop("disabled", true);
				$('#MaterialPrice').prop("disabled", false);
			}
		}
		
		function add_staff() {
			total_staff++;
			var content = '<tr id="staff_'+total_staff+'"><td id="staff_number_'+total_staff+'">'+total_staff+'</td>';
			content += '<td><input type="text" id="staff_skill_'+total_staff+'" style="width:112px;"></td>';
			content += '<td><input type="text" id="staff_salary_'+total_staff+'" style="width:112px;"></td>';
			content += '<td><button type="button" id="staff_remove_'+total_staff+'" class="pure-button pure-button-primary" onClick="delete_staff('+total_staff+');">移除</button></td></tr>';
			$(content).appendTo("#staff_data");
			check_staff();
		}
		
		function delete_staff(remove_id) {
			if (total_staff > 1) {
				var next = remove_id + 1;
				$('#staff_'+remove_id).remove();
				while (remove_id < total_staff) {
					$("#staff_number_"+next).text(remove_id);
					$("#staff_remove_"+next).attr("onClick", 'delete_staff('+remove_id+');');
					$("#staff_"+next).attr("id", "staff_"+remove_id);
					$("#staff_number_"+next).attr("id", "staff_number_"+remove_id);
					$("#staff_skill_"+next).attr("id", "staff_skill_"+remove_id);
					$("#staff_salary_"+next).attr("id", "staff_salary_"+remove_id);
					$("#staff_remove_"+next).attr("id", "staff_remove_"+remove_id);
					remove_id++;
					next++;
				}
				total_staff--;
				check_staff();
			}
		}
		
		function check_staff() {
			if (total_staff == 1) {
				$('#staff_remove_1').prop("disabled", true);
			} else {
				$('#staff_remove_1').prop("disabled", false);
			}
		}
		
		function company_data_change() {
			var check = new RegExp(/^\d{1,5}$/);
			if (check.test($('#company_id').val())) {
				company_data_on();
			} else {
				company_data_off();
			}
		}
		
		function company_data_on() {
			$('#ImportStaffSkill').prop("disabled", false);
			$('#ImportStaffSalary').prop("disabled", false);
			if (!$('#ImportStaffSalary').is(":checked")) {
				$('#currency_rate').prop("disabled", true);
			} else {
				$('#currency_rate').prop("disabled", false);
			}
		}
		
		function company_data_off() {
			$('#ImportStaffSkill').prop("disabled", true);
			$('#ImportStaffSalary').prop("disabled", true);
			$('#currency_rate').prop("disabled", true);
		}
		
		$(document).ready(function() {
			company_data_off();
			company_data_change();
			company_change();
			add_staff();
			
			$("#company_data").submit(function(){
				$('#error_message').empty();
				$('#profit_data').empty();
				$('#staff_skill_1').val('');
				$('#staff_salary_1').val('');
				
				var check_company_id = new RegExp(/^\d{1,5}$/);
				if (check_company_id.test($('#company_id').val())) {
					$.get(
						'http://crux.coder.tw/freedom/tools/api/Company.php',
						{companyid:$('#company_id').val(),server:$('#server').val()},
						function (data) {
							if (typeof data.error != 'undefined') {
								$('#error_message').text(data.error);
							} else {
								var totalstaff = parseInt(data.totalstaff);
								if (totalstaff == 0) {
									$('#error_message').text('There is no worker in the company');
								} else {
									var skill = salary = false;
									while (total_staff > 1) {
										delete_staff(total_staff);
									}
									currency_rate = ($('#currency_rate').val()) ? $('#currency_rate').val() : 0;
									if ($('#ImportStaffSkill').is(":checked")) {
										skill = true;
									}
									if ($('#ImportStaffSalary').is(":checked")) {
										salary = true;
									}
									var i = 1;
									for (var key in data.workers) {
										if (data.workers.hasOwnProperty(key)) {
											var server = ($('#server').val() == 2) ? 'secura' : (($('#server').val() == 1) ? 'primera': 'suna');
											var content = '<tr><td>'+(i)+'</td>';
											if (skill) {
												$('#staff_skill_'+total_staff).val(data.workers[key].skilllevel);
												content += '<td>'+data.workers[key].skilllevel+'</td>';
											} else {
												content += '<td></td>';
											}
											if (salary) {
												$('#staff_salary_'+total_staff).val(data.workers[key].salary);
												content += '<td>'+data.workers[key].salary+'</td>';
											} else {
												content += '<td></td>';
											}
											content += '<td></td>';
											content += '<td></td>';
											content += '<td></td></tr>';
											if (skill || salary) {
												add_staff();
												i++
											}
											$(content).appendTo("#profit_data");
										}
									}
								}
							}
						}, "json"
					);
				} else {
					$('#error_message').text('Wrong company id');
				}
				return false;
			});
		});
	*/
?>