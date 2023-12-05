<div class="col-md-12">
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

<?php if(isset($form_action)){ ?>
                <div class="">
                <div class="main-card mb-3 card card-body"><h5 class="card-title"></h5>
                <?php  
                        if(isset($formData['id'])){
                            $id=$formData['id'];
                            $url=CONFIG_SERVER_ADMIN_ROOT."ContractTemplates/edit/$id";
                        }else {
                            $url = CONFIG_SERVER_ADMIN_ROOT . "ContractTemplates/add";
                        }
                       
                        echo form_open_multipart($url,array('class' => 'headerMenu','id' => 'headerMenu')); ?> 

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                <label for="first name" class="">Template Name</label>
                                <input value="<?php if(isset($formData['template_name'])){echo $formData['template_name'];} ?>" name="template_name" id="template_name" placeholder="Please Enter Template Name"  autocomplete='off' type="text" class="form-control" maxlength="100"></div>
                            </div>
                       </div>
                       <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                <label for="first name" class="">Template Body</label>
                                <textarea name="template_body"  placeholder="Please Enter Template Body"  autocomplete='off'  class="form-control"><?php if(isset($formData['template_body'])){echo $formData['template_body'];} ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Status</label>
                                <select class="form-control" name='status'>
                                    <option value='Active' <?php if(isset($formData['status'])){ if($formData['status']=='Active'){ echo "selected=selected";} } ?> >Active</option>
                                    <option value='Inactive' <?php if(isset($formData['status'])){ if($formData['status']=='Inactive'){ echo "selected=selected";} } ?> >Inactive</option>
                                </select>
                            </div>
                        </div>
         
                        <div class="position-relative form-check">
                            <?php if(isset($formData['id'])){ ?>
                                <input type="submit" name="edit" class="mt-2 btn btn-primary pull-right" value="Update">
                            <?php }else{ ?>
                                <input type="submit" name="add" class="mt-2 btn btn-primary pull-right resetSubmit" value="submit">
                            <?php } ?>
                        </div>
                    </form>
                </div>
        </div>
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace( 'template_body' );
</script>
<?php }else{ ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body"> 
                    <div class="table-responsive tasks dataGridTable">
                    <table id="HeaderMenuList" class="table card-table table-vcenter text-nowrap mb-0 border">
                        <thead>
                            <tr>        
                                <th class="wd-lg-30p">Template Name</th>
                                <th class="wd-lg-30p">Status</th>
                                <th class="wd-lg-30p">Created On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
            $(document).ready(function() {
                $('#HeaderMenuList').DataTable({
                    "responsive": false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url" : "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>ContractTemplates/ajaxListing",
                        "type" : 'POST'
                    },
                });
            });
        </script>
<?php } ?>
</div>

                   