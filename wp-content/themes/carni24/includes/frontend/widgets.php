<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget 1', 'carni24'),
        'id'            => 'footer-1',
        'description'   => __('Pierwszy obszar widgetów w stopce', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title mb-3">',
        'after_title'   => '</h5>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget 2', 'carni24'),
        'id'            => 'footer-2',
        'description'   => __('Drugi obszar widgetów w stopce', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title mb-3">',
        'after_title'   => '</h5>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget 3', 'carni24'),
        'id'            => 'footer-3',
        'description'   => __('Trzeci obszar widgetów w stopce', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title mb-3">',
        'after_title'   => '</h5>',
    ));
    
    register_sidebar(array(
        'name'          => __('Sidebar Post', 'carni24'),
        'id'            => 'sidebar-post',
        'description'   => __('Sidebar dla pojedynczych postów', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title mb-3">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Sidebar Archive', 'carni24'),
        'id'            => 'sidebar-archive',
        'description'   => __('Sidebar dla stron archiwów', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title mb-3">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Sidebar Species', 'carni24'),
        'id'            => 'sidebar-species',
        'description'   => __('Sidebar dla gatunków', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title mb-3">',
        'after_title'   => '</h4>',
    ));
    
    register_sidebar(array(
        'name'          => __('Homepage Widget Area', 'carni24'),
        'id'            => 'homepage-widgets',
        'description'   => __('Obszar widgetów na stronie głównej', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-5">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title text-center mb-4">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'carni24_widgets_init');

class Carni24_Popular_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_popular_posts',
            'Carni24: Popularne posty',
            array('description' => 'Wyświetla listę najpopularniejszych postów')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : 'Popularne posty';
        $count = !empty($instance['count']) ? absint($instance['count']) : 5;
        $post_types = !empty($instance['post_types']) ? $instance['post_types'] : array('post');
        $show_views = !empty($instance['show_views']);
        $show_date = !empty($instance['show_date']);
        $show_excerpt = !empty($instance['show_excerpt']);
        
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        
        $popular_posts = new WP_Query(array(
            'post_type'      => $post_types,
            'posts_per_page' => $count,
            'meta_key'       => 'post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'post_status'    => 'publish'
        ));
        
        if ($popular_posts->have_posts()) {
            echo '<ul class="popular-posts-list list-unstyled">';
            while ($popular_posts->have_posts()) {
                $popular_posts->the_post();
                echo '<li class="popular-post-item d-flex mb-3 pb-3 border-bottom">';
                
                if (has_post_thumbnail()) {
                    echo '<div class="popular-post-thumb me-3 flex-shrink-0">';
                    echo '<a href="' . get_permalink() . '">';
                    echo get_the_post_thumbnail(get_the_ID(), 'widget_thumb', array('class' => 'rounded'));
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="popular-post-content flex-grow-1">';
                echo '<h6 class="mb-1"><a href="' . get_permalink() . '" class="text-decoration-none">' . get_the_title() . '</a></h6>';
                
                if ($show_date) {
                    echo '<small class="text-muted d-block">' . carni24_format_date() . '</small>';
                }
                
                if ($show_views) {
                    $views = carni24_get_post_views(get_the_ID());
                    if ($views > 0) {
                        echo '<small class="text-muted d-block"><i class="bi bi-eye me-1"></i>' . number_format($views) . ' wyświetleń</small>';
                    }
                }
                
                if ($show_excerpt) {
                    echo '<p class="mb-0 small text-muted mt-1">' . wp_trim_words(get_the_excerpt(), 15) . '</p>';
                }
                
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p class="text-muted">Brak popularnych postów.</p>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Popularne posty';
        $count = !empty($instance['count']) ? $instance['count'] : 5;
        $post_types = !empty($instance['post_types']) ? $instance['post_types'] : array('post');
        $show_views = !empty($instance['show_views']);
        $show_date = !empty($instance['show_date']);
        $show_excerpt = !empty($instance['show_excerpt']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Tytuł:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Liczba postów:</label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" 
                   name="<?php echo $this->get_field_name('count'); ?>" type="number" 
                   step="1" min="1" max="20" value="<?php echo esc_attr($count); ?>">
        </p>
        <p>
            <label>Typy postów:</label><br>
            <input type="checkbox" id="<?php echo $this->get_field_id('post_types_post'); ?>" 
                   name="<?php echo $this->get_field_name('post_types'); ?>[]" value="post" 
                   <?php checked(in_array('post', $post_types)); ?>>
            <label for="<?php echo $this->get_field_id('post_types_post'); ?>">Posty</label><br>
            
            <input type="checkbox" id="<?php echo $this->get_field_id('post_types_species'); ?>" 
                   name="<?php echo $this->get_field_name('post_types'); ?>[]" value="species" 
                   <?php checked(in_array('species', $post_types)); ?>>
            <label for="<?php echo $this->get_field_id('post_types_species'); ?>">Gatunki</label><br>
            
            <input type="checkbox" id="<?php echo $this->get_field_id('post_types_guides'); ?>" 
                   name="<?php echo $this->get_field_name('post_types'); ?>[]" value="guides" 
                   <?php checked(in_array('guides', $post_types)); ?>>
            <label for="<?php echo $this->get_field_id('post_types_guides'); ?>">Poradniki</label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_views'); ?>" 
                   name="<?php echo $this->get_field_name('show_views'); ?>" value="1" 
                   <?php checked($show_views); ?>>
            <label for="<?php echo $this->get_field_id('show_views'); ?>">Pokaż wyświetlenia</label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_date'); ?>" 
                   name="<?php echo $this->get_field_name('show_date'); ?>" value="1" 
                   <?php checked($show_date); ?>>
            <label for="<?php echo $this->get_field_id('show_date'); ?>">Pokaż datę</label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_excerpt'); ?>" 
                   name="<?php echo $this->get_field_name('show_excerpt'); ?>" value="1" 
                   <?php checked($show_excerpt); ?>>
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>">Pokaż fragment</label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['count'] = (!empty($new_instance['count'])) ? absint($new_instance['count']) : 5;
        $instance['post_types'] = (!empty($new_instance['post_types'])) ? $new_instance['post_types'] : array('post');
        $instance['show_views'] = !empty($new_instance['show_views']);
        $instance['show_date'] = !empty($new_instance['show_date']);
        $instance['show_excerpt'] = !empty($new_instance['show_excerpt']);
        return $instance;
    }
}

class Carni24_Recent_Species_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_recent_species',
            'Carni24: Najnowsze gatunki',
            array('description' => 'Wyświetla najnowsze dodane gatunki')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : 'Najnowsze gatunki';
        $count = !empty($instance['count']) ? absint($instance['count']) : 5;
        $show_difficulty = !empty($instance['show_difficulty']);
        $show_origin = !empty($instance['show_origin']);
        
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        
        $recent_species = new WP_Query(array(
            'post_type'      => 'species',
            'posts_per_page' => $count,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish'
        ));
        
        if ($recent_species->have_posts()) {
            echo '<ul class="recent-species-list list-unstyled">';
            while ($recent_species->have_posts()) {
                $recent_species->the_post();
                
                $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                
                echo '<li class="recent-species-item d-flex mb-3 pb-3 border-bottom">';
                
                if (has_post_thumbnail()) {
                    echo '<div class="species-thumb me-3 flex-shrink-0">';
                    echo '<a href="' . get_permalink() . '">';
                    echo get_the_post_thumbnail(get_the_ID(), 'widget_thumb', array('class' => 'rounded'));
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="species-content flex-grow-1">';
                echo '<h6 class="mb-1"><a href="' . get_permalink() . '" class="text-decoration-none">' . get_the_title() . '</a></h6>';
                
                if ($scientific_name) {
                    echo '<em class="small text-muted d-block">' . esc_html($scientific_name) . '</em>';
                }
                
                if ($show_difficulty && $difficulty) {
                    $difficulty_labels = array(
                        'easy' => '🟢 Łatwa',
                        'medium' => '🟡 Średnia',
                        'hard' => '🔴 Trudna'
                    );
                    echo '<small class="d-block mt-1">' . $difficulty_labels[$difficulty] . '</small>';
                }
                
                if ($show_origin && $origin) {
                    echo '<small class="text-muted d-block">📍 ' . esc_html($origin) . '</small>';
                }
                
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p class="text-muted">Brak gatunków.</p>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Najnowsze gatunki';
        $count = !empty($instance['count']) ? $instance['count'] : 5;
        $show_difficulty = !empty($instance['show_difficulty']);
        $show_origin = !empty($instance['show_origin']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Tytuł:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>">Liczba gatunków:</label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" 
                   name="<?php echo $this->get_field_name('count'); ?>" type="number" 
                   step="1" min="1" max="10" value="<?php echo esc_attr($count); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_difficulty'); ?>" 
                   name="<?php echo $this->get_field_name('show_difficulty'); ?>" value="1" 
                   <?php checked($show_difficulty); ?>>
            <label for="<?php echo $this->get_field_id('show_difficulty'); ?>">Pokaż trudność</label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_origin'); ?>" 
                   name="<?php echo $this->get_field_name('show_origin'); ?>" value="1" 
                   <?php checked($show_origin); ?>>
            <label for="<?php echo $this->get_field_id('show_origin'); ?>">Pokaż pochodzenie</label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['count'] = (!empty($new_instance['count'])) ? absint($new_instance['count']) : 5;
        $instance['show_difficulty'] = !empty($new_instance['show_difficulty']);
        $instance['show_origin'] = !empty($new_instance['show_origin']);
        return $instance;
    }
}

class Carni24_Newsletter_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_newsletter',
            'Carni24: Newsletter',
            array('description' => 'Formularz subskrypcji newslettera')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : 'Newsletter';
        $description = !empty($instance['description']) ? $instance['description'] : 'Otrzymuj najnowsze informacje o roślinach mięsożernych!';
        
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        
        if ($description) {
            echo '<p class="newsletter-description">' . esc_html($description) . '</p>';
        }
        ?>
        
        <form class="newsletter-form" id="newsletter-widget-form">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Twój adres email" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="name" placeholder="Imię (opcjonalne)">
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-envelope me-2"></i>Subskrybuj
            </button>
            <div class="newsletter-message mt-2" style="display: none;"></div>
        </form>
        
        <script>
        document.getElementById('newsletter-widget-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            formData.append('action', 'carni24_newsletter_signup');
            formData.append('nonce', '<?php echo wp_create_nonce("carni24_frontend_nonce"); ?>');
            
            const messageDiv = form.querySelector('.newsletter-message');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-spinner"></i> Wysyłanie...';
            
            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.className = 'newsletter-message mt-2 alert alert-success';
                    messageDiv.textContent = data.data;
                    form.reset();
                } else {
                    messageDiv.className = 'newsletter-message mt-2 alert alert-danger';
                    messageDiv.textContent = data.data;
                }
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.className = 'newsletter-message mt-2 alert alert-danger';
                messageDiv.textContent = 'Wystąpił błąd. Spróbuj ponownie.';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-envelope me-2"></i>Subskrybuj';
            });
        });
        </script>
        
        <?php
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Newsletter';
        $description = !empty($instance['description']) ? $instance['description'] : 'Otrzymuj najnowsze informacje o roślinach mięsożernych!';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Tytuł:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Opis:</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" 
                      name="<?php echo $this->get_field_name('description'); ?>" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? sanitize_textarea_field($new_instance['description']) : '';
        return $instance;
    }
}

class Carni24_Social_Links_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_social_links',
            'Carni24: Linki społecznościowe',
            array('description' => 'Wyświetla ikony mediów społecznościowych')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        echo '<div class="social-links d-flex flex-wrap gap-3">';
        
        if ($facebook) {
            echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noopener" class="social-link text-primary">';
            echo '<i class="bi bi-facebook fs-4"></i>';
            echo '</a>';
        }
        
        if ($instagram) {
            echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noopener" class="social-link text-danger">';
            echo '<i class="bi bi-instagram fs-4"></i>';
            echo '</a>';
        }
        
        if ($youtube) {
            echo '<a href="' . esc_url($youtube) . '" target="_blank" rel="noopener" class="social-link text-danger">';
            echo '<i class="bi bi-youtube fs-4"></i>';
            echo '</a>';
        }
        
        if ($twitter) {
            echo '<a href="' . esc_url($twitter) . '" target="_blank" rel="noopener" class="social-link text-info">';
            echo '<i class="bi bi-twitter fs-4"></i>';
            echo '</a>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Tytuł:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>">Facebook URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" 
                   name="<?php echo $this->get_field_name('facebook'); ?>" type="url" 
                   value="<?php echo esc_attr($facebook); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('instagram'); ?>">Instagram URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" 
                   name="<?php echo $this->get_field_name('instagram'); ?>" type="url" 
                   value="<?php echo esc_attr($instagram); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>">YouTube URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" 
                   name="<?php echo $this->get_field_name('youtube'); ?>" type="url" 
                   value="<?php echo esc_attr($youtube); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>">Twitter URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" 
                   name="<?php echo $this->get_field_name('twitter'); ?>" type="url" 
                   value="<?php echo esc_attr($twitter); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['facebook'] = (!empty($new_instance['facebook'])) ? esc_url_raw($new_instance['facebook']) : '';
        $instance['instagram'] = (!empty($new_instance['instagram'])) ? esc_url_raw($new_instance['instagram']) : '';
        $instance['youtube'] = (!empty($new_instance['youtube'])) ? esc_url_raw($new_instance['youtube']) : '';
        $instance['twitter'] = (!empty($new_instance['twitter'])) ? esc_url_raw($new_instance['twitter']) : '';
        return $instance;
    }
}

function carni24_register_widgets() {
    register_widget('Carni24_Popular_Posts_Widget');
    register_widget('Carni24_Recent_Species_Widget');
    register_widget('Carni24_Newsletter_Widget');
    register_widget('Carni24_Social_Links_Widget');
}
add_action('widgets_init', 'carni24_register_widgets');

function carni24_show_sidebar() {
    if (is_front_page()) {
        return false;
    }
    
    $hide_sidebar_pages = array('kontakt', 'o-nas', 'polityka-prywatnosci');
    if (is_page($hide_sidebar_pages)) {
        return false;
    }
    
    if (is_page_template('page-full-width.php')) {
        return false;
    }
    
    return is_single() || is_archive() || is_home() || is_search() || is_404();
}

function carni24_get_sidebar_id() {
    if (is_singular('species')) {
        return 'sidebar-species';
    } elseif (is_single()) {
        return 'sidebar-post';
    } elseif (is_archive() || is_home() || is_search()) {
        return 'sidebar-archive';
    }
    
    return 'sidebar-post';
}

function carni24_display_sidebar() {
    if (carni24_show_sidebar()) {
        $sidebar_id = carni24_get_sidebar_id();
        
        if (is_active_sidebar($sidebar_id)) {
            echo '<aside class="sidebar col-lg-4">';
            dynamic_sidebar($sidebar_id);
            echo '</aside>';
        }
    }
}

function carni24_widget_text_shortcodes($text) {
    return do_shortcode($text);
}
add_filter('widget_text', 'carni24_widget_text_shortcodes');

function carni24_widget_categories_dropdown_args($cat_args) {
    $cat_args['show_count'] = 1;
    $cat_args['hierarchical'] = 1;
    $cat_args['depth'] = 2;
    return $cat_args;
}
add_filter('widget_categories_dropdown_args', 'carni24_widget_categories_dropdown_args');

function carni24_widget_tag_cloud_args($args) {
    $args['number'] = 20;
    $args['largest'] = 16;
    $args['smallest'] = 12;
    $args['unit'] = 'px';
    $args['format'] = 'flat';
    $args['separator'] = ' ';
    return $args;
}
add_filter('widget_tag_cloud_args', 'carni24_widget_tag_cloud_args');

function carni24_custom_search_widget() {
    ?>
    <div class="widget widget-search mb-4">
        <h4 class="widget-title mb-3">Wyszukiwarka</h4>
        <form class="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="input-group">
                <input type="search" class="form-control" placeholder="Czego szukasz..." 
                       name="s" value="<?php echo esc_attr(get_search_query()); ?>">
                <button class="btn btn-success" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
        
        <div class="search-suggestions mt-3">
            <small class="text-muted">Popularne wyszukiwania:</small>
            <div class="mt-2">
                <?php
                $popular_searches = array('dionaea', 'sarracenia', 'drosera', 'nepenthes', 'pinguicula');
                foreach ($popular_searches as $search) {
                    echo '<a href="' . esc_url(home_url('/?s=' . $search)) . '" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">' . ucfirst($search) . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}

function carni24_species_categories_widget() {
    $categories = get_terms(array(
        'taxonomy' => 'species_category',
        'hide_empty' => true,
        'number' => 10
    ));
    
    if (!empty($categories) && !is_wp_error($categories)) {
        ?>
        <div class="widget widget-species-categories mb-4">
            <h4 class="widget-title mb-3">Kategorie gatunków</h4>
            <ul class="list-unstyled">
                <?php foreach ($categories as $category): ?>
                    <li class="mb-2">
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                           class="d-flex justify-content-between align-items-center text-decoration-none">
                            <span><?php echo esc_html($category->name); ?></span>
                            <span class="badge bg-success"><?php echo $category->count; ?></span>
                        </a>
                        <?php if (!empty($category->description)): ?>
                            <small class="text-muted d-block mt-1"><?php echo esc_html(wp_trim_words($category->description, 10)); ?></small>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="mt-3">
                <a href="<?php echo esc_url(get_post_type_archive_link('species')); ?>" 
                   class="btn btn-outline-success btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Zobacz wszystkie gatunki
                </a>
            </div>
        </div>
        <?php
    }
}

function carni24_quick_stats_widget() {
    $species_count = wp_count_posts('species')->publish;
    $guides_count = wp_count_posts('guides')->publish;
    $posts_count = wp_count_posts('post')->publish;
    
    ?>
    <div class="widget widget-quick-stats mb-4">
        <h4 class="widget-title mb-3">Statystyki</h4>
        <div class="stats-grid">
            <div class="stat-item d-flex align-items-center mb-3 p-3 bg-light rounded">
                <div class="stat-icon me-3">
                    <i class="bi bi-flower1 text-success fs-3"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number fw-bold"><?php echo number_format($species_count); ?></div>
                    <div class="stat-label text-muted small">
                        <?php echo carni24_format_species_count($species_count); ?>
                    </div>
                </div>
            </div>
            
            <div class="stat-item d-flex align-items-center mb-3 p-3 bg-light rounded">
                <div class="stat-icon me-3">
                    <i class="bi bi-book text-primary fs-3"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number fw-bold"><?php echo number_format($guides_count); ?></div>
                    <div class="stat-label text-muted small">
                        <?php echo carni24_format_guides_count($guides_count); ?>
                    </div>
                </div>
            </div>
            
            <div class="stat-item d-flex align-items-center mb-3 p-3 bg-light rounded">
                <div class="stat-icon me-3">
                    <i class="bi bi-journal-text text-warning fs-3"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number fw-bold"><?php echo number_format($posts_count); ?></div>
                    <div class="stat-label text-muted small">
                        <?php echo carni24_format_posts_count($posts_count); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function carni24_featured_species_widget() {
    $featured_species = carni24_get_featured_posts(array(
        'post_type' => 'species',
        'posts_per_page' => 3,
        'meta_query' => array(
            array(
                'key' => '_is_featured',
                'value' => '1',
                'compare' => '='
            )
        )
    ));
    
    if ($featured_species->have_posts()) {
        ?>
        <div class="widget widget-featured-species mb-4">
            <h4 class="widget-title mb-3">⭐ Wyróżnione gatunki</h4>
            <div class="featured-species-list">
                <?php while ($featured_species->have_posts()): $featured_species->the_post(); ?>
                    <?php
                    $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                    $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                    ?>
                    <div class="featured-species-item card mb-3">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="card-img-top position-relative">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('widget_medium', array('class' => 'w-100', 'style' => 'height: 120px; object-fit: cover;')); ?>
                                </a>
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">⭐</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body p-3">
                            <h6 class="card-title mb-1">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                    <?php the_title(); ?>
                                </a>
                            </h6>
                            
                            <?php if ($scientific_name): ?>
                                <em class="text-muted small d-block mb-2"><?php echo esc_html($scientific_name); ?></em>
                            <?php endif; ?>
                            
                            <?php if ($difficulty): ?>
                                <?php
                                $difficulty_labels = array(
                                    'easy' => '🟢 Łatwa',
                                    'medium' => '🟡 Średnia', 
                                    'hard' => '🔴 Trudna'
                                );
                                ?>
                                <small class="badge bg-light text-dark">
                                    <?php echo $difficulty_labels[$difficulty]; ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            wp_reset_postdata();
        </div>
        <?php
    }
}

function carni24_auto_display_widgets() {
    if (is_singular('species') && is_active_sidebar('sidebar-species')) {
        add_action('carni24_species_sidebar', function() {
            carni24_species_categories_widget();
            carni24_featured_species_widget();
        });
    }
    
    if ((is_archive() || is_home()) && is_active_sidebar('sidebar-archive')) {
        add_action('carni24_archive_sidebar', function() {
            carni24_custom_search_widget();
            carni24_quick_stats_widget();
        });
    }
}
add_action('wp', 'carni24_auto_display_widgets');

function carni24_widget_styles() {
    ?>
    <style>
    .widget {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 1.5rem;
    }
    
    .widget-title {
        color: #198754;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
    
    .popular-posts-list .popular-post-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .social-links .social-link:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
    
    .stats-grid .stat-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    
    .featured-species-item .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s ease;
    }
    
    .search-suggestions .badge:hover {
        background-color: #198754 !important;
        color: white !important;
    }
    </style>
    <?php
}
add_action('wp_head', 'carni24_widget_styles');