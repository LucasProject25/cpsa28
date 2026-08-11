<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modèle pour gérer les rendez-vous
 */
class RendezVousModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'rendezvous';
    }

    /**
     * Récupérer tout les rendez-vous
     * 
     * @return array Liste des rendez-vous
     */
    public function getAll()
    {
        global $wpdb;

        $table_membres = $wpdb->prefix . 'customer';
        $table_responsable = $wpdb->prefix . 'equipe';
        $table_motif = $wpdb->prefix . 'motif';
        $table_type = $wpdb->prefix . 'types';

        $sql = "SELECT r.*, 
        m.name AS membre_name, 
        m.surname AS membre_surname,
        res.name AS responsable_name, 
        res.surname AS responsable_surname,
        mo.intitule AS motif_intitule,
        ty.intitule AS type_intitule
        FROM {$this->table_name} r
        JOIN $table_membres m ON r.id_membre = m.id_customer 
        LEFT JOIN $table_responsable res ON r.id_responsable = res.id_membre 
        JOIN $table_motif mo ON r.id_motif = mo.id_motif 
        JOIN $table_type ty ON r.id_type = ty.id_type
        ORDER BY id_rendezvous DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un rendez-vous par son ID
     * 
     * @param int $id ID du rendez-vous
     * @return object|null Le rendez-vous ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_rendezvous = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau rendez-vous
     * 
     * @param date $date Date du rendez-vous
     * @param time $heure Heure du rendez-vous
     * @param int $id_motif Motif du rendez-vous
     * @param int $id_membre Destinataire
     * @param int $id_responsable Responsable
     * @param int $id_type Type de rendez-vous
     */
    public function create($date, $heure, $id_motif, $id_membre, $id_responsable, $id_type)
    {

        $motif_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}motif WHERE id_motif = %d",
                $id_motif
            )
        );

        if (!$motif_exists) {
            return new WP_Error('invalid_motif', 'Motif inexistant');
        }


        $membre_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}customer WHERE id_customer = %d",
                $id_membre
            )
        );

        if (!$membre_exists) {
            return new WP_Error('invalid_membre', 'Membre inexistant');
        }

        $responsable_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}equipe WHERE id_membre = %d",
                $id_responsable
            )
        );

        if (!$responsable_exists) {
            return new WP_Error('invalid_responsable', 'Responsable inexistant');
        }

        $type_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}types WHERE id_type = %d",
                $id_type
            )
        );

        if (!$type_exists) {
            return new WP_Error('invalid_type', 'Type inexistant');
        }

        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'date_rdv' => $date,
                'heure_rdv' => $heure,
                'id_motif' => $id_motif,
                'id_membre' => $id_membre,
                'id_responsable' => $id_responsable,
                'id_type' => $id_type,
            ],
            ['%s', '%s', '%d', '%d', '%d', '%d']
        );
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un rendez-vous
     * @param int $id ID du rendez-vous
     * @param date $date Date du rendez-vous
     * @param time $heure Heure du rendez-vous
     * @param int $id_motif Nouveau motif
     * @param int $id_membre Nouveau destinataire
     * @param int $id_responsable Nouveau responsable
     * @param int $id_type Type de rendez-vous
     */
    public function update($id, $date, $heure, $id_motif, $id_membre, $id_responsable, $id_type)
    {

        $motif_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}motif WHERE id_motif = %d",
                $id_motif
            )
        );

        if (!$motif_exists) {
            return new WP_Error('invalid_motif', 'Motif inexistant');
        }

        $membre_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}customer WHERE id_customer = %d",
                $id_membre
            )
        );
        if (!$membre_exists) {
            return new WP_Error('invalid_membre', 'Membre inexistant');
        }

        $responsable_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}equipe WHERE id_membre = %d",
                $id_responsable
            )
        );

        if (!$responsable_exists) {
            return new WP_Error('invalid_responsable', 'Responsable inexistant');
        }

        $type_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}types WHERE id_type = %d",
                $id_type
            )
        );

        if (!$type_exists) {
            return new WP_Error('invalid_type', 'Type inexistant');
        }

        return $this->wpdb->update(
            $this->table_name,
            [
                'date_rdv' => $date,
                'heure_rdv' => $heure,
                'id_motif' => $id_motif,
                'id_membre' => $id_membre,
                'id_responsable' => $id_responsable,
                'id_type' => $id_type,
            ],
            ['id_rendezVous' => $id],
            ['%s', '%s', '%d', '%d', '%d', '%d'],
            ['%d']
        ) !== false;
    }

    /**
     * Supprimer un rendez-vous
     * 
     * @param int $id ID du rendez-vous à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_rendezvous' => $id],
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
        id_rendezvous INT(11) NOT NULL AUTO_INCREMENT,
        date_rdv DATE NOT NULL,
        heure_rdv TIME NOT NULL,
        id_motif INT(11) NOT NULL,
        id_membre INT(11) NOT NULL,
        id_responsable INT(11) NULL,
        id_type INT(11) NOT NULL,
        PRIMARY KEY (id_rendezvous),
        KEY id_motif (id_motif),
        KEY id_membre (id_membre),
        KEY id_responsable (id_responsable),
        KEY id_type (id_type)
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
