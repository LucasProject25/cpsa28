<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class RechercheModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'rechercheperso';
    }

    /**
     * Récupérer toute les recherches personnalisées
     * 
     * @return array Liste des recherches
     */
    public function getAll()
    {
        global $wpdb;

        $table_membres = $wpdb->prefix . 'customer';
        $table_etat = $wpdb->prefix . 'etat';
        $table_responsable = $wpdb->prefix . 'equipe';

        $sql = "SELECT rp.*,
        m.name as membre_name,
        m.surname as membre_surname,
        e.intitule as etat_intitule,
        res.name as responsable_name,
        res.surname as responsable_surname
        FROM {$this->table_name} rp 
        LEFT JOIN $table_membres m ON rp.id_membre = m.id_customer 
        LEFT JOIN $table_etat e ON rp.id_etat = e.id_etat
        LEFT JOIN $table_responsable res ON rp.id_responsable = res.id_membre 
        ORDER BY id_rechercheperso DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer une recherche perso par son ID
     * 
     * @param int $id ID du recherche perso
     * @return object|null Le recherche perso ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_rechercheperso = %d",
                $id
            )
        );
    }

    /**
     * Consulter une recherche perso par son ID
     * 
     * @param int $id ID du recherche perso
     * @return object|null Le recherche perso ou null si non trouvé
     */
    public function getByIdConsult($id)
    {

        global $wpdb;

        $table_recherche = $wpdb->prefix . 'rechercheperso';
        $table_customer  = $wpdb->prefix . 'customer';

        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT r.*, c.name, c.surname FROM $table_recherche r 
                LEFT JOIN $table_customer c ON r.id_membre = c.id_customer
                WHERE r.id_rechercheperso = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau recherche perso.
     * Tout est écrit par le client ou le conseiller
     *
     * @param string $nom_véhicule Nom du véhicule
     * @param date $mise_circulation Mise en circulation du véhicule
     * @param decimal $kilometrage Kilomètre roulé
     * @param string $transmission Transmission du véhicule
     * @param int $puissance_fiscale Puissance fiscale du véhicule
     * @param int $puissance_DIN Puissance DIN du véhicule
     * @param string $couleur_ext Couleur extérieur
     * @param string $couleur_int Couleur intérieur
     * @param int $nbr_portes Nombre de portes
     * @param float $prix_vehicule Prix demandé
     * @param string $energie Energie du véhicule 
     * @param string $categorie Categorie du véhicule 
     * @param string $modele Modèle du véhicule
     * @param string $marque Marque du véhicule
     * @param string $info_supp Info supplémentaire
     * @param string $reponse_apportee Réponses apporté par le conseiller (ex : "1 véhicule trouvé" -> sous forme de lien)
     * @param int $id_membre Membre inscrit qui a fait la demande
     * @param int $id_etat Etat de la demande (Terminée ou en cours)
     * @param int $id_responsable Conseiller attitré à la demande
     */
    public function create($nom_vehicule, $modele, $marque, $categorie, $energie, $mise_circulation, $kilometrage, $transmission, $puissance_fiscale, $puissance_DIN, $couleur_ext, $couleur_int, $nbr_portes, $prix_vehicule, $info_supp, $reponse_apportee, $id_membre, $id_etat, $id_responsable)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'nom_vehicule' => $nom_vehicule,
                'mise_en_circulation_vehicule' => $mise_circulation,
                'kilometrage_vehicule' => $kilometrage,
                'transmission_vehicule' => $transmission,
                'puissance_fiscale_vehicule' => $puissance_fiscale,
                'puissance_DIN_vehicule' => $puissance_DIN,
                'couleur_exterieur_vehicule' => $couleur_ext,
                'couleur_interieur_vehicule' => $couleur_int,
                'nbr_portes_vehicule' => $nbr_portes,
                'prix_vehicule' => $prix_vehicule,
                'energie' => $energie,
                'categorie' => $categorie,
                'modele' => $modele,
                'marque' => $marque,
                'info_supp' => $info_supp,
                'reponse_apportee' => $reponse_apportee,
                'id_membre' => $id_membre,
                'id_etat' => $id_etat,
                'id_responsable' => $id_responsable
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d']
        );
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un recherche perso
     * 
     * @param int $id ID de la recherche perso
     * @param string $nom_véhicule Nom du véhicule
     * @param date $mise_circulation Mise en circulation du véhicule
     * @param decimal $kilometrage Kilomètre roulé
     * @param string $transmission Transmission du véhicule
     * @param int $puissance_fiscale Puissance fiscale du véhicule
     * @param int $puissance_DIN Puissance DIN du véhicule
     * @param string $couleur_ext Couleur extérieur
     * @param string $couleur_int Couleur intérieur
     * @param int $nbr_portes Nombre de portes
     * @param float $prix_vehicule Prix demandé
     * @param string $energie Energie du véhicule
     * @param string $categorie Categorie du véhicule 
     * @param string $modele Modèle du véhicule
     * @param string $marque Marque du véhicule
     * @param string $reponse_apportee Réponses apporté par le conseiller (ex : "1 véhicule trouvé" -> sous forme de lien)
     * @param int $id_membre Membre inscrit qui a fait la demande
     * @param int $id_etat Etat de la demande (Terminée, en cours ou à traiter)
     * @param int $id_responsable Conseiller attitré à la demande
     */
    public function update($id, $nom_vehicule, $modele, $marque, $categorie, $energie, $mise_circulation, $kilometrage, $transmission, $puissance_fiscale, $puissance_DIN, $couleur_ext, $couleur_int, $nbr_portes, $prix_vehicule, $info_supp, $reponse_apportee, $id_membre, $id_etat, $id_responsable)
    {
        $result = $this->wpdb->update(
            $this->table_name,
            [
                'nom_vehicule' => $nom_vehicule,
                'mise_en_circulation_vehicule' => $mise_circulation,
                'kilometrage_vehicule' => $kilometrage,
                'transmission_vehicule' => $transmission,
                'puissance_fiscale_vehicule' => $puissance_fiscale,
                'puissance_DIN_vehicule' => $puissance_DIN,
                'couleur_exterieur_vehicule' => $couleur_ext,
                'couleur_interieur_vehicule' => $couleur_int,
                'nbr_portes_vehicule' => $nbr_portes,
                'prix_vehicule' => $prix_vehicule,
                'energie' => $energie,
                'categorie' => $categorie,
                'modele' => $modele,
                'marque' => $marque,
                'info_supp' => $info_supp,
                'reponse_apportee' => $reponse_apportee,
                'id_membre' => $id_membre,
                'id_etat' => $id_etat,
                'id_responsable' => $id_responsable
            ],
            ['id_rechercheperso' => $id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d'],
            ['%d']
        ) !== false;
    }

    /**
     * Mettre à jour l'état (En attente, terminée) de la recherche personnalisé après l'avoir consulté
     * 
     * @param int $id ID de la recherche perso
     * @param int $etat_id ID de l'état
     */
    public function updateEtat($id, $etat_id)
    {
        return $this->wpdb->update(
            $this->table_name,
            ['id_etat' => $etat_id],
            ['id_rechercheperso' => $id],
            ['%d'],
            ['%d']
        ) !== false;;
    }

    /**
     * Affecter le responsable de la recherche personnalisé après l'avoir consulté
     * 
     * @param int $id ID de la recherche perso
     * @param int $etat_id ID de l'état
     */
    public function updateResponsable($id, $responsable_id)
    {
        return $this->wpdb->update(
            $this->table_name,
            ['id_responsable' => $responsable_id],
            ['id_rechercheperso' => $id],
            ['%d'],
            ['%d']
        );
    }

    /**
     * Supprimer un rechercheperso
     * 
     * @param int $id ID du rechercheperso à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_rechercheperso' => $id],
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
        id_rechercheperso INT(11) NOT NULL AUTO_INCREMENT,
        date_recherche DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        nom_vehicule VARCHAR(50) NOT NULL,
        modele VARCHAR(50),
        marque VARCHAR(50),
        categorie VARCHAR(50),
        energie VARCHAR(50),
        mise_en_circulation_vehicule VARCHAR(50),
        kilometrage_vehicule DECIMAL(8,2),
        transmission_vehicule VARCHAR(50),
        puissance_fiscale_vehicule INT(11),
        puissance_DIN_vehicule INT(11),
        couleur_exterieur_vehicule VARCHAR(50),
        couleur_interieur_vehicule VARCHAR(50),
        nbr_portes_vehicule INT(11),
        prix_vehicule INT(11) NOT NULL,
        info_supp VARCHAR(250),
        reponse_apportee VARCHAR(50),
        id_membre INT(11) NOT NULL, 
        id_etat INT(11),
        id_responsable INT(11) NULL,
        PRIMARY KEY (id_rechercheperso),
        KEY id_membre (id_membre),
        KEY id_etat (id_etat),
        KEY id_responsable (id_responsable)
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
