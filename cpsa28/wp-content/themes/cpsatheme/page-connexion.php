<?php get_header(); ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['valid_login'])) {

    global $wpdb;

    $login = sanitize_email($_POST['email']);
    $mdp = $_POST['mdp'];
    $table = $wpdb->prefix . 'customer';

    $query = $wpdb->prepare(
        "SELECT * FROM $table WHERE email = %s",
        $login
    );

    $user = $wpdb->get_row($query);

    if ($user && wp_check_password($mdp, $user->password)) {

        $_SESSION['customer_id'] = $user->id_customer;
        $_SESSION['customer_email'] = $user->email;
        // $_SESSION['customer_statut'] = $user->id_statut;

        wp_redirect(home_url('/club-prive-accueil'));
        exit;
    } else {
        echo "Email ou mot de passe incorrect";
    }
}

if (isset($_POST['valid_signin'])) {

    global $wpdb;


    $saisie_valide = 1;

    $nom = htmlspecialchars(sanitize_text_field($_POST['nom']), ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars(sanitize_text_field($_POST['prenom']), ENT_QUOTES, 'UTF-8');
    $email = sanitize_email($_POST['email']);
    $mdp = $_POST['mdp'];
    $mdp_confirm = $_POST['mdp_confirm'];

    $table = $wpdb->prefix . 'customer';

    if (!champs_obligatoires_remplis([
        $nom,
        $prenom,
        $email,
        $mdp,
        $mdp_confirm
    ])) {
        $saisie_valide = 0;
        echo "Veuillez remplir tous les champs<br>";
    }

    if (!email_valide($email)) {
        $saisie_valide = 0;
        echo "Format d'email invalide<br>";
    }

    $verif_Email = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table WHERE email = %s",
            $email
        )
    );

    if ($wpdb->num_rows > 0) {
        $saisie_valide = 0;
        echo "Email déjà utilisé";
    }

    if ($mdp !== $mdp_confirm) {
        $saisie_valide = 0;
        echo "Les mots de passes ne correspondent pas";
    }

    if ($saisie_valide == 1) {
        $wpdb->insert(
            $table,
            [
                'id_statut' => null,
                'name' => $nom,
                'surname' => $prenom,
                'birthday' => null,
                'pays' => null,
                'ville' => null,
                'cp' => null,
                'phone' => null,
                'email' => $email,
                'password' => $mdp_confirm
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'],
        );

        wp_redirect(wp_get_referer());
        exit;
    }
}

?>

<section class="section section--connexion">
    <header class="section__header">
        <h2 class="section__title section__title--connexion"><?php the_title(); ?></h2>
    </header>
</section>
<section class="container section section--connexion">
    <div class="scene">
        <svg class="hexagon main" width="818" height="737" viewBox="0 0 818 737" fill="none">

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

        <svg class="hexagon deco top-left" width="177" height="196" viewBox="0 0 177 196" fill="none">
            <path d="M176.847 135.041L99.2512 195.32L10.8278 157.94L8.2016e-05 60.2797L77.5958 -5.96086e-06L166.019 37.3804L176.847 135.041Z" fill="#C52F38" />
        </svg>
        <svg class="hexagon deco top-right" xmlns="http://www.w3.org/2000/svg" width="185" height="191" viewBox="0 0 185 191" fill="none">
            <path d="M14.9905 153.592L1.61078 60.3003L78.6668 1.701L169.114 36.4804L182.494 129.772L105.439 188.371L14.9905 153.592Z" stroke="#C52F38" stroke-width="3" />
        </svg>
        <svg class="hexagon deco bottom-left" xmlns="http://www.w3.org/2000/svg" width="185" height="191" viewBox="0 0 185 191" fill="none">
            <path d="M14.9905 153.592L1.61078 60.3003L78.6668 1.701L169.114 36.4804L182.494 129.772L105.439 188.371L14.9905 153.592Z" stroke="#C52F38" stroke-width="3" />
        </svg>
        <svg class="hexagon deco bottom-right" width="177" height="196" viewBox="0 0 177 196" fill="none">
            <path d="M176.847 135.041L99.2512 195.32L10.8278 157.94L8.2016e-05 60.2797L77.5958 -5.96086e-06L166.019 37.3804L176.847 135.041Z" fill="#C52F38" />
        </svg>

        <div class="container formulaire formulaire--connexion">
            <div class="formulaire__header">
                <h2 class="formulaire__title">Bienvenue</h2>
                <p class="formulaire__intro">Inscrivez-vous ou connectez-vous pour profiter de toutes nos fonctionnalités.</p>
            </div>
            <div class="formulaire__btns">
                <div class="formulaire__btn-choice">
                    <a data-form="login" class="myBtn myBtn--connexion btnSwap btnLogin" href="">Connexion</a>
                    <a data-form="signin" class="myBtn myBtn--connexion btnSwap btnSignin" href="">Inscription</a>
                </div>
            </div>
            <div class="formulaire__separation">
                <div class="formulaire__left-line"></div>
                <p class="formulaire__text">OU</p>
                <div class="formulaire__right-line"></div>
            </div>
            <form class="formulaire__inputs formulaire__inputs--login" method="POST">
                <div class="formulaire__login" id="login">
                    <div class="formulaire__inputs-container formulaire__inputs-container--signin">
                        <div class="formulaire__top-inputs">
                            <input class="formulaire__input" type="email" id="email" name="email" placeholder="Email*" required>
                            <input class="formulaire__input" type="password" id="mdp" name="mdp" placeholder="Mot de passe*" required>
                        </div>
                    </div>
                    <div class="formulaire__valid">
                        <input class="myBtn myBtn--connexion" type="submit" value="Se connecter" name="valid_login">
                    </div>
                </div>

                <div class="formulaire__login" id="signin">
                    <div class="formulaire__inputs-container formulaire__inputs-container--signin">
                        <div class="formulaire__top-inputs">
                            <input class="formulaire__input" type="text" id="nom" name="nom" placeholder="Nom*" required>
                            <input class="formulaire__input" type="text" id="prenom" name="prenom" placeholder="Prénom*" required>
                        </div>
                        <div class="formulaire__bottom-inputs">
                            <input class="formulaire__input" type="email" id="email" name="email" placeholder="Email*" required>
                            <input class="formulaire__input" type="password" id="mdp" name="mdp" placeholder="Mot de passe*" required>
                            <input class="formulaire__input" type="password" id="mdp_confirm" name="mdp_confirm" placeholder="Confirmer le mot de passe*" required>
                        </div>
                    </div>
                    <div class="formulaire__valid">
                        <input onclick="confirmSignin()" class="myBtn myBtn--connexion" type="submit" value="Créer mon compte" style="width: 400px;" name="valid_signin">
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>