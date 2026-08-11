<?php
$adresse = get_option('config_info_manager_adresse');
$codePostal = get_option('config_info_manager_codePostal');
$email = get_option('config_info_manager_email');
$telephone = get_option('config_info_manager_telephone');

$jour1 = get_option('config_info_manager_jour1');
$jour2 = get_option('config_info_manager_jour2');

$matin1 = format_heure(get_option('config_info_manager_matin1'));
$matin2 = format_heure(get_option('config_info_manager_matin2'));
$aprem1 = format_heure(get_option('config_info_manager_aprem1'));
$aprem2 = format_heure(get_option('config_info_manager_aprem2'));
?>

<?php get_header(); ?>

<section class="section section--contact">
	<header class="section__header">
		<h2 class="section__title section__title--contact"><?php the_title(); ?></h2>
	</header>
</section>
<section class="container section section--situer">
	<div class="section__coordonnees">
		<h2 class="section__nameCompany">CPSA28</h2>
		<div class="section__adresse">
			<p class="section__texte"><?php echo $adresse; ?></p>
			<p class="section__texte"><?php echo $codePostal; ?></p>
		</div>
		<div class="section__contact">
			<p class="section__texte"><?php echo $email; ?></p>
			<p class="section__texte section__texte--telephone"><?php echo $telephone; ?></p>
		</div>
		<div class="section__horaires">
			<p class="section__texte">
				Du <?php echo $jour1; ?> au <?php echo $jour2; ?>
			</p>
			<p class="section__texte">
				<?php echo $matin1 . " - " . $matin2 . " / " . $aprem1 . " - " . $aprem2; ?>
			</p>
		</div>
	</div>
	<div class="section__map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2647.8693861794063!2d1.443065576827474!3d48.42065183141107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e40c7b4d7f402b%3A0xa2285c8d7a7e8a50!2sCpsa%2028!5e0!3m2!1sfr!2sfr!4v1773240554056!5m2!1sfr!2sfr"
			width="600" height="450"
			style="border:0;"
			allowfullscreen=""
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade">
		</iframe>
		<!-- <?php
				$image = get_field('map');
				$size = 'full'; // (thumbnail, medium, large, full or custom size)
				if ($image) {
					echo wp_get_attachment_image($image, $size);
				}
				?> -->
	</div>
</section>
<section class="container section section--formulaire__contact">
	<div class="formulaire">
		<p class="formulaire__rules">Les champs indiqués par un astérisque (*) sont obligatoires.</p>
		<div class="formulaire__inputs">
			<?php echo do_shortcode('[contact-form-7 id="565d4d4" title="Formulaire de contact"]'); ?>
		</div>
		<!-- <form class="formulaire__inputs" action="">
			<div class="formulaire__inputs-container">
				<div class="formulaire__left-inputs">
					<input class="formulaire__input" type="text" id="nom" name="nom" placeholder="Nom*" required>
					<input class="formulaire__input" type="text" id="tel" name="tel" placeholder="Télephone*" required>
					<select class="formulaire__input" id="habitat" name="habitat" required>
						<option class="formulaire__input--option" value="">Département ou pays*</option>
						<option class="formulaire__input--option" value="paris">75 - Ville de Paris</option>
						<option class="formulaire__input--option" value="seine">77 - Seine-Et-Marne</option>
						<option class="formulaire__input--option" value="yvelines">78 - Yvelines</option>
						<option class="formulaire__input--option" value="essonne">91 - Essonne</option>
						<option class="formulaire__input--option" value="hautSeine">92 - Haut-de-Seine</option>
						<option class="formulaire__input--option" value="st_denis">93 - Seine-Saint-Denis</option>
						<option class="formulaire__input--option" value="marne">94 - Val-De-Marne</option>
						<option class="formulaire__input--option" value="oise">95 - Val-d'Oine</option>
					</select>
				</div>
				<div class="formulaire__right-inputs">
					<input class="formulaire__input" type="text" id="prenom" name="prenom" placeholder="Prénom*" required>
					<input class="formulaire__input" type="email" id="email" name="email" placeholder="Email*" required>
					<select class="formulaire__input" id="motif" name="motif" required>
						<option class="formulaire__input--option" value="">Motif*</option>
						<option class="formulaire__input--option" value="motif1">Vendre votre véhicule</option>
						<option class="formulaire__input--option" value="motif2">Acheter un véhicule</option>
						<option class="formulaire__input--option" value="motif3">Prendre rendez-vous</option>
						<option class="formulaire__input--option" value="motif4">Utiliser nos services</option>
						<option class="formulaire__input--option" value="motif5">Autre (A préciser)</option>
					</select>
				</div>
			</div>
			<textarea class="formulaire__message" name="message" id="message" placeholder="Message*" rows="10" required></textarea>
			<div class="formulaire__condition">
				<label class="formulaire__label" for="condition">
					En soumettant ce formulaire, j’accepte que les informations saisies soient traitées par <span class="section__surlign">CPSA28</span> dans le cadre de ma demande contact et de la relation commerciale qui peut en découler.
					<span class="formulaire__soulign">Pour en savoir plus, consultez notre politique de confidentialité.</span> *
					<input class="formulaire__checkbox" type="checkbox" id="condition" name="condition" value="Condition" required>
					<span class="formulaire__checkmark"></span>
				</label>
			</div>
			<input class="myBtn myBtn--contact" type="submit" value="Envoyer">
		</form> -->
	</div>
</section>

<?php get_footer(); ?>