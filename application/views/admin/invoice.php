<html>
    <style>
        .table{
            border:1px solid #ccc;
            border-collapse: collapse;
        }
        .table td,th{
            border: 1px solid #ccc;
        }
        .text-center{
            text-align: center;
        }
        .bold{
            font-weight: bold;
        }
    </style>
    <table class="table">
        <tr>
            <td colspan="4"><img src="<?= base_url($settings[4]['value'])?>"></td>
        </tr>
        <tr>
            <td class="text-center bold" colspan="4">Receipt</td>
        </tr>
        <tr>
            <td colspan="2">dfkjskf ks fs ss sv fs</td>
            <td colspan="2">
                Receipt No: <?= $purchase[0]['receipt_number']?><br>
                Receipt Date: <?= date("d-m-Y",strtotime($purchase[0]['receipt_date']));?>
            </td>
        </tr>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        
    </table>
</html>