<?php

/**
 * Title: Call to Action 3
 * Slug: blockbooster/cta-block-3
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/cta_bg_2.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[0]) ?>","id":3500,"dimRatio":90,"overlayColor":"primary","minHeight":520,"layout":{"type":"constrained","contentSize":"740px"}} -->
    <div class="wp-block-cover" style="min-height:520px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-90 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-3500" alt="" src="<?php echo esc_url($blockbooster_images[0]) ?>" data-object-fit="cover" />
        <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"64px","lineHeight":"1.3"}},"className":"blockbooster-flip-down"} -->
            <h1 class="wp-block-heading has-text-align-center blockbooster-flip-down" style="font-size:64px;font-style:normal;font-weight:600;line-height:1.3"><?php esc_html_e('Let’s Work Together on YourNext Project', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:buttons {"className":"blockbooster-slide-up","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"44px"}}}} -->
            <div class="wp-block-buttons blockbooster-slide-up" style="margin-top:44px"><!-- wp:button {"backgroundColor":"light-color","textColor":"heading-color","style":{"spacing":{"padding":{"left":"var:preset|spacing|70","right":"var:preset|spacing|70","top":"1.3rem","bottom":"1.3rem"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} -->
                <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link has-heading-color-color has-light-color-background-color has-text-color has-background has-link-color wp-element-button" style="border-radius:60px;padding-top:1.3rem;padding-right:var(--wp--preset--spacing--70);padding-bottom:1.3rem;padding-left:var(--wp--preset--spacing--70)"><?php esc_html_e('Let’s Meet Up', 'blockbooster') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
    </div>
    <!-- /wp:cover -->
</div>
<!-- /wp:group -->