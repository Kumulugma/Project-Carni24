<?php
/**
 * Archive Species Template - NAPRAWIONY I DOPRACOWANY
 * wp-content/themes/carni24/archive-species.php
 */
get_header();

// Sortowanie i filtrowanie
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';

// Query arguments
$args = array(
    'post_type' => 'species',
    'posts_per_page' => 12,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
);

// Sortowanie
switch ($orderby) {
    case 'title':
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
        break;
    case 'title-desc':
        $args['orderby'] = 'title';
        $args['order'] = 'DESC';
        break;
    case 'difficulty':
        $args['meta_key'] = '_species_difficulty';
        $args['orderby'] = 'meta_value';
        $args['order'] = 'ASC';
        break;
    case 'popularity':
        $args['meta_key'] = '_species_views';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        break;
    default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
}

$species_query = new WP_Query($args);
?>

<section class="species-archive-hero">
    <div class="hero-content">
        <h1 class="hero-title">Katalog Gatunków</h1>
        <p class="hero-description">
            Odkryj różnorodność gatunków, ich unikalne cechy i sposoby pielęgnacji.
        </p>

        <?php if ($species_query->have_posts()) : ?>
            <div class="species-count">
                <span style="background: white; color: #16a34a; padding: 0.75rem 1.5rem; border-radius: 50px; font-weight: 600; display: inline-block;">
                    <?php
                    echo $species_query->found_posts . ' ' . 
                    (($species_query->found_posts == 1) ? 'gatunek' : 
                    (($species_query->found_posts < 5) ? 'gatunki' : 'gatunków')) . 
                    ' dostępnych';
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container-fluid p-5">
    <div class="species-content">
        
        <!-- CONTROLS SECTION -->
        <div class="species-controls">
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
                    <label for="species-sort" class="control-label">Sortuj:</label>
                    <select id="species-sort" class="sort-select" onchange="location = this.value;">
                        <option value="<?= remove_query_arg('orderby') ?>" <?= !isset($_GET['orderby']) ? 'selected' : '' ?>>
                            Najnowsze
                        </option>
                        <option value="<?= add_query_arg('orderby', 'title') ?>" <?= ($orderby == 'title') ? 'selected' : '' ?>>
                            A-Z
                        </option>
                        <option value="<?= add_query_arg('orderby', 'title-desc') ?>" <?= ($orderby == 'title-desc') ? 'selected' : '' ?>>
                            Z-A
                        </option>
                        <option value="<?= add_query_arg('orderby', 'popularity') ?>" <?= ($orderby == 'popularity') ? 'selected' : '' ?>>
                            Najpopularniejsze
                        </option>
                        <option value="<?= add_query_arg('orderby', 'difficulty') ?>" <?= ($orderby == 'difficulty') ? 'selected' : '' ?>>
                            Łatwość pielęgnacji
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SPECIES GRID -->
        <div class="species-grid" data-view="grid" id="speciesGrid">
            <?php if ($species_query->have_posts()) : ?>
                <?php while ($species_query->have_posts()) : $species_query->the_post(); ?>
                    <article class="species-card" onclick="location.href='<?= esc_url(get_permalink()) ?>'">
                        <div class="card-image-container" 
                             <?php if (has_post_thumbnail()) : ?>
                                 style="background-image: url('<?= esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')) ?>');"
                             <?php endif; ?>>
                            <?php if (!has_post_thumbnail()) : ?>
                                <div class="card-image-placeholder">
                                    <i class="bi bi-flower1"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-content">
                            <h3 class="card-title"><?= esc_html(get_the_title()) ?></h3>
                            
                            <?php
                            $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                            if ($scientific_name) :
                            ?>
                                <p class="card-scientific"><?= esc_html($scientific_name) ?></p>
                            <?php endif; ?>
                            
                            <p class="card-excerpt"><?= wp_trim_words(get_the_excerpt(), 15, '...') ?></p>
                            
                            <div class="card-meta">
                                <?php
                                $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                                $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                                ?>
                                
                                <div class="species-meta-extended">
                                    <?php if ($origin) : ?>
                                        <span class="species-meta-item">
                                            <i class="bi bi-geo-alt"></i>
                                            <?= esc_html($origin) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($difficulty) : ?>
                                        <span class="species-difficulty">
                                            <i class="bi bi-star-fill"></i>
                                            <?= esc_html($difficulty) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-date">
                                    <i class="bi bi-calendar3"></i>
                                    <?= get_the_date('j M Y') ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="no-species">
                    <div class="card-image-placeholder">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3>Nie znaleziono gatunków</h3>
                    <p>Spróbuj zmienić kryteria wyszukiwania lub sortowania.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINACJA -->
        <?php if ($species_query->max_num_pages > 1) : ?>
            <div class="custom-pagination">
                <div class="pagination-container">
                    <?php
                    $pagination = paginate_links(array(
                        'total' => $species_query->max_num_pages,
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
                    ));
                    
                    if ($pagination) {
                        foreach ($pagination as $link) {
                            echo $link;
                        }
                    }
                    ?>
                </div>
                
                <div class="pagination-info">
                    Strona <?= max(1, get_query_var('paged')) ?> z <?= $species_query->max_num_pages ?> 
                    (<?= $species_query->found_posts ?> gatunków)
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php
wp_reset_postdata();
get_footer();
?>