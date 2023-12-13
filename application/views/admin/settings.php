<div class="col-md-12">
    <?php
    defined('BASEPATH') or exit('No direct script access allowed');
    if (isset($settings)) {
        if (!empty($settings)) {
            $formData = $settings;
        } else {
            $formData = $this->form_validation->get_session_data();
        }
    } else {
        $formData = $this->form_validation->get_session_data();
    }
    // echo "<pre>";
    // print_r($formData);exit;
    ?>
    <?php if (isset($form_action)) { ?>
        <div class="">
            <div class="main-card mb-3 card card-body">
                <h5 class="card-title"></h5>
                <?php
                $url = CONFIG_SERVER_ADMIN_ROOT . "settings/edit";
                echo form_open($url, array('class' => 'settings', 'id' => 'settings')); ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Name <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData[0]['key']) && $formData[0]['key'] == 'name') { echo $formData[0]['value']; } ?>" name="name" id="name" placeholder="Please Enter Name" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Website <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData[1]['key']) && $formData[1]['key'] == 'website') { echo $formData[1]['value']; } ?>" name="website" id="website" placeholder="Please Enter Website" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Phone Number <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData[2]['key']) && $formData[2]['key'] == 'phone_number') { echo $formData[2]['value']; } ?>" name="phone_number" id="phone_number" placeholder="Please Enter Phone Number" autocomplete='off' type="text" class="numberOnly form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first name" class="">Address <span class="text-danger">*</span></label>
                            <input value="<?php if (isset($formData[3]['key']) && $formData[3]['key'] == 'address') { echo $formData[3]['value']; } ?>" name="address" id="address" placeholder="Please Enter Address" autocomplete='off' type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Logo</label>
                            <input type="file" class="logo_element form-control border" >
                            <input type="hidden" id="logo"  class="logo" value="<?php if(isset($formData[5]['key']) && $formData[5]['key'] == 'logo'){ echo $formData[5]['value']; } ?>" name="logo">
                            <ul id="logoUploadedDoc">
                            <?php if(isset($formData[5]['key']) && $formData[5]['key'] == 'logo'){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData[5]['value']); ?>')" >View</a>
                            <?php } ?>
                            </ul>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleEmail11" class="">Invoice Logo</label>
                            <input type="file" class="invoice_logo_element form-control border" >
                            <input type="hidden" id="invoice_logo"  class="invoice_logo" value="<?php if(isset($formData[4]['key']) && $formData[4]['key'] == 'invoice_logo'){ echo $formData[4]['value']; } ?>" name="invoice_logo">
                            <ul id="invoice_logoUploadedDoc">
                            <?php if(isset($formData[4]['key']) && $formData[4]['key'] == 'invoice_logo'){ ?>
                                <a href='javascript:void(0)' onclick="openimage('<?= base_url($formData[4]['value']); ?>')" >View</a>
                            <?php } ?>
                            </ul>
                            <div style="display:none" class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <input type='hidden' name="id" value="<?php echo $formData['id'] ?>">
                    <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                </div>
                </form>
            </div>
        </div>
<script>
    $('.logo_element').change(function() {
        formdata = new FormData();
        var path = "uploads/settings/";
        if ($(this).prop('files').length > 0) {
            file = $(this).prop('files')[0];
            formdata.append("file", file);
            formdata.append("path", path);
            $(".logo_element").val('');
            uploadDocs(formdata, 'logoUploadedDoc', 'logo');
        }
    });
    $('.invoice_logo_element').change(function() {
        formdata = new FormData();
        var path = "uploads/settings/";
        if ($(this).prop('files').length > 0) {
            file = $(this).prop('files')[0];
            formdata.append("file", file);
            formdata.append("path", path);
            $(".invoice_logo_element").val('');
            uploadDocs(formdata, 'invoice_logoUploadedDoc', 'invoice_logo');
        }
    });
</script>
    <?php }  ?>

