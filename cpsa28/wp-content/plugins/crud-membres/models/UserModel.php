<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modèle pour gérer les utilisateurs
 */
class UserModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'customer';
    }

    /**
     * Récupérer tout les clients
     * 
     * @return array Liste des clients
     */
    public function getAll()
    {
        global $wpdb;

        $table_statut = $wpdb->prefix . 'statut';

        $sql = "SELECT c.*,
        s.name AS nom_statut
         FROM {$this->table_name} c 
         LEFT JOIN $table_statut s ON c.id_statut = s.id_statut
         ORDER BY id_customer DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un client par son ID
     * 
     * @param int $id ID du client
     * @return object|null Le client ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_customer = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau client
     * (Sera géré grâce aux formulaire d'inscription)
     * 
     * @param string $name Nom du client
     * @param string $surname Prénom du client
     * @param date $birthday Naissance du client
     * @param string $pays Pays du client
     * @param string $ville Ville du client
     * @param string $cp Code postal du client
     * @param string $phone Numéro de téléphone du client
     * @param string $email Email du client
     * @param string $password MDP du client
     * @param int $id_statut Statut du client
     */
    public function create($name, $surname, $birthday, $pays, $ville, $cp, $phone, $email, $password, $id_statut)
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
                'name' => $name,
                'surname' => $surname,
                'birthday' => $birthday,
                'pays' => $pays,
                'ville' => $ville,
                'cp' => $cp,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'id_statut' => $id_statut,
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d']
        );
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Mettre à jour un client
     * (Idem que la création, ça sera le client qui mettra à jour. L'admin pourra mettre à jour que le statut si besoin)
     * 
     * @param int $id ID du client
     * @param string $name Nouveau nom
     * @param string $surname Nouveau prénom
     * @param date $birthday Nouvel date anniv
     * @param string $pays Pays du client
     * @param string $ville Ville du client
     * @param string $cp Code postal du client
     * @param telephone $phone Nouveau numéro
     * @param string $email Nouvel email
     * @param string $password Nouveau mot de passe
     * @param int $id_statut Nouveau statut
     */
    public function update($id, $name, $surname, $birthday, $pays, $ville, $cp, $phone, $email, $password, $id_statut)
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
                'name' => $name,
                'surname' => $surname,
                'birthday' => $birthday,
                'pays' => $pays,
                'ville' => $ville,
                'cp' => $cp,
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'id_statut' => $id_statut,
            ],
            ['id_customer' => $id],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'],
            ['%d']
        ) !== false;
    }

    /**
     * Supprimer un client
     * 
     * @param int $id ID du client à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_customer' => $id],
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
        id_customer int(11) NOT NULL AUTO_INCREMENT,
        id_statut INT(11) NULL,
        name varchar(50) NOT NULL,
        surname varchar(50) NOT NULL,
        birthday DATE NULL,
        pays varchar(50) NULL,
        ville varchar(50) NULL,
        cp varchar(50) NULL,
        phone varchar(50) NULL,
        email varchar(50) NOT NULL,
        password varchar(255) NOT NULL,
        PRIMARY KEY (id_customer),
        KEY id_statut (id_statut)
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
