<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class MotifModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'motif';
    }

    /**
     * Récupérer tout les motifs
     * 
     * @return array Liste des clients
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_motif DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un motif par son ID
     * 
     * @param int $id ID du motif
     * @return object|null Le motif ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_motif = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau motif
     * 
     * @param string $intitule Intitulé du motif
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
     * Mettre à jour un motif
     * 
     * @param int $id ID du motif
     * @param string $intitule Intitulé du motif
     */
    public function update($id, $intitule)
    {
        $result = $this->wpdb->update(
            $this->table_name,
            [
                'intitule' => $intitule
            ],
            ['id_motif' => $id]['%s'],
            ['%d']
        ) !== false;
    }

    /**
     * Supprimer un motif
     * 
     * @param int $id ID du motif à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_motif' => $id],
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
        id_motif INT(11) NOT NULL AUTO_INCREMENT,
        intitule VARCHAR(100),
        PRIMARY KEY (id_motif)
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
