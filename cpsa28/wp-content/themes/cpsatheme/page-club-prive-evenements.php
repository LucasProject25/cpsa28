<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['customer_id'])) {

    wp_redirect(home_url('/connexion'));
    exit;
}

$user = get_current_customer();

?>

<?php get_header(); ?>

<section class="section section--event">
    <header class="section__header section__header--event">
        <div class="container header__top header__top--event">
            <div class="header__infos">
                <p class="header__name">
                    <?php echo esc_html($user->surname); ?>
                    <?php echo esc_html($user->name); ?>
                </p>
                <img class="header__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/<?php echo esc_html($user->icone_statut) ?>" alt="icone role">
                <p class="header__roleName"><?php echo esc_html($user->nom_statut); ?></p>
                <p class="header__mail"><?php echo esc_html($user->email); ?></p>
            </div>
            <h2 class="section__title"><?php the_title(); ?></h2>
            <a class="header__link" href="<?php echo esc_url(home_url('/connexion/')); ?>">
                <p class="header__quit">Se déconnecter</p>
                <i class="bi bi-arrow-bar-right"></i>
            </a>
        </div>
        <div class="header__btn">
            <a class="myBtn" href="<?php echo esc_url(home_url('/club-prive-accueil/')); ?>">Retour</a>
        </div>
    </header>
</section>
<section class="section section--event-list">
    <div class="events">
        <div class="container events__portrait">
            <div class="events__name">
                <p class="events__title"><?php echo esc_html(get_field('titre_evenement_1')); ?></p>
                <?php
                $image = get_field('image_evenement_1');
                $size = 'large'; // (thumbnail, medium, large, full or custom size)
                if ($image) {
                    echo wp_get_attachment_image($image, $size);
                }
                ?>
                <!-- <img class="events__img" src="<?php echo get_template_directory_uri(); ?>/assets/img/Macchina_e_caffe.avif" alt="Macchina é Caffé"> -->
            </div>
            <div class="events__descriptions">
                <?php the_field('text_evenement_1'); ?>
            </div>
        </div>
        <div class="container events__paysage">
            <div class="events__name events__name--paysage">
                <p class="events__title"><?php echo esc_html(get_field('titre_evenement_2')); ?></p>
                <?php
                $image = get_field('image_evenement_2');
                $size = 'large'; // (thumbnail, medium, large, full or custom size)
                if ($image) {
                    echo wp_get_attachment_image($image, $size);
                }
                ?>
                <!-- <img class="events__img events__img--paysage" src="<?php echo get_template_directory_uri(); ?>/assets/img/24h_LeMans.avif" alt="24h du Mans 2026"> -->
            </div>
            <div class="events__descriptions events__descriptions--paysage">
                <?php the_field('text_evenement_2'); ?>
            </div>
        </div>
    </div>
    <button class="myBtn">Voir plus d'évènements</button>
</section>
<?php get_footer(); ?>