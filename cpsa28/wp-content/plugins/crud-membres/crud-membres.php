<?php

/**
 * Plugin Name: CRUD Membre
 * Description: Module WordPress permetant de gérer les membres inscrits et d'affecter leur rôle ou de les supprimer en cas de désinscription
 * Version: 1.0
 * Author: Lucas
 */

//Sécurité : Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'models/UserModel.php';
require_once plugin_dir_path(__FILE__) . 'models/StatutModel.php';
require_once plugin_dir_path(__FILE__) . 'models/PrivilegeModel.php';

/**
 * Classe principale
 */
class CrudMembresPlugin
{
    private $userModel;
    private $statutModel;
    private $privilegeModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->statutModel = new StatutModel();
        $this->privilegeModel = new privilegeModel();

        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'handleActions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts($hook)
    {
        if (strpos($hook, 'crud-membres' === false)) {
            return;
        }

        // Bootstrap 5 CSS
        wp_enqueue_style(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
            [],
            '5.3.2'
        );

        // CSS personnalisé
        wp_enqueue_style(
            'crud-membres-admin',
            plugin_dir_url(__FILE__) . 'assets/css/membres-crud.css',
            ['bootstrap'],
            '1.0.0'
        );

        wp_enqueue_script(
            'crud-membres-admin',
            plugin_dir_url(__FILE__) . 'assets/js/membres-crud.js',
            ['jquery'],
            '1.0',
            true
        );
    }

    public function addAdminMenu()
    {
        add_menu_page(
            'Membres',
            'Membres',
            'manage_options',
            'crud-membres-listing',
            [$this, 'renderMembresListingPage'],
            'dashicons-admin-users',
            30
        );
        add_submenu_page(
            'crud-membres-listing',
            'Ajouter un utilisateur',
            'Ajouter',
            'manage_options',
            'crud-membres-form',
            [$this, 'renderMembreFormPage']
        );
        add_submenu_page(
            'crud-membres-listing',
            'Ajouter un statut',
            'Statut',
            'manage_options',
            'crud-statut-form',
            [$this, 'renderStatutFormPage']
        );
        add_submenu_page(
            'crud-membres-listing',
            'Ajouter un privilège',
            'Privilège',
            'manage_options',
            'crud-privilege-form',
            [$this, 'renderPrivilegeFormPage']
        );
    }

