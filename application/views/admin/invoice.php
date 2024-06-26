<html>
    <style>
        .table{
            border:1px solid #ccc;
            border-collapse: collapse;
            width: 100%;
        }
        .table td,th{
            border: 1px solid #ccc;
            padding: 5px;
        }
        .text-center{
            text-align: center;
        }
        .bold{
            font-weight: bold;
        }
        .text-right{
            text-align: right;
        }
    </style>
    <table class="table">
        <tr>
            <td colspan="5"><img src="<?= base_url($settings[4]['value'])?>"></td>
        </tr>
        <tr>
            <td class="text-center bold" colspan="5">Receipt</td>
        </tr>
        <tr>
            <td colspan="2"><b>Supplier:</b><br>
                <?= $supplier[0]['company_name'] ?>,<br>
                <?= $supplier[0]['company_address'] ?>,<br>
                <?= $supplier[0]['supplier_name'] ?>
            </td>
            <td colspan="3">
                Receipt No: <?= $purchase[0]['receipt_number']?><br>
                Receipt Date: <?= displayDateInWords($purchase[0]['receipt_date']);?><br>
                Generated By: <?= ucwords($user[0]['first_name']);?>
            </td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php
        if(!empty($purchase_items)){ 
            $i=0;
        foreach($purchase_items as $items){    
        ?>
        <tr>
            <td><?= ++$i; ?></td>
            <td><?= $items['product_name']; ?></td>
            <td><?= $items['quantity']; ?></td>
            <td><?= ($items['price']>0) ? '$ '.$items['price'] : '-' ; ?></td>
            <td>$ <?= $items['total']; ?></td>
        </tr>
        <?php } } ?>
        <tr>
            <td class="bold text-right" colspan="4">Subtotal :</td>
            <td>$ <?= $purchase[0]['sub_total'];?></td>
        </tr>
        <tr>
            <td class="bold text-right" colspan="4">GST :</td>
            <td>$ <?= $purchase[0]['gst'];?></td>
        </tr>
        <tr>
            <td class="bold text-right" colspan="4">PST :</td>
            <td>$ <?= $purchase[0]['pst'];?></td>
        </tr>
        <tr>
            <td class="bold text-right" colspan="4">Total :</td>
            <td>$ <?= $purchase[0]['grand_total'];?></td>
        </tr>
        <?php if((int)$purchase[0]['final_amount']!=0){ ?>
        <tr>
            <td class="bold">Round Off :</td>
            <td colspan="4">$ <?= $purchase[0]['final_amount'] - $purchase[0]['grand_total'];?></td>
        </tr>
        <?php } ?>
        <tr>
            <td class="bold">Note :</td>
            <td colspan="4"><?= $purchase[0]['notes'];?></td>
        </tr>
    </table>
    <p class="bold">* This is computer generated invoice and does not required signature.</p>
</html>