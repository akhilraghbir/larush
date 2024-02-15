<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<div class="col-md-12">
    <?php
    defined('BASEPATH') or exit('No direct script access allowed');
    if (isset($data)) {
        if (!empty($data)) {
            $formData = $data;
        } else {
            $formData = $this->form_validation->get_session_data();
        }
    } else {
        $formData = $this->form_validation->get_session_data();
    }
    ?>
    <?php if (isset($form_action)) { ?>
        <div class="">
            <div class="main-card mb-3 card card-body">
                <h5 class="card-title"></h5>
                <?php
                $url = CONFIG_SERVER_ADMIN_ROOT . "dispatch/add";
                echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="buyer_id" class="">Select Buyer <span class="text-danger">*</span></label>
                            <select name="buyer_id" id="buyer_id" class="form-control">
                                <option value="">Select Buyer</option>
                                <?php if (!empty($buyers)) {
                                    foreach ($buyers as $buyer) {
                                ?>
                                        <option value="<?= $buyer['id']; ?>" <?php if(isset($formData) && ($formData['buyer_id']==$buyer['id'])){ echo "selected"; } ?> ><?= $buyer['buyer_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="">Dispatch Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" value="<?php if (isset($formData['dispatch_date'])) { echo $formData['dispatch_date']; } ?>" name="dispatch_date">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input type="text" id="product" class="form-control" placeholder="Enter Product Name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Product Name</th>
                                <th>Gross</th>
                                <th>Tare</th>
                                <th>Net</th>
                                <th>Actions</th>
                            </thead>
                            <tbody class="dispatch_body">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td>Notes</td>
                                <td><textarea class="form-control" name="notes" placeholder="Enter Notes"></textarea>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td colspan="3" class="fw-bold text-end">Total Net</td>
                                <td><input type="text" name="total_net" readonly  placeholder="Total Net" class="total_net form-control" placeholder="Total Net"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="fw-bold text-end">Total Gross</td>
                                <td><input type="text" readonly  name="total_gross" placeholder="Total Gross" class="total_gross form-control" placeholder="Total Gross"></td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                <div>
                    <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Generate Receipt">
                </div>
                </form>
            </div>
        </div>
        <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $('#product').keyup(function() {
                product_search();
            });

            function product_search() {
                $('#product').autocomplete({
                    source: function(request, response) {
                        var input = this.element;
                        $.ajax({
                            url: base_url + 'administrator/inventory/getProducts',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                term: request.term,
                                is_catalytic:'no'
                            },
                            success: function(data) {
                                if (data.length > '0') {
                                    response($.map(data, function(item) {
                                        Object.values(item);
                                        return {
                                            id: item.id,
                                            value: item.name
                                        };
                                    }));
                                }
                            }
                        });
                    },
                    select: function(event, ui) {
                        var input = $(this);
                        addProduct(ui.item.id);
                        $(this).val(ui.item.label);
                        return false;
                    }
                });
            }

            function addProduct(id = null) {
                if (id != '') {
                    $.ajax({
                        url: '<?php echo base_url(); ?>administrator/dispatch/dispatchProduct',
                        type: 'POST',
                        data: {"id": id},
                        success: function(data) {
                            var result = JSON.parse(data);
                            if (result.error == '0') {
                                $(".dispatch_body").append(result.html);
                                $('#product').val('');
                            } else {
                                console.log(result);
                            }
                        },
                        error: function(e) {
                            console.log(e.message);
                        }
                    });
                }
            }
            function calculateTotal(elementId){
                if(elementId!=''){
                    var gross = $(".gross_"+elementId).val();
                    var tare = $(".tare_"+elementId).val();
                    var total = parseFloat(gross) - parseFloat(tare);
                    $(".net_"+elementId).val(total.toFixed(2));
                    calculateGrandTotal();
                }
            }

            function calculateGrandTotal(){
                var tot_gross = 0;
                var tot_net = 0;
                $(".gross").each(function(){
                    tot_gross = tot_gross + parseFloat($(this).val());
                });
                $(".net").each(function(){
                    tot_net = tot_net + parseFloat($(this).val());
                });
                $(".total_gross").val(tot_gross.toFixed(2));
                $(".total_net").val(tot_net.toFixed(2));
            }
            function removeRow(id){
                if(id!=''){
                    $(".tr_"+id).hide(500, function(){
                        this.remove(); 
                        calculateGrandTotal();   
                    });
                    
                }
            }
            function print(elementId){
                if(elementId!=''){
                    var pname = $(".gross_"+elementId).attr('data-pname');
                    var gross = $(".gross_"+elementId).val();
                    var net = $(".net_"+elementId).val();
                    var tare = $(".tare_"+elementId).val();
                    if(gross == '' || gross ==undefined){
                        toastr['error']('Please enter gross');return false;
                    }
                    if(tare == '' || tare ==undefined || tare == 0){
                        toastr['error']('Please enter tare');return false;
                    }
                    var data = JSON.stringify({pname:pname,gross:gross,net:net,tare:tare});
                    var token = window.btoa(data);     
                    //console.log(window.btoa(data));return false;
                    window.open(base_url+'administrator/dispatch/packing_slip/'+token);
                }
            }
        </script>
    <?php } else { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                <label for="">Select Buyer</label>
                                    <select name="buyer" id="buyer" class="form-control">
                                        <option value="All">All</option>
                                        <?php if(!empty($buyers)){ 
                                        foreach($buyers as $buyer){    
                                        ?>
                                        <option value="<?= $buyer['id'];?>"><?= $buyer['company_name'];?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="daterange">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive tasks dataGridTable">
                            <table id="dispatchList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Dispatch Number</th>
                                        <th>Company Name</th>
                                        <th>Dispacth Date</th>
                                        <th>Created On</th>
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
</div>

<script type="text/javascript">
    function getdata() {
        var date = $("#daterange").val();
        var buyer = $("#buyer").val();
        $('#dispatchList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [4, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>dispatch/ajaxListing",
                "type": 'POST',
                'data': {
                    date:date,
                    buyer:buyer
                }
            },
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });
    }
    getdata();
</script>

<?php } ?>