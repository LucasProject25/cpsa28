<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = 'Consulter une demande';
$button_valid = 'Terminée';
$button_attente = 'Mettre en attente';
$button_add = 'Ajouter';
$modele_value = $customer_details ? esc_attr($recherches->modele) : '';
$marque_value = $customer_details ? esc_attr($recherches->marque) : '';
$energie_value = $customer_details ? esc_attr($recherches->energie) : '';
$kilometrage_value = $customer_details ? esc_attr($recherches->kilometrage_vehicule) : '';
$info_supp_value = $customer_details ? esc_textarea($recherches->info_supp) : '';
$categorie_value = $customer_details ? esc_attr($recherches->categorie) : '';
$mise_circulation_value = $customer_details ? esc_attr($recherches->mise_en_circulation_vehicule) : '';
$transmission_value = $customer_details ? esc_attr($recherches->transmission_vehicule) : '';
$prix_value = $customer_details ? esc_attr($recherches->prix_vehicule) : '';

?>

<div class="wrap">
    <h1>
        <?php if (!empty($membre)): ?>
            <?php echo esc_html($membre->name . ' ' . $membre->surname); ?>
        <?php else: ?>
            Demande
        <?php endif; ?>
    </h1>
    <form method="post" enctype="multipart/form-data">
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] === 'empty'): ?>
                <div class="notice notice-error is-dismissible">
                    <p>Les champs ne peuvent pas être vide.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        <?php if ($customer_details): ?>
            <input type="hidden" name="id_rechercheperso" value="<?php echo esc_attr($recherches->id_rechercheperso); ?>">
        <?php endif; ?>

        <?php wp_nonce_field('crud_consult_save', 'crud_consult_nonce'); ?>
        <div class="container">
            <div class="myCard">
                <h2>Détails de la demande</h2>
                <div class="myCard__details">
                    <div class="myCard__lines">
                        <div class="myCard__line">
                            <p class="myCard__title">Marque</p>
                            <p><?php echo $marque_value; ?></p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Modèle</p>
                            <p><?php echo $modele_value; ?></p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Mise en circulation</p>
                            <p><?php echo date_i18n('Y', strtotime($mise_circulation_value)); ?></p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Kilométrage</p>
                            <p><?php echo $kilometrage_value; ?> km</p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Budget</p>
                            <p><?php echo $prix_value; ?> €</p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Energie</p>
                            <p><?php echo $energie_value; ?></p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Transmission</p>
                            <p><?php echo $transmission_value; ?></p>
                        </div>
                        <div class="myCard__line">
                            <p class="myCard__title">Catégorie</p>
                            <p><?php echo $categorie_value; ?></p>
                        </div>
                    </div>
                    <div class="myCard__infoSupp">
                        <p class="myCard__title">Informations supplémentaire</p>
                        <p><?php echo $info_supp_value; ?></p>
                    </div>
                </div>
            </div>
            <div class="container__bottom">

                <div class="myCard myCard--docs">
                    <h2>Documents associés</h2>
                    <?php wp_nonce_field('crud_document_save', 'crud_document_nonce'); ?>
                    <?php if ($customer_details): ?>
                        <input type="hidden" name="id_rechercheperso" value="<?php echo esc_attr($recherches->id_rechercheperso); ?>">
                    <?php endif; ?>
                    <input type="file" name="nom_document">
                    <div class="myCard__docs">
                        <?php if (!empty($documents)): ?>
                            <?php foreach ($documents as $doc): ?>
                                <div class="myCard__doc">
                                    <p><?php echo esc_html($doc->nom_document); ?></p>
                                    <a href="<?php echo wp_nonce_url(
                                                    admin_url(
                                                        'admin.php?page=recherche-consult'
                                                            . '&action=delete-document'
                                                            . '&id=' . $doc->id_document
                                                            . '&id_rechercheperso=' . $recherches->id_rechercheperso
                                                    ),
                                                    'delete_document_' . $doc->id_document
                                                ); ?>"
                                        class="button button-small button-link-delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun document</p>
                        <?php endif; ?>
                    </div>
                    <div class="myCard__valid">
                        <input
                            type="submit"
                            name="crud_document_submit"
                            id="submit"
                            class="button button-primary"
                            value="<?php echo $button_add; ?>">
                    </div>
                </div>
                <div class="container__right">
                    <div class="myCard myCard--propositions">
                        <div class="myCard__header">
                            <h2>Propositions en cours</h2>
                            <a href="<?php echo admin_url('admin.php?page=recherche-consult-proposition-form&id=' . $recherches->id_rechercheperso); ?>" class="page-title-action">Ajouter</a>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col" class="manage-column column-primary" style="width: 80px;">Modèle</th>
                                    <th scope="col" class="manage-column column-primary" style="width: 80px;">Année</th>
                                    <th scope="col" class="manage-column column-primary" style="width: 80px;">Prix</th>
                                    <th scope="col" class="manage-column column-primary" style="width: 80px;">Statut</th>
                                    <th scope="col" class="manage-column column-primary" style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($propositions)): ?>
                                    <?php foreach ($propositions as $prop): ?>
                                        <tr>
                                            <td class="column-primary" data-colname="Modèle">
                                                <?php echo esc_html($prop->modele); ?>
                                            </td>
                                            <td class="column-primary" data-colname="Année">
                                                <?php echo date_i18n('Y', strtotime($prop->annee)); ?>
                                            </td>
                                            <td class="column-primary" data-colname="Prix">
                                                <?php echo esc_html($prop->prix); ?> €
                                            </td>
                                            <td class="column-primary" data-colname="Statut">
                                                <?php echo esc_html($prop->statut); ?>
                                            </td>
                                            <td class="column-primary" data-colname="Action">
                                                <a href="<?php echo wp_nonce_url(
                                                                admin_url(
                                                                    'admin.php?page=recherche-consult'
                                                                        . '&action=delete-proposition'
                                                                        . '&id=' . $prop->id_proposition
                                                                        . '&id_rechercheperso=' . $recherches->id_rechercheperso
                                                                ),
                                                                'delete_proposition_' . $prop->id_proposition
                                                            ); ?>"
                                                    class="button button-small button-link-delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="column-primary" data-colname="Modèle">

                                        </td>
                                        <td class="column-primary" data-colname="Année">

                                        </td>
                                        <td class="column-primary" data-colname="Prix">

                                        </td>
                                        <td class="column-primary" data-colname="Statut">

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="myCard myCard--conseiller">
                        <h2>Conseiller assigné</h2>
                        <select name="id_responsable" id="id_responsable" class="form__input" required>
                            <option value="">-- Sélectionnez un conseiller --</option>
                            <?php foreach ($responsables as $responsable): ?>
                                <?php if ($responsable->id_role == 1): ?>
                                    <option
                                        value="<?php echo esc_attr($responsable->id_membre); ?>"
                                        <?php selected($customer_details ? $recherches->id_responsable : '', $responsable->id_membre); ?>>
                                        <?php echo esc_html($responsable->name . ' ' . $responsable->surname); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <!-- <input
                            type="submit"
                            name="crud_affect_submit"
                            id="submit"
                            class="button button-primary"
                            value="Affecter"> -->
                    </div>
                    <div class="form__buttons">
                        <input
                            type="submit"
                            name="crud_consult_submit"
                            id="submit"
                            class="button button-primary"
                            value="<?php echo $button_valid; ?>">
                        <input
                            type="submit"
                            name="crud_consult_submit_attente"
                            id="submit"
                            class="button button-primary"
                            value="<?php echo $button_attente; ?>">
                        <a href="<?php echo admin_url('admin.php?page=crud-recherche-listing'); ?>" class="button">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>