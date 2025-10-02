<?php // listings.php
declare(strict_types = 1);

session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/listings.tpl.php');
require_once(__DIR__ . '/../classes/listing.class.php'); // Your updated Listing class

try {
    $db = getDatabaseConnection();
} catch (PDOException $e) {
    // In a real app, log this error and show a user-friendly message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Critical error: Could not connect to the database. Please check server logs or contact support.");
}

$searchTerm = null;
if (isset($_GET['search_query'])) {
    $searchTerm = trim($_GET['search_query']);
    if ($searchTerm === '') { // Treat empty string as no search
        $searchTerm = null;
    }
}

$sortOption = $_GET['sort_by'] ?? 'date_desc';

$validSortOptions = ['date_desc', 'date_asc', 'price_asc', 'price_desc', 'title_asc', 'title_desc'];
if (!in_array($sortOption, $validSortOptions)) {
    $sortOption = 'date_desc'; // Fallback to default if an invalid option is provided
}

$listings = Listing::getListingsBySearch($db, $searchTerm, $sortOption);

$pageTitle = "All Listings";
if ($searchTerm) {
    $pageTitle = "Search Results for \"" . htmlspecialchars($searchTerm) . "\"";
}

drawHeader();
drawSearch($searchTerm);
drawListings($pageTitle, $listings, $searchTerm, $sortOption);
drawFooter();
?>