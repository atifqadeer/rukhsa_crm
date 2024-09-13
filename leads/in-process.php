<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php'; // Adjust the path as necessary
include 'header.php'; // Adjust the path as necessary


// Get data from the orders table
$user_id = $_SESSION['user_id'];
$query = "SELECT `address_order`, `type`, `client_code`, `status`,`preferences`,`folder_path` FROM `orders` WHERE `users_id` = '$user_id'";
$result = mysqli_query($conn, $query);

// Get total number of records
$total_records_query = "SELECT COUNT(*) FROM `orders` WHERE `users_id` = '$user_id'";
$total_records_result = mysqli_query($conn, $total_records_query);
$total_records = mysqli_fetch_array($total_records_result)[0];
?>

<!-- third party css -->
<link href="dist/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="dist/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="dist/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="dist/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<body>
    <!-- Begin page -->
    <div class="layout-wrapper">
        <?php include 'sidebar.php'; // Adjust the path as necessary ?>
        <!-- Start Page Content here -->
        <div class="page-content">
            <?php include 'topbar.php'; // Adjust the path as necessary ?>
            <div class="px-3">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Orders Management</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Orders Management</a></li>
                                        <li class="breadcrumb-item active">In Process Orders</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">In Process Orders</h4>
                                    <p class="text-muted font-size-13 mb-4">
                                        View and track orders that are currently being processed.
                                    </p>
                                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Address / Order ID</th>
                                                <th>Order Type</th>
                                                <th>Code</th>
                                                <th>Preference</th>
                                                <th>View Files</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?php echo $row['address_order']; ?></td>
                                                    <td><?php echo $row['type']; ?></td>
                                                    <td><?php echo $row['client_code']; ?></td>
                                                    <td><?php echo $row['preferences']; ?></td>
                                                    <td>
                                                        <?php echo $row['status']; ?>
                                                        <button type="button" class="btn btn-primary btn-sm preview-files" data-folder="<?php echo $row['folder_path']; ?>" data-bs-toggle="modal" data-bs-target="#filePreviewModal">
                                                            Preview Files
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                   
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end row-->
                </div> <!-- container -->
            </div> <!-- content -->
        </div>
        <!-- End Page content -->
    </div>
    <!-- END wrapper -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="filePreviewContent">
                    <!-- File previews will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <?php include 'footer.php'; // Adjust the path as necessary ?>
    
    <script>
        function showFilesModal(button) {
    var folderPath = $(button).data('folder');

    $.ajax({
        url: 'fetch_files.php', // Create this PHP script to fetch the files
        type: 'POST',
        data: { folder_path: folderPath },
        success: function(response) {
            $('#modalFileContent').html(response);
            $('#filePreviewModal').modal('show');
        },
        error: function() {
            alert('Failed to load files.');
        }
    });
}
        
    </script>
    
    <!-- third party js -->
    <script src="dist/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="dist/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="dist/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="dist/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="dist/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="dist/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="dist/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="dist/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="dist/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="dist/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="dist/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="dist/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="dist/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <!-- third party js ends -->

    <!-- Datatables js -->
    <script src="dist/assets/js/pages/datatables.js"></script>