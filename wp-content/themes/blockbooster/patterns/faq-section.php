<?php

/**
 * Title: FAQ Section
 * Slug: blockbooster/faq-section
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/icon_faq.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"className":"blockbooster-flip-up","fontSize":"xx-large"} -->
    <h1 class="wp-block-heading has-text-align-center blockbooster-flip-up has-xx-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Frequently Asked Question', 'blockbooster') ?></h1>
    <!-- /wp:heading -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"44px"},"blockGap":{"top":"30px","left":"30px"}}}} -->
    <div class="wp-block-columns" style="margin-top:44px"><!-- wp:column {"style":{"spacing":{"blockGap":"30px"}}} -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|40"},"border":{"radius":"12px"}},"backgroundColor":"light-shade","className":"blockbooster-fade-up","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-fade-up has-light-shade-background-color has-background" style="border-radius:12px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":3770,"width":"32px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3770" style="width:32px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
                    <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('What is Full Site Editing in WordPress?', 'blockbooster') ?></h4>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Full Site Editing in WordPress refers to a feature that allows users to create and edit their website’s entire layout, including the header, footer, and other structural elements, using a visual editor.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|40"},"border":{"radius":"12px"}},"backgroundColor":"light-shade","className":"blockbooster-fade-up","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-fade-up has-light-shade-background-color has-background" style="border-radius:12px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":3770,"width":"32px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3770" style="width:32px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
                    <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('How do I enable Full Site Editing in WordPress?', 'blockbooster') ?></h4>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('To enable Full Site Editing in WordPress, you need to ensure that you are using a compatible WordPress theme that supports Full Site Editing. You will also need to install the latest version of WordPress and enable the Gutenberg editor.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"style":{"spacing":{"blockGap":"30px"}}} -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|40"},"border":{"radius":"12px"}},"backgroundColor":"light-shade","className":"blockbooster-fade-up","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-fade-up has-light-shade-background-color has-background" style="border-radius:12px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":3770,"width":"32px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3770" style="width:32px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
                    <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('What are the benefits of Full Site Editing in WordPress?', 'blockbooster') ?></h4>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Full Site Editing in WordPress allows users to create and edit their website more efficiently and with more control. It enables users to create unique and custom designs without needing to have advanced coding skills.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"},"blockGap":"var:preset|spacing|40"},"border":{"radius":"12px"}},"backgroundColor":"light-shade","className":"blockbooster-fade-up","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-fade-up has-light-shade-background-color has-background" style="border-radius:12px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":3770,"width":"32px","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3770" style="width:32px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"medium"} -->
                    <h4 class="wp-block-heading has-medium-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('What are some popular WordPress themes that support Full Site Editing?', 'blockbooster') ?></h4>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph -->
                <p><?php esc_html_e('Some popular WordPress themes that support Full Site Editing include the FotaWP Theme, ReviveNews Theme, and the Hello Agency Theme. These themes have built-in support for Full Site Editing, allowing users to create custom designs without needing to know how to code.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->