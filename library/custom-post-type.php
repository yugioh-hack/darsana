<?php
/* Bones Custom Post Type Example
Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'bones_flush_rewrite_rules' );

function bones_flush_rewrite_rules() {
    flush_rewrite_rules();
}

// let's create the function for the custom type
function custom_post_example() { 
    // creating (registering) the custom type 
    register_post_type( 'custom_type',
        array( 'labels' => array(
            'name' => __( 'Custom Types', 'bonestheme' ),
            'singular_name' => __( 'Custom Post', 'bonestheme' ),
            'all_items' => __( 'All Custom Posts', 'bonestheme' ),
            'add_new' => __( 'Add New', 'bonestheme' ),
            'add_new_item' => __( 'Add New Custom Type', 'bonestheme' ),
            'edit' => __( 'Edit', 'bonestheme' ),
            'edit_item' => __( 'Edit Post Types', 'bonestheme' ),
            'new_item' => __( 'New Post Type', 'bonestheme' ),
            'view_item' => __( 'View Post Type', 'bonestheme' ),
            'search_items' => __( 'Search Post Type', 'bonestheme' ),
            'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ),
            'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ),
            'parent_item_colon' => ''
            ),
            'description' => __( 'This is the example custom post type', 'bonestheme' ),
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'query_var' => true,
            'menu_position' => 8,
            'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png',
            'rewrite'   => array( 'slug' => 'custom_type', 'with_front' => false ),
            'has_archive' => 'custom_type',
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky')
        )
    );
    
    /* this adds your post categories to your custom post type */
    register_taxonomy_for_object_type( 'category', 'custom_type' );
    /* this adds your post tags to your custom post type */
    register_taxonomy_for_object_type( 'post_tag', 'custom_type' );
    
    // PHP 8 & Modern WP fix: タクソノミーの登録処理を関数内に移動
    // now let's add custom categories (these act like categories)
    register_taxonomy( 'custom_cat', 
        array('custom_type'),
        array('hierarchical' => true,
            'labels' => array(
                'name' => __( 'Custom Categories', 'bonestheme' ),
                'singular_name' => __( 'Custom Category', 'bonestheme' ),
                'search_items' =>  __( 'Search Custom Categories', 'bonestheme' ),
                'all_items' => __( 'All Custom Categories', 'bonestheme' ),
                'parent_item' => __( 'Parent Custom Category', 'bonestheme' ),
                'parent_item_colon' => __( 'Parent Custom Category:', 'bonestheme' ),
                'edit_item' => __( 'Edit Custom Category', 'bonestheme' ),
                'update_item' => __( 'Update Custom Category', 'bonestheme' ),
                'add_new_item' => __( 'Add New Custom Category', 'bonestheme' ),
                'new_item_name' => __( 'New Custom Category Name', 'bonestheme' )
            ),
            'show_admin_column' => true, 
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'custom-slug' ),
        )
    );
    
    // now let's add custom tags (these act like categories)
    register_taxonomy( 'custom_tag', 
        array('custom_type'),
        array('hierarchical' => false,
            'labels' => array(
                'name' => __( 'Custom Tags', 'bonestheme' ),
                'singular_name' => __( 'Custom Tag', 'bonestheme' ),
                'search_items' =>  __( 'Search Custom Tags', 'bonestheme' ),
                'all_items' => __( 'All Custom Tags', 'bonestheme' ),
                'parent_item' => __( 'Parent Custom Tag', 'bonestheme' ),
                'parent_item_colon' => __( 'Parent Custom Tag:', 'bonestheme' ),
                'edit_item' => __( 'Edit Custom Tag', 'bonestheme' ),
                'update_item' => __( 'Update Custom Tag', 'bonestheme' ),
                'add_new_item' => __( 'Add New Custom Tag', 'bonestheme' ),
                'new_item_name' => __( 'New Custom Tag Name', 'bonestheme' )
            ),
            'show_admin_column' => true,
            'show_ui' => true,
            'query_var' => true,
        )
    );
}

// adding the function to the Wordpress init
add_action( 'init', 'custom_post_example');

?>