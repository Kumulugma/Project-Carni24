<?php
// wp-content/themes/carni24/front-page.php
// Finalna wersja z elegancką wyszukiwarką, poprawkami CTA i zmienionym sidebar

get_header(); ?>

<main>
    <!-- Istniejące elementy -->
    <?php get_template_part('template-parts/main-submenu'); ?>
    
    <!-- ELEGANCKA WYSZUKIWARKA AJAX -->
    <?php get_template_part('template-parts/homepage/ajax-search'); ?>
    
    <!-- HERO SLIDER SEKCJA -->
    <section class="hero-slider-section mb-5">
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
                        
                        $slide_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
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
                                                        <?= get_the_date('d.m.Y') ?>
                                                    </small>
                                                </div>
                                                <a href="<?= esc_url(get_permalink()) ?>" class="btn btn-hero-primary btn-lg px-4 py-2">
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
                
                <!-- Navigation -->
                <?php if ($slide_index > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Poprzedni</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Następny</span>
                </button>
                
                <div class="carousel-indicators">
                    <?php for ($i = 0; $i < $slide_index; $i++): ?>
                        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="<?= $i ?>" 
                                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?> 
                                aria-label="Slide <?= $i + 1 ?>"></button>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- DWUKOLUMNOWY LAYOUT Z WPISAMI -->
    <section class="posts-layout-section py-5">
        <div class="container-fluid">
            <div class="row">
                <!-- LEWA KOLUMNA - WPISY -->
                <div class="col-lg-8 col-md-7">
                    <div class="posts-main-column">
                        <div class="section-header mb-4">
                            <h2 class="section-title h1 mb-3">
                                Najnowsze wpisy
                            </h2>
                            <p class="section-subtitle text-muted">
                                Odkryj fascynujący świat roślin mięsożernych
                            </p>
                        </div>
                        
                        <div class="posts-grid">
                            <?php
                            // Pobierz najnowsze wpisy (wykluczając te ze slidera)
                            $posts_query = new WP_Query(array(
                                'post_type' => 'post',
                                'posts_per_page' => 8,
                                'post__not_in' => $slider_post_ids,
                                'orderby' => 'date',
                                'order' => 'DESC'
                            ));
                            
                            if ($posts_query->have_posts()) :
                                while ($posts_query->have_posts()) : $posts_query->the_post();
                                    $post_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                    if (!$post_image) {
                                        $post_image = get_template_directory_uri() . '/assets/images/default-post.jpg';
                                    }
                                    
                                    $post_excerpt = get_the_excerpt();
                                    if (empty($post_excerpt)) {
                                        $post_excerpt = wp_trim_words(get_the_content(), 15);
                                    }
                            ?>
                                    <article class="post-card mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <div class="post-image-container">
                                                        <img src="<?= esc_url($post_image) ?>" 
                                                             class="card-img post-image" 
                                                             alt="<?= esc_attr(get_the_title()) ?>">
                                                        
                                                        <div class="post-date-badge">
                                                            <span class="day"><?= get_the_date('d') ?></span>
                                                            <span class="month"><?= get_the_date('M') ?></span>
                                                            <span class="year"><?= get_the_date('Y') ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body d-flex flex-column h-100">
                                                        <div class="post-categories mb-2">
                                                            <?php
                                                            $categories = get_the_category();
                                                            if ($categories) {
                                                                echo '<span class="badge bg-carni-green">' . esc_html($categories[0]->name) . '</span>';
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <h3 class="card-title h5 mb-3">
                                                            <a href="<?= esc_url(get_permalink()) ?>" class="text-decoration-none">
                                                                <?php the_title(); ?>
                                                            </a>
                                                        </h3>
                                                        
                                                        <p class="card-text text-muted flex-grow-1">
                                                            <?= esc_html(wp_trim_words($post_excerpt, 20)) ?>
                                                        </p>
                                                        
                                                        <div class="post-meta mt-auto">
                                                            <div class="d-flex justify-content-end align-items-center">
                                                                <a href="<?= esc_url(get_permalink()) ?>" class="btn btn-outline-carni-green btn-sm">
                                                                    Czytaj więcej
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Przycisk "Zobacz więcej" -->
                        <div class="text-center mt-4">
                            <a href="<?= get_permalink(get_option('page_for_posts')) ?>" 
                               class="btn btn-carni-green btn-lg px-5">
                                Zobacz wszystkie wpisy
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- PRAWA KOLUMNA - SIDEBAR -->
                <div class="col-lg-4 col-md-5">
                    <div class="homepage-sidebar">
                        
                        <!-- Widget z kategoriami - PIERWSZE -->
                        <div class="widget-box mb-4">
                            <h3 class="widget-title">Kategorie</h3>
                            <div class="categories-list">
                                <?php
                                $categories = get_categories(array(
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                    'number' => 8,
                                    'hide_empty' => true
                                ));
                                
                                if ($categories) :
                                    echo '<ul class="categories-simple-list">';
                                    foreach ($categories as $category) :
                                ?>
                                        <li class="category-item">
                                            <a href="<?= esc_url(get_category_link($category->term_id)) ?>" class="category-link">
                                                <span class="category-name"><?= esc_html($category->name) ?></span>
                                                <span class="category-count badge bg-secondary"><?= $category->count ?></span>
                                            </a>
                                        </li>
                                <?php 
                                    endforeach; 
                                    echo '</ul>';
                                endif; 
                                ?>
                            </div>
                        </div>
                        
                        <!-- Widget z listą gatunków (bez obrazków) - DRUGIE -->
                        <?php 
                        $species_count = wp_count_posts('species');
                        if ($species_count && $species_count->publish > 0): 
                        ?>
                        <div class="widget-box mb-4">
                            <h3 class="widget-title">Gatunki roślin</h3>
                            <div class="species-list">
                                <?php
                                $species_query = new WP_Query(array(
                                    'post_type' => 'species',
                                    'posts_per_page' => 8,
                                    'post_status' => 'publish',
                                    'orderby' => 'date',
                                    'order' => 'DESC'
                                ));
                                
                                if ($species_query->have_posts()) :
                                    echo '<ul class="species-simple-list">';
                                    while ($species_query->have_posts()) : $species_query->the_post();
                                ?>
                                        <li class="species-item">
                                            <a href="<?= esc_url(get_permalink()) ?>" class="species-link">
                                                <span class="species-name"><?php the_title(); ?></span>
                                                <small class="species-date"><?= get_the_date('d.m.Y') ?></small>
                                            </a>
                                        </li>
                                <?php 
                                    endwhile; 
                                    echo '</ul>';
                                    wp_reset_postdata();
                                endif; 
                                ?>
                                
                                <div class="widget-footer text-center mt-4">
                                    <?php 
                                    $total_species = $species_count->publish;
                                    // Prosta odmiana
                                    if ($total_species == 1) {
                                        $species_text = $total_species . ' spisany gatunek';
                                    } elseif ($total_species >= 2 && $total_species <= 4) {
                                        $species_text = $total_species . ' spisane gatunki';
                                    } else {
                                        $species_text = $total_species . ' spisanych gatunków';
                                    }
                                    ?>
                                    <a href="<?= get_post_type_archive_link('species') ?>" class="btn btn-outline-success btn-sm">
                                        Zobacz wszystkie (<?= $species_text ?>)
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- KOLOROWA SEKCJA CTA - POPRAWIONA -->
    <section class="cta-section py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="cta-content text-center text-white">
                        <div class="row align-items-center">
                            <div class="col-lg-8 offset-lg-2">
                                <h2 class="cta-title display-5 fw-bold mb-4">
                                    Fascynujący świat roślin mięsożernych
                                </h2>
                                <p class="cta-description lead mb-4">
                                    Odkryj niezwykłe adaptacje natury! Rośliny mięsożerne to jedne z najbardziej 
                                    intrygujących organizmów na Ziemi. Dowiedz się jak je uprawiać, pielęgnować 
                                    i podziwiać w swoim domu.
                                </p>
                                <div class="cta-stats row text-center mb-4">
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <h3 class="stat-number display-6 fw-bold">
                                                <?php 
                                                $species_count = wp_count_posts('species');
                                                echo $species_count ? $species_count->publish : '0';
                                                ?>
                                            </h3>
                                            <p class="stat-label">Gatunków opisanych</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <h3 class="stat-number display-6 fw-bold">
                                                <?php 
                                                $posts_count = wp_count_posts('post');
                                                echo $posts_count ? $posts_count->publish : '0';
                                                ?>
                                            </h3>
                                            <p class="stat-label">Artykułów opublikowanych</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <h3 class="stat-number display-6 fw-bold">
                                                <?php 
                                                // Sprawdź czy funkcja gallery_count istnieje
                                                if (function_exists('gallery_count')) {
                                                    echo gallery_count();
                                                } else {
                                                    // Fallback - policz attachmenty
                                                    $images = get_posts(array(
                                                        'post_type' => 'attachment',
                                                        'post_mime_type' => 'image',
                                                        'posts_per_page' => -1,
                                                        'post_status' => 'inherit'
                                                    ));
                                                    echo count($images);
                                                }
                                                ?>
                                            </h3>
                                            <p class="stat-label">Zdjęć w galeriach</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="cta-buttons">
                                    <a href="<?= get_post_type_archive_link('species') ?>" class="btn btn-cta-light btn-lg me-3 mb-2">
                                        Przeglądaj gatunki
                                    </a>
                                    <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="btn btn-cta-outline btn-lg mb-2">
                                        Czytaj przewodniki
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Zachowaj manifest jeśli chcesz -->
    <?php get_template_part('template-parts/homepage/manifest'); ?>
</main>

<style>
/* Carni Green Colors */
:root {
    --carni-green: #2d5016;
    --carni-green-light: #3d6b20;
    --carni-green-dark: #1e3810;
}

.bg-carni-green {
    background-color: var(--carni-green) !important;
}

.btn-carni-green {
    background-color: var(--carni-green);
    border-color: var(--carni-green);
    color: white;
}

.btn-carni-green:hover,
.btn-carni-green:focus {
    background-color: var(--carni-green-light);
    border-color: var(--carni-green-light);
    color: white;
}

.btn-outline-carni-green {
    border-color: var(--carni-green);
    color: var(--carni-green);
}

.btn-outline-carni-green:hover,
.btn-outline-carni-green:focus {
    background-color: var(--carni-green);
    border-color: var(--carni-green);
    color: white;
}

.btn-hero-primary {
    background-color: var(--carni-green);
    border-color: var(--carni-green);
    color: white;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-hero-primary:hover {
    background-color: var(--carni-green-light);
    border-color: var(--carni-green-light);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(45, 80, 22, 0.3);
}

/* CTA Section Improved Buttons */
.btn-cta-light {
    background-color: rgba(255, 255, 255, 0.95);
    border: 2px solid rgba(255, 255, 255, 0.95);
    color: var(--carni-green);
    font-weight: 700;
    padding: 12px 30px;
    border-radius: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-cta-light:hover {
    background-color: white;
    border-color: white;
    color: var(--carni-green-dark);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.btn-cta-outline {
    background-color: transparent;
    border: 2px solid rgba(255, 255, 255, 0.8);
    color: white;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-cta-outline:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: white;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Hero Slider Styles */
.hero-slider-section {
    margin-bottom: 2rem;
}

.hero-slide {
    min-height: 70vh;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    position: relative;
}

.hero-content-row {
    min-height: 60vh;
}

.hero-content {
    animation: slideInUp 1s ease-out;
}

.hero-category {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #ffc107;
    margin-bottom: 1rem;
    display: inline-block;
}

.hero-title {
    line-height: 1.2;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.hero-excerpt {
    font-size: 1.1rem;
    line-height: 1.6;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

/* Carousel Indicators Styling */
.carousel-indicators {
    bottom: 30px;
}

.carousel-indicators button {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 3px solid rgba(255, 255, 255, 0.8);
    background: transparent;
    opacity: 0.6;
    margin: 0 8px;
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    opacity: 1;
    background: white;
    border-color: white;
    transform: scale(1.2);
}

.carousel-indicators button:hover {
    opacity: 0.9;
    transform: scale(1.1);
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    background: rgba(45, 80, 22, 0.7);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    top: 50%;
    transform: translateY(-50%);
    border: 3px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background: rgba(45, 80, 22, 0.9);
    border-color: rgba(255, 255, 255, 0.6);
}

.carousel-control-prev {
    left: 30px;
}

.carousel-control-next {
    right: 30px;
}

/* Posts Layout */
.posts-layout-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.section-title {
    color: var(--carni-green);
    font-weight: 700;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(45deg, var(--carni-green), var(--carni-green-light));
    border-radius: 2px;
}

/* Post Cards */
.post-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.post-card:hover {
    transform: translateY(-3px);
}

.post-card .card {
    border: none;
    overflow: hidden;
    border-radius: 12px;
}

.post-card:hover .card {
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.post-image-container {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.post-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.post-card:hover .post-image {
    transform: scale(1.05);
}

.post-date-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255,255,255,0.95);
    padding: 8px 12px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    min-width: 60px;
}

.post-date-badge .day {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    line-height: 1;
}

.post-date-badge .month {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    line-height: 1;
    margin: 2px 0;
}

.post-date-badge .year {
    display: block;
    font-size: 0.65rem;
    font-weight: 500;
    color: #888;
    line-height: 1;
}

/* Sidebar Widgets */
.homepage-sidebar {
    position: sticky;
    top: 20px;
}

.widget-box {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
}

.widget-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

/* Species List */
.species-simple-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.species-item {
    margin-bottom: 0.8rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid #f0f0f0;
}

.species-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.species-link {
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: color 0.3s ease;
}

.species-link:hover {
    color: var(--carni-green);
}

.species-name {
    font-weight: 600;
    color: #333;
}

.species-date {
    color: #666;
    font-size: 0.8rem;
}

/* Categories */
.categories-simple-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-item {
    margin-bottom: 0.6rem;
}

.category-link {
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.4rem 0;
    transition: color 0.3s ease;
}

.category-link:hover {
    color: var(--carni-green);
}

.category-name {
    color: #333;
    font-weight: 500;
}

.category-count {
    font-size: 0.75rem;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, var(--carni-green) 0%, var(--carni-green-dark) 100%);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1.5" fill="rgba(255,255,255,0.03)"/><circle cx="50" cy="10" r="0.8" fill="rgba(255,255,255,0.04)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-title {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.cta-description {
    opacity: 0.95;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.stat-item {
    margin-bottom: 1rem;
}

.stat-number {
    color: #ffc107;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
}

.cta-buttons .btn {
    transition: all 0.3s ease;
}

.cta-buttons .btn:hover {
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-slide {
        min-height: 50vh;
    }
    
    .hero-content-row {
        min-height: 40vh;
    }
    
    .hero-title {
        font-size: 2rem !important;
    }
    
    .hero-excerpt {
        font-size: 1rem;
    }
    
    .post-image-container {
        height: 150px;
    }
    
    .homepage-sidebar {
        position: static;
        margin-top: 2rem;
    }
    
    .posts-layout-section .row {
        flex-direction: column-reverse;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 50px;
        height: 50px;
    }
    
    .carousel-control-prev {
        left: 15px;
    }
    
    .carousel-control-next {
        right: 15px;
    }
    
    .carousel-indicators {
        bottom: 20px;
    }
    
    .carousel-indicators button {
        width: 12px;
        height: 12px;
        margin: 0 5px;
    }
    
    .cta-stats .col-md-4 {
        margin-bottom: 2rem;
    }
    
    .cta-buttons .btn {
        display: block;
        width: 100%;
        margin: 0 0 1rem 0 !important;
    }
}

@media (max-width: 576px) {
    .hero-slide {
        min-height: 40vh;
    }
    
    .hero-content {
        text-align: center;
    }
    
    .hero-title {
        font-size: 1.75rem !important;
    }
    
    .post-card .row {
        flex-direction: column;
    }
    
    .post-image-container {
        height: 180px;
    }
    
    .post-date-badge {
        top: 8px;
        right: 8px;
        padding: 6px 10px;
        min-width: 50px;
    }
    
    .post-date-badge .day {
        font-size: 1rem;
    }
    
    .post-date-badge .month {
        font-size: 0.65rem;
    }
    
    .post-date-badge .year {
        font-size: 0.6rem;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
    }
    
    .carousel-control-prev {
        left: 10px;
    }
    
    .carousel-control-next {
        right: 10px;
    }
    
    .carousel-indicators {
        bottom: 15px;
    }
    
    .carousel-indicators button {
        width: 10px;
        height: 10px;
        margin: 0 3px;
    }
    
    .cta-title {
        font-size: 2rem !important;
    }
    
    .stat-number {
        font-size: 2.5rem !important;
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Additional animations */
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.widget-box {
    animation: fadeInScale 0.6s ease-out;
}

.post-card {
    animation: fadeInUp 0.6s ease-out;
}

.post-card:nth-child(2) { animation-delay: 0.1s; }
.post-card:nth-child(3) { animation-delay: 0.2s; }
.post-card:nth-child(4) { animation-delay: 0.3s; }
.post-card:nth-child(5) { animation-delay: 0.4s; }
.post-card:nth-child(6) { animation-delay: 0.5s; }
.post-card:nth-child(7) { animation-delay: 0.6s; }
.post-card:nth-child(8) { animation-delay: 0.7s; }

/* Hover effects for CTA section */
.cta-section .stat-item:hover .stat-number {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

/* Focus states for accessibility */
.btn:focus,
.carousel-control-prev:focus,
.carousel-control-next:focus,
.carousel-indicators button:focus {
    outline: 3px solid rgba(255, 193, 7, 0.5);
    outline-offset: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced carousel functionality
    const carousel = document.getElementById('heroSlider');
    if (carousel) {
        // Pause on hover
        carousel.addEventListener('mouseenter', function() {
            if (window.bootstrap && window.bootstrap.Carousel) {
                const carouselInstance = window.bootstrap.Carousel.getInstance(carousel);
                if (carouselInstance) {
                    carouselInstance.pause();
                }
            }
        });
        
        // Resume on mouse leave
        carousel.addEventListener('mouseleave', function() {
            if (window.bootstrap && window.bootstrap.Carousel) {
                const carouselInstance = window.bootstrap.Carousel.getInstance(carousel);
                if (carouselInstance) {
                    carouselInstance.cycle();
                }
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                const prevBtn = carousel.querySelector('.carousel-control-prev');
                if (prevBtn) prevBtn.click();
            } else if (e.key === 'ArrowRight') {
                const nextBtn = carousel.querySelector('.carousel-control-next');
                if (nextBtn) nextBtn.click();
            }
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Enhanced hover effects for post cards
    const postCards = document.querySelectorAll('.post-card');
    
    postCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            postCards.forEach(otherCard => {
                if (otherCard !== card) {
                    otherCard.style.opacity = '0.8';
                }
            });
        });
        
        card.addEventListener('mouseleave', function() {
            postCards.forEach(otherCard => {
                otherCard.style.opacity = '1';
            });
        });
    });
    
    // Animate statistics on scroll
    const statNumbers = document.querySelectorAll('.stat-number');
    if ('IntersectionObserver' in window && statNumbers.length > 0) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const number = entry.target;
                    const finalValue = parseInt(number.textContent);
                    animateNumber(number, 0, finalValue, 2000);
                    statsObserver.unobserve(number);
                }
            });
        });
        
        statNumbers.forEach(stat => statsObserver.observe(stat));
    }
    
    function animateNumber(element, start, end, duration) {
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(start + (end - start) * easeOutQuart(progress));
            element.textContent = current;
            
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = end;
            }
        }
        
        requestAnimationFrame(update);
    }
    
    function easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
    }
});
</script>

<?php get_footer(); ?>