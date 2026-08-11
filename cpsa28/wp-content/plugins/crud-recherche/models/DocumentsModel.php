<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class DocumentsModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'documentrecherche';
    }

    /**
     * Récupérer tout les document
     * 
     * @return array Liste des clients
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_document DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un document par son ID
     * 
     * @param int $id ID du document
     * @return object|null Le document ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_document = %d",
                $id
            )
        );
    }

    /**
     * Récupérer les documents d'une recherche spécifique
     *
     * @param int $id_rechercheperso
     * @return array
     */
    public function getByRecherche($id)
    {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT *
             FROM {$this->table_name}
             WHERE id_rechercheperso = %d
             ORDER BY id_document DESC",
                $id
            )
        );
    }

    /**
     * Créer un nouveau document
     * 
     * @param string $nom_document Intitulé du document
     * @param date $date_upload Date de l'upload du document
     * @param int $id_rechercheperso ID de la recherche
     */
    public function create($nom_document, $id_rechercheperso)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'nom_document' => $nom_document,
                'id_rechercheperso' => $id_rechercheperso
            ],
            ['%s', '%s', '%d']
        );
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un document
     * Fonction pas vraiment nécessaire
     * 
     * @param int $id ID du document
     * @param string $nom_document Intitulé du document
     * @param date $date_upload Date de l'upload du document
     * @param int $id_rechercheperso ID de la recherche
     */
    public function update($id, $nom_document, $id_rechercheperso)
    {
        $result = $this->wpdb->update(
            $this->table_name,
            [
                'nom_document' => $nom_document,
                'id_rechercheperso' => $id_rechercheperso
            ],
            ['id_document' => $id],
            ['%s', '%s', '%d']['%d']
        ) !== false;
    }

    /**
     * Supprimer un document
     * 
     * @param int $id ID du document à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_document' => $id],
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
        id_document INT(11) NOT NULL AUTO_INCREMENT,
        nom_document VARCHAR(100) NOT NULL,
        date_upload DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        id_rechercheperso INT(11) NOT NULL,
        PRIMARY KEY (id_document),
        KEY id_rechercheperso (id_rechercheperso)
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
