<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../classes/listing.class.php');
    require_once(__DIR__ . '/../classes/user.class.php');
    require_once(__DIR__ . '/../classes/message.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/listings.tpl.php');

    $db = getDatabaseConnection();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /../pages/login.php');
        exit;
    }

    $sender = User::getUser($db, intval($_SESSION['user_id']));
    $receiver = User::getUser($db, (int)$_POST['receiver_id']);
    $messages = Message::getMessages($db, $receiver->id, $sender->id, intval($_POST['listing_id']));

    drawHeader();
    drawMessages($sender, $receiver, intval($_POST['listing_id']), $messages);
    drawFooter();
?>