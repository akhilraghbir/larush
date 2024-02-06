<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive tasks dataGridTable">
                        <table id="attendanceList" class="table card-table table-vcenter text-nowrap mb-0 border nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Product Name</th>
                                    <th>Sale Quantity</th>
                                    <th>Avg Purchase Price</th>
                                    <th>Avg Sale Price</th>
                                    <th>Profit / Loss</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($products)){
                                    $i=0;
                                    foreach($products as $product){
                                        $saleqty = (!empty($productsales[$product['id']]['qty'])) ? $productsales[$product['id']]['qty'] : '0';
                                        $avgsaleprice = (!empty($productsales[$product['id']]['avgprice'])) ? $productsales[$product['id']]['avgprice'] : '0';
                                        $avgpurchaseprice = (!empty($productpurchase[$product['id']]['avgprice'])) ? $productpurchase[$product['id']]['avgprice'] : '0';
                                        $totalsale = $saleqty * $avgsaleprice;
                                        $totalpurchase = $saleqty * $avgpurchaseprice;
                                        $total = $totalsale - $totalpurchase;
                                        $class = ($total>0) ? 'text-success' : (($total<0) ? 'text-danger' : '');
                                ?>
                                <tr>
                                    <td><?= ++$i; ?></td>
                                    <td><?= $product['product_name']; ?></td>
                                    <td><?= $saleqty;?></td>
                                    <td><?= number_format($avgpurchaseprice,2);?></td>
                                    <td><?= number_format($avgsaleprice,2);?></td>
                                    <td class="<?= $class; ?>"><?= $total; ?></td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

