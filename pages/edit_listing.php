<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../classes/listing.class.php');
    require_once(__DIR__ . '/../classes/user.class.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/listings.tpl.php');

    $db = getDatabaseConnection();

    if (!isset($_SESSION['user_id'])) {
        header('Location: /../pages/login.php');
        exit;
    }

    $listing = Listing::getListingById($db, (int)$_GET['listing_id']);
    
    // Verify listing exists and user is owner
    if (!$listing || $_SESSION['user_id'] !== $listing->provider_id) {
        header('Location: /../pages/');
        exit;
    }

    drawHeader();
    drawListingEditForm($listing); // Add this function to listings.tpl.php
    drawFooter();
?>