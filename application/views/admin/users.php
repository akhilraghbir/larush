<div class="col-md-12">
    <?php
    defined('BASEPATH') or exit('No direct script access allowed');
    if (isset($data)) {
        if (!empty($data)) {
            $formData = $data;
        } else {
            $formData = $this->form_validation->get_session_data();
        }
    } else {
        $formData = $this->form_validation->get_session_data();
    }
    ?>
    <?php if (isset($form_action)) { ?>
        <div class="">
            <div class="main-card mb-3 card card-body">
                <h5 class="card-title"></h5>
                <?php
                if (isset($formData['id'])) {
                    $id = $formData['id'];
                    $url = CONFIG_SERVER_ADMIN_ROOT . "users/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "users/add";
                }
                echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">First Name</label>
                            <input value="<?php if (isset($formData['first_name'])) { echo $formData['first_name'];} ?>" name="first_name" id="first_name" placeholder="Please Enter First Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last name" class="">Last Name</label>
                            <input value="<?php if (isset($formData['last_name'])) { echo $formData['last_name']; } ?>" name="last_name" id="last_name" placeholder="Please Enter Last Name" type="text" autocomplete='off' class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Email</label>
                            <input value="<?php if (isset($formData['email_id'])) { echo $formData['email_id']; } ?>" name="email_id" id="email" placeholder="Please Enter Email Id" autocomplete='off' type="email" class="email form-control"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="examplePassword11" class="">Mobile</label>
                            <input value="<?php if (isset($formData['phno'])) { echo $formData['phno']; } ?>" name="phno" id="mobile" placeholder="Please Enter Mobile Number" maxlength="10" type="text" class="numberOnly form-control"></div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($this->session->user_type == 'Admin') {  ?>
                        <div class="col-md-6">
                            <div class="mb-3"><label for="examplePassword11" class="">Role</label>
                                <select class="form-control" name='user_type' id="user_type">
                                    <option value='Admin' <?php if (isset($formData['user_type'])) {
                                                                if ($formData['user_type'] == 'Admin') {
                                                                    echo "selected=selected";
                                                                }
                                                            } ?>>Admin</option>
                                    <option value='Employee' <?php if (isset($formData['user_type'])) {
                                                                    if ($formData['user_type'] == 'Employee') {
                                                                        echo "selected=selected";
                                                                    }
                                                                } ?>>Employee</option>
                                    <option value='Accountant' <?php if (isset($formData['user_type'])) {
                                                                    if ($formData['user_type'] == 'Accountant') {
                                                                        echo "selected=selected";
                                                                    }
                                                                } ?>>Accountant</option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-md-6 warehouse <?= (isset($formData['user_type']) && $formData['user_type'] == 'Employee') ? '' : 'd-none'?>">
                        <div class="mb-3"><label for="exampleEmail11" class="">Select Warehouse</label>
                            <select class="form-control" name='warehouse_id'>
                               <option value="">Select Warehouse</option>
                               <?php if(!empty($warehouses)){ 
                                foreach($warehouses as $warehouse){ ?>
                                <option value="<?= $warehouse['id']; ?>"><?= $warehouse['warehouse_name']; ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div>
                    <?php if (isset($formData['id'])) { ?>
                        <input type='hidden' name="id" value="<?php echo $formData['user_id'] ?>">
                        <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                    <?php } else { ?>
                        <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Submit">
                    <?php } ?>

                </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                $("#user_type").change(function(){
                    var user_type = $(this).val();
                    if(user_type == 'Employee'){
                        $(".warehouse").removeClass('d-none');
                    }else{
                        $(".warehouse").addClass('d-none');
                    }
                });
            });
        </script>
    <?php } else { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="All">All</option>
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">In Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Role</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="All">All</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Accountant">Accountant</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive tasks dataGridTable">
                            <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>User Id</th>
                                        <th>Name</th>
                                        <th>Email Id</th>
                                        <th>Phone Number</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registered On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
    function getdata() {
        var status = $("#status").val();
        var role = $("#role").val();
        $('#userList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>users/ajaxListing",
                "type": 'POST',
                'data': {
                    status: status,
                    role: role
                }
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

    function statusUpdate(e, uId, sTaTus) {
        $('#page-overlay1').hide();
        var TtMsg = 'Are you sure you want to ' + sTaTus + ' this status';
        $.confirm({
            title: TtMsg,
            buttons: {
                formSubmit: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function() {
                        $('#page-overlay').show();
                        $.ajax({
                            url: '<?php echo base_url(); ?>administrator/users/updateStatus',
                            type: 'POST',
                            data: {
                                "statusresult": "1",
                                "user_id": uId,
                                "status": sTaTus
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    toastr['success'](msg);
                                    $('#userList').DataTable().ajax.reload();
                                } else {
                                    toastr['warning'](msg);
                                    $('#userList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                toastr['warning'](e.message);
                                $('#userList').DataTable().ajax.reload();
                            }
                        });

                    }
                },
                no: function() {
                    $('#page-overlay').hide();
                },
            },
            onContentReady: function() {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function(e) {
                    // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    }
</script>

<?php } ?>