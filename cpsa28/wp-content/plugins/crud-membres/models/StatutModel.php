<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modèle pour gérer les statuts
 */
class StatutModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'statut';
    }

    /**
     * Récupérer tous les statuts
     * 
     * @return array Liste des statuts
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_statut DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un statut par son ID
     * 
     * @param int $id ID du statut
     * @return object|null Le statut ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_statut = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau statut
     * 
     * @param string $name Nom du statut
     * @param string $icon Icone du statut
     * @return int|false ID du statut créé ou false en cas d'erreur
     */
    public function create($name, $icon)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'name' => $name,
                'icon' => $icon
            ],
            ['%s', '%s']
        );

        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un rôle
     * 
     * @param int $id ID du rôle
     * @param string $name Nouveau nom
     * @param string $icon Nouvel icone
     * @return bool True si succès, false sinon
     */
    public function update($id, $name, $icon)
    {
        return $this->wpdb->update(
            $this->table_name,
            [
                'name' => $name,
                'icon' => $icon
            ],
            ['id_statut' => $id],
            ['%s', '%s'],
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
            ['id_statut' => $id],
            ['%d']
        ) !== false;
    }

    /**
     * Créer la table
     */
    public function createTablestatut()
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id_statut int(11) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            icon varchar(50) NULL,
            PRIMARY KEY (id_statut)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Supprimer la table
     */
    public function dropTablestatut()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->table_name}");
    }
}
