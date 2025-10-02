<?php
    declare(strict_types = 1);

    session_start();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../classes/listing.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/listings.tpl.php');

    $db = getDatabaseConnection();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /../pages/login.php');
        exit;
    }

    drawHeader();
?>
    <div style="display: flex; justify-content: center;">
      <img src="../images/SecretUberScreenshot.png" alt="sus">
    </div>
<?php
    drawFooter();
?>