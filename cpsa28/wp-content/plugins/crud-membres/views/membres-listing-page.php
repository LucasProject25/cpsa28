<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les membres</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-membres-form'); ?>" class="page-title-action">Ajouter</a>

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

    <?php if (empty($customers)): ?>
        <p>Aucun membre trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Nom</th>
                    <th scope="col" class="manage-column">Prénom</th>
                    <th scope="col" class="manage-column">Naissance</th>
                    <th scope="col" class="manage-column">Pays</th>
                    <th scope="col" class="manage-column">Ville</th>
                    <th scope="col" class="manage-column">Code postal</th>
                    <th scope="col" class="manage-column">Téléphone</th>
                    <th scope="col" class="manage-column">Email</th>
                    <th scope="col" class="manage-column">Statut</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($customer->id_customer); ?></strong>
                        </td>
                        <td data-colname="Nom">
                            <?php echo esc_html($customer->name); ?>
                        </td>
                        <td data-colname="Prénom">
                            <?php echo esc_html($customer->surname); ?>
                        </td>
                        <?php if ($customer->birthday == null) : ?>
                            <td data-colname="Naissance">
                                <?php echo '—'; ?>
                            </td>
                        <?php else: ?>
                            <td data-colname="Naissance">
                                <?php echo date('j/m/Y', strtotime($customer->birthday));
                                ?>
                            </td>
                        <?php endif ?>
                        <td data-colname="Pays">
                            <?php echo esc_html($customer->pays ?? '—'); ?>
                        </td>
                        <td data-colname="Ville">
                            <?php echo esc_html($customer->ville ?? '—'); ?>
                        </td>
                        <td data-colname="Code postal">
                            <?php echo esc_html($customer->cp ?? '—'); ?>
                        </td>
                        <td data-colname="Téléphone">
                            <?php echo esc_html($customer->phone ?? '—'); ?>
                        </td>
                        <td data-colname="Email">
                            <?php echo esc_html($customer->email); ?>
                        </td>
                        <td data-colname="Statut">
                            <?php echo esc_html($customer->nom_statut ?? '—'); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-membres-form&id=' . $customer->id_customer); ?>"
                                class="button button-small button-edit">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-membres-listing&action=delete&id=' . $customer->id_customer), 'delete_membre_' . $customer->id_customer); ?>"
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

<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les statuts</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-statut-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['addedstatut']) && $_GET['addedstatut'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Statut ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deletedstatut']) && $_GET['deletedstatut'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Statut supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($statuts)): ?>
        <p>Aucun statut le trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Nom</th>
                    <th scope="col" class="manage-column">Icone</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statuts as $statut): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($statut->id_statut); ?></strong>
                        </td>
                        <td data-colname="Nom">
                            <?php echo esc_html($statut->name); ?>
                        </td>
                        <td data-colname="Icone">
                            <?php echo esc_html($statut->icon); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-statut-form&id=' . $statut->id_statut); ?>"
                                class="button button-small button-edit">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-membres-listing&action=delete-statut&id=' . $statut->id_statut), 'delete_statut_' . $statut->id_statut); ?>"
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
<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les privilèges</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-privilege-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['addedprivilege']) && $_GET['addedprivilege'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Privilège ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deletedprivilege']) && $_GET['deletedprivilege'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>Privilège supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($privileges)): ?>
        <p>Aucun privilège le trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Intitule</th>
                    <th scope="col" class="manage-column">Statut</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($privileges as $privilege): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($privilege->id_privilege); ?></strong>
                        </td>
                        <td data-colname="Nom">
                            <?php echo esc_html($privilege->intitule); ?>
                        </td>
                        <td data-colname="Statut">
                            <?php echo esc_html($privilege->nom_statut); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-privilege-form&id=' . $privilege->id_privilege); ?>"
                                class="button button-small button-edit">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-membres-listing&action=delete-privilege&id=' . $privilege->id_privilege), 'delete_privilege_' . $privilege->id_privilege); ?>"
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