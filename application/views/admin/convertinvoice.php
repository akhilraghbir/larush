<div class="col-md-12">
    <div class="">
        <div class="main-card mb-3 card card-body">
            <h5 class="card-title"></h5>
            <?php
            $url = CONFIG_SERVER_ADMIN_ROOT . "invoices/add";
            echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="buyer_id" class="">Buyer <span class="text-danger">*</span></label>
                        <h2><?= $buyer[0]['buyer_name'] ?></h2>
                        <p><?= $buyer[0]['company_name'] ?></p>
                        <p><?= $buyer[0]['company_address'] ?></p>
                        <input name="buyer_id" type="hidden" value="<?= $dispatch[0]['buyer_id'] ?>">
                        <input name="dispatch_id" type="hidden" value="<?= $dispatch[0]['id'] ?>">
                        <input name="warehouse_id" type="hidden" value="<?= $dispatch[0]['warehouse_id'] ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date" class="">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" value="<?php if (isset($formData['invoice_date'])) {
                                                                            echo $formData['invoice_date'];
                                                                        } ?>" name="invoice_date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>Product Name</th>
                            <th>Net</th>
                            <th>Price</th>
                            <th>Total</th>
                        </thead>
                        <tbody class="dispatch_body">
                          <?php if(!empty($dispatch_items)){
                            $totalNet = $totalamt = 0;
                            foreach($dispatch_items as $items){ 
                                $total = $items['buyer_price'] * $items['net'];
                          ?>
                            <tr>
                                <td><input type="hidden" value="<?= $items['product_id'];?>" name="product_id[]"><?= $items['product_name'];?></td>
                                <td><input type="hidden" class="qty_<?= $items['product_id']; ?>" value="<?= $items['net'];?>" name="qty[]"><?= $items['net'];?></td>
                                <td><input type="text" name="price[]" onkeyup="calculateTotal(<?= $items['product_id']; ?>)" value="<?= $items['buyer_price'];?>" class="price_<?= $items['product_id'];?> form-control"></td>
                                <td><input type="text" name="total[]" readonly value="<?= $total;?>" class="total_<?= $items['product_id']; ?> total form-control"></td>
                            </tr>
                          <?php } } ?>
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
                            <td colspan="3" class="fw-bold text-end">Sub Total</td>
                            <td><input type="text" name="sub_total" readonly placeholder="Sub Total" value="" class="sub_total form-control"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="fw-bold text-end">GST</td>
                            <td><input type="text" name="gst" readonly placeholder="Total GST" value="" class="gst form-control"></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="fw-bold text-end">Total Gross</td>
                            <td><input type="text" readonly name="grand_total" placeholder="Total" value="" class="grand_total form-control"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div>
                <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Generate Invoice">
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    function calculateTotal(elementId){
        var price = $(".price_"+elementId).val();
        var qty = $(".qty_"+elementId).val();
        var total = price * qty;
        $(".total_"+elementId).val(total.toFixed(2));
        calculateGrandTotal();
    }

    function calculateGrandTotal(){
        var subtotal_price = 0;
        var gstpercentage = 5;
        $(".total").each(function(){
            var thisTotal = $(this).val();
            if(thisTotal>0 && thisTotal!=undefined){
                subtotal_price = subtotal_price + parseFloat(thisTotal);
            }
        });
        var gst = (subtotal_price * gstpercentage) / 100;
        var grandtotal = gst + subtotal_price;
        $(".gst").val(gst);
        $(".sub_total").val(subtotal_price.toFixed(2));
        $(".grand_total").val(grandtotal.toFixed(2));
    }
    calculateGrandTotal();
</script>