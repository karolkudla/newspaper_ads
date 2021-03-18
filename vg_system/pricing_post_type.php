<?php

/****************************************************************************************************/
/********************************** CENNIK GAZET POST TYPE ******************************************/
/****************************************************************************************************/


function custom_post_type_cennik_gazet() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Cenniki Gazet Admin', 'Post Type General Name', 'viral-news' ),
        'singular_name'       => _x( 'Cennik Gazet', 'Post Type Singular Name', 'viral-news' ),
        'menu_name'           => __( 'Cenniki Gazet Admin', 'viral-news' ),
        'parent_item_colon'   => __( 'Rodzic', 'viral-news' ),
        'all_items'           => __( 'Wszystkie', 'viral-news' ),
        'view_item'           => __( 'Zobacz', 'viral-news' ),
        'add_new_item'        => __( 'Dodaj nowy cennik Gazety', 'viral-news' ),
        'add_new'             => __( 'Dodaj nowy', 'viral-news' ),
        'edit_item'           => __( 'Edytuj', 'viral-news' ),
        'update_item'         => __( 'Aktualizuj', 'viral-news' ),
        'search_items'        => __( 'Szukaj cennika', 'viral-news' ),
        'not_found'           => __( 'Nie znaleziono', 'viral-news' ),
        'not_found_in_trash'  => __( 'Nie znaleziono w koszu', 'viral-news' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Cennik Gazet Admin', 'viral-news' ),
        'description'         => __( 'Lista Cenników Gazet', 'viral-news' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        /* 'taxonomies'          => array( 'genres' ), */
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
 
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => false,
		'menu_icon' => 'dashicons-format-gallery'
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'cennik_gazet', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type_cennik_gazet', 0 );


/****************************************************************************************************/
/************************************* CENNIK PORTAL** POST TYPE ************************************/
/****************************************************************************************************/


function custom_post_type_cennik_portal() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Cenniki Portal Admin', 'Post Type General Name', 'viral-news' ),
        'singular_name'       => _x( 'Cennik Portal', 'Post Type Singular Name', 'viral-news' ),
        'menu_name'           => __( 'Cenniki Portal Admin', 'viral-news' ),
        'parent_item_colon'   => __( 'Rodzic', 'viral-news' ),
        'all_items'           => __( 'Wszystkie', 'viral-news' ),
        'view_item'           => __( 'Zobacz', 'viral-news' ),
        'add_new_item'        => __( 'Dodaj nowy cennik Portal', 'viral-news' ),
        'add_new'             => __( 'Dodaj nowy', 'viral-news' ),
        'edit_item'           => __( 'Edytuj', 'viral-news' ),
        'update_item'         => __( 'Aktualizuj', 'viral-news' ),
        'search_items'        => __( 'Szukaj cennika', 'viral-news' ),
        'not_found'           => __( 'Nie znaleziono', 'viral-news' ),
        'not_found_in_trash'  => __( 'Nie znaleziono w koszu', 'viral-news' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Cennik Portal Admin', 'viral-news' ),
        'description'         => __( 'Lista Cenników Portali', 'viral-news' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        /* 'taxonomies'          => array( 'genres' ), */
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
 
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => false,
		'menu_icon' => 'dashicons-format-gallery'
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'cennik_portal', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type_cennik_portal', 0 );

;?>