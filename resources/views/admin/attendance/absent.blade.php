<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables with Bootstrap 5</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.0/css/buttons.dataTables.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Absent Members</h2>
        <div class="mb-3">
            <label for="fromDate" class="form-label">From Date:</label>
            <input type="date" class="form-control" id="fromDate">
        </div>
        <div class="mb-3">
            <label for="toDate" class="form-label">To Date:</label>
            <input type="date" class="form-control" id="toDate">
        </div>
        <button id="filterButton" class="btn btn-primary">Filter</button>
        <button id="exportExcelButton" class="btn btn-success">Export Excel</button>
        <button id="exportCsvButton" class="btn btn-info">Export CSV</button>
        <button id="exportPdfButton" class="btn btn-danger">Export PDF</button>
        <button id="printButton" class="btn btn-secondary">Print</button>
        <table class="table table-bordered mt-4" id="datatable">
            <thead>
                <tr>
                    <th class="text-center">Member</th>
                    <th class="text-center">Date</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons extension JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.bootstrap5.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var table = $('#datatable').DataTable({
                "order": [[ 1, "desc" ]],
                "processing": true,
                "serverSide": false, 
                "ajax": "{{ route('absent.members') }}",
                "columns": [
                    {
                        "data": "name",
                        "render": function (data, type, row) {
                            return row.member_id + ' (' + data + ')';
                        }
                    },
                    { "data": "date" },
                ],
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'Absent Members List',
                        filename: 'absent_members_list',
                        exportOptions: {
                            columns: [0, 1]
                        },
                        className: 'd-none' // Hide default button
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'Export CSV',
                        title: 'Absent Members List',
                        filename: 'absent_members_list',
                        exportOptions: {
                            columns: [0, 1]
                        },
                        className: 'd-none' // Hide default button
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        title: 'Absent Members List',
                        filename: 'absent_members_list',
                        exportOptions: {
                            columns: [0, 1]
                        },
                        className: 'd-none' // Hide default button
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Absent Members List',
                        exportOptions: {
                            columns: [0, 1]
                        },
                        className: 'd-none' // Hide default button
                    }
                ]
            });

            $('#filterButton').on('click', function() {
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();
                if (toDate && fromDate > toDate) {
                    alert('To Date must be greater than or equal to From Date');
                    return;
                }
                table.ajax.url("{{ url('/admin/attendance/get-absent') }}?fromDate=" + fromDate + "&toDate=" + toDate).load();
            });

            $('#exportExcelButton').on('click', function() {
                table.button('.buttons-excel').trigger(); // Trigger the hidden Excel export button
            });

            $('#exportCsvButton').on('click', function() {
                table.button('.buttons-csv').trigger(); // Trigger the hidden CSV export button
            });

            $('#exportPdfButton').on('click', function() {
                table.button('.buttons-pdf').trigger(); // Trigger the hidden PDF export button
            });

            $('#printButton').on('click', function() {
                table.button('.buttons-print').trigger(); // Trigger the hidden Print button
            });
        });
    </script>
</body>
</html>


