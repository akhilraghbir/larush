<div class="col-md-12">
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="">
    <div class="main-card mb-3 card card-body">
        <h5 class="card-title"></h5>
        <?php  
            $url=CONFIG_SERVER_ADMIN_ROOT."myProfile/changePassword";
            echo form_open($url,array('class' => 'changePassword','id' => 'ChangePassword')); 
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="first name" class="">Your Current Password <span class="text-danger">*</span></label>
                    <input  name="current_password" id="old_password" placeholder="Please Enter Your Current Password"  autocomplete='off' type="password" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="first name" class="">New Current Password <span class="text-danger">*</span></label>
                    <input  name="new_password" id="new_password" placeholder="Please Enter Your New Password" type="password" autocomplete='off'  class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="first name" class="">Re-type Current Password <span class="text-danger">*</span></label>
                    <input name="retype_password" id="retype_password" placeholder="Re-Type Your New Password" type="password" autocomplete='off'  class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <input type="submit" name="changePassword" class="mt-2 btn btn-primary pull-right" value="Change Password">
            </div>
        </div> 
        
        </form>
    </div>
</div>