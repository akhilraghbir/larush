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
            <td class="text-center bold" colspan="5">Dispatch</td>
        </tr>
        <tr>
            <td colspan="2"><b>Buyer:</b><br>
                <?= $buyer[0]['buyer_name'] ?>,<br>
                <?= $buyer[0]['company_name'] ?>,<br>
                <?= $buyer[0]['company_address'] ?>
            </td>
            <td colspan="3">
                Invoice No: <?= $invoice[0]['invoice_number']?><br>
                Invoice Date: <?= date("d-m-Y",strtotime($invoice[0]['invoice_date']));?>
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
        if(!empty($invoice_items)){ 
            $i=0;
        foreach($invoice_items as $items){    
        ?>
        <tr>
            <td><?= ++$i; ?></td>
            <td><?= $items['product_name']; ?></td>
            <td><?= $items['quantity']; ?></td>
            <td><?= $items['price']; ?></td>
            <td><?= $items['total']; ?></td>
        </tr>
        <?php } } ?>
        <tr>
            <td class="bold text-right" colspan="4">Total Net :</td>
            <td><?= $invoice[0]['sub_total'];?></td>
        </tr>
        <tr>
            <td class="bold text-right" colspan="4">Total GST :</td>
            <td><?= $invoice[0]['gst'];?></td>
        </tr>
        <tr>
            <td class="bold text-right" colspan="4">Total :</td>
            <td><?= $invoice[0]['grand_total'];?></td>
        </tr>
        <tr>
            <td class="bold">Note :</td>
            <td colspan="4"><?= $invoice[0]['notes'];?></td>
        </tr>
    </table>
    <p class="bold">* This is computer generated invoice slip and does not required signature.</p>
</html>