<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manbah Gas Invoice</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 10px;
        }

        /* Header */
        .header {
            border: 2px solid #000;
            padding: 8px;
            margin-bottom: 0;
            position: relative;
            background: #f5f5f5;
        }

        .header .logo {
            position: absolute;
            left: 8px;
            top: 8px;
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

        .header h2 {
            color: #d00;
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header .contact-info {
            font-size: 9px;
            line-height: 1.4;
        }

        .header .invoice-header {
            position: absolute;
            right: 8px;
            top: 8px;
            text-align: right;
        }

        .header .invoice-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header .customer-label {
            font-size: 10px;
            border: 1px solid #000;
            padding: 2px 5px;
            display: inline-block;
            margin-top: 5px;
        }

        /* Title Section */
        .title-section {
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 1px solid #000;
            padding: 8px;
            text-align: center;
            background: #fff;
        }

        .title-section h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Invoice Details */
        .invoice-details {
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 1px solid #000;
            display: table;
            width: 100%;
        }

        .invoice-details .left-column,
        .invoice-details .right-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 8px;
        }

        .invoice-details .right-column {
            border-left: 1px solid #000;
        }

        .detail-row {
            margin-bottom: 8px;
            min-height: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        .detail-label {
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 11px;
            font-weight: normal;
        }

        /* Product Table */
        .product-table {
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 1px solid #000;
        }

        .product-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-table th {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9px;
            text-align: center;
            background: #f5f5f5;
            font-weight: bold;
        }

        .product-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            min-height: 40px;
        }

        .product-table .total-row {
            font-weight: bold;
            background: #f9f9f9;
        }

        /* Footer */
        .footer {
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px;
        }

        .footer-section {
            margin-bottom: 8px;
            min-height: 25px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        .footer-label {
            font-size: 9px;
            text-transform: uppercase;
            display: inline-block;
            width: 120px;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 5px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 30px;
            margin-bottom: 5px;
        }

        .note {
            font-size: 9px;
            font-style: italic;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <div class="logo">
            <div class="logo-icon">
                <img src="{{ public_path('assets/images/mambah_logo.jpeg') }}" alt="Manbah Gas Logo" style="width:82px;">
            </div>
        </div>
        <div class="company-info">
            <h2>MANBAH GAS COMPANY LTD.</h2>
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
        <div class="invoice-header">
            <div class="invoice-number">N° {{ $invoice->invoice_no ?? '0011322' }}</div>
            <div class="customer-label">Customer</div>
        </div>
    </div>

    {{-- Title --}}
    <div class="title-section">
        <h3>SALES INVOICE</h3>
    </div>

    {{-- Invoice Details --}}
    <div class="invoice-details">
        <div class="left-column">
            <div class="detail-row">
                <div class="detail-label">SOLD TO (CUSTOMER'S NAME AND ADDRESS)</div>
                <div class="detail-value">{{ strtoupper($invoice->customer->name ?? 'N/A') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">DELIVERY BRANCH</div>
                <div class="detail-value">{{ strtoupper($invoice->gasRequest->deliveryBranch->name ?? 'N/A') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">ORDER/DEPOT</div>
                <div class="detail-value">{{ strtoupper($invoice->depot ?? 'TOR') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">REP NAME</div>
                <div class="detail-value">{{ strtoupper($invoice->gasRequest->rep_name ?? 'N/A') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">REP CONTACT</div>
                <div class="detail-value">{{ strtoupper($invoice->gasRequest->rep_contact ?? 'N/A') }}</div>
            </div>
        </div>
        <div class="right-column">
            <div class="detail-row">
                <div class="detail-label">INVOICE DATE</div>
                <div class="detail-value">{{ $invoice->date ?? now()->format('d/m/Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">CUSTOMER CODE</div>
                <div class="detail-value">{{ $invoice->customer->customer_id ?? '' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">CUSTOMER ORDER NO.</div>
                <div class="detail-value">{{ $invoice->order_no ?? '' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">TRANSPORTER</div>
                <div class="detail-value">Manbah Gas</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">VEHICLE NO.</div>
                <div class="detail-value">
                    {{ strtoupper($invoice->gasRequest->driverAssigned->vehicle->vehicle_number ?? 'N/A') }}</div>
            </div>
            <div class="detail-row" style="border-bottom: none;">
                <div class="detail-label">PRODUCT ORDER NOTE NO.</div>
                <div class="detail-value">{{ $invoice->receipt_no ?? '' }}</div>
            </div>
        </div>
    </div>

    {{-- Product Table --}}
    <div class="product-table">
        @if ($invoice->discount == 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">PRODUCT<br>CODE</th>
                        <th style="width: 30%;">PRODUCT<br>DESCRIPTION</th>
                        <th style="width: 15%;">QUANTITY</th>
                        <th style="width: 15%;">UNIT<br>PRICE</th>
                        <th style="width: 25%;">VALUE OF<br>PRODUCTS(GHC)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align: left; padding-left: 10px;">
                            <strong>{{ $invoice->product_code ?? 'LPG' }}</strong>
                        </td>
                        <td>{{ number_format($invoice->kg ?? 'N/A', 2) }}</td>
                        <td>{{ number_format($invoice->rate ?? '0.0', 2) }}</td>
                        <td>GH₵ {{ number_format($invoice->amount ?? '0.0', 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" style="text-align: right; padding-right: 10px;">TOTAL</td>
                        <td>GH₵ {{ number_format($invoice->amount ?? '0.0', 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">PRODUCT<br>CODE</th>
                        <th style="width: 15%;">PRODUCT<br>DESCRIPTION</th>
                        <th style="width: 15%;">QUANTITY</th>
                        <th style="width: 15%;">UNIT<br>PRICE</th>
                        <th style="width: 10%">DISCOUNT(%)</th>
                        <th style="width: 10%">DISCOUNT AMT(GHC)</th>
                        <th style="width: 20%;">VALUE OF<br>PRODUCTS(GHC)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align: left; padding-left: 10px;">
                            <strong>{{ $invoice->product_code ?? 'LPG' }}</strong>
                        </td>
                        <td>{{ number_format($invoice->kg ?? 'N/A', 2) }}</td>
                        <td>{{ number_format($invoice->rate ?? '0.0', 2) }}</td>
                        <td>{{ $invoice->discount }}</td>
                        <td>{{ number_format($invoice->discount_amount, 2) }}</td>
                        <td>GH₵ {{ number_format($invoice->amount ?? '0.0', 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="6" style="text-align: right; padding-right: 10px;">TOTAL</td>
                        <td>GH₵ {{ number_format($invoice->amount ?? '0.0', 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-section">
            <span class="footer-label">AMOUNT IN WORDS</span>
            <span>{{ $invoice->amount_in_words ?? '' }}</span>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="footer-label">DRIVER'S NAME:</div>
                <div>{{ strtoupper($invoice->gasRequest->driverAssigned->name ?? 'N/A') }}</div>
                <div class="signature-line"></div>
                <div style="font-size: 9px;">DRIVER'S SIGN:</div>
            </div>
            <div class="signature-box">
                <img src="{{ public_path('assets/images/manbah_signature.jpg') }}" alt="Manbah Signature"
                    style="width:102px;margin-bottom:-80px">
                <div class="signature-line"></div>
                <div style="font-size: 9px;">AUTHORISED<br>SIGNATORY</div>
            </div>
        </div>

        <div class="note">
            RECEIVED IN GOOD CONDITION<br>
            CUSTOMER'S SIGNATURE & STAMP
        </div>
    </div>
</body>

</html>
