<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Gérer l'équipe</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-equipe-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Membre ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Membre supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($membres)): ?>
        <p>Aucun membre trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Prénom</th>
                    <th scope="col" class="manage-column">Nom</th>
                    <th scope="col" class="manage-column">Email</th>
                    <th scope="col" class="manage-column">Rôle</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($membres as $membre): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($membre->id_membre); ?></strong>
                        </td>
                        <td data-colname="Prénom">
                            <?php echo esc_html($membre->name); ?>
                        </td>
                        <td data-colname="Nom">
                            <?php echo esc_html($membre->surname); ?>
                        </td>
                        <td data-colname="Email">
                            <?php echo esc_html($membre->email); ?>
                        </td>
                        <td data-colname="Role">
                            <?php echo esc_html($membre->nom_role); ?>
                        </td>
                        <td data-colname="Actions">
                            <div class="buttons">
                                <a href="<?php echo admin_url('admin.php?page=crud-equipe-form&id=' . $membre->id_membre); ?>"
                                    class="button button-small button-edit">
                                    <i class="bi bi-pen"></i>
                                </a>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-equipe-listing&action=delete&id=' . $membre->id_membre), 'delete_membre_' . $membre->id_membre); ?>"
                                    class="button button-small button-link-delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les rôles</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-role-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['addedrole']) && $_GET['addedrole'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Rôle ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deletedrole']) && $_GET['deletedrole'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Rôle supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($roles)): ?>
        <p>Aucun rôle trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Nom</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($role->id_role); ?></strong>
                        </td>
                        <td data-colname="Nom">
                            <?php echo esc_html($role->name); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-role-form&id=' . $role->id_role); ?>"
                                class="button button-small button-edit">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-equipe-listing&action=delete-role&id=' . $role->id_role), 'delete_role_' . $role->id_role); ?>"
                                class="button button-small button-link-delete">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>