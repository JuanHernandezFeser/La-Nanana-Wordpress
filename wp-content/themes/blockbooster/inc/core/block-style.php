<?php

/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package blockbooster
 * @since 1.0.0
 */

if (function_exists('register_block_style')) {
    /**
     * Register block styles.
     *
     * @since 0.1
     *
     * @return void
     */
    function blockbooster_register_block_styles()
    {
        register_block_style(
            'core/columns',
            array(
                'name'  => 'blockbooster-boxshadow',
                'label' => __('Box Shadow', 'blockbooster')
            )
        );

        register_block_style(
            'core/column',
            array(
                'name'  => 'blockbooster-boxshadow',
                'label' => __('Box Shadow', 'blockbooster')
            )
        );
        register_block_style(
            'core/column',
            array(
                'name'  => 'blockbooster-boxshadow-medium',
                'label' => __('Box Shadow Medium', 'blockbooster')
            )
        );
        register_block_style(
            'core/column',
            array(
                'name'  => 'blockbooster-boxshadow-large',
                'label' => __('Box Shadow Large', 'blockbooster')
            )
        );

        register_block_style(
            'core/group',
            array(
                'name'  => 'blockbooster-boxshadow',
                'label' => __('Box Shadow', 'blockbooster')
            )
        );
        register_block_style(
            'core/group',
            array(
                'name'  => 'blockbooster-boxshadow-medium',
                'label' => __('Box Shadow Medium', 'blockbooster')
            )
        );
        register_block_style(
            'core/group',
            array(
                'name'  => 'blockbooster-boxshadow-large',
                'label' => __('Box Shadow Larger', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-boxshadow',
                'label' => __('Box Shadow', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-boxshadow-medium',
                'label' => __('Box Shadow Medium', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-boxshadow-larger',
                'label' => __('Box Shadow Large', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-image-pulse',
                'label' => __('Iamge Pulse Effect', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-image-hover-pulse',
                'label' => __('Hover Pulse Effect', 'blockbooster')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'blockbooster-image-hover-rotate',
                'label' => __('Hover Rotate Effect', 'blockbooster')
            )
        );
        register_block_style(
            'core/columns',
            array(
                'name'  => 'blockbooster-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'blockbooster')
            )
        );

        register_block_style(
            'core/column',
            array(
                'name'  => 'blockbooster-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'blockbooster')
            )
        );

        register_block_style(
            'core/group',
            array(
                'name'  => 'blockbooster-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'blockbooster')
            )
        );

        register_block_style(
            'core/post-terms',
            array(
                'name'  => 'categories-background-with-round',
                'label' => __('Background with round corner style', 'blockbooster')
            )
        );
        register_block_style(
            'core/post-title',
            array(
                'name'  => 'title-hover-primary-color',
                'label' => __('Hover: Primary color', 'blockbooster')
            )
        );
        register_block_style(
            'core/post-title',
            array(
                'name'  => 'title-hover-secondary-color',
                'label' => __('Hover: Secondary color', 'blockbooster')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-primary-color',
                'label' => __('Hover: Primary Color', 'blockbooster')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-secondary-color',
                'label' => __('Hover: Secondary Color', 'blockbooster')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-primary-bgcolor',
                'label' => __('Hover: Primary color fill', 'blockbooster')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-secondary-bgcolor',
                'label' => __('Hover: Secondary color fill', 'blockbooster')
            )
        );

        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-primary-color',
                'label' => __('Hover: Primary Color', 'blockbooster')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-secondary-color',
                'label' => __('Hover: Secondary Color', 'blockbooster')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-primary-fill',
                'label' => __('Hover: Primary Fill', 'blockbooster')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-secondary-fill',
                'label' => __('Hover: secondary Fill', 'blockbooster')
            )
        );

        register_block_style(
            'core/list',
            array(
                'name'  => 'list-style-no-bullet',
                'label' => __('List Style: Hide bullet', 'blockbooster')
            )
        );
        register_block_style(
            'core/list',
            array(
                'name'  => 'hide-bullet-list-link-hover-style-primary',
                'label' => __('Hover style with primary color and hide bullet', 'blockbooster')
            )
        );
        register_block_style(
            'core/list',
            array(
                'name'  => 'hide-bullet-list-link-hover-style-secondary',
                'label' => __('Hover style with secondary color and hide bullet', 'blockbooster')
            )
        );

        register_block_style(
            'core/gallery',
            array(
                'name'  => 'enable-grayscale-mode-on-image',
                'label' => __('Enable Grayscale Mode on Image', 'blockbooster')
            )
        );
        register_block_style(
            'core/social-links',
            array(
                'name'  => 'social-icon-border',
                'label' => __('Border Style', 'blockbooster')
            )
        );
        register_block_style(
            'core/page-list',
            array(
                'name'  => 'blockbooster-page-list-bullet-hide-style',
                'label' => __('Hide Bullet Style', 'blockbooster')
            )
        );
        register_block_style(
            'core/categories',
            array(
                'name'  => 'blockbooster-categories-bullet-hide-style',
                'label' => __('Hide Bullet Style', 'blockbooster')
            )
        );
        register_block_style(
            'core/cover',
            array(
                'name'  => 'blockbooster-cover-round-style',
                'label' => __('Round Corner Style', 'blockbooster')
            )
        );
    }
    add_action('init', 'blockbooster_register_block_styles');
}
