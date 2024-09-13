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
            <?
            include 'sidebar.php'; // Adjust the path as necessary
            ?>
            <!-- Start Page Content here -->
            <div class="page-content">

               <?
                include 'topbar.php'; // Adjust the path as necessary
                ?>
            </div>
            <!-- End Page content -->


        </div>
        <!-- END wrapper -->
<?       
include 'footer.php'; // Adjust the path as necessary
?>

