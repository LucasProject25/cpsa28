<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un membre' : 'Ajouter un membre';
$button_text = $is_edit ? 'Mettre à jour' : 'Ajouter';
$name_value = $is_edit ? esc_attr($customer->name) : '';
$surname_value = $is_edit ? esc_attr($customer->surname) : '';
$birthday_value = $is_edit ? esc_attr($customer->birthday) : '';
$pays_value = $is_edit ? esc_attr($customer->pays) : '';
$ville_value = $is_edit ? esc_attr($customer->ville) : '';
$cp_value = $is_edit ? esc_attr($customer->cp) : '';
$phone_value = $is_edit ? esc_attr($customer->phone) : '';
$email_value = $is_edit ? esc_attr($customer->email) : '';
$password_value = $is_edit ? esc_attr($customer->password) : '';
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
                <p>Les champs textes ne peuvent pas dépasser 50 caractères.</p>
            </div>
        <?php elseif ($_GET['error'] === 'invalid_date'): ?>
            <div class="notice notice-error is-dismissible">
                <p>Date invalide</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('crud_membre_save', 'crud_membre_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_customer" value="<?php echo esc_attr($customer->id_customer); ?>">
        <?php endif; ?>

        <div class="form-membre" role="presentation">
            <div class="form-membre__inputs-container">
                <div class="form-membre__inputs-left">
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="name">Nom <span class="description">(requis)</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="<?php echo $name_value; ?>"
                            class="form-membre__input"
                            maxlength="50"
                            required>
                    </div>
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="birthday">Date de naissance</label>
                        <input
                            type="date"
                            name="birthday"
                            id="birthday"
                            value="<?php echo $birthday_value; ?>"
                            class="form-membre__input"
                            maxlength="50">
                    </div>
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="ville">Ville</label>
                        <input
                            type="text"
                            name="ville"
                            id="ville"
                            value="<?php echo $ville_value; ?>"
                            class="form-membre__input"
                            maxlength="50">
                    </div>
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="email">Email <span class="description">(requis)</span></label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="<?php echo $email_value; ?>"
                            class="form-membre__input"
                            maxlength="50"
                            required>
                    </div>
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="password">Mot de passe <span class="description">(requis)</span></label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            value="<?php echo $password_value; ?>"
                            class="form-membre__input"
                            maxlength="50"
                            required>
                    </div>
                </div>
                <div class="form-membre__inputs-right">
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="surname">Prénom <span class="description">(requis)</span></label>
                        <input
                            type="text"
                            name="surname"
                            id="surname"
                            value="<?php echo $surname_value; ?>"
                            class="form-membre__input"
                            maxlength="50"
                            required>
                    </div>

                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="pays">Pays</label>
                        <input
                            type="text"
                            name="pays"
                            id="pays"
                            value="<?php echo $pays_value; ?>"
                            class="form-membre__input"
                            maxlength="50">
                    </div>

                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="cp">Code postal</label>
                        <input
                            type="text"
                            name="cp"
                            id="cp"
                            value="<?php echo $cp_value; ?>"
                            class="form-membre__input"
                            maxlength="50">
                    </div>
                    <div class="form-membre__inputs">
                        <label class="form-membre__label" for="phone">Téléphone</label>
                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="<?php echo $phone_value; ?>"
                            class="form-membre__input"
                            maxlength="50">
                    </div>
                    <div class="form-membre__statut">
                        <div class="form-membre__inputs">
                            <label class="form-membre__label" for="id_statut">Statut <span class="description">(requis)</span></label>
                            <select name="id_statut" id="id_statut" class="form-membre__input" required>
                                <option value="">-- Sélectionnez un statut --</option>
                                <?php foreach ($statuts as $statut): ?>
                                    <option
                                        value="<?php echo esc_attr($statut->id_statut); ?>"
                                        <?php selected($is_edit ? $customer->id_statut : '', $statut->id_statut); ?>>
                                        <?php echo esc_html($statut->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form__buttons">
            <input
                type="submit"
                name="crud_membre_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-membres-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>

    </form>
</div>