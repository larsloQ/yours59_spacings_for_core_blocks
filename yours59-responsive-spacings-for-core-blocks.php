<?php
/**
 *
 * Plugin Name:  Yours59 Responsive Spacings for core blocks
 * Plugin URI:   https://github.com/larsloQ/yours59_spacings_for_core_blocks
 * Description:  Plugin provides an Panel for Gutenberg Inspector which allows you to fine-tune spacings for Core-Blocks.
 * Author:       larslo
 * Author URI:   https://larslo.de
 * Version:      1.0.1
 * Text Domain:  yours59
 *
 */

namespace Yours59SpaingsForCoreBlocks;

$yours59_spacings_for_core_blocks = Yours59SpaingsForCoreBlocks::getInstance();

class Yours59SpaingsForCoreBlocks {

	protected static $instance = null;
	private static $name       = 'Yours59 responsive Spacings for Core Blocks';
	private static $filename   = 'yours59-spacings-for-core-blocks';


	// Method to get the unique instance. Singleton
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self(); }
		self::$instance->init();

		return self::$instance;
	}

	private function __construct() {}
	private function __clone() {}
	public function __wakeup() {
		throw new Exception( 'Cannot unserialize' );
	}

	public function init() {
		if ( ! function_exists( 'register_block_type' ) ) {
			/*  if Gutenberg is not installed we stop here */
			return;
		}
		/* enqueue block-editor js and css */
		add_action(
			'enqueue_block_editor_assets',
			function() {
				wp_register_script(
					self::$filename,
					plugin_dir_url( __FILE__ ) . '/editor/' . self::$filename . '.js',
					array( 'wp-blocks', 'wp-element', 'wp-edit-post' ),
					'yours59'
				);
				/* some config, see config.php */
				require_once __DIR__ . '/config.php';
				/* inject config data to JS */
				wp_localize_script( self::$filename, 'yours59_spacings_server_side_settings', $server_side_settings );
				wp_enqueue_script( self::$filename );

				wp_enqueue_style(
					self::$filename,
					plugin_dir_url( __FILE__ ) . '/editor/' . self::$filename . '.css',
					array(),
					'yours59',
				);
			}
		);

		/* enqueue frontend assets */
		add_action(
			'wp_enqueue_scripts',
			function() {
				wp_enqueue_style(
					self::$filename,
					plugin_dir_url( __FILE__ ) . '/css/' . self::$filename . '.css',
					array(),
					'yours59',
				);
			},
			10
		);
	}

	/* thats it. */

} // class



