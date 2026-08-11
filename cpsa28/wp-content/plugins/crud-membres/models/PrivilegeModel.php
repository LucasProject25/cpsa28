<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modèle pour gérer les privileges
 */
class privilegeModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'privilege';
    }

    /**
     * Récupérer tous les privileges
     * 
     * @return array Liste des privileges
     */
    public function getAll()
    {

        global $wpdb;

        $table_statut = $wpdb->prefix . 'statut';

        $sql = "SELECT p.*,
        s.name as nom_statut
        FROM {$this->table_name} p
        JOIN $table_statut s ON p.id_statut = s.id_statut
        ORDER BY id_privilege DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un privilege par son ID
     * 
     * @param int $id ID du privilege
     * @return object|null Le privilege ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_privilege = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau privilege
     * 
     * @param string $intitule Intitulé du privilege
     * @param int $id_statut Id du statut 
     * @return int|false ID du privilege créé ou false en cas d'erreur
     */
    public function create($intitule, $id_statut)
    {

        $statut_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}statut WHERE id_statut = %d",
                $id_statut
            )
        );

        if (!$statut_exists) {
            return new WP_Error('invalid_statut', 'Statut inexistant');
        }

        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'intitule' => $intitule,
                'id_statut' => $id_statut
            ],
            ['%s', '%d']
        );

        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un rôle
     * 
     * @param int $id ID du rôle
     * @param string $intitule Nouvel intitulé
     * @param int $id_statut Id du statut
     * @return bool True si succès, false sinon
     */
    public function update($id, $intitule, $id_statut)
    {

        $statut_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}statut WHERE id_statut = %d",
                $id_statut
            )
        );

        if (!$statut_exists) {
            return new WP_Error('invalid_statut', 'Statut inexistant');
        }

        return $this->wpdb->update(
            $this->table_name,
            [
                'intitule' => $intitule,
                'id_statut' => $id_statut
            ],
            ['id_privilege' => $id],
            ['%s', '%d'],
            ['%d']
        ) !== false;
    }

    /**
     * Supprimer un rôle
     * 
     * @param int $id ID du rôle à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_privilege' => $id],
            ['%d']
        ) !== false;
    }

    /**
     * Créer la table
     */
    public function createTableprivilege()
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id_privilege int(11) NOT NULL AUTO_INCREMENT,
            intitule varchar(255) NOT NULL,
            id_statut INT(11) NOT NULL,
            PRIMARY KEY (id_privilege),
            KEY id_statut (id_statut)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Supprimer la table
     */
    public function dropTableprivilege()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->table_name}");
    }
}
