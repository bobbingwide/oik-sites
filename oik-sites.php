<?php
/*
Plugin Name: oik-sites
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-sites
Description: oik sites - Sites Custom Post Type 
Depends: oik base plugin, oik-fields
Version: 0.2
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

    Copyright 2014 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

/**
 * Implement "oik_fields_loaded" action for oik-sites
 */
function oik_sites_init( ) {
  oik_register_oik_sites();
}

/** 
 * Register custom post type "oik_site"
 *  
 * The title should contain the website title - possibly prefixed by the web address
 * Wild cards could be used. e.g. wp-a2z.*
 * 
 * 
 * The description of the site is the content field.
 * Sites should be hierarchical to allow for WPMS and/or subdomains
 
 * Fields for the site are: 
 * oik-site 	_oik_business_website 	
   oik-site 	rating 	
   oik-site 	_of_mshot 	 
   oik-site 	site_status 	
   oik-site 	site_content 	 
   oik-site 	site_audience 
 */
function oik_register_oik_sites() {
  $post_type = 'oik_site';
  $post_type_args = array();
  $post_type_args['label'] = 'Sites';
  $post_type_args['description'] = 'Sites dedicated to WordPress. Initially for identifying sites prefixed wp- or wp but can also be used for recording the location of Premium plugins and themes.';
  $post_type_args['hierarchical'] = true; 
  $post_type_args['has_archive'] = true;
  $post_type_args['supports'] = array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'publicize', 'author' );
  $post_type_args = oik_sites_capabilities( $post_type_args );
  
  bw_register_post_type( $post_type, $post_type_args );
  //add_post_type_support( $post_type, 'page-attributes' );
  //add_post_type_support( $post_type, 'publicize' );
  
  // bw_register_field( "_oik_sites_ref", "noderef", "Reference" ); 
  bw_register_field( "_website", "URL", "Website" );
  bw_register_field( "_rating", "rating", "Rating" ); 
  bw_register_field( "_mshot", "mshot", "Screenshot" ); 
  //bw_register_field_for_object_type( "_oik_sites_ref", $post_type );
  bw_register_field_for_object_type( "_website", $post_type );
  bw_register_field_for_object_type( "_rating", $post_type );
  bw_register_field_for_object_type( "_mshot", $post_type );
  
  $taxonomy = 'site_content'; 
  $labels = array( "labels" => array( "singular_name" => __( "Content type" ), "name" => __( "Contents" ) ) );
  bw_register_custom_tags( $taxonomy, $post_type, $labels );
  $taxonomy = "site_status";
  $labels = array( "labels" => array( "singular_name" => __( "Status" ), "name" => __( "Statuses" ) ) );
  bw_register_custom_category( $taxonomy, $post_type, $labels );
  $taxonomy = "site_audience";
  $labels = array( "labels" => array( "singular_name" => __( "Audience" ), "name" => __( "Audience" ) ) );
  bw_register_custom_category( $taxonomy, $post_type, $labels );
//  add_filter( "manage_edit-${post_type}_columns", "oik_sites_columns", 10, 1 );
//  add_action( "manage_${post_type}_posts_custom_column", "bw_custom_column_admin", 10, 2 );
}

/**
 * Set capabilities for oik-sites
 * 
 * Is there any need to set the capabilities array, other than to set a different capability for create_posts?
  
 * The capability that is checked for when determining whether or not to display the Admin menu items is set from the value of the capabilities array.
 * e.g. Extract of $submenu from wp-admin\includes\menu.php
 
    [edit.php?post_type=oik_site] => Array
        (
            [5] => Array
                (
                    [0] => All Sites
                    [1] => edit_oik_sites
                    [2] => edit.php?post_type=oik_site
                )

            [10] => Array
                (
                    [0] => New Site
                    [1] => create_oik_sites
                    [2] => post-new.php?post_type=oik_site
                )

            [15] => Array
                (
                    [0] => Contents
                    [1] => manage_categories
                    [2] => edit-tags.php?taxonomy=site_content&amp;post_type=oik_site
                )

            [16] => Array
                (
                    [0] => Statuses
                    [1] => manage_categories
                    [2] => edit-tags.php?taxonomy=site_status&amp;post_type=oik_site
                )

            [17] => Array
                (
                    [0] => Audience
                    [1] => manage_categories
                    [2] => edit-tags.php?taxonomy=site_audience&amp;post_type=oik_site
                )

        )
 
 *
 * @param array $post_type_args - args for register_post_type
 * @return array - updated post type args
 
 * Post type object capabilities set when map_meta_cap is false/null
 
 
     [cap] => stdClass Object
        (
            [edit_post] => edit_oik_site
            [read_post] => read_oik_site
            [delete_post] => delete_oik_site
            [edit_posts] => edit_oik_sites
            [edit_others_posts] => edit_others_oik_sites
            [publish_posts] => publish_oik_sites
            [read_private_posts] => read_private_oik_sites
            [create_posts] => edit_oik_sites
        )
        
        
 * Post type object capabilities set when map_meta_cap is true
  include the 
 
 
        
        
 */
function oik_sites_capabilities( $post_type_args ) {
  $post_type_args['capability_type'] = 'oik_site';
  $post_type_args['capabilities'] = array( 'create_posts' => 'create_oik_sites' );
  $post_type_args['map_meta_cap'] = true;
  return( $post_type_args );
}

/**
 * Columns to display in the admin page
 */
function oik_sites_columns( $columns, $arg2=null ) {
  $columns["_website"] = __( "Website" ); 
  $columns["_rating"] = __( "Rating" ); 
  //bw_trace2();
  //bw_backtrace();
  return( $columns ); 
} 

/**
 * Implement "user_has_cap" filter for oik-sites
 *
 * We want to able to control it so that only certain user types may edit_posts or create_posts
 * How can this be achieved most efficiently. 
 * Is this based on "role" or do we just need a single capability to check for?
 * Well, we shouldn't check for the Role but we could create a capability called "oik-sites"
 * and base all our decisions on whether or not this has been given to the user.
 * And that's a bit silly since we could just use the same capability for each key in oik_sites_capabilities().
 * What we actually want to achieve is to have different levels of registered user: FREE, standard, premium
 *  FREE - registered users who can create ONE oik_site 
 *  Standard - users who can do a bit more than a FREE user 
 *  Upgraded - users who can do more again... such as
 * 
 * Capability              FREE  Standard  Upgraded 
 * --------------------    ----  --------  -------- 
 * delete_others_nodes     N     N         N 		
 * delete_nodes            N     N         N 	
 * delete_private_nodes    N     N         N  		
 * delete_published_nodes  N     N         N
 * edit_others_node        N     N         N			
 * edit_nodes              Y     Y         Y     
 * edit_private_nodes      N     N         N			
 * edit_published_nodes    N     Y         Y		
 * publish_nodes           N     Y         Y 	
 * read                    Y     Y         Y
 * read_private_node       N     N         N 
 * create_node             N     N         N
 * 
 * 
 * 
 *
 * The "normal" way of looking at it is like this?
 * {@link http://codex.wordpress.org/Roles_and_Capabilities}
 http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table
 *
 * Capability,Capability type,Map meta data,Super Admin,Admin,Editor,Author,Contributor,Subscriber
 * 
Capability type,map_meta_data,Capability,Super Admin,Administrator,Editor,Author,Contributor,Subscriber
post
page 

na,,manage_categories	Y,Y,Y			
na,,manage_links,Y,Y,Y			
na,,read,Y,Y,Y,Y,Y,Y
na,,upload_files,Y,Y,Y,Y		
 * 
page,,delete_others_pages,Y,Y,Y			
page,,delete_pages,Y,Y,Y			
page,,delete_private_pages,Y,Y,Y			
page,,delete_published_pages,Y,Y,Y			
page,,edit_others_pages,Y,Y,Y			
page,,edit_pages,Y,Y,Y			
page,,edit_private_pages,Y,Y,Y			
page,,edit_published_pages,Y,Y,Y			
page,,publish_pages,Y,Y,Y			
page,,read_private_pages,Y,Y,Y	
		
post,,delete_others_posts,Y,Y,Y			
post,,delete_posts,Y,Y,Y,Y,Y	
post,,delete_private_posts,Y,Y,Y			
post,,delete_published_posts,Y,Y,Y,Y		
post,,edit_others_posts,Y,Y,Y			
post,,edit_posts,Y,Y,Y,Y,Y	
post,,edit_private_posts,Y,Y,Y			
post,,edit_published_posts,Y,Y,Y,Y		
post,,publish_posts,Y,Y,Y,Y		
post,,read_private_posts,Y,Y,Y 
 		
 * How do we factor in the 'capability_type' and 'map_meta_data' ?
 
 *     @type string      $capability_type      The string to use to build the read, edit, and delete capabilities.
 *                                             May be passed as an array to allow for alternative plurals when using
 *                                             this argument as a base to construct the capabilities, e.g.
 *                                             array('story', 'stories'). Default 'post'.
 *     @type array       $capabilities         Array of capabilities for this post type. $capability_type is used
 *                                             as a base to construct capabilities by default.
 *                                             {@see get_post_type_capabilities()}.
 *     @type bool        $map_meta_cap         Whether to use the internal default meta capability handling.
 *                                             Default false.
 
 * 
 * 
 * @param array $caps = available capabilities 
 * @param array $cap = array of capability names 
 * @param array $args = context 
 * @param WP_User $user_id = the current user ID 
 * @return array - the updated capabilities
 *
 */
function oik_sites_user_has_cap( $caps, $cap, $args, $user ) {
  //bw_trace2( $cap, "cap", false );
  if ( isset( $cap[0] ) ) {
    $capstr = $cap[0];
  } else {
    $capstr = null;
  }
  switch ( $capstr ) {
    case 'edit_oik_sites':
    
      //bw_trace2( "can I edit oik sites?" );
      //bw_backtrace();
      $caps['edit_oik_sites'] = true;
      //gobang();
      global $pagenow;
      if ( $pagenow == "edit.php" && isset( $_REQUEST['post_type'] ) ) {
        //$pagenow .= '?post_type=' . $_REQUEST['post_type' ];
      }
      break;
     
    case 'create_oik_sites':
      //bw_trace2( "can I create oik sites?" );
      //bw_backtrace();
      
      if ( isset( $caps['manage_categories'] )  ) {
         $caps['create_oik_sites'] = true;
      }
      break;
      
    case 'edit_oik_site': 
      //bw_trace2( $caps, "caps- can I edit this oik site?", false);
      $caps['edit_oik_site'] = true;
      gobang(); // don't expect this request do we?
      break;
      
    default:
      // Remember that $cap is an array.
      // If we want to allow access then, for each of the requested $cap values, we need to set the $caps array to true.
      // So, when the original request is for "edit_post", and map_meta_cap() has determined that this is someone else's published posst
      // then we'll need to say that we can "edit_others_oik_sites" and "edit_published_oik_sites" 
      // We may also need to say we can "edit_private_oik_sites".
      // Similarly for "delete" and even "read".
      // Note: Without any further checking this implementation is particularly insecure. 
      
      //bw_trace2( $caps, "checking for oik_site $capstr", false );
      if ( isset( $caps['subscriber'] ) || isset( $caps['manage_categories'] )  ) {
        if ( strpos( $capstr, "oik_site" ) ) {
          foreach ( $cap as $key => $value ) {
            $caps[ $value ] = true;  
          }  
        }
      }  
  }
  
  return( $caps ); 
}

/**
 * Implement "map_meta_cap" filter for oik-sites
 *
 * When we don't want someone to do something then we require them to have the 'do_not_allow' capability...
 * which people don't get.
 * When we want to somehow limit them then we add a capability they'll need.
 * When we want to allow them to perform a function we reduce the capabilities they need
 * and/or ensure they need a capability they have. 
 *
 * @param $caps = available capabilities (array)
 * @param $cap = capability name (string)
 * @param $user_id = the current user ID (integer)
 * @param $args = context (array) 
 * @return array - the updated caps
 */
function oik_sites_map_meta_cap( $caps, $cap, $user_id, $args ) {
  //bw_trace2( $cap, "cap", false );
  if ( $cap == "edit_oik_sites" ) {
    //bw_trace2();
    //bw_backtrace();
    //gobang();
  
  }
  return( $caps );
}

/**
 * Implement "add_menu_classes" filter for oik-sites
 *
 * Part 2 of the Workaround for TRAC #29714  
 */
function oik_sites_add_menu_classes( $menu ) {
  global $pagenow;
  if ( false !== strpos( $pagenow, "edit.php" ) ) {
    $pagenow = "edit.php";
  }
  return( $menu );
}

/** 
 * Function to invoke when oik-sites plugin file is loaded 
 */
function oik_sites_loaded() {
  add_action( 'oik_fields_loaded', 'oik_sites_init' );
  add_filter( 'user_has_cap', "oik_sites_user_has_cap", 1, 4 );
  add_filter( 'map_meta_cap', "oik_sites_map_meta_cap", 10, 4 );
  add_filter( 'show_admin_bar', '__return_true');
  add_filter( 'show_admin_bar', 'oik_sites_report_filters');
  add_filter( "add_menu_classes", "oik_sites_add_menu_classes" );
}

oik_sites_loaded();

/**
 * Report hooks for the current filter
 *
 * Sometimes things aren't working as expected
 * Perhaps the filters or action hooks aren't quite what you thought they were.
 * Let's take a look.
 * 
 * 
 */

function oik_sites_report_filters( $content ) {
  global $wp_filter;
  
  //bw_trace2( $wp_filter, "wp_filter" );
  return( $content );
}








  

 
