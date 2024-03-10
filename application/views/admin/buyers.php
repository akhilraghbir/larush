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
                    $url = CONFIG_SERVER_ADMIN_ROOT . "buyers/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "buyers/add";
                }
                echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="buyer_name" class="">Buyer Name </label>
                            <input value="<?php if (isset($formData['buyer_name'])) { echo $formData['buyer_name']; } ?>" name="buyer_name" id="buyer_name" placeholder="Please Enter Buyer Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="company_name" class="">Company Name <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['company_name'])) { echo $formData['company_name']; } ?>" name="company_name" id="company_name" placeholder="Please Enter Company Name" type="text" autocomplete='off' class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="company_address" class="">Company Address <span class="text-danger">*</span></label>
                            <textarea value="<?php if (isset($formData['company_address'])) {echo $formData['company_address']; } ?>" name="company_address" id="company_address" placeholder="Please Enter Company Address" class="form-control"><?php if (isset($formData['company_address'])) {echo $formData['company_address']; } ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="phno" class="">Phone Number </label>
                            <input value="<?php if (isset($formData['phno'])) { echo $formData['phno'];} ?>" name="phno" id="phno" placeholder="Please Enter Phone Number"  maxlength="10" autocomplete='off' type="text" class="numberOnly form-control"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="alternate_phno" class="">Alternate Phone Number </label>
                            <input value="<?php if (isset($formData['alternate_phno'])) { echo $formData['alternate_phno']; } ?>" name="alternate_phno" id="alternate_phno" placeholder="Please Enter Alternate Phone Number" maxlength="10" autocomplete='off' type="text" class="numberOnly form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="company_email" class="">Email Address </label>
                            <input value="<?php if (isset($formData['company_email'])) { echo $formData['company_email']; } ?>" name="company_email" id="company_email" placeholder="Please Enter Company Email Address" type="text" class="email form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="company_website" class="">Company Website</label>
                            <input value="<?php if (isset($formData['company_website'])) { echo $formData['company_website'];} ?>" name="company_website" id="company_website" placeholder="Please Enter Company Website"   autocomplete='off' type="text" class="form-control"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="gstn" class="">GST Number </label>
                            <input value="<?php if (isset($formData['gstn'])) { echo $formData['gstn']; } ?>" name="gstn" id="gstn" placeholder="Please Enter GSTN" maxlength="14" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Pollution Document</label>
                            <input type="file" class="pollution_doc_element form-control border" >
                            <input type="hidden" id="pollution_document"  class="pollution_document" value="<?php if(isset($formData)){ echo $formData['pollution_document']; } ?>" name="pollution_document">
                            <ul id="pollution_documentUploadedDoc">
                            <?php if(isset($formData) && $formData['pollution_document']!=''){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData['pollution_document']); ?>')" >View</a>
                            <?php } ?>
                            </ul>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="bank_account_number" class="">Bank Account Number </label>
                            <input value="<?php if (isset($formData['bank_account_number'])) { echo $formData['bank_account_number']; } ?>" name="bank_account_number" id="bank_account_number" placeholder="Please Enter Bank Account Number" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="bank_name" class="">Bank Name </label>
                            <input value="<?php if (isset($formData['bank_name'])) { echo $formData['bank_name'];} ?>" name="bank_name" id="bank_name" placeholder="Please Enter Bank Name"   autocomplete='off' type="text" class="form-control"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ifsc" class="">IFSC </label>
                            <input value="<?php if (isset($formData['ifsc'])) { echo $formData['ifsc']; } ?>" name="ifsc" id="ifsc" placeholder="Please Enter IFSC" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="branch" class="">Branch </label>
                            <input value="<?php if (isset($formData['branch'])) { echo $formData['branch']; } ?>" name="branch" id="branch" placeholder="Please Enter Branch" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="contact_person_name" class="">Contact Person Name </label>
                            <input value="<?php if (isset($formData['contact_person_name'])) { echo $formData['contact_person_name'];} ?>" name="contact_person_name" id="contact_person_name" placeholder="Please Enter Contact Person Name"   autocomplete='off' type="text" class="form-control"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="contact_person_number" class="">Contact Person Number </label>
                            <input value="<?php if (isset($formData['contact_person_number'])) { echo $formData['contact_person_number']; } ?>" name="contact_person_number" id="contact_person_number" placeholder="Please Enter Contact Person Number" autocomplete='off' maxlength="10" type="text" class="numberOnly form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="contact_person_email" class="">Contact Person Email</label>
                            <input value="<?php if (isset($formData['contact_person_email'])) { echo $formData['contact_person_email']; } ?>" name="contact_person_email" id="contact_person_email" placeholder="Please Enter Contact person Email" type="text" class="form-control">
                        </div>
                    </div>
                </div>


                <div>
                    <?php if (isset($formData['id'])) { ?>
                        <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                    <?php } else { ?>
                        <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Submit">
                    <?php } ?>

                </div>
                </form>
            </div>
        </div>
        <script>
        $('.pollution_doc_element').change(function() {
            formdata = new FormData();
            var path = "uploads/buyers/";
            if ($(this).prop('files').length > 0) {
                file = $(this).prop('files')[0];
                formdata.append("file", file);
                formdata.append("path", path);
                $(".pollution_doc_element").val('');
                uploadDocs(formdata, 'pollution_documentUploadedDoc', 'pollution_document');
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
                            <table id="buyersList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Buyer Id</th>
                                        <th>Buyer Name</th>
                                        <th>Company Name</th>
                                        <th>Email Id</th>
                                        <th>Phone Number</th>
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
        $('#buyersList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>buyers/ajaxListing",
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
                            url: '<?php echo base_url(); ?>administrator/buyers/updateStatus',
                            type: 'POST',
                            data: {
                                "statusresult": "1",
                                "sid": uId,
                                "status": sTaTus
                            },
                            success: function(data) {
                                result = JSON.parse(data);
                                var msg = result.message;
                                if (result.error == '0') {
                                    toastr['success'](msg);
                                    $('#buyersList').DataTable().ajax.reload();
                                } else {
                                    toastr['warning'](msg);
                                    $('#buyersList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                toastr['warning'](e.message);
                                $('#buyersList').DataTable().ajax.reload();
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
    function getDetails(id) {
        if (id != '') {
            $.ajax({
                url: '<?php echo base_url(); ?>administrator/buyers/getDetails',
                type: 'POST',
                data: {"id": id},
                success: function(data) {
                    result = JSON.parse(data);
                    var msg = result.message;
                    if (result.error == '0') {
                       $(".modalTitle").text('Buyers Details');
                       $(".modal-body").html(result.html);
                       $(".bs-example-modal-lg").modal('show');
                    } else {
                       console.log(result);
                    }
                },
                error: function(e) {
                    console.log(e.message);
                }
            });
        }
    }
</script>

<?php } ?>