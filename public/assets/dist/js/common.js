$(document).ready(function() {
    
    $("#zero_config").DataTable({
        dom: 'lBfrtip',
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Data Export',
            className: 'btn btn-primary'
        }]
    });

    $("#zero_config2").DataTable({
        dom: 'lBfrtip',
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Data Export',
            className: 'btn btn-primary'
        }]
    });
});