<?php
function cpsatheme_setup()
{
    // permet aux plugins et aux thèmes de gérer la balise de titre du document.
    add_theme_support('title-tag');

    // permet la prise en charge des images mises en avant.
    add_theme_support('post-thumbnails');

    // permet de rendre le code valide pour HTML5.
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    //permet la prise en charge d'un logo personnalisé.
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Désactive les tailles de police et couleurs pour Gutenberg
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('disable-custom-colors');
    add_theme_support('editor-color-palette');

    // permet la prise en charge des extraits.
    add_post_type_support('page', 'excerpt');

    // Tailles d'images personalisées
    add_image_size('square', 1024, 1024, true);
    add_image_size('paysage', 1024, 680, true);
}

function cpsatheme_scripts_styles()
{
    wp_enqueue_style('cpsa-style', get_stylesheet_uri());
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.7.1.slim.min.js', array(), '', true);
    wp_enqueue_script('slick', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array(), '', true);
    wp_enqueue_script('cpsa-script', get_template_directory_uri() . '/js/script.js', array(), '', true);
    wp_enqueue_script('cpsa-script__formulaire', get_template_directory_uri() . '/js/formulaire.js', array(), '', true);
    wp_enqueue_script('cpsa-script__custom-select', get_template_directory_uri() . '/js/custom-select.js', array(), '', true);
    wp_enqueue_script('cpsa-script__dashboard', get_template_directory_uri() . '/js/dashboard.js', array(), '', true);
}

function cpsatheme_register_menus()
{
    register_nav_menus(array(
        'main-menu' => esc_html__('En-tête de page', 'cpsatheme'),
        'footer-menu' => esc_html__('Pied de page', 'cpsatheme'),
    ));
}
