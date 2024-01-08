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
                Dispatch No: <?= $dispatch[0]['dispatch_number']?><br>
                Dispatch Date: <?= date("d-m-Y",strtotime($dispatch[0]['dispatch_date']));?>
            </td>
        </tr>
        <tr>
            <th>S.No</th>
            <th>Product Name</th>
            <th>Gross</th>
            <th>Tare</th>
            <th>Net</th>
        </tr>
        <?php
        if(!empty($dispatch_items)){ 
            $i=0;
        foreach($dispatch_items as $items){    
        ?>
        <tr>
            <td><?= ++$i; ?></td>
            <td><?= $items['product_name']; ?></td>
            <td><?= $items['gross']; ?></td>
            <td><?= $items['tare']; ?></td>
            <td><?= $items['net']; ?></td>
        </tr>
        <?php } } ?>
        <tr>
            <td class="bold text-right" colspan="4">Total Net :</td>
            <td><?= $dispatch[0]['total_net'];?></td>
        </tr>
        <tr>
            <td class="bold text-right" colspan="4">Total Gross :</td>
            <td><?= $dispatch[0]['total_gross'];?></td>
        </tr>
        <tr>
            <td class="bold">Note :</td>
            <td colspan="4"><?= $dispatch[0]['notes'];?></td>
        </tr>
    </table>
    <p class="bold">* This is computer generated dispatch slip and does not required signature.</p>
</html>