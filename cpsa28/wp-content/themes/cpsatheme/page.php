<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['customer_id'])) {
    wp_redirect(home_url('/connexion'));
    exit;
}

global $wpdb;

// $table = $wpdb->prefix . 'customer';
$table_statut = $wpdb->prefix . 'statut';
$table_privilege = $wpdb->prefix . 'privilege';
$table_demande = $wpdb->prefix . 'rechercheperso';
$table_etat = $wpdb->prefix . 'etat';
$table_responsable = $wpdb->prefix . 'equipe';
$table_proposition = $wpdb->prefix . 'propositionrecherche';
$table_rdv = $wpdb->prefix . 'rendezvous';
$table_motif = $wpdb->prefix . 'motif';
$table_types = $wpdb->prefix . 'types';

$user = get_current_customer();

// Afficher les privilèges
$query_priv = $wpdb->prepare(
    "SELECT * FROM $table_privilege WHERE id_statut = %d",
    $user->id_statut
);

$privileges = $wpdb->get_results($query_priv);

$query_recherche = $wpdb->prepare(
    "SELECT rp.*, 
    e.intitule as etat_intitule, 
    res.name as prenom_responsable, 
    res.surname as nom_responsable,
    COUNT(pr.id_proposition) as nbr_reponse
    FROM $table_demande rp 
    LEFT JOIN $table_etat e on rp.id_etat = e.id_etat 
    LEFT JOIN $table_responsable res on rp.id_responsable = res.id_membre
    LEFT JOIN $table_proposition pr on rp.id_rechercheperso = pr.id_rechercheperso
    WHERE rp.id_membre = %d
    GROUP BY rp.id_rechercheperso
    ORDER BY id_rechercheperso DESC",
    $_SESSION['customer_id']
);

$recherches = $wpdb->get_results($query_recherche);

$query_RDV = $wpdb->prepare(
    "SELECT rdv.*,
    res.name as prenom_responsable,
    res.surname as nom_responsable,
    m.intitule as intitule_motif,
    t.intitule as intitule_type
    FROM $table_rdv rdv
    LEFT JOIN $table_motif m on rdv.id_motif = m.id_motif
    LEFT JOIN $table_types t on rdv.id_type = t.id_type
    LEFT JOIN $table_responsable res on rdv.id_responsable = res.id_membre
    WHERE rdv.id_membre = %d
    ORDER BY id_rendezVous DESC",
    $_SESSION['customer_id']
);

$RDVs = $wpdb->get_results($query_RDV);

$query_motif = "SELECT * FROM $table_motif";
$query_type = "SELECT * FROM $table_types";

$motifs = $wpdb->get_results($query_motif);
$types = $wpdb->get_results($query_type);


?>

<?php get_header(); ?>

