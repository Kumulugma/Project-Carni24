<?php
// wp-content/themes/carni24/template-parts/homepage/searchbar.php
// Zaktualizowana wersja z poprawną odmianą liczebników i bez komentarzy
?>

<section id="search" class="bg-light border-bottom px-5">
    <div class="nav-scroller py-1">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 p-2">
            
            <!-- Statystyka gatunków -->
            <div class="col fadeInLeft animate">
                <div class='row search-box'>
                    <div class='col-2'>
                        <div class="droplet mx-auto justify-content-center d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"/>
                            </svg>
                        </div>
                    </div>
                    <div class='col-10'>
                        <h4 class='mb-0 mt-2'><?= carni24_species_count() ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Statystyka zdjęć -->
            <div class="col fadeInLeft animate">
                <div class='row search-box'>
                    <div class='col-2'>
                        <div class="droplet mx-auto justify-content-center d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                                <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                            </svg>
                        </div>
                    </div>
                    <div class='col-10'>
                        <h4 class='mb-0 mt-2'><?= carni24_images_count() ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Statystyka wpisów -->
            <div class="col fadeInLeft animate">
                <div class='row search-box'>
                    <div class='col-2'>
                        <div class="droplet mx-auto justify-content-center d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-richtext" viewBox="0 0 16 16">
                                <path d="M7.5 3.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm-.861 1.542l1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047L11 4.75V7a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 7v-.5s1.54-1.274 1.639-1.208zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                            </svg>
                        </div>
                    </div>
                    <div class='col-10'>
                        <h4 class='mb-0 mt-2'><?= carni24_posts_count() ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Formularz wyszukiwania -->
            <div class="col">
                <form class="d-flex" method="get" action="<?= esc_url(home_url('/')) ?>">
                    <?php wp_nonce_field('search_form', 'search_nonce'); ?>
                    <input class="form-control w-75 me-2" 
                           name="s" 
                           type="search" 
                           placeholder="<?= esc_attr(carni24_get_option('search_placeholder', 'Wpisz czego poszukujesz...')) ?>" 
                           aria-label="Szukaj"
                           value="<?= esc_attr(get_search_query()) ?>">
                    <button class="btn btn-outline-success" type="submit">Szukaj</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.search-box {
    padding: 1rem;
    background: white;
    border-radius: 8px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.search-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.droplet {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 50%;
    color: white;
}

.search-box h4 {
    color: #333;
    font-weight: 600;
    font-size: 1rem;
}

.fadeInLeft.animate {
    animation: fadeInLeft 0.6s ease-out;
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsywne ustawienia */
@media (max-width: 768px) {
    .search-box {
        margin-bottom: 0.5rem;
        padding: 0.75rem;
    }
    
    .search-box h4 {
        font-size: 0.9rem;
    }
    
    .droplet {
        width: 35px;
        height: 35px;
    }
    
    .droplet svg {
        width: 14px;
        height: 14px;
    }
}

@media (max-width: 576px) {
    #search .row {
        padding: 1rem !important;
    }
    
    .search-box {
        text-align: center;
    }
    
    .search-box .row {
        justify-content: center;
        align-items: center;
    }
}
</style>