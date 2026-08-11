<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un motif' : 'Ajouter un motif';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$intitule_value = $is_edit ? esc_attr($motif->intitule) : '';
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
        <?php wp_nonce_field('crud_motif_save', 'crud_motif_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_motif" value="<?php echo esc_attr($motif->id_motif); ?>">
        <?php endif; ?>

        <div class="form-motif" role="presentation">
            <div class="form-motif__inputs">
                <label for="intitule">Intitulé du motif <span class="description">(requis)</span></label>
                <input
                    type="text"
                    name="intitule"
                    id="intitule"
                    value="<?php echo $intitule_value; ?>"
                    class="form-motif__input"
                    maxlength="100"
                    required>
                <p class="description">Maximum 100 caractères.</p>
            </div>
        </div>

        <div class="form__buttons">
            <input
                type="submit"
                name="crud_motif_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-rendezVous-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>
    </form>
</div>