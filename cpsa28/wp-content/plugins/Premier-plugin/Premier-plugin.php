<?php
/**
 * Plugin Name: Premier Plugin
 * Description: Mon premier plugin WordPress pour voir comment ça marche.
 * Version: 1.0
 * Author: Lucas
 */

// Sécurité : Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}

// Création du lien dans le menu
function premier_plugin_menu() {
    add_menu_page(
        'Plugin de test', // Titre de la page
        'Plugin n°1', // Titre du menu
        'manage_options', // Capacité requise
        'premier-plugin', // Slug du menu
        'premier_plugin_page' // Fonction de rappel
    );
}

// Hook pour ajouter le menu
add_action('admin_menu', 'premier_plugin_menu');

// Fonction pour afficher le contenu de la page du plugin
function premier_plugin_page() {
    echo '<h1> Bienvenue dans mon premier plugin WordPress !</h1>';
    echo '<p>Ceci est une démonstration simple de la création d\'un plugin WordPress.</p>';
}