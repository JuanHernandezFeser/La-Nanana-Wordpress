<?php

/**
 * Title: Logos Showcase
 * Slug: blockbooster/logo-showcase
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/brand_1.png',
    $blockbooster_url . 'assets/images/brand_2.png',
    $blockbooster_url . 'assets/images/brand_3.png',
    $blockbooster_url . 'assets/images/brand_4.png',
    $blockbooster_url . 'assets/images/brand_5.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"},"top":[],"right":[],"left":[]}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group has-light-color-background-color has-background" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"blockbooster-flip-up","layout":{"type":"constrained","contentSize":"640px"}} -->
    <div class="wp-block-group blockbooster-flip-up"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.7"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"big"} -->
        <h1 class="wp-block-heading has-text-align-center has-big-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:600;line-height:1.7"><?php esc_html_e('Showcasing Our Valued Partners: A Comprehensive Display of Collaboration and Mutual Success', 'blockbooster') ?></h1>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"className":"blockbooster-zoom-in-up","layout":{"type":"constrained","contentSize":"100%"}} -->
    <div class="wp-block-group blockbooster-zoom-in-up" style="margin-top:0;margin-bottom:0"><!-- wp:gallery {"columns":6,"imageCrop":false,"linkTo":"none","style":{"spacing":{"margin":{"top":"40px"}}},"className":"blockbooster-brands is-style-default"} -->
        <figure class="wp-block-gallery has-nested-images columns-6 blockbooster-brands is-style-default" style="margin-top:40px"><!-- wp:image {"id":4568,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-4568" /></figure>
            <!-- /wp:image -->

            <!-- wp:image {"id":4567,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($blockbooster_images[1]) ?>" alt="" class="wp-image-4567" /></figure>
            <!-- /wp:image -->

            <!-- wp:image {"id":4569,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-4569" /></figure>
            <!-- /wp:image -->

            <!-- wp:image {"id":4565,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($blockbooster_images[3]) ?>" alt="" class="wp-image-4565" /></figure>
            <!-- /wp:image -->

            <!-- wp:image {"id":4566,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($blockbooster_images[4]) ?>" alt="" class="wp-image-4566" /></figure>
            <!-- /wp:image -->

            <!-- wp:image {"id":4569,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-4569" /></figure>
            <!-- /wp:image -->
        </figure>
        <!-- /wp:gallery -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->