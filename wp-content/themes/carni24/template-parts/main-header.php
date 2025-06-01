<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header>
        <div class="collapse bg-dark" id="navbarHeader">
            <div class="container d-none d-lg-block">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4 class="text-white"><?= get_field('navigation_headling', 7) ?></h4>
                        <p class="text-muted">
                            <?= get_field('navigation_content', 7) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container-fluid logo">
                <a href="/" class="navbar-brand d-flex align-items-center">
                    <strong>Carni24</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
        <div class="collapse bg-dark" id="navbarHeader">
            <div class="container d-block d-lg-none text-light">
                <div class="row d-none d-lg-block">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4 class="text-white"><?= get_field('navigation_headling', 7) ?></h4>
                        <p class="text-muted">
                            <?= get_field('navigation_content', 7) ?>
                        </p>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <ul>
                            <li>
                                <a href="https://www.facebook.com/Carni24" class="text-white nav-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                    </svg> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="">
                    <?php
                    wp_nav_menu(
                            array(
                                'menu' => 'Menu Główne',
                                'container' => false,
                                'items_wrap' => '<ul id="%1$s" class="navbar-nav me-auto mb-2 mb-lg-0">%3$s</ul>'
                            )
                    );
                    ?>
                </div>
            </div>
        </div>
    </header>