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
                    $url = CONFIG_SERVER_ADMIN_ROOT . "expenses/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "expenses/add";
                }
                echo form_open($url, array('class' => 'expenses', 'id' => 'expenses')); ?>

                <div class="row">
                    <div class="col-md-6 ">
                        <div class="mb-3">
                            <label for="last name" class="">Expense Category <span class="text-danger">*</span></label>
                            <select name="expense_category" class="form-control">
                                <option value="">Select Category</option>
                                <?php foreach($categories as $category){ ?>
                                    <option value="<?= $category['id'];?>" <?php if(isset($formData) && ($formData['expense_category']==$category['id'])){ echo "selected"; } ?>><?= $category['category'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Expense Purpose<span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['expense_purpose'])) {
                                                echo $formData['expense_purpose'];
                                            } ?>" name="expense_purpose" id="expense_purpose" placeholder="Please Enter Expense Purpose" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount" class="">Expense Amount <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['amount'])) { echo $formData['amount']; } ?>" name="amount" id="amount" placeholder="Please Enter Expense Amount" autocomplete='off' type="text" class="Onlynumbers form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="expense_date" class="">Expense Date <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['expense_date'])) { echo $formData['expense_date']; } ?>" name="expense_date" id="expense_date"  autocomplete='off' type="date" class="form-control">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Expense Reciept</label>
                            <input type="file" class="main_image_element form-control border" >
                            <input type="hidden" id="expense_receipt"  class="expense_receipt" value="<?php if(isset($formData)){ echo $formData['expense_receipt']; } ?>" name="expense_receipt">
                            <ul id="main_imageUploadedDoc">
                            <?php if(isset($formData) && $formData['expense_receipt']!=''){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData['expense_receipt']); ?>')" >View</a>
                            <?php } ?>
                            </ul>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
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
<script>
    $('.main_image_element').change(function() {
        formdata = new FormData();
        var path = "uploads/expenses/";
        if ($(this).prop('files').length > 0) {
            file = $(this).prop('files')[0];
            formdata.append("file", file);
            formdata.append("path", path);
            $(".main_image_element").val('');
            uploadDocs(formdata, 'main_imageUploadedDoc', 'expense_receipt');
        }
    });
</script>
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="last name" class="">Expense Category <span class="text-danger">*</span></label>
                                    <select name="expense_category" id="expense_category" class="form-control">
                                        <option value="All">All</option>
                                        <?php foreach($categories as $category){ ?>
                                            <option value="<?= $category['id'];?>" <?php if(isset($formData) && ($formData['category']==$category['id'])){ echo "selected"; } ?>><?= $category['category'];?></option>
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
                            <table id="expenseList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Category</th>
                                        <th>Purpose</th>
                                        <th>Amount</th>
                                        <th>Expense Date</th>
                                        <th>Created On</th>
                                        <th>Status</th>
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
        var date = $("#daterange").val();
        var expense_category = $("#expense_category").val();
        var clist = $('#expenseList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [5, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>expenses/ajaxListing",
                "type": 'POST',
                'data': {
                    date:date,
                    expense_category:expense_category
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
                            url: '<?php echo base_url(); ?>administrator/expenses/updateStatus',
                            type: 'POST',
                            data: {
                                "statusresult": "1",
                                "status": sTaTus,
                                "pid":uId
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    toastr['success'](msg);
                                    $('#expenseList').DataTable().ajax.reload();
                                } else {
                                    toastr['warning'](msg);
                                    $('#expenseList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                toastr['danger'](msg);
                                $('#expenseList').DataTable().ajax.reload();
                            }
                        });
                    }
                },
                no: function() {
                    //close
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

