<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if the user has the correct role to access this page
if ($_SESSION['role'] !== 'admin') {
    // Redirect the user to their appropriate dashboard or an error page
    switch ($_SESSION['role']) {
        case 'agent':
            header("Location: agent_dashboard.php");
            break;
        case 'team_lead':
            header("Location: team_lead_dashboard.php");
            break;
        case 'admin':
            header("Location: admin_dashboard.php");
            break;
        default:
            header("Location: index.php");
    }
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
            <!-- Your admin dashboard content here -->
        </div>
        <!-- End Page content -->
    </div>
    <!-- END wrapper -->
    <?php include 'footer.php'; // Adjust the path as necessary ?>
</body>