<section class="container section section--dashboard">
    <div class="container dashboard-buttons">
        <div class="dashboard-buttons__btn toggleBtn" data-div="profil">
            <i class="bi bi-person-lines-fill"></i>
            <h2 class="dashboard-buttons__intitule">Profil</h2>
        </div>
        <!-- Div qui contiendra la partie profil, apparait grace au js -->
        <a class="dashboard-buttons__btn" href="<?php echo esc_url(home_url('/club-prive-recherche-personnalisee/')); ?>">
            <i class="bi bi-search"></i>
            <h2 class="dashboard-buttons__intitule">Recherche personnalisée</h2>
        </a>
        <!-- Le bouton amène à la page du formulaire de recherche -> faut faire la page d'abord -->
        <div class="dashboard-buttons__btn toggleBtn" data-div="demande">
            <i class="bi bi-file-earmark-text"></i>
            <h2 class="dashboard-buttons__intitule">Mes demandes</h2>
        </div>
        <!-- Div qui contiendra la partie demande, apparait grace au js -->
        <div class="dashboard-buttons__btn toggleBtn" data-div="RDV">
            <i class="bi bi-clock"></i>
            <h2 class="dashboard-buttons__intitule">Mes rendez-vous</h2>
        </div>
        <!-- Div qui contiendra la partie rendez-vous, apparait grace au js -->
    </div>
    <div class="dashboard-buttons__content content" id="profil">
        <header class="section__header text">
            <h2 class="section__title section__title--service">Mon profil</h2>
        </header>
        <div class="text">
            <div class="profile-card">
                <div class="profile-card__left">
                    <p class="profile-card__name"><?php echo esc_html($user->surname) . ' ' . esc_html($user->name); ?></p>
                    <div class="profile-card__privilege">
                        <img class="header__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/VIP.svg" alt="icone role">
                        <p class="profile-card__privilege-name">VIP</p>
                    </div>
                </div>
                <div class="profile-card__right">
                    <div class="profile-card__header">
                        <p class="profile-card__title">Informations personnelles</p>
                        <button class="myBtn myBtn--active myBtn--profile-card" data-modal="modal-profile">Modifier</button>
                    </div>
                    <div class="profile-card__container">
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Prénom</p>
                            <p class="profile-card__label"><?php echo esc_html($user->surname); ?></p>
                        </div>
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Nom</p>
                            <p class="profile-card__label"><?php echo esc_html($user->name); ?></p>
                        </div>
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Date de naissance</p>
                            <p class="profile-card__label"><?php echo date('j/m/Y', strtotime($user->birthday)); ?></p>
                        </div>
                    </div>
                    <div class="profile-card__container">
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Pays</p>
                            <p class="profile-card__label"><?php echo esc_html($user->pays); ?></p>
                        </div>
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Ville</p>
                            <p class="profile-card__label"><?php echo esc_html($user->ville); ?></p>
                        </div>
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Code postal</p>
                            <p class="profile-card__label"><?php echo esc_html($user->cp); ?></p>
                        </div>
                    </div>
                    <div class="profile-card__container">
                        <div class="profile-card__infos profile-card__infos--mail">
                            <p class="profile-card__intitule">Adresse mail</p>
                            <p class="profile-card__label"><?php echo esc_html($user->email); ?></p>
                        </div>
                        <div class="profile-card__infos">
                            <p class="profile-card__intitule">Télephone</p>
                            <p class="profile-card__label"><?php echo esc_html($user->phone); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-profile" class="modal modal-dashboard">
        <div class="modal__content">
            <div class="close">
                <i class="bi bi-x-lg"></i>
            </div>
            <div class="modal__container-title">
                <h2 class="modal__title">Modifier mon profil</h2>
            </div>
            <form class="modal__container" method="POST" action="<?php echo esc_url(get_permalink()); ?>">
                <div class="modal_container-inputs">
                    <div class="modal__inputs">
                        <div class="modal__input">
                            <label class="modal__label" for="name">Nom</label>
                            <input class="modal__text" name="name" type="text" value="<?php echo esc_html($user->name); ?>">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="birthday">Naissance</label>
                            <input class="modal__text" name="birthday" type="date" value="<?php echo esc_html($user->birthday); ?>">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="ville">Ville</label>
                            <input class="modal__text" name="ville" type="text" value="<?php echo esc_html($user->ville); ?>">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="mail">Adresse email</label>
                            <input class="modal__text" name="mail" type="email" value="<?php echo esc_html($user->email); ?>">
                        </div>
                    </div>
                    <div class="modal__inputs">
                        <div class="modal__input">
                            <label class="modal__label" for="surname">Prénom</label>
                            <input class="modal__text" name="surname" type="text" value="<?php echo esc_html($user->surname); ?>">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="pays">Pays</label>
                            <input class="modal__text" name="pays" type="text" value="<?php echo esc_html($user->pays); ?>">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="cp">Code postal</label>
                            <input class="modal__text" name="cp" type="text" value="<?php echo esc_html($user->cp); ?>">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="tel">Numéro de téléphone</label>
                            <input class="modal__text" name="tel" type="tel" value="<?php echo esc_html($user->phone); ?>">
                        </div>
                    </div>
                </div>
                <input class="myBtn myBtn--modal" type="submit" name="valid_modif" value="Sauvegarder">
            </form>
        </div>
    </div>
    <div class="dashboard-buttons__content content" id="demande">
        <header class="section__header text">
            <h2 class="section__title section__title--service">Mes demandes de recherches</h2>
            <p class="section__chapo">
                Vous pouvez consulter ici, vos demandes de recherches personnalisées effectués. Elles sont classées du plus récents au plus anciens !
            </p>
        </header>
        <table class="wp-list-table widefat fixed striped table-content">
            <thead>
                <tr>
                    <th scope="col" class="manage-column">Ma demande</th>
                    <th scope="col" class="manage-column">Date de la demande</th>
                    <th scope="col" class="manage-column">Etat</th>
                    <th scope="col" class="manage-column">Conseiller</th>
                    <th scope="col" class="manage-column">Réponses apportées</th>
                </tr>
            </thead>
            <?php foreach ($recherches as $recherche): ?>
                <tbody>
                    <tr>
                        <td data-colname="Ma demande">
                            <?php echo esc_html($recherche->nom_vehicule); ?>
                        </td>
                        <td data-colname="Date de la demande">
                            <?php echo date_i18n('d/m/Y', strtotime($recherche->date_recherche)); ?>
                        </td>
                        <td data-colname="Etat">
                            <?php echo esc_html($recherche->etat_intitule); ?>
                        </td>
                        <td data-colname="Conseiller">
                            <?php echo esc_html($recherche->prenom_responsable . ' ' . $recherche->nom_responsable); ?>
                        </td>
                        <?php if ($recherche->nbr_reponse == 0) : ?>
                            <td data-colname="Réponses apportées">
                                Aucunes réponses pour le moment
                            </td>
                        <?php else: ?>
                            <td data-colname="Réponses apportées">
                                <?php echo esc_html($recherche->nbr_reponse); ?> véhicule(s) disponible
                            </td>
                        <?php endif ?>
                    </tr>
                </tbody>
            <?php endforeach ?>
        </table>
        <a class="myBtn myBtn--dashboard btn-link" href="<?php echo esc_url(home_url('/club-prive-recherche-personnalisee/')); ?>">Faire une recherche</a>
    </div>
    <div class="dashboard-buttons__content content" id="RDV">
        <header class="section__header text">
            <h2 class="section__title section__title--service">Mes rendez-vous</h2>
            <p class="section__chapo">
                Vous pouvez consulter ici, vos rendez-vous avec vos conseiller. Elles sont classées du plus récents au plus anciens !
            </p>
        </header>
        <table class="wp-list-table widefat fixed striped table-content">
            <thead>
                <tr>
                    <th scope="col" class="manage-column">Date</th>
                    <th scope="col" class="manage-column">Heure</th>
                    <th scope="col" class="manage-column">Conseiller</th>
                    <th scope="col" class="manage-column">Type</th>
                    <th scope="col" class="manage-column">Motif</th>
                </tr>
            </thead>
            <?php foreach ($RDVs as $unRDV): ?>
                <tbody>
                    <tr>
                        <td data-colname="Date">
                            <?php echo date_i18n('j F Y', strtotime($unRDV->date_rdv)); ?>
                        </td>
                        <td data-colname="Heure">
                            <?php echo date_i18n('H\hi', strtotime($unRDV->heure_rdv)); ?>
                        </td>
                        <td data-colname="Conseiller">
                            <?php echo esc_html($unRDV->prenom_responsable ?? '—'); ?>
                            <?php echo esc_html($unRDV->nom_responsable ?? '—'); ?>
                        </td>
                        <td data-colname="Type">
                            <?php echo esc_html($unRDV->intitule_type); ?>
                        </td>
                        <td data-colname="Motif">
                            <?php echo esc_html($unRDV->intitule_motif); ?>
                        </td>
                    </tr>
                </tbody>
            <?php endforeach ?>
        </table>
        <a class="myBtn myBtn--dashboard btn-link" data-modal="modal-RDV">Réserver un rendez-vous</a>
    </div>
    <div id="modal-RDV" class="modal modal-dashboard">
        <div class="modal__content">
            <div class="close">
                <i class="bi bi-x-lg"></i>
            </div>
            <div class="modal__container-title">
                <h2 class="modal__title">Faire une réservation</h2>
            </div>
            <form class="modal__container" method="POST" action="<?php echo esc_url(get_permalink()); ?>">
                <div class="modal_container-inputs modal_container-inputs--RDV">
                    <div class="modal__inputs">
                        <div class="modal__input">
                            <label class="modal__label" for="date_rdv">Date de la réservation</label>
                            <input class="modal__text" name="date_rdv" type="date">
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="heure_rdv">Heure de la réservation</label>
                            <input class="modal__text" name="heure_rdv" type="time">
                        </div>
                    </div>
                    <div class="modal__inputs">
                        <div class="modal__input">
                            <label class="modal__label" for="id_motif">Motif</label>
                            <select class="modal__text" name="id_motif">
                                <option value="">-- Sélectionnez un motif -- </option>
                                <?php foreach ($motifs as $motif): ?>
                                    <option
                                        value="<?php echo esc_attr($motif->id_motif); ?>">
                                        <?php echo esc_html($motif->intitule); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="modal__input">
                            <label class="modal__label" for="id_type">Type de rendez-vous</label>
                            <select class="modal__text" name="id_type">
                                <option value="">-- Sélectionnez un type -- </option>
                                <?php foreach ($types as $type): ?>
                                    <option
                                        value="<?php echo esc_attr($type->id_type); ?>">
                                        <?php echo esc_html($type->intitule); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input class="myBtn myBtn--modal" type="submit" name="valid_rdv" value="Réserver">
            </form>
        </div>
    </div>
    <div class="container section__privilege">
        <p class="section__texte section__texte--memberRole">Vous êtes un membre <span class="section__surlign"><?php echo esc_html($user->nom_statut); ?></span>, découvrez vos privilèges :</p>
        <div class="section__privilege-top">
            <!-- <p class="section__texte">Offres exclusifs sur nos services</p>
            <a class="section__texte section__texte-link" href="<?php echo esc_url(home_url('/club-prive-evenements/')); ?>">Invitations à des évènements privés</a> Liens vers la page évènement -->

            <?php foreach ($privileges as $privilege): ?>
                <p class="section__texte"> <?php echo esc_html($privilege->intitule) . ' '; ?> </p>
            <?php endforeach; ?>
        </div>
        <!-- <p class="section__texte">Accès prioritaires à des véhicules rares et en avant-première</p> --> <!-- Liens vers une page de véhicule exclusif -->
    </div>
</section>
<?php get_footer(); ?>