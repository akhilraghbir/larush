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
                    $url = CONFIG_SERVER_ADMIN_ROOT . "categories/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "categories/add";
                }
                echo form_open($url, array('class' => 'categories', 'id' => 'categories')); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="first name" class="">Category <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['category'])) { echo $formData['category']; } ?>" name="category" id="category" placeholder="Please Enter Category" autocomplete='off' type="text" class="form-control">
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
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="All">All</option>
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">In Active</option>
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
                            <table id="categoriesList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Created On</th>
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
        var clist = $('#categoriesList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [3, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>categories/ajaxListing",
                "type": 'POST',
                'data': {
                    status: status,
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
                            url: '<?php echo base_url(); ?>administrator/categories/updateStatus',
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
                                    $('#categoriesList').DataTable().ajax.reload();
                                } else {
                                    toastr['warning'](msg);
                                    $('#categoriesList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                toastr['danger'](msg);
                                $('#categoriesList').DataTable().ajax.reload();
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

