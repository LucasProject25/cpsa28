<?php
/*
Template Name: Catalogue de véhicule
*/
?>
<?php get_header(); ?>

<section class="section section--catalogue">
    <header class="section__header">
        <h2 class="section__title"><?php the_title(); ?></h2>
    </header>
    <div class="container filtre">
        <?php
        $args = array(
            'post_type' => 'vehicule',
            'tax_query' => array(
                array(
                    'taxonomy' => 'marque',
                    'field' => 'name'
                ),
            ),
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $logo = get_field('logo');
            }
        }
        ?>
        <ul class="list-unstyled filtre__itemsLogo--firstLine">
            <li class="filtre__item">
                <a class="filtre__link" href="#" data-brand="porsche">
                    <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/porsche-logo.png" alt="Logo Porsche">
                </a>
            </li>
            <li class="filtre__item">
                <a class="filtre__link" href="#" data-brand="ferrari">
                    <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/scuderia_ferrari-logo.png" alt="Logo Ferrari">
                </a>
            </li>
            <li class="filtre__item">
                <a class="filtre__link" href="#" data-brand="lamborghini">
                    <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/lamborghini-logo.png" alt="Logo Lamborghini">
                </a>
            </li>
            <li class="filtre__item">
                <a class="filtre__link" href="#" data-brand="aston_martin">
                    <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/aston_martin-logo.png" alt="Logo Aston Martin">
                </a>
            </li>
            <li class="filtre__item">
                <a class="filtre__link" href="#" data-brand="bentley">
                    <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/bentley-logo.png" alt="Logo Bentley">
                </a>
            </li>
        </ul>
        <ul class="filtre__itemsLogo--secondLine list-unstyled">
            <li class="filtre__item filtre__item--autre"><a class="filtre__link" href="#" data-brand="autre">
                    <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/autre-logo.png" alt="Logo autres marques">
                </a>
                <p class="filtre__text">Autres modèles</p>
            </li>
        </ul>
    </div>
    <div class="container filtre filtre--mobile">
        <ul class="filtre__itemsLogo--firstLine list-unstyled">
            <li class="filtre__item"><a class="filtre__link" href=""></a>
                <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/porsche-logo.png" alt="Logo Porsche">
            </li>
            <li class="filtre__item"><a class="filtre__link" href=""></a>
                <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/scuderia_ferrari-logo.png" alt="Logo Ferrari">
            </li>
            <li class="filtre__item"><a class="filtre__link" href=""></a>
                <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/lamborghini-logo.png" alt="Logo Lamborghini">
            </li>
        </ul>
        <ul class="filtre__itemsLogo--secondLine list-unstyled">
            <li class="filtre__item"><a class="filtre__link" href=""></a>
                <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/aston_martin-logo.png" alt="Logo Aston Martin">
            </li>
            <li class="filtre__item"><a class="filtre__link" href=""></a>
                <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/bentley-logo.png" alt="Logo Bentley">
            </li>
            <li class="filtre__item filtre__item--autre"><a class="filtre__link" href=""></a>
                <img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/autre-logo.png" alt="Logo autres marques">
            </li>
        </ul>
    </div>
</section>

<div id="resultats-vehicules">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            get_template_part('template-parts/vehicule', 'vehicule');
        endwhile;
    endif;
    ?>
</div>

<?php get_footer(); ?>