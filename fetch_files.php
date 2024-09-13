<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php'; // Adjust the path as necessary
if (isset($_POST['folder_path'])) {
    $folder_path = $_POST['folder_path'];

    // Check if the folder exists
    if (is_dir($folder_path)) {
        $files = scandir($folder_path);
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $file_path = $folder_path . '/' . $file;
                $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
                
                echo "<div>";
                echo "<strong>" . $file . "</strong><br>";

                // Display different types of previews based on file type
                if (in_array($file_ext, ['jpg', 'png', 'gif','webp'])) {
                    echo "<img src='$file_path' style='max-width: 100%; height: auto;'><br>";
                } elseif ($file_ext === 'pdf') {
                    echo "<embed src='$file_path' width='100%' height='500px' />";
                } elseif (in_array($file_ext, ['zip', 'rar'])) {
                    echo "<a href='$file_path' download>Download $file</a>";
                } else {
                    echo "<a href='$file_path' download>Download $file</a>";
                }

                echo "</div><hr>";
            }
        }
    } else {
        echo "Folder not found.";
    }
}
?>
