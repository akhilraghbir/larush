<?php if($recordData->user_type=='Employee'){ ?>
<div class="row">
    <?php if(!empty($tasks)){ 
        foreach($tasks as $task){
    ?>
    <div class="col-md-12 task_div_<?= $task['id'];?>">
        <div class="card <?= getPriorityDivClass($task['priority']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?= $task['task_title']; ?></h5>
                <p class="card-text"><?= $task['task_description']; ?></p>
                <a href="javascript:void()" onclick="markcompleted(<?= $task['id'];?>)" class="btn <?= getPriorityBtnClass($task['priority']); ?> btn-sm">Mark as Completed</a>
            </div>
        </div>
    </div>
    <?php } } ?>
</div>
<?php }else{ ?>
	<div class="row">
		<div class="col-xl-12">
			<div class="row">
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<div class="d-flex">
								<div class="flex-1 overflow-hidden">
									<p class="text-truncate font-size-14 mb-2">Employees</p>
									<h4 class="mb-0"><?= $employees; ?></h4>
								</div>
								<div class="text-primary ms-auto">
									<i class=" ri-user-fill font-size-24"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<div class="d-flex">
								<div class="flex-1 overflow-hidden">
									<p class="text-truncate font-size-14 mb-2">Suppliers</p>
									<h4 class="mb-0"><?= $suppliers; ?></h4>
								</div>
								<div class="text-primary ms-auto">
									<i class="ri-store-2-line font-size-24"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<div class="d-flex">
								<div class="flex-1 overflow-hidden">
									<p class="text-truncate font-size-14 mb-2">Buyers</p>
									<h4 class="mb-0"><?= $buyers; ?></h4>
								</div>
								<div class="text-primary ms-auto">
									<i class="ri-briefcase-4-line font-size-24"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card">
						<div class="card-body">
							<div class="d-flex">
								<div class="flex-1 overflow-hidden">
									<p class="text-truncate font-size-14 mb-2">Products</p>
									<h4 class="mb-0"><?= $products; ?></h4>
								</div>
								<div class="text-primary ms-auto">
									<i class="ri-briefcase-4-line font-size-24"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4">
			<div class="card">
				<div class="card-body">
					<!-- <h4 class="card-title mb-4">Sales Vs Purchases</h4> -->
					<div class="float-end">
						<input type="text" class="form-control daterange" autocomplete="off" onchange="getSalesVsPurchases()"  id="daterange-sales-purchases">
					</div>
					<div id="column_chart_datalabel" class="apex-charts" dir="ltr"></div>
				</div>
			</div><!--end card-->
		</div>
		<div class="col-xl-4">
			<div class="card">
				<div class="card-body">
					<div class="float-end">
						<input type="text" class="form-control daterange" autocomplete="off" onchange="getSalesChart()"  id="daterange">
					</div>
					<h4 class="card-title mb-4">Sales (Fe & Non Fe)</h4>
					<div id="donut-chart" class="apex-charts"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="card">
				<div class="card-body">
					<div class="float-end">
						<input type="text" class="form-control daterange" autocomplete="off" onchange="getPurchaseChart()"  id="daterange">
					</div>
					<h4 class="card-title mb-4">Purchase (Fe & Non Fe)</h4>
					<div id="purchase-donut-chart" class="apex-charts"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mb-4">Employee login time records</h4>
					<div class="table-responsive">
						<div class="table-responsive tasks dataGridTable">
                            <table id="todayAttendance" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
								<thead>
									<tr>
										<th>#</th>
										<th>Employee Name</th>
										<th>Login Time</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<div class="float-end">
						<input type="text" class="form-control daterange" autocomplete="off" onchange="getExpensesChart()"  id="daterange-expenses">
					</div>
					<h4 class="card-title mb-4">Company Expenses</h4>
					<div id="bar_chart" class="apex-charts" dir="ltr"></div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<script src="<?= base_url(); ?>assets/backend/libs/apexcharts/apexcharts.min.js"></script>
<script>
function markcompleted(id){
	if(id!=''){
		$.ajax({
			url: '<?php echo base_url(); ?>administrator/dashboard/updateTask',
			type: 'POST',
			data: {
				"tid": id,
			},
			success: function(data) {
				result = JSON.parse(data);
				var msg = result.message;
				if (result.error == '0') {
					toastr['success'](msg);
					$(".task_div_"+result.id).remove();
				} else {
					toastr['warning'](msg);
				}
			},
			error: function(e) {
				toastr['warning'](e.message);
			}
		});
	}
}
function getSalesChart(){
	var date = $("#daterange").val();
	$.ajax({
		url: '<?php echo base_url(); ?>administrator/Dashboard/getSalesReport',
		type: 'POST',
		data: {
			"date": date,
		},
		beforeSend:function(){
			//renderGraph([0,0]);
		},
		success: function(data) {
			result = JSON.parse(data);
			if (result.error == 0) {
				salesChart(result.data);
			} else {
				toastr['warning']('Something went wrong');
			}
		},
		error: function(e) {
			toastr['warning'](e.message);
		}
	});
}

function getPurchaseChart(){
	var date = $("#daterange").val();
	$.ajax({
		url: '<?php echo base_url(); ?>administrator/Dashboard/getPurchaseReport',
		type: 'POST',
		data: {
			"date": date,
		},
		beforeSend:function(){
		},
		success: function(data) {
			result = JSON.parse(data);
			if (result.error == 0) {
				purchaseChart(result.data);
			} else {
				toastr['warning']('Something went wrong');
			}
		},
		error: function(e) {
			toastr['warning'](e.message);
		}
	});
}

function getSalesVsPurchases(){
	var date = $("#daterange-sales-purchases").val();
	$.ajax({
		url: '<?php echo base_url(); ?>administrator/Dashboard/getSalesVsPurchases',
		type: 'POST',
		data: {
			"date": date,
		},
		beforeSend:function(){
			//renderGraph([0,0]);
		},
		success: function(data) {
			result = JSON.parse(data);
			if (result.error == 0) {
				SalesVsPurchases(result.data);
			} else {
				toastr['warning']('Something went wrong');
			}
		},
		error: function(e) {
			toastr['warning'](e.message);
		}
	});
}

function salesChart(data){
	var options ={
		chart: {
			height: 320,
			type: "donut"
		},
		series: data,
		labels: ["Ferrours Qty", "Non Ferrous Qty"],
		colors: ["#1cbb8c", "#5664d2"],
		legend: {
			show: !0,
			position: "bottom",
			horizontalAlign: "center",
			verticalAlign: "middle",
			floating: !1,
			fontSize: "14px",
			offsetX: 0,
			offsetY: 5
		},
		responsive: [{
			breakpoint: 600,
			options: {
				chart: {
					height: 240
				},
				legend: {
					show: !1
				}
			}
		}]
	};
	chart = new ApexCharts(document.querySelector("#donut-chart"), options);
	chart.render();
}

function purchaseChart(data){
	var options ={
		chart: {
			height: 320,
			type: "donut"
		},
		series: data,
		labels: ["Ferrours Qty", "Non Ferrous Qty"],
		colors: ["#0984db", "#edb10c"],
		legend: {
			show: !0,
			position: "bottom",
			horizontalAlign: "center",
			verticalAlign: "middle",
			floating: !1,
			fontSize: "14px",
			offsetX: 0,
			offsetY: 5
		},
		responsive: [{
			breakpoint: 600,
			options: {
				chart: {
					height: 240
				},
				legend: {
					show: !1
				}
			}
		}]
	};
	chart = new ApexCharts(document.querySelector("#purchase-donut-chart"), options);
	chart.render();
}

function SalesVsPurchases(data){
	var options = 
			{	
				chart: {
					height: 350,
					type: "bar",
					toolbar: {
						show: !1
					}
				},
				plotOptions: {
					bar: {
						dataLabels: {
							position: "top"
						}
					}
				},
				dataLabels: {
					enabled: !0,
					formatter: function(e) {
						return e + "$"
					},
					offsetY: -20,
					style: {
						fontSize: "12px",
						colors: ["#000"]
					}
				},
				series: [{
					name: "Value",
					data: data
				}],
				colors: ["#0586ad"],
				grid: {
					borderColor: "#f1f1f1",
					padding: {
						bottom: 10
					}
				},
				xaxis: {
					categories: ["Sales", "Purchases"],
					position: "top",
					labels: {
						offsetY: -18
					},
					axisBorder: {
						show: !1
					},
					axisTicks: {
						show: !1
					},
					crosshairs: {
						fill: {
							type: "gradient",
							gradient: {
								colorFrom: "#D8E3F0",
								colorTo: "#BED1E6",
								stops: [0, 100],
								opacityFrom: .4,
								opacityTo: .5
							}
						}
					},
					tooltip: {
						enabled: !0,
						offsetY: -35
					}
				},
				fill: {
					gradient: {
						shade: "light",
						type: "horizontal",
						shadeIntensity: .25,
						gradientToColors: void 0,
						inverseColors: !0,
						opacityFrom: 1,
						opacityTo: 1,
						stops: [50, 0, 100, 100]
					}
				},
				yaxis: {
					axisBorder: {
						show: !1
					},
					axisTicks: {
						show: !1
					},
					labels: {
						show: !1,
						formatter: function(e) {
							return e + "$"
						}
					}
				},
				title: {
					text: "Sales Vs Purchase",
					floating: !0,
					offsetY: 320,
					align: "center",
					style: {
						color: "#000"
					}
				},
				legend: {
					offsetY: 7
				}
			};
    options = (chart = new ApexCharts(document.querySelector("#column_chart_datalabel"), options)).render()
}

function getdata() {
	$('#todayAttendance').DataTable({
		"destroy": true,
		"responsive": false,
		"processing": true,
		"serverSide": true,
		"order": [
			[2, "desc"]
		],
		"ajax": {
			"url": "<?php echo base_url(); ?>administrator/Dashboard/ajaxListingAttendance",
			"type": 'POST',
			'data': {}
		},
		language: {
			paginate: {
				previous: "<i class='mdi mdi-chevron-left'>",
				next: "<i class='mdi mdi-chevron-right'>"
			}
		},
		drawCallback: function() {
			$(".dataTables_paginate > .pagination").addClass("pagination-rounded")
		}
	});
}
getdata();

function getExpensesChart(){
	var date = $("#daterange-expenses").val();
	$.ajax({
		url: '<?php echo base_url(); ?>administrator/Dashboard/getExpenses',
		type: 'POST',
		data: {
			"date": date,
		},
		beforeSend:function(){
			//renderGraph([0,0]);
		},
		success: function(data) {
			result = JSON.parse(data);
			if (result.error == 0) {
				expensesChart(result.amount,result.category);
			} else {
				toastr['warning']('Something went wrong');
			}
		},
		error: function(e) {
			toastr['warning'](e.message);
		}
	});
}

function expensesChart(data,labels){
	
    var options =
		{    
		chart: {
            height: 350,
            type: "bar",
            toolbar: {
                show: !1
            }
        },
        plotOptions: {
            bar: {
                horizontal: !0
            }
        },
        dataLabels: {
            enabled: !1
        },
        series: [{
            data: data
        }],
        colors: ["#1cbb8c"],
        grid: {
            borderColor: "#f1f1f1",
            padding: {
                bottom: 5
            }
        },
        xaxis: {
            categories: labels
        },
        legend: {
            offsetY: 5
        }
    };
    options = (chart = new ApexCharts(document.querySelector("#bar_chart"), options)).render();
}
</script>