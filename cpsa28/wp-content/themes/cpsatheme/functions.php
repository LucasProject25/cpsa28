<?php

// Intégration du fichier avec les appels add_action().
require_once get_template_directory() . '/inc/actions.php';

// Intégration du fichier avec les appels add_filter().
require_once get_template_directory() . '/inc/filters.php';

// Intégration du fichier avec les fonctions de filtrage.
require_once get_template_directory() . '/inc/filter-functions.php';

// Intégration du fichier avec les fonctions de template.
require_once get_template_directory() . '/inc/template-functions.php';

// Walker Nav Menu.
require_once get_template_directory() . '/classes/class-cpsa-walker-menu.php';

add_filter('upload_mimes', function ($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
});

add_filter('body_class', function (array $classes) {
	if (is_front_page()) {
		$classes[] = 'body--home';
	}
	return $classes;
});

function bootstrap_icons()
{
	// Enqueue Bootstrap CSS
	wp_enqueue_style(
		'bootstrap-icons-css',
		'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css'
	);

	// Enqueue Bootstrap JS (with dependency on jQuery)
	wp_enqueue_script(
		'bootstrap-js',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		array('jquery'),
		null,
		true
	);
}

function format_heure($time)
{
	if (!$time) return '';
	[$h, $m] = explode(':', $time);
	return ($m === '00') ? $h . 'h' : $h . 'h' . $m;
}

/**
 * Vérifie si tous les champs requis sont remplis
 */
function champs_obligatoires_remplis(array $champs): bool
{
	foreach ($champs as $champ) {
		if (empty($champ)) {
			return false;
		}
	}
	return true;
}

/**
 * Vérifie le format d'un email
 */
function email_valide(string $email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}



// Récupérer les informations de l'utilisateur connecté
function get_current_customer()
{
	global $wpdb;
	if (!isset($_SESSION['customer_id'])) return null;

	$table = $wpdb->prefix . 'customer';
	$table_statut = $wpdb->prefix . 'statut';

	return $wpdb->get_row($wpdb->prepare(
		"SELECT c.*, s.name AS nom_statut, s.icon AS icone_statut
         FROM $table c
         JOIN $table_statut s ON c.id_statut = s.id_statut
         WHERE id_customer = %d",
		$_SESSION['customer_id']
	));
}


// Modifier les informations du profil du membre
add_action('template_redirect', function () {
	session_start();
	if (!isset($_SESSION['customer_id'])) return;


	global $wpdb;
	if (isset($_POST['valid_modif'])) {
		$table = $wpdb->prefix . 'customer';

		$wpdb->update(
			$table,
			[
				'name'     => sanitize_text_field($_POST['name']),
				'surname'  => sanitize_text_field($_POST['surname']),
				'birthday' => sanitize_text_field($_POST['birthday']),
				'pays'     => sanitize_text_field($_POST['pays']),
				'ville'    => sanitize_text_field($_POST['ville']),
				'cp'       => sanitize_text_field($_POST['cp']),
				'email'    => sanitize_email($_POST['mail']),
				'phone'    => sanitize_text_field($_POST['tel']),
			],
			['id_customer' => $_SESSION['customer_id']],
			['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'],
			['%d']
		);

		wp_redirect(wp_get_referer());
		exit;
	}

	if (isset($_POST['valid_rdv'])) {
		$table = $wpdb->prefix . 'rendezVous';

		$wpdb->insert(
			$table,
			[
				'date_rdv'    => sanitize_text_field($_POST['date_rdv']),
				'heure_rdv'   => sanitize_text_field($_POST['heure_rdv']),
				'id_motif'    => isset($_POST['id_motif']) ? absint($_POST['id_motif']) : 0,
				'id_type'     => isset($_POST['id_type']) ? absint($_POST['id_type']) : 0,
				'id_membre'   => $_SESSION['customer_id'],
				'id_responsable' => null,
			],

			['%s', '%s', '%d', '%d', '%d'],
		);

		wp_redirect(wp_get_referer());
		exit;
	}

	if (isset($_POST['valid_recherche'])) {
		$table = $wpdb->prefix . 'rechercheperso';

		$wpdb->insert(
			$table,
			[
				'nom_vehicule'                 => sanitize_text_field($_POST['vehicule_name']),
				'marque'                       => sanitize_text_field($_POST['marque']),
				'modele'                       => sanitize_text_field($_POST['modele']),
				'categorie'                    => sanitize_text_field($_POST['categorie']),
				'energie'                      => sanitize_text_field($_POST['energie']),
				'mise_en_circulation_vehicule' => sanitize_text_field($_POST['annee']),
				'kilometrage_vehicule'         => sanitize_text_field($_POST['kilometrage']),
				'transmission_vehicule'        => sanitize_text_field($_POST['transmission']),
				'prix_vehicule'                => sanitize_text_field($_POST['prix']),
				'id_membre'                    => $_SESSION['customer_id'],
				'id_etat'                      => 1,
				'id_responsable'               => null,
			],

			['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'],
		);

		wp_redirect(home_url('/club-prive-accueil'));
		exit;
	}
});

add_action('wp_ajax_filtrer_vehicules',        'filtrer_vehicules_callback');
add_action('wp_ajax_nopriv_filtrer_vehicules', 'filtrer_vehicules_callback');

function filtrer_vehicules_callback()
{
	check_ajax_referer('filtrer_vehicules_nonce', 'nonce');

	ob_start();
	get_template_part('template-parts/vehicule', 'vehicule');
	$html = ob_get_clean();

	wp_send_json_success($html);
}

function enqueue_filtre_vehicules()
{
	wp_enqueue_script(
		'filtre-vehicules',
		get_template_directory_uri() . '/js/filtre-vehicules.js',
		[],
		'1.0',
		true
	);

	wp_localize_script('filtre-vehicules', 'filtreParams', [
		'ajaxurl' => admin_url('admin-ajax.php'),
		'nonce'   => wp_create_nonce('filtrer_vehicules_nonce'),
	]);
}
add_action('wp_enqueue_scripts', 'enqueue_filtre_vehicules');
