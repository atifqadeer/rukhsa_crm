<?php
    session_start();
    
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: <?=BASE_URL?>index.php");
        exit();
    }

    // Include configuration file
    include '../config.php'; // Adjust the path as necessary
    include '../header.php'; // Adjust the path as necessary
    
    // Get agent ID from URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Initialize variables for form data
    $firstName = $lastName = $email = $phone = $user_name = $role = '';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
        // Fetch existing agent data for editing
        $stmt = $conn->prepare("SELECT name FROM teams WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Bind result variables
        $stmt->bind_result($name);
        
        // Fetch the result
        $stmt->fetch();
        $stmt->close();
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
                                <h4 class="page-title mb-0">Edit Teams FORM</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Users Management</a></li>
                                        <li class="breadcrumb-item active">Teams</li>
                                        <li class="breadcrumb-item active">Edit Teams FORM</li>
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

                                       <form class="needs-validation" method="POST" novalidate>
                                               <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <div class="mb-3">
                                                <label for="validationCustom01" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="validationCustom01" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"  required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                             <div class="mb-3">
                                                <label for="teamLeadSelect" class="form-label">Team Lead</label>
                                                <select class="form-select" id="teamLeadSelect" name="team_lead" required>
                                                    <option value="" disabled selected>Select a Team Lead</option>
                                                    <!-- Add options dynamically from the database or hardcode them -->
                                                    <option value="team_lead_1">Team Lead 1</option>
                                                    <option value="team_lead_2">Team Lead 2</option>
                                                    <option value="team_lead_3">Team Lead 3</option>
                                                    <!-- ...other team leads -->
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a team lead.
                                                </div>
                                            </div>
                                        
                                            <div class="mb-3">
                                                <label for="agentSelect" class="form-label">Agent</label>
                                                <select class="form-select" id="agentSelect" name="agent" required>
                                                    <option value="" disabled selected>Select an Agent</option>
                                                    <!-- Add options dynamically from the database or hardcode them -->
                                                    <option value="agent_1">Agent 1</option>
                                                    <option value="agent_2">Agent 2</option>
                                                    <option value="agent_3">Agent 3</option>
                                                    <!-- ...other agents -->
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select an agent.
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </form>

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

</div>

    <?php include '../footer.php'; // Adjust the path as necessary ?>
   
<script>
$(document).ready(function() {
    $('form.needs-validation').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        var formData = $(this).serialize(); // Serialize form data for submission

        $.ajax({
            url: '../functions.php', // URL of the PHP script
            type: 'POST', // POST request
            data: {
                action: 'update_team', // Action name
                ...$(this).serializeArray().reduce((obj, item) => {
                    obj[item.name] = item.value;
                    return obj;
                }, {})
            },
            success: function(response) {
                try {
                    var result = JSON.parse(response); // Parse JSON response
                    toastr.success(result.message);

                    if (result.status === 'success') {
                        // Redirect to another page
                        window.location.href = '../teams/index.php';
                    }
                } catch (e) {
                    console.error('JSON parse error: ' + e);
                    toastr.error('An error occurred while processing the response.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' ' + error);
                toastr.error('An error occurred while submitting the form.');
            }
        });
    });
});
</script>
