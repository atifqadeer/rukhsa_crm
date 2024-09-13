<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if the user has the correct role to access this page
if ($_SESSION['role'] !== 'team_lead') {
    // Redirect the user to their appropriate dashboard or an error page
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin_dashboard.php");
            break;
        case 'agent':
            header("Location: agent_dashboard.php");
            break;
        default:
            header("Location: index.php");
    }
    exit();
}

include 'config.php'; // Adjust the path as necessary
include 'header.php'; // Adjust the path as necessary
?>
<!-- Team Lead dashboard content -->

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

