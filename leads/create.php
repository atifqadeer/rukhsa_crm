<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: <?=BASE_URL?>index.php");
    exit();
}

include '../config.php'; // Database connection
include '../header.php'; // Header HTML
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
</style>
<div class="layout-wrapper">
    <?php include '../sidebar.php'; ?>
    <div class="page-content">
        <?php include '../topbar.php'; ?>
        <div class="px-3">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="py-3 py-lg-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="page-title mb-0">Place a new lead</h4>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-none d-lg-block">
                                <ol class="breadcrumb m-0 float-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Leads Management</a></li>
                                    <li class="breadcrumb-item active">Place a new lead</li>
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
                                <form id="order-form" class="needs-validation" action="process_order.php" method="post" enctype="multipart/form-data" novalidate>
                                    <!-- Your form fields -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom06" class="form-label">Address / Order ID<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" id="validationCustom06" name="address_order_id" placeholder="Enter Property Address or Order ID" required>
                                            <div class="invalid-feedback">
                                                Please provide Address / Order ID.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom07" class="form-label">Order Type<span style="color: red;">*</span></label>
                                            <select class="form-select" id="validationCustom07" name="order_type" required>
                                                <option value="">Select Order Type</option>
                                                <option value="2D FloorPlan">2D Floor Plan</option>
                                                <option value="3D Floorplan">3D Floorplan</option>
                                                <option value="EPC">EPC</option>
                                                <option value="Site Plan">Site Plan</option>
                                                <option value="Photo Editing">Photo Editing</option>
                                                <option value="Video Editing">Video Editing</option>
                                                <option value="Virtual Staging">Virtual Staging</option>
                                                <option value="Video Slideshows">Video Slideshows</option>
                                                <option value="Walkthrough Animation">Walkthrough Animation</option>
                                                <option value="CGI">CGI</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select an Order Type.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom08" class="form-label">Code</label>
                                            <input type="text" class="form-control" id="validationCustom08" name="code" placeholder="Enter Client Code if any">
                                            <div class="invalid-feedback">
                                                Please provide Code.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom10" class="form-label">Files Link (URL)</label>
                                            <input type="url" class="form-control" id="validationCustom10" name="file_link" placeholder="Files Link (e.g., Dropbox, WeTransfer)">
                                            <div class="invalid-feedback">
                                                Please provide a valid Files Link (URL).
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="validationCustom09" class="form-label">Preferences</label>
                                            <textarea class="form-control" id="validationCustom09" name="preferences" placeholder="Enter order preferences if any"></textarea>
                                            <div class="invalid-feedback">
                                                Please provide Preferences.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="validationCustomFile" class="form-label">Upload Files</label>
                                            <div id="drop-zone" class="drop-zone">
                                                <input type="file" class="form-control" id="validationCustomFile" name="files[]" multiple style="display: none;">
                                                <label class="btn btn-primary">
                                                    <input type="file" id="file-input" name="files[]" multiple style="display: none;">
                                                    Choose files 
                                                </label> 
                                                <span class="form-label">or drag & drop here to upload</span>
                                                <div id="file-list" class="mt-3">
                                                    <p>Selected files:</p>
                                                    <!-- File items will be added here dynamically -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                </form>
                                <div id="loading-message" style="display: none; text-align: center;">
                                    <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
                                    <h4>Submitting Order and Sending Email...</h4>
                                    <div class="progress mt-3">
                                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
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

<?php include '../footer.php'; ?>
<script>
    const form = document.getElementById('order-form');
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileList = document.getElementById('file-list');
    let filesToUpload = new Map();

    form.addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            this.classList.add('was-validated');
        } else {
            event.preventDefault();
            // Hide the form and show the loading message
            document.getElementById('order-form').style.display = 'none';
            document.getElementById('loading-message').style.display = 'block';

            const formData = new FormData(this);

            // Append files to formData
            filesToUpload.forEach((file) => {
                formData.append('files[]', file);
            });

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process_order.php', true);

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    const progressBar = document.getElementById('progress-bar');
                    progressBar.style.width = percentComplete + '%';
                    progressBar.innerHTML = Math.floor(percentComplete) + '%';
                    progressBar.setAttribute('aria-valuenow', Math.floor(percentComplete));
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status == 200) {
                    window.location.href = 'order_confirmation.php';
                } else {
                    console.error('Error:', xhr.statusText);
                    document.getElementById('loading-message').style.display = 'none';
                    document.getElementById('order-form').style.display = 'block';
                    alert('An error occurred while submitting the form. Please try again.');
                }
            });

            xhr.addEventListener('error', function() {
                console.error('Error:', xhr.statusText);
                document.getElementById('loading-message').style.display = 'none';
                document.getElementById('order-form').style.display = 'block';
                alert('An error occurred while submitting the form. Please try again.');
            });

            xhr.send(formData);
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFiles(files);
        }
    });

    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files.length > 0) {
            handleFiles(files);
        }
    });

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileId = `${file.name}-${file.size}-${file.lastModified}`;
            if (!filesToUpload.has(fileId)) {
                filesToUpload.set(fileId, file); // Add file to filesToUpload map
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB conversion
                const listItem = document.createElement('div');
                listItem.classList.add('d-flex', 'justify-content-between', 'align-items-center');
                listItem.innerHTML = `
                    <p class="mb-0">${file.name} (${fileSize} MB)</p>
                    <button type="button" class="btn btn-sm btn-danger">Remove</button>
                `;
                fileList.appendChild(listItem);

                listItem.querySelector('.btn-danger').addEventListener('click', () => {
                    filesToUpload.delete(fileId); // Remove file from filesToUpload map
                    fileList.removeChild(listItem);
                    updateFileInput();
                });
            }
        }
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        filesToUpload.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;
    }
</script>


