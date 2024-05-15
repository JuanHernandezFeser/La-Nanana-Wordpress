<?php

/**
 * Title: Header Default
 * Slug: blockbooster/header-default
 * Categories: blockbooster, header
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"22px","right":"var:preset|spacing|40","bottom":"22px","left":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"},"top":[],"right":[],"left":[]}},"className":"blockbooster-header","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group blockbooster-header" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;padding-top:22px;padding-right:var(--wp--preset--spacing--40);padding-bottom:22px;padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group"><!-- wp:site-logo {"width":32,"shouldSyncIcon":false} /-->

            <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"none","letterSpacing":"0px","fontSize":"28px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|secondary"}}}},"spacing":{"margin":{"top":"5px"}}}} /-->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"style":{"spacing":{"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|light-color"}}}},"textColor":"light-color","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group has-light-color-color has-text-color has-link-color">
            <!-- wp:navigation {"textColor":"heading-color","overlayBackgroundColor":"secondary-bg","overlayTextColor":"heading-color","className":"blockbooster-navigation","layout":{"type":"flex","justifyContent":"center"},"style":{"typography":{"textTransform":"none","fontStyle":"normal","fontWeight":"500","lineHeight":"2"},"spacing":{"blockGap":"32px"}},"fontSize":"normal"} -->
            <!-- wp:page-list /-->
            <!-- /wp:navigation -->

            <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search products…","width":100,"widthUnit":"%","buttonText":"Search","buttonPosition":"button-only","buttonUseIcon":true,"query":{"post_type":"product"},"isSearchFieldHidden":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}},"layout":{"selfStretch":"fill","flexSize":null}},"backgroundColor":"transparent","textColor":"heading-color","className":"blockbooster-nav-search","fontSize":"normal"} /-->

            <!-- wp:woocommerce/mini-cart {"priceColor":{"slug":"heading-color","color":"#262625","name":"Heading Color","class":"has-heading-color-icon-color"},"iconColor":{"slug":"heading-color","color":"#262625","name":"Heading Color","class":"has-heading-color-icon-color"},"productCountColor":{"slug":"primary","color":"#3767FF","name":"Primary","class":"has-primary-product-count-color"}} /-->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->