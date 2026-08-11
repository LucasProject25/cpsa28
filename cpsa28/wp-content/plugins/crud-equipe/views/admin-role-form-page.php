<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un rôle' : 'Ajouter un rôle';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$name_value = $is_edit ? esc_attr($role->name) : '';
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
        <?php wp_nonce_field('crud_role_save', 'crud_role_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_role" value="<?php echo esc_attr($role->id_role); ?>">
        <?php endif; ?>

        <div class="admin-form" role="presentation">
            <div class="admin-form__inputs-container">
                <div class="admin-form__name">
                    <div class="admin-form__inputs">
                        <label for="name">Nom <span class="description">(requis)</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="<?php echo $name_value; ?>"
                            class="form__input"
                            maxlength="50"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form__buttons">
            <p class="submit">
                <input
                    type="submit"
                    name="crud_role_submit"
                    id="submit"
                    class="button button-primary form__button"
                    value="<?php echo $button_text; ?>">
                <a href="<?php echo admin_url('admin.php?page=crud-equipe-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
            </p>
        </div>
    </form>
</div>