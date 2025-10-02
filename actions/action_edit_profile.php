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

    $db = getDatabaseConnection();

    $user = User::getUser($db, $_SESSION['user_id']);

    if ($user) {

        if (isset($_POST['name']) && $_POST['name'] !== '')
            $user->name = $_POST['name'];

        if (isset($_POST['username']) && $_POST['username'] !== '')
            $user->username = $_POST['username'];

        if (isset($_POST['password']) && $_POST['password'] !== '')
            $user->password = $_POST['password'];

        if (isset($_POST['email']) && $_POST['email'] !== '')
            $user->email = $_POST['email'];

        if (isset($_POST['phone']) && $_POST['phone'] !== '')
            $user->phone = $_POST['phone'];


        $user->save($db);
    }

    header('Location: /../pages/profile.php');
?>