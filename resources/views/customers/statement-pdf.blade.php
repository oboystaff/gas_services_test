<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Customer Statement</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 15px;
        }

        /* Header */
        .header {
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
            background: #f5f5f5;
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

        /* Statement Title */
        .statement-title {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .statement-title h2 {
            margin: 0;
            font-size: 16px;
            color: #d00;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Customer Info Box */
        .customer-info {
            border: 2px solid #000;
            padding: 12px;
            margin-bottom: 20px;
            background: #fff;
        }

        .customer-info .customer-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 35%;
            padding: 5px 10px;
            font-weight: bold;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }

        .info-value {
            display: table-cell;
            padding: 5px 10px;
            border: 1px solid #ddd;
        }

        .balance-positive {
            color: #d00;
            font-weight: bold;
        }

        .balance-negative {
            color: #008000;
            font-weight: bold;
        }

        /* Statement Table */
        .statement-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: 2px solid #000;
        }

        .statement-table thead {
            background: #d00;
            color: white;
        }

        .statement-table th {
            border: 1px solid #000;
            padding: 10px 8px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }

        .statement-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .statement-table tbody tr:hover {
            background: #f0f0f0;
        }

        .statement-table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }

        .text-success {
            color: #008000;
            font-weight: bold;
        }

        .text-danger {
            color: #d00;
            font-weight: bold;
        }

        .amount-column {
            text-align: right;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #000;
            font-size: 9px;
            text-align: center;
            color: #666;
        }

        .footer p {
            margin: 3px 0;
        }

        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 30px 20px 5px 20px;
        }

        .signature-label {
            font-size: 10px;
            font-weight: bold;
        }

        /* Print styles */
        @media print {
            body {
                padding: 0;
            }
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

    {{-- Statement Title --}}
    <div class="statement-title">
        <h2>Customer Account Statement</h2>
    </div>

    {{-- Customer Info --}}
    <div class="customer-info">
        <div class="customer-name">ACCOUNT HOLDER: {{ strtoupper($customer->name ?? 'N/A') }}</div>

        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Statement Date:</div>
                <div class="info-value">{{ now()->format('d F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Customer Code:</div>
                <div class="info-value">{{ $customer->customer_id ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Invoiced:</div>
                <div class="info-value">GH₵ {{ number_format($total_invoiced ?? 0, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Paid:</div>
                <div class="info-value">GH₵ {{ number_format($total_paid ?? 0, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Outstanding Balance:</div>
                <div class="info-value">
                    <span class="{{ ($balance ?? 0) > 0 ? 'balance-positive' : 'balance-negative' }}">
                        GH₵ {{ number_format(abs($balance ?? 0), 2) }}
                        @if (($balance ?? 0) > 0)
                            (DR)
                        @else
                            (CR)
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Statement Table --}}
    <table class="statement-table">
        <thead>
            <tr>
                <th style="width: 12%;">Date</th>
                <th style="width: 12%;">Type</th>
                <th style="width: 40%;">Description</th>
                <th style="width: 18%;">Debit (GH₵)</th>
                <th style="width: 18%;">Credit (GH₵)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($statement ?? [] as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                    <td>{{ $row['type'] }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td class="amount-column">
                        @if ($row['type'] !== 'Payment' && $row['type'] !== 'Withholding Tax')
                            <span class="text-danger">{{ number_format($row['amount'], 2) }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount-column">
                        @if ($row['type'] === 'Payment' || $row['type'] === 'Withholding Tax')
                            <span class="text-success">{{ number_format($row['amount'], 2) }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                        No transactions found for this period
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">PREPARED BY</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">AUTHORISED SIGNATORY</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p><strong>This is a computer-generated statement and does not require a signature.</strong></p>
        <p>For any queries regarding this statement, please contact our accounts department.</p>
        <p>Tel: 0558 202675 | Email: manbahgas@yahoo.com</p>
    </div>
</body>

</html>
