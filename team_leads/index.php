<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../config.php'; // Adjust the path as necessary
include '../header.php'; // Adjust the path as necessary

$query = "SELECT * FROM `users` where `role`='team_lead' ORDER BY `id` DESC";
$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id']; // Get the ID from the POST data
        $message = softDeleteAgentById($id, $conn); // Call the function to soft delete

        // Return a JSON response
        echo json_encode(['message' => $message]);
    } else {
        echo json_encode(['message' => 'ID not set in POST request.']);
    }
    exit();
} 

function softDeleteAgentById($id, $conn) {
    // Prepare the SQL query to set the deleted_at timestamp
    $stmt = $conn->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
    
    // Bind the ID parameter
    $stmt->bind_param('i', $id);
    
    // Execute the query
    $stmt->execute();
    
    // Check if any rows were affected (i.e., if the ID exists)
    if ($stmt->affected_rows > 0) {
        return "Record deleted successfully.";
    } else {
        return "No record found with ID $id.";
    }
}

    // Get total number of records
    $total_records_query = "SELECT COUNT(*) AS count FROM `users` WHERE `role` = 'team_lead'";
    
    // Execute the query
    $total_records_result = mysqli_query($conn, $total_records_query);
    
    // Check if the query was successful
    if ($total_records_result) {
        // Fetch the result
        $total_records_row = mysqli_fetch_array($total_records_result);
    
        // Get the count value, defaulting to 0 if there are no records
        $total_records = isset($total_records_row[0]) ? (int)$total_records_row[0] : 0;
    } else {
        // Handle query error
        $total_records = 0;
    }
?>

<body>
    <!-- Begin page -->
    <div class="layout-wrapper">
        <?php include '../sidebar.php'; // Adjust the path as necessary ?>
        <!-- Start Page Content here -->
        <div class="page-content">
            <?php include '../topbar.php'; // Adjust the path as necessary ?>
            <div class="px-3">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Users Management</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Users Management</a></li>
                                        <li class="breadcrumb-item active">Team Leads</li>
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
                                   <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="header-title">Team Leads</h4>
                                    <p class="text-muted font-size-13 mb-4">
                                        View and manage your team leads efficiently.
                                    </p>
                                </div>
                                <div>
                                    <a href="/team_leads/create.php">
                                        <button type="button" class="btn btn-primary btn-bordered waves-effect waves-light">
                                            <i class="bx bx-plus"></i> Add New
                                        </button>
                            
                                    </a>
                                </div>
                            </div>


                                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if (mysqli_num_rows($result) > 0) {
                                                $sr = 1; // Initialize the serial number
                                                while ($row = mysqli_fetch_assoc($result)) { ?>
                                                    <tr>
                                                        <td><?php echo $sr++; ?></td> <!-- Serial number -->
                                                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                                        <td>
                                                            <?php 
                                                                if($row['status'] == 'active'){
                                                                    $status = '<span class="badge badge-outline-success rounded-pill">Active</span>';    
                                                                }else{
                                                                    $status = '<span class="badge badge-outline-danger rounded-pill">Inactive</span>';    
                                                                }
                                                                echo $status;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-success waves-effect waves-light">Edit</a>
                                                             <?php if ($row['status'] === 'inactive') { ?>
                                                                <a href="#" 
                                                                   class="btn btn-primary waves-effect waves-light" 
                                                                   onclick="restoreAgent(<?php echo $row['id']; ?>); return false;">
                                                                   <i class="mdi mdi-restore"></i> Restore
                                                                </a>
                                                            <?php } else { ?>
                                                                <a href="#" 
                                                                   class="btn btn-danger waves-effect waves-light" 
                                                                   onclick="confirmAndDelete(<?php echo $row['id']; ?>); return false;">
                                                                   <i class="mdi mdi-delete"></i> Delete
                                                                </a>
                                                            <?php } ?>
                                                            
                                                        </td>
                                                    </tr>
                                                <?php } 
                                            } else { ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No Data Found</td>
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


    <?php include '../footer.php'; // Adjust the path as necessary ?>


<script>
    function confirmAndDelete(recordId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../functions.php', // URL of the PHP script
                    type: 'POST', // POST request
                    data: {
                        action: 'delete_team_lead',
                        id: recordId
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        toastr.success(result.message);
    
                        if (result.status === 'success') {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                        toastr.error('An error occurred while trying to delete the record.');
                    }
                });
            }
        });
    }
    
    function restoreAgent(agentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to restore this record.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../functions.php', // URL of the PHP script
                    type: 'POST',
                    data: {
                        action: 'restore_team_lead',
                        id: agentId
                    },
                    success: function(response) {
                        // Parse the JSON response
                        var result = JSON.parse(response);
                        
                        // Display the message using Toastr
                        toastr.success(result.message);
    
                        // Optionally, reload the page or update the UI
                        if (result.status === 'success') {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + error);
                        toastr.error('An error occurred while trying to restore the record.');
                    }
                });
            }
        });
    }
</script>
