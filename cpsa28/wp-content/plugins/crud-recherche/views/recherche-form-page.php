<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier une recherche' : 'Faire une recherche personnalisée';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$nom_value = $is_edit ? esc_attr($recherches->nom_vehicule) : '';
$modele_value = $is_edit ? esc_attr($recherches->modele) : '';
$marque_value = $is_edit ? esc_attr($recherches->marque) : '';
$energie_value = $is_edit ? esc_attr($recherches->energie) : '';
$kilometrage_value = $is_edit ? esc_attr($recherches->kilometrage_vehicule) : '';
$puissance_fiscale_value = $is_edit ? esc_attr($recherches->puissance_fiscale_vehicule) : '';
$couleur_ext_value = $is_edit ? esc_attr($recherches->couleur_exterieur_vehicule) : '';
$nbr_portes_value = $is_edit ? esc_attr($recherches->nbr_portes_vehicule) : '';
$info_supp_value = $is_edit ? esc_textarea($recherches->info_supp) : '';
$categorie_value = $is_edit ? esc_attr($recherches->categorie) : '';
$mise_circulation_value = $is_edit ? esc_attr($recherches->mise_en_circulation_vehicule) : '';
$transmission_value = $is_edit ? esc_attr($recherches->transmission_vehicule) : '';
$puissance_din_value = $is_edit ? esc_attr($recherches->puissance_DIN_vehicule) : '';
$couleur_int_value = $is_edit ? esc_attr($recherches->couleur_interieur_vehicule) : '';
$prix_value = $is_edit ? esc_attr($recherches->prix_vehicule) : '';


print_r($recherches);
?>

<div class="wrap">
    <h1><?php echo $page_title; ?></h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'empty'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Les champs ne peuvent pas être vide.</p>
            </div>
        <?php elseif ($_GET['error'] === 'invalid_date'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Date invalide</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_recherche_save', 'crud_recherche_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_rechercheperso" value="<?php echo esc_attr($recherches->id_rechercheperso); ?>">
        <?php endif; ?>

        <div class="form" role="presentation">
            <div class="form__container">
                <div class="form__inputs">
                    <label class="form__label" for="id_membre">Membre <span class="description">(requis)</span></label>
                    <select name="id_membre" id="id_membre" class="form__input" required>
                        <option value="">-- Sélectionnez un membre --</option>
                        <?php foreach ($membres as $membre): ?>
                            <option
                                value="<?php echo esc_attr($membre->id_customer); ?>"
                                <?php selected($is_edit ? $recherches->id_membre : '', $membre->id_customer); ?>>
                                <?php echo esc_html($membre->name); ?>
                                <?php echo esc_html($membre->surname); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form__columns">
                    <div class="form__firstColumn">
                        <div class="form__inputs">
                            <label class="form__label" for="nom_vehicule">Nom du véhicule<span class="description">(requis)</span></label>
                            <input
                                type="text"
                                name="nom_vehicule"
                                id="nom_vehicule"
                                value="<?php echo $nom_value; ?>"
                                class="form__input"
                                required>
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="marque">Marque</label>
                            <input type="text" name="marque" id="marque"
                                value="<?php echo $marque_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="energie">Énergie</label>
                            <input type="text" name="energie" id="energie"
                                value="<?php echo $energie_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="kilometrage_vehicule">Kilométrage</label>
                            <input type="number" step="0.01" name="kilometrage_vehicule" id="kilometrage_vehicule"
                                value="<?php echo $kilometrage_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="puissance_fiscale_vehicule">Puissance fiscale</label>
                            <input type="number" name="puissance_fiscale_vehicule" id="puissance_fiscale_vehicule"
                                value="<?php echo $puissance_fiscale_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="couleur_exterieur_vehicule">Couleur extérieure</label>
                            <input type="text" name="couleur_exterieur_vehicule" id="couleur_exterieur_vehicule"
                                value="<?php echo $couleur_ext_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="nbr_portes_vehicule">Nombre de portes</label>
                            <input type="number" name="nbr_portes_vehicule" id="nbr_portes_vehicule"
                                value="<?php echo $nbr_portes_value; ?>"
                                class="form__input">
                        </div>
                    </div>
                    <div class="form__secondColumn">
                        <div class="form__inputs">
                            <label class="form__label" for="modele">Modèle</label>
                            <input
                                type="text"
                                name="modele"
                                id="modele"
                                value="<?php echo $modele_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="categorie">Catégorie</label>
                            <input type="text" name="categorie" id="categorie"
                                value="<?php echo $categorie_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="mise_en_circulation_vehicule">Mise en circulation</label>
                            <input type="date" name="mise_en_circulation_vehicule" id="mise_en_circulation_vehicule"
                                value="<?php echo $mise_circulation_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="transmission_vehicule">Transmission</label>
                            <input type="text" name="transmission_vehicule" id="transmission_vehicule"
                                value="<?php echo $transmission_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="puissance_DIN_vehicule">Puissance DIN</label>
                            <input type="number" name="puissance_DIN_vehicule" id="puissance_DIN_vehicule"
                                value="<?php echo $puissance_din_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="couleur_interieur_vehicule">Couleur intérieure</label>
                            <input type="text" name="couleur_interieur_vehicule" id="couleur_interieur_vehicule"
                                value="<?php echo $couleur_int_value; ?>"
                                class="form__input">
                        </div>
                        <div class="form__inputs">
                            <label class="form__label" for="prix_vehicule">Prix <span class="description">(requis)</span></label>
                            <input type="number" name="prix_vehicule" id="prix_vehicule"
                                value="<?php echo $prix_value; ?>"
                                class="form__input"
                                required>
                        </div>
                    </div>
                </div>
                <div class="form__inputs">
                    <label class="form__label" for="info_supp">Informations supplémentaires</label>
                    <textarea class="form__textarea" name="info_supp" id="info_supp" class="form__input" rows="4"><?php echo $info_supp_value; ?></textarea>
                </div>
                <div class="form__inputs">
                    <label class="form__label" for="id_responsable">Conseiller <span class="description">(requis)</span></label>
                    <select name="id_responsable" id="id_responsable" class="form__input">
                        <option value="">-- Sélectionnez un conseiller --</option>
                        <?php foreach ($responsables as $responsable): ?>
                            <?php if ($responsable->id_role == 1): ?>
                                <option
                                    value="<?php echo esc_attr($responsable->id_membre); ?>"
                                    <?php selected($is_edit ? $recherches->id_responsable : '', $responsable->id_membre); ?>>
                                    <?php echo esc_html($responsable->name . ' ' . $responsable->surname); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>
        </div>
        <div class="form__buttons">
            <input
                type="submit"
                name="crud_recherche_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-recherche-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>

    </form>
</div>