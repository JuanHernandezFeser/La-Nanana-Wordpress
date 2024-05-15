<?php

/**
 * Title: Service CTA Section
 * Slug: blockbooster/service-cta
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/icon_service_1.jpg',
    $blockbooster_url . 'assets/images/icon_service_2.jpg',
    $blockbooster_url . 'assets/images/icon_service_3.jpg'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"0px","left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"80px"},"margin":{"top":"0","bottom":"0"}},"border":{"bottom":{"width":"0px","style":"none"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-color-background-color has-background" style="border-bottom-style:none;border-bottom-width:0px;margin-top:0;margin-bottom:0;padding-top:80px;padding-right:var(--wp--preset--spacing--40);padding-bottom:0px;padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"40px"}}}} -->
    <div class="wp-block-columns"><!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"border":{"radius":"12px","width":"0px","style":"none"}},"backgroundColor":"light-shade","className":"blockbooster-service-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-service-box has-light-shade-background-color has-background" style="border-style:none;border-width:0px;border-radius:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"id":3928,"width":"82px","sizeSlug":"full","linkDestination":"none","align":"center","style":{"border":{"radius":"7px"}}} -->
                <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3928" style="border-radius:7px;width:82px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"textAlign":"center","level":4,"style":{"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h4 class="wp-block-heading has-text-align-center has-big-font-size" style="margin-top:30px"><?php esc_html_e('24/7 Support', 'blockbooster') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"border":{"radius":"12px","width":"0px","style":"none"}},"backgroundColor":"light-shade","className":"blockbooster-service-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-service-box has-light-shade-background-color has-background" style="border-style:none;border-width:0px;border-radius:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"id":3928,"width":"82px","sizeSlug":"full","linkDestination":"none","align":"center","style":{"border":{"radius":"7px"}}} -->
                <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[1]) ?>" alt="" class="wp-image-3928" style="border-radius:7px;width:82px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"textAlign":"center","level":4,"style":{"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h4 class="wp-block-heading has-text-align-center has-big-font-size" style="margin-top:30px"><?php esc_html_e('Exclusive Design', 'blockbooster') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"border":{"radius":"12px","width":"0px","style":"none"}},"backgroundColor":"light-shade","className":"blockbooster-service-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-service-box has-light-shade-background-color has-background" style="border-style:none;border-width:0px;border-radius:12px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"id":3928,"width":"82px","sizeSlug":"full","linkDestination":"none","align":"center","style":{"border":{"radius":"7px"}}} -->
                <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-3928" style="border-radius:7px;width:82px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"textAlign":"center","level":4,"style":{"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h4 class="wp-block-heading has-text-align-center has-big-font-size" style="margin-top:30px"><?php esc_html_e('Powerful Tools', 'blockbooster') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->