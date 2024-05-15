<?php

/**
 * Title: Latest News and Articles layout 2
 * Slug: blockbooster/latest-post-2
 * Categories: blockbooster
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-color-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:columns {"style":{"spacing":{"margin":{"bottom":"59px"},"blockGap":{"left":"60px"}}}} -->
    <div class="wp-block-columns" style="margin-bottom:59px"><!-- wp:column {"width":"33%","style":{"spacing":{"padding":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|30"}},"className":"blockbooster-fade-up"} -->
        <div class="wp-block-column blockbooster-fade-up" style="padding-top:0;padding-bottom:0;flex-basis:33%"><!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"xx-large"} -->
            <h1 class="wp-block-heading has-text-align-left has-xx-large-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:600"><?php esc_html_e('Latest News &amp; Articles', 'blockbooster') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"left"} -->
            <p class="has-text-align-left"><?php esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'blockbooster') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"60px"},"typography":{"fontSize":"18px"}}} -->
                <div class="wp-block-button has-custom-font-size" style="font-size:18px"><a class="wp-block-button__link wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php esc_html_e('View All Articles', 'blockbooster') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"67%","style":{"spacing":{"padding":{"top":"16px"}}},"className":"blockbooster-slide-up"} -->
        <div class="wp-block-column blockbooster-slide-up" style="padding-top:16px;flex-basis:67%"><!-- wp:query {"queryId":22,"query":{"perPage":"2","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
            <div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"30px"}},"layout":{"type":"grid","columnCount":2}} -->
                <!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"minHeight":280,"contentPosition":"bottom left","style":{"border":{"radius":"12px"}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-cover has-custom-content-position is-position-bottom-left" style="border-radius:12px;min-height:280px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
                    <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"padding":{"top":"14px","bottom":"14px","left":"22px","right":"22px"},"blockGap":"3px"},"border":{"radius":"7px"}},"backgroundColor":"primary","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                        <div class="wp-block-group has-primary-background-color has-background" style="border-radius:7px;padding-top:14px;padding-right:22px;padding-bottom:14px;padding-left:22px"><!-- wp:post-date {"format":"M","style":{"typography":{"textTransform":"none","lineHeight":"1"}}} /-->

                            <!-- wp:post-date {"format":"j","style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1"},"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"0","left":"0"}}},"fontSize":"large"} /-->
                        </div>
                        <!-- /wp:group -->
                    </div>
                </div>
                <!-- /wp:cover -->

                <!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"28px","lineHeight":"1.3"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}},"spacing":{"margin":{"top":"24px"}}}} /-->

                <!-- wp:post-excerpt {"excerptLength":27} /-->
                <!-- /wp:post-template -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->