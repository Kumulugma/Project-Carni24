<?php
/**
 * Template głównej stopki
 * Plik: template-parts/main-footer.php
 * Autor: Carni24 Team
 * POPRAWIONA WERSJA - container-fluid, bez newsletter, bez social
 */
?>

<footer id="footer" class="main-footer">
    <div class="container-fluid">
        
        <!-- Główna treść stopki -->
        <div class="footer-content">
            <div class="row px-5 g-5">
                
                <!-- Kolumna 1: O nas -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <?php
                            $custom_logo_id = get_theme_mod('custom_logo');
                            if ($custom_logo_id) {
                                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                                echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="footer-logo-img">';
                            } else {
                                echo '<h3 class="footer-brand">' . get_bloginfo('name') . '</h3>';
                            }
                            ?>
                        </div>
                        
                        <p class="footer-description">
                            <?= get_bloginfo('description') ?: 'Twoje źródło wiedzy o roślinach mięsożernych. Odkryj fascynujący świat natury!' ?>
                        </p>
                    </div>
                </div>
                
                <!-- Kolumna 2: Nasze zasoby -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">
                            <i class="bi bi-collection"></i>
                            Nasze zasoby
                        </h5>
                        <?php
                        wp_nav_menu(array(
                            'menu' => 'Stopka - Menu treści',
                            'container' => false,
                            'menu_class' => 'footer-menu',
                            'fallback_cb' => function() {
                                echo '<ul class="footer-menu">
                                    <li><a href="' . home_url('/kategorie/') . '">Kategorie</a></li>
                                    <li><a href="' . home_url('/gatunki/') . '">Gatunki</a></li>
                                    <li><a href="' . home_url('/poradniki/') . '">Poradniki</a></li>
                                    <li><a href="' . home_url('/galeria/') . '">Galeria</a></li>
                                </ul>';
                            }
                        ));
                        ?>
                    </div>
                </div>
                
                <!-- Kolumna 3: Najnowsze wpisy -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-section">
                        <h5 class="footer-title">
                            <i class="bi bi-newspaper"></i>
                            Najnowsze wpisy
                        </h5>
                        
                        <div class="footer-posts">
                            <?php
                            $recent_posts = new WP_Query(array(
                                'post_type' => 'post',
                                'posts_per_page' => 3,
                                'post_status' => 'publish'
                            ));
                            
                            if ($recent_posts->have_posts()) :
                                while ($recent_posts->have_posts()) : $recent_posts->the_post();
                            ?>
                                <article class="footer-post">
                                    <a href="<?= esc_url(get_permalink()) ?>" class="footer-post-link">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="footer-post-thumb">
                                                <?= get_the_post_thumbnail(get_the_ID(), 'widget_thumb') ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="footer-post-content">
                                            <h6 class="footer-post-title">
                                                <?= wp_trim_words(get_the_title(), 8) ?>
                                            </h6>
                                            <span class="footer-post-date">
                                                <i class="bi bi-calendar3"></i>
                                                <?= get_the_date('d.m.Y') ?>
                                            </span>
                                        </div>
                                    </a>
                                </article>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Dolna część stopki -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-copyright">
                        <span>&copy; <?= date('Y') ?> <?= get_bloginfo('name') ?>. Wszystkie prawa zastrzeżone.</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-credits">
                        <span>Realizacja: <a href="https://k3e.pl" target="_blank" rel="noopener">K3e.pl</a></span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Tło animowane -->
    <div class="footer-bg-animation">
        <div class="bg-element bg-element-1"></div>
        <div class="bg-element bg-element-2"></div>
        <div class="bg-element bg-element-3"></div>
    </div>
</footer>

<!-- Pływający przycisk "Do góry" -->
<button id="backToTop" class="back-to-top-floating" aria-label="Przewiń do góry">
    <i class="bi bi-arrow-up"></i>
</button>