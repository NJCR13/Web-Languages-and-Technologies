<?php 
    declare(strict_types=1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../classes/message.class.php');
    require_once(__DIR__ . '/../classes/user.class.php');

    header('Content-Type: application/json');

    if (!isset($_POST['sender_id'], $_POST['receiver_id'], $_POST['listing_id'], $_POST['message'])) {
        echo json_encode(['success' => false, 'error' => 'Missing data']);
        exit;
    }

    $sender_id = intval($_POST['sender_id']);
    $receiver_id = intval($_POST['receiver_id']);
    $listing_id = intval($_POST['listing_id']);
    $message_text = trim($_POST['message']);

    if ($message_text === '') {
        echo json_encode(['success' => false, 'error' => 'Empty message']);
        exit;
    }

    try {
        $db = getDatabaseConnection();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Make sure the sender and receiver are valid users
        $sender = User::getUser($db, $sender_id);
        $receiver = User::getUser($db, $receiver_id);

        if (!$sender || !$receiver) {
            echo json_encode(['success' => false, 'error' => 'Invalid sender or receiver']);
            exit;
        }

        $success = Message::sendMessage($db, $message_text, $sender_id, $receiver_id, $listing_id);

        if ($success) {
            echo json_encode([
                'success' => true,
                'date' => date('H:i'),
                'text' => $message_text
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to send message']);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Server error: ' . $e->getMessage()
        ]);
    }
?>
