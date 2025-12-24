<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            width: 80mm;
            margin: 0 auto;
            background: #fff;
            padding: 10px;
        }

        .receipt {
            max-width: 80mm;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .item-table td {
            padding: 5px 0;
        }

        .total-table {
            width: 100%;
            margin-top: 10px;
        }

        .total-table td {
            padding: 3px 0;
        }

        .total {
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .right {
            text-align: right;
        }

        .barcode {
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm;
            }

            .receipt {
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="receipt">
        <div class="center bold">MIGHTY GAS CO. LTD</div>
        <div class="center">P.O. BOX HP 1504</div>
        <div class="center">Volta Region</div>
        <hr>
        <div>Name: <span class="bold">{{ $sale->name ?? 'N/A' }}</span></div>
        <div>Date: <span class="bold">{{ now()->format('d/m/Y h:i A') }}</span></div>
        <div>Sales Receipt #: <span class="bold">{{ $sale->transaction_id ?? 'N/A' }}</span></div>
        <div>Cashier: <span class="bold">{{ auth()->user()->name ?? 'N/A' }}</span></div>

        <hr>

        <table class="item-table">
            <tr>
                <td class="bold">Item Name</td>
                <td class="bold">Qty</td>
                <td class="bold" style="padding-left: 10px;">Price</td>
                <td class="bold">Total</td>
            </tr>
            <tr>
                <td>Miscellaneous Item</td>
                <td>1</td>
                <td style="padding-left: 10px;">GHC {{ number_format($sale->amount, 2) ?? 0 }}</td>
                <td>GHC {{ number_format($sale->amount, 2) ?? 0 }}</td>
            </tr>
        </table>

        <hr>

        <table class="total-table">
            <tr>
                <td class="right">Subtotal:</td>
                <td class="bold right">GHC {{ number_format($sale->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="right">Local Sales Tax:</td>
                <td class="bold right">0% Tax + GHC 0.00</td>
            </tr>
            <tr>
                <td class="bold right total">RECEIPT TOTAL:</td>
                <td class="bold right">GHC {{ number_format($sale->amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2">Cash: {{ number_format($sale->amount, 2) }}</td>
            </tr>
        </table>

        <div class="center" style="margin-top: 10px;">Thanks for shopping with us!</div>

        <div class="barcode">
            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $sale->transaction_id ?? 'N/A' }}&code=Code128&dpi=96"
                alt="Barcode">
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.print(); // Auto trigger print
            setTimeout(function() {
                window.close(); // Close the window after printing
            }, 1000);
        });
    </script>
</body>

</html>
