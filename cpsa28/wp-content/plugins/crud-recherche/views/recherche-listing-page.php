<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Liste des demandes</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-recherche-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>recherche ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>recherche supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($recherches)): ?>
        <p>Aucune recherche trouvée.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Nom du membre</th>
                    <th scope="col" class="manage-column">Demande</th>
                    <th scope="col" class="manage-column">Date</th>
                    <th scope="col" class="manage-column">Conseiller</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recherches as $recherche): ?>
                    <?php
                    $row_class = '';

                    if ($recherche->id_etat == 2) {
                        $row_class = 'orange';
                    } elseif ($recherche->id_etat == 3) {
                        $row_class = 'green';
                    } else {
                        $row_class = '';
                    }

                    ?>
                    <tr class="<?php echo esc_attr($row_class); ?>">
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($recherche->id_rechercheperso); ?></strong>
                        </td>
                        <td data-colname="Nom du membre">
                            <?php echo esc_html($recherche->membre_name ?? '—'); ?>
                            <?php echo esc_html($recherche->membre_surname ?? '—'); ?>

                        </td>
                        <td data-colname="Demande">
                            <?php echo esc_html($recherche->nom_vehicule); ?>
                        </td>
                        <td data-colname="Date">
                            <?php echo date_i18n('d/m/Y', strtotime($recherche->date_recherche)); ?>
                        </td>
                        <td data-colname="Conseiller">
                            <?php echo esc_html($recherche->responsable_name ?? '—'); ?>
                            <?php echo esc_html($recherche->responsable_surname ?? '—'); ?>
                        </td>

                        <td data-colname="Actions">
                            <div class="container__buttons">
                                <div class="buttons__top">
                                    <a href="<?php echo admin_url('admin.php?page=recherche-consult&id=' . $recherche->id_rechercheperso); ?>" class="button button-edit">
                                        Consulter <i class="bi bi-file-earmark-arrow-up"></i>
                                    </a>
                                </div>
                                <div class="buttons__bot">
                                    <a href="<?php echo admin_url('admin.php?page=crud-recherche-form&id=' . $recherche->id_rechercheperso); ?>"
                                        class="button button-small button-edit">
                                        <i class="bi bi-pen"></i>
                                    </a>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-recherche-listing&action=delete&id=' . $recherche->id_rechercheperso), 'delete_recherche_' . $recherche->id_rechercheperso); ?>"
                                        class="button button-small button-link-delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Tableau pour les états-->
<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les états</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-etat-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['addedetat']) && $_GET['addedetat'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>etat ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deletedetat']) && $_GET['deletedetat'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>etat supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($etats)): ?>
        <p>Aucun etat le trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Intitule</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etats as $etat): ?>
                    <?php
                    $row_class = '';

                    if ($etat->id_etat == 2) {
                        $row_class = 'orange';
                    } elseif ($etat->id_etat == 3) {
                        $row_class = 'green';
                    } else {
                        $row_class = '';
                    }

                    ?>
                    <tr class="<?php echo esc_attr($row_class); ?>">
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($etat->id_etat); ?></strong>
                        </td>
                        <td data-colname="Intitule">
                            <?php echo esc_html($etat->intitule); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-etat-form&id=' . $etat->id_etat); ?>"
                                class="button button-small">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-recherche-listing&action=delete-etat&id=' . $etat->id_etat), 'delete_etat_' . $etat->id_etat); ?>"
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