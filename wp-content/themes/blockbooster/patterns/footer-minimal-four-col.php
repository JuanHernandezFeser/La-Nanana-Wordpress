<?php

/**
 * Title: Footer minimal layout with col 4
 * Slug: blockbooster/footer-minimal-four-col
 * Categories: blockbooster, footer
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/payment_cards.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","right":"0","left":"0","bottom":"30px"}},"border":{"width":"0px","style":"none"}},"backgroundColor":"primary","className":"blockbooster-footer","layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group blockbooster-footer has-primary-background-color has-background" style="border-style:none;border-width:0px;padding-top:20px;padding-right:0;padding-bottom:30px;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
    <div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"50px"},"margin":{"top":"40px"}}}} -->
        <div class="wp-block-columns" style="margin-top:40px"><!-- wp:column {"className":"blockbooster-footer-list"} -->
            <div class="wp-block-column blockbooster-footer-list"><!-- wp:list {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"lineHeight":"2.5","textTransform":"none"},"elements":{"link":{"color":{"text":"var:preset|color|light-shade"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"className":"is-style-hide-bullet-list-link-hover-style-white is-style-list-style-no-bullet","fontSize":"small"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:2.5;text-transform:none" class="is-style-hide-bullet-list-link-hover-style-white is-style-list-style-no-bullet has-link-color has-small-font-size"><!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('Digital Marketing', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('Brand Building', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('SEO', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('Social Media Marketing', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('Content Writing', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"className":"blockbooster-footer-list"} -->
            <div class="wp-block-column blockbooster-footer-list"><!-- wp:list {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"lineHeight":"2.5","textTransform":"none"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"className":"is-style-hide-bullet-list-link-hover-style-white is-style-list-style-no-bullet","fontSize":"small"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:2.5;text-transform:none" class="is-style-hide-bullet-list-link-hover-style-white is-style-list-style-no-bullet has-link-color has-small-font-size"><!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('About Us', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('Terms and Conditions', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->

                    <!-- wp:list-item -->
                    <li><a href="#"><?php esc_html_e('Contact Us', 'blockbooster') ?></a></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}},"typography":{"lineHeight":"1.5","textTransform":"none"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"textColor":"light-color","className":"is-style-hide-bullet-list-link-hover-style-white is-style-list-style-no-bullet","fontSize":"small"} -->
                <ul style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;line-height:1.5;text-transform:none" class="is-style-hide-bullet-list-link-hover-style-white is-style-list-style-no-bullet has-light-color-color has-text-color has-link-color has-small-font-size"><!-- wp:list-item {"fontSize":"small"} -->
                    <li class="has-small-font-size"><?php esc_html_e('Monday - Friday&nbsp; - 8:00 a.m. - 5:00 p.m.', 'blockbooster') ?></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"width":"","className":"blockbooster-footer-list"} -->
            <div class="wp-block-column blockbooster-footer-list"><!-- wp:social-links {"iconColor":"primary","iconColorValue":"#3767FF","iconBackgroundColor":"light-color","iconBackgroundColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"top":"0","left":"var:preset|spacing|30"},"margin":{"bottom":"0","top":"10px"}}}} -->
                <ul class="wp-block-social-links has-icon-color has-icon-background-color" style="margin-top:10px;margin-bottom:0"><!-- wp:social-link {"url":"#","service":"instagram"} /-->

                    <!-- wp:social-link {"url":"#","service":"facebook"} /-->

                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->

                    <!-- wp:social-link {"url":"#","service":"x"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"width":"","style":{"spacing":{"blockGap":"0"}},"className":"blockbooster-footer-list"} -->
            <div class="wp-block-column blockbooster-footer-list"><!-- wp:paragraph {"align":"left","style":{"elements":{"link":{"color":{"text":"var:preset|color|border-color"}}},"typography":{"fontSize":"14px"},"spacing":{"margin":{"top":"5px"}}},"textColor":"border-color"} -->
                <p class="has-text-align-left has-border-color-color has-text-color has-link-color" style="margin-top:5px;font-size:14px"><?php esc_html_e('Stay Ahead of the Curve! Subscribe for Exclusive Offers!', 'blockbooster') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:contact-form-7/contact-form-selector {"id":1127,"hash":"de17a25","title":"Newsletter Form","className":"blockbooster-newsletter minimal-footer-layout"} -->
                <div class="wp-block-contact-form-7-contact-form-selector blockbooster-newsletter minimal-footer-layout">[contact-form-7 id="de17a25" title="Newsletter Form"]</div>
                <!-- /wp:contact-form-7/contact-form-selector -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->

        <!-- wp:group {"style":{"spacing":{"margin":{"top":"60px"},"padding":{"top":"30px","bottom":"10px"},"blockGap":"24px"},"border":{"top":{"width":"0px","style":"none"},"right":[],"bottom":[],"left":[]}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
        <div class="wp-block-group" style="border-top-style:none;border-top-width:0px;margin-top:60px;padding-top:30px;padding-bottom:10px"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}},"typography":{"lineHeight":"1.5"}},"textColor":"light-color","fontSize":"normal"} -->
            <p class="has-light-color-color has-text-color has-link-color has-normal-font-size" style="line-height:1.5"><?php esc_html_e('Proudly powered by WordPress | Blockbooster by CozyThemes.', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"tertiary","textColor":"foregound-alt","style":{"border":{"radius":"50%"}},"className":"blockbooster-scrollto-top is-style-button-hover-secondary-bgcolor"} -->
    <div class="wp-block-button blockbooster-scrollto-top is-style-button-hover-secondary-bgcolor"><a class="wp-block-button__link has-foregound-alt-color has-tertiary-background-color has-text-color has-background wp-element-button" style="border-radius:50%"><?php esc_html_e('Scroll to Top', 'blockbooster') ?></a></div>
    <!-- /wp:button -->
</div>
<!-- /wp:buttons -->