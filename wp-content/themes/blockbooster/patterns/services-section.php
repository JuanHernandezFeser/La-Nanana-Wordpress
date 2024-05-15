<?php

/**
 * Title: Service Section 2
 * Slug: blockbooster/services-section
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/icon_1.png',
    $blockbooster_url . 'assets/images/icon_2.png',
    $blockbooster_url . 'assets/images/icon_3.png',
    $blockbooster_url . 'assets/images/icon_4.png',
    $blockbooster_url . 'assets/images/icon_5.png',
    $blockbooster_url . 'assets/images/icon_6.png'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"light-shade","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-shade-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"640px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"className":"blockbooster-flip-up","fontSize":"xx-large"} -->
        <h1 class="wp-block-heading has-text-align-center blockbooster-flip-up has-xx-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Our Services', 'blockbooster') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","className":"blockbooster-fade-up"} -->
        <p class="has-text-align-center blockbooster-fade-up"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"48px"}}}} -->
    <div class="wp-block-columns" style="margin-top:48px"><!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"10px","width":"1px"}},"borderColor":"border-color","backgroundColor":"light-color","className":"is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-blockbooster-boxshadow blockbooster-hover-box has-border-color has-border-color-border-color has-light-color-background-color has-background" style="border-width:1px;border-radius:10px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:image {"id":3780,"width":"67px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3780" style="border-radius:60px;width:67px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h3 class="wp-block-heading has-big-font-size" style="margin-top:30px;font-style:normal;font-weight:600"><?php esc_html_e('Business Strategy', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Our team specializes in crafting compelling brand identities that resonate with your audience.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"backgroundColor":"transparent","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"is-style-button-hover-secondary-color","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color has-medium-font-size"><a class="wp-block-button__link has-foreground-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"10px","width":"1px"}},"borderColor":"border-color","backgroundColor":"light-color","className":"is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-blockbooster-boxshadow blockbooster-hover-box has-border-color has-border-color-border-color has-light-color-background-color has-background" style="border-width:1px;border-radius:10px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:image {"id":3780,"width":"67px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[1]) ?>" alt="" class="wp-image-3780" style="border-radius:60px;width:67px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h3 class="wp-block-heading has-big-font-size" style="margin-top:30px;font-style:normal;font-weight:600"><?php esc_html_e('eCommerce', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Our team specializes in crafting compelling brand identities that resonate with your audience.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"backgroundColor":"transparent","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"is-style-button-hover-secondary-color","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color has-medium-font-size"><a class="wp-block-button__link has-foreground-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"10px","width":"1px"}},"borderColor":"border-color","backgroundColor":"light-color","className":"is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-blockbooster-boxshadow blockbooster-hover-box has-border-color has-border-color-border-color has-light-color-background-color has-background" style="border-width:1px;border-radius:10px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:image {"id":3780,"width":"67px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-3780" style="border-radius:60px;width:67px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h3 class="wp-block-heading has-big-font-size" style="margin-top:30px;font-style:normal;font-weight:600"><?php esc_html_e('SEO', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Our team specializes in crafting compelling brand identities that resonate with your audience.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"backgroundColor":"transparent","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"is-style-button-hover-secondary-color","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color has-medium-font-size"><a class="wp-block-button__link has-foreground-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"10px","width":"1px"}},"borderColor":"border-color","backgroundColor":"light-color","className":"is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-blockbooster-boxshadow blockbooster-hover-box has-border-color has-border-color-border-color has-light-color-background-color has-background" style="border-width:1px;border-radius:10px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:image {"id":3780,"width":"67px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[3]) ?>" alt="" class="wp-image-3780" style="border-radius:60px;width:67px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h3 class="wp-block-heading has-big-font-size" style="margin-top:30px;font-style:normal;font-weight:600"><?php esc_html_e('Web Application', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Our team specializes in crafting compelling brand identities that resonate with your audience.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"backgroundColor":"transparent","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"is-style-button-hover-secondary-color","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color has-medium-font-size"><a class="wp-block-button__link has-foreground-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"10px","width":"1px"}},"borderColor":"border-color","backgroundColor":"light-color","className":"is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-blockbooster-boxshadow blockbooster-hover-box has-border-color has-border-color-border-color has-light-color-background-color has-background" style="border-width:1px;border-radius:10px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:image {"id":3780,"width":"67px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[4]) ?>" alt="" class="wp-image-3780" style="border-radius:60px;width:67px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h3 class="wp-block-heading has-big-font-size" style="margin-top:30px;font-style:normal;font-weight:600"><?php esc_html_e('Digital Marketing', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Our team specializes in crafting compelling brand identities that resonate with your audience.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"backgroundColor":"transparent","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"is-style-button-hover-secondary-color","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color has-medium-font-size"><a class="wp-block-button__link has-foreground-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|30"},"border":{"radius":"10px","width":"1px"}},"borderColor":"border-color","backgroundColor":"light-color","className":"is-style-blockbooster-boxshadow blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group is-style-blockbooster-boxshadow blockbooster-hover-box has-border-color has-border-color-border-color has-light-color-background-color has-background" style="border-width:1px;border-radius:10px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:image {"id":3780,"width":"67px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($blockbooster_images[5]) ?>" alt="" class="wp-image-3780" style="border-radius:60px;width:67px" /></figure>
                <!-- /wp:image -->

                <!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"30px"}}},"fontSize":"big"} -->
                <h3 class="wp-block-heading has-big-font-size" style="margin-top:30px;font-style:normal;font-weight:600"><?php esc_html_e('Digital Marketing', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Our team specializes in crafting compelling brand identities that resonate with your audience.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"backgroundColor":"transparent","textColor":"foreground","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}}},"className":"is-style-button-hover-secondary-color","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-secondary-color has-medium-font-size"><a class="wp-block-button__link has-foreground-color has-transparent-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'blockbooster') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->