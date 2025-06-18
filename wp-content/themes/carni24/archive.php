<?php
/**
 * Archive template for blog posts - NAPRAWIONY I DOPRACOWANY
 * Plik: archive.php
 * Autor: Carni24 Team
 * Szablon dla kategorii, tagów i archiwów wpisów
 */

get_header();

// Sortowanie i filtrowanie
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';

// Pobranie informacji o archiwum
$archive_title = '';
$archive_description = '';

if (is_category()) {
    $archive_title = 'Kategoria: ' . single_cat_title('', false);
    $archive_description = category_description();
} elseif (is_tag()) {
    $archive_title = 'Tag: ' . single_tag_title('', false);
    $archive_description = tag_description();
} elseif (is_author()) {
    $archive_title = 'Autor: ' . get_the_author();
    $archive_description = get_the_author_meta('description');
} elseif (is_date()) {
    $archive_title = 'Archiwum: ' . get_the_date('F Y');
    $archive_description = 'Wpisy z ' . get_the_date('F Y');
} else {
    $archive_title = 'Archiwum wpisów';
    $archive_description = 'Przeglądaj wszystkie wpisy z naszego bloga.';
}

// Modyfikacja głównego query - POPRAWIONE!
global $wp_query;
switch ($orderby) {
    case 'title':
        $wp_query->set('orderby', 'title');
        $wp_query->set('order', 'ASC');
        break;
    case 'title-desc':
        $wp_query->set('orderby', 'title');
        $wp_query->set('order', 'DESC');
        break;
    case 'popular':
        $wp_query->set('meta_key', '_post_views');
        $wp_query->set('orderby', 'meta_value_num');
        $wp_query->set('order', 'DESC');
        break;
    case 'date':
    default:
        $wp_query->set('orderby', 'date');
        $wp_query->set('order', 'DESC');
}

// Debug - usuń po teście
if (isset($_GET['debug'])) {
    echo '<pre>Archive Orderby: ' . $orderby . '</pre>';
    echo '<pre>Archive Query Vars: ' . print_r($wp_query->query_vars, true) . '</pre>';
}
?>

<section class="archive-hero">
    <div class="hero-content">
        <h1 class="hero-title"><?= esc_html($archive_title) ?></h1>
        <?php if ($archive_description) : ?>
            <p class="hero-description"><?= wp_kses_post($archive_description) ?></p>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            <div class="archive-count">
                <span style="background: white; color: #16a34a; padding: 0.75rem 1.5rem; border-radius: 50px; font-weight: 600; display: inline-block;">
                    <?php
                    echo $wp_query->found_posts . ' ' . 
                    (($wp_query->found_posts == 1) ? 'wpis' : 
                    (($wp_query->found_posts < 5) ? 'wpisy' : 'wpisów')) . 
                    ' znalezionych';
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container-fluid p-md-5">
    <div class="archive-content">
        
        <!-- ARCHIVE CONTROLS SECTION -->
        <div class="archive-controls">
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
                    <label for="archive-sort" class="control-label">Sortuj:</label>
                    <select id="archive-sort" class="sort-select">
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

        <!-- ARCHIVE GRID -->
        <div class="blog-grid" data-view="grid" id="archiveGrid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
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
                    <p>Brak wpisów w tym archiwum.</p>
                    <a href="<?= home_url() ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #16a34a; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 1rem;">
                        <i class="bi bi-house"></i>
                        Strona główna
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINACJA -->
        <?php
        $pagination_args = array(
            'total' => $wp_query->max_num_pages,
            'current' => max(1, get_query_var('paged')),
            'format' => '?paged=%#%',
            'show_all' => false,
            'type' => 'array',
            'end_size' => 2,
            'mid_size' => 1,
            'prev_next' => true,
            'prev_text' => '<i class="bi bi-chevron-left"></i> <span class="text">Poprzednia</span>',
            'next_text' => '<span class="text">Następna</span> <i class="bi bi-chevron-right"></i>',
            'add_args' => array(),
        );
        
        // POPRAWKA: Dodaj parametry sortowania do paginacji
        if ($orderby && $orderby !== 'date') {
            $pagination_args['add_args'] = ['orderby' => $orderby];
        }
        
        $pagination = paginate_links($pagination_args);
        
        if ($pagination) : ?>
            <div class="custom-pagination">
                <div class="pagination-container">
                    <?php foreach ($pagination as $link) {
                        echo $link;
                    } ?>
                </div>
                
                <div class="pagination-info">
                    Strona <?= max(1, get_query_var('paged')) ?> z <?= $wp_query->max_num_pages ?> 
                    (<?= $wp_query->found_posts ?> wpisów)
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<!-- POPRAWIONY JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('archive-sort');
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

<?php get_footer(); ?>