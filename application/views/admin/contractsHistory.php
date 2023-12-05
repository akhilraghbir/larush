<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="first name" class="">Select Client</label>
                                <input value="" name="user_id" id="client_id" placeholder="Please Enter Name or Email Id or Phone Number" autocomplete='off' type="text" class="ui-autocomplete-input ui-autocomplete-loading form-control">
                                <input value="" name="client_id" id="user_id" type="hidden">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="first name" class="">Select Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="All">All</option>
                                    <option value="Signed">Signed</option>
                                    <option value="Unsigned">Unsigned</option>
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
                        <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Reference Number</th>
                                    <th>Contract Name</th>
                                    <th>Client Name</th>
                                    <th>Signed</th>
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
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
function getdata() {
    var user_id = $("#user_id").val();
    var type = $("#type").val();
    $('#userList').DataTable({
    "destroy": true,
    "responsive": false,
    "processing": true,
    "serverSide": true,
    "order": [
        [0, "desc"]
    ],
    "ajax": {
        "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>ContractTemplates/contractsajaxListing",
        "type": 'POST',
        'data': {
            user_id: user_id,
            type:type
        }
    },
    });
}
getdata();

$('#client_id').keyup(function(){
    user_search();
});

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
            return false;
        }
    });
}
</script>