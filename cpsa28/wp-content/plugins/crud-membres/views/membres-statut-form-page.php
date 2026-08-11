<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un statut' : 'Ajouter un statut';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$name_value = $is_edit ? esc_attr($statut->name) : '';
$icon_value = $is_edit ? esc_attr($statut->icon) : '';
?>

<div class="wrap">
    <h1><?php echo $page_title; ?></h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'empty'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Le nom ne peut pas être vide.</p>
            </div>
        <?php elseif ($_GET['error'] === 'toolong'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Le nom ne peut pas dépasser 50 caractères.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_statut_save', 'crud_statut_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_statut" value="<?php echo esc_attr($statut->id_statut); ?>">
        <?php endif; ?>

        <div class="form-statut" role="presentation">
            <div class="form-statut__inputs">
                <label for="name">Intitulé du statut <span class="description">(requis)</span></label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="<?php echo $name_value; ?>"
                    class="form-statut__input"
                    maxlength="50"
                    required>
                <label for="name">Icone du statut</label>
                <input
                    type='file'
                    name="icon"
                    id="icon"
                    value="<?php echo $icon_value; ?>"
                    class="form-statut__input">
            </div>
        </div>

        <div class="form__buttons">
            <input
                type="submit"
                name="crud_statut_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-membres-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>
    </form>
</div>