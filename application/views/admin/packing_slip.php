<html>
    <head>
        <title>Packing Slip</title>
        <style>
        table{
            border:1px solid black;
            width: 100%;
        }    
        table td{
            text-align: center;
            font-size: 20px;
        }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td>SKID : SKID</td>
            </tr>
            <tr>
                <td>Item : <?= $pname; ?></td>
            </tr>
            <tr>
                <td>Gross : <?= floatval($gross); ?></td>
            </tr>
            <tr>
                <td>Tare : <?= floatval($tare); ?></td>
            </tr>
            <tr>
                <td>Net : <?= floatval($net); ?></td>
            </tr>
        </table>
    </body>
    <script>
        window.print();
    </script>
</html>