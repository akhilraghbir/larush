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
                    $url = CONFIG_SERVER_ADMIN_ROOT . "inventory/edit/$id";
                } else {
                    $url = CONFIG_SERVER_ADMIN_ROOT . "inventory/add";
                }
                echo form_open($url, array('class' => 'inventory', 'id' => 'inventory')); ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Product Name <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['product_name'])) {
                                                echo $formData['product_name'];
                                            } ?>" name="product_name" id="product_name" placeholder="Please Enter Product Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="mb-3">
                            <label for="last name" class="">Units <span class="text-danger">*</span></label>
                            <select name="units" class="form-control">
                                <option value="">Select Unit</option>
                                <?php foreach($units as $unit){ ?>
                                    <option value="<?= $unit['id'];?>" <?php if(isset($formData) && ($formData['units']==$unit['id'])){ echo "selected"; } ?>><?= $unit['unit_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="is_catalytic" class="">Is Catalytic <span class="text-danger">*</span></label>
                            <select name="is_catalytic" id="is_catalytic" class="form-control">
                                <option value="No" <?php if(isset($formData) && ($formData['is_catalytic']=='No')){ echo "selected"; } ?>>No</option>
                                <option value="Yes" <?php if(isset($formData) && ($formData['is_catalytic']=='Yes')){ echo "selected"; } ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 ferrous_div <?= (isset($formData) && ($formData['is_catalytic']=='Yes')) ? 'd-none' : '' ?>">
                        <div class="mb-3">
                            <label for="is_ferrous" class="">Is Ferrous <span class="text-danger">*</span></label>
                            <select name="is_ferrous" id="is_ferrous" class="form-control">
                                <option value="No" <?php if(isset($formData) && ($formData['is_ferrous']=='No')){ echo "selected"; } ?>>No</option>
                                <option value="Yes" <?php if(isset($formData) && ($formData['is_ferrous']=='Yes')){ echo "selected"; } ?>>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 ferrous_div <?= (isset($formData) && ($formData['is_catalytic']=='Yes')) ? 'd-none' : '' ?>">
                        <div class="mb-3">
                            <label for="buyer_price" class="">Buyer Price <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['buyer_price'])) { echo $formData['buyer_price']; } ?>" name="buyer_price" id="buyer_price" placeholder="Please Enter Buyer Price" autocomplete='off' type="text" class="Onlynumbers form-control">
                        </div>
                    </div>
                    <div class="col-md-3 ferrous_div <?= (isset($formData) && ($formData['is_catalytic']=='Yes')) ? 'd-none' : '' ?>">
                        <div class="mb-3">
                            <label for="tier_price" class="">Tier Price <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData['tier_price'])) { echo $formData['tier_price']; } ?>" name="tier_price" id="tier_price" placeholder="Please Enter Tier Price" autocomplete='off' type="text" class="Onlynumbers form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Main Image</label>
                            <input type="file" class="main_image_element form-control border" >
                            <input type="hidden" id="main_image"  class="main_image" value="<?php if(isset($formData)){ echo $formData['main_image']; } ?>" name="main_image">
                            <ul id="main_imageUploadedDoc">
                            <?php if(isset($formData) && $formData['main_image']!=''){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData['main_image']); ?>')" >View</a>
                            <?php } ?>
                            </ul>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Zoom Image</label>
                            <input type="file" class="zoom_image_element form-control border" >
                            <input type="hidden" id="zoom_image"  class="zoom_image" value="<?php if(isset($formData)){ echo $formData['zoom_image']; } ?>" name="zoom_image">
                            <ul id="zoom_imageUploadedDoc">
                            <?php if(isset($formData) && $formData['zoom_image']!=''){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData['zoom_image']); ?>')" >View</a>
                            <?php } ?>
                            </ul>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Wide Image</label>
                            <input type="file" class="wide_image_element form-control border" >
                            <input type="hidden" id="wide_image"  class="wide_image" value="<?php if(isset($formData)){ echo $formData['wide_image']; } ?>" name="wide_image">
                            <ul id="wide_imageUploadedDoc">
                            <?php if(isset($formData) && $formData['wide_image']!=''){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData['wide_image']); ?>')" >View</a>
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
        var path = "uploads/products/";
        if ($(this).prop('files').length > 0) {
            file = $(this).prop('files')[0];
            formdata.append("file", file);
            formdata.append("path", path);
            $(".main_image_element").val('');
            uploadDocs(formdata, 'main_imageUploadedDoc', 'main_image');
        }
    });
    $('.zoom_image_element').change(function() {
        formdata = new FormData();
        var path = "uploads/products/";
        if ($(this).prop('files').length > 0) {
            file = $(this).prop('files')[0];
            formdata.append("file", file);
            formdata.append("path", path);
            $(".zoom_image_element").val('');
            uploadDocs(formdata, 'zoom_imageUploadedDoc', 'zoom_image');
        }
    });
    $('.wide_image_element').change(function() {
        formdata = new FormData();
        var path = "uploads/products/";
        if ($(this).prop('files').length > 0) {
            file = $(this).prop('files')[0];
            formdata.append("file", file);
            formdata.append("path", path);
            $(".wide_image_element").val('');
            uploadDocs(formdata, 'wide_imageUploadedDoc', 'wide_image');
        }
    });
    $("#is_catalytic").change(function(){
        var is_catalytic = $(this).val();
        if(is_catalytic == 'Yes'){
            $(".ferrous_div").addClass('d-none');
        }else{
            $(".ferrous_div").removeClass('d-none');
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
                            <table id="invetoryList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Product Name</th>
                                        <th>Units</th>
                                        <th>Is Ferrous</th>
                                        <th>Buyer Price</th>
                                        <th>Tier Price</th>
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
        var clist = $('#invetoryList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [1, "asc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>inventory/ajaxListing",
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
                            url: '<?php echo base_url(); ?>administrator/inventory/updateStatus',
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
                                    $('#invetoryList').DataTable().ajax.reload();
                                } else {
                                    toastr['warning'](msg);
                                    $('#invetoryList').DataTable().ajax.reload();
                                }
                            },
                            error: function(e) {
                                toastr['danger'](msg);
                                $('#invetoryList').DataTable().ajax.reload();
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

