<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Verdana;
            font-size: 12px;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }

        .container {
            width: 80%;
            margin: 10px auto;
            max-width: 794px;
            max-height: 1096px;
        }

        .header-address-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
        }

        .address {
            margin-top: 10px;
        }

        .content {
            clear: both;
            margin-top: 20px;
        }

        .content-title {
            font-size: 28px;
        }

        .table-title {
            font-size: 14px;
            text-align: center;
        }

        .service {
            width: 100%;
            border-collapse: collapse;
        }

        .service-title {
            text-align: left;
        }

        .border {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            padding: 8px;
        }

        .service-content {
            padding: 8px;
        }

        .total {
            font-size: 16px;
            font-weight: lighter;
        }

        .footer {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-address-container">
            <div class="header">
                <img src="typofx-2024.svg" alt="Logo">
                <div>Typo FX</div>
            </div>
            <div class="address">
                <br>TYPO FX Ireland<br> <br>
                WorkHub Group<br>
                77 Camden Street Lower Saint<br>
                Kevin's Dublin D02 XE80 Ireland<br> <br>
                CRO: XXXXXX<br>
                VAT: 1282313RA
            </div>
        </div>
        <div class="content">
            Name: Adam Soares <br>

            <h1 class="content-title">Billing Invoice</h1>
            <br> Services Rendered: Computing Service
            <br> Invoice serial number: XX.XX.XX.00
            <br> Invoice period: 01 July 2024 - 03 July 2024
            <br>
            <h6 class="table-title">Services</h6>
            <table class="service">
                <tr>
                    <th class="service-title border">Day</th>
                    <th class="service-title border">Date</th>
                    <th class="service-title border">Total</th>
                </tr>
                <tr>
                    <td class="service-content">Tuesday</td>
                    <td class="service-content">02 July 2024</td>
                    <td class="service-content">€11.22</td>
                </tr>
                <tr>
                    <td class="service-content">Wednesday</td>
                    <td class="service-content">03 July 2024</td>
                    <td class="service-content">€22.11</td>
                </tr>
                <tr>
                    <td class="border">Total</td>
                    <td class="border"></td>
                    <td class="border">€33.33</td>
                </tr>
            </table>
            <h6 class="table-title">Invoice Total</h6>
            <table class="service">
                <tr>
                    <th class="service-title border">Totals</th>
                    <th class="service-title border">Total</th>
                    <th class="service-title border">Gross</th>
                </tr>
                <tr>
                    <td class="service-content">Worked Hours</td>
                    <td class="service-content">€33.33</td>
                    <td class="service-content">€33.33</td>
                </tr>
                <tr>
                    <td class="service-content">Transaction Fee</td>
                    <td class="service-content">(€0.50)</td>
                    <td class="service-content">(€0.50)</td>
                </tr>
                <tr>
                    <td class="service-content">Adjustments</td>
                    <td class="service-content">--</td>
                    <td class="service-content">--</td>
                </tr>
                <tr>
                    <td class="border">Total</td>
                    <td class="border">€32.83</td>
                    <td class="border">€32.83</td>
                </tr>
            </table>
            <br>
            <h1 class="total">Total fee payable: €32.83</h1>
            <p class="footer">This billing invoice is issued in the name, and on behalf of, the supplier Adam Soares in
                accordance with the terms agreement between the parties</p>
        </div>
    </div>
</body>

</html>