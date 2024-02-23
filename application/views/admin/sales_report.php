<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="last name" class="">Select Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control daterange" id="daterange">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Select Buyer</label>
                                <select class="form-control" id="buyer_id" name="user_id">
                                    <option value="">Select Buyer</option>
                                    <?php if(!empty($buyers)){
                                        foreach($buyers as $buyer){
                                    ?>
                                    <option value="<?= $buyer['id']; ?>"><?= $buyer['buyer_name']; ?> - (<?= $buyer['company_name'];?>)</option>
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
                        <table id="sellerReport" class="table d-none" style="width:100%">
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
        var date = $("#daterange").val();
        var buyer_id = $("#buyer_id").val();
        $.ajax({
            type:"post",
            url:base_url + 'administrator/SalesReport/getReport',
            data:{date:date,buyer_id:buyer_id},
            beforeSend:function(){
                $("#sellerReport").addClass('d-none');
                $(".tbody").html('');
            },
            success:function(res){
                var resp = JSON.parse(res);
                if(resp.error == 0){
                    $("#sellerReport").removeClass('d-none');
                    $(".tbody").html(resp.html);
                }else{
                    toastr['error']('No Data Found');
                }
            }
        });
    }
</script>
