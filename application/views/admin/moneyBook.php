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
                    $url = CONFIG_SERVER_ADMIN_ROOT . "moneyBook/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "moneyBook/add";
                }
                echo form_open($url, array('class' => 'expenses', 'id' => 'expenses')); ?>

                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Purpose<span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['purpose'])) {
                                                echo $formData['purpose'];
                                            } ?>" name="purpose" id="purpose" placeholder="Please Enter Purpose" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount" class="">Amount <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['amount'])) { echo $formData['amount']; } ?>" name="amount" id="amount" placeholder="Please Enter Amount" autocomplete='off' type="text" class="Onlynumbers form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="expense_date" class="">Type  <span class="text-danger">*</span></label>
                            <select name="type" class="form-control">
                                <option value="Taken">Taken</option>
                                <option value="Given">Given</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="mb-3">
                            <label for="last name" class="">User <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control">
                                <option value="">Select User</option>
                                <?php foreach($users as $user){ ?>
                                    <option value="<?= $user['id'];?>"><?= $user['first_name'];?>(<?= $user['user_type'];?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div>
                    <?php if (isset($formData['id'])) { ?>
                        <input type='hidden' name="id" value="<?php echo $formData['id'] ?>">
                        <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                    <?php } else { ?>
                        <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Submit">
                    <?php } ?>

                </div>
                </form>
            </div>
        </div>

    <?php } else { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control daterange" id="daterange">
                            </div>
                            <div class="col-md-3 <?= ($this->session->user_type == 'Employee') ? 'd-none' : '' ?>">
                                <div class="mb-3">
                                    <label for="last name" class="">Employee <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="All">All</option>
                                        <?php foreach($users as $user){ ?>
                                            <option value="<?= $user['id'];?>"><?= $user['first_name'];?></option>
                                        <?php } ?>
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
                            <table id="bookList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Purpose</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>User</th>
                                        <th>Created On</th>
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
        var date = $("#daterange").val();
        var user_id = $("#user_id").val();
        var clist = $('#bookList').DataTable({
            "destroy": true,
            "responsive": false,
            "dom": 'Bfrtip',
            "processing": true,
            "serverSide": true,
            "order": [
                [5, "desc"]
            ],
            buttons: ["copy", "csv", "pdf"],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>MoneyBook/ajaxListing",
                "type": 'POST',
                'data': {
                    date:date,
                    user_id:user_id
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
</script>

<?php } ?>

