<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../classes/listing.class.php');
    require_once(__DIR__ . '/../classes/user.class.php');
    require_once(__DIR__ . '/../classes/order.class.php');


    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/listings.tpl.php');
    require_once(__DIR__ . '/../templates/review.tpl.php');

    $db = getDatabaseConnection();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /../pages/');
        exit;
    }

    $listing = Listing::getListingById($db, intval($_GET['listing_id']));
    $user = User::getUser($db, $listing->provider_id);
    $order = Order::getByListingAndUser($db, $listing->id, $_SESSION['user_id']);

    if($_SESSION['user_id'] != $order->buyer_id) {
        header('Location: /../pages/');
        exit;
    }

    drawHeader();
    drawReviewForm($listing, $user, $order);
    drawFooter();
?>