<?php
global $wpdb;

$membre_id = $_SESSION['customer_id'] ?? null; // Récupérer l'utilisateur connecté 
$statut_user = 'public'; // Les utilisateurs sans compte ne voient que les véhicules public


// On récupère le statut du membre connecté
if ($membre_id) {
    $table_membre = $table_membre = $wpdb->prefix . 'customer';
    $table_statut = $wpdb->prefix . 'statut';

    $statut_user = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT s.name
            FROM $table_membre m
            JOIN $table_statut s ON m.id_statut = s.id_statut
            WHERE m.id_customer = %d",
            $membre_id
        )
    );
}


// On transforme le statut en liste de visibilités autorisées afin d'afficher les bons véhicules exclusifs selon le statut
/**
 * Visiteur => véhciules public
 * Standard => public + standard
 * Premium => public + standard + premium
 * VIP => tous
 */
$visibilites_autorisees = ['public'];

if ($statut_user == 'Standard') {
    $visibilites_autorisees[] = 'standard';
}

if ($statut_user == 'Premium') {
    $visibilites_autorisees[] = 'standard';
    $visibilites_autorisees[] = 'premium';
}

if ($statut_user == 'VIP') {
    $visibilites_autorisees[] = 'standard';
    $visibilites_autorisees[] = 'premium';
    $visibilites_autorisees[] = 'vip';
}


?>


<section class="section section--list">
    <div class="container catalogue">
        <div class="catalogue__itemsCar list-unstyled">
            <?php
            // the query.
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $tax_query = array();

            // Si une marque est demandée via AJAX
            if (!empty($_POST['marque'])) {
                $marque_slug = sanitize_text_field($_POST['marque']);
                $tax_query = array(
                    array(
                        'taxonomy' => 'marque',
                        'field'    => 'slug',
                        'terms'    => $marque_slug,
                    )
                );
            }
            $args = array(
                'post_type' => 'vehicule',
                'posts_per_page' => 4,
                'paged' => $paged,
                'orderby' => 'title',
                'order' => 'ASC',
                'tax_query'      => $tax_query,
                // Va cibler la bonne valeur du tableau visibilites_autorisees selon la visibilité rentrée (key) puis afficher les bons véhicules selon le statut
                'meta_query' => array(
                    array(
                        'key' => 'visibilite_vehicule',
                        'value' => $visibilites_autorisees,
                        'compare' => 'IN'
                    )
                )
            );
            $the_query = new WP_Query($args);

            $max_pages = (int) $the_query->max_num_pages;
            if ($max_pages < 1) {
                $max_pages = 1;
            }

            $prev_page = max(1, $paged - 1);
            $next_page = min($max_pages, $paged + 1);

            if ($the_query->have_posts()) {
                $i = 0; // Compteur pour pouvoir inverser l'image et les infos pour les items pairs (cf ligne 96)
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $i++;
                    $marque = get_the_terms($post->ID, 'marque')[0];
                    $logo = get_field('logo', $marque);
            ?>

                    <article class="<?php echo 'catalogue__item' . ($i % 2 === 0 ? ' catalogue__item--reverse' : ''); ?>">
                        <a href=<?php the_permalink(); ?>>
                            <img class="catalogue__img" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo esc_html(get_field('nom_complet')); ?>">
                        </a>
                        <div class="catalogue__details">
                            <img class="catalogue__logo" src="<?php echo $logo; ?>" alt="Logo Porsche">
                            <h2 class="catalogue__title"><?php echo esc_html(get_field('nom_complet')); ?></h2>
                            <div class="catalogue__informations">
                                <div class="catalogue__info">
                                    <p class="catalogue__firstInfo">Catégorie</p>
                                    <p class="catalogue__secondInfo"><?php echo esc_html(get_field('categorie')); ?></p>
                                </div>
                                <div class="catalogue__info">
                                    <p class="catalogue__firstInfo">Énergie</p>
                                    <p class="catalogue__secondInfo"><?php echo esc_html(get_field('energie')); ?></p>
                                </div>
                                <div class="catalogue__info">
                                    <p class="catalogue__firstInfo">Transmission</p>
                                    <p class="catalogue__secondInfo"><?php echo esc_html(get_field('transmission')); ?></p>
                                </div>
                                <div class="catalogue__info">
                                    <p class="catalogue__firstInfo">Kilométrage</p>
                                    <p class="catalogue__secondInfo"><?php echo esc_html(get_field('energie')); ?></p>
                                </div>
                                <div class="catalogue__info">
                                    <p class="catalogue__firstInfo">Année</p>
                                    <p class="catalogue__secondInfo"><?php echo esc_html(get_field('mise_en_circulation')); ?></p>
                                </div>
                            </div>
                            <div class="catalogue__more">
                                <h2 class="catalogue__price"><?php echo esc_html(get_field('prix')); ?>€</h2>
                                <a class="catalogue__link" href="<?php the_permalink(); ?>">En savoir plus</a>
                            </div>
                        </div>
                    </article>

            <?php
                }
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <div class="section__btns section__btns--catalogue">
        <a class="myBtn myBtn--page" href="<?php echo esc_url(get_pagenum_link($prev_page)); ?>">Page précédente</a>

        <?php for ($p = 1; $p <= $max_pages; $p++) : ?>
            <a class="myBtn myBtn--page__number <?php echo ((int) $paged === $p) ? 'myBtn--active' : ''; ?>" href="<?php echo esc_url(get_pagenum_link($p)); ?>"><?php echo $p; ?></a>
        <?php endfor; ?>

        <a class="myBtn myBtn--page" href="<?php echo esc_url(get_pagenum_link($next_page)); ?>">Page suivante</a>
    </div>
</section>