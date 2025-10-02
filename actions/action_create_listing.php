<?php
declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../classes/listing.class.php');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(header('Location: /'));
}

$db = getDatabaseConnection();

$title = trim($_POST['title']);
$category = trim($_POST['category']);
$price = (float)$_POST['price'];
$delivery_time = (int)$_POST['delivery_time'];
$description = trim($_POST['description']);
$provider_id = (int)$_SESSION['user_id'];
$date = date('Y-m-d');  // Current date

if (Listing::createListing($db, $title, $category, $price, $delivery_time, 
                         $description, $date, $provider_id)) {
    header('Location: /../pages/profile.php?success=1');
} else {
    header('Location: /../pages/create_listing.php?error=1');
}
?>