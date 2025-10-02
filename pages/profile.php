<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../classes/listing.class.php');
    require_once(__DIR__ . '/../classes/user.class.php');    

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/listings.tpl.php');
    require_once(__DIR__ . '/../templates/user.tpl.php');

    $db = getDatabaseConnection();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /../pages/login.php');
        exit;
    }

    $user = User::getUser($db, $_SESSION['user_id']);

    $listings = Listing::getListingsbyUser($db, $user->id);
    $listings_bought = Listing::getListingsByBuyer($db, $user->id);


    drawHeader();
    drawUser($user);
    drawListingsFromUser($listings, false);
    drawListingsFromUser($listings_bought, true);
    drawFooter();
?>