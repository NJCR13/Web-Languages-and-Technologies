<?php
    declare(strict_types=1);

    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /../pages/login.php');
        exit;
    }

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../includes/session.php');

    require_once(__DIR__ . '/../classes/user.class.php');
    require_once(__DIR__ . '/../classes/listing.class.php');
    require_once(__DIR__ . '/../classes/order.class.php');
    require_once(__DIR__ . '/../classes/review.class.php');

    $db = getDatabaseConnection();

    $listing = Listing::getListingById($db, intval($_POST['listing_id']));
    $buyer = User::getUser($db, $_SESSION['user_id']);
    $provider = User::getUser($db, $listing->provider_id);
    

    Review::createReview($db, intval($_POST['rating']), $_POST['comment'], $provider->id, $buyer->id, $listing->id);
    
    header('Location: /../pages/listing.php?listing_id=' . $listing->id);
?>