<?php
/**
 * Plugin Name: CP Admin Colors
 * Plugin URI: https://github.com/klein-the-donkey/cp-admin-colors
 * Description: Admin color schemes based on the ClassicPress brand
 * Version: 0.5.1
 * Author: Klein
 * Author URI: https://forums.classicpress.net/u/klein
 * Text Domain: admin_schemes
 * Domain Path: /languages
 */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class CP_Color_Scheme {

	/**
	 * Color scheme registered in this plugin.
	 *
	 * @since 1.0
	 * @access private
	 * @var array $colors Color scheme registered in this plugin.
	 *                    Needed for registering colors-fresh dependency.
	 */
	private $colors = array(
		'cp-default', 'cp-contrast', 'cp-purple', 'cp-bright', 'cp-superdark'
	);

	public function __construct() {
		/* Add new color schemes */
		add_action( 'admin_init' , array( $this, 'add_colors' ) );
		/* Add default setting to writing */
		add_action( 'admin_init' , array( $this, 'auto_default_color_scheme_init' ) );

		/* If default setting is on, make default */
		if ( get_option('auto_default_color_scheme') === '1' ) {
			add_action( 'user_register' , array( $this, 'set_default_admin_color' ) );
		}
	}

	/**
	 * Register color schemes.
	 */
	public function add_colors() {
		$suffix = is_rtl() ? '-rtl' : '';

		wp_admin_css_color(
			'cp-default', __( 'ClassicPress Default', 'admin_schemes' ),
			plugins_url( "cp-default/colors$suffix.css", __FILE__ ),
			array( '#057f99', '#3ebba6', '#89288f', '#361946' ),
			array( 'base' => '#f1f2f3', 'focus' => '#fff', 'current' => '#fff' )
		);

		wp_admin_css_color(
			'cp-contrast', __( 'ClassicPress Contrast', 'admin_schemes' ),
			plugins_url( "cp-contrast/colors$suffix.css", __FILE__ ),
			array( '#057f99', '#89288f', '#361946', '#040402' ),
			array( 'base' => '#f1f2f3', 'focus' => '#fff', 'current' => '#fff' )
		);

		wp_admin_css_color(
			'cp-purple', __( 'ClassicPress Purple', 'admin_schemes' ),
			plugins_url( "cp-purple/colors$suffix.css", __FILE__ ),
			array( '#361946', '#89288f', '#057f99', '#60cd6f' ),
			array( 'base' => '#f1f2f3', 'focus' => '#fff', 'current' => '#fff' )
		);

		wp_admin_css_color(
			'cp-bright', __( 'ClassicPress Bright', 'admin_schemes' ),
			plugins_url( "cp-bright/colors$suffix.css", __FILE__ ),
			array( '#3ebba6', '#361946', '#60cd6f', '#057f99' ),
			array( 'base' => '#f1f2f3', 'focus' => '#fff', 'current' => '#fff' )
		);

		wp_admin_css_color(
			'cp-superdark', __( 'ClassicPress Superdark', 'admin_schemes' ),
			plugins_url( "cp-superdark/colors$suffix.css", __FILE__ ),
			array( '#040402', '#2e123c', '#26a2a0', '#242424' ),
			array( 'base' => '#f1f2f3', 'focus' => '#fff', 'current' => '#fff' )
		);

	}

	/**
	 * Register setting.
	 */
	 
	/* Settings Init */
	public function auto_default_color_scheme_init(){
	 
	    /* Register Settings */
	    register_setting(
	        'writing',             	// Options group
	        'auto_default_color_scheme', // Option name/database
	        'auto_default_color_scheme_sanitize' 	// Sanitize callback function
	    );

	    /* Create settings section */
		add_settings_section(
            'color-scheme-section-01',          // Section ID
            'Default ClassicPress Admin Color Scheme',  // Section title
            [$this, 'cp_default_setting_description'], // Section callback function
            'writing'                          // Settings page slug
        );

		/* Create settings field */
		add_settings_field(
		    'color-scheme-field-01',       // Field ID
		    'Make ClassicPress color scheme default for new users',       // Field title 
		    [$this, 'cp_default_setting_field_callback'], // Field callback function
		    'writing',                    // Settings page slug
		    'color-scheme-section-01'               // Section ID
		);
	}
	 
	/* Sanitize Callback Function */
	public function auto_default_color_scheme_sanitize( $input ){
	    return isset( $input ) ? true : false;
	}
	 
	/* Setting Section Description */
	public function cp_default_setting_description(){
	    echo wpautop( "If this setting is active, all new users will automatically get the ClassicPress Default color scheme. This will not change the admin color schemes of existing users." );
	}
	 
	/* Settings Field Callback */
	public function cp_default_setting_field_callback(){
	    ?>
	    <label for="cp-color-scheme-default">
	        <input id="cp-color-scheme-default" type="checkbox" value="1" name="auto_default_color_scheme" <?php checked( get_option( 'auto_default_color_scheme', true ) ); ?>> Make ClassicPress color scheme default for new users.
	    </label>
	    <?php
	}

	/**
	 * Change default color setting.
	 */
	public function set_default_admin_color($user_id) {
	$args = array(
		'ID' => $user_id,
		'admin_color' => 'cp-default'
	);
	wp_update_user( $args );
}

}
global $cp_colors;
$cp_colors = new CP_Color_Scheme;

