<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Verdana;
            font-size: 10px;
        }

        img {
            margin-top: -100px;
            margin-bottom: -10%;
            max-width: 100px;
            max-height: 100px;
        }

        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 3%;
        }

        .three-columns {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 5px;
            margin-top: 3%;
        }

        .two-columns-small-gap {
            display: grid;
            grid-template-columns: 2fr 3fr;
            gap: 5px;
            margin-top: 3%;
        }

        .two-columns-small-width {
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 10px;
            margin-top: 3%;
        }

        .two-columns-border {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 3%;
            position: relative;
        }

        .two-columns-border::before {
            content: "";
            position: absolute;
            width: 1px;
            background-color: gray;
            top: -30%;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .container {
            width: 80%;
            margin: 10px auto;
            max-width: 794px;
            max-height: 1096px;
        }

        .header {
            margin-bottom: 10%;
        }

        .title {
            margin-top: 15%;
        }

        .address {
            margin-top: 20px;
            font-size: 16px;
        }

        .invoice-title {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .invoice-info {
            text-align: left;
            margin-left: 2%;
            font-size: 14px;
        }

        .invoice-title h2 {
            margin: 0;
        }

        .invoice-title h3 {
            margin: 0;
            font-weight: normal;
        }

        .details,
        .summary {
            width: 100%;
            margin-top: 20px;
        }

        .left-align {
            text-align: left;
        }

        .right-align {
            text-align: right;
        }

        .divisor {
            border: none;
            border-top: 2px solid #000;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .table-container {
            height: 300px;
            max-height: 300px;
        }

        .summary {
            width: 40%;
            float: right;
            text-align: right;
            font-size: small;
        }

        .row-number {
            color: lightgray;
        }

        .euros {
            font-weight: bold;
        }

        .footer {
            margin-top: -4%;
            margin-left: 5%;
        }

        .footer-right {
            margin-top: -4%;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="two-columns">
                <div class="title">
                    <img src="typofx-2024.svg" alt="Logo">
                    <br><br><br><br><br>Typo FX @ WorkHub Group, 77 Lower Camden Street, Dublin D02 XE80 Ireland
                    <div class="address">
                        <p>Typo FX @ WorkHub Group<br>77 Lower Camden Street<br>Dublin D02 XE80 Ireland</p>
                    </div>
                </div>
                <div class="invoice-info">
                    <div class="invoice-title">
                        <h2>INVOICE</h2>
                        <h3>1(1)</h3>
                    </div>
                    <hr>
                    <table>
                        <tr>
                            <td>Invoice number</td>
                            <td>20281</td>
                        </tr>
                        <tr>
                            <td>Invoice date</td>
                            <td>02.03.2017</td>
                        </tr>
                        <tr>
                            <td>Due date</td>
                            <td><strong>16.03.2017</strong></td>
                        </tr>
                        <tr>
                            <td>Delivery date</td>
                            <td>20.02.2017</td>
                        </tr>
                        <tr>
                            <td>Payment terms</td>
                            <td><strong>14 days net</strong></td>
                        </tr>
                        <tr>
                            <td>Our reference</td>
                            <td>Marc Miller</td>
                        </tr>
                        <tr>
                            <td>Your reference</td>
                            <td>James Anderson</td>
                        </tr>
                        <tr>
                            <td>Buyer's order number</td>
                            <td>1234</td>
                        </tr>
                        <tr>
                            <td>Penalty interest</td>
                            <td>7.50 %</td>
                        </tr>
                        <tr>
                            <td>Customer's business ID</td>
                            <td>1212121-2</td>
                        </tr>
                        <tr>
                            <td>Customer number</td>
                            <td>2</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <hr class="divisor">
        <p>Order delivered according to the accepted offer 19.2.2017</p>
        <div class="table-container">
            <table class="details">
                <tr>
                    <th></th>
                    <th class="left-align">Product No.</th>
                    <th class="left-align">Description</th>
                    <th class="right-align">Unit price €</th>
                    <th class="right-align">Qty</th>
                    <th class="right-align">VAT %</th>
                    <th class="right-align">Total €</th>
                </tr>
                <tr>
                    <td class="row-number">1. </td>
                    <td class="left-align">18</td>
                    <td class="left-align">Curry, 280g</td>
                    <td class="right-align">4,50</td>
                    <td class="right-align">50 pcs</td>
                    <td class="right-align">19</td>
                    <td class="right-align">225,00</td>
                </tr>
                <tr>
                    <td class="row-number">2. </td>
                    <td class="left-align">16</td>
                    <td class="left-align">Stubb's Beef Spice Rub, 56g</td>
                    <td class="right-align">5,90</td>
                    <td class="right-align">10 pcs</td>
                    <td class="right-align">19</td>
                    <td class="right-align">59,00</td>
                </tr>
                <tr>
                    <td class="row-number">3. </td>
                    <td class="left-align">15</td>
                    <td class="left-align">Tex-Mex spice mix, 370g</td>
                    <td class="right-align">6,00</td>
                    <td class="right-align">5 pcs</td>
                    <td class="right-align">19</td>
                    <td class="right-align">30,00</td>
                </tr>
                <tr>
                    <td class="row-number">4. </td>
                    <td class="left-align">13</td>
                    <td class="left-align">Stubb's Oregano, 30g</td>
                    <td class="right-align">2,90</td>
                    <td class="right-align">15 pcs</td>
                    <td class="right-align">19</td>
                    <td class="right-align">43,50</td>
                </tr>
            </table>
            <hr>
            <div class="summary">
                <div class="two-columns-small-width">
                    <div>
                        Total excluding VAT € <br>
                        VAT total € <br>
                        Total to pay €
                    </div>
                    <div class="euros">
                        357,50 <br>
                        67,92 <br>
                        425,42
                    </div>
                </div>
            </div>
        </div>
        <hr class="divisor">
        <div class="two-columns">
            <div class="footer">
                <p style="font-weight: bold;">Due date</p>
                <p style="font-size: 16px;">16.03.2017</p>
            </div>
            <div class="footer-right">
                <p style="font-size: 18px;"><strong>Total</strong> 452,42 €</p>
            </div>
        </div>
        <hr>
        <div class="two-columns-border">
            <div class="footer">
                <div class="three-columns">
                    <div>
                        <strong>BIC/SWIFT</strong> <br> <br>
                        NDEAFIHH
                    </div>
                    <div>
                        <strong>Bank Account Number / IBAN</strong> <br>
                        FI21 1234 5600 0007 85
                    </div>
                    <div>
                        <br> <br>
                        Nordea
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="two-columns-small-gap">
                    <div>
                        Typo FX @ WorkHub Group. <br>
                        77 Lower Camden Street. <br>
                        Dublin D02 XE80 Ireland.
                    </div>
                    <div>
                        Tel: +358207181710 <br>
                        info@spiceimporter.com <br>
                        VAT number: IE1282313RA
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>