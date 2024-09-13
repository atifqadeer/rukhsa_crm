<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php'; // Database connection
include 'header.php'; // Header HTML

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']); // Sanitize input

    $query = "SELECT * FROM orders WHERE address_order LIKE '%$search%' AND users_id = '" . $_SESSION['user_id'] . "'"; // Adjust the table and column name if necessary
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-bordered mt-3'>"; // Start table
        echo "<thead><tr><th>Address/Order ID</th><th>Type</th><th>Client Code</th><th>Status</th></tr></thead><tbody>"; // Table headers

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>"; // Start row
            echo "<td>" . $row['address_order'] . "</td>"; 
            echo "<td>" . $row['type'] . "</td>"; 
            echo "<td>" . $row['client_code'] . "</td>"; 
            echo "<td>" . $row['status'] . "</td>"; 
            echo "</tr>"; // End row
        }
    } else {
        echo "<p>No matching orders found.</p>";
    }
}
?>
