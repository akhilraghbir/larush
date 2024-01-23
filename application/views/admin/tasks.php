<div class="col-md-12">
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($data)){
    if(!empty($data)){
        $formData=$data;
    }else{
        $formData=$this->form_validation->get_session_data();
    }
}else{
    $formData=$this->form_validation->get_session_data();
}
?>

<?php if(isset($form_action)){ ?>
                <div class="">
                        <div class="main-card mb-3 card card-body"><h5 class="card-title"></h5>
                        <?php  
                                if(isset($formData['id'])){
                                    $id=$formData['id'];
                                    $url=CONFIG_SERVER_ADMIN_ROOT."tasks/edit/$id";
                                }else {
                                    $url = CONFIG_SERVER_ADMIN_ROOT . "tasks/add";
                                }
                               
                                echo form_open_multipart($url,array('class' => 'headerMenu','id' => 'headerMenu')); ?> 

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                        <label for="first name" class="">Task Title</label>
                                        <input value="<?php if(isset($formData['task_title'])){echo $formData['task_title'];} ?>" name="task_title" id="task_title" placeholder="Please Enter Task Title"  autocomplete='off' type="text" class="form-control" maxlength="100"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                        <label for="first name" class="">Select Employee</label>
                                        <select name="employee_id" class="form-control">
                                            <option value="">Select Employee</option>
                                            <?php if(!empty($employees)){ 
                                            foreach($employees as $employee){
                                            ?>
                                            <option value="<?= $employee['id'];?>" <?= (isset($formData['employee_id']) && $formData['employee_id'] == $employee['id']) ? "selected" : "";?> ><?= $employee['first_name'];?></option>
                                            <?php } } ?>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="first name" class="">Priority</label>
                                            <select name="priority" class="form-control">
                                                <option value="">Select Priority</option>
                                                <option value="3" <?= (isset($formData['priority']) && $formData['priority'] == 3) ? "selected" : "";?> >Critical</option>
                                                <option value="2" <?= (isset($formData['priority']) && $formData['priority'] == 2) ? "selected" : "";?> >High Priority</option>
                                                <option value="1" <?= (isset($formData['priority']) && $formData['priority'] == 1) ? "selected" : "";?> >Nuetral</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                               <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                        <label for="first name" class="">Description</label>
                                        <textarea name="task_description"  placeholder="Please Enter Description"  autocomplete='off'  class="form-control"><?php if(isset($formData['task_description'])){echo $formData['task_description'];} ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    <?php if(isset($formData['id'])){ ?>
                                        <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                                    <?php }else{ ?>
                                        <input type="submit" name="add" class="mt-2 btn btn-primary pull-right resetSubmit" value="Submit">
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                </div>
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'task_description' );
</script>
<?php }else{ ?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body"> 
                    <div class="table-responsive tasks dataGridTable">
                    <table id="TasksList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="wd-lg-30p">S.No </th>        
                                <th class="wd-lg-30p">Task Title</th>
                                <th class="wd-lg-30p">Employee</th>
                                <th class="wd-lg-30p">Status</th>
                                <th class="wd-lg-30p">Created On</th>
                                <th>Action</th>
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
<script type="text/javascript">
            $(document).ready(function() {
                $('#TasksList').DataTable({
                    "responsive": false,
                    "processing": true,
                    "serverSide": true,
                    "order":[4,'desc'],
                    "ajax": {
                        "url" : "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>tasks/ajaxListing",
                        "type" : 'POST'
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
            });
        </script>
<?php } ?>
</div>

                   