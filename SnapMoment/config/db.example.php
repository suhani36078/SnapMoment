<?php
// Copy this file to db.php and update these values for your local environment.
$host = '127.0.0.1';
$port = 3306; // Change to your MySQL port (for example, 3307 in some XAMPP setups).
$dbname = 'snapmoment_db';
$username = 'root';
$password = ''; // Set your own local database password; do not commit real credentials.
try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check your local config/db.php settings.');
}
