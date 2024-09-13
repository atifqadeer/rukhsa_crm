<?php
include 'config.php'; // Adjust the path as necessary
include 'header.php'; // Adjust the path as necessary
?>
<style>
.custom-dropdown {
    position: relative;
}

.custom-dropdown select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding-right: 30px; /* Space for the icon */
}

.custom-dropdown:after {
    content: '\25BC'; /* Unicode for down arrow */
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    pointer-events: none;
}
</style>
<body class="bg-primary d-flex justify-content-center align-items-center min-vh-100 p-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-md-5">
                <div class="card">
                    <div class="card-body p-4">

                        <div class="text-center w-75 mx-auto auth-logo mb-4">
                            <a href="#" class="logo-dark">
                                <!--<span><img src="dist/assets/images/logo.webp" alt="" height="50px" width="200px"></span>-->
                                <h2>Rukhsa CRM</h2>
                            </a>

                            <a href="#" class="logo-light">
                                <!--<span><img src="dist/assets/images/logo.webp" alt="" height="50px" width="200px"></span>-->
                                <h2>Rukhsa CRM</h2>
                            </a>
                        </div>

                        <form id="loginForm" action="login.php" method="post">
                            <div class="form-group mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input class="form-control" type="text" id="username" name="username" required="" placeholder="Enter your username">
                            </div>
                        
                            <div class="form-group mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
                            </div>
                        
                            <div class="form-group mb-3">
                                <div class="">
                                    <input class="form-check-input" type="checkbox" id="checkbox-signin" checked>
                                    <label class="form-check-label ms-2" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>
                        
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary w-100" type="submit"> Log In </button>
                            </div>
                        </form>

                        <!-- Loader -->
                        <div id="loader" style="display: none;" class="text-center mt-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <!-- Response Message -->
                        <div id="responseMessage" class="mt-3 text-center" style="margin-top: 15px;"></div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <!--<div class="row mt-3">-->
                <!--    <div class="col-12 text-center">-->
                <!--        <p class="text-white-50"> <a href="pages-register.html" class="text-white-50 ms-1">Forgot your password?</a></p>-->
                <!--    </div> <!-- end col -->-->
                <!--</div>-->
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Show the loader
    document.getElementById('loader').style.display = 'block';

    // Disable the submit button to prevent multiple submissions
    document.querySelector('#loginForm button[type="submit"]').disabled = true;

    // Get form data
    var formData = new FormData(this);

    // Send the form data via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'login.php', true);
    xhr.onload = function() {
        document.getElementById('loader').style.display = 'none'; // Hide the loader
        document.querySelector('#loginForm button[type="submit"]').disabled = false; // Enable the submit button

        var response = JSON.parse(this.responseText);
        var responseMessage = document.getElementById('responseMessage');
        responseMessage.innerHTML = response.message;

        if (response.status === 'success') {
            responseMessage.className = 'alert alert-success';//DataTables and related libraries
            setTimeout(function() {
                window.location.href = response.redirect; // Redirect to the appropriate dashboard
            }, 2000);
        } else {
            responseMessage.className = 'alert alert-danger';
        }
    };
    xhr.send(formData);
});

    </script>

</body>

<?php
include 'footer.php';
?>
