<?php

/**
 * Title: Footer Default
 * Slug: blockbooster/footer-default
 * Categories: blockbooster, footer
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-color-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)"><!-- wp:social-links {"iconBackgroundColor":"primary","iconBackgroundColorValue":"#3767FF","style":{"spacing":{"margin":{"top":"32px","bottom":"54px"},"blockGap":{"left":"var:preset|spacing|30"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
    <ul class="wp-block-social-links has-icon-background-color" style="margin-top:32px;margin-bottom:54px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

        <!-- wp:social-link {"url":"#","service":"x"} /-->

        <!-- wp:social-link {"url":"#","service":"instagram"} /-->

        <!-- wp:social-link {"url":"#","service":"linkedin"} /-->

        <!-- wp:social-link {"url":"#","service":"tiktok"} /-->

        <!-- wp:social-link {"url":"#","service":"spotify"} /-->
    </ul>
    <!-- /wp:social-links -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|border-color","width":"1px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"textColor":"heading-color"} -->
        <p class="has-heading-color-color has-text-color has-link-color"><?php esc_html_e('Proudly powered by WordPress | Blockbooster by ', 'blockbooster') ?><a href="https://cozythemes.com/" target="_blank" rel="noreferrer noopener"><?php esc_html_e('CozyThemes', 'blockbooster') ?></a>.</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}}},"textColor":"heading-color"} -->
        <p class="has-heading-color-color has-text-color has-link-color"><a href="#"><?php esc_html_e('Terms &amp; Conditions', 'blockbooster') ?></a></p>
        <!-- /wp:paragraph -->
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