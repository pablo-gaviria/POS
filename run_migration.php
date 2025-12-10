<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pos_system';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = 'ALTER TABLE expenses ADD COLUMN attachment_path VARCHAR(255) NULL AFTER amount';

if ($conn->query($sql)) {
    echo 'Migration successful: attachment_path column added to expenses table';
} else {
    if (strpos($conn->error, '1060') !== false) {
        echo 'Column already exists - no action needed';
    } else {
        echo 'Error: ' . $conn->error;
    }
}

$conn->close();
?>
