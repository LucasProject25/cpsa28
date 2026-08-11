<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

$page_title = $is_edit ? 'Modifier un rendez-vous' : 'Planifier un rendez-vous';
$button_text = $is_edit ? 'Mettre à jour' : 'Planifier';
$date_value = $is_edit ? esc_attr($RDV->date_rdv) : '';
$heure_value = $is_edit ? esc_attr($RDV->heure_rdv) : '';
print_r($RDV);
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
        <?php wp_nonce_field('crud_rendezVous_save', 'crud_rendezVous_nonce'); ?>

        <?php if ($is_edit): ?>
            <input type="hidden" name="id_rendezvous" value="<?php echo esc_attr($RDV->id_rendezvous); ?>">
        <?php endif; ?>

        <div class="form" role="presentation">
            <div class="form__firstColumn">
                <div class="form__inputs">
                    <label class="form__label" for="date">Date du rendez-vous <span class="description">(requis)</span></label>
                    <input
                        type="date"
                        name="date"
                        id="date"
                        value="<?php echo $date_value; ?>"
                        class="form__input"
                        required>
                </div>
                <div class="form__inputs">
                    <label class="form__label" for="id_motif">Motif <span class="description">(requis)</span></label>
                    <select name="id_motif" id="id_motif" class="form__input" required>
                        <option value="">-- Sélectionnez un motif --</option>
                        <?php foreach ($motifs as $motif): ?>
                            <option
                                value="<?php echo esc_attr($motif->id_motif); ?>"
                                <?php selected($is_edit ? $RDV->id_motif : '', $motif->id_motif); ?>>
                                <?php echo esc_html($motif->intitule); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form__inputs">
                    <label class="form__label" for="id_membre">Destinataire <span class="description">(requis)</span></label>
                    <select name="id_membre" id="id_membre" class="form__input" required>
                        <option value="">-- Sélectionnez un membre --</option>
                        <?php foreach ($membres as $membre): ?>
                            <option
                                value="<?php echo esc_attr($membre->id_customer); ?>"
                                <?php selected($is_edit ? $RDV->id_membre : '', $membre->id_customer); ?>>
                                <?php echo esc_html($membre->name); ?>
                                <?php echo esc_html($membre->surname); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form__secondColumn">
                <div class="form__inputs">
                    <label class="form__label" for="heure">Heure du rendez-vous <span class="description">(requis)</span></label>
                    <input
                        type="time"
                        name="heure"
                        id="heure"
                        value="<?php echo $heure_value; ?>"
                        class="form__input"
                        required>
                </div>
                <div class="form__inputs">
                    <label class="form__label" for="id_responsable">Responsable <span class="description">(requis)</span></label>
                    <select name="id_responsable" id="id_responsable" class="form__input" required>
                        <option value="">-- Sélectionnez un responsable --</option>
                        <?php foreach ($responsables as $responsable): ?>
                            <option
                                value="<?php echo esc_attr($responsable->id_membre); ?>"
                                <?php selected($is_edit ? $RDV->id_responsable : '', $responsable->id_membre); ?>>
                                <?php echo esc_html($responsable->name); ?>
                                <?php echo esc_html($responsable->surname); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form__inputs">
                    <label class="form__label" for="id_type">Type de rendez-vous <span class="description">(requis)</span></label>
                    <select name="id_type" id="id_type" class="form__input" required>
                        <option value="">-- Sélectionnez un type --</option>
                        <?php foreach ($types as $type): ?>
                            <option
                                value="<?php echo esc_attr($type->id_type); ?>"
                                <?php selected($is_edit ? $RDV->id_type : '', $type->id_type); ?>>
                                <?php echo esc_html($type->intitule); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form__buttons">
            <input
                type="submit"
                name="crud_rendezVous_submit"
                id="submit"
                class="button button-primary form__button"
                value="<?php echo $button_text; ?>">
            <a href="<?php echo admin_url('admin.php?page=crud-rendezVous-listing'); ?>" class="button form__button form__button--cancel">Annuler</a>
        </div>

    </form>
</div>