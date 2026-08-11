<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $user;

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();

    wp_redirect(home_url('/connexion'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr" <?php language_attributes(); ?>>

<head>
    <meta charset="utf-8 <?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <header class="header  <?php if (is_front_page()) : ?> header--home <?php elseif (is_page('club-prive-accueil')) : ?> header--club <?php endif; ?>">
        <?php if (is_front_page()) : ?>
            <div class="header__video">
                <video autoplay muted loop>
                    <source src="<?php echo get_template_directory_uri(); ?>/assets/vid/Film_Presentation_CPSA_28.mp4" type="video/mp4">
                </video>
            </div>
        <?php endif; ?>
        <nav class="header__menu menu menu--accueil" id="mainNav" aria-hidden="undefined">
            <?php
            if (has_custom_logo()) :
                // $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'));
            ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="menu__logoLink">
                    <img class="menu__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/CPSA-clr.svg" alt="Logo">
                </a>
            <?php endif; ?>
            <a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                <i class="bi bi-list"></i>
            </a>

            <!-- <ul class="list-unstyled menu__list">
                <li class="menu__item"><a class="menu__link" href="catalogue.html">Véhicules</a></li>
                <li class="menu__item"><a class="menu__link" href="services.html">Services</a></li>
                <li class="menu__item"><a class="menu__link" href="">Contact</a></li>
            </ul> -->
            <?php
            if (isset($_SESSION['customer_id'])) {
                wp_nav_menu(array(
                    'menu'           => 'Membre',
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'     => 'list-unstyled menu__list',
                    'depth'          => 2,
                    'walker'         => new MyCustom_Walker_Nav_Menu()
                ));
            } else {
                wp_nav_menu(array(
                    'menu'           => 'Principal',
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'     => 'list-unstyled menu__list',
                    'depth'          => 1,
                    'walker'         => new MyCustom_Walker_Nav_Menu()
                ));
            }
            ?>
            <div class="menu__icons">
                <ul class="list-unstyled menu__social" aria-label="Social media">
                    <li>
                        <a class="menu__iconLink" href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/Facebook-wh.svg"
                                alt="Icon Facebook"></a>
                    </li>
                    <li>
                        <a class="menu__iconLink" href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/Instagram-wh.svg"
                                alt="Icon Instagram"></a>
                    </li>
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <li><a class="menu__iconLink" href="<?php echo esc_url(home_url('/contact/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/phone.svg" alt="Icon Contact"></a></li>
                    <?php else: ?>
                        <li><a class="menu__iconLink" href="<?php echo esc_url(home_url('/connexion/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/profile-wh.svg" alt="Icon Contact"></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <?php if (is_front_page()) : ?>
            <div class="header__hero">
                <h1 class="header__slogan">Visez le professionnalisme automobile</h1>
                <div class="header__search">
                    <!-- Composant select custom accessible. Valeur finale stockée dans l'input hidden `#models` -->
                    <div class="js-custom-select" data-value="">
                        <button class="cs-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">Rechercher par
                            marque</button>
                        <ul class="cs-list" role="listbox" aria-label="Choix de marque" aria-hidden="true">
                            <li role="option" data-value="">Rechercher par marque</li>
                            <li role="option" data-value="porsche">Porsche</li>
                            <li role="option" data-value="ferrari">Ferrari</li>
                            <li role="option" data-value="lamborghini">Lamborghini</li>
                            <li role="option" data-value="am">Aston Martin</li>
                            <li role="option" data-value="bentley">Bentley</li>
                            <li role="option" data-value="autre">Autre</li>
                        </ul>
                        <input type="hidden" name="models" id="models" value="">
                    </div>
                    <button class="myBtn myBtn-cs-submit" type="submit">Trouver</button>
                </div>
            </div>
        <?php elseif (is_page('club-prive-accueil')) : ?>
            <div class="header_deco">
                <a class="header__link" href="<?php echo home_url('/connexion?logout=1'); ?>">
                    <p class="header__quit">Se déconnecter</p>
                    <i class="bi bi-arrow-bar-right"></i>
                </a>
            </div>
            <div class="container header__top">
                <div class="header__infos">
                    <p class="header__name">
                        <?php echo esc_html($user->surname); ?>
                        <?php echo esc_html($user->name); ?>
                    </p>
                    <img class="header__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/<?php echo esc_html($user->icone_statut) ?>" alt="icone role">
                    <p class="header__roleName"><?php echo esc_html($user->nom_statut); ?></p>
                    <p class="header__mail"><?php echo esc_html($user->email); ?></p>
                </div>

            </div>
            <div class="header__hero header__hero--top">
                <h1 class="header__slogan header__slogan--club">Bienvenue dans le club privé de <span class="section__surlign">CPSA28</span></h1>
            </div>
            <div class="header__hero header__hero--bottom">
                <p class="header__subSlogan">Cette espace client vous permet d’accéder à de nombreux avantages !</p>
                <p class="header__thankYou">Merci d’être devenu un membre exclusif à CPSA28, grâce à vous, notre concession devient meilleure chaque jour !</p>
            </div>
        <?php endif; ?>
    </header>
    <main>