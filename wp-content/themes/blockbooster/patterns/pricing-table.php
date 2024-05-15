<?php

/**
 * Title: Pricing Tables Layout
 * Slug: blockbooster/pricing-table
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/check_circle.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"light-shade","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-shade-background-color has-background" style="padding-top:80px;padding-right:var(--wp--preset--spacing--40);padding-bottom:80px;padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"className":"blockbooster-flip-right","layout":{"type":"constrained","contentSize":"760px"}} -->
    <div class="wp-block-group blockbooster-flip-right"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"60px"}},"backgroundColor":"light-color","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-light-color-background-color has-background" style="border-radius:60px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--40)"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}}},"textColor":"secondary","fontSize":"small"} -->
                <h4 class="wp-block-heading has-text-align-center has-secondary-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:400"><?php esc_html_e('Simple Pricing', 'blockbooster') ?></h4>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"bottom":"24px","top":"16px"}}},"fontSize":"xx-large"} -->
        <h1 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="margin-top:16px;margin-bottom:24px;font-style:normal;font-weight:600"><?php esc_html_e('Find the Best Fit for Your Needs', 'blockbooster') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"24px"},"margin":{"top":"60px"}}}} -->
    <div class="wp-block-columns" style="margin-top:60px"><!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"border":{"radius":"7px","width":"0px","style":"none"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}}},"backgroundColor":"light-color","className":"is-style-homedroid-boxshadow is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-homedroid-boxshadow is-style-blockbooster-boxshadow blockbooster-hover-box has-light-color-background-color has-background" style="border-style:none;border-width:0px;border-radius:7px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1"}},"fontSize":"xx-large"} -->
                    <h1 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600;line-height:1"><?php esc_html_e('$49', 'blockbooster') ?></h1>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('/Year', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"fontSize":"medium"} -->
                <h4 class="wp-block-heading has-medium-font-size" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);font-style:normal;font-weight:500"><?php esc_html_e('Basic', 'blockbooster') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Intuitive world-class Support tools For growing teams and agency', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"28px","bottom":"28px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:28px;margin-bottom:28px"><!-- wp:button {"textColor":"light-color","width":100,"style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"8px"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size has-medium-font-size" style="font-style:normal;font-weight:500"><a class="wp-block-button__link has-light-color-color has-text-color has-link-color wp-element-button" style="border-radius:8px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)"><?php esc_html_e('Get Started', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Live Chat for support', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Ticketing Workflows', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Services Level Agreement Rules', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Role Based Permissions', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Outcome Reporting', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-slide-up"} -->
        <div class="wp-block-column blockbooster-slide-up"><!-- wp:group {"style":{"border":{"radius":"7px","width":"0px","style":"none"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}}},"backgroundColor":"light-color","className":"is-style-homedroid-boxshadow is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-homedroid-boxshadow is-style-blockbooster-boxshadow blockbooster-hover-box has-light-color-background-color has-background" style="border-style:none;border-width:0px;border-radius:7px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1"}},"fontSize":"xx-large"} -->
                    <h1 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600;line-height:1"><?php esc_html_e('$149', 'blockbooster') ?></h1>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('/Year', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"fontSize":"medium"} -->
                <h4 class="wp-block-heading has-medium-font-size" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);font-style:normal;font-weight:500"><?php esc_html_e('Standard', 'blockbooster') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Intuitive world-class Support tools For growing teams and agency', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"28px","bottom":"28px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:28px;margin-bottom:28px"><!-- wp:button {"textColor":"light-color","width":100,"style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"8px"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size has-medium-font-size" style="font-style:normal;font-weight:500"><a class="wp-block-button__link has-light-color-color has-text-color has-link-color wp-element-button" style="border-radius:8px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)"><?php esc_html_e('Get Started', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Live Chat for support', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Ticketing Workflows', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Services Level Agreement Rules', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Role Based Permissions', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Outcome Reporting', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-slide-up"} -->
        <div class="wp-block-column blockbooster-slide-up"><!-- wp:group {"style":{"border":{"radius":"7px","width":"0px","style":"none"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}}},"backgroundColor":"light-color","className":"is-style-homedroid-boxshadow is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-homedroid-boxshadow is-style-blockbooster-boxshadow blockbooster-hover-box has-light-color-background-color has-background" style="border-style:none;border-width:0px;border-radius:7px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1"}},"fontSize":"xx-large"} -->
                    <h1 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600;line-height:1"><?php esc_html_e('$449', 'blockbooster') ?></h1>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('/Year', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"fontSize":"medium"} -->
                <h4 class="wp-block-heading has-medium-font-size" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30);font-style:normal;font-weight:500"><?php esc_html_e('Enterprise', 'blockbooster') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Intuitive world-class Support tools For growing teams and agency', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"28px","bottom":"28px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:28px;margin-bottom:28px"><!-- wp:button {"textColor":"light-color","width":100,"style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"8px"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size has-medium-font-size" style="font-style:normal;font-weight:500"><a class="wp-block-button__link has-light-color-color has-text-color has-link-color wp-element-button" style="border-radius:8px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)"><?php esc_html_e('Get Started', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Live Chat for support', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Ticketing Workflows', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Services Level Agreement Rules', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Role Based Permissions', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:image {"id":3921,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3921" style="width:24px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":5,"style":{"spacing":{"padding":{"top":"6px"}}},"fontSize":"normal"} -->
                        <h5 class="wp-block-heading has-normal-font-size" style="padding-top:6px"><?php esc_html_e('Outcome Reporting', 'blockbooster') ?></h5>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->