<?php

// Initialisation des fonctions personnalisées du thème.
add_action("after_setup_theme", "cpsatheme_setup");

// File d'attente des styles et des scripts
add_action("wp_enqueue_scripts", "cpsatheme_scripts_styles");
add_action('wp_enqueue_scripts', 'bootstrap_icons');

// Register menu location
add_action("init", "cpsatheme_register_menus");
