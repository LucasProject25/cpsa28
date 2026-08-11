<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class EtatModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'etat';
    }

    /**
     * Récupérer tout les etat
     * 
     * @return array Liste des clients
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_etat DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un etat par son ID
     * 
     * @param int $id ID du etat
     * @return object|null Le etat ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_etat = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau etat
     * 
     * @param string $intitule Intitulé du etat
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
     * Mettre à jour un etat
     * 
     * @param int $id ID du etat
     * @param string $intitule Intitulé du etat
     */
    public function update($id, $intitule)
    {
        $result = $this->wpdb->update(
            $this->table_name,
            [
                'intitule' => $intitule
            ],
            ['id_etat' => $id],
            ['%s'],
            ['%d']
        ) !== false;
    }

    /**
     * Supprimer un etat
     * 
     * @param int $id ID du etat à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_etat' => $id],
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
        id_etat INT(11) NOT NULL AUTO_INCREMENT,
        intitule VARCHAR(100),
        PRIMARY KEY (id_etat)
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
