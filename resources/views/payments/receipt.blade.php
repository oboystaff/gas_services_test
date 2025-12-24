<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Payment Receipt - {{ $receipt_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 10px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 10px;
        }

        .header .logo {
            position: absolute;
            left: 15px;
            top: 15px;
            width: 70px;
            height: 70px;
            background: #d00;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header .logo-icon {
            color: white;
            font-size: 40px;
        }

        .header .company-info {
            margin-left: 95px;
        }

        .header h1 {
            color: #d00;
            margin: 0 0 8px 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header .contact-info {
            font-size: 10px;
            line-height: 1.6;
            margin: 0;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 8pt;
            color: #666;
            font-style: italic;
            margin-bottom: 5px;
        }

        .company-contact {
            font-size: 7pt;
            color: #666;
            margin-top: 5px;
        }

        .receipt-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin: 15px 0 10px 0;
            color: #2c5aa0;
            text-transform: uppercase;
            border-bottom: 2px solid #2c5aa0;
            margin-top: -12px;
        }

        .receipt-number {
            text-align: left;
            font-size: 8pt;
            font-weight: normal;
            margin-bottom: 15px;
            color: #555;
            margin-left: 5px;
        }

        .receipt-number strong {
            color: #555;
        }

        .two-column-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e0e0e0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 5px;
            font-size: 8pt;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-table td:first-child {
            font-weight: 600;
            color: #555;
            width: 40%;
        }

        .info-table td:last-child {
            color: #333;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .service-table thead {
            background-color: #2c5aa0;
            color: white;
        }

        .service-table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
        }

        .service-table td {
            padding: 8px;
            font-size: 8pt;
            border-bottom: 1px solid #e0e0e0;
        }

        .service-table tbody tr:last-child td {
            border-bottom: 2px solid #2c5aa0;
        }

        .amount-cell {
            text-align: right;
            font-weight: bold;
            color: #2c5aa0;
        }

        .total-section {
            background-color: #f8f9fa;
            padding: 12px;
            margin-top: 10px;
            border-left: 3px solid #2c5aa0;
        }

        .total-row {
            display: table;
            width: 100%;
        }

        .total-row span:first-child {
            display: table-cell;
            font-size: 11pt;
            font-weight: bold;
            color: #2c5aa0;
        }

        .total-row span:last-child {
            display: table-cell;
            text-align: right;
            font-size: 11pt;
            font-weight: bold;
            color: #2c5aa0;
        }

        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .thank-you {
            font-size: 10pt;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 8px;
        }

        .powered-by {
            font-size: 7pt;
            color: #999;
            margin-top: 8px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60pt;
            color: rgba(44, 90, 160, 0.04);
            font-weight: bold;
            z-index: -1;
        }

        .date-stamp {
            text-align: right;
            font-size: 7pt;
            color: #666;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="watermark">MANBAH GAS COMPANY LTD</div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ public_path('assets/images/mambah_logo.jpeg') }}" alt="Manbah Gas Logo"
                        style="width:82px;">
                </div>
            </div>
            <div class="company-info">
                <h1>MANBAH GAS COMPANY LTD.</h1>
                <div class="contact-info">
                    Head Office Location:<br>
                    No. 3 Building Off Terazzo Road, Odorkor - N1 Highway<br>
                    Box KN 5513, KANESHIE Accra Ghana, West Africa.<br>
                    Mobile No.: 0558 202675<br>
                    Fax.: 0302 319166<br>
                    Line: 0302 315621<br>
                    E-mail: manbahgas@yahoo.com<br>
                    fdegos@yahoo.com
                </div>
            </div>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">Service Payment Receipt</div>

        <div class="receipt-number">
            <strong>CUSTOMER:</strong> {{ $customer_name }}<br>
            <strong>RECEIPT NO:</strong> {{ $receipt_no }}<br>
            <strong>PAYMENT DATE:</strong> {{ $payment_date }}
        </div>

        <!-- Two Column Section: Customer Info & Payment Summary -->
        <div class="two-column-section">
            <!-- Left Column: Customer Information -->
            <div class="column">
                <div class="section">
                    <div class="section-title">Billing Information</div>
                    <table class="info-table">
                        <tr>
                            <td>Customer Name:</td>
                            <td>{{ $customer_name }}</td>
                        </tr>
                        <tr>
                            <td>Customer #:</td>
                            <td>{{ $customer_id }}</td>
                        </tr>
                        <tr>
                            <td>Phone No:</td>
                            <td>{{ $phone_no }}</td>
                        </tr>
                        <tr>
                            <td>Delivery Branch:</td>
                            <td>{{ $delivery_branch }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Right Column: Payment Summary -->
            <div class="column">
                <div class="section">
                    <div class="section-title">Payment Summary</div>
                    <table class="info-table">
                        <tr>
                            <td>Payment Mode:</td>
                            <td>{{ $payment_mode }}</td>
                        </tr>
                        <tr>
                            <td>Reference:</td>
                            <td style="font-size: 7pt;">{{ $reference }}</td>
                        </tr>
                        <tr>
                            <td>Paid By:</td>
                            <td>{{ $paid_by }}</td>
                        </tr>
                        <tr>
                            <td>Total Amount:</td>
                            <td><strong>GHC {{ number_format($amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Service Details -->
        <div class="section">
            <div class="section-title">Description</div>
            <table class="service-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                        <th style="text-align: right;">Amount (GHC)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $description }}</td>
                        <td>{{ $payment_date }}</td>
                        <td class="amount-cell">{{ number_format($amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total Amount -->
        <div class="total-section">
            <div class="total-row">
                <span>TOTAL AMOUNT PAID:</span>
                <span>GHC {{ number_format($amount, 2) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">Thank you for choosing Manbah Gas Company Limited!</div>
            <p style="font-size: 7pt; color: #666;">
                This is a computer-generated receipt and does not require a signature.
            </p>
            <div class="powered-by">
                Provider: MANBAH GAS COMPANY LIMITED
            </div>
            <div style="margin-top: 40px; text-align: right; padding-right: 10px;">
                <div style="width: 200px; float: right; text-align: center;">
                    <div style="margin-bottom: -60px">
                        <img src="{{ public_path('assets/images/manbah_signature.jpg') }}" alt="Manbah Signature"
                            style="width:120px; margin-bottom:-160pxpx;">
                    </div>
                    <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                    <div style="font-size: 8pt; color: #333; margin-top: 3px;">Authorised Signature</div>
                </div>
            </div>
            <div class="date-stamp">
                Generated on: {{ $generated_date }}
            </div>
        </div>
    </div>
</body>

</html>
