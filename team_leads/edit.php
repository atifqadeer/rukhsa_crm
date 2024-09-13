<?php
    session_start();
    
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }

    include '../config.php'; // Adjust the path as necessary
    include '../header.php'; // Adjust the path as necessary
    
    // Get agent ID from URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Initialize variables for form data
    $firstName = $lastName = $email = $phone = $user_name = $role = '';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
        // Fetch existing agent data for editing
        $stmt = $conn->prepare("SELECT first_name, last_name, email, phone, username, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Bind result variables
        $stmt->bind_result($firstName, $lastName, $email, $phone, $user_name, $role);
        
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
                                <h4 class="page-title mb-0">Edit Team Lead FORM</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Users Management</a></li>
                                        <li class="breadcrumb-item active">Team Lead</li>
                                        <li class="breadcrumb-item active">Edit Team Lead FORM</li>
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
                                           <div class="row">
                                               <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <div class="col-6 mb-3">
                                                <label for="validationCustom01" class="form-label">First name</label>
                                                <input type="text" class="form-control" id="validationCustom01" name="first_name" placeholder="First name" value="<?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>"  required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="validationCustom02" class="form-label">Last name</label>
                                                <input type="text" class="form-control" id="validationCustom02" name="last_name" placeholder="Last name" value="<?php echo htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'); ?>"  required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="validationCustom06" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="validationCustom06" name="username" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Username" required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="validationCustom03" class="form-label">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                    <input type="email" class="form-control" id="validationCustom03" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Email" aria-describedby="inputGroupPrepend" required>
                                                    <div class="invalid-feedback">
                                                        Please choose a email.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label for="validationCustom04" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="validationCustom04" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a valid phone.
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label for="validationCustom04" class="form-label">Role</label>
                                                    <select name="role" class="form-control" required>
                                                        <option value="">Select Role</option>
                                                        <option value="agent" <?php echo ($role == 'agent' ? 'selected' : ''); ?>>Agent</option>
                                                        <option value="team_lead" <?php echo ($role == 'team_lead' ? 'selected' : ''); ?>>Team Lead</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select a role.
                                                    </div>
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
                    action: 'update_team_lead', // Action name
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
                            window.location.href = '../team_leads/index.php';
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
