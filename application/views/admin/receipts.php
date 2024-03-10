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
                $url = CONFIG_SERVER_ADMIN_ROOT . "receipts/add";
                echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="warehouse" class="">Select Warehouse <span class="text-danger">*</span></label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control">
                                <option value="">Select Warehouse</option>
                                <?php if (!empty($warehouses)) {
                                    foreach ($warehouses as $warehouse) {
                                ?>
                                        <option value="<?= $warehouse['id']; ?>" data-gst="<?= $warehouse['gst']; ?>" data-pst="<?= $warehouse['pst']; ?>" <?php if(isset($formData) && ($formData['warehouse_id']==$warehouse['id'])){ echo "selected"; } ?> ><?= $warehouse['warehouse_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="supplier" class="">Select Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select Supplier</option>
                                <?php if (!empty($suppliers)) {
                                    foreach ($suppliers as $supplier) {
                                ?>
                                        <option value="<?= $supplier['id']; ?>" <?php if(isset($formData) && ($formData['supplier_id']==$supplier['id'])){ echo "selected"; } ?> ><?= $supplier['supplier_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date" class="">Receipt Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" value="<?php if (isset($formData['receipt_date'])) { echo $formData['receipt_date']; } ?>" name="receipt_date">
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
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </thead>
                            <tbody class="purchase_body">

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
                            <tr>
                                <td>Final Amount</td>
                                <td><input type="text" class="form-control Onlynumbers" name="final_amount" placeholder="Enter Final Amount"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td colspan="3" class="fw-bold text-end">Sub Total</td>
                                <td><input type="text" name="sub_total" readonly  class="subtotal_price form-control" placeholder="Sub Total"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="fw-bold text-end">GST (<span class="gst_span">0</span> %)</td>
                                <td><input type="text" readonly name="gst" class="gst form-control" placeholder="Total">
                                    <input type="hidden" name="gst_per" class="gst_per">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="fw-bold text-end">PST (<span class="pst_span">0</span> %)</td>
                                <td><input type="text" readonly name="pst" class="pst form-control" placeholder="Total">
                                    <input type="hidden" name="pst_per" class="pst_per">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="fw-bold text-end">Total</td>
                                <td><input type="text" readonly  name="grand_total" class="total_price form-control" placeholder="Total"></td>
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

            $("#warehouse_id").change(function(){
                var gst = $("#warehouse_id option:selected").attr('data-gst');
                var pst = $("#warehouse_id option:selected").attr('data-pst');
                $(".pst_per").val(pst);
                $(".gst_per").val(gst);
                $(".gst_span").text(gst);
                $(".pst_span").text(pst);
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
                        url: '<?php echo base_url(); ?>administrator/inventory/addProduct',
                        type: 'POST',
                        data: {"id": id},
                        success: function(data) {
                            var result = JSON.parse(data);
                            if (result.error == '0') {
                                $(".purchase_body").append(result.html);
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
                var price = $(".price_"+elementId).val();
                var qty = $(".qty_"+elementId).val();
                var total = price * qty;
                $(".total_"+elementId).val(total.toFixed(2));
                calculateGrandTotal();
            }

            function calculateGrandTotal(){
                var subtotal_price = 0;
                var qty = 0;
                var gstpercentage = $('.gst_per').val();
                var pstpercentage = $('.pst_per').val();
                $(".total").each(function(){
                    var thisTotal = $(this).val();
                    if(thisTotal>0 && thisTotal!=undefined){
                        subtotal_price = subtotal_price + parseFloat(thisTotal);
                    }
                });
                var gst = (subtotal_price * gstpercentage) / 100;
                var pst = (subtotal_price * pstpercentage) / 100;
                var grandtotal = gst + subtotal_price + pst;
                $(".gst").val(gst);
                $(".pst").val(pst);
                $(".subtotal_price").val(subtotal_price.toFixed(2));
                $(".total_price").val(grandtotal.toFixed(2));
            }
            function calculateNet(elementId){
                var gross = $(".gross_"+elementId).val();
                var tare = $(".tare_"+elementId).val();
                var net = gross - tare;
                $(".qty_"+elementId).val(net.toFixed(2));
                calculateTotal(elementId);
            }
            function removeRow(id){
                if(id!=''){
                    $(".tr_"+id).hide(500, function(){
                        this.remove(); 
                        calculateGrandTotal();   
                    });
                    
                }
            }
        </script>
    <?php } else { ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <?php if($this->session->user_type == 'Admin'){ ?>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                    <label for="">Select Employee</label>
                                        <select name="employee" id="employee" class="form-control">
                                            <option value="All">All</option>
                                            <?php if(!empty($employees)){ 
                                            foreach($employees as $employee){    
                                            ?>
                                            <option value="<?= $employee['id'];?>"><?= $employee['first_name'];?> (<?= $employee['username']; ?>) </option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-3">
                                <div class="mb-3">
                                <label for="">Select Supplier</label>
                                    <select name="supplier" id="supplier" class="form-control">
                                        <option value="All">All</option>
                                        <?php if(!empty($suppliers)){ 
                                        foreach($suppliers as $supplier){    
                                        ?>
                                        <option value="<?= $supplier['id'];?>"><?= $supplier['company_name'];?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control daterange" id="daterange">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive tasks dataGridTable">
                            <table id="receiptList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Receipt Number</th>
                                        <th>Supplier Name</th>
                                        <th>Total</th>
                                        <th>Receipt Date</th>
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
        var supplier = $("#supplier").val();
        var employee = $("#employee").val();
        var date = $("#daterange").val();
        $('#receiptList').DataTable({
            "destroy": true,
            "responsive": false,
            "processing": true,
            "serverSide": true,
            "order": [
                [4, "desc"]
            ],
            "ajax": {
                "url": "<?php echo CONFIG_SERVER_ADMIN_ROOT ?>receipts/ajaxListing",
                "type": 'POST',
                'data': {
                    employee:employee,
                    supplier:supplier,
                    date:date
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