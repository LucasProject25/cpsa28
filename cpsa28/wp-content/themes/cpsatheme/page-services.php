<?php get_header(); ?>

<section class="container section section--service">
    <header class="section__header">
        <h2 class="section__title section__title--service"><?php the_title(); ?></h2>
        <p class="section__chapo">
            <?php echo get_the_excerpt(); ?>
        </p>
    </header>
    <div class="services-list">
        <div class="services-list__first-column">
            <div class="services-list__item">
                <i class="bi bi-person-check services-list__icon"></i>
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_1')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_1')); ?>
                </p>
            </div>
            <div class="services-list__item">
                <img class="services-list__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/service-debosselage.svg" alt="icone débosselage">
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_2')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_2')); ?>
                </p>
            </div>
            <div class="services-list__item">
                <img class="services-list__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/service-concierge.svg" alt="icone concierge">
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_3')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_3')); ?>
                </p>
            </div>
        </div>
        <div class="services-list__mid-column">
            <div class="services-list__item">
                <i class="bi bi-wrench-adjustable services-list__icon"></i>
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_4')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_4')); ?>
                </p>
            </div>
            <div class="services-list__item">
                <img class="services-list__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/service-sellerie.svg" alt="icone sellerie">
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_5')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_5')); ?>
                </p>
            </div>
        </div>
        <div class="services-list__last-column">
            <div class="services-list__item">
                <i class="bi bi-patch-check services-list__icon"></i>
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_6')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_6')); ?>
                </p>
            </div>
            <div class="services-list__item">
                <img class="services-list__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/service-nettoyage.svg" alt="icone nettoyage">
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_7')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_7')); ?>
                </p>
            </div>
            <div class="services-list__item">
                <i class="bi bi-search services-list__icon"></i>
                <h2 class="services-list__titre"><?php echo esc_html(get_field('titre_service_8')); ?></h2>
                <p class="services-list__description">
                    <?php echo esc_html(get_field('contenue_service_8')); ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="container section section--garantie">
    <div class="section__paragraph section__paragraph--garantie">
        <p class="section__texte section__texte--garantie">
            <?php echo esc_html(get_field('contenue_service_9')); ?>
        </p>
        <div class="section__container-text">
            <div class="section__container-text__first">
                <img class="services-list__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/garantie-ecoute.svg" alt="icone ecoute">
                <p class="section__text section__texte--garantie section__container-text__width">
                    <?php echo esc_html(get_field('contenue_service_10')); ?>
                </p>
            </div>
            <div class="section__container-text__second">
                <img class="services-list__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/garantie-souscription.svg" alt="icone souscription">
                <p class="section__text section__texte--garantie section__container-text__width">
                    <?php echo esc_html(get_field('contenue_service_11')); ?>
                </p>
            </div>
        </div>
        <div class="section__btn section__btn--service">
            <?php the_content(); ?>
            <a class="myBtn myBtn--service" href="<?php the_field('lien_vers_contact') ?>">Contactez-nous</a>
        </div>
    </div>
</section>
<section class="container section section--partners">
    <header class="section__header">
        <h2 class="section__title">Nos partenaires distributeurs</h2>
    </header>
    <div class="section--partners__logos">
        <a href="<?php echo esc_attr(get_field('url_partenaire_1')); ?>">

            <?php
            $image = get_field('image_partenaire_1');
            $size = 'medium';
            if ($image) {
                echo wp_get_attachment_image($image, $size, "", array('class' => 'section--partners__img'));
            }
            ?>
        </a>
        <a href="<?php echo esc_attr(get_field('url_partenaire_2')); ?>">
            <?php
            $image = get_field('image_partenaire_2');
            $size = 'medium';
            if ($image) {
                echo wp_get_attachment_image($image, $size, "", array('class' => 'section--partners__img'));
            }
            ?>
        </a>
        <a href="<?php echo esc_attr(get_field('url_partenaire_3')); ?>">
            <?php
            $image = get_field('image_partenaire_3');
            $size = 'medium';
            if ($image) {
                echo wp_get_attachment_image($image, $size, "", array('class' => 'section--partners__img'));
            }
            ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>