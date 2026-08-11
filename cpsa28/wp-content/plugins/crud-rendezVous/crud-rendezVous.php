<?php

/**
 * Plugin Name: CRUD rendezVous
 * Description: Module WordPress permetant de gérer les rendez-vous des clients. Lorsque le client planifie son rendez-vous, il apparaitra ici, l'administrateur peut également en planifier, modifier et en supprimer grâce à ce plugin
 * Version: 1.0
 * Author: Lucas
 */

//Sécurité : Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'models/RendezVousModel.php';
require_once plugin_dir_path(__FILE__) . 'models/MotifModel.php';
require_once plugin_dir_path(__FILE__) . 'models/TypeModel.php';

/**
 * Classe principale
 */
class CrudRendezVousPlugin
{
    private $RDV;
    private $motif;
    private $type;

    public function __construct()
    {
        $this->RDV = new RendezVousModel();
        $this->motif = new MotifModel();
        $this->type = new TypeModel();

        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'handleActions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts($hook)
    {
        if (strpos($hook, 'crud-rendezVous' === false)) {
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
            'crud-rendezVous-admin',
            plugin_dir_url(__FILE__) . 'assets/css/styles.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'crud-rendezVous-admin',
            plugin_dir_url(__FILE__) . 'assets/js/script.js',
            ['jquery'],
            '1.0',
            true
        );
    }

    public function addAdminMenu()
    {
        add_menu_page(
            'Rendez-vous',
            'Rendez-vous',
            'manage_options',
            'crud-rendezVous-listing',
            [$this, 'renderRendezVousListingPage'],
            'dashicons-calendar',
            30
        );
        add_submenu_page(
            'crud-rendezVous-listing',
            'Planifier un rendez-vous',
            'Planifier',
            'manage_options',
            'crud-rendezVous-planif',
            [$this, 'renderRendezVousPlanif']
        );
        add_submenu_page(
            'crud-rendezVous-listing',
            'Ajouter un motif',
            'Ajouter un motif',
            'manage_options',
            'crud-motif-form',
            [$this, 'renderMotifFormPage']
        );
        add_submenu_page(
            'crud-rendezVous-listing',
            'Ajouter un type de rendez-vous',
            'Ajouter un type de rendez-vous',
            'manage_options',
            'crud-type-form',
            [$this, 'renderTypeFormPage']
        );
    }

    public function renderRendezVousPlanif()
    {

        global $wpdb;

        $RDV = null;
        $membres = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}customer"
        );
        $responsables = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}equipe"
        );
        $motifs = $this->motif->getAll();
        $types = $this->type->getAll();
        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $RDV = $this->RDV->getById($id);

            if (!$RDV) {
                wp_die('RendezVous non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/rendezVous-planif-page.php';
    }

    public function renderRendezVousListingPage()
    {

        global $wpdb;

        $RDV = $this->RDV->getAll();
        $membres = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}customer"
        );
        $responsables = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}equipe"
        );
        $motifs = $this->motif->getAll();
        $types = $this->type->getAll();

        include 'views/rendezVous-listing-page.php';
    }

    public function renderMotifFormPage()
    {
        $motif = null;

        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $motif = $this->motif->getById($id);

            if (!$motif) {
                wp_die('Motif non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/rendezVous-motif-form-page.php';
    }

    public function renderTypeFormPage()
    {
        $type = null;

        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $type = $this->type->getById($id);

            if (!$type) {
                wp_die('Type non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/rendezVous-type-form-page.php';
    }

    public function handleActions()
    {
        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-rendezVous-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete'
            && isset($_GET['id'])
        ) {
            $id = intval($_GET['id']);

            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_rendezVous_' . $id)) {
                wp_die('Action non autorisée');
            }

            $this->RDV->delete($id);

            wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&deleted=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-rendezVous-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete-motif'
            && isset($_GET['id'])
        ) {

            $idMotif = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_motif_' . $idMotif)) {
                wp_die('Action non autorisée');
            }

            $this->motif->delete($idMotif);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&deletedmotif=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-rendezVous-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete-type'
            && isset($_GET['id'])
        ) {

            $idType = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_type_' . $idType)) {
                wp_die('Action non autorisée');
            }

            $this->type->delete($idType);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&deletedtype=1'));
            exit;
        }

        if (isset($_POST['crud_rendezVous_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_rendezVous_nonce'])
                || !wp_verify_nonce($_POST['crud_rendezVous_nonce'], 'crud_rendezVous_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : null;
            $heure = isset($_POST['heure']) ? sanitize_text_field($_POST['heure']) : null;
            $id_motif = isset($_POST['id_motif']) ? absint($_POST['id_motif']) : 0;
            $id_membre = isset($_POST['id_membre']) ? absint($_POST['id_membre']) : 0;
            $id_responsable = isset($_POST['id_responsable']) ? absint($_POST['id_responsable']) : 0;
            $id_type = isset($_POST['id_type']) ? absint($_POST['id_type']) : 0;



            if ($date) {
                $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                $errors = DateTime::getLastErrors();

                if (!$dateObj || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
                    wp_redirect(admin_url('admin.php?page=crud-rendezVous-planif&error=invalid_date'));
                    exit;
                }

                // ✅ IMPORTANT : on remet une string pour la BDD
                $date = $dateObj->format('Y-m-d');
            }




            // Validation
            if (empty($date) || empty($heure) || empty($id_motif) || empty($id_membre) || empty($id_responsable) || empty($id_type)) {
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-planif&error=empty'));
                exit;
            }
            // Modification ou ajout
            if (isset($_POST['id_rendezvous']) && !empty($_POST['id_rendezvous'])) {
                // Modification
                $id = intval($_POST['id_rendezvous']);
                $this->RDV->update($id, $date, $heure, $id_motif, $id_membre, $id_responsable, $id_type);
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&updated=1'));
            } else {
                // Ajout
                $this->RDV->create($date, $heure, $id_motif, $id_membre, $id_responsable, $id_type);
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&added=1'));
            }

            exit;
        }

        if (isset($_POST['crud_motif_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_motif_nonce'])
                || !wp_verify_nonce($_POST['crud_motif_nonce'], 'crud_motif_save')
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
                wp_redirect(admin_url('admin.php?page=crud-motif-form&error=empty'));
                exit;
            }

            if (strlen($intitule) > 100) {
                wp_redirect(admin_url('admin.php?page=crud-motif-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_motif']) && !empty($_POST['id_motif'])) {
                // Modification
                $idMotif = intval($_POST['id_motif']);
                $this->motif->update($idMotif, $intitule);
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&updatedmotif=1'));
            } else {
                // Ajout
                $this->motif->create($intitule);
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&addedmotif=1'));
            }

            exit;
        }

        if (isset($_POST['crud_type_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_type_nonce'])
                || !wp_verify_nonce($_POST['crud_type_nonce'], 'crud_type_save')
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
                wp_redirect(admin_url('admin.php?page=crud-type-form&error=empty'));
                exit;
            }

            if (strlen($intitule) > 100) {
                wp_redirect(admin_url('admin.php?page=crud-type-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_type']) && !empty($_POST['id_type'])) {
                // Modification
                $idType = intval($_POST['id_type']);
                $this->type->update($idType, $intitule);
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&updatedtype=1'));
            } else {
                // Ajout
                $this->type->create($intitule);
                wp_redirect(admin_url('admin.php?page=crud-rendezVous-listing&addedtype=1'));
            }

            exit;
        }
    }

    public function createTableRDV()
    {
        $this->RDV->createTable();
    }

    public function createTableMotif()
    {
        $this->motif->createTable();
    }

    public function createTableType()
    {
        $this->type->createTable();
    }

    public function dropTableRDV()
    {
        $this->RDV->dropTable();
    }

    public function dropTableMotif()
    {
        $this->motif->dropTable();
    }

    public function dropTableType()
    {
        $this->type->dropTable();
    }
}

$CrudRendezVousPlugin = new CrudRendezVousPlugin();
register_activation_hook(__FILE__, [$CrudRendezVousPlugin, 'createTableRDV']);
register_activation_hook(__FILE__, [$CrudRendezVousPlugin, 'createTableMotif']);
register_activation_hook(__FILE__, [$CrudRendezVousPlugin, 'createTableType']);
register_deactivation_hook(__FILE__, [$CrudRendezVousPlugin, 'dropTableRDV']);
register_deactivation_hook(__FILE__, [$CrudRendezVousPlugin, 'dropTableMotif']);
register_deactivation_hook(__FILE__, [$CrudRendezVousPlugin, 'dropTableType']);
