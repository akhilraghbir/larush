<div class="col-md-12">
    <div class="main-card mb-3 card card-body">
    <h5 class="card-title"></h5>
        <?php  
            $url = CONFIG_SERVER_ADMIN_ROOT . "PushNotifications/add";
            echo form_open_multipart($url,array('class' => 'headerMenu','id' => 'headerMenu')); ?> 
            <div class="form-row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                    <label for="first name" class="">Title </label>
                    <input value="" name="title" id="title" placeholder="Please Enter Title"  autocomplete='off' type="text" class="form-control"></div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="first name" class="">To </label>
                        <select name="to" id="to" class="form-control">
                            <option value="All">All Users</option>
                            <option value="Individual">All Individual</option>
                            <option value="Firm">All Firms</option>
                            <option value="Only">Only</option>
                        </select>
                    </div>
                </div>
                <div class="d-none user col-md-6">
                    <div class="position-relative form-group">
                        <label for="first name" class="">Select User </label>
                        <input value="" name="search" id="user_search" placeholder="Please Enter Name or Email Id or Phone Number"  autocomplete='off' type="text" class="ui-autocomplete-input ui-autocomplete-loading form-control">
                        <input value="" name="user_id" id="user_id" type="hidden">
                    </div>
                </div>
           </div>
           <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                    <label for="first name" class="">Message</label>
                    <textarea name="message"  placeholder="Please Enter Message"  autocomplete='off'  class="form-control"></textarea>
                </div>
            </div>

            <div class="position-relative form-check">
                <input type="submit" name="add" class="mt-2 btn btn-primary pull-right resetSubmit" value="submit">
            </div>
        </form>
    </div>
</div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$("#to").change(function(){
    var val = $(this).val();
    if(val == 'Only'){
        $(".user").removeClass('d-none');
    }else{
        $(".user").addClass('d-none');
    }
});
$('#user_search').keyup(function(){
    user_search();
});
function user_search(){
    $('#user_search').autocomplete({
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
            return false;
        }
    });
}
</script>