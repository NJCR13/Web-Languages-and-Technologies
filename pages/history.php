<!DOCTYPE html>
<html lang="en-US">

    <head>
        <meta charset="UTF-8">
        <title>Nome do Site</title>
    </head>

    <body>

        <header class="header_main">
            <a  href="index.php">
                <img src="https://picsum.photos/200/50?business" alt="Site Photo">
            </a>
            

            <form>
                <button formaction="listings.php" formmethod="post" type="submit">
                    Listings
                </button>
                <button formaction="register.php" formmethod="post" type="submit">
                    Register
                </button>
                <button formaction="login.php" formmethod="post" type="submit">
                    Login
                </button>
            </form>

        </header>
        <main>
            <div class="listing_history">
                <form>
                    <button>
                        <img src="https://picsum.photos/200/200?listing" alt="Site Photo">
                        <h2>Listing Title</h2>
                        <p>Listing description</p>
                    </button>
                </form>
                <form>
                    <button>
                        <img src="https://picsum.photos/200/200?listing" alt="Site Photo">
                        <h2>Listing Title</h2>
                        <p>Listing description</p>
                    </button>
                </form>
                <form>
                    <button>
                        <img src="https://picsum.photos/200/200?listing" alt="Site Photo">
                        <h2>Listing Title</h2>
                        <p>Listing description</p>
                    </button>
                </form>
            </div>
        </main>
        <footer>
            
            <p class="footer_mini_title">FreelanceHub</p>
            <p class="footer_text">Find the best freelancers for your projects</p>
            
            <p class="footer_text">Need assistance?</p>
            <p class="footer_mini_title">Contact us at (+123) 910 911 912</p>
            <p class="footer_mini_title_underlined">support@freelancers.com</p>

        </footer>

    </body>

</html>