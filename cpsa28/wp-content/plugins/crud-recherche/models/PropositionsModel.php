<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class PropositionsModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'propositionrecherche';
    }

    /**
     * Récupérer tout les proposition
     * 
     * @return array Liste des propositions
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table_name} ORDER BY id_proposition DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un proposition par son ID
     * 
     * @param int $id ID du proposition
     * @return object|null Le proposition ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_proposition = %d",
                $id
            )
        );
    }

    /**
     * Récupérer les propositions d'une recherche spécifique
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
             ORDER BY id_proposition DESC",
                $id
            )
        );
    }

    /**
     * Créer un nouveau proposition
     * 
     * @param string $modele Modèle du véhicule proposé
     * @param date $annee Date de mise en circulation du véhicule proposé
     * @param int $prix Prix du véhicule proposé
     * @param string $statut Véhicule disponible à la concession ou non
     * @param int $id_rechercheperso ID de la recherche
     */
    public function create($modele, $annee, $prix, $statut, $id_rechercheperso)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'modele' => $modele,
                'annee' => $annee,
                'prix' => $prix,
                'statut' => $statut,
                'id_rechercheperso' => $id_rechercheperso
            ],
            ['%s', '%s', '%s', '%s', '%d']
        );
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un proposition
     * Fonction pas vraiment nécessaire
     * 
     * @param int $id ID du proposition
     * @param string $modele Modèle du véhicule proposé
     * @param date $annee Date de mise en circulation du véhicule proposé
     * @param int $prix Prix du véhicule proposé
     * @param string $statut Véhicule disponible à la concession ou non
     * @param int $id_rechercheperso ID de la recherche
     */
    public function update($id, $modele, $annee, $prix, $statut, $id_rechercheperso)
    {
        $result = $this->wpdb->update(
            $this->table_name,
            [
                'modele' => $modele,
                'annee' => $annee,
                'prix' => $prix,
                'statut' => $statut,
                'id_rechercheperso' => $id_rechercheperso
            ],
            ['id_proposition' => $id],
            ['%s', '%s', '%s', '%s', '%d']['%d']
        ) !== false;
    }

    /**
     * Supprimer un proposition
     * 
     * @param int $id ID du proposition à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_proposition' => $id],
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
        id_proposition INT(11) NOT NULL AUTO_INCREMENT,
        modele VARCHAR(50) NOT NULL,
        annee DATE NOT NULL,
        prix INT NOT NULL,
        statut VARCHAR(50) NOT NULL,
        id_rechercheperso INT(11) NOT NULL,
        PRIMARY KEY (id_proposition),
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
