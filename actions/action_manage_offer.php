<?php
    declare(strict_types=1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../classes/serviceorder.class.php');

    if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        die(header('Location: /'));
    }

    $db = getDatabaseConnection();
    $order_id = (int)$_POST['order_id'];
    $action = $_POST['action'];

    // Get order details
    $stmt = $db->prepare('SELECT * FROM ServiceOrder WHERE id = ?');
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    // Verify user is the provider
    if (!$order || $_SESSION['user_id'] !== (int)$order['provider_id']) {
        die(header('Location: /'));
    }

    // Update status
    $new_status = match($action) {
        'accept' => 'In Progress',
        'decline' => 'Cancelled',
        'complete' => 'Completed',
        'cancel' => 'Cancelled',
        default => 'Pending'
    };

    ServiceOrder::updateStatus($db, $order_id, $new_status);

    header("Location: ../pages/manage_offers.php?listing_id=" . (int)$order['listing_id']);
?>