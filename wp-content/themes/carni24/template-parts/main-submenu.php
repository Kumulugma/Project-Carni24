<section id="sub-menu" class="bg-dark px-5">
    <div class="nav-scroller">
        <?php wp_nav_menu(
                array(
                        'menu' => 'Menu Główne',
                        'container'       => false,
                        'items_wrap' => '<ul id="%1$s" class="nav d-flex justify-content-between">%3$s</ul>'
                )
            ); 
        ?>
    </div>
</section>