<?php declare(strict_types = 1); ?>

<?php function drawUser(User $user) { ?>
    <div class="user_profile">
        <div class="profile_pic">
            <img src="../images/defaultUser.jpg" alt="Default Avatar" width="380" height="400">
        </div>
        <div class="personal_info">
            <div class="user_name">
                <p><?=htmlspecialchars($user->name) ?></p>
            </div>
            <div class="user_contacts">
                <p><span class="label">Email:</span><?=htmlspecialchars($user->email) ?></p>
                <p><span class="label">Phone:</span><?=htmlspecialchars($user->phone ?? '') ?></p>
            </div>
            <div class="edit_button">
                <button onclick="window.location.href='../pages/edit_profile.php'" type="submit">
                    Edit
                </button>
            </div>
        </div>
    </div>
<?php } ?>

<?php function drawProfileEditForm(User $user) { ?>
    <div class="authform">
        <form action="action_edit_profile.php" method="post">
            <label for="name"><b>Name</b></label>
            <input type="text" id="name" placeholder="First and Last Name" name="name" class="name">

            <label for="uname"><b>Username</b></label>
            <input type="text" id="username" placeholder="Enter Username" name="username" class="username">

            <label for="psw"><b>Password</b></label>
            <input type="password" id="password" placeholder="Enter Password" name="password" class="password">

            <label for="email"><b>Email</b></label>
            <input type="text" id="email" placeholder="Enter Email" name="email" class="email">

            <label for="phone"><b>Phone</b></label>
            <input type="text" id="phone" placeholder="Enter Phone Number" name="phone" class="phone">
            
            <button formaction="../actions/action_edit_profile.php" formmethod="post" type="submit">Confirm</button>
        

            <div class="container">
                <button onclick="window.location.href='../pages/profile.php'" type="button" class="cancelbtn">Cancel</button>
            </div>
        </form>
    </div>
<?php } ?>

<?php function drawAdminPage(string $message = '') { ?>
    <div class="admin-container">
        <h1>Admin Dashboard</h1>

        <?php if ($message) : ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="admin-section">
            <h2>Promote User to Admin</h2>
            <form method="post">
                <input type="text" name="username" placeholder="Enter username" required>
                <button type="submit" name="promote_user" class="confirmbtn">Promote</button>
            </form>
        </div>

        <div class="admin-section">
            <h2>Delete User</h2>
            <form method="post">
                <input type="text" name="username" placeholder="Enter username" required>
                <button type="submit" name="delete_user" class="cancelbtn">Delete User</button>
            </form>
        </div>

        <div class="admin-section">
            <h2>Delete Listing</h2>
            <form method="post">
                <input type="number" name="listing_id" placeholder="Enter listing ID" required>
                <button type="submit" name="delete_listing" class="cancelbtn">Delete Listing</button>
            </form>
        </div>
    </div>
<?php } ?>