    public function renderMembreFormPage()
    {
        $customer = null;
        $statuts = $this->statutModel->getAll();
        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $customer = $this->userModel->getById($id);

            if (!$customer) {
                wp_die('Membre non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/membres-form-page.php';
    }

    public function renderMembresListingPage()
    {
        $customers = $this->userModel->getAll();
        $statuts = $this->statutModel->getAll();
        $privileges = $this->privilegeModel->getAll();
        include 'views/membres-listing-page.php';
    }

    public function renderStatutFormPage()
    {
        $statut = null;

        $is_edit = false;


        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $statut = $this->statutModel->getById($id);

            if (!$statut) {
                wp_die('Statut non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/membres-statut-form-page.php';
    }

    public function renderPrivilegeFormPage()
    {
        $privilege = null;
        $statuts = $this->statutModel->getAll();
        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $privilege = $this->privilegeModel->getById($id);

            if (!$privilege) {
                wp_die('Privilège non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/membres-privilege-form-page.php';
    }

    public function handleActions()
    {
        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-membres-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete'
            && isset($_GET['id'])
        ) {
            $id = intval($_GET['id']);

            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_membre_' . $id)) {
                wp_die('Action non autorisée');
            }

            $this->userModel->delete($id);

            wp_redirect(admin_url('admin.php?page=crud-membres-listing&deleted=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-membres-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete-statut'
            && isset($_GET['id'])
        ) {

            $idStatut = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_statut_' . $idStatut)) {
                wp_die('Action non autorisée');
            }

            $this->statutModel->delete($idStatut);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-membres-listing&deletedstatut=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-membres-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete-privilege'
            && isset($_GET['id'])
        ) {

            $idPrivilege = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_privilege_' . $idPrivilege)) {
                wp_die('Action non autorisée');
            }

            $this->privilegeModel->delete($idPrivilege);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-membres-listing&deletedprivilege=1'));
            exit;
        }

        if (isset($_POST['crud_membre_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_membre_nonce'])
                || !wp_verify_nonce($_POST['crud_membre_nonce'], 'crud_membre_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $name = sanitize_text_field($_POST['name']);
            $surname = sanitize_text_field($_POST['surname']);
            $birthday = isset($_POST['birthday'])
                ? sanitize_text_field($_POST['birthday'])
                : null;
            $pays = sanitize_text_field($_POST['pays']);
            $ville = sanitize_text_field($_POST['ville']);
            $cp = sanitize_text_field($_POST['cp']);
            $phone = sanitize_text_field($_POST['phone']);
            $email = sanitize_email($_POST['email']);
            $password = trim($_POST['password']);
            $password_hash = wp_hash_password($password);
            $id_statut = isset($_POST['id_statut']) ? absint($_POST['id_statut']) : 0;


            if ($birthday && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
                wp_redirect(admin_url('admin.php?page=crud-membres-form&error=invalid_date'));
                exit;
            }


            // Validation
            if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($id_statut)) {
                wp_redirect(admin_url('admin.php?page=crud-membres-form&error=empty'));
                exit;
            }

            if (strlen($name) > 50 || strlen($surname) > 50 || strlen($pays) > 50 || strlen($ville) > 50 || strlen($cp) > 50 || strlen($phone) > 50 || strlen($email) > 50 || strlen($password) > 100) {
                wp_redirect(admin_url('admin.php?page=crud-membres-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_customer']) && !empty($_POST['id_customer'])) {
                // Modification
                $id = intval($_POST['id_customer']);
                $this->userModel->update($id, $name, $surname, $birthday, $pays, $ville, $cp, $phone, $email, $password_hash, $id_statut);
                wp_redirect(admin_url('admin.php?page=crud-membres-listing&updated=1'));
            } else {
                // Ajout
                $result = $this->userModel->create($name, $surname, $birthday, $pays, $ville, $cp, $phone, $email, $password_hash, $id_statut);
                if ($result === false) {
                    global $wpdb;
                    wp_die('Erreur SQL : ' . $wpdb->last_error);
                }
                wp_redirect(admin_url('admin.php?page=crud-membres-listing&added=1'));
            }

            exit;
        }

        if (isset($_POST['crud_statut_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_statut_nonce'])
                || !wp_verify_nonce($_POST['crud_statut_nonce'], 'crud_statut_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $name = sanitize_text_field($_POST['name']);
            $icon = sanitize_file_name($_POST['icon']);

            // Validation
            if (empty($name)) {
                wp_redirect(admin_url('admin.php?page=crud-statut-form&error=empty'));
                exit;
            }

            if (strlen($name) > 50) {
                wp_redirect(admin_url('admin.php?page=crud-statut-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_statut']) && !empty($_POST['id_statut'])) {
                // Modification
                $idStatut = intval($_POST['id_statut']);
                $this->statutModel->update($idStatut, $name, $icon);
                wp_redirect(admin_url('admin.php?page=crud-membres-listing&updatedrole=1'));
            } else {
                // Ajout
                $this->statutModel->create($name, $icon);
                wp_redirect(admin_url('admin.php?page=crud-membres-listing&addedrole=1'));
            }

            exit;
        }

        if (isset($_POST['crud_privilege_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_privilege_nonce'])
                || !wp_verify_nonce($_POST['crud_privilege_nonce'], 'crud_privilege_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $intitule = sanitize_text_field($_POST['intitule']);
            $id_statut = isset($_POST['id_statut']) ? absint($_POST['id_statut']) : 0;

            // Validation
            if (empty($intitule)) {
                wp_redirect(admin_url('admin.php?page=crud-privilege-form&error=empty'));
                exit;
            }

            if (strlen($intitule) > 255) {
                wp_redirect(admin_url('admin.php?page=crud-privilege-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_privilege']) && !empty($_POST['id_privilege'])) {
                // Modification
                $idPrivilege = intval($_POST['id_privilege']);
                $this->privilegeModel->update($idPrivilege, $intitule, $id_statut);
                wp_redirect(admin_url('admin.php?page=crud-membres-listing&updatedprivilege=1'));
            } else {
                // Ajout
                $this->privilegeModel->create($intitule, $id_statut);
                wp_redirect(admin_url('admin.php?page=crud-membres-listing&addedprivilege=1'));
            }

            exit;
        }
    }

    public function createTable()
    {
        $this->userModel->createTable();
    }

    public function createTableStatut()
    {
        $this->statutModel->createTableStatut();
    }

    public function createTablePrivilege()
    {
        $this->privilegeModel->createTableprivilege();
    }

    public function dropTable()
    {
        $this->userModel->dropTable();
    }

    public function dropTableStatut()
    {
        $this->statutModel->dropTableStatut();
    }

    public function dropTableprivilege()
    {
        $this->privilegeModel->dropTableprivilege();
    }
}

$CrudMembresPlugin = new CrudMembresPlugin();
register_activation_hook(__FILE__, [$CrudMembresPlugin, 'createTable']);
register_activation_hook(__FILE__, [$CrudMembresPlugin, 'createTableStatut']);
register_activation_hook(__FILE__, [$CrudMembresPlugin, 'createTablePrivilege']);
register_deactivation_hook(__FILE__, [$CrudMembresPlugin, 'dropTable']);
register_deactivation_hook(__FILE__, [$CrudMembresPlugin, 'dropTableStatut']);
register_deactivation_hook(__FILE__, [$CrudMembresPlugin, 'dropTablePrivilege']);
