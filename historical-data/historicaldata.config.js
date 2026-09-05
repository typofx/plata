$(document).ready(function () {
    $('#tokenTable').DataTable({
        "lengthMenu": [
            [50, 100, 150, 250],
            [50, 100, 150, 250]
        ],
        "columnDefs": [{
            "width": "20px",
            "targets": 0
        },
        {
            "width": "20px",
            "targets": 5
        }
        ],

        "order": [
            [0, "asc"]
        ]
    });
});