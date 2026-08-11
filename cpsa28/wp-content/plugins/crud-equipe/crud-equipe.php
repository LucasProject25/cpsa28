<?php

/**
 * Plugin Name: CRUD Equipe
 * Description: Module WordPress permettant de créer une table, et de créer un système d'ajout, modification et suppression dans cette table
 * Version: 1.0
 * Author: Lucas
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'models/MembreModel.php';
require_once plugin_dir_path(__FILE__) . 'models/RoleModel.php';

/**
 * Classe principale du plugin
 */
class CrudEquipePlugin
{
    private $membreModel;
    private $roleModel;

    public function __construct()
    {
        $this->membreModel = new MembreModel();
        $this->roleModel = new RoleModel();

        // Hooks WordPress
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_init', [$this, 'handleActions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts($hook)
    {
        // Charger uniquement sur nos pages
        if (strpos($hook, 'crud-equipe') === false) {
            return;
        }


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

        wp_enqueue_style(
            'crud-rendezVous-admin',
            plugin_dir_url(__FILE__) . 'assets/css/admin-crud.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'crud-equipe-admin',
            plugin_dir_url(__FILE__) . 'assets/js/admin-crud.js',
            ['jquery'],
            '1.0',
            true
        );
    }

    // Ajouter le menu dans l'admin
    public function addAdminMenu()
    {
        add_menu_page(
            'Équipe',
            'Équipe',
            'manage_options',
            'crud-equipe-listing',
            [$this, 'renderAdminListingPage'],
            'dashicons-admin-users',
            30
        );
        // Sous-page : Ajouter un élément
        add_submenu_page(
            'crud-equipe-listing',           // Parent slug
            'Ajouter un membre',          // Titre de la page
            'Ajouter',                     // Titre du menu
            'manage_options',              // Capacité requise
            'crud-equipe-form',               // Slug de la page
            [$this, 'renderAdminFormPage']  // Fonction de rendu
        );
        add_submenu_page(
            'crud-equipe-listing',           // Parent slug
            'Ajouter un rôle',          // Titre de la page
            'Rôle',                     // Titre du menu
            'manage_options',              // Capacité requise
            'crud-role-form',               // Slug de la page
            [$this, 'renderRoleFormPage']  // Fonction de rendu
        );
    }

    public function renderAdminFormPage()
    {
        $membre = null;
        $roles = $this->roleModel->getAll();
        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $membre = $this->membreModel->getById($id);

            if (!$membre) {
                wp_die('Membre non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/admin-form-page.php';
    }

    public function renderAdminListingPage()
    {
        $membres = $this->membreModel->getAll();
        $roles = $this->roleModel->getAll();
        include 'views/admin-listing-page.php';
    }

    public function renderRoleFormPage()
    {
        $role = null;

        $is_edit = false;

        // Si un ID est présent, on est en mode édition
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            $role = $this->roleModel->getById($id);

            if (!$role) {
                wp_die('Rôle non trouvé');
            }

            $is_edit = true;
        }

        include plugin_dir_path(__FILE__) . 'views/admin-role-form-page.php';
    }

    /* public function renderAdminListingRolePage()
    {
        $roles = $this->roleModel->getAll();
        include 'views/admin-listing-page.php';
    }
 */

    /**
     * Gérer les actions (suppression, ajout, modification)
     */
    public function handleActions()
    {
        // Gestion de la suppression
        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-equipe-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete'
            && isset($_GET['id'])
        ) {

            $id = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_membre_' . $id)) {
                wp_die('Action non autorisée');
            }

            $this->membreModel->delete($id);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-equipe-listing&deleted=1'));
            exit;
        }

        if (
            isset($_GET['page']) && $_GET['page'] === 'crud-equipe-listing'
            && isset($_GET['action']) && $_GET['action'] === 'delete-role'
            && isset($_GET['id'])
        ) {

            $idRole = intval($_GET['id']);

            // Vérifier le nonce pour la sécurité
            if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_role_' . $idRole)) {
                wp_die('Action non autorisée');
            }

            $this->roleModel->delete($idRole);

            // Redirection après suppression
            wp_redirect(admin_url('admin.php?page=crud-equipe-listing&deletedrole=1'));
            exit;
        }

        // Gestion de l'ajout/modification
        if (isset($_POST['crud_equipe_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_equipe_nonce'])
                || !wp_verify_nonce($_POST['crud_equipe_nonce'], 'crud_equipe_save')
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
            $email = sanitize_text_field($_POST['email']);
            $id_role = isset($_POST['id_role']) ? absint($_POST['id_role']) : 0;

            // Validation
            if (empty($name) || empty($email) || empty($id_role)) {
                wp_redirect(admin_url('admin.php?page=crud-equipe-form&error=empty'));
                exit;
            }

            if (strlen($name) > 50 || strlen($email) > 50) {
                wp_redirect(admin_url('admin.php?page=crud-equipe-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_membre']) && !empty($_POST['id_membre'])) {
                // Modification
                $id = intval($_POST['id_membre']);
                $this->membreModel->update($id, $name, $surname, $email, $id_role);
                wp_redirect(admin_url('admin.php?page=crud-equipe-listing&updated=1'));
            } else {
                // Ajout
                $this->membreModel->create($name, $surname, $email, $id_role);
                wp_redirect(admin_url('admin.php?page=crud-equipe-listing&added=1'));
            }

            exit;
        }

        if (isset($_POST['crud_role_submit'])) {
            // Vérifier le nonce
            if (
                !isset($_POST['crud_role_nonce'])
                || !wp_verify_nonce($_POST['crud_role_nonce'], 'crud_role_save')
            ) {
                wp_die('Action non autorisée');
            }

            // Vérifier les capacités
            if (!current_user_can('manage_options')) {
                wp_die('Vous n\'avez pas les permissions nécessaires');
            }

            // Récupérer et nettoyer les données
            $name = sanitize_text_field($_POST['name']);

            // Validation
            if (empty($name)) {
                wp_redirect(admin_url('admin.php?page=crud-role-form&error=empty'));
                exit;
            }

            if (strlen($name) > 50) {
                wp_redirect(admin_url('admin.php?page=crud-role-form&error=toolong'));
                exit;
            }

            // Modification ou ajout
            if (isset($_POST['id_role']) && !empty($_POST['id_role'])) {
                // Modification
                $idRole = intval($_POST['id_role']);
                $this->roleModel->update($idRole, $name);
                wp_redirect(admin_url('admin.php?page=crud-equipe-listing&updatedrole=1'));
            } else {
                // Ajout
                $this->roleModel->create($name);
                wp_redirect(admin_url('admin.php?page=crud-equipe-listing&addedrole=1'));
            }

            exit;
        }
    }

    public function createTable()
    {
        $this->membreModel->createTable();
    }

    public function createTableRole()
    {
        $this->roleModel->createTableRole();
    }

    /**
     * Supprimer la table lors de la désactivation du plugin
     */
    public function dropTable()
    {
        $this->membreModel->dropTable();
    }

    public function dropTableRole()
    {
        $this->roleModel->dropTableRole();
    }
}

// Démarrer le plugin
$CrudEquipePlugin = new CrudEquipePlugin();
register_activation_hook(__FILE__, [$CrudEquipePlugin, 'createTable']);
register_activation_hook(__FILE__, [$CrudEquipePlugin, 'createTableRole']);
register_deactivation_hook(__FILE__, [$CrudEquipePlugin, 'dropTable']);
register_deactivation_hook(__FILE__, [$CrudEquipePlugin, 'dropTableRole']);
