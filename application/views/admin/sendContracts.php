<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<div class="col-md-12">
    <div class="">
        <div class="main-card mb-3 card card-body">
            <h5 class="card-title"></h5>
            <?php
            $url = CONFIG_SERVER_ADMIN_ROOT . "ContractTemplates/sendContract";
            echo form_open($url, array('class' => ' userRegistration', 'id' => 'userRegistration')); ?>

            <div class="form-row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="first name" class="">Select Client</label>
                        <input value="" name="user_id" id="client_id" placeholder="Please Enter Name or Email Id or Phone Number" autocomplete='off' type="text" class="ui-autocomplete-input ui-autocomplete-loading form-control">
                        <input value="" name="client_id" id="user_id" type="hidden">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="first name" class="">Select Contract</label>
                        <select name="contract_id" id="contract_id" class="form-control">
                            <option value="">Select Contract</option>
                            <?php if(!empty($contracts)){ 
                                foreach($contracts as $contract){ ?>
                                <option value="<?= $contract['id'];?>"><?= $contract['template_name'];?></option>
                                <?php } }?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label for="first name" class="">Template</label>
                        <textarea name="contract" id="template_body" placeholder="Please Enter Contract"  autocomplete='off'  class="form-control"></textarea>
                    </div>
                </div>
                <div class="position-relative form-check">
                    <input type="submit" name="add" class="mt-2 btn btn-primary pull-right resetSubmit" value="submit">
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'template_body' );
</script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('#client_id').keyup(function(){
    user_search();
});
$("#contract_id").change(function(){
    var id = $("#user_id").val();
    if(id == ''){
        notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown','Please Select Client');
        $(this).val('');
        return false;
    }else{
        loadContractData();
    }
})
function user_search(){
    $('#client_id').autocomplete({
    source: function (request, response) {
    var input = this.element;
    $.ajax({
        url: base_url+'administrator/users/getUsers',
        type: 'POST',
        dataType: 'json',
        data: {
            accountant:'',
            term: request.term,
        },
        success: function (data) {
            $("#UploadedDoc").html('');
            if (data.length == '0') {
                $("#user_id").val('');
            } else {
                response($.map(data, function (item) {
                    Object.values(item);
                   return {id: item.id, value: item.name};
                })
                );
            }
        }
    });
    }, select: function (event, ui) {
        var input = $(this);
        $("#user_id").val(ui.item.id);
        $(this).val(ui.item.label);
        setTimeout(loadContractData(),1000);
            return false;
        }
    });
}
function loadContractData(){
    var cid = $("#contract_id").val();
    var id = $("#user_id").val();
    if(id!='' && cid!=''){
        $.ajax({
        	url: base_url + "administrator/ContractTemplates/getContract",
        	type: "POST",
        	data: {'cid':cid,id:id},
        	success: function (resdata) {
        	    var res = JSON.parse(resdata);
        	    console.log(res);
        	    console.log(res.status);
        	    if(res.status == 'success'){
        	        console.log('erer');
        	        CKEDITOR.instances.template_body.setData(res.template);
        	        //$("#template_body").html();
        	    }else{
        	       notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown','Something went wrong, try after sometime'); 
        	    }
        	},
        	error: function () {},
        });
    }
}
</script>
