<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class TypeModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'types';
    }

    /**
     * Récupérer tout les types de rendez-vous
     * 
     * @return array Liste des types
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_type DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un type par son ID
     * 
     * @param int $id ID du type
     * @return object|null Le type ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_type = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau type
     * 
     * @param string $intitule Intitulé du type
     */
    public function create($intitule)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'intitule' => $intitule
            ],
            ['%s']
        );
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un type
     * 
     * @param int $id ID du type
     * @param string $intitule Intitulé du type
     */
    public function update($id, $intitule)
    {
        $result = $this->wpdb->update(
            $this->table_name,
            [
                'intitule' => $intitule
            ],
            ['id_type' => $id]['%s'],
            ['%d']
        ) !== false;
    }

    /**
     * Supprimer un type
     * 
     * @param int $id ID du type à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_type' => $id],
            ['%d']
        ) !== false;
    }

    /**
     * Créer la table
     */
    public function createTable()
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
        id_type INT(11) NOT NULL AUTO_INCREMENT,
        intitule VARCHAR(100),
        PRIMARY KEY (id_type)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Supprimer la table
     */
    public function dropTable()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS {$this->table_name}");
    }
}
