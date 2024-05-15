<?php

/**
 * Title: Call to Action
 * Slug: blockbooster/call-to-action
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/cta_bg.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[0]) ?>","id":3667,"dimRatio":0,"minHeight":520,"isDark":false,"layout":{"type":"constrained","contentSize":"740px"}} -->
    <div class="wp-block-cover is-light" style="min-height:520px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-3667" alt="" src="<?php echo esc_url($blockbooster_images[0]) ?>" data-object-fit="cover" />
        <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"64px","lineHeight":"1.3"}},"className":"blockbooster-flip-down"} -->
            <h1 class="wp-block-heading has-text-align-center blockbooster-flip-down" style="font-size:64px;font-style:normal;font-weight:600;line-height:1.3"><?php esc_html_e('Let’s Work Together on YourNext Project', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","className":"blockbooster-fade-up"} -->
            <p class="has-text-align-center blockbooster-fade-up"><?php esc_html_e('Frustrated by a slow website? We’ll help you tame those speed demons so you can keep visitors coming back for more!', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"className":"blockbooster-slide-up","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"44px"}}}} -->
            <div class="wp-block-buttons blockbooster-slide-up" style="margin-top:44px"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('Let’s Meet Up', 'blockbooster') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
    </div>
    <!-- /wp:cover -->
</div>
<!-- /wp:group -->