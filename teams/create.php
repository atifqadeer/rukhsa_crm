<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../config.php'; // Adjust the path as necessary
include '../header.php'; // Adjust the path as necessary

//get team leads
$teamLead_query = "SELECT * FROM `users` WHERE `role`='team_lead' and `status`='active' ORDER BY `id` DESC";
$team_leads = mysqli_query($conn, $teamLead_query);

//get agents
$agent_query = "SELECT * FROM `users` WHERE `role`='agent' and `status`='active' ORDER BY `id` DESC";
$agents = mysqli_query($conn, $agent_query);


?>


<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.3/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.3/dist/js/select2.min.js"></script>

</head>
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
                                <h4 class="page-title mb-0">Create Team FORM</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Users Management</a></li>
                                        <li class="breadcrumb-item active">Teams</li>
                                        <li class="breadcrumb-item active">Create Team FORM</li>
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
                                            <div class="mb-3">
                                                <label for="validationCustom01" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="validationCustom01" name="name" placeholder="Name" required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                        
                                            <div class="mb-3">
                                                <label for="teamLeadSelect" class="form-label">Team Lead</label>
                                               <select class="form-select" id="teamLeadSelect" name="team_lead" required>
                                                    <option value="" disabled selected>Select a Team Lead</option>
                                                    <?php
                                                    if (mysqli_num_rows($team_leads) > 0) {
                                                        while ($team_lead = mysqli_fetch_assoc($team_leads)) { ?>
                                                            <option value="<?= htmlspecialchars($team_lead['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                <?= htmlspecialchars($team_lead['first_name'] . ' ' . $team_lead['last_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                            </option>
                                                        <?php }
                                                    } else { ?>
                                                        <option value="" disabled>No Team Leads Found</option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a team lead.
                                                </div>
                                            </div>
                                        
                                            <div class="mb-3">
                                                <label for="agentSelect" class="form-label">Agents</label>
                                                <select class="form-select select" id="teamLeadSelect" name="agents[]" required multiple>
                                                    <option value="" disabled selected>Select Agents</option>
                                                    <?php
                                                    if (mysqli_num_rows($agents) > 0) {
                                                        while ($agent = mysqli_fetch_assoc($agents)) { ?>
                                                            <option value="<?= htmlspecialchars($agent['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                <?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name'], ENT_QUOTES, 'UTF-8'); ?>
                                                            </option>
                                                        <?php }
                                                    } else { ?>
                                                        <option value="" disabled>No Agents Found</option>
                                                    <?php } ?>
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
        $('#teamLeadSelect').select2({
            placeholder: 'Select Agents',
            allowClear: true
        });
    });
</script>

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
                action: 'save_team', // Action name
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



