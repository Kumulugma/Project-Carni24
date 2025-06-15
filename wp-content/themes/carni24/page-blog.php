<?php
/**
 * Template Name: Lista wpisów - NAPRAWIONY I DOPRACOWANY
 * Plik: page-blog.php
 * Autor: Carni24 Team
 * Szablon strony wyświetlającej wszystkie wpisy z paginacją
 */

get_header();

// Helper function dla URL - POPRAWIONA!
function get_sort_url($new_orderby = '') {
    $current_url = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($current_url);
    $base_url = $parsed_url['path'];
    
    // Usuń istniejące parametry orderby i paged
    $query_params = array();
    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query_params);
        unset($query_params['orderby']);
        unset($query_params['paged']);
    }
    
    // Dodaj nowy orderby jeśli nie jest pusty i różny od 'date'
    if ($new_orderby && $new_orderby !== 'date' && $new_orderby !== '') {
        $query_params['orderby'] = $new_orderby;
    }
    
    // Zbuduj URL
    $query_string = !empty($query_params) ? '?' . http_build_query($query_params) : '';
    return $base_url . $query_string;
}

// Sortowanie i filtrowanie
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';

// Paginacja
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Query arguments
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 12,
    'paged' => $paged,
);

// Sortowanie - POPRAWIONE!
switch ($orderby) {
    case 'title':
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
        break;
    case 'title-desc':
        $args['orderby'] = 'title';
        $args['order'] = 'DESC';
        break;
    case 'popular':
        $args['meta_key'] = '_post_views';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        break;
    case 'date':
    default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
}

// Debug - usuń po teście
if (isset($_GET['debug'])) {
    echo '<pre>Orderby: ' . $orderby . '</pre>';
    echo '<pre>Args: ' . print_r($args, true) . '</pre>';
}

$blog_query = new WP_Query($args);
?>

<section class="blog-archive-hero">
    <div class="hero-content">
        <h1 class="hero-title">Aktualności</h1>
        <p class="hero-description">
            Odkryj najnowsze artykuły, porady i informacje o roślinach mięsożernych.
        </p>

        <?php if ($blog_query->have_posts()) : ?>
            <div class="blog-count">
                <span style="background: white; color: #16a34a; padding: 0.75rem 1.5rem; border-radius: 50px; font-weight: 600; display: inline-block;">
                    <?php
                    echo $blog_query->found_posts . ' ' . 
                    (($blog_query->found_posts == 1) ? 'artykuł' : 
                    (($blog_query->found_posts < 5) ? 'artykuły' : 'artykułów')) . 
                    ' dostępnych';
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container-fluid p-5">
    <div class="blog-content">
        
        <!-- BLOG CONTROLS SECTION -->
        <div class="blog-controls">
            <div class="controls-left">
                <span class="control-label">Widok:</span>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" data-view="grid">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <span class="btn-text">Siatka</span>
                    </button>
                    <button class="view-toggle-btn" data-view="list">
                        <i class="bi bi-list"></i>
                        <span class="btn-text">Lista</span>
                    </button>
                </div>
            </div>
            
            <div class="controls-right">
                <div class="sort-control">
                    <label for="blog-sort" class="control-label">Sortuj:</label>
                    <select id="blog-sort" class="sort-select">
                        <option value="" <?= ($orderby == 'date' || !isset($_GET['orderby'])) ? 'selected' : '' ?>>
                            Najnowsze
                        </option>
                        <option value="title" <?= ($orderby == 'title') ? 'selected' : '' ?>>
                            A-Z
                        </option>
                        <option value="title-desc" <?= ($orderby == 'title-desc') ? 'selected' : '' ?>>
                            Z-A
                        </option>
                        <option value="popular" <?= ($orderby == 'popular') ? 'selected' : '' ?>>
                            Najpopularniejsze
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- BLOG GRID -->
        <div class="blog-grid" data-view="grid" id="blogGrid">
            <?php if ($blog_query->have_posts()) : ?>
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <article class="blog-card" onclick="location.href='<?= esc_url(get_permalink()) ?>'">
                        <div class="card-image-container" 
                             <?php if (has_post_thumbnail()) : ?>
                                 style="background-image: url('<?= esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')) ?>');"
                             <?php endif; ?>>
                            <?php if (!has_post_thumbnail()) : ?>
                                <div class="card-image-placeholder">
                                    <i class="bi bi-file-text"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-content">
                            <h3 class="card-title"><?= esc_html(get_the_title()) ?></h3>
                            <p class="card-excerpt"><?= wp_trim_words(get_the_excerpt(), 20, '...') ?></p>
                            
                            <div class="card-meta">
                                <div class="blog-meta-extended">
                                    <div class="blog-meta-left">
                                        <span class="card-date">
                                            <i class="bi bi-calendar3"></i>
                                            <?= get_the_date('j M Y') ?>
                                        </span>
                                    </div>
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) :
                                    ?>
                                        <span class="blog-category-badge">
                                            <?= esc_html($categories[0]->name) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="no-posts">
                    <div class="card-image-placeholder">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>Nie znaleziono wpisów</h3>
                    <p>Spróbuj zmienić kryteria sortowania.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINACJA -->
        <?php if ($blog_query->max_num_pages > 1) : ?>
            <div class="custom-pagination">
                <div class="pagination-container">
                    <?php
                    $pagination_args = array(
                        'total' => $blog_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'format' => '?paged=%#%',
                        'show_all' => false,
                        'type' => 'array',
                        'end_size' => 2,
                        'mid_size' => 1,
                        'prev_next' => true,
                        'prev_text' => '<i class="bi bi-chevron-left"></i> <span class="text">Poprzednia</span>',
                        'next_text' => '<span class="text">Następna</span> <i class="bi bi-chevron-right"></i>',
                        'add_args' => false,
                        'add_fragment' => '',
                    );
                    
                    // POPRAWKA: Dodaj parametry sortowania do paginacji
                    if ($orderby && $orderby !== 'date') {
                        $pagination_args['add_args'] = ['orderby' => $orderby];
                    }
                    
                    $pagination = paginate_links($pagination_args);
                    
                    if ($pagination) {
                        foreach ($pagination as $link) {
                            echo $link;
                        }
                    }
                    ?>
                </div>
                
                <div class="pagination-info">
                    Strona <?= max(1, get_query_var('paged')) ?> z <?= $blog_query->max_num_pages ?> 
                    (<?= $blog_query->found_posts ?> wpisów)
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<!-- POPRAWIONY JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('blog-sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const value = this.value;
            const url = new URL(window.location);
            
            // Usuń stare parametry
            url.searchParams.delete('orderby');
            url.searchParams.delete('paged');
            
            // Dodaj nowy parametr jeśli nie jest pusty
            if (value && value !== '') {
                url.searchParams.set('orderby', value);
            }
            
            // Przekieruj
            window.location.href = url.toString();
        });
    }
});
</script>

<?php
wp_reset_postdata();
get_footer();
?>