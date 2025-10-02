<?php declare(strict_types = 1); 

// require_once(__DIR__ . '/../includes/session.php');
?>

<?php function drawHeader() { ?>
    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <title>FreelanceHub</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../javascript/choosePayment.js"></script>
        <script src="../javascript/chooseRating.js"></script>
        <script src="../javascript/slider.js"></script>
        <script src="../javascript/chat.js"></script>
    </head>

    <body>
        <header class="header_main">

            <a href="../index.php"><img src="https://picsum.photos/50/50?business" alt="Site Photo"><h1>FreelanceHub</h1></a>

            <form class="header_form">
                <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) { ?> 
                    <button formaction="../pages/admin.php" formmethod="post" type="submit">
                        Admin
                    </button>
                <?php } ?>
                <button formaction="../pages/listings.php" formmethod="post" type="submit">
                    Listings
                </button>
                <?php if (!isset($_SESSION['user_id'])) {?>
                <button formaction="../pages/login.php" formmethod="post" type="submit">
                    Login
                </button>
                <button formaction="../pages/register.php" formmethod="post" type="submit">
                    Register
                </button>
                <?php } else { ?>
                <button formaction="../pages/profile.php" formmethod="get" type="submit">
                    Profile
                </button>
                <button formaction="../actions/action_logout.php" formmethod="post" type="submit">
                    Logout
                </button>
                <?php } ?>
            </form>

        </header>
    <main class="site-content">
<?php } ?>

<?php function drawFooter() { ?>
    </main>
    <footer>
            <div class="footer_leftsection">
                <div class="footer_text">
                    <h1>FreelanceHub</h1>
                    <p>Find the best freelancers for your projects!</p>
                </div>
                <ul class="footer_icons">
                    <li><a href="https://instagram.com"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="https://facebook.com"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://x.com"><i class="fa fa-twitter"></i></a></li>
                </ul>
            </div>
            
            <div class="footer_rightsection">
                <span>
                    Need help?
                    Our support team is here to help    
                </span>
                <h2><i class="fa fa-phone"></i> Contact us at (+123)</h2>
                <h2><i class="fa fa-envelope"></i> support@email.com</h2>
            </div>
        </footer>

    </body>

</html>
<?php } ?>

<?php function drawWelcome() { ?>
    <div class="welcome">
        <h1>Welcome to FreelanceHub</h1>
    </div>
<?php } ?> 

<?php function drawSearch(?string $currentSearchTerm = '') { ?>
    <div class="searchbar">
        <p>Find the perfect freelancer for your project</p>
        <form action="listings.php" method="GET">
            <input type="search" name="search_query" placeholder="Search for services..." value="<?= htmlspecialchars((string)$currentSearchTerm) ?>">
            <button type="submit">Search</button>
        </form>
    </div>
<?php } ?>

<?php function drawLoginPage() { ?>
      <div class="authform">
        <form action="action_login.php" method="post">
                <label for="username"><b>Username</b></label>
                <input type="text" id="username" placeholder="Enter Username" name="username" required>

                <label for="password"><b>Password</b></label>
                <input type="password" id="password" placeholder="Enter Password" name="password" required>

                <label>
                <input type="checkbox" name="remember"> Remember me
                </label>
                <button formaction="../actions/action_login.php" formmethod="post" type="submit" class="login">Login</button>

            <div class="container">
                <button onclick="window.location.href='../pages/'" type="button" class="cancelbtn">Cancel</button>
                <span class="psw"><a href="../pages/register.php">Dont have an account? Register</a></span>
            </div>
        </form>
    </div>
<?php } ?>

<?php function drawRegisterPage() { ?>
    <div class="authform">
        <form action="action_register.php" method="post">
            <label for="name"><b>Name</b></label>
            <input type="text" id="name" placeholder="First and Last Name" name="name" class="name" required>

            <label for="uname"><b>Username</b></label>
            <input type="text" id="username" placeholder="Enter Username" name="username" class="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" id="password" placeholder="Enter Password" name="password" class="password" required>

            <label for="email"><b>Email</b></label>
            <input type="text" id="email" placeholder="Enter Email" name="email" class="email" required>

            
            <button formaction="../actions/action_register.php" formmethod="post"type="submit">Register</button>
        

            <div class="container">
                <button onclick="window.location.href='../pages/'" type="button" class="cancelbtn">Cancel</button>
                <span class="psw"><a href="../pages/login.php">Already have account?</a></span>
            </div>
        </form>
    </div>
<?php } ?>

<?php function drawMessages(User $sender, User $receiver, int $listing, array $messages) { ?>
    <div class="chat-container">
        <h2>Conversation with <?= htmlspecialchars($receiver->name) ?></h2>

        <div class="chat-box" id="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="chat-message <?= $message->provider_id === $sender->id ? 'sent' : 'received' ?>">
                    <p class="message-text"><?= htmlspecialchars($message->text) ?></p>
                    <span class="message-date"><?= date('H:i', strtotime($message->date)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <form class="chat-form" id="chat-form" action="../actions/action_send_message.php" method="post">
            <input type="hidden" name="sender_id" value="<?= $sender->id ?>">
            <input type="hidden" name="receiver_id" value="<?= $receiver->id ?>">
            <input type="hidden" name="listing_id" value="<?= $listing ?>">
            <textarea name="message" placeholder="Type your message..." required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
<?php } ?>

<?php function drawSingleMessage(Message $message, int $currentUserId) {
    $isSender = $message->provider_id === $currentUserId; ?>
    <div class="message <?= $isSender ? 'sent' : 'received' ?>">
        <div class="message-content">
            <p><?= htmlspecialchars($message->text) ?></p>
            <span class="timestamp"><?= date('H:i', strtotime($message->date)) ?></span>
        </div>
    </div>
<?php } ?>