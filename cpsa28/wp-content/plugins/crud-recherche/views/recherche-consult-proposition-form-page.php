<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = 'Ajouter une proposition';
$button_text = 'Ajouter';
?>

<div class="wrap">
    <h1><?php echo $page_title; ?></h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'empty'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Les champs ne peuvent pas être vide.</p>
            </div>
        <?php elseif ($_GET['error'] === 'toolong'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Les champs ne peuvent pas dépasser 100 caractères.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_proposition_save', 'crud_proposition_nonce'); ?>
        <input type="hidden" name="id_rechercheperso" value="<?php echo esc_attr($recherches->id_rechercheperso); ?>">
        <div class="form" role="presentation">
            <div class="form-container">
                <div class="form__columns">
                    <div class="form__firstColumn">
                        <div class="form__inputs">
                            <label for="modele">Modèle du véhicule <span class="description">(requis)</span></label>
                            <input
                                type="text"
                                name="modele"
                                id="modele"
                                class="form__input"
                                maxlength="100"
                                required>
                            <p class="description">Maximum 100 caractères.</p>
                        </div>
                        <div class="form__inputs">
                            <label for="prix">Prix du véhicule <span class="description">(requis)</span></label>
                            <input
                                type="number"
                                name="prix"
                                id="prix"
                                class="form__input"
                                maxlength="100"
                                required>
                        </div>
                    </div>
                    <div class="form__secondColumn">
                        <div class="form__inputs">
                            <label for="annee">Mise en circulation du véhicule <span class="description">(requis)</span></label>
                            <input
                                type="date"
                                name="annee"
                                id="annee"
                                class="form__input"
                                maxlength="100"
                                required>
                        </div>
                        <div class="form__inputs">
                            <label for="statut">Statut du véhicule <span class="description">(requis)</span></label>
                            <input
                                type="text"
                                name="statut"
                                id="statut"
                                class="form__input"
                                maxlength="100"
                                required>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form__buttons">
            <input
                type="submit"
                name="crud_proposition_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=recherche-consult&id=' . $recherches->id_rechercheperso); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>
    </form>
</div>