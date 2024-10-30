<?php

/**
 * Plugin main file.
 *
 * @wordpress-plugin
 * Plugin Name:       Kntnt's Row Closer for Beaver Builder Page Builder
 * Plugin URI:        https://github.com/TBarregren/kntnt-bb-row-closer
 * Description:       WordPress plugin that allows a row in a layout created with Beaver Builder's Page Builder to be visible for a visitor until she clicks on a configured element (e.g. a link, button, icon or the row itself) upon which the row closes and remain closed for a configured time (e.g. the session, number of days or "for ever").
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.se/
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       kntnt-bb-row-closer
 * Domain Path:       /languages
 */

namespace Kntnt\BB_Row_Closer;

defined( 'WPINC' ) || die;

new Plugin();

final class Plugin {

	// Start here. :-)
	public function __construct() {

		// Setup localization.
		load_plugin_textdomain( 'kntnt-bb-row-closer', false, 'languages' );

		// Defer running this plugin until all plugins are loaded.
		add_action( 'plugins_loaded', [ $this, 'run' ] );

	}

	public function run() {

		// For all rows, on the tab "Advanced", in the section "Visibility",
		// add to the dropdown for "Display" the option "Row has not been closed"
		// and realted fields. The machine readable name of this option is `not_closed`.
		add_filter( 'fl_builder_register_settings_form', [ $this, 'settings_form' ], 10, 2 );

		// Prevent rows that have been closed to be rendered.
		// Already rendered and cached rows are hidden by CSS.
		add_filter( 'fl_builder_is_node_visible', [ $this, 'is_visible' ], 10, 2 );

		// Add the CSS class .closing-row
		add_filter( 'fl_builder_row_attributes', [ $this, 'add_attributes' ], 10, 2 );

		// Add animation and cookie management to each row using the `not_closed` option.
		add_filter( 'fl_builder_render_js', [ $this, 'add_js' ], 10, 3 );

		// Add CSS- and JS-files. The CSS file is necessary in case the rendered row has been cached.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

	}

	// Extends row settings with those neededfor the `not_closed` option.
	public function settings_form( $form, $id ) {

		// Do nothing if settings form for something else than a row.
		if ( 'row' != $id ) {
			return $form;
		}

		// The array with fields we are going to work with.
		$fields =& $form['tabs']['advanced']['sections']['visibility']['fields'];

		// Add the new option "not closed" to the visibility section under the advanced tab.
		$fields['visibility_display']['options'] = $this->array_insert( $fields['visibility_display']['options'], - 1, [
			'not_closed' => __( 'Row has not been closed', 'kntnt-bb-row-closer' ),
		] );

		// List of fields to show when the option `not_closed` is chosen.
		$fields['visibility_display']['toggle']['not_closed']['fields'] = [
			'not_closed_selector',
			'not_closed_time',
			'not_closed_cookie_expiration',
			'not_closed_cookie_domain',
		];

		// "Not closed" field for CSS selector.
		$fields['not_closed_selector'] = [
			'type'    => 'text',
			'label'   => sprintf( __( 'Closing trigger selector (e.g. %s)', 'kntnt-bb-row-closer' ), '<code>.closing-row .fl_button</code>' ),
			'default' => '.closing-row .fl_button',
			'preview' => [
				'type' => 'none'
			],
		];

		// "Not closed" field for closing speed.
		$fields['not_closed_time'] = [
			'type'        => 'unit',
			'label'       => __( 'Close time', 'kntnt-bb-row-closer' ),
			'description' => __( 'milliseconds', 'kntnt-bb-row-closer' ),
			'default'     => '500',
			'preview'     => [
				'type' => 'none'
			],
		];

		// "Not closed" field for cookie expiration.
		$fields['not_closed_cookie_expiration'] = [
			'type'        => 'unit',
			'label'       => __( 'Cookie expiration', 'kntnt-bb-row-closer' ),
			'description' => __( 'days', 'kntnt-bb-row-closer' ),
			'default'     => '0',
			'preview'     => [
				'type' => 'none'
			],
		];

		// "Not closed" field for cookie domain.
		$fields['not_closed_cookie_domain'] = [
			'type'    => 'text',
			'label'   => __( 'Cookie domain', 'kntnt-bb-row-closer' ),
			'default' => '',
			'preview' => [
				'type' => 'none'
			],
		];

		return $form;

	}

	// Returns false if the row given by $node is closed, and $is_visible otherwise.
	public function is_visible( $is_visible, $node ) {
		if ( isset( $node->settings->visibility_display ) && 'not_closed' == $node->settings->visibility_display ) {
			return ! isset( $_COOKIE["row_{$node->node}_closed"] );
		} else {
			return $is_visible;
		}
	}

	// Add CSS class `.closing-row` to any row with the not_closed option chosen.
	public function add_attributes( $attrs, $row ) {
		if ( isset( $row->settings->visibility_display ) && 'not_closed' == $row->settings->visibility_display ) {
			$attrs['class'][] = 'closing-row';
		}
		return $attrs;
	}

	// Add JavaScript that sets a row specific cookie for each row that is closed.
	public function add_js( $js, $nodes, $global_settings ) {
		foreach ( $nodes['rows'] as $nid => $row ) {
			if ( isset( $row->settings->visibility_display ) && 'not_closed' == $row->settings->visibility_display ) {

				$container_selector = ".fl-node-{$nid}";
				$button_selector    = $row->settings->not_closed_selector ? $row->settings->not_closed_selector : "$container_selector .fl-button";
				$cookie_name        = "row_{$nid}_closed";
				$cookie_expires     = $row->settings->not_closed_cookie_expiration ? 86400 * $row->settings->not_closed_cookie_expiration : 0;
				$cookie_domain      = $row->settings->not_closed_cookie_domain;
				$close_time         = $row->settings->not_closed_time ? $row->settings->not_closed_time : 0;

				ob_start();
				include "partials/kntnt-bb-row-closer.js.php";
				$js .= ob_get_clean();

			}
		}
		return $js;
	}

	// Add this plugin's CSS and JavaScript.
	public function enqueue_assets() {
		$this->enqueue_style( "kntnt-bb-row-closer.css" );
		$this->enqueue_script( 'cookies.min.js' );
	}

	// Returns a new array that contains all elements of $dst with those of $src
	// inserted at position $pos.
	private function array_insert($dst, $pos, $src) {
		return array_slice($dst, 0, $pos, true) + $src + array_slice($dst, $pos, NULL, true);
	}

	// Add the CSS file $css located in a folder namned `css` relative this file.
	private function enqueue_style( $css, $deps = [] ) {
		wp_enqueue_style( $css, plugins_url( "/css/$css", __FILE__ ), $deps );
	}

	// Add the JavaScript file $js located in a folder namned `js` relative this file.
	private function enqueue_script( $js, $deps = [] ) {
		wp_enqueue_script( $js, plugins_url( "/js/$js", __FILE__ ), $deps );
	}

}
