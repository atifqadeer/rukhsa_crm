<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php'; // Database connection
include 'header.php'; // Header HTML
?>
 
<body>
    <!-- Begin page -->
    <div class="layout-wrapper">
        <?php
        include 'sidebar.php'; // Adjust the path as necessary
        ?>
      
        <!-- Start Page Content here -->
        <div class="page-content">
            <!-- ========== Topbar Start ========== -->
            <?php
            include 'topbar.php'; // Adjust the path as necessary
            ?>
            <!-- ========== Topbar End ========== -->
          
          <div class="px-3">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="py-3 py-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="page-title mb-0">Order Placed</h4>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-none d-lg-block">
                                <ol class="breadcrumb m-0 float-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Orders Management</a></li>
                                    <li class="breadcrumb-item active">Order Placed</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                
                                
                             <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-body">
                                    <h5 class="card-title">Thank you for placing an order with us!</h5>
                                    <p class="card-text">Your order is currently being processed, and we are working diligently to complete it. We appreciate your business and will notify you once your order is ready. If you have any questions or need further assistance, please feel free to contact us.</p>
                                    
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card card-body">
                                    
                                    <p class="card-text">Want to place another order? Click below!</p>
                                    <a href="new-order.php" class="btn btn-primary waves-effect waves-light">Place a new order</a>
                                </div>
                            </div>
                        </div>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                                    
        </div>
        <!-- End Page content -->
    </div>



<?php include 'footer.php'; // Footer HTML ?>
