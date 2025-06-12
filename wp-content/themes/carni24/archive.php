<?php
/**
 * Archive template for blog posts
 * Plik: archive.php
 * Autor: Carni24 Team
 */

get_header(); ?>

<main class="blog-archive-main">
    
    <!-- Breadcrumbs -->
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= home_url() ?>">Strona główna</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    if (is_category()) {
                        echo 'Kategoria: ' . single_cat_title('', false);
                    } elseif (is_tag()) {
                        echo 'Tag: ' . single_tag_title('', false);
                    } elseif (is_author()) {
                        echo 'Autor: ' . get_the_author();
                    } elseif (is_date()) {
                        echo 'Archiwum: ' . get_the_date('F Y');
                    } else {
                        echo 'Blog';
                    }
                    ?>
                </li>
            </ol>
        </nav>
    </div>
    
    <!-- Header sekcji -->
    <section class="blog-archive-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="blog-archive-title">
                        <i class="bi bi-newspaper me-3"></i>
                        <?php
                        if (is_category()) {
                            echo single_cat_title('', false);
                        } elseif (is_tag()) {
                            echo 'Tag: ' . single_tag_title('', false);
                        } elseif (is_author()) {
                            echo 'Wpisy autora: ' . get_the_author();
                        } elseif (is_date()) {
                            echo 'Archiwum: ' . get_the_date('F Y');
                        } else {
                            echo 'Wszystkie wpisy';
                        }
                        ?>
                    </h1>
                    
                    <?php if (is_category() && category_description()) : ?>
                        <div class="blog-archive-description">
                            <?= category_description() ?>
                        </div>
                    <?php elseif (is_tag() && tag_description()) : ?>
                        <div class="blog-archive-description">
                            <?= tag_description() ?>
                        </div>
                    <?php else : ?>
                        <p class="blog-archive-description">
                            Odkryj nasze artykuły o roślinach mięsożernych, poradniki uprawy i najnowsze informacje ze świata botaniki.
                        </p>
                    <?php endif; ?>
                    
                    <?php if (have_posts()) : ?>
                        <div class="blog-archive-meta">
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-journal-text me-1"></i>
                                <?= $wp_query->found_posts ?> 
                                <?= _n('wpis', 'wpisy', $wp_query->found_posts, 'carni24') ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Treść bloga -->
    <section class="blog-archive-content pb-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                
                <!-- Filtry/Sortowanie -->
                <div class="blog-filters">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="blog-view-toggle">
                                <span class="text-muted me-3">Widok:</span>
                                <div class="btn-group" role="group" aria-label="Przełącznik widoku">
                                    <button type="button" class="btn btn-outline-success btn-sm active" data-view="grid">
                                        <i class="bi bi-grid-3x3-gap"></i> 
                                        <span class="d-none d-sm-inline ms-1">Siatka</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" data-view="list">
                                        <i class="bi bi-list"></i> 
                                        <span class="d-none d-sm-inline ms-1">Lista</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="blog-sort-select">
                                <select class="form-select form-select-sm" id="blogSort">
                                    <option value="date">Najnowsze</option>
                                    <option value="title">Alfabetycznie</option>
                                    <option value="popular">Najpopularniejsze</option>
                                    <option value="commented">Najkomentowane</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="blog-search-input">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Szukaj w wpisach..." id="blogSearch">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grid wpisów -->
                <div class="blog-posts-grid" id="blogPostsGrid">
                    <div class="row g-4">
                        <?php while (have_posts()) : the_post();
                            $post_image = get_the_post_thumbnail_url(get_the_ID(), 'blog_thumb');
                            if (!$post_image) {
                                $post_image = get_template_directory_uri() . '/assets/images/default-post.jpg';
                            }
                            $categories = get_the_category();
                            $reading_time = carni24_calculate_reading_time(get_the_content());
                        ?>
                            <div class="col-lg-4 col-md-6">
                                <article class="blog-post-card">
                                    <a href="<?= esc_url(get_permalink()) ?>" class="blog-post-link">
                                        <div class="blog-post-image-container">
                                            <img src="<?= esc_url($post_image) ?>" 
                                                 alt="<?= esc_attr(get_the_title()) ?>" 
                                                 class="blog-post-image">
                                            <div class="blog-post-date-badge">
                                                <span class="day"><?= get_the_date('d') ?></span>
                                                <span class="month"><?= get_the_date('M') ?></span>
                                            </div>
                                        </div>
                                        <div class="blog-post-content">
                                            <?php if ($categories) : ?>
                                                <div class="blog-post-category">
                                                    <?= esc_html($categories[0]->name) ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <h3 class="blog-post-title">
                                                <?php the_title() ?>
                                            </h3>
                                            
                                            <div class="blog-post-excerpt">
                                                <?= wp_trim_words(get_the_excerpt(), 20, '...') ?>
                                            </div>
                                            
                                            <div class="blog-post-meta">
                                                <div class="blog-post-author">
                                                    <i class="bi bi-person me-1"></i>
                                                    <?= get_the_author() ?>
                                                </div>
                                                <div class="blog-post-reading-time">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= $reading_time ?> min
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <!-- Paginacja -->
                <div class="blog-pagination">
                    <?php
                    echo paginate_links(array(
                        'total' => $wp_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'format' => '?paged=%#%',
                        'show_all' => false,
                        'end_size' => 1,
                        'mid_size' => 2,
                        'prev_next' => true,
                        'prev_text' => '<i class="bi bi-chevron-left"></i> Poprzednia',
                        'next_text' => 'Następna <i class="bi bi-chevron-right"></i>',
                        'add_args' => false,
                        'add_fragment' => '',
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                
                <!-- Brak wpisów -->
                <div class="no-blog-posts">
                    <h3>Brak wpisów</h3>
                    <p>
                        Nie znaleziono żadnych wpisów w tej kategorii.
                        <br>Sprawdź później lub przeglądaj inne kategorie.
                    </p>
                    <div class="no-blog-posts-actions">
                        <a href="<?= home_url() ?>" class="btn btn-success me-3">
                            <i class="bi bi-house me-2"></i>
                            Strona główna
                        </a>
                        <a href="<?= home_url('/species/') ?>" class="btn btn-outline-success">
                            <i class="bi bi-flower1 me-2"></i>
                            Przeglądaj gatunki
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
        </div>
    </section>
    
</main>

<?php get_footer(); ?>