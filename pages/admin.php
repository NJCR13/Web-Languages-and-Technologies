<?php
declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../includes/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../classes/user.class.php');
require_once(__DIR__ . '/../classes/listing.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/user.tpl.php');

// Verify admin status
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /');
    exit;
}

$db = getDatabaseConnection();

// Handle form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['promote_user'])) {
        $username = trim($_POST['username']);
        if (User::promoteToAdmin($db, $username)) {
            $message = "User $username promoted to admin";
        } else {
            $message = "Error promoting user";
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $username = trim($_POST['username']);
        if (User::deleteUser($db, $username)) {
            $message = "User $username deleted";
        } else {
            $message = "Error deleting user";
        }
    }
    
    if (isset($_POST['delete_listing'])) {
        $listing_id = (int)$_POST['listing_id'];
        if (Listing::deleteListing($db, $listing_id)) {
            $message = "Listing #$listing_id deleted";
        } else {
            $message = "Error deleting listing";
        }
    }
}

drawHeader();
drawAdminPage($message);
drawFooter();
?>