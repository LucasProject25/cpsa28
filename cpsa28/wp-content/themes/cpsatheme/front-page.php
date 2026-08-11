<?php
global $wpdb;

$membre_id = $_SESSION['customer_id'] ?? null; // Récupérer l'utilisateur connecté 
$statut_user = 'public'; // Les utilisateurs sans compte ne voient que les véhicules public


// On récupère le statut du membre connecté
if ($membre_id) {
	$table_membre = $table_membre = $wpdb->prefix . 'customer';
	$table_statut = $wpdb->prefix . 'statut';

	$statut_user = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT s.name
            FROM $table_membre m
            JOIN $table_statut s ON m.id_statut = s.id_statut
            WHERE m.id_customer = %d",
			$membre_id
		)
	);
}


// On transforme le statut en liste de visibilités autorisées afin d'afficher les bons véhicules exclusifs selon le statut
/**
 * Visiteur => véhciules public
 * Standard => public + standard
 * Premium => public + standard + premium
 * VIP => tous
 */
$visibilites_autorisees = ['public'];

if ($statut_user == 'Standard') {
	$visibilites_autorisees[] = 'standard';
}

if ($statut_user == 'Premium') {
	$visibilites_autorisees[] = 'standard';
	$visibilites_autorisees[] = 'premium';
}

if ($statut_user == 'VIP') {
	$visibilites_autorisees[] = 'standard';
	$visibilites_autorisees[] = 'premium';
	$visibilites_autorisees[] = 'vip';
}


?>

<?php get_header(); ?>




<section class="section section--filtre">
	<div class="container filtre">
		<?php
		$args = array(
			'post_type' => 'vehicule',
			'tax_query' => array(
				array(
					'taxonomy' => 'marque',
					'field' => 'name'
				),
			),
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$logo = get_field('logo');
				var_dump($post);
				die();
			}
		}
		?>
		<ul class="list-unstyled filtre__itemsLogo--firstLine">
			<li class="filtre__item">
				<a class="filtre__link" href="#" data-brand="porsche">
					<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/porsche-logo.png" alt="Logo Porsche">
				</a>
			</li>
			<li class="filtre__item">
				<a class="filtre__link" href="#" data-brand="ferrari">
					<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/scuderia_ferrari-logo.png" alt="Logo Ferrari">
				</a>
			</li>
			<li class="filtre__item">
				<a class="filtre__link" href="#" data-brand="lamborghini">
					<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/lamborghini-logo.png" alt="Logo Lamborghini">
				</a>
			</li>
			<li class="filtre__item">
				<a class="filtre__link" href="#" data-brand="aston_martin">
					<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/aston_martin-logo.png" alt="Logo Aston Martin">
				</a>
			</li>
			<li class="filtre__item"><a class="filtre__link" href="#" data-brand="bentley">
					<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/bentley-logo.png" alt="Logo Bentley">
				</a>
			</li>
		</ul>
		<ul class="filtre__itemsLogo--secondLine list-unstyled">
			<li class="filtre__item filtre__item--autre"><a class="filtre__link" href="#" data-brand="autre">
					<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/autre-logo.png" alt="Logo autres marques">
				</a>
				<p class="filtre__text">Autres modèles</p>
			</li>
		</ul>
	</div>
	<div class="container filtre filtre--mobile">
		<ul class="filtre__itemsLogo--firstLine list-unstyled">
			<li class="filtre__item"><a class="filtre__link" href=""></a>
				<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/porsche-logo.png" alt="Logo Porsche">
			</li>
			<li class="filtre__item"><a class="filtre__link" href=""></a>
				<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/scuderia_ferrari-logo.png" alt="Logo Ferrari">
			</li>
			<li class="filtre__item"><a class="filtre__link" href=""></a>
				<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/lamborghini-logo.png" alt="Logo Lamborghini">
			</li>
		</ul>
		<ul class="filtre__itemsLogo--secondLine list-unstyled">
			<li class="filtre__item"><a class="filtre__link" href=""></a>
				<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/aston_martin-logo.png" alt="Logo Aston Martin">
			</li>
			<li class="filtre__item"><a class="filtre__link" href=""></a>
				<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/bentley-logo.png" alt="Logo Bentley">
			</li>
			<li class="filtre__item filtre__item--autre"><a class="filtre__link" href=""></a>
				<img class="filtre__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/autre-logo.png" alt="Logo autres marques">
			</li>
		</ul>
	</div>
</section>
<section class="section section--welcome" style="background-image: url(<?php the_post_thumbnail_url(); ?>);">
	<header class="section__header">
		<h2 class="section__title section__title--welcome"><?php echo esc_html(get_field('titre_1')); ?></h2>
	</header>
	<div class="container section__paragraph">
		<p class="section__texte">
			<?php echo wp_kses_post(get_field('paragraphe_1')); ?>
		</p>
		<p class="section__texte">
			<?php echo wp_kses_post(get_field('paragraphe_2')); ?>
		</p>
	</div>
</section>
<section class="section section--nouveaute">
	<header>
		<h2 class="section__title section__title--nouveaute"><?php echo esc_html(get_field('titre_2')); ?></h2>
	</header>
	<div class="carousel">
		<div class="container diaporama">
			<?php
			// the query.
			$args = array(
				'post_type' => 'vehicule',
				'posts_per_page' => 3,
				'orderby' => 'id',
				'order' => 'DESC',

				'meta_query' => array(
					array(
						'key' => 'visibilite_vehicule',
						'value' => $visibilites_autorisees,
						'compare' => 'IN'
					)
				)
			);
			$the_query = new WP_Query($args);

			if ($the_query->have_posts()) {
				while ($the_query->have_posts()) {
					$the_query->the_post();
					$marque = get_the_terms($post->ID, 'marque')[0];
					$logo = get_field('logo', $marque);
			?>
					<div>
						<div class="carousel__item">
							<img class="carousel__image" src="<?php the_post_thumbnail_url(); ?>"
								alt="<?php echo esc_html(get_field('nom_complet')); ?>">
							<div class="carousel__info">
								<h2 class="carousel__titre"><?php echo esc_html(get_field('nom_complet')); ?></h2>
								<h1 class="carousel__prix"><?php echo esc_html(get_field('prix')); ?>€</h1>
								<img class="carousel__logo" src="<?php echo $logo; ?>" alt="Logo Ferrari">
								<a class="carousel__myBtn myBtn" href="<?php the_permalink(); ?>">+ En savoir plus</a>
							</div>
						</div>
					</div>
			<?php
				}
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
	<div class="section__btns">
		<a class="myBtn myBtn-cs-submit myBtn-cs-submit--all" href="<?php the_field('lien_vers_catalogue') ?>">Voir tous les véhicules</a>
	</div>
</section>
<section class="section section--club">
	<header class="section__header">
		<h2 class="section__title"><?php echo esc_html(get_field('titre_3')); ?></h2>
	</header>
	<div class="container section__paragraph section__paragraph--club">
		<h2 class="section__intro">
			<?php echo wp_kses_post(get_field('intro')); ?>
		</h2>
		<p class="section__texte">
			<?php echo wp_kses_post(get_field('contenue')); ?>
		</p>
		<a class="myBtn" href="<?php echo esc_url(home_url('/connexion/')); ?>">Inscription</a>
	</div>
</section>

<?php get_footer(); ?>