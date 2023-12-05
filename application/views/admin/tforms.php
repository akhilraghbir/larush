<div class="col-md-12">
  <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('administrator/tforms/export');?>" id="export" method="post">
                    <div class="row">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="submit" id="submitBtn" class="d-none btn btn-primary"></button>
                                <input type="hidden" name="type" value="" id="type">
                                <button type="button" onclick="getdata()" name="submit" class="btn btn-primary"><i class="icon-magnifier"></i> Search</button>
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
                    <button type="submit" onclick="submitForm('Export')" style="margin-top:28px" name="submit" class="btn btn-primary" value="Export"><i class="icon-doc"></i> Export</button>
                    <button type="submit" onclick="submitForm('Download Bills')" style="margin-top:28px" name="submit" class="btn btn-primary" value="Download Tforms"><i class="icon-cloud-download"></i> Download Tforms</button>
                </div>
                    <div class="table-responsive tasks dataGridTable">
                        <table id="userList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Financial Year</th>
                                    <th>File</th>
                                    <th>Created On</th>

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
        var financial_year = $("#financial_year").val();
        var client = $("#client").val();
        var accountant = $("#accountant").val();
        var blist = $('#userList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>tforms/ajaxListing",
                "type": 'POST',
                'data': {
                    accountant:accountant,
                    client:client,
                    financial_year:financial_year,
                }
            },
        });
    }
    getdata();
 
    $("#accountant").change(function(){
        $.ajax({
            url: '<?php echo base_url(); ?>administrator/clients/getClientsByAccountant',
            type: 'POST',
            data: { "accountant": $(this).val(),'type':'Individual'},
            success: function(data) {
                result = JSON.parse(data);
                $("#client").html(result.html);
            }
        });
    });
    function submitForm(type){
        $("#type").val(type);
        $("#submitBtn").click();
    }
</script>