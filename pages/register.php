<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/user.tpl.php');

    drawHeader();
    drawRegisterPage();
    drawFooter();
?>