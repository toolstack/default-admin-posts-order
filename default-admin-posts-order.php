<?php
/*
Plugin Name: Default Admin Posts Order
Version: 1.0
Plugin URI: http://toolstack.com/default-admin-posts-order
Author: Greg Ross
Author URI: http://toolstack.com/
Text Domain: default-admin-posts-order
Description: Adds the ID to the admin posts list and sets it to the default sort order.

Compatible with WordPress 3+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2022 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

// Set our version for use later as a define.
define( 'DAPO_VERSION', '1.0' );

add_action( 'pre_get_posts', 'dapo_get_posts_orderby', 1 );
add_action( 'admin_menu', 'dapo_admin_menu', 10 );

function dapo_admin_menu() {

    add_filter( 'manage_edit-post_sortable_columns', 'dapo_manage_post_posts_sortable_columns' );
    add_filter( 'manage_post_posts_columns', 'dapo_manage_post_posts_columns' );
    add_action( 'manage_post_posts_custom_column', 'dapo_manage_post_posts_custom_column', 10, 2 );

    wp_enqueue_style( 'dapo-css', plugin_dir_url( __FILE__ ) . 'default-admin-posts-order.css', array(), DAPO_VERSION );

}

function dapo_manage_post_posts_sortable_columns( $columns ) {
    $columns['id']      = 'ID';

	return $columns;

}

function dapo_manage_post_posts_columns( $columns ) {

    // Loop through and create a new array, adding in our column at the right spot.
    foreach( $columns as $key => $value ) {
        $new_columns[$key] = $value;

        if( $key == 'cb' ) {
            $new_columns[ 'id' ] = __( 'ID', 'default-admin-posts-order' );
        }
    }

    return $new_columns;
}

function dapo_manage_post_posts_custom_column( $column_key, $post_id ) {
    if( $column_key == 'id' ) {
        echo esc_html( $post_id );
    }
}


function dapo_get_posts_orderby( $query ) {

	// Nothing to do:
    if( ! is_admin() && ! $query->is_main_query() )
        return;

    // If the order is blank, then use the ID as a substitute for creation date.
    if( $query->get( 'orderby' ) == '' ) {
	   	$query->set( 'orderby',  'ID' );
    }

}