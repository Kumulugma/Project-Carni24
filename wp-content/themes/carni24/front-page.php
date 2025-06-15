<?php
/**
 * Template strony głównej - Carni24
 * Plik: front-page.php
 * Autor: Carni24 Team
 * Wersja: 3.0.0 - NAPRAWIONA
 */

get_header(); ?>

<main>
    
    <!-- HERO SLIDER SEKCJA -->
    <section class="hero-slider-section">
        <div class="container-fluid p-0">
            <div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner">
                    <?php
                    // Pobierz najnowsze wpisy dla slidera
                    $slider_query = new WP_Query(array(
                        'post_type' => 'post',
                        'posts_per_page' => 5,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    $slide_index = 0;
                    $slider_post_ids = array();
                    
                    while ($slider_query->have_posts()) : $slider_query->the_post();
                        $slider_post_ids[] = get_the_ID();
                        $slide_index++;
                        
                        $slide_image = get_the_post_thumbnail_url(get_the_ID(), 'homepage_slider');
                        if (!$slide_image) {
                            $slide_image = get_template_directory_uri() . '/assets/images/default-hero.jpg';
                        }
                        
                        $excerpt = get_the_excerpt();
                        if (empty($excerpt)) {
                            $excerpt = wp_trim_words(get_the_content(), 20);
                        }
                        
                        $slide_active = $slide_index === 1 ? 'active' : '';
                    ?>
                        <div class="carousel-item <?= $slide_active ?>">
                            <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('<?= esc_url($slide_image) ?>')">
                                <div class="container">
                                    <div class="row align-items-center hero-content-row">
                                        <div class="col-lg-8 col-xl-7">
                                            <div class="hero-content text-white">
                                                <span class="hero-category">
                                                    <?php 
                                                    $categories = get_the_category();
                                                    if ($categories) {
                                                        echo esc_html($categories[0]->name);
                                                    }
                                                    ?>
                                                </span>
                                                <h1 class="hero-title display-4 fw-bold mb-3">
                                                    <?php the_title(); ?>
                                                </h1>
                                                <p class="hero-excerpt lead mb-4">
                                                    <?= esc_html(wp_trim_words($excerpt, 25)) ?>
                                                </p>
                                                <div class="hero-meta mb-4">
                                                    <small class="text-light">
                                                        <i class="bi bi-calendar3 me-2"></i>
                                                        <?= get_the_date('d.m.Y') ?>
                                                    </small>
                                                </div>
                                                <a href="<?= esc_url(get_permalink()) ?>" class="btn btn-hero-primary btn-lg">
                                                    <i class="bi bi-book-open me-2"></i>
                                                    Czytaj artykuł
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                
                <!-- Navigation Controls -->
                <?php if ($slide_index > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Poprzedni</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Następny</span>
                </button>
                
                <!-- Indicators -->
                <div class="carousel-indicators">
                    <?php for ($i = 0; $i < $slide_index; $i++): ?>
                        <button type="button" 
                                data-bs-target="#heroSlider" 
                                data-bs-slide-to="<?= $i ?>" 
                                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                                aria-label="Slide <?= $i + 1 ?>">
                        </button>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- LATEST POSTS SECTION -->
    <div class="posts-layout-section p-5">
        <div class="container-fluid">
            <div class="row">
                
                <!-- POSTS COLUMN -->
                <div class="col-lg-8">
                    <!-- Posts Header -->
                    <div class="posts-header mb-5">
                        <h2 class="posts-title">
                            <i class="bi bi-newspaper me-3"></i>
                            Najnowsze wpisy
                        </h2>
                        <p class="posts-subtitle">Odkryj fascynujący świat roślin mięsożernych</p>
                    </div>
                    
                    <!-- Posts Grid -->
                    <div class="posts-grid">
                        <?php
                        // Query for latest posts (exclude slider posts)
                        $posts_query = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 6,
                            'post__not_in' => $slider_post_ids,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($posts_query->have_posts()) :
                            while ($posts_query->have_posts()) : $posts_query->the_post();
                                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'homepage_card');
                                if (!$post_image) {
                                    $post_image = get_template_directory_uri() . '/assets/images/default-post.jpg';
                                }
                                $categories = get_the_category();
                                $reading_time = carni24_calculate_reading_time(get_the_content());
                        ?>
                            <article class="post-card mb-4">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <div class="post-image-container">
                                            <img src="<?= esc_url($post_image) ?>" 
                                                 alt="<?= esc_attr(get_the_title()) ?>" 
                                                 class="post-image">
                                            <div class="post-date-badge">
                                                <span class="day"><?= get_the_date('d') ?></span>
                                                <span class="month"><?= get_the_date('M') ?></span>
                                                <span class="year"><?= get_the_date('Y') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 px-3">
                                        <div class="post-content">
                                            <?php if ($categories): ?>
                                                <div class="post-category">
                                                    <?= esc_html($categories[0]->name) ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <h3 class="post-title">
                                                <a href="<?= esc_url(get_permalink()) ?>">
                                                    <?php the_title() ?>
                                                </a>
                                            </h3>
                                            
                                            <div class="post-excerpt">
                                                <?= wp_trim_words(get_the_excerpt(), 25, '...') ?>
                                            </div>
                                            
                                            <div class="post-meta">
                                                <span class="post-reading-time">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= $reading_time ?> min czytania
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php
                            endwhile;
                        else :
                        ?>
                            <div class="no-posts">
                                <p>Brak wpisów do wyświetlenia.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Zobacz więcej wpisów (zamiast paginacji) -->
                    <?php if ($posts_query->found_posts > 6): ?>
                        <div class="posts-see-more text-center mt-5">
                            <?php
                            // Sprawdź czy istnieje strona z szablonem "Lista wpisów"
                            $blog_page = get_pages(array(
                                'meta_key' => '_wp_page_template',
                                'meta_value' => 'page-blog.php',
                                'number' => 1
                            ));
                            
                            if ($blog_page) {
                                $blog_url = get_permalink($blog_page[0]->ID);
                            } else {
                                // Fallback - strona z wpisami WordPress lub /blog/
                                $blog_url = get_permalink(get_option('page_for_posts')) ?: home_url('/blog/');
                            }
                            ?>
                            <a href="<?= esc_url($blog_url) ?>" 
                               class="btn btn-success btn-lg px-5 py-3">
                                <i class="bi bi-arrow-right me-2"></i>
                                Zobacz wszystkie wpisy
                                <small class="d-block mt-1 text-white opacity-75">
                                    Dostępnych jest <?= $posts_query->found_posts ?> artykułów
                                </small>
                            </a>
                        </div>
                    <?php endif; wp_reset_postdata(); ?>
                </div>
                
                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    <aside class="homepage-sidebar">
                        
                        <!-- Categories Widget -->
                        <div class="widget-box mb-4">
                            <h4 class="widget-title">
                                <i class="bi bi-tags me-2"></i>
                                Kategorie
                            </h4>
                            <ul class="category-list">
                                <?php
                                $categories = get_categories(array('number' => 8, 'orderby' => 'count', 'order' => 'DESC'));
                                foreach ($categories as $category) :
                                ?>
                                    <li>
                                        <a href="<?= esc_url(get_category_link($category->term_id)) ?>">
                                            <?= esc_html($category->name) ?>
                                            <span class="post-count"><?= $category->count ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        
                        
                    </aside>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA SECTION -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h2 class="cta-title display-5 fw-bold mb-3">
                        Odkryj fascynujący świat roślin mięsożernych!
                    </h2>
                    <p class="cta-subtitle lead mb-5">
                        Poznaj sekrety uprawy, dziel się doświadczeniami i odkrywaj niezwykłe gatunki roślin drapieżnych.
                    </p>
                </div>
            </div>
            
            <!-- Statystyki -->
            <div class="row cta-stats text-center">
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number display-4 fw-bold">
                            <?php
                            // Prawdziwa liczba gatunków (species CPT) lub kategorii
                            $species_count = wp_count_posts('species');
                            if ($species_count && isset($species_count->publish) && $species_count->publish > 0) {
                                echo $species_count->publish;
                            } else {
                                // Fallback - liczba kategorii wpisów
                                $categories_count = wp_count_terms('category', array('hide_empty' => true));
                                echo $categories_count > 0 ? $categories_count : '25';
                            }
                            ?>+
                        </div>
                        <div class="stat-label">Gatunków opisanych</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number display-4 fw-bold">
                            <?php
                            // Prawdziwa liczba zdjęć w bibliotece mediów
                            $media_count = wp_count_posts('attachment');
                            if ($media_count && isset($media_count->inherit) && $media_count->inherit > 0) {
                                echo $media_count->inherit;
                            } else {
                                echo '150';
                            }
                            ?>+
                        </div>
                        <div class="stat-label">Zdjęć w galerii</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <div class="stat-number display-4 fw-bold">
                            <?php
                            // Prawdziwa liczba poradników (guides CPT)
                            $guides_count = wp_count_posts('guides');
                            if ($guides_count && isset($guides_count->publish) && $guides_count->publish > 0) {
                                echo $guides_count->publish;
                            } else {
                                echo 0;
                            }
                            ?>+
                        </div>
                        <div class="stat-label">Poradników uprawy</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
// Include manifest section
get_template_part('template-parts/homepage/manifest'); 
?>

<?php get_footer(); ?>