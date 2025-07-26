
<?php
$host = 'localhost';
$dbname = 'progear_rentals';
$user = 'root';
$pass = ''; // Default is empty for XAMPP/WAMP

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
