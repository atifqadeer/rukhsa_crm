<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';
// Increase the maximum execution time to handle large uploads
set_time_limit(0); // Unlimited execution time
//ini_set('memory_limit', '-1'); // Use as much memory as available

// Set PHP configurations for file uploads to a very high value
ini_set('upload_max_filesize', '10G');
ini_set('post_max_size', '10G');

// Set the timezone for the MySQL connection
$conn->query("SET time_zone = '+05:00'");
date_default_timezone_set('Asia/Karachi');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $address_order_id = $_POST['address_order_id'];
    $order_type = $_POST['order_type'];
    $code = $_POST['code'];
    $file_link = $_POST['file_link'];
    $preferences = $_POST['preferences'];

    // Fetch username from database
    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Create a unique directory structure
    $year = date('Y');
    $month = date('m');
    $date = date('d-m-Y');
    $timestamp = time(); // Use current timestamp for uniqueness
    $directory = "OrderFiles/$year/$month/$date/{$user_id}_{$username}_{$address_order_id}_$timestamp/";

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Handle file upload
    $uploaded_files = [];
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['files']['name'][$key];
        $file_tmp = $_FILES['files']['tmp_name'][$key];
        $file_path = $directory . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $uploaded_files[] = $file_path;
        }
    }

    // Remove duplicate file paths
    $uploaded_files = array_unique($uploaded_files);

    // Insert form data and directory path into the database
    $query = "INSERT INTO orders (users_id, address_order, type, client_code, files_link, preferences, folder_path, order_time) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issssss", $user_id, $address_order_id, $order_type, $code, $file_link, $preferences, $directory);
    $stmt->execute();
    $stmt->close();

    // Respond with a success message
    echo json_encode(['status' => 'success']);
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
