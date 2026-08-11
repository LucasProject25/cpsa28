<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un etat' : 'Ajouter un etat';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$intitule_value = $is_edit ? esc_attr($etats->intitule) : '';
?>

<div class="wrap">
    <h1><?php echo $page_title; ?></h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'empty'): ?>
            <div class="notice notice-error is-dismissible">
                <p>L'intitulé ne peut pas être vide.</p>
            </div>
        <?php elseif ($_GET['error'] === 'toolong'): ?>
            <div class="notice notice-error is-dismissible">
                <p>L'intitulé ne peut pas dépasser 100 caractères.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_etat_save', 'crud_etat_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_etat" value="<?php echo esc_attr($etats->id_etat); ?>">
        <?php endif; ?>

        <div class="form-etat" role="presentation">
            <div class="form-etat__inputs">
                <label for="intitule">Intitulé de l'état <span class="description">(requis)</span></label>
                <input
                    type="text"
                    name="intitule"
                    id="intitule"
                    value="<?php echo $intitule_value; ?>"
                    class="form-etat__input"
                    maxlength="100"
                    required>
                <p class="description">Maximum 100 caractères.</p>
            </div>
        </div>

        <div class="form__buttons">
            <input
                type="submit"
                name="crud_etat_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-recherche-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>
    </form>
</div>