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
</main>
<footer class="footer">
	<div class="container footer__content">
		<div class="footer__details">
			<div class="footer__coordonnees">
				<h2 class="footer__title">Coordonnées</h2>
				<p class="footer__text"> <?php echo $adresse; ?> </p>
				<p class="footer__text"> <?php echo $codePostal; ?> </p>
				<p class="footer__text"> <?php echo $email; ?> </p>
				<p class="footer__text"> <?php echo $telephone; ?> </p>
			</div>
			<div class="footer__horaires">
				<h2 class="footer__title">Nos horaires</h2>
				<p class="footer__text">
					Du <?php echo $jour1; ?> au <?php echo $jour2; ?>
				</p>
				<p class="footer__text">
					<?php echo $matin1 . " - " . $matin2 . " / " . $aprem1 . " - " . $aprem2; ?>
				</p>
			</div>
			<div class="footer__social">
				<h2 class="footer__title">Nos réseaux</h2>
				<div class="footer__iconLinks">
					<a class="footer__iconLink" href="">
						<img class="footer__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/Facebook-wh.svg" alt="Icon Facebook">
					</a>
					<a class="footer__iconLink" href="">
						<img class="footer__icon" src="<?php echo get_template_directory_uri(); ?>/assets/icon/Instagram-wh.svg" alt="Icon Instagram">
					</a>
				</div>
			</div>
		</div>
		<div class="footer__utilities">
			<?php
			if (has_custom_logo()) :
				// $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'));
			?>
				<a href="">
					<img class="footer__logo" src="<?php echo get_template_directory_uri(); ?>/assets/logo/CPSA-clr.svg" alt="Logo">
				</a>
			<?php endif; ?>
			<div class="footer__utilsLink">
				<p class="footer__text">Mentions légales</p>
				<a class="footer__text footer__link" href="<?php echo esc_url(home_url('/politique-de-confidentialite/')); ?>">Politique de confidentialité</a>
				<p class="footer__text">Cookies</p>
				<p class="footer__text">&copy; CPSA28 2025</p>
			</div>
		</div>
	</div>
</footer>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	<!-- <ul class="list-unstyled menu__list menu__list--canva">
            <li class="menu__item"><a class="menu__link" href="catalogue.html">Véhicules</a></li>
            <li class="menu__item"><a class="menu__link" href="">Services</a></li>
            <li class="menu__item"><a class="menu__link" href="">Contact</a></li>
        </ul> -->
	<?php
	wp_nav_menu(array(
		'theme_location' => 'main-menu',
		'container'      => false,
		'menu_class'     => 'list-unstyled menu__list menu__list--canva',
		'depth'          => 1,
		'walker'         => new MyCustom_Walker_Nav_Menu()
	)); ?>
	<div>
		<ul class="list-unstyled menu__social menu__social--canva" aria-label="Social media">
			<li>
				<a class="menu__iconLink" href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/Facebook-wh.svg" alt="Icon Facebook"></a>
			</li>
			<li>
				<a class="menu__iconLink" href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/Instagram-wh.svg" alt="Icon Instagram"></a>
			</li>
			<li>
				<a class="menu__iconLink" href="<?php echo esc_url(home_url('/connexion/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/icon/profile-wh.svg" alt="Icon Contact"></a>
			</li>
		</ul>
	</div>
</div>

<?php wp_footer(); ?>

</body>

</html>