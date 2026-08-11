<?php
// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modèle pour gérer les membres
 */
class MembreModel
{
    private $table_name;
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'equipe';
    }

    /**
     * Récupérer tous les membres
     * 
     * @return array Liste des membres
     */
    public function getAll()
    {
        global $wpdb;
        $table_role = $wpdb->prefix . 'role';

        $sql = "SELECT e.*,
        r.name AS nom_role
        FROM {$this->table_name} e
        JOIN $table_role r ON e.id_role = r.id_role
        ORDER BY id_membre DESC";
        return $this->wpdb->get_results($sql);
    }

    /**
     * Récupérer un membre par son ID
     * 
     * @param int $id ID du membre
     * @return object|null Le membre ou null si non trouvé
     */
    public function getById($id)
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id_membre = %d",
                $id
            )
        );
    }

    /**
     * Créer un nouveau membre
     * 
     * @param string $name Prénom du membre
     * @param string $surname Nom du membre
     * @param string $email Email du membre
     * @param int $id_role Role du membre
     * @return int|false ID du membre créé ou false en cas d'erreur
     */
    /* public function create($name, $email, $role)
    {
        $result = $this->wpdb->insert(
            $this->table_name,
            ['name' => $name, 'email' => $email, 'role' => $role],
            ['%s']
        );

        return $result !== false ? $this->wpdb->insert_id : false;
    } */
    public function create($name, $surname, $email, $id_role)
    {
        // 1️⃣ Vérifier que le rôle existe
        $role_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}role WHERE id_role = %d",
                $id_role
            )
        );

        if (!$role_exists) {
            return new WP_Error('invalid_role', 'Rôle inexistant');
        }

        // 2️⃣ Insert
        $result = $this->wpdb->insert(
            $this->table_name,
            [
                'name'    => $name,
                'surname'    => $surname,
                'email'   => $email,
                'id_role' => $id_role,
            ],
            ['%s', '%s', '%s', '%d']
        );

        return $result !== false ? $this->wpdb->insert_id : false;
    }


    /**
     * Mettre à jour un membre
     * 
     * @param int $id ID du membre
     * @param string $name Nouveau prénom
     * @param string $surname Nouveau nom
     * @param string $email Nouvel email
     * @param int $id_role Nouveau role
     * @return bool True si succès, false sinon
     */
    /* public function update($id, $name, $email, $role)
    {
        return $this->wpdb->update(
            $this->table_name,
            ['name' => $name, 'email' => $email, 'role' => $role],
            ['id_membre' => $id],
            ['%s'],
            ['%d']
        ) !== false;
    } */

    public function update($id, $name, $surname, $email, $id_role)
    {
        // 1️⃣ Vérifier que le rôle existe
        $role_exists = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->wpdb->prefix}role WHERE id_role = %d",
                $id_role
            )
        );

        if (!$role_exists) {
            return new WP_Error('invalid_role', 'Rôle inexistant');
        }

        // 2️⃣ Update
        return $this->wpdb->update(
            $this->table_name,
            [
                'name'    => $name,
                'surname' => $surname,
                'email'   => $email,
                'id_role' => $id_role,
            ],
            ['id_membre' => $id],
            ['%s', '%s', '%s', '%d'],
            ['%d']
        ) !== false;
    }


    /**
     * Supprimer un membre
     * 
     * @param int $id ID du membre à supprimer
     * @return bool True si succès, false sinon
     */
    public function delete($id)
    {
        return $this->wpdb->delete(
            $this->table_name,
            ['id_membre' => $id],
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
            id_membre int(11) NOT NULL AUTO_INCREMENT,
            id_role INT(11) NOT NULL,
            name varchar(50) NOT NULL,
            surname varchar(50) NOT NULL,
            email varchar(50) NOT NULL,
            PRIMARY KEY (id_membre),
            KEY id_role (id_role)
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
