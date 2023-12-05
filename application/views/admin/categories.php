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
                echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group"><label for="first name" class="">Category Name</label>
                            <input value="<?php if (isset($formData['category_name'])) {
                                                echo $formData['category_name'];
                                            } ?>" name="category_name" id="category_name" placeholder="Please Enter Category Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group"><label for="exampleEmail11" class="">Status</label>
                            <select class="form-control" name='status'>
                                <option value='Active' <?php if (isset($formData['status'])) {
                                                            if ($formData['status'] == 'Active') {
                                                                echo "selected=selected";
                                                            }
                                                        } ?>>Active</option>
                                <option value='Inactive' <?php if (isset($formData['status'])) {
                                                                if ($formData['status'] == 'Inactive') {
                                                                    echo "selected=selected";
                                                                }
                                                            } ?>>Inactive</option>
                            </select>
                        </div>
                </div>
                </div>
               
                <div class="position-relative form-check">
                    <?php if (isset($formData['id'])) { ?>
                        <input type='hidden' name="id" value="<?php echo $formData['id'] ?>">
                        <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                    <?php } else { ?>
                        <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="submit">
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
                            <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
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
        var accountant = $("#accountant").val();
        $('#userList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>categories/ajaxListing",
                "type": 'POST',
                'data': {
                    status: status,
                    accountant:accountant
                }
            },
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
                                "id": uId,
                                "status": sTaTus
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    notify('top', 'right', 'fa fa-success', 'success', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                    $('#userList').DataTable().ajax.reload();
                                } else {
                                    notify('top', 'right', 'fa fa-danger', 'error', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                    $('#userList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                notify('top', 'right', 'fa fa-danger', 'error', 'animated fadeInDown', 'animated fadeOutDown',e.msg);
                                $('#studentListing').DataTable().ajax.reload();
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
