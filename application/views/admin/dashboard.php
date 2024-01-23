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