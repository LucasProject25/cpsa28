<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 	<section class="section section--detail__vehicule">
            <header class="section__header section__header--vehicule">
                <h2 class="section__title"><?php the_title(); ?></h2>
                <div class="section__btn">
                    <a class="myBtn myBtn--back" href="<?php the_field('retour') ?>">Retour</a>
                </div>
            </header>
            <div class="carousel">
                <div class="container" style="margin-bottom: 20px;">
                    <?php
                    // Affiche dynamiquement les images présentes dans les champs ACF image_1 .. image_5
                    for ($i = 1; $i <= 10; $i++) {
                        $image = get_field('image_' . $i);
                        if ($image) {
                            $size = 'large';
                            ?>
                            <div>
                                <div class="carousel__item carousel__item--detail__vehicule">
                                    <div>
                                        <?php echo wp_get_attachment_image( $image, $size ); ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="container slider_nav nav">
                    <?php
                    // Miniatures de navigation — mêmes champs que ci-dessus, affichage si présent
                    for ($i = 1; $i <= 10; $i++) {
                        $image = get_field('image_' . $i);
                        if ($image) {
                            $size = 'full';
                            ?>
                            <div>
                                <div class="carousel__nav-item">
                                    <?php echo wp_get_attachment_image( $image, $size, false, array('class' => 'carousel__nav-image') ); ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
			<?php
                $marque = get_the_terms($post->ID, 'marque')[0];
                $logo = get_field('logo', $marque);
			?>
            <div class="infos-generales">
                <div class="infos-generales__logo">
                    <img class="infos-generales__img-logo" src="<?php echo $logo; ?>" alt="Logo Porsche">
                </div>
                <div class="infos-generales__lines">
                    <div class="infos-generales__line">
                        <div class="infos-generales__line-item infos-generales__line-item--start">
                            <p class="infos-generales__label">Modèle</p>
                            <p class="infos-generales__intitule infos-generales__intitule--marque"><?php echo esc_html(get_field('modele')); ?></p>
                        </div>
                        <div class="infos-generales__line-item infos-generales__line-item--end">
                            <p class="infos-generales__label">Catégorie</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('categorie')); ?></p>
                        </div>
                    </div>
                    <div class="infos-generales__line">
                        <div class="infos-generales__line-item infos-generales__line-item--start">
                            <p class="infos-generales__label">Kilomètrage</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('kilometrage')); ?> km</p>
                        </div>
                        <div class="infos-generales__line-item infos-generales__line-item--end">
                            <p class="infos-generales__label">Mise en circulation</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('mise_en_circulation')); ?></p>
                        </div>
                    </div>
                    <div class="infos-generales__line">
                        <div class="infos-generales__line-item infos-generales__line-item--start">
                            <p class="infos-generales__label">Énergie</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('energie')); ?></p>
                        </div>
                        <div class="infos-generales__line-item infos-generales__line-item--end">
                            <p class="infos-generales__label">Transmission</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('transmission')); ?></p>
                        </div>
                    </div>
                    <div class="infos-generales__line">
                        <div class="infos-generales__line-item infos-generales__line-item--start">
                            <p class="infos-generales__label">Puissance fiscale</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('puissance_fiscale')); ?> cv</p>
                        </div>
                        <div class="infos-generales__line-item infos-generales__line-item--end">
                            <p class="infos-generales__label">Puissance DNI</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('puissance_din')); ?> ch</p>
                        </div>
                    </div>
                    <div class="infos-generales__line">
                        <div class="infos-generales__line-item infos-generales__line-item--start">
                            <p class="infos-generales__label">Couleur extérieur</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('couleur_exterieur')); ?></p>
                        </div>
                        <div class="infos-generales__line-item infos-generales__line-item--end">
                            <p class="infos-generales__label">Couleur intérieur</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('couleur_interieur')); ?></p>
                        </div>
                    </div>
                    <div class="infos-generales__line">
                        <div class="infos-generales__line-item infos-generales__line-item--start">
                            <p class="infos-generales__label">Portes</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('portes')); ?></p>
                        </div>
                        <div class="infos-generales__line-item infos-generales__line-item--end">
                            <p class="infos-generales__label">Garantie</p>
                            <p class="infos-generales__intitule"><?php echo esc_html(get_field('garantie')); ?> mois</p>
                        </div>
                    </div>
                </div>
                <h2 class="infos-generales__prix"><?php echo esc_html(get_field('prix')); ?> €</h2>
            </div>
        </section>
		<section class="section section--equip-option">
            <header class="section__header">
                <h2 class="section__title">Équipement et options</h2>
            </header>
            <div class="container option-buttons">
                <div class="option-buttons__first-column">
                    <div class="option-buttons__btn option-buttons__btn--mecanique" data-modal="modal-meca">
                        <h2 class="option-buttons__intitule">Mécanique Performances</h2>
                    </div>
                    <div id="modal-meca" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-gear-fill"></i>
                                <h2 class="modal__title">Mécanique & Performances</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('mecanique_performance') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="option-buttons__btn option-buttons__btn--exterieur" data-modal="modal-ext">
                        <h2 class="option-buttons__intitule">Extérieur</h2>
                    </div>
                    <div id="modal-ext" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-car-front-fill"></i>
                                <h2 class="modal__title">Extérieur</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('exterieur') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="option-buttons__btn option-buttons__btn--interieur" data-modal="modal-int">
                        <h2 class="option-buttons__intitule">Intérieur Finition</h2>
                    </div>
                    <div id="modal-int" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-palette-fill"></i>
                                <h2 class="modal__title">Intérieur & Finition</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('interieur_finition') ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="option-buttons__second-column">
                    <div class="option-buttons__btn option-buttons__btn--protection" data-modal="modal-protec">
                        <h2 class="option-buttons__intitule">Protection Antivol</h2>
                    </div>
                    <div id="modal-protec" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-lock-fill"></i>
                                <h2 class="modal__title">Protection & Antivol</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('protection_antivol') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="option-buttons__btn option-buttons__btn--document" data-modal="modal-doc">
                        <h2 class="option-buttons__intitule">Documents Garantie</h2>
                    </div>
                    <div id="modal-doc" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <h2 class="modal__title">Documents & Garantie</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('documents_garantie') ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="option-buttons__third-column">
                    <div class="option-buttons__btn option-buttons__btn--confort" data-modal="modal-conf">
                        <h2 class="option-buttons__intitule">Confort Technologie</h2>
                    </div>
                    <div id="modal-conf" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-lightbulb-fill"></i>
                                <h2 class="modal__title">Confort & Technologie</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('confort_technologie') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="option-buttons__btn option-buttons__btn--audio" data-modal="modal-aud">
                        <h2 class="option-buttons__intitule">Audio Multimédia</h2>
                    </div>
                    <div id="modal-aud" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-volume-up-fill"></i>
                                <h2 class="modal__title">Audio & Multimédia</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('audio_multimedia') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="option-buttons__btn option-buttons__btn--securite" data-modal="modal-secu">
                        <h2 class="option-buttons__intitule">Sécurité</h2>
                    </div>
                    <div id="modal-secu" class="option-buttons__modal modal">
                        <div class="modal__content">
                            <div class="close">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="modal__container-title">
                                <i class="bi bi-shield-fill"></i>
                                <h2 class="modal__title">Sécurité</h2>
                            </div>
                            <div class="modal__container-text">
                                <?php echo wp_kses_post ( get_field('securite') ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
 <?php endwhile;
endif; ?>

<?php get_footer(); ?>