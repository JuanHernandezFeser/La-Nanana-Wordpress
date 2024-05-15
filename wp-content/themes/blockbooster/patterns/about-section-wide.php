<?php

/**
 * Title: About Us Section Wide Layout
 * Slug: blockbooster/about-section-wide
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/about_21.jpg',
    $blockbooster_url . 'assets/images/about_22.jpg',
    $blockbooster_url . 'assets/images/icon_21.png'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"margin":{"top":"0","bottom":"0"}}},"gradient":"gradient-bg-two","layout":{"type":"constrained","contentSize":"1400px"}} -->
<div class="wp-block-group has-gradient-bg-two-gradient-background has-background" style="margin-top:0;margin-bottom:0;padding-top:120px;padding-right:var(--wp--preset--spacing--40);padding-bottom:120px;padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"80px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":4460,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"24px"}},"className":"blockbooster-fade-up"} -->
            <figure class="wp-block-image size-full has-custom-border blockbooster-fade-up"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-4460" style="border-radius:24px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"10px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
            <div class="wp-block-group" style="margin-bottom:10px"><!-- wp:group {"style":{"spacing":{"padding":{"top":"5px","bottom":"5px","left":"14px","right":"14px"}},"border":{"radius":"50px"}},"backgroundColor":"border-color","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-border-color-background-color has-background" style="border-radius:50px;padding-top:5px;padding-right:14px;padding-bottom:5px;padding-left:14px"><!-- wp:heading {"textAlign":"center","level":5,"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"x-small"} -->
                    <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-link-color has-x-small-font-size" style="font-style:normal;font-weight:600;text-transform:uppercase"><?php esc_html_e('Our Story', 'blockbooster') ?></h5>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.3"}},"className":"blockbooster-flip-up","fontSize":"xx-large"} -->
            <h1 class="wp-block-heading blockbooster-flip-up has-xx-large-font-size" style="font-style:normal;font-weight:700;line-height:1.3"><?php esc_html_e('Pioneering Excellence in Tech for Over 10 Years', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"blockbooster-fade-up"} -->
            <p class="blockbooster-fade-up"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"className":"blockbooster-slide-up","style":{"spacing":{"margin":{"top":"40px"}}}} -->
            <div class="wp-block-buttons blockbooster-slide-up" style="margin-top:40px"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Explore More', 'blockbooster') ?></a></div>
                <!-- /wp:button -->

                <!-- wp:button {"backgroundColor":"transparent","textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"border":{"radius":"60px"},"typography":{"fontSize":"28px","fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"className":"is-style-button-hover-secondary-color"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color" style="font-size:28px;font-style:normal;font-weight:600"><a class="wp-block-button__link has-primary-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php esc_html_e('(888) 123-4567', 'blockbooster') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"120px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="margin-top:120px"><!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"10px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
            <div class="wp-block-group" style="margin-bottom:10px"><!-- wp:group {"style":{"spacing":{"padding":{"top":"5px","bottom":"5px","left":"14px","right":"14px"}},"border":{"radius":"50px"}},"backgroundColor":"border-color","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-border-color-background-color has-background" style="border-radius:50px;padding-top:5px;padding-right:14px;padding-bottom:5px;padding-left:14px"><!-- wp:heading {"textAlign":"center","level":5,"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"x-small"} -->
                    <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-link-color has-x-small-font-size" style="font-style:normal;font-weight:600;text-transform:uppercase"><?php esc_html_e('Mission &amp; Goal', 'blockbooster') ?></h5>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.3"}},"className":"blockbooster-flip-up","fontSize":"xx-large"} -->
            <h1 class="wp-block-heading blockbooster-flip-up has-xx-large-font-size" style="font-style:normal;font-weight:700;line-height:1.3"><?php esc_html_e('A Commitment to Excellence, Crafting Our Legacy!', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"className":"blockbooster-fade-up"} -->
            <p class="blockbooster-fade-up"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:columns {"style":{"spacing":{"margin":{"top":"40px"}}},"className":"blockbooster-fade-up"} -->
            <div class="wp-block-columns blockbooster-fade-up" style="margin-top:40px"><!-- wp:column {"style":{"spacing":{"blockGap":"14px"}}} -->
                <div class="wp-block-column"><!-- wp:image {"id":4474,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-4474" style="width:24px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('Lorem ipsum&nbsp;is placeholder text commonly used in the graphic &amp; industries.', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"style":{"spacing":{"blockGap":"14px"}}} -->
                <div class="wp-block-column"><!-- wp:image {"id":4474,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-4474" style="width:24px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('Lorem ipsum&nbsp;is placeholder text commonly used in the graphic &amp; industries.', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:columns {"style":{"spacing":{"margin":{"top":"40px","bottom":"30px"}}},"className":"blockbooster-fade-up"} -->
            <div class="wp-block-columns blockbooster-fade-up" style="margin-top:40px;margin-bottom:30px"><!-- wp:column {"style":{"spacing":{"blockGap":"14px"}}} -->
                <div class="wp-block-column"><!-- wp:image {"id":4474,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-4474" style="width:24px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('Lorem ipsum&nbsp;is placeholder text commonly used in the graphic &amp; industries.', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"style":{"spacing":{"blockGap":"14px"}}} -->
                <div class="wp-block-column"><!-- wp:image {"id":4474,"width":"24px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-4474" style="width:24px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('Lorem ipsum&nbsp;is placeholder text commonly used in the graphic &amp; industries.', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:buttons {"className":"blockbooster-slide-up"} -->
            <div class="wp-block-buttons blockbooster-slide-up"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Explore More', 'blockbooster') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":4461,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"24px"}},"className":"blockbooster-fade-up"} -->
            <figure class="wp-block-image size-full has-custom-border blockbooster-fade-up"><img src="<?php echo esc_url($blockbooster_images[1]) ?>" alt="" class="wp-image-4461" style="border-radius:24px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->