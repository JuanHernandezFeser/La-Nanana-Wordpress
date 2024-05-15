<?php

/**
 * Title: Featured Projects Section
 * Slug: blockbooster/featured-project
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/p1.jpg',
    $blockbooster_url . 'assets/images/p2.jpg',
    $blockbooster_url . 'assets/images/p3.jpg',
    $blockbooster_url . 'assets/images/p4.jpg'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"7rem","bottom":"7rem","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-color-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:7rem;padding-right:var(--wp--preset--spacing--40);padding-bottom:7rem;padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"30px"}}},"className":"blockbooster-fade-up"} -->
    <div class="wp-block-columns blockbooster-fade-up"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
            <h1 class="wp-block-heading" style="font-style:normal;font-weight:600"><?php esc_html_e('Featured Projects', 'blockbooster') ?></h1>
            <!-- /wp:heading -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:paragraph -->
            <p><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('View all Portfolio', 'blockbooster') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"30px","left":"30px"},"margin":{"top":"60px"}}}} -->
    <div class="wp-block-columns" style="margin-top:60px"><!-- wp:column {"width":"40%","className":"blockbooster-zoom-in-up"} -->
        <div class="wp-block-column blockbooster-zoom-in-up" style="flex-basis:40%"><!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[0]) ?>","id":4553,"dimRatio":0,"customOverlayColor":"#40352d","isUserOverlayColor":true,"minHeight":540,"style":{"border":{"radius":"12px"}},"className":"blockbooster-portfolio-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-cover blockbooster-portfolio-box" style="border-radius:12px;min-height:540px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#40352d"></span><img class="wp-block-cover__image-background wp-image-4553" alt="" src="<?php echo esc_url($blockbooster_images[0]) ?>" data-object-fit="cover" />
                <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"blockbooster-portfolio-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group blockbooster-portfolio-content"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","fontSize":"large"} -->
                        <h3 class="wp-block-heading has-text-align-center has-light-color-color has-text-color has-link-color has-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Portfolio One', 'blockbooster') ?></h3>
                        <!-- /wp:heading -->

                        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                        <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","textColor":"light-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"},"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"className":"is-style-button-hover-secondary-bgcolor"} -->
                            <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-light-color-color has-primary-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--50)"><?php esc_html_e('Read More', 'blockbooster') ?></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
            </div>
            <!-- /wp:cover -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"60%"} -->
        <div class="wp-block-column" style="flex-basis:60%"><!-- wp:columns {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":{"left":"30px"}}}} -->
            <div class="wp-block-columns" style="margin-top:0px"><!-- wp:column {"className":"blockbooster-zoom-in-up"} -->
                <div class="wp-block-column blockbooster-zoom-in-up"><!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[1]) ?>","id":4545,"dimRatio":0,"customOverlayColor":"#b1b9b6","isUserOverlayColor":true,"minHeight":256,"isDark":false,"style":{"border":{"radius":"12px"}},"className":"blockbooster-portfolio-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-cover is-light blockbooster-portfolio-box" style="border-radius:12px;min-height:256px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#b1b9b6"></span><img class="wp-block-cover__image-background wp-image-4545" alt="" src="<?php echo esc_url($blockbooster_images[1]) ?>" data-object-fit="cover" />
                        <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"blockbooster-portfolio-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                            <div class="wp-block-group blockbooster-portfolio-content"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","fontSize":"large"} -->
                                <h3 class="wp-block-heading has-text-align-center has-light-color-color has-text-color has-link-color has-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Portfolio Two', 'blockbooster') ?></h3>
                                <!-- /wp:heading -->

                                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                                <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","textColor":"light-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"},"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"className":"is-style-button-hover-secondary-bgcolor"} -->
                                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-light-color-color has-primary-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--50)"><?php esc_html_e('Read More', 'blockbooster') ?></a></div>
                                    <!-- /wp:button -->
                                </div>
                                <!-- /wp:buttons -->
                            </div>
                            <!-- /wp:group -->
                        </div>
                    </div>
                    <!-- /wp:cover -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"className":"blockbooster-zoom-in-up"} -->
                <div class="wp-block-column blockbooster-zoom-in-up"><!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[2]) ?>","id":4546,"dimRatio":0,"customOverlayColor":"#39322e","isUserOverlayColor":true,"minHeight":256,"style":{"border":{"radius":"12px"}},"className":"blockbooster-portfolio-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-cover blockbooster-portfolio-box" style="border-radius:12px;min-height:256px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#39322e"></span><img class="wp-block-cover__image-background wp-image-4546" alt="" src="<?php echo esc_url($blockbooster_images[2]) ?>" data-object-fit="cover" />
                        <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"blockbooster-portfolio-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                            <div class="wp-block-group blockbooster-portfolio-content"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","fontSize":"large"} -->
                                <h3 class="wp-block-heading has-text-align-center has-light-color-color has-text-color has-link-color has-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Portfolio Three', 'blockbooster') ?></h3>
                                <!-- /wp:heading -->

                                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                                <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","textColor":"light-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"},"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"className":"is-style-button-hover-secondary-bgcolor"} -->
                                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-light-color-color has-primary-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--50)"><?php esc_html_e('Read More', 'blockbooster') ?></a></div>
                                    <!-- /wp:button -->
                                </div>
                                <!-- /wp:buttons -->
                            </div>
                            <!-- /wp:group -->
                        </div>
                    </div>
                    <!-- /wp:cover -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[4]) ?>","id":4555,"dimRatio":0,"isUserOverlayColor":true,"focalPoint":{"x":0.58,"y":0.41},"minHeight":256,"style":{"border":{"radius":"12px"},"spacing":{"margin":{"top":"28px"}}},"className":"blockbooster-portfolio-box blockbooster-zoom-in-up","layout":{"type":"constrained"}} -->
            <div class="wp-block-cover blockbooster-portfolio-box blockbooster-zoom-in-up" style="border-radius:12px;margin-top:28px;min-height:256px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-4555" alt="" src="<?php echo esc_url($blockbooster_images[3]) ?>" style="object-position:58% 41%" data-object-fit="cover" data-object-position="58% 41%" />
                <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"blockbooster-portfolio-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group blockbooster-portfolio-content"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","fontSize":"large"} -->
                        <h3 class="wp-block-heading has-text-align-center has-light-color-color has-text-color has-link-color has-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Portfolio Four', 'blockbooster') ?></h3>
                        <!-- /wp:heading -->

                        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                        <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","textColor":"light-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"},"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"className":"is-style-button-hover-secondary-bgcolor"} -->
                            <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-light-color-color has-primary-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--50)"><?php esc_html_e('Read More', 'blockbooster') ?></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
            </div>
            <!-- /wp:cover -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->