<?php
/**
 * Archive Species Template - NAPRAWIONY
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

<!-- CSS INLINE PRO TESTY -->
<style>
    /* CRITICAL STYLES FOR VIEW TOGGLE */
    .species-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
        transition: all 0.3s ease;
    }

    .species-grid[data-view="list"] {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }

    .species-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .species-grid[data-view="list"] .species-card {
        display: flex !important;
        height: auto !important;
        min-height: 200px !important;
    }

    .species-image-container {
        position: relative;
        height: 250px;
        background: linear-gradient(135deg, #f8faf8 0%, #f0f4f0 100%);
        overflow: hidden;
    }

    .species-grid[data-view="list"] .species-image-container {
        width: 100% !important;
        height: 200px !important;
        flex-shrink: 0 !important;
    }

    .species-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: calc(100% - 250px);
    }

    .species-grid[data-view="list"] .species-content {
        flex: 1 !important;
        height: auto !important;
        padding: 2rem !important;
    }

    .view-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .view-btn:hover,
    .view-btn.active {
        border-color: #16a34a;
        background: #16a34a;
        color: white;
    }

    .species-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }

    .view-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-label {
        font-weight: 600;
        color: #374151;
        margin-right: 1rem;
    }

    /* Hero section */
    .species-archive-hero {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 4rem 0;
        text-align: center;
    }

    .hero-content {
        margin: 0 auto;
        padding: 0 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .hero-description {
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .container {
        margin: 0 auto;
        padding: 0 2rem;
    }

    .species-content {
        padding: 4rem 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .species-controls {
            flex-direction: column;
            gap: 1rem;
        }

        .species-grid {
            grid-template-columns: 1fr;
        }

        .species-grid[data-view="list"] .species-card {
            flex-direction: column !important;
        }

        .species-grid[data-view="list"] .species-image-container {
            width: 100% !important;
            height: 200px !important;
        }
    }
</style>

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
</section>

<section class="species-content p-5">
    <div class="container-fluid">
        <?php if ($species_query->have_posts()) : ?>

            <div class="species-controls">
                <div class="view-toggle">
                    <span class="control-label">Widok:</span>
                    <button type="button" class="view-btn active" data-view="grid" id="grid-btn">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <span>Siatka</span>
                    </button>
                    <button type="button" class="view-btn" data-view="list" id="list-btn">
                        <i class="bi bi-list"></i>
                        <span>Lista</span>
                    </button>
                </div>
                <div>
                    <label for="species-sort-select" class="control-label">Sortuj:</label>
                    <select id="species-sort-select" style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px;">
                        <option value="date" <?= $orderby === 'date' ? 'selected' : '' ?>>Najnowsze</option>
                        <option value="title" <?= $orderby === 'title' ? 'selected' : '' ?>>Alfabetycznie A-Z</option>
                        <option value="title-desc" <?= $orderby === 'title-desc' ? 'selected' : '' ?>>Alfabetycznie Z-A</option>
                        <option value="difficulty" <?= $orderby === 'difficulty' ? 'selected' : '' ?>>Poziom trudności</option>
                        <option value="popularity" <?= $orderby === 'popularity' ? 'selected' : '' ?>>Popularność</option>
                    </select>
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

                    // URL obrazka dla tła
                    $image_url = '';
                    if (has_post_thumbnail()) {
                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    }
                    ?>
                    <article class="species-card">
                        <a href="<?= esc_url(get_permalink()) ?>" style="text-decoration: none; color: inherit; display: block; height: 100%; width: 100%;">

                            <!-- Obrazek jako tło -->
                            <div class="species-image-container" 
                            <?php if ($image_url) : ?>
                                     style="background-image: url('<?= esc_url($image_url) ?>') !important; background-size: cover !important; background-position: center !important; background-repeat: no-repeat;"
                                 <?php else : ?>
                                     style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);"
                                 <?php endif; ?>>

                                <!-- Placeholder ikona jeśli brak obrazka -->
                                <?php if (!$image_url) : ?>
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 4rem; color: #16a34a;">
                                        <i class="bi bi-flower1"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Badge trudności -->
                                <?php if ($difficulty) : ?>
                                    <div class="species-difficulty" style="position: absolute; top: 15px; right: 15px; padding: 0.5rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: rgba(34, 197, 94, 0.9); color: white; backdrop-filter: blur(10px);">
                                        <?php
                                        switch (strtolower($difficulty)) {
                                            case 'łatwy':
                                            case 'easy':
                                                echo '<i class="bi bi-1-circle"></i> Łatwy';
                                                break;
                                            case 'średni':
                                            case 'medium':
                                                echo '<i class="bi bi-2-circle"></i> Średni';
                                                break;
                                            case 'trudny':
                                            case 'hard':
                                                echo '<i class="bi bi-3-circle"></i> Trudny';
                                                break;
                                            default:
                                                echo esc_html($difficulty);
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="species-content" style="padding: 1.5rem; display: flex; flex-direction: column; height: calc(100% - 250px);">
                                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;"><?php the_title(); ?></h2>

                                <?php if ($scientific_name) : ?>
                                    <div style="font-style: italic; color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">
                                        <em><?= esc_html($scientific_name) ?></em>
                                    </div>
                                <?php endif; ?>

                                <div style="color: #6b7280; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem; flex: 1;">
                                    <?= wp_trim_words(get_the_excerpt(), 20, '...') ?>
                                </div>

                                <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem; font-size: 0.8rem; color: #9ca3af;">
                                    <?php if ($origin) : ?>
                                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                                            <i class="bi bi-geo-alt"></i>
                                            <span><?= esc_html($origin) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($light_requirements) : ?>
                                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                                            <i class="bi bi-sun"></i>
                                            <span><?= esc_html($light_requirements) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($water_requirements) : ?>
                                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                                            <i class="bi bi-droplet"></i>
                                            <span><?= esc_html($water_requirements) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #e5e7eb; font-size: 0.8rem; color: #9ca3af; margin-top: auto;">
                                    <div style="display: flex; align-items: center; gap: 0.25rem;">
                                        <i class="bi bi-calendar3"></i>
                                        <time datetime="<?= get_the_date('c') ?>">
                                            <?= get_the_date('d.m.Y') ?>
                                        </time>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.25rem;">
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
                <div style="margin-top: 3rem; text-align: center;">
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
            <div style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 4rem; color: #9ca3af; margin-bottom: 1rem;">
                    <i class="bi bi-search"></i>
                </div>
                <h2>Brak gatunków</h2>
                <p>Nie znaleziono żadnych gatunków w bazie danych.</p>
                <a href="<?= home_url() ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #16a34a; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 1rem;">
                    <i class="bi bi-house"></i>
                    Wróć na stronę główną
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- JAVASCRIPT INLINE -->
<script>
    console.log('Species archive script loading...');

    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, initializing view toggle...');

        const viewButtons = document.querySelectorAll('[data-view]');
        const speciesGrid = document.getElementById('speciesGrid');
        const sortSelect = document.getElementById('species-sort-select');

        console.log('Found elements:', {
            buttons: viewButtons.length,
            grid: !!speciesGrid,
            select: !!sortSelect
        });

        // VIEW TOGGLE
        if (viewButtons.length > 0 && speciesGrid) {
            // Restore saved view
            const savedView = localStorage.getItem('species-view') || 'grid';
            setView(savedView);

            viewButtons.forEach((button, index) => {
                console.log('Setting up button', index, ':', button.dataset.view);

                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const view = this.getAttribute('data-view');
                    console.log('View button clicked:', view);

                    setView(view);
                    localStorage.setItem('species-view', view);
                });
            });
        }

        function setView(view) {
            if (!speciesGrid)
                return;

            console.log('Setting view to:', view);

            // Update grid
            speciesGrid.setAttribute('data-view', view);

            // Update buttons
            viewButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-view') === view) {
                    btn.classList.add('active');
                }
            });

            // Force layout update
            speciesGrid.style.display = 'none';
            speciesGrid.offsetHeight; // Trigger reflow
            speciesGrid.style.display = '';

            console.log('View set to:', view, 'Grid data-view:', speciesGrid.getAttribute('data-view'));
        }

        // SORT FUNCTIONALITY
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                const orderby = this.value;
                const url = new URL(window.location);

                if (orderby === 'date') {
                    url.searchParams.delete('orderby');
                } else {
                    url.searchParams.set('orderby', orderby);
                }

                url.searchParams.delete('paged');
                window.location.href = url.toString();
            });
        }

        console.log('Species archive initialized successfully');
    });

// BACKUP - Direct button handlers
    setTimeout(function () {
        const gridBtn = document.getElementById('grid-btn');
        const listBtn = document.getElementById('list-btn');
        const grid = document.getElementById('speciesGrid');

        if (gridBtn && listBtn && grid) {
            gridBtn.onclick = function (e) {
                e.preventDefault();
                console.log('Grid button direct click');
                listBtn.classList.remove('active');
                gridBtn.classList.add('active');
                grid.setAttribute('data-view', 'grid');
                grid.style.display = 'none';
                grid.offsetHeight;
                grid.style.display = '';
            };

            listBtn.onclick = function (e) {
                e.preventDefault();
                console.log('List button direct click');
                gridBtn.classList.remove('active');
                listBtn.classList.add('active');
                grid.setAttribute('data-view', 'list');
                grid.style.display = 'none';
                grid.offsetHeight;
                grid.style.display = '';
            };

            console.log('Direct click handlers set as backup');
        }
    }, 500);
</script>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>