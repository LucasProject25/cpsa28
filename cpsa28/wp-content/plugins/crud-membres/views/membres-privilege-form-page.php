<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un privilege' : 'Ajouter un privilege';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$intitule_value = $is_edit ? esc_attr($privilege->intitule) : '';
?>

<div class="wrap">
    <h1><?php echo $page_title; ?></h1>

    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'empty'): ?>
            <div class="notice notice-error is-dismissible">
                <p>L'intitule ne peut pas être vide.</p>
            </div>
        <?php elseif ($_GET['error'] === 'toolong'): ?>
            <div class="notice notice-error is-dismissible">
                <p>L'intitule ne peut pas dépasser 255 caractères.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_privilege_save', 'crud_privilege_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_privilege" value="<?php echo esc_attr($privilege->id_privilege); ?>">
        <?php endif; ?>

        <div class="form-privilege" role="presentation">
            <div class="form-privilege__inputs">
                <label for="intitule">Intitulé du privilège <span class="description">(requis)</span></label>
                <input
                    type="text"
                    name="intitule"
                    id="intitule"
                    value="<?php echo $intitule_value; ?>"
                    class="form-privilege__input"
                    maxlength="255"
                    required>
            </div>
            <div class="form-privilege__statut">
                <div class="form-privilege__inputs">
                    <label class="form-privilege__label" for="id_statut">Statut <span class="description">(requis)</span></label>
                    <select name="id_statut" id="id_statut" class="form-privilege__inputs" required>
                        <option value="">-- Sélectionnez un statut --</option>
                        <?php foreach ($statuts as $statut): ?>
                            <option
                                value="<?php echo esc_attr($statut->id_statut); ?>"
                                <?php selected($is_edit ? $privilege->id_statut : '', $statut->id_statut); ?>>
                                <?php echo esc_html($statut->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>


        <div class="form__buttons">
            <input
                type="submit"
                name="crud_privilege_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-membres-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>
    </form>
</div>