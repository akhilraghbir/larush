<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Month</label>
                                <input type="month" class="form-control" id="month" name="month">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Supplier</label>
                                <select class="form-control" id="supplier_id" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    <?php if(!empty($suppliers)){
                                        foreach($suppliers as $supplier){
                                    ?>
                                    <option value="<?= $supplier['id']; ?>"><?= $supplier['supplier_name']; ?> - (<?= $supplier['company_name'];?>)</option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="button" style="margin-top:28px" onclick="getdata()" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive tasks dataGridTable">
                        <table id="PurchaseReport" class="table d-none" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
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
        var month = $("#month").val();
        var supplier_id = $("#supplier_id").val();
        $.ajax({
            type:"post",
            url:base_url + 'administrator/PurchaseReport/getReport',
            data:{month:month,supplier_id:supplier_id},
            beforeSend:function(){
                $("#PurchaseReport").addClass('d-none');
                $(".tbody").html('');
            },
            success:function(res){
                var resp = JSON.parse(res);
                if(resp.error == 0){
                    $("#PurchaseReport").removeClass('d-none');
                    $(".tbody").html(resp.html);
                }else{
                    toastr['error']('No Data Found');
                }
            }
        });
    }
</script>
