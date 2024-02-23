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
                    $url = CONFIG_SERVER_ADMIN_ROOT . "leads/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "leads/add";
                }
                echo form_open($url, array('class' => 'leads', 'id' => 'leads')); ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Business Name <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['business_name'])) { echo $formData['business_name']; } ?>" name="business_name" id="business_name" placeholder="Please Enter Business Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Contact Person Name <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['contact_person_name'])) { echo $formData['contact_person_name']; } ?>" name="contact_person_name" id="contact_person_name" placeholder="Please Enter Contact Person Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Contact Person Number<span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['contact_person_number'])) { echo $formData['contact_person_number']; } ?>" name="contact_person_number" id="contact_person_number" placeholder="Please Enter Contact Person Number"  maxlength="10" autocomplete='off' type="text" class="numberOnly form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Address<span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['address'])) { echo $formData['address']; } ?>" name="address" id="address" placeholder="Please Enter Address" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="first name" class="">Purpose<span class="text-danger">*</span></label>
                            <textarea value="<?php if (isset($formData['purpose'])) { echo $formData['purpose']; } ?>" name="purpose" id="purpose" placeholder="Please Enter Purpose" autocomplete='off' class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Visible<span class="text-danger">*</span></label>
                            <select name="visible" class="form-control" id="visible">
                                <option value="Self">Self</option>
                                <option value="Everyone">Everyone</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Visited Date<span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['visited_date'])) { echo $formData['visited_date']; } ?>" name="visited_date" id="visited_date" autocomplete='off' type="date" class="form-control">
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
                            <div class="col-md-3 <?= ($this->session->user_type=='Employee') ? 'd-none' : ''; ?>">
                                <div class="form-group">
                                    <label>Select Employee</label>
                                    <select class="form-control" id="user_id" name="user_id">
                                        <option value="">Select Employee</option>
                                        <?php if(!empty($employees)){
                                            foreach($employees as $employee){
                                        ?>
                                        <option value="<?= $employee['id']; ?>"><?= $employee['first_name']; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control daterange" id="daterange">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive tasks dataGridTable">
                            <table id="leadsList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Employee</th>
                                        <th>Business Name</th>
                                        <th>Contact Name</th>
                                        <th>Contact Number</th>
                                        <th>Visited On</th>
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
        var clist = $('#leadsList').DataTable({
            "destroy": true,
            "responsive": false,
            "dom": 'Bfrtip',
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>leads/ajaxListing",
                "type": 'POST',
                'data': {
                    date: date,
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
        if(role == 'Employee'){
         clist.column(1).visible(false);
        }
    }

    getdata();
    // function statusUpdate(e, uId, sTaTus) {
    //     $('#page-overlay1').hide();
    //     var TtMsg = 'Are you sure you want to ' + sTaTus + ' this status';
    //     $.confirm({
    //         title: TtMsg,
    //         buttons: {
    //             formSubmit: {
    //                 text: 'Yes',
    //                 btnClass: 'btn-blue',
    //                 action: function() {
    //                     $('#page-overlay').show();
    //                     $.ajax({
    //                         url: '<?php echo base_url(); ?>administrator/leads/updateStatus',
    //                         type: 'POST',
    //                         data: {
    //                             "statusresult": "1",
    //                             "status": sTaTus,
    //                             "pid":uId
    //                         },
    //                         success: function(data) {
    //                             result = JSON.parse(data);
    //                             var msg = result.message;
    //                             if (result.error == '0') {
    //                                 toastr['success'](msg);
    //                                 $('#categoriesList').DataTable().ajax.reload();
    //                             } else {
    //                                 toastr['warning'](msg);
    //                                 $('#categoriesList').DataTable().ajax.reload();
    //                             }
    //                         },
    //                         error: function(e) {
    //                             toastr['danger'](msg);
    //                             $('#categoriesList').DataTable().ajax.reload();
    //                         }
    //                     });
    //                 }
    //             },
    //             no: function() {
    //                 //close
    //                 $('#page-overlay').hide();
    //             },
    //         },
    //         onContentReady: function() {
    //             // bind to events
    //             var jc = this;
    //             this.$content.find('form').on('submit', function(e) {
    //                 // if the user submits the form by pressing enter in the field.
    //                 e.preventDefault();
    //                 jc.$$formSubmit.trigger('click'); // reference the button and click it
    //             });
    //         }
    //     });

    // }
</script>

<?php } ?>

