<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modèle pour gérer les roles
 */
class RoleModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'role';
    }

    /**
     * Récupérer tous les rôles
     * 
     * @return array Liste des rôles
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_role DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un rôle par son ID
     * 
     * @param int $id ID du rôle
     * @return object|null Le role ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_role = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau rôle
     * 
     * @param string $name Nom du rôle
     * @return int|false ID du rôle créé ou false en cas d'erreur
     */
    public function create($name)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            ['name' => $name],
            ['%s']
        );

        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un rôle
     * 
     * @param int $id ID du rôle
     * @param string $name Nouveau nom
     * @return bool True si succès, false sinon
     */
    public function update($id, $name)
    {
        return $this->wpdb->update(
            $this->table_name,
            ['name' => $name],
            ['id_role' => $id],
            ['%s'],
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
            ['id_role' => $id],
            ['%d']
        ) !== false;
    }

    /**
     * Créer la table
     */
    public function createTableRole()
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id_role int(11) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            PRIMARY KEY (id_role)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Supprimer la table
     */
    public function dropTableRole()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->table_name}");
    }
}
