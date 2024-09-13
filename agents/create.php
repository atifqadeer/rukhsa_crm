<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Include configuration file
include '../config.php'; // Adjust the path as necessary
include '../header.php'; // Adjust the path as necessary
?>
<style>
    .drop-zone {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        cursor: pointer;
    }

    .dragover {
        background-color: #f0f0f0;
    }

    #file-list {
        margin-top: 10px;
    }

    #file-list div {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }

    #file-list p {
        margin-bottom: 0;
        flex-grow: 1;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Image preview styles */
    #image-preview {
        display: none;
        margin-top: 10px;
        position: relative;
    }

    #image-preview img {
        max-width: 100%;
        max-height: 300px;
        cursor: pointer;
        border: 2px solid #ccc;
    }

    .btn-remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
        border-radius: 5px;
    }
</style>

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
                                <h4 class="page-title mb-0">Create Agent FORM</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Users Management</a></li>
                                        <li class="breadcrumb-item active">Agents</li>
                                        <li class="breadcrumb-item active">Create Agent FORM</li>
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
                                      <form class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom01" class="form-label">First name</label>
                                                <input type="text" class="form-control" id="validationCustom01" name="first_name" placeholder="First name" required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom02" class="form-label">Last name</label>
                                                <input type="text" class="form-control" id="validationCustom02" name="last_name" placeholder="Last name" required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom06" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="validationCustom06" name="username" placeholder="Username" required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom03" class="form-label">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                    <input type="email" class="form-control" id="validationCustom03" name="email" placeholder="Email" aria-describedby="inputGroupPrepend" required>
                                                    <div class="invalid-feedback">
                                                        Please choose a valid email.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom04" class="form-label">Phone#</label>
                                                <input type="phone" class="form-control phone-input" id="validationCustom04" required name="phone" placeholder="+971 XXX XXX XXXX">
                                                <div class="invalid-feedback">
                                                    Please provide a valid phone.
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom05" class="form-label">WhatsApp#</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bx bxl-whatsapp" style="font-size:18px"></i></span>
                                                    <input type="phone" class="form-control phone-input" id="validationCustom05" required name="whatsapp_number" placeholder="+971 XXX XXX XXXX" aria-describedby="inputGroupPrepend">
                                                    <div class="invalid-feedback">
                                                        Please choose a valid WhatsApp No.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row">
                                           
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom06" class="form-label">CNIC#</label>
                                                <input type="text" class="form-control emirates-id-input" id="validationCustom06" name="cnic" placeholder="784-XXXX-XXXXXXX-X">
                                                <div class="invalid-feedback">
                                                    Please provide a valid CNIC.
                                                </div>
                                            </div>
                                             <div class="col-md-6 col-sm-12 mb-3">
                                                <label for="validationCustom07" class="form-label">Passport</label>
                                                <input type="text" class="form-control passport-input" id="validationCustom07" name="passport" placeholder="Passport">
                                                <div class="invalid-feedback">
                                                    Please provide a valid passport.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="validationCustomFile" class="form-label">Upload Files</label>
                                                <div id="drop-zone" class="drop-zone">
                                                    <input type="file" class="form-control" id="validationCustomFile" name="user_file[]" multiple style="display: none;">
                                                    <label class="btn btn-primary" for="validationCustomFile">
                                                        Choose files
                                                    </label>
                                                    <span class="form-label">or drag & drop here to upload</span>
                                                    <div id="file-list" class="mt-3">
                                                        <p>Selected files:</p>
                                                        <!-- File items will be added here dynamically -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="image-preview"></div>
                                        </div>
                                        <a href="/agents/index.php" class="btn btn-secondary">Back</a>
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
<!-- Plugins js -->
<script src="<?=BASE_URL?>dist/assets/libs/dropzone/min/dropzone.min.js"></script>

<!-- Demo js-->
<script src="<?=BASE_URL?>dist/assets/js/pages/form-fileuploads.js"></script>
<script>
    $(document).ready(function () {
        const fileInput = $('#validationCustomFile');
        const fileList = $('#file-list');

        // Show selected file names
        fileInput.on('change', function () {
            let files = fileInput[0].files;
            fileList.empty();
            if (files.length > 0) {
                let list = $('<ul></ul>');
                for (let i = 0; i < files.length; i++) {
                    list.append('<li>' + files[i].name + '</li>');
                }
                fileList.append(list);
            }
        });

        // Drag-and-drop functionality
        $('#drop-zone').on('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragging');
        });

        $('#drop-zone').on('dragleave', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragging');
        });

        $('#drop-zone').on('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragging');

            let files = e.originalEvent.dataTransfer.files;
            fileInput[0].files = files; // Assign dropped files to input
            fileInput.trigger('change'); // Trigger change event to display selected files
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('form.needs-validation').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Create a FormData object
            var formData = new FormData(this);

            // Add the action parameter
            formData.append('action', 'save_agent');

            $.ajax({
                url: '../functions.php', // URL of the PHP script
                type: 'POST', // POST request
                data: formData, // Form data with file input
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting the content type (it will be set by the browser)
                success: function(response) {
                    try {
                        var result = JSON.parse(response); // Parse JSON response
                        if(result.status == 'error'){
                            toastr.error(result.message);
                        }else{
                            toastr.success(result.message);
                        }
                        

                        if (result.status === 'success') {
                            // Redirect to another page
                            window.location.href = '../agents/index.php';
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




