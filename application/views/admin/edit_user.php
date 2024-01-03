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
				$url=CONFIG_SERVER_ADMIN_ROOT."users/edit/$id";
			}else{
				$url=CONFIG_SERVER_ADMIN_ROOT."users/add";
			}
		
			echo form_open($url,array('class' => 'userRegistration','id' => 'userRegistration')); ?>
			
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3"><label for="first name" class="">First Name</label>
					<input value="<?php if(isset($formData['first_name'])){echo $formData['first_name'];} ?>" name="first_name" id="first_name" placeholder="Please Enter First Name"  autocomplete='off' type="text" class="form-control"></div>
				</div>
				<div class="col-md-6">
					<div class="mb-3"><label for="last name" class="">Last Name</label><input value="<?php if(isset($formData['last_name'])){echo $formData['last_name'];} ?>" name="last_name" id="last_name" placeholder="Please Enter Last Name" type="text" autocomplete='off'  class="form-control"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="mb-3"><label for="exampleEmail11" class="">Email</label><input value="<?php if(isset($formData['email_id'])){echo $formData['email_id'];} ?>" name="email_id" id="email" placeholder="Please Enter Email Id"  autocomplete='off' type="email" class="form-control email"></div>
				</div>
				<div class="col-md-6">
					<div class="mb-3"><label for="examplePassword11" class="">Mobile</label><input value="<?php if(isset($formData['phno'])){echo $formData['phno'];} ?>" name="phno" maxlength="10" id="mobile" placeholder="Please Enter Mobile Number" type="text"  class="numberOnly form-control"></div>
				</div>
			</div>

			<div class="row">
			    <div class="col-md-6">
                            <div class="mb-3"><label for="examplePassword11" class="">Role</label>
                                <select class="form-control" name='user_type'>
                                    <option value='Admin' <?php if (isset($formData['user_type'])) {
                                                                    if ($formData['user_type'] == 'Admin') {
                                                                        echo "selected=selected";
                                                                    }
                                                                } ?>>Admin</option>
                                    <option value='Accountant' <?php if (isset($formData['user_type'])) {
                                                                if ($formData['user_type'] == 'Accountant') {
                                                                    echo "selected=selected";
                                                                }
                                                            } ?>>Accountant</option>
									<option value='Employee' <?php if (isset($formData['user_type'])) {
									if ($formData['user_type'] == 'Employee') {
										echo "selected=selected";
									}
								} ?>>Employee</option>
                                </select>
                            </div>
                        </div>
				<div class="col-md-6">
					<div class="mb-3"><label for="exampleEmail11" class="">Status</label>
						<select class="form-control" name='status'>
							<option value='Active' <?php if(isset($formData['status'])){ if($formData['status']=='Active'){ echo "selected=selected";} } ?> >Active</option>
							<option value='Inactive' <?php if(isset($formData['status'])){ if($formData['status']=='Inactive'){ echo "selected=selected";} } ?> >Inactive</option>
						</select>
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
  