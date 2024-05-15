<?php

/**
 * Title: Services Page
 * Slug: blockbooster/blockbooster-service-page
 * Categories: blockbooster-service
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/heading_bg.jpg',
    $blockbooster_url . 'assets/images/about-image.jpg',
    $blockbooster_url . 'assets/images/about_image_2.jpg',
    $blockbooster_url . 'assets/images/project_1.jpg'
);
?>
<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"100%"}} -->
<main class="wp-block-group has-light-color-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
    <!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[0]) ?>","id":4270,"dimRatio":80,"overlayColor":"primary","isUserOverlayColor":true,"focalPoint":{"x":1,"y":0.6},"minHeight":360,"contentPosition":"center center","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"className":"is-style-default","layout":{"type":"constrained","contentSize":"640px"}} -->
    <div class="wp-block-cover is-style-default" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-4270" alt="" src="<?php echo esc_url($blockbooster_images[0]) ?>" style="object-position:100% 60%" data-object-fit="cover" data-object-position="100% 60%" />
        <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"light-color","fontSize":"xx-large"} -->
            <h1 class="wp-block-heading has-text-align-center has-light-color-color has-text-color has-link-color has-xx-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Our Services', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-shade"}}}},"textColor":"light-shade","fontSize":"normal"} -->
            <p class="has-text-align-center has-light-shade-color has-text-color has-link-color has-normal-font-size"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->
        </div>
    </div>
    <!-- /wp:cover -->


    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"1180px"}} -->
    <div class="wp-block-group has-light-color-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"0px"}}}} -->
        <div class="wp-block-columns are-vertically-aligned-center" style="margin-top:0px"><!-- wp:column {"verticalAlignment":"center","width":"35%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:35%"><!-- wp:image {"id":3625,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($blockbooster_images[1]) ?>" alt="" class="wp-image-3625" /></figure>
                <!-- /wp:image -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center"} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.3"}},"fontSize":"xx-large"} -->
                <h1 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600;line-height:1.3"><?php esc_html_e('Service Title', 'blockbooster') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                    <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Book Now', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->

        <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"120px"}}}} -->
        <div class="wp-block-columns are-vertically-aligned-center" style="margin-top:120px"><!-- wp:column {"verticalAlignment":"center"} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.3"}},"fontSize":"xx-large"} -->
                <h1 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600;line-height:1.3"><?php esc_html_e('Service Title', 'blockbooster') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                    <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Book Now', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"35%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:35%"><!-- wp:image {"id":3923,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-3923" /></figure>
                <!-- /wp:image -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->

        <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"120px"}}}} -->
        <div class="wp-block-columns are-vertically-aligned-center" style="margin-top:120px"><!-- wp:column {"verticalAlignment":"center","width":"35%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:35%"><!-- wp:image {"id":3625,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($blockbooster_images[3]) ?>" alt="" class="wp-image-3625" /></figure>
                <!-- /wp:image -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center"} -->
            <div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.3"}},"fontSize":"xx-large"} -->
                <h1 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600;line-height:1.3"><?php esc_html_e('Service Title', 'blockbooster') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                    <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Book Now', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
    <div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"dimRatio":0,"overlayColor":"light-color","isUserOverlayColor":true,"minHeight":420,"isDark":false,"style":{"border":{"radius":"0px"}},"layout":{"type":"constrained","contentSize":"600px"}} -->
        <div class="wp-block-cover is-light" style="border-radius:0px;min-height:420px"><span aria-hidden="true" class="wp-block-cover__background has-light-color-background-color has-background-dim-0 has-background-dim"></span>
            <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.4","fontSize":"44px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}},"textColor":"heading-color","className":"blockbooster-flip-down"} -->
                <h1 class="wp-block-heading has-text-align-center blockbooster-flip-down has-heading-color-color has-text-color has-link-color" style="font-size:44px;font-style:normal;font-weight:600;line-height:1.4"><?php esc_html_e('Let’s Work Together on Your Next Project?', 'blockbooster') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:buttons {"className":"blockbooster-slide-up","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"0"}}} -->
                <div class="wp-block-buttons blockbooster-slide-up" style="margin-top:30px"><!-- wp:button {"backgroundColor":"primary","textColor":"light-color","style":{"spacing":{"padding":{"left":"var:preset|spacing|70","right":"var:preset|spacing|70","top":"1.3rem","bottom":"1.3rem"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"className":"is-style-button-hover-secondary-bgcolor"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-light-color-color has-primary-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:1.3rem;padding-right:var(--wp--preset--spacing--70);padding-bottom:1.3rem;padding-left:var(--wp--preset--spacing--70)"><?php esc_html_e('Let’s Meet Up', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->

                    <!-- wp:button {"backgroundColor":"transparent","textColor":"primary","style":{"spacing":{"padding":{"left":"var:preset|spacing|70","right":"var:preset|spacing|70","top":"1.3rem","bottom":"1.3rem"}},"border":{"radius":"60px"},"typography":{"fontSize":"21px","fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"className":"is-style-button-hover-secondary-color"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color" style="font-size:21px;font-style:normal;font-weight:600"><a class="wp-block-button__link has-primary-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:1.3rem;padding-right:var(--wp--preset--spacing--70);padding-bottom:1.3rem;padding-left:var(--wp--preset--spacing--70)"><?php esc_html_e('Hotline : +1 (012) 345-678', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
        </div>
        <!-- /wp:cover -->
    </div>
    <!-- /wp:group -->
</main>
<!-- /wp:group -->