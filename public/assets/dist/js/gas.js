$(document).ready(function () {

    $('select[name="quantity_type"]').change(function () {
        let selectedValue = $(this).val(); 
        
        if (selectedValue === "KG") {
            $('#value1_label').text('Gas Quantity (KG)');
            $('input[name="value1"]').attr('placeholder', 'Gas Quantity (KG)');

            $('#value2_label').text('Gas Amount (GHS)');
            $('input[name="value2"]').attr('placeholder', 'Gas Amount (GHS)');
        } else if (selectedValue === "GHS") {
            $('#value1_label').text('Gas Amount (GHS)');
            $('input[name="value1"]').attr('placeholder', 'Gas Amount (GHS)');

            $('#value2_label').text('Gas Quantity (KG)');
            $('input[name="value2"]').attr('placeholder', 'Gas Quantity (KG)');
        }

        $('input[name="value1"]').val("");
        $('input[name="value2"]').val("");
    });

    $('input[name="value1"]').on('input', function () {
        let rate = $('input[name="rate"]').val().trim();
        let selectedValue = $('select[name="quantity_type"]').val(); 
        let kg = 0;
        let amount = 0;

        if ($(this).val().trim() === "") {
            $('input[name="value2"]').val("");
            return;
        }

        if (selectedValue === "") {
            alert('Select quantity type');
            return;
        }
        
        if (selectedValue === "KG") {
            kg = $(this).val().trim();
            amount = parseFloat($(this).val()) * rate;

            $('input[name="value2"]').val(amount);

            $('input[name="kg"]').val(kg);
            $('input[name="amount"]').val(amount);
        } else if (selectedValue === "GHS") {
            kg = parseFloat($(this).val()) / rate;
            amount = $(this).val().trim();

            $('input[name="value2"]').val(kg);

            $('input[name="kg"]').val(kg);
            $('input[name="amount"]').val(amount);
        }
    });

    if ($('select[name="payment_mode"]').length > 0) {
        $('select[name="payment_mode"]').change(function () {
            toggleChequeDetails();
        });
    }

    function toggleChequeDetails() {
        var paymentMode = $('select[name="payment_mode"]').val();

        if (paymentMode === 'cheque') {
            $('#cheque_no').show();
            $('#bank_name').show();
        } else if (paymentMode === 'bank transfer') {
            $('#cheque_no').hide();
            $('#bank_name').show();
        } else if (paymentMode === 'cash') {
            $('#cheque_no').hide();
            $('#bank_name').hide();
        } 
    }
});