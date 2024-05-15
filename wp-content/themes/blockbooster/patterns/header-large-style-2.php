<?php

/**
 * Title: Header Large Style 2
 * Slug: blockbooster/header-large-style-2
 * Categories: blockbooster, header
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/banner_bg_img.jpg',
);
?>
<!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[0]) ?>","id":4377,"dimRatio":40,"overlayColor":"dark-shade","isUserOverlayColor":true,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-cover" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><span aria-hidden="true" class="wp-block-cover__background has-dark-shade-background-color has-background-dim-40 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-4377" alt="" src="<?php echo esc_url($blockbooster_images[0]) ?>" data-object-fit="cover" />
    <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"padding":{"top":"22px","right":"var:preset|spacing|40","bottom":"22px","left":"var:preset|spacing|40"}},"border":{"bottom":{"width":"0px","style":"none"}}},"className":"blockbooster-header","layout":{"type":"constrained","contentSize":"1180px"}} -->
        <div class="wp-block-group blockbooster-header" style="border-bottom-style:none;border-bottom-width:0px;padding-top:22px;padding-right:var(--wp--preset--spacing--40);padding-bottom:22px;padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
            <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:site-logo {"width":32,"shouldSyncIcon":false} /-->

                    <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"none","letterSpacing":"0px","fontSize":"28px"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}},"spacing":{"margin":{"top":"5px"}}}} /-->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"20px"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group has-light-color-color has-text-color has-link-color"><!-- wp:navigation {"textColor":"light-color","overlayBackgroundColor":"secondary-bg","overlayTextColor":"heading-color","className":"blockbooster-navigation","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"textTransform":"none","fontStyle":"normal","fontWeight":"500","lineHeight":"2"},"spacing":{"blockGap":"32px"}},"fontSize":"normal"} -->
                    <!-- wp:page-list /-->
                    <!-- /wp:navigation -->

                    <!-- wp:buttons -->
                    <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"light-color","textColor":"heading-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}},"border":{"radius":"60px"},"spacing":{"padding":{"left":"var:preset|spacing|50","right":"var:preset|spacing|50","top":"13px","bottom":"13px"}},"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"is-style-button-hover-secondary-bgcolor","fontSize":"normal"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor has-normal-font-size" style="font-style:normal;font-weight:500"><a class="wp-block-button__link has-heading-color-color has-light-color-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:13px;padding-right:var(--wp--preset--spacing--50);padding-bottom:13px;padding-left:var(--wp--preset--spacing--50)"><?php esc_html_e('Sign Up Free', 'blockbooster') ?></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:cover {"dimRatio":0,"minHeight":640,"isDark":false,"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"5rem","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"980px"}} -->
        <div class="wp-block-cover is-light" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:5rem;padding-left:var(--wp--preset--spacing--40);min-height:640px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
            <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"84px","lineHeight":"1.3"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","className":"blockbooster-flip-up"} -->
                <h1 class="wp-block-heading has-text-align-center blockbooster-flip-up has-light-color-color has-text-color has-link-color" style="font-size:84px;font-style:normal;font-weight:700;line-height:1.3"><?php esc_html_e('Evolve &amp; Grow With Blockbooster', 'blockbooster') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:group {"layout":{"type":"constrained","contentSize":"640px"}} -->
                <div class="wp-block-group"><!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","className":"blockbooster-fade-up"} -->
                    <p class="has-text-align-center blockbooster-fade-up has-light-color-color has-text-color has-link-color"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:buttons {"className":"blockbooster-slide-up","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"32px"}}}} -->
                <div class="wp-block-buttons blockbooster-slide-up" style="margin-top:32px"><!-- wp:button {"textColor":"light-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"1.2rem","bottom":"1.2rem"}},"border":{"radius":"50px"},"typography":{"fontSize":"18px"}},"className":"is-style-button-hover-secondary-bgcolor"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-light-color-color has-text-color has-link-color wp-element-button" style="border-radius:50px;padding-top:1.2rem;padding-right:var(--wp--preset--spacing--60);padding-bottom:1.2rem;padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Get Started Today', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
        </div>
        <!-- /wp:cover -->
    </div>
</div>
<!-- /wp:cover -->