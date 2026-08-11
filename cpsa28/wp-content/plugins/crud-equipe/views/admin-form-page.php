<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un membre' : 'Ajouter un membre';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$name_value = $is_edit ? esc_attr($membre->name) : '';
$surname_value = $is_edit ? esc_attr($membre->surname) : '';
$email_value = $is_edit ? esc_attr($membre->email) : '';
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
                <p>Les champs ne peuvent pas dépasser 50 caractères.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_equipe_save', 'crud_equipe_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_membre" value="<?php echo esc_attr($membre->id_membre); ?>">
        <?php endif; ?>

        <div class="admin-form" role="presentation">
            <div class="admin-form__inputs-container">
                <div class="admin-form__name">
                    <div class="admin-form__inputs">
                        <label for="name">Prénom <span class="description">(requis)</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="<?php echo $name_value; ?>"
                            class="admin-form__input"
                            maxlength="50"
                            required>
                    </div>
                    <div class="admin-form__inputs">
                        <label for="surname">Nom <span class="description">(requis)</span></label>
                        <input
                            type="text"
                            name="surname"
                            id="surname"
                            value="<?php echo $surname_value; ?>"
                            class="admin-form__input"
                            maxlength="50"
                            required>
                    </div>
                    <div class="admin-form__inputs">
                        <label for="email">Email <span class="description">(requis)</span></label>
                        <input
                            type="text"
                            name="email"
                            id="email"
                            value="<?php echo $email_value; ?>"
                            class="admin-form__input"
                            maxlength="50"
                            required>
                    </div>
                </div>
                <div class="admin-form__role">
                    <div class="admin-form__inputs">
                        <label for="id_role">Role <span class="description">(requis)</span></label>
                        <select name="id_role" id="id_role" class="admin-form__input" required>
                            <option value="">-- Sélectionnez un rôle --</option>
                            <?php foreach ($roles as $role): ?>
                                <option
                                    value="<?php echo esc_attr($role->id_role); ?>"
                                    <?php selected($is_edit ? $membre->id_role : '', $role->id_role); ?>>
                                    <?php echo esc_html($role->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form__buttons">
            <p class="submit">
                <input
                    type="submit"
                    name="crud_equipe_submit"
                    id="submit"
                    class="button button-primary form__button"
                    value="<?php echo $button_text; ?>">
                <a href="<?php echo admin_url('admin.php?page=crud-equipe-listing'); ?>" class="button">Annuler</a>
            </p>
        </div>
    </form>
</div>