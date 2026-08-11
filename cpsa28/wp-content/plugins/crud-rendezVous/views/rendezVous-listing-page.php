<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les rendez-vous</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-rendezVous-planif'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>rendezVous ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>rendezVous supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($RDV)): ?>
        <p>Aucun rendezVous trouvé.</p>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-primary" style="width: 80px;">ID</th>
                    <th scope="col" class="manage-column">Date</th>
                    <th scope="col" class="manage-column">Heure</th>
                    <th scope="col" class="manage-column">Motif</th>
                    <th scope="col" class="manage-column">Destinataire</th>
                    <th scope="col" class="manage-column">Responsable</th>
                    <th scope="col" class="manage-column">Type de rendez-vous</th>
                    <th scope="col" class="manage-column" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($RDV as $unRDV): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($unRDV->id_rendezvous); ?></strong>
                        </td>
                        <td data-colname="Date">
                            <?php
                            echo date_i18n('j F Y', strtotime($unRDV->date_rdv));
                            ?>

                        </td>
                        <td data-colname="Heure">
                            <?php
                            echo date_i18n('H\hi', strtotime($unRDV->heure_rdv)); ?>
                        </td>
                        <td data-colname="Motif">
                            <?php echo esc_html($unRDV->motif_intitule); ?>
                        </td>
                        <td data-colname="Destinataire">
                            <?php echo esc_html($unRDV->membre_name ?? '—'); ?>
                            <?php echo esc_html($unRDV->membre_surname ?? '—'); ?>
                        </td>
                        <td data-colname="Responsable">
                            <?php echo esc_html($unRDV->responsable_name ?? '—'); ?>
                            <?php echo esc_html($unRDV->responsable_surname ?? '—'); ?>
                        </td>
                        <td data-colname="Type de rendez-vous">
                            <?php echo esc_html($unRDV->type_intitule); ?>
                        </td>

                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-rendezVous-planif&id=' . $unRDV->id_rendezvous); ?>"
                                class="button button-small button-edit">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-rendezVous-listing&action=delete&id=' . $unRDV->id_rendezvous), 'delete_rendezVous_' . $unRDV->id_rendezvous); ?>"
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

<!-- Tableau pour les motifs-->
<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les motifs</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-motif-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['addedmotif']) && $_GET['addedmotif'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>motif ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deletedmotif']) && $_GET['deletedmotif'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>motif supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($motifs)): ?>
        <p>Aucun motif le trouvé.</p>
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
                <?php foreach ($motifs as $motif): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($motif->id_motif); ?></strong>
                        </td>
                        <td data-colname="Intitule">
                            <?php echo esc_html($motif->intitule); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-motif-form&id=' . $motif->id_motif); ?>"
                                class="button button-small">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-membres-listing&action=delete-motif&id=' . $motif->id_motif), 'delete_motif_' . $motif->id_motif); ?>"
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

<!-- Tableau pour les types de rendez-vous-->
<div class="wrap">
    <h1 class="wp-heading-inline">Gérer les types de rendez-vous</h1>

    <a href="<?php echo admin_url('admin.php?page=crud-type-form'); ?>" class="page-title-action">Ajouter</a>

    <hr class="wp-header-end">

    <?php if (isset($_GET['addedtype']) && $_GET['addedtype'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>type ajouté avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deletedtype']) && $_GET['deletedtype'] == 1): ?>
        <div class="notice notice-success is-dismissible">
            <p>type supprimé avec succès.</p>
        </div>
    <?php endif; ?>

    <?php if (empty($types)): ?>
        <p>Aucun type le trouvé.</p>
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
                <?php foreach ($types as $type): ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">
                            <strong><?php echo esc_html($type->id_type); ?></strong>
                        </td>
                        <td data-colname="Intitule">
                            <?php echo esc_html($type->intitule); ?>
                        </td>
                        <td data-colname="Actions">
                            <a href="<?php echo admin_url('admin.php?page=crud-type-form&id=' . $type->id_type); ?>"
                                class="button button-small">
                                <i class="bi bi-pen"></i>
                            </a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=crud-membres-listing&action=delete-type&id=' . $type->id_type), 'delete_type_' . $type->id_type); ?>"
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