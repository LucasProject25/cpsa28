<?php

/**
 * Plugin Name: CRUD Recherche
 * Description: Module WordPress permetant de gérer les recherches personnalisées des clients. Lorsque le client fait une recherche personnalisée, elle apparaitra ici, l'administrateur peut également en planifier, modifier, en supprimer et les consulter grâce à ce plugin
 * Version: 1.0
 * Author: Lucas
 */

//Sécurité : Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}


require_once plugin_dir_path(__FILE__) . 'models/DocumentsModel.php';
require_once plugin_dir_path(__FILE__) . 'models/EtatModel.php';
require_once plugin_dir_path(__FILE__) . 'models/PropositionsModel.php';
require_once plugin_dir_path(__FILE__) . 'models/RechercheModel.php';

class CrudRecherchePlugin
{
    private $documents;
    private $etats;
    private $propositions;
    private $recherches;

    public function __construct()
    {
        $this->documents = new DocumentsModel;
        $this->etats = new EtatModel;
        $this->propositions = new PropositionsModel;
        $this->recherches = new RechercheModel;

        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'handleActions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts($hook)
    {
        if (strpos($hook, 'crud-recherche' === false)) {
            return;
        }

        // Bootstrap 5 CSS
        wp_enqueue_style(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
            [],
            '5.3.2'
        );

        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css',
            [],
            '5.3.2'
        );

        // CSS personnalisé
        wp_enqueue_style(
            'crud-recherche-admin',
            plugin_dir_url(__FILE__) . 'assets/css/styles.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'crud-recherche-admin',
            plugin_dir_url(__FILE__) . 'assets/js/script.js',
            ['jquery'],
            '1.0',
            true
        );

        add_action('admin_head', function () {
            echo '<style>
        #toplevel_page_crud-recherche-listing 
        a[href="admin.php?page=recherche-consult"],
        #toplevel_page_crud-recherche-listing 
        a[href="admin.php?page=recherche-consult-proposition-form"] {
            display:none !important;
        }
    </style>';
        });
    }

    public function addAdminMenu()
    {
        add_menu_page(
            'Demande de recherche',
            'Demande de recherche',
            'manage_options',
            'crud-recherche-listing',
            [$this, 'renderRechercheListingPage'],
            'dashicons-calendar',
            30
        );
        add_submenu_page(
            'crud-recherche-listing',
            'Faire une recherche personnalisée',
            'Recherche personnalisée',
            'manage_options',
            'crud-recherche-form',
            [$this, 'renderRechercheForm']
        );
        add_submenu_page(
            'crud-recherche-listing',
            'Ajouter un état',
            'Ajouter un état',
            'manage_options',
            'crud-etat-form',
            [$this, 'renderEtatFormPage']
        );
        add_submenu_page(
            'crud-recherche-listing', // parent valide
            'Consulter recherche',
            'Consulter recherche',
            'manage_options',
            'recherche-consult',
            [$this, 'renderConsultPage']
        );
        add_submenu_page(
            'crud-recherche-listing',
            'Ajouter une proposition',
            'Ajouter une proposition',
            'manage_options',
            'recherche-consult-proposition-form',
            [$this, 'renderConsultPropositionFormPage']
        );
    }

    public function renderRechercheForm()
    {
        global $wpdb;

        $recherches = null;
        $etats = $this->etats->getAll();
        $membres = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}customer"
        );
        $responsables = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}equipe"
        );
        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $recherches = $this->recherches->getById($id);

            if (!$recherches) {
                wp_die('recherche non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/recherche-form-page.php';
    }

    public function renderRechercheListingPage()
    {
        global $wpdb;

        $recherches = $this->recherches->getAll();
        $etats = $this->etats->getAll();
        $membres = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}customer"
        );
        $responsables = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}equipe"
        );
        include 'views/recherche-listing-page.php';
    }

    public function renderEtatFormPage()
    {
        $etats = null;

        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $etats = $this->etats->getById($id);

            if (!$etats) {
                wp_die('etat non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/recherche-etat-form-page.php';
    }

    public function renderConsultPage()
    {

        global $wpdb;

        $recherches = null;
        $membre = null;
        $customer_details = false;


        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $recherches = $this->recherches->getByIdConsult($id);
            $documents = $this->documents->getByRecherche($id);
            $propositions = $this->propositions->getByRecherche($id);

            if (!$recherches) {
                wp_die('consultation non trouvé');
            }

            $customer_details = true;
        }

        if ($recherches && !empty($recherches->id_membre)) {
            $membre = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT name, surname 
                 FROM {$wpdb->prefix}customer 
                 WHERE id_customer = %d",
                    $recherches->id_membre
                )
            );
        }

        $responsables = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}equipe"
        );

        include 'views/recherche-consult-page.php';
    }

    public function renderConsultPropositionFormPage()
    {

        $propositions = null;
        $customer_details = false;
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $recherches = $this->recherches->getByIdConsult($id);

            if (!$recherches) {
                wp_die('recherches non trouvé');
            }

            $customer_details = true;
        }

        /* $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $propositions = $this->propositions->getById($id);

            if (!$propositions) {
                wp_die('propositions non trouvé');
            }

            $is_edit = true;
        } */
        include 'views/recherche-consult-proposition-form-page.php';
    }

    public function handleActions()
    {
        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-recherche-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete'
            && isset($_GET['id'])
        ) {
            $id = intval($_GET['id']);

            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_recherche_' . $id)) {
                wp_die('Action non autorisée');
            }

            $this->recherches->delete($id);

            wp_redirect(admin_url('admin.php?page=crud-recherche-listing&deleted=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-recherche-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete-etat'
            && isset($_GET['id'])
        ) {

            $idEtat = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_etat_' . $idEtat)) {
                wp_die('Action non autorisée');
            }

            $this->etats->delete($idEtat);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-recherche-listing&deletedetat=1'));
            exit;
        }


        if (
            isset($_GET['page']) && $_GET['page'] === 'recherche-consult'
            && isset($_GET['action']) && $_GET['action'] === 'delete-document'
            && isset($_GET['id'])
        ) {

            $idDocument = intval($_GET['id']);
            $idRecherche = intval($_GET['id_rechercheperso'] ?? 0);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_document_' . $idDocument)) {
                wp_die('Action non autorisée');
            }

            $this->documents->delete($idDocument);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=recherche-consult&id=' . $idRecherche . '&deleteddocument=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'recherche-consult'
            && isset($_GET['action']) && $_GET['action'] === 'delete-proposition'
            && isset($_GET['id'])
        ) {

            $idProposition = intval($_GET['id']);
            $idRecherche = intval($_GET['id_rechercheperso'] ?? 0);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_proposition_' . $idProposition)) {
                wp_die('Action non autorisée');
            }

            $this->propositions->delete($idProposition);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=recherche-consult&id=' . $idRecherche . '&deletedproposition=1'));
            exit;
        }

        if (isset($_POST['crud_recherche_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_recherche_nonce'])
                || !wp_verify_nonce($_POST['crud_recherche_nonce'], 'crud_recherche_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            $nom_vehicule   = sanitize_text_field($_POST['nom_vehicule']);
            $modele         = sanitize_text_field($_POST['modele']);
            $marque         = sanitize_text_field($_POST['marque']);
            $energie        = sanitize_text_field($_POST['energie']);
            $categorie      = sanitize_text_field($_POST['categorie']);
            $mise_circulation = sanitize_text_field($_POST['mise_en_circulation_vehicule']);
            $transmission   = sanitize_text_field($_POST['transmission_vehicule']);
            $couleur_ext    = sanitize_text_field($_POST['couleur_exterieur_vehicule']);
            $couleur_int    = sanitize_text_field($_POST['couleur_interieur_vehicule']);

            // ✅ textarea
            $info_supp = sanitize_textarea_field($_POST['info_supp']);

            // ✅ numériques
            $kilometrage        = isset($_POST['kilometrage_vehicule']) ? floatval($_POST['kilometrage_vehicule']) : 0;
            $puissance_fiscale  = isset($_POST['puissance_fiscale_vehicule']) ? intval($_POST['puissance_fiscale_vehicule']) : 0;
            $puissance_din      = isset($_POST['puissance_DIN_vehicule']) ? intval($_POST['puissance_DIN_vehicule']) : 0;
            $nbr_portes         = isset($_POST['nbr_portes_vehicule']) ? intval($_POST['nbr_portes_vehicule']) : 0;
            $prix               = isset($_POST['prix_vehicule']) ? intval($_POST['prix_vehicule']) : 0;

            // ✅ select
            $id_membre = isset($_POST['id_membre']) ? absint($_POST['id_membre']) : 0;
            $id_responsable = isset($_POST['id_responsable']) ? absint($_POST['id_responsable']) : 0;

            if ($mise_circulation) {
                $dateObj = DateTime::createFromFormat('Y-m-d', $mise_circulation);
                $errors = DateTime::getLastErrors();

                if (!$dateObj || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
                    wp_redirect(admin_url('admin.php?page=crud-recherche-planif&error=invalid_date'));
                    exit;
                }

                // ✅ IMPORTANT : on remet une string pour la BDD
                $mise_circulation = $dateObj->format('Y-m-d');
            }

            // Validation
            if (empty($nom_vehicule) || empty($prix) || empty($id_membre)) {
                wp_redirect(admin_url('admin.php?page=crud-recherche-planif&error=empty'));
                exit;
            }
            // Modification ou ajout
            if (isset($_POST['id_rechercheperso']) && !empty($_POST['id_rechercheperso'])) {
                // Modification
                $id = intval($_POST['id_rechercheperso']);
                $this->recherches->update($id, $nom_vehicule, $modele, $marque, $categorie, $energie, $mise_circulation, $kilometrage, $transmission, $puissance_fiscale, $puissance_din, $couleur_ext, $couleur_int, $nbr_portes, $prix, $info_supp, '', $id_membre, 1, $id_responsable);
                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&updated=1'));
            } else {
                // Ajout
                $this->recherches->create($nom_vehicule, $modele, $marque, $categorie, $energie, $mise_circulation, $kilometrage, $transmission, $puissance_fiscale, $puissance_din, $couleur_ext, $couleur_int, $nbr_portes, $prix, $info_supp, '', $id_membre, 1, $id_responsable);
                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&added=1'));
            }

            exit;
        }

        if (isset($_POST['crud_etat_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_etat_nonce'])
                || !wp_verify_nonce($_POST['crud_etat_nonce'], 'crud_etat_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $intitule = sanitize_text_field($_POST['intitule']);

            // Validation
            if (empty($intitule)) {
                wp_redirect(admin_url('admin.php?page=crud-etat-form&error=empty'));
                exit;
            }

            if (strlen($intitule) > 100) {
                wp_redirect(admin_url('admin.php?page=crud-etat-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_etat']) && !empty($_POST['id_etat'])) {
                // Modification
                $idEtat = intval($_POST['id_etat']);
                $this->etats->update($idEtat, $intitule);
                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&updatedetat=1'));
            } else {
                // Ajout
                $this->etats->create($intitule);
                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&addedetat=1'));
            }

            exit;
        }

        if (isset($_POST['crud_document_submit'])) {

            // Nonce
            if (
                !isset($_POST['crud_document_nonce']) ||
                !wp_verify_nonce($_POST['crud_document_nonce'], 'crud_document_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Capacité
            if (!current_user_can('manage_options')) {
                wp_die('Permissions insuffisantes');
            }

            // Vérifier fichier
            if (empty($_FILES['nom_document']['name'])) {
                wp_die('Aucun fichier sélectionné');
            }

            // Upload WordPress
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $uploaded = wp_handle_upload($_FILES['nom_document'], ['test_form' => false]);

            if (isset($uploaded['error'])) {
                wp_die($uploaded['error']);
            }

            // Nom sécurisé
            $filename = sanitize_file_name(basename($uploaded['file']));
            $file_url = $uploaded['url'];

            $id_rechercheperso = intval($_POST['id_rechercheperso']);

            // INSERT / UPDATE
            if (!empty($_POST['id_document'])) {

                $idDocument = intval($_POST['id_document']);
                $this->documents->update($idDocument, $filename, $id_rechercheperso);

                wp_redirect(admin_url('admin.php?page=recherche-consult&id=' . $id_rechercheperso . '&updateddocument=1'));
            } else {

                $this->documents->create($filename, $id_rechercheperso);

                wp_redirect(admin_url('admin.php?page=recherche-consult&id=' . $id_rechercheperso . '&addeddocument=1'));
            }

            exit;
        }

        /* if (isset($_POST['crud_affect_submit'])) {
            if (
                !isset($_POST['crud_affect_nonce'])
                || !wp_verify_nonce($_POST['crud_affect_nonce'], 'crud_affect_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            $id_responsable_affect = isset($_POST['id_responsable']) ? absint($_POST['id_responsable']) : 0;

            // Modification
            if (isset($_POST['id_etat']) && !empty($_POST['id_etat'])) {
                // Modification
                $idEtat = intval($_POST['id_etat']);
                $this->recherches->update($idEtat, $intitule);
                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&updatedetat=1'));
            }
        } */

        if (isset($_POST['crud_proposition_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_proposition_nonce'])
                || !wp_verify_nonce($_POST['crud_proposition_nonce'], 'crud_proposition_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $modele = sanitize_text_field($_POST['modele']);
            $annee = sanitize_text_field($_POST['annee']);
            $prix_prop = sanitize_text_field($_POST['prix']);
            $statut = sanitize_text_field($_POST['statut']);
            $id_rechercheperso_prop = intval($_POST['id_rechercheperso']);

            // Validation
            if (empty($modele) || empty($annee) || empty($prix_prop) || empty($statut)) {
                wp_redirect(admin_url('admin.php?page=recherche-consult-proposition-form&error=empty'));
                exit;
            }

            if (strlen($modele) > 100 || strlen($statut) > 100) {
                wp_redirect(admin_url('admin.php?page=recherche-consult-proposition-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_proposition']) && !empty($_POST['id_proposition'])) {
                // Modification
                $idProposition = intval($_POST['id_proposition']);
                $this->propositions->update($idProposition, $modele, $annee, $prix_prop, $statut, $id_rechercheperso_prop);
                wp_redirect(admin_url('admin.php?page=recherche-consult&id=' . $id_rechercheperso_prop . '&updatedproposition=1'));
            } else {
                // Ajout
                $this->propositions->create($modele, $annee, $prix_prop, $statut, $id_rechercheperso_prop);
                wp_redirect(admin_url('admin.php?page=recherche-consult&id=' . $id_rechercheperso_prop . '&addedproposition=1'));
                /* if ($result === false) {
                    global $wpdb;
                    wp_die('Erreur SQL : ' . $wpdb->last_error);
                } */
            }

            exit;
        }

        // Mettre l'état de la recherche à "terminée" une fois le conseiller affecté
        if (isset($_POST['crud_consult_submit'])) {

            if (
                !isset($_POST['crud_consult_nonce']) ||
                !wp_verify_nonce($_POST['crud_consult_nonce'], 'crud_consult_save')
            ) {
                wp_die('Action non autorisée');
            }

            if (!current_user_can('manage_options')) {
                wp_die('Permissions insuffisantes');
            }

            $id_responsable_affect = isset($_POST['id_responsable']) ? absint($_POST['id_responsable']) : 0;

            if (empty($id_responsable_affect)) {
                wp_redirect(admin_url('admin.php?page=recherche-consult&error=empty'));
                exit;
            }

            if (!empty($_POST['id_rechercheperso'])) {

                $id = intval($_POST['id_rechercheperso']);

                $this->recherches->updateEtat($id, 3);
                $this->recherches->updateResponsable($id, $id_responsable_affect);

                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&updated=1'));
                exit;
            }
        }

        // Mettre l'état à "En cours de recherche"
        if (isset($_POST['crud_consult_submit_attente'])) {

            if (
                !isset($_POST['crud_consult_nonce']) ||
                !wp_verify_nonce($_POST['crud_consult_nonce'], 'crud_consult_save')
            ) {
                wp_die('Action non autorisée');
            }

            if (!current_user_can('manage_options')) {
                wp_die('Permissions insuffisantes');
            }

            $id_responsable_affect = isset($_POST['id_responsable']) ? absint($_POST['id_responsable']) : 0;

            if (empty($id_responsable_affect)) {
                wp_redirect(admin_url('admin.php?page=recherche-consult&error=empty'));
                exit;
            }

            if (!empty($_POST['id_rechercheperso'])) {

                $id = intval($_POST['id_rechercheperso']);

                $this->recherches->updateEtat($id, 2);
                $this->recherches->updateResponsable($id, $id_responsable_affect);

                wp_redirect(admin_url('admin.php?page=crud-recherche-listing&updated=1'));
                exit;
            }
        }
    }

    public function createTableRecherche()
    {
        $this->recherches->createTable();
    }

    public function createTableEtat()
    {
        $this->etats->createTable();
    }

    public function createTableDocument()
    {
        $this->documents->createTable();
    }

    public function createTableProposition()
    {
        $this->propositions->createTable();
    }

    public function dropTableRecherche()
    {
        $this->recherches->dropTable();
    }

    public function dropTableEtat()
    {
        $this->etats->dropTable();
    }

    public function dropTableDocument()
    {
        $this->documents->dropTable();
    }

    public function dropTableProposition()
    {
        $this->propositions->dropTable();
    }
}

$CrudRecherchePlugin = new CrudRecherchePlugin();
register_activation_hook(__FILE__, [$CrudRecherchePlugin, 'createTableRecherche']);
register_activation_hook(__FILE__, [$CrudRecherchePlugin, 'createTableEtat']);
register_activation_hook(__FILE__, [$CrudRecherchePlugin, 'createTableDocument']);
register_activation_hook(__FILE__, [$CrudRecherchePlugin, 'createTableProposition']);
register_deactivation_hook(__FILE__, [$CrudRecherchePlugin, 'dropTableRecherche']);
register_deactivation_hook(__FILE__, [$CrudRecherchePlugin, 'dropTableEtat']);
register_deactivation_hook(__FILE__, [$CrudRecherchePlugin, 'dropTableDocument']);
register_deactivation_hook(__FILE__, [$CrudRecherchePlugin, 'dropTableProposition']);
