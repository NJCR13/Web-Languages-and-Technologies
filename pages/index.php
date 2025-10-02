<?php

    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../classes/listing.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/listings.tpl.php');

    $db = getDatabaseConnection();

    $listings = Listing::getTopListings($db);
    $categories = Listing::getCategories($db);

    drawHeader();
    drawWelcome();
    drawSearch();
    drawListingsSlider($listings);
    drawCategoriesSlider($categories);
    drawFooter();
?>