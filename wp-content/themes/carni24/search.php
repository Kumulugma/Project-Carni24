<?php
/**
 * Template strony wyszukiwania
 * Plik: search.php
 * Autor: Carni24 Team
 */

get_header(); ?>

<main class="search-page">
    
    <!-- Header strony wyszukiwania -->
    <section class="search-header py-md-5">
        <div class="container-fluid p-md-5">
            <div class="row">
                <div class="col-12">
                    <div class="search-header-content text-center">
                        <h1 class="search-title">
                            <i class="bi bi-search me-3"></i>
                            Wyniki wyszukiwania
                        </h1>
                        
                        <?php if (have_posts()) : ?>
                            <p class="search-subtitle">
                                Znalezione wyniki dla: <strong>"<?= get_search_query() ?>"</strong>
                            </p>
                            <div class="search-stats">
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <?php
                                    global $wp_query;
                                    $found_posts = $wp_query->found_posts;
                                    echo $found_posts;
                                    if ($found_posts == 1) {
                                        echo ' wynik';
                                    } elseif ($found_posts % 10 >= 2 && $found_posts % 10 <= 4 && ($found_posts % 100 < 10 || $found_posts % 100 >= 20)) {
                                        echo ' wyniki';
                                    } else {
                                        echo ' wyników';
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php else : ?>
                            <p class="search-subtitle">
                                Brak wyników dla: <strong>"<?= get_search_query() ?>"</strong>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Zawartość wyników -->
    <section class="search-content py-5">
        <div class="container-fluid p-5">
            <div class="row">
                
                <!-- Główna kolumna z wynikami -->
                <div class="col-lg-8">
                    
                    <?php if (have_posts()) : ?>
                        
                        <!-- Lista wyników -->
                        <div class="search-results">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="search-result-card mb-4">
                                    <div class="row g-0">
                                        
                                        <!-- Obrazek -->
                                        <div class="col-md-3">
                                            <div class="search-result-image-container">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <img src="<?= esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')) ?>" 
                                                         alt="<?= esc_attr(get_the_title()) ?>" 
                                                         class="search-result-image">
                                                <?php else : ?>
                                                    <div class="search-result-image search-result-placeholder">
                                                        <i class="bi bi-file-text"></i>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Typ postu -->
                                                <div class="search-result-type">
                                                    <?php
                                                    $post_type = get_post_type();
                                                    switch ($post_type) {
                                                        case 'guides':
                                                            echo '<i class="bi bi-book me-1"></i>Poradnik';
                                                            break;
                                                        case 'species':
                                                            echo '<i class="bi bi-flower1 me-1"></i>Gatunek';
                                                            break;
                                                        default:
                                                            echo '<i class="bi bi-journal-text me-1"></i>Artykuł';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Treść -->
                                        <div class="col-md-9">
                                            <div class="search-result-content">
                                                
                                                <!-- Meta informacje -->
                                                <div class="search-result-meta mb-2">
                                                    <span class="search-result-date">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <?= get_the_date('d.m.Y') ?>
                                                    </span>
                                                    
                                                    <?php
                                                    $categories = get_the_category();
                                                    if ($categories && $post_type === 'post') :
                                                    ?>
                                                        <span class="search-result-category">
                                                            <i class="bi bi-tag me-1"></i>
                                                            <?= esc_html($categories[0]->name) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (function_exists('carni24_calculate_reading_time')) : ?>
                                                        <span class="search-result-reading-time">
                                                            <i class="bi bi-clock me-1"></i>
                                                            <?= carni24_calculate_reading_time(get_the_content()) ?> min
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Tytuł -->
                                                <h2 class="search-result-title">
                                                    <a href="<?= esc_url(get_permalink()) ?>">
                                                        <?php
                                                        $title = get_the_title();
                                                        $search_term = get_search_query();
                                                        if ($search_term) {
                                                            $title = str_ireplace($search_term, '<mark class="search-highlight">' . $search_term . '</mark>', $title);
                                                        }
                                                        echo $title;
                                                        ?>
                                                    </a>
                                                </h2>
                                                
                                                <!-- Excerpt z podświetleniem -->
                                                <div class="search-result-excerpt">
                                                    <?php
                                                    $excerpt = get_the_excerpt();
                                                    if (empty($excerpt)) {
                                                        $excerpt = wp_trim_words(get_the_content(), 30);
                                                    }
                                                    
                                                    // Podświetl szukaną frazę
                                                    if ($search_term) {
                                                        $excerpt = str_ireplace($search_term, '<mark class="search-highlight">' . $search_term . '</mark>', $excerpt);
                                                    }
                                                    echo $excerpt;
                                                    ?>
                                                </div>
                                                
                                                <!-- URL -->
                                                <div class="search-result-url">
                                                    <i class="bi bi-link-45deg me-1"></i>
                                                    <small class="text-muted"><?= esc_url(get_permalink()) ?></small>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        
                        <!-- Paginacja -->
                        <?php if ($wp_query->max_num_pages > 1) : ?>
                            <div class="search-pagination mt-5">
                                <?php
                                echo paginate_links(array(
                                    'total' => $wp_query->max_num_pages,
                                    'current' => max(1, get_query_var('paged')),
                                    'format' => '?s=' . urlencode(get_search_query()) . '&paged=%#%',
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
                        <?php endif; ?>
                        
                    <?php else : ?>
                        
                        <!-- Brak wyników -->
                        <div class="no-search-results text-center py-5">
                            <div class="no-results-icon mb-4">
                                <i class="bi bi-search display-1 text-muted"></i>
                            </div>
                            <h3 class="no-results-title">Nie znaleziono wyników</h3>
                            <p class="no-results-text text-muted mb-4">
                                Niestety, nie znaleźliśmy nic dla frazy "<strong><?= get_search_query() ?></strong>".
                                <br>Spróbuj użyć innych słów kluczowych lub sprawdź sugestie poniżej.
                            </p>
                            
                            <!-- Sugestie -->
                            <div class="search-suggestions">
                                <h4 class="suggestions-title">Może spróbujesz:</h4>
                                <ul class="suggestions-list">
                                    <li>Sprawdź pisownię słów kluczowych</li>
                                    <li>Użyj bardziej ogólnych terminów</li>
                                    <li>Spróbuj synonimów lub powiązanych słów</li>
                                    <li>Przeglądnij nasze kategorie poniżej</li>
                                </ul>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <aside class="search-sidebar">
                        
                        <!-- Nowe wyszukiwanie -->
                        <div class="widget-box mb-4">
                            <h5 class="widget-title">
                                <i class="bi bi-search me-2"></i>
                                Nowe wyszukiwanie
                            </h5>
                            <form class="search-form" method="get" action="<?= home_url('/') ?>">
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
                                Przeglądaj kategorie
                            </h5>
                            <ul class="category-list">
                                <?php
                                $categories = get_categories(array(
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                    'number' => 8,
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
                        
                        <!-- Ostatnio dodane -->
                        <div class="widget-box">
                            <h5 class="widget-title">
                                <i class="bi bi-clock-history me-2"></i>
                                Ostatnio dodane
                            </h5>
                            <?php
                            $recent_posts = new WP_Query(array(
                                'post_type' => array('post', 'guides', 'species'),
                                'posts_per_page' => 5,
                                'orderby' => 'date',
                                'order' => 'DESC'
                            ));
                            
                            if ($recent_posts->have_posts()) :
                            ?>
                                <div class="recent-posts">
                                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                        <article class="recent-post">
                                            <a href="<?= esc_url(get_permalink()) ?>">
                                                <div class="recent-post-type">
                                                    <?php
                                                    $post_type = get_post_type();
                                                    switch ($post_type) {
                                                        case 'guides':
                                                            echo '<i class="bi bi-book"></i>';
                                                            break;
                                                        case 'species':
                                                            echo '<i class="bi bi-flower1"></i>';
                                                            break;
                                                        default:
                                                            echo '<i class="bi bi-journal-text"></i>';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="recent-post-content">
                                                    <h6 class="recent-post-title">
                                                        <?= esc_html(wp_trim_words(get_the_title(), 8)) ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <?= get_the_date('d.m.Y') ?>
                                                    </small>
                                                </div>
                                            </a>
                                        </article>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </aside>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* ===== SEARCH PAGE STYLES ===== */

.search-page {
    background: #f8f9fa;
    min-height: 100vh;
}

/* Header */
.search-header {
    background: linear-gradient(135deg, #e8f5f0 0%, #f8fff9 100%);
    border-bottom: 1px solid #e9ecef;
}

.search-title {
    color: #268155;
    font-weight: 700;
    margin-bottom: 1rem;
}

.search-subtitle {
    font-size: 1.1rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.search-highlight {
    background: #fff3cd;
    padding: 0 4px;
    border-radius: 3px;
    font-weight: 600;
}

/* Results */
.search-result-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.search-result-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: #268155;
}

.search-result-image-container {
    position: relative;
    overflow: hidden;
}

.search-result-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.search-result-card:hover .search-result-image {
    transform: scale(1.1);
}

.search-result-placeholder {
    background: linear-gradient(135deg, #268155, #1a5f3f);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.search-result-type {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(38, 129, 85, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.search-result-content {
    padding: 1.5rem;
}

.search-result-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    font-size: 0.85rem;
    color: #9ca3af;
}

.search-result-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.search-result-title a {
    color: #1f2937;
    text-decoration: none;
    transition: color 0.3s ease;
}

.search-result-title a:hover,
.search-result-card:hover .search-result-title a {
    color: #268155;
}

.search-result-excerpt {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.search-result-url {
    border-top: 1px solid #e5e7eb;
    padding-top: 0.75rem;
}

/* No results */
.no-search-results {
    background: white;
    border-radius: 15px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.no-results-title {
    color: #1f2937;
    margin-bottom: 1rem;
}

.suggestions-title {
    color: #268155;
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.suggestions-list {
    list-style: none;
    padding: 0;
    text-align: left;
    display: inline-block;
}

.suggestions-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.suggestions-list li:last-child {
    border-bottom: none;
}

/* Pagination */
.search-pagination {
    display: flex;
    justify-content: center;
}

.search-pagination .page-numbers {
    display: inline-block;
    padding: 8px 16px;
    margin: 0 4px;
    color: #268155;
    text-decoration: none;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.search-pagination .page-numbers:hover,
.search-pagination .page-numbers.current {
    background: #268155;
    color: white;
    border-color: #268155;
}

/* Sidebar */
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

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 0.5rem;
}

.category-list a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.category-list a:hover {
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

.popular-posts,
.recent-posts {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.popular-post,
.recent-post {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.popular-post:last-child,
.recent-post:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.popular-post a,
.recent-post a {
    text-decoration: none;
    color: inherit;
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.popular-post-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}

.popular-post-title,
.recent-post-title {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.3;
    color: #1f2937;
}

.popular-post:hover .popular-post-title,
.recent-post:hover .recent-post-title {
    color: #268155;
}

.recent-post-type {
    color: #268155;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.recent-post-content {
    flex: 1;
}

/* Mobile responsive */
@media (max-width: 767.98px) {
    .search-result-card .row {
        flex-direction: column;
    }
    
    .search-result-image-container {
    }
    
    .search-result-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .search-result-title {
        font-size: 1.1rem;
    }
    
    .no-search-results {
        padding: 2rem 1rem;
    }
    
    .suggestions-list {
        width: 100%;
    }
}
</style>

<?php get_footer(); ?>