<?php
/**
 * Archive Species Template
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
        <div class="hero-text">
            <div class="hero-breadcrumbs">
                <a href="<?= home_url() ?>">Strona główna</a>
                <span class="breadcrumb-separator">›</span>
                <span>Gatunki</span>
            </div>
            
            <h1 class="hero-title">Katalog Gatunków</h1>
            <p class="hero-description">
                Odkryj różnorodność gatunków, ich unikalne cechy i sposoby pielęgnacji.
            </p>
            
            <?php if ($species_query->have_posts()) : ?>
                <div class="species-count">
                    <span class="count-badge">
                        <i class="bi bi-collection"></i>
                        <?php
                        echo $species_query->found_posts . ' ';
                        if ($species_query->found_posts == 1) {
                            echo 'gatunek';
                        } elseif ($species_query->found_posts % 10 >= 2 && $species_query->found_posts % 10 <= 4 && ($species_query->found_posts % 100 < 10 || $species_query->found_posts % 100 >= 20)) {
                            echo 'gatunki';
                        } else {
                            echo 'gatunków';
                        }
                        echo ' w kolekcji';
                        ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="species-content">
    <div class="container">
        <?php if ($species_query->have_posts()) : ?>
            
            <div class="species-controls">
                <div class="controls-left">
                    <div class="view-toggle">
                        <span class="control-label">Widok:</span>
                        <button type="button" class="view-btn active" data-view="grid">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <span class="btn-text">Siatka</span>
                        </button>
                        <button type="button" class="view-btn" data-view="list">
                            <i class="bi bi-list"></i>
                            <span class="btn-text">Lista</span>
                        </button>
                    </div>
                </div>
                <div class="controls-right">
                    <div class="sort-control">
                        <label for="species-sort-select" class="control-label">Sortuj:</label>
                        <select id="species-sort-select" class="control-select">
                            <option value="date" <?= $orderby === 'date' ? 'selected' : '' ?>>Najnowsze</option>
                            <option value="title" <?= $orderby === 'title' ? 'selected' : '' ?>>Alfabetycznie A-Z</option>
                            <option value="title-desc" <?= $orderby === 'title-desc' ? 'selected' : '' ?>>Alfabetycznie Z-A</option>
                            <option value="difficulty" <?= $orderby === 'difficulty' ? 'selected' : '' ?>>Poziom trudności</option>
                            <option value="popularity" <?= $orderby === 'popularity' ? 'selected' : '' ?>>Popularność</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="species-grid" id="speciesGrid" data-view="grid">
                <?php 
                while ($species_query->have_posts()) : $species_query->the_post(); 
                    
                    // Meta dane
                    $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                    $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                    $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                    $light_requirements = get_post_meta(get_the_ID(), '_species_light', true);
                    $water_requirements = get_post_meta(get_the_ID(), '_species_water', true);
                    $views = get_post_meta(get_the_ID(), '_species_views', true) ?: 0;
                    
                    // Kategorie
                    $categories = wp_get_post_terms(get_the_ID(), 'species_category');
                ?>
                    <article class="species-card">
                        <a href="<?= esc_url(get_permalink()) ?>" class="species-link">
                            
                            <div class="species-image-container">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', [
                                        'alt' => get_the_title(),
                                        'class' => 'species-thumbnail'
                                    ]); ?>
                                <?php else : ?>
                                    <div class="species-placeholder">
                                        <i class="bi bi-flower1"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($difficulty) : ?>
                                    <div class="species-difficulty difficulty-<?= esc_attr(strtolower($difficulty)) ?>">
                                        <?php
                                        switch (strtolower($difficulty)) {
                                            case 'łatwy':
                                            case 'easy':
                                                echo '<i class="bi bi-1-circle"></i>Łatwy';
                                                break;
                                            case 'średni':
                                            case 'medium':
                                                echo '<i class="bi bi-2-circle"></i>Średni';
                                                break;
                                            case 'trudny':
                                            case 'hard':
                                                echo '<i class="bi bi-3-circle"></i>Trudny';
                                                break;
                                            default:
                                                echo '<i class="bi bi-question-circle"></i>' . esc_html($difficulty);
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="species-content">
                                <h2 class="species-title"><?php the_title(); ?></h2>
                                
                                <?php if ($scientific_name) : ?>
                                    <div class="species-scientific">
                                        <em><?= esc_html($scientific_name) ?></em>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="species-excerpt">
                                    <?= wp_trim_words(get_the_excerpt(), 20, '...') ?>
                                </div>
                                
                                <div class="species-meta">
                                    <?php if ($origin) : ?>
                                        <div class="meta-item">
                                            <i class="bi bi-geo-alt"></i>
                                            <span><?= esc_html($origin) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($light_requirements) : ?>
                                        <div class="meta-item">
                                            <i class="bi bi-sun"></i>
                                            <span><?= esc_html($light_requirements) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($water_requirements) : ?>
                                        <div class="meta-item">
                                            <i class="bi bi-droplet"></i>
                                            <span><?= esc_html($water_requirements) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($categories)) : ?>
                                    <div class="species-categories">
                                        <?php foreach ($categories as $category) : ?>
                                            <span class="species-category"><?= esc_html($category->name) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="species-footer">
                                    <div class="species-date">
                                        <i class="bi bi-calendar3"></i>
                                        <time datetime="<?= get_the_date('c') ?>">
                                            <?= get_the_date('d.m.Y') ?>
                                        </time>
                                    </div>
                                    <div class="species-views">
                                        <i class="bi bi-eye"></i>
                                        <span><?= number_format($views) ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php if ($species_query->max_num_pages > 1) : ?>
                <div class="species-pagination">
                    <?php
                    echo paginate_links(array(
                        'total' => $species_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'format' => '?paged=%#%',
                        'show_all' => false,
                        'end_size' => 1,
                        'mid_size' => 2,
                        'prev_next' => true,
                        'prev_text' => '<i class="bi bi-chevron-left"></i> Poprzednia',
                        'next_text' => 'Następna <i class="bi bi-chevron-right"></i>',
                        'add_args' => array('orderby' => $orderby),
                    ));
                    ?>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <div class="no-species">
                <div class="no-species-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h2>Brak gatunków</h2>
                <p>Nie znaleziono żadnych gatunków w bazie danych.</p>
                <a href="<?= home_url() ?>" class="btn btn-primary">
                    <i class="bi bi-house"></i>
                    Wróć na stronę główną
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>