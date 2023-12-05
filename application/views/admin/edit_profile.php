<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($data)){
    if(!empty($data)){
        $formData=$data;
    }else{
        $formData=$this->form_validation->get_session_data();
    }
}else{
    $formData=$this->form_validation->get_session_data();
}
?>

<div class="col-md-12">
	<div class="main-card mb-3 card card-body"><h5 class="card-title"></h5>
  
	<?php  
			if(isset($formData['id'])){
				$id=$formData['id'];
				$url=CONFIG_SERVER_ADMIN_ROOT."myProfile/edit_profile";
			}

			echo form_open($url,array('class' => 'userRegistration','id' => 'userRegistration')); ?>
			
			<div class="form-row">
				<div class="col-md-6">
					<div class="position-relative form-group"><label for="first name" class="">First Name</label>
					<input value="<?php if(isset($formData['first_name'])){echo $formData['first_name'];} ?>" name="first_name" id="first_name" placeholder="Please Enter First Name"  autocomplete='off' type="text" class="form-control"></div>
				</div>
				<div class="col-md-6">
					<div class="position-relative form-group"><label for="last name" class="">Last Name</label><input value="<?php if(isset($formData['last_name'])){echo $formData['last_name'];} ?>" name="last_name" id="last_name" placeholder="Please Enter Last Name" type="text" autocomplete='off'  class="form-control"></div>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-6">
					<div class="position-relative form-group"><label for="exampleEmail11" class="">Email</label><input value="<?php if(isset($formData['email_id'])){echo $formData['email_id'];} ?>" name="email_id" id="email" placeholder="Please Enter Email Id"  autocomplete='off' type="text" class="email form-control"></div>
				</div>
				<div class="col-md-6">
					<div class="position-relative form-group"><label for="examplePassword11" class="">Mobile</label><input value="<?php if(isset($formData['phno'])){echo $formData['phno'];} ?>" name="mobile_no" id="mobile" placeholder="Please Enter Mobile Number" maxlength="10" type="text"  class="numberOnly form-control"></div>
				</div>
			</div>

			<div class="form-row">

				<div class="col-md-6">
					<div class="position-relative form-group"><label for="exampleEmail11" class="">Status</label>
						<select class="form-control" name='status'>
							<option value='Active' <?php if(isset($formData['status'])){ if($formData['status']=='Active'){ echo "selected=selected";} } ?> >Active</option>
							<option value='Inactive' <?php if(isset($formData['status'])){ if($formData['status']=='Inactive'){ echo "selected=selected";} } ?> >Inactive</option>
						</select>
					</div>
				</div>
			   
			</div>
	
			<div class="form-row">
					<input type='hidden' name="id" value="<?php echo $formData['id'] ?>">
					<input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
			</div>
		</form>
	</div>
</div>

<?php //if(isset($formData['user_type'])){ if($formData['user_type']=='Super Admin'){ $user_check = "Super Admin";}elseif($formData['user_type']=='Admin'){ $user_check = "Admin"; }elseif($formData['user_type']=='User'){ $user_check = "User"; } } ?>

<script type="text/javascript">
// function checkchild(module)
// {
// 	if($("#"+module).is(":checked"))
// 	{
// 		$("."+module).find("input").prop("disabled",false);
// 		$("."+module).find("input").prop("checked",true);
// 	}else{
// 		$("."+module).find("input").prop("disabled",true);
// 		$("."+module).find("input").prop("checked",false);
// 	}
// }
// var userCheck = '<?php //echo $user_check; ?>';
// $(document).ready(function(){
// 	if(userCheck == 'Super Admin'){
// 		$('.module_permission').css('display','none');
// 	}else if(userCheck == 'Admin'){
// 		$('.module_permission').css('display','block');
// 	}else if(userCheck == 'User'){
// 		$('.module_permission').css('display','none');
// 	}
// });

// function checkUserType(uTypeName)
// {
// 	if(uTypeName == 'Super Admin'){
// 		$('.module_permission').css('display','none');
// 	}else if(uTypeName == 'Admin'){
// 		$('.module_permission').css('display','block');
// 	}else if(uTypeName == 'User'){
// 		$('.module_permission').css('display','none');
// 	}
// }
	
</script>
  