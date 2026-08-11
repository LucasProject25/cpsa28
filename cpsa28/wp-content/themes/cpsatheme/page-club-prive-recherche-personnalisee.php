<?php get_header(); ?>

<?php
if (!isset($_SESSION['customer_id'])) {

    wp_redirect(home_url('/connexion'));
    exit;
}

?>

<section class="section section--recherche">
    <header class="section__header">
        <h2 class="section__title"><?php the_title(); ?></h2>
        <div class="section__chapo section__chapo--recherche">
            <p>Vous cherchez un véhicule en particulier ?</p>
            <p>Déposez votre recherche de voiture en complétant le formulaire ci-dessous. Nous vous recontacterons lorsque nous disposerons d'un véhicule correspondant à votre recherche.</p>
        </div>
    </header>
</section>
<section class="section section__form-champs">
    <p class="section__texte section__texte--form-champs">Les champs indiqués par un astérisque (*) sont obligatoires.</p>
    <p class="section__texte section__texte--form-champs">Précisez plus de critères, les options du véhicule recherché ou laissez nous un commentaire pour le champ indiqué par deux astérisques (**).</p>
</section>
<section class="container section section--formulaire-recherche">
    <div class="scene">
        <svg class="hexagon main recherche" width="818" height="737" viewBox="0 0 818 737" fill="none">

            <path d="M388.009 4.511C401.192 -1.50366 416.335 -1.50366 429.518 4.51099L788.282 168.198C806.097 176.326 817.528 194.106 817.528 213.687V522.396C817.528 541.978 806.097 559.757 788.282 567.885L429.518 731.572C416.335 737.587 401.192 737.587 388.009 731.572L29.2452 567.885C11.4303 559.757 -0.000305176 541.978 -0.000305176 522.396V213.687C-0.000305176 194.106 11.4303 176.326 29.2452 168.198L388.009 4.511Z"
                fill="url(#pattern0)" />
            <path d="M388.009 4.511C401.192 -1.50366 416.335 -1.50366 429.518 4.51099L788.282 168.198C806.097 176.326 817.528 194.106 817.528 213.687V522.396C817.528 541.978 806.097 559.757 788.282 567.885L429.518 731.572C416.335 737.587 401.192 737.587 388.009 731.572L29.2452 567.885C11.4303 559.757 -0.000305176 541.978 -0.000305176 522.396V213.687C-0.000305176 194.106 11.4303 176.326 29.2452 168.198L388.009 4.511Z"
                fill="black" fill-opacity="0.75" />

            <defs>
                <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                    <use href="#image0" transform="matrix(0.000359206 0 0 0.000454545 -0.176027 0)" />
                </pattern>

                <image id="image0"
                    width="3764"
                    height="2200"
                    preserveAspectRatio="none"
                    href="<?php echo get_template_directory_uri(); ?>/assets/img/Club.png" />
            </defs>

        </svg>

        <svg class="hexagon deco top-left recherche" width="177" height="196" viewBox="0 0 177 196" fill="none">
            <path d="M176.847 135.041L99.2512 195.32L10.8278 157.94L8.2016e-05 60.2797L77.5958 -5.96086e-06L166.019 37.3804L176.847 135.041Z" stroke="#C52F38" stroke-width="3" />
        </svg>
        <svg class="hexagon deco top-right recherche" xmlns="http://www.w3.org/2000/svg" width="185" height="191" viewBox="0 0 185 191" fill="none">
            <path d="M14.9905 153.592L1.61078 60.3003L78.6668 1.701L169.114 36.4804L182.494 129.772L105.439 188.371L14.9905 153.592Z" fill="#C52F38" />
        </svg>
        <svg class="hexagon deco bottom-left recherche" xmlns="http://www.w3.org/2000/svg" width="185" height="191" viewBox="0 0 185 191" fill="none">
            <path d="M14.9905 153.592L1.61078 60.3003L78.6668 1.701L169.114 36.4804L182.494 129.772L105.439 188.371L14.9905 153.592Z" fill="#C52F38" />
        </svg>
        <svg class="hexagon deco bottom-right recherche" width="177" height="196" viewBox="0 0 177 196" fill="none">
            <path d="M176.847 135.041L99.2512 195.32L10.8278 157.94L8.2016e-05 60.2797L77.5958 -5.96086e-06L166.019 37.3804L176.847 135.041Z" stroke="#C52F38" stroke-width="3" />
        </svg>

        <div class="container formulaire formulaire--recherche">
            <form class="formulaire__inputs formulaire__inputs--recherche" method="POST" action="<?php echo esc_url(home_url('/club-prive-accueil/')); ?>">
                <input class="formulaire__input" type="text" name="vehicule_name" placeholder="Nom du véhicule*" required>
                <div class="formulaire__inputs-container formulaire__inputs-container--recherche">
                    <div class="formulaire__inputs-sub-container">
                        <input class="formulaire__input" type="text" name="marque" placeholder="Marque">
                        <input class="formulaire__input" type="text" name="transmission" placeholder="Transmission">
                        <input class="formulaire__input" type="text" name="energie" placeholder="Energie">
                        <input class="formulaire__input" type="text" name="categorie" placeholder="Catégorie">
                    </div>
                    <div class="formulaire__inputs-sub-container">
                        <input class="formulaire__input" type="text" name="modele" placeholder="Modèle">
                        <input class="formulaire__input" type="text" name="kilometrage" placeholder="Kilométrage">
                        <input class="formulaire__input" type="date" name="annee" placeholder="Année">
                        <input class="formulaire__input" type="text" name="prix" placeholder="Prix*" required>
                    </div>
                </div>
                <textarea class="formulaire__message " name="info_sup" rows="5">Informations supplémentaires**</textarea>
                <div class="formulaire__valid">
                    <input class="myBtn myBtn--recherche" type="submit" name="valid_recherche" value="Envoyer">
                </div>
            </form>
        </div>
    </div>
</section>
<?php get_footer(); ?>