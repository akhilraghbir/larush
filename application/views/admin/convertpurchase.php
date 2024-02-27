<div class="col-md-12">
    <div class="">
        <div class="main-card mb-3 card card-body">
            <h5 class="card-title"></h5>
            <?php
            $url = CONFIG_SERVER_ADMIN_ROOT . "receipts/updateReceipt";
            echo form_open($url, array('class' => 'userRegistration', 'id' => 'userRegistration')); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="buyer_id" class="">Receipt Number : <?= $purchase[0]['receipt_number'];?></label>
                        <input name="purchase_id" type="hidden" value="<?= $purchase[0]['id'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>Product Name</th>
                            <th>Purchase Units</th>
                            <th>Weight</th>
                        </thead>
                        <tbody class="dispatch_body">
                          <?php if(!empty($dispatch_items)){
                            foreach($dispatch_items as $items){ 
                          ?>
                            <tr>
                                <td><input type="hidden" value="<?= $items['product_id'];?>" name="product_id[]"><?= $items['product_name'];?></td>
                                <td><?= $items['quantity'];?></td>
                                <td><input type="text" name="qty[]" value="" placeholder="Enter Weight" maxlength="10" class="price_<?= $items['product_id'];?> Onlynumbers form-control"></td>
                            </tr>
                          <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <input type="submit" name="add" class="mt-2 btn btn-primary pull-right" value="Update Receipt">
            </div>
            </form>
        </div>
    </div>
</div>
