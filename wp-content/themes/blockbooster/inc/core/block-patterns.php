<?php

/**
 * blockbooster: Block Patterns
 *
 * @since blockbooster 1.0.0
 */

/**
 * Registers pattern categories for blockbooster
 *
 * @since blockbooster 1.0.0
 *
 * @return void
 */
function blockbooster_register_pattern_category()
{
	$block_pattern_categories = array(
		'blockbooster' => array('label' => __('Blockbooster Patterns', 'blockbooster')),
		'blockbooster-homes' => array('label' => __('Blockbooster Homepage Templates', 'blockbooster')),
		'blockbooster-about' => array('label' => __('Blockbooster About Us Page', 'blockbooster')),
		'blockbooster-service' => array('label' => __('Blockbooster Services Page', 'blockbooster')),
		'blockbooster-contact' => array('label' => __('Blockbooster Contact Us Page', 'blockbooster'))
	);

	$block_pattern_categories = apply_filters('blockbooster_block_pattern_categories', $block_pattern_categories);

	foreach ($block_pattern_categories as $name => $properties) {
		if (!WP_Block_Pattern_Categories_Registry::get_instance()->is_registered($name)) {
			register_block_pattern_category($name, $properties); // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_pattern_category
		}
	}
}
add_action('init', 'blockbooster_register_pattern_category', 9);
