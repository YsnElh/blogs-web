<header class="tm-header" id="tm-header">
    <div class="tm-header-wrapper">
        <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="tm-site-header">
            <a href="/" style="color:#fff">
                <h1 class="text-center">Blogs</h1>
            </a>
        </div>
        <nav class="tm-nav" id="tm-nav">
            <ul>
                <?php
                $currentPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
                $navItems = array(
                    'index' => array('Blog Home', 'fas fa-home'),
                    'posts' => array('All Posts', 'fas fa-pen'),
                    'login' => array('Login', 'fas fa-sign-in-alt'),
                    'register' => array('Sign Up', 'fas fa-user-plus'),
                    'account' => array('Account', 'fas fa-user'),
                    'about' => array('About Us', 'fas fa-users'),
                    'contact' => array('Contact Us', 'far fa-comments'),
                );
                $loggedIn = isset($_SESSION['user_id']);

                foreach ($navItems as $link => $data) {
                    $title = $data[0];
                    $iconClass = $data[1];
                    if (($link == 'login' || $link == 'register') && $loggedIn) {
                        continue;
                    }

                    if ($link == 'account' && !$loggedIn) {
                        continue;
                    }
                    $class = ($currentPage == $link) ? 'active' : '';
                    echo '<li class="tm-nav-item ' . $class . '"><a href="' . ($link == 'index' ? '/' : $link) . '" class="tm-nav-link">';
                    echo '<i class="' . $iconClass . '"></i>';
                    echo $title;
                    echo '</a></li>';
                }
                ?>
            </ul>
        </nav>
        <div class="tm-mb-65">
            <a href="https://github.com/YsnElh" target="_blank" class="tm-social-link">
                <i class="fab fa-github tm-social-icon"></i>
            </a>
            <a href="https://www.linkedin.com/in/yassine-elhainouni/" target="_blank" class="tm-social-link">
                <i class="fab fa-linkedin tm-social-icon"></i>
            </a>
        </div>
        <p class="tm-mb-80 pr-5 text-white">
            Explore transformative blogs on mindful living, global flavors, sustainable choices, and more • crafting a
            conscious lifestyle awaits.
        </p>
    </div>
</header>