<?php
    declare(strict_types=1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../includes/session.php');

    require_once(__DIR__ . '/../classes/user.class.php');

    $db = getDatabaseConnection();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = User::getUserByNameAndPassword($db,$username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['is_admin'] = $user->is_admin;
    }

    header('Location: /../pages/');
?>