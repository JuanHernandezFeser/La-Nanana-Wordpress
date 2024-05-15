<?php

/**
 * Title: Contact Us Page
 * Slug: blockbooster/blockbooster-contact-page
 * Categories: blockbooster-contact
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/heading_bg.jpg',
);
?>
<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"100%"}} -->
<main class="wp-block-group has-light-color-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
    <!-- wp:cover {"url":"<?php echo esc_url($blockbooster_images[0]) ?>","id":4270,"dimRatio":80,"overlayColor":"primary","isUserOverlayColor":true,"focalPoint":{"x":1,"y":0.6},"minHeight":360,"contentPosition":"center center","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"className":"is-style-default","layout":{"type":"constrained","contentSize":"640px"}} -->
    <div class="wp-block-cover is-style-default" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-4270" alt="" src="<?php echo esc_url($blockbooster_images[0]) ?>" style="object-position:100% 60%" data-object-fit="cover" data-object-position="100% 60%" />
        <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"light-color","fontSize":"xx-large"} -->
            <h1 class="wp-block-heading has-text-align-center has-light-color-color has-text-color has-link-color has-xx-large-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Contact Us', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-shade"}}}},"textColor":"light-shade","fontSize":"normal"} -->
            <p class="has-text-align-center has-light-shade-color has-text-color has-link-color has-normal-font-size"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->
        </div>
    </div>
    <!-- /wp:cover -->

    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
    <div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"10rem"}}}} -->
        <div class="wp-block-columns"><!-- wp:column {"width":"","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
            <div class="wp-block-column"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"x-large"} -->
                <h3 class="wp-block-heading has-x-large-font-size" style="font-style:normal;font-weight:500"><?php esc_html_e('Get in Touch', 'blockbooster') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"40px"}}}} -->
                <p style="margin-bottom:40px"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud commodo consequat.', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:list {"style":{"typography":{"lineHeight":"1.5"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"textColor":"heading-color","className":"is-style-list-style-no-bullet","fontSize":"normal"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1.5" class="is-style-list-style-no-bullet has-heading-color-color has-text-color has-link-color has-normal-font-size"><!-- wp:list-item -->
                    <li><a href="https://themedemos.cozythemes.com/blockbooster/contact-us/#"><?php esc_html_e('2824 Fleming Street, Montgomery', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:list {"style":{"typography":{"lineHeight":"1.5"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"textColor":"heading-color","className":"is-style-list-style-no-bullet","fontSize":"normal"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1.5" class="is-style-list-style-no-bullet has-heading-color-color has-text-color has-link-color has-normal-font-size"><!-- wp:list-item -->
                    <li><a href="https://themedemos.cozythemes.com/blockbooster/contact-us/#"><?php esc_html_e('+1 (012) 345-6789', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:list {"style":{"typography":{"lineHeight":"1.5"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"textColor":"heading-color","className":"is-style-list-style-no-bullet","fontSize":"normal"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1.5" class="is-style-list-style-no-bullet has-heading-color-color has-text-color has-link-color has-normal-font-size"><!-- wp:list-item -->
                    <li><a href="https://themedemos.cozythemes.com/blockbooster/contact-us/#"><?php esc_html_e('email@example.com', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:list {"style":{"typography":{"lineHeight":"1.5"},"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}},"textColor":"heading-color","className":"is-style-list-style-no-bullet","fontSize":"normal"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1.5" class="is-style-list-style-no-bullet has-heading-color-color has-text-color has-link-color has-normal-font-size"><!-- wp:list-item -->
                    <li><?php esc_html_e('Monday - Friday : 10:00AM - 6:00PM', 'blockbooster') ?></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:social-links {"style":{"spacing":{"margin":{"top":"40px"}}}} -->
                <ul class="wp-block-social-links" style="margin-top:40px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

                    <!-- wp:social-link {"url":"#","service":"x"} /-->

                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->

                    <!-- wp:social-link {"url":"#","service":"instagram"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column -->
            <div class="wp-block-column"><!-- wp:contact-form-7/contact-form-selector {"id":502,"hash":"db9b663","title":"Contact form 1"} -->
                <div class="wp-block-contact-form-7-contact-form-selector">[contact-form-7 id="db9b663" title="Contact form 1"]</div>
                <!-- /wp:contact-form-7/contact-form-selector -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"layout":{"type":"constrained","contentSize":"1180px"}} -->
    <div class="wp-block-group"><!-- wp:group {"layout":{"type":"constrained","contentSize":"1180px"}} -->
        <div class="wp-block-group"><!-- wp:html -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d43364.884789971555!2d-86.66094005382332!3d32.432486611165366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x888e8194b0d481f9%3A0x8e1b511d354285ff!2sMontgomery%2C%20AL%2C%20USA!5e0!3m2!1sen!2snp!4v1712313551708!5m2!1sen!2snp" width="100%" height="360" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <!-- /wp:html -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</main>
<!-- /wp:group -->