<?php

$servername = "localhost";
$dbusername = "root";
$password = "";
$dbname = "rukhsa_crm";

// Create connection
$conn = new mysqli($servername, $dbusername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

define('ROOT_PATH', __DIR__);
define('BASE_URL', 'https://localhost/crm.rukhsa.com/');

date_default_timezone_set('Asia/Dubai'); // Set Dubai Timezone

// Set Dubai timezone for MySQL
$conn->query("SET time_zone = '+04:00'");
?>
