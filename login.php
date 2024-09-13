<?php
include 'config.php'; // Adjust the path as necessary
session_start();

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize input
    // $email = mysqli_real_escape_string($conn, $email);
    // $portal = mysqli_real_escape_string($conn, $portal);

    // Query to get the user's details and role
    $sql = "SELECT id, password, role FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['password'];
        $user_role = $row['role']; // Get the user's role from the database

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $stored_password)) {
            // Password is correct

            // Check if the user's role matches the selected portal
            // if (($user_role === 'agent') ||
            //     ($user_role === 'team_lead') ||
            //     ($user_role === 'admin')) {

                // Store user details and role in the session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $user_role; 

                // Redirect based on the selected portal
                switch ($user_role) {
                    case 'agent':
                        $redirect_url = 'agent_dashboard.php';
                        break;
                    case 'team_lead':
                        $redirect_url = 'team_lead_dashboard.php';
                        break;
                    case 'admin':
                        $redirect_url = 'admin_dashboard.php';
                        break;
                    default:
                        $redirect_url = 'main.php'; // Default page if portal is not recognized
                }

                $response = [
                    'status' => 'success', 
                    'message' => 'Login successful. Redirecting...', 
                    'redirect' => $redirect_url
                ];
            // } else {
            //     // Role does not match the selected portal
            //     $response = ['status' => 'error', 'message' => 'Invalid portal selection for your role.'];
            // }
        } else {
            // Password is incorrect
            $response = ['status' => 'error', 'message' => 'Invalid password.'];
        }
    } else {
        // No user found with that email address
        $response = ['status' => 'error', 'message' => 'No user found with that username.'];
    }

    // Close the database connection
    mysqli_close($conn);

    // Send the response as JSON
    echo json_encode($response);
    exit();
}
