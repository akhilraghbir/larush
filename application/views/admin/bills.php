<div class="col-md-12">
  <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('administrator/bills/export');?>" id="export" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Reference Number</label>
                                <input type="text" name="reference_number" class="form-control" id="reference_number" placeholder="Enter Reference Number">
                            </div>
                        </div>
                        
                        <?php if($this->session->user_type == 'Admin'){?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Accountant</label>
                                <select name="accountant" id="accountant" class="form-control">
                                    <option value="All">All</option>
                                    <?php if(!empty($accountants)){
                                        foreach($accountants as $accountant){ ?>
                                        <option value="<?= $accountant['id'];?>"><?= $accountant['name'];?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <?php }else{?>
                            <input type="hidden" id="accountant" name="accountant" value="<?= $this->session->id; ?>">
                        <?php } ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Client</label>
                                <select name="client" id="client" class="form-control">
                                    <option value="All">All</option>
                                    <?php if(!empty($clients)){
                                        foreach($clients as $client){ ?>
                                        <option value="<?= $client['id'];?>"><?= $client['name'];?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Financial Year</label>
                                <select name="financial_year" id="financial_year" class="form-control">
                                    <?php if(!empty($finanicial_years)){
                                        foreach($finanicial_years as $years){ ?>
                                        <option value="<?= $years['id'];?>" <?= ($years['status'] =='Active') ? 'selected' : ''; ?>><?= $years['financial_year'];?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Month </label>
                                <select name="month" id="month" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach(months() as $month => $monthname){ ?>
                                        <option value="<?= $month;?>"  <?= (date('m') == $month) ? 'selected' : ''; ?> ><?= $monthname;?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Bill Type</label>
                                <select name="bill_type" id="bill_type" class="form-control">
                                    <option value="All" selected>All</option>
                                    <option value="Income">Income</option>
                                    <option value="Expense">Expense</option>
                                </select>
                            </div>
                        </div>
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
                                <button type="submit" name="submit" id="submitBtn" class="d-none btn btn-primary"></button>
                                <input type="hidden" name="type" value="" id="type">
                                <button type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary"><i class="icon-magnifier"></i> Search</button>
                            </div>
                        </div>
                    </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="text-right">
                    <button type="button" onclick="submitForm('Export')" style="margin-bottom:15px" class="btn btn-primary"><i class="icon-doc"></i> Export</button>
                    <button type="button" onclick="submitForm('Download Bills')"  style="margin-bottom:15px" class="btn btn-primary"><i class="icon-cloud-download"></i> Download Bills</button>
                </div>
                <div class="table-responsive tasks dataGridTable">
                        <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Ref Id</th>
                                    <th>Bill Title</th>
                                    <th>Bill Amount</th>
                                    <th>Payment Mode</th>
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
<div class="modal fade" id="large-Modal" tabindex="-1"role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bill Preview</h4>
                <button type="button" class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span
                        aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bill">
                
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-default waves-effect "
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function getdata() {
        var status = $("#status").val();
        var financial_year = $("#financial_year").val();
        var client = $("#client").val();
        var accountant = $("#accountant").val();
        var month = $("#month").val();
        var reference_number = $("#reference_number").val();
        var bill_type = $("#bill_type").val();
        var blist = $('#userList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>bills/ajaxListing",
                "type": 'POST',
                'data': {
                    status: status,
                    accountant:accountant,
                    client:client,
                    financial_year:financial_year,
                    month:month,
                    reference_number:reference_number,
                    bill_type:bill_type
                }
            },
        });
    }
    getdata();
    function requestEdit(id){
        if(id!=''){
            $.confirm({
            title: 'Prompt!',
            content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Enter Reason For Edit</label>' +
            '<input type="text" placeholder="Reason For Edit" class="reason form-control" required />' +
            '</div>' +
            '</form>',
            buttons: {
                formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                        var reason = this.$content.find('.reason').val();
                        if(!reason){
                            $.alert('provide a valid reason');
                            return false;
                        }else{
                            $('#page-overlay').show();
                            $.ajax({
                                url: '<?php echo base_url(); ?>administrator/bills/requestEdit',
                                type: 'POST',
                                data: {
                                    "bill_id": id,
                                    "reason": reason
                                },
                                success: function(data) {
                                    result = JSON.parse(data);
                                    var msg = result.message;
                                    if (result.error == '0') {
                                        notify('top', 'right', 'fa fa-success', 'success', 'animated fadeInDown', 'animated fadeOutDown',msg);
                                        $('#userList').DataTable().ajax.reload();
                                    } else {
                                        notify('top', 'right', 'fa fa-danger','danger',  'animated fadeInDown', 'animated fadeOutDown',msg);
                                        $('#userList').DataTable().ajax.reload();
                                    }
                                },
                                error: function(e) {
                                    notify('top', 'right', 'fa fa-danger', 'danger', 'animated fadeInDown', 'animated fadeOutDown',e.msg);
                                    $('#userList').DataTable().ajax.reload();
                                }
                            });
                        }
                    }
                },
                cancel: function () {
                },
            },
            onContentReady: function () {
                // bind to events
                var jc = this;
                this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
            });
        }
    }
    $("#accountant").change(function(){
        $.ajax({
            url: '<?php echo base_url(); ?>administrator/clients/getClientsByAccountant',
            type: 'POST',
            data: { "accountant": $(this).val(),'type':'Firm'},
            success: function(data) {
                result = JSON.parse(data);
                $("#client").html(result.html);
            }
        });
    });
    
    function viewBill(id){
        $.ajax({
            url: '<?php echo base_url(); ?>administrator/bills/getBill',
            type: 'POST',
            data: { "billId": id},
            success: function(data) {
                result = JSON.parse(data);
                if(result.status == 0){
                    $("#bill").html(result.html);
                   $("#large-Modal").modal('show');
                }else{
                    console.log(result);
                }
            }
        });
    }
    
    function submitForm(type){
        $("#type").val(type);
        $("#submitBtn").click();
    }
</script>