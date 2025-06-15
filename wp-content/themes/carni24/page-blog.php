<?php
/**
 * Template Name: Lista wpisów
 * Plik: page-blog.php
 * Autor: Carni24 Team
 * Szablon strony wyświetlającej wszystkie wpisy z paginacją
 */

get_header(); ?>

<main class="blog-page">
    
    <!-- Header strony -->
    <section class="blog-header py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="blog-title display-4 fw-bold mb-3">
                        <i class="bi bi-journal-text me-3 text-success"></i>
                        Wszystkie wpisy
                    </h1>
                    <p class="blog-subtitle lead text-muted">
                        Poznaj fascynujący świat roślin mięsożernych - artykuły, poradniki i ciekawostki
                    </p>
                    <div class="blog-stats">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <?php
                            $total_posts = wp_count_posts()->publish;
                            echo $total_posts . ' ' . _n('artykuł', 'artykuły', $total_posts, 'carni24');
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Lista wpisów -->
    <section class="blog-content p-5">
        <div class="container-fluid">
            <div class="row">
                
                <!-- Główna kolumna z wpisami -->
                <div class="col-lg-8">
                    
                    <!-- Filtry -->
                    <div class="blog-filters mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="blog-view-toggle">
                                    <span class="text-muted me-3">Widok:</span>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-success btn-sm active" data-view="grid">
                                            <i class="bi bi-grid-3x3-gap"></i> Siatka
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm" data-view="list">
                                            <i class="bi bi-list"></i> Lista
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="blog-sort text-end">
                                    <select class="form-select form-select-sm d-inline-block w-auto" id="blogSort">
                                        <option value="date">Najnowsze</option>
                                        <option value="title">Alfabetycznie</option>
                                        <option value="popular">Najpopularniejsze</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grid wpisów -->
                    <div class="blog-posts-grid" id="blogPostsGrid">
                        <?php
                        // Paginacja
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        
                        // Query dla wszystkich wpisów
                        $blog_query = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 12,
                            'post_status' => 'publish',
                            'paged' => $paged,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                        
                        if ($blog_query->have_posts()) :
                            echo '<div class="row g-4">';
                            
                            while ($blog_query->have_posts()) : $blog_query->the_post();
                                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'blog_thumb');
                                if (!$post_image) {
                                    $post_image = get_template_directory_uri() . '/assets/images/default-post.jpg';
                                }
                                $categories = get_the_category();
                                $reading_time = function_exists('carni24_calculate_reading_time') ? 
                                    carni24_calculate_reading_time(get_the_content()) : 
                                    max(1, ceil(str_word_count(strip_tags(get_the_content())) / 200));
                        ?>
                            <div class="col-md-6 col-lg-4">
                                <article class="blog-post-card h-100">
                                    <a href="<?= esc_url(get_permalink()) ?>" class="blog-post-link">
                                        
                                        <!-- Obrazek -->
                                        <div class="blog-post-image-container">
                                            <img src="<?= esc_url($post_image) ?>" 
                                                 alt="<?= esc_attr(get_the_title()) ?>" 
                                                 class="blog-post-image">
                                            
                                            <!-- Badge z datą -->
                                            <div class="blog-post-date-badge">
                                                <span class="day"><?= get_the_date('d') ?></span>
                                                <span class="month"><?= get_the_date('M') ?></span>
                                                <span class="year"><?= get_the_date('Y') ?></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Treść -->
                                        <div class="blog-post-content">
                                            <?php if ($categories): ?>
                                                <div class="blog-post-category">
                                                    <?= esc_html($categories[0]->name) ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <h3 class="blog-post-title">
                                                <?= esc_html(get_the_title()) ?>
                                            </h3>
                                            
                                            <div class="blog-post-excerpt">
                                                <?= wp_trim_words(get_the_excerpt(), 20, '...') ?>
                                            </div>
                                            
                                            <div class="blog-post-meta">
                                                <div class="blog-post-reading-time">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= $reading_time ?> min czytania
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </div>
                        <?php
                            endwhile;
                            echo '</div>';
                        else :
                        ?>
                            <div class="no-posts text-center py-5">
                                <i class="bi bi-journal-x display-1 text-muted mb-3"></i>
                                <h3 class="text-muted">Brak wpisów</h3>
                                <p class="text-muted">Nie znaleziono żadnych wpisów do wyświetlenia.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Paginacja -->
                    <?php if ($blog_query->max_num_pages > 1): ?>
                        <div class="blog-pagination mt-5">
                            <?php
                            echo paginate_links(array(
                                'total' => $blog_query->max_num_pages,
                                'current' => $paged,
                                'format' => '?paged=%#%',
                                'show_all' => false,
                                'end_size' => 1,
                                'mid_size' => 2,
                                'prev_next' => true,
                                'prev_text' => '<i class="bi bi-chevron-left"></i> Poprzednia',
                                'next_text' => 'Następna <i class="bi bi-chevron-right"></i>',
                                'add_args' => false,
                                'add_fragment' => '',
                                'type' => 'list'
                            ));
                            ?>
                        </div>
                    <?php endif; wp_reset_postdata(); ?>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="blog-sidebar">
                        
                        <!-- Wyszukiwanie -->
                        <div class="widget-box mb-4">
                            <h5 class="widget-title">
                                <i class="bi bi-search me-2"></i>
                                Wyszukaj
                            </h5>
                            <form class="blog-search-form" method="get" action="<?= home_url('/') ?>">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="s" 
                                           placeholder="Wpisz szukaną frazę..." 
                                           value="<?= get_search_query() ?>">
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Kategorie -->
                        <div class="widget-box mb-4">
                            <h5 class="widget-title">
                                <i class="bi bi-tags me-2"></i>
                                Kategorie
                            </h5>
                            <ul class="category-list">
                                <?php
                                $categories = get_categories(array(
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                    'number' => 10,
                                    'hide_empty' => true
                                ));
                                
                                if ($categories) :
                                    foreach ($categories as $category) :
                                ?>
                                    <li>
                                        <a href="<?= esc_url(get_category_link($category->term_id)) ?>">
                                            <?= esc_html($category->name) ?>
                                            <span class="post-count"><?= $category->count ?></span>
                                        </a>
                                    </li>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </ul>
                        </div>
                        
                        
                    </aside>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Style dla strony blog */
.blog-page {
    background: #f8f9fa;
    min-height: 100vh;
}

.blog-header {
    background: linear-gradient(135deg, #e9f7ef 0%, #f8fff9 100%);
    border-bottom: 1px solid #e9ecef;
}

.blog-title {
    color: #268155;
    margin-bottom: 1rem;
}

.blog-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
}

.blog-view-toggle .btn {
    border-color: #268155;
    color: #268155;
}

.blog-view-toggle .btn.active {
    background: #268155;
    color: white;
}

.blog-posts-grid .blog-post-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.blog-post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: #268155;
    text-decoration: none;
    color: inherit;
}

.blog-post-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-post-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-post-card:hover .blog-post-image {
    transform: scale(1.1);
}

.blog-post-date-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(38, 129, 85, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
    min-width: 60px;
    backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    align-items: center;
    line-height: 1;
}

.blog-post-date-badge .day {
    font-size: 1.2rem;
    font-weight: 700;
}

.blog-post-date-badge .month {
    font-size: 0.7rem;
    text-transform: uppercase;
    opacity: 0.9;
    margin: 2px 0;
}

.blog-post-date-badge .year {
    font-size: 0.65rem;
    opacity: 0.8;
    border-top: 1px solid rgba(255, 255, 255, 0.3);
    padding-top: 2px;
    margin-top: 2px;
}

.blog-post-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: calc(100% - 200px);
}

.blog-post-category {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #268155;
    margin-bottom: 0.5rem;
}

.blog-post-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.blog-post-excerpt {
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.6;
    flex: 1;
    margin-bottom: 1rem;
}

.blog-post-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: #9ca3af;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.blog-pagination {
    display: flex;
    justify-content: center;
}

.blog-pagination .page-numbers {
    display: flex;
    padding: 8px 16px;
    margin: 0 4px;
    color: #268155;
    text-decoration: none;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.blog-pagination .page-numbers:hover,
.blog-pagination .page-numbers.current {
    background: #268155;
    color: white;
    border-color: #268155;
}

.widget-box {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.widget-title {
    color: #1f2937;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.widget-title i {
    color: #268155;
}

.category-list,
.archive-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li,
.archive-list li {
    margin-bottom: 0.5rem;
}

.category-list a,
.archive-list a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.category-list a:hover,
.archive-list a:hover {
    background: #f3f4f6;
    color: #268155;
    transform: translateX(5px);
}

.post-count {
    background: #f3f4f6;
    color: #9ca3af;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.popular-posts {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.popular-post {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.popular-post:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.popular-post a {
    text-decoration: none;
    color: inherit;
}

.popular-post-thumb {
    width: 100%;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.popular-post-title {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.3;
    color: #1f2937;
}

.popular-post:hover .popular-post-title {
    color: #268155;
}
</style>

<?php get_footer(); ?>