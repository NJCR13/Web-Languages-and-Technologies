<?php
declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../classes/listing.class.php');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    die(header('Location: /../pages/login.php'));
}
$listing_id = (int)$_POST['listing_id'];
// var_dump($listing_id);
// exit;
$db = getDatabaseConnection();

// Validate listing ownership
$listing = Listing::getListingById($db, $listing_id);
if (!$listing || $_SESSION['user_id'] !== $listing->provider_id) {
    die(header('Location: /../pages/'));
}

// Update listing properties (excluding images)
$listing->title = trim($_POST['title']);
$listing->category = trim($_POST['category']);
$listing->price = (float)$_POST['price'];
$listing->delivery_time = (int)$_POST['delivery_time'];
$listing->description = trim($_POST['description']);

// Persist changes
if ($listing->update($db)) {
    header("Location: /../pages/listing.php?listing_id=" . $listing->id);
} else {
    header("Location: /../pages/edit_listing.php?listing_id=" . $listing->id . "&error=1");
}
?>