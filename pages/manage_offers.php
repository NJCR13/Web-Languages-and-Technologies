<?php
declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../classes/listing.class.php');
require_once(__DIR__ . '/../classes/user.class.php');
require_once(__DIR__ . '/../classes/serviceorder.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/offers.tpl.php');

$db = getDatabaseConnection();

// Check if user is logged in and is provider
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$listings = Listing::getListingsbyUser($db, $_SESSION['user_id']);

drawHeader();
drawManageOffers($db,$listings);
drawFooter();
?>