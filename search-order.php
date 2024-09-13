<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'config.php'; // Adjust the path as necessary
include 'header.php'; // Adjust the path as necessary
?>

<body>
    <!-- Begin page -->
    <div class="layout-wrapper">
        <?php include 'sidebar.php'; // Adjust the path as necessary ?>
        <!-- Start Page Content here -->
        <div class="page-content">
            <?php include 'topbar.php'; // Adjust the path as necessary ?>
            <div class="px-3">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Search Order</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Orders Management</a></li>
                                        <li class="breadcrumb-item active">Search Order</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Search Form -->
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" id="searchInput" class="form-control" placeholder="Type address or order id to search...">
                        </div>
                    </div>
                    <!-- Search Results -->
                    <div id="searchResults" class="mt-3"></div>

                </div>
            </div>
        </div>
        <!-- End Page content -->
    </div>
    <!-- END wrapper -->
    <?php include 'footer.php'; // Adjust the path as necessary ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#searchInput').keyup(function() {
            var query = $(this).val().trim(); // Trim whitespace

            if (query !== '') {
                $.ajax({
                    url: "search_order.php", // PHP file that will fetch the data
                    method: "POST",
                    data: {query: query},
                    success: function(data) {
                        $('#searchResults').html(data);
                    }
                });
            } else {
                $('#searchResults').html(''); // Clear the search results
            }
        });
    });
</script>
</body>
