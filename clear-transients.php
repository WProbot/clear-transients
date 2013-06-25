<?php
/*
Plugin Name: Clear Transients
Plugin URI: http://ryanduff.net
Description: A simple plugin to clear transients and site-transients during development. Add ?clear_transients to url to clear transients. Use ?clear_all_transients to clear transients and site transients.
Author: Ryan Duff
Author URI: http://ryanduff.net
Version: 1.0
License: GPL2+
*/

class WP_Clear_Transients {

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {

        add_action( 'init', array( $this, 'clear_transients' ) );

    }


    /**
     * Clears transients based on query var
     *
     * @since  1.0
     *
     * @return void
     */
    public function clear_transients() {

        global $wpdb;

        // If we're clearing transients
        if ( isset( $_GET['clear_transients'] ) && current_user_can( 'manage_options' ) ) {

            $wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE '_transient_%'" );

        // If we're clearing all transients
        } elseif ( isset( $_GET['clear_all_transients'] ) ) {

            // If we're on multisite, make sure the user is a network admin then delete from the sitemeta table
            if ( is_multisite() && current_user_can( 'manage_network_options' ) ) {

                $wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE '_transient_%'" );
                $wpdb->query( "DELETE FROM $wpdb->sitemeta WHERE `option_name` LIKE '_site_transient_%'" );

            // If we're not on multisite, make sure the user is an admin and delete from the options table
            } elseif ( current_user_can( 'manage_options' ) ) {

                $wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE '_transient_%' OR `option_name` LIKE '_site_transient_%'" );

            }

        }

    }

}

new WP_Clear_Transients;