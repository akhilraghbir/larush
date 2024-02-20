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
	<div class="col-xl-8">
		<div class="row">
			<div class="col-md-4">
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
			<div class="col-md-4">
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
			<div class="col-md-4">
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
		</div>
		<!-- end row -->

		<div class="card">
			<div class="card-body">
				<div class="float-end d-none d-md-inline-block">
					<div class="btn-group mb-2">
						<button type="button" class="btn btn-sm btn-light">Today</button>
						<button type="button" class="btn btn-sm btn-light active">Weekly</button>
						<button type="button" class="btn btn-sm btn-light">Monthly</button>
					</div>
				</div>
				<h4 class="card-title mb-4">Revenue Analytics</h4>
				<div>
					<div id="line-column-chart" class="apex-charts" dir="ltr"></div>
				</div>
			</div>

			<div class="card-body border-top text-center">
				<div class="row">
					<div class="col-sm-4">
						<div class="d-inline-flex">
							<h5 class="me-2">$12,253</h5>
							<div class="text-success">
								<i class="mdi mdi-menu-up font-size-14"> </i>2.2 %
							</div>
						</div>
						<p class="text-muted text-truncate mb-0">This month</p>
					</div>

					<div class="col-sm-4">
						<div class="mt-4 mt-sm-0">
							<p class="mb-2 text-muted text-truncate"><i class="mdi mdi-circle text-primary font-size-10 me-1"></i> This Year :</p>
							<div class="d-inline-flex">
								<h5 class="mb-0 me-2">$ 34,254</h5>
								<div class="text-success">
									<i class="mdi mdi-menu-up font-size-14"> </i>2.1 %
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="mt-4 mt-sm-0">
							<p class="mb-2 text-muted text-truncate"><i class="mdi mdi-circle text-success font-size-10 me-1"></i> Previous Year :</p>
							<div class="d-inline-flex">
								<h5 class="mb-0">$ 32,695</h5>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card">
			<div class="card-body">
				<div class="float-end">
					<select class="form-select form-select-sm">
						<option selected>Apr</option>
						<option value="1">Mar</option>
						<option value="2">Feb</option>
						<option value="3">Jan</option>
					</select>
				</div>
				<h4 class="card-title mb-4">Sales Analytics</h4>

				<div id="donut-chart" class="apex-charts"></div>

				<div class="row">
					<div class="col-4">
						<div class="text-center mt-4">
							<p class="mb-2 text-truncate"><i class="mdi mdi-circle text-primary font-size-10 me-1"></i> Product A</p>
							<h5>42 %</h5>
						</div>
					</div>
					<div class="col-4">
						<div class="text-center mt-4">
							<p class="mb-2 text-truncate"><i class="mdi mdi-circle text-success font-size-10 me-1"></i> Product B</p>
							<h5>26 %</h5>
						</div>
					</div>
					<div class="col-4">
						<div class="text-center mt-4">
							<p class="mb-2 text-truncate"><i class="mdi mdi-circle text-warning font-size-10 me-1"></i> Product C</p>
							<h5>42 %</h5>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-body">
				<div class="dropdown float-end">
					<a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="mdi mdi-dots-vertical"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end">
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item">Export Report</a>
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item">Profit</a>
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item">Action</a>
					</div>
				</div>

				<h4 class="card-title mb-4">Earning Reports</h4>
				<div class="text-center">
					<div class="row">
						<div class="col-sm-6">
							<div>
								<div class="mb-3">
									<div id="radialchart-1" class="apex-charts"></div>
								</div>

								<p class="text-muted text-truncate mb-2">Weekly Earnings</p>
								<h5 class="mb-0">$2,523</h5>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="mt-5 mt-sm-0">
								<div class="mb-3">
									<div id="radialchart-2" class="apex-charts"></div>
								</div>

								<p class="text-muted text-truncate mb-2">Monthly Earnings</p>
								<h5 class="mb-0">$11,235</h5>
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
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
</script>