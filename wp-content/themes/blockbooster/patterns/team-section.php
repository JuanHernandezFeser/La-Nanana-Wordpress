<?php

/**
 * Title: Team Section
 * Slug: blockbooster/team-section
 * Categories: blockbooster
 */
$blockbooster_url = trailingslashit(get_template_directory_uri());
$blockbooster_images = array(
    $blockbooster_url . 'assets/images/team_1.jpg',
    $blockbooster_url . 'assets/images/team_2.jpg',
    $blockbooster_url . 'assets/images/team_3.jpg'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"backgroundColor":"light-color","layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group has-light-color-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"blockbooster-fade-up","layout":{"type":"constrained","contentSize":"680px"}} -->
    <div class="wp-block-group blockbooster-fade-up"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"10px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
        <div class="wp-block-group" style="margin-bottom:10px"><!-- wp:group {"style":{"spacing":{"padding":{"top":"5px","bottom":"5px","left":"14px","right":"14px"}},"border":{"radius":"50px"}},"backgroundColor":"border-color","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-border-color-background-color has-background" style="border-radius:50px;padding-top:5px;padding-right:14px;padding-bottom:5px;padding-left:14px"><!-- wp:heading {"textAlign":"center","level":5,"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"600"}},"textColor":"primary","fontSize":"x-small"} -->
                <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-link-color has-x-small-font-size" style="font-style:normal;font-weight:600;text-transform:uppercase"><?php esc_html_e('Our Team', 'blockbooster') ?></h5>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"xx-large"} -->
        <h1 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:700"><?php esc_html_e('Introducing the Pioneers Behind Our Progress', 'blockbooster') ?></h1>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"30px"},"margin":{"top":"54px"}}},"className":"blockbooster-slide-up"} -->
    <div class="wp-block-columns blockbooster-slide-up" style="margin-top:54px"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-hover-box"><!-- wp:image {"id":3886,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($blockbooster_images[0]) ?>" alt="" class="wp-image-3886" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"big"} -->
                    <h3 class="wp-block-heading has-big-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('John Doe', 'blockbooster') ?></h3>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('Founder', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-hover-box"><!-- wp:image {"id":3887,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($blockbooster_images[1]) ?>" alt="" class="wp-image-3887" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"big"} -->
                    <h3 class="wp-block-heading has-big-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Liyana Motel', 'blockbooster') ?></h3>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('CTO', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockbooster-hover-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockbooster-hover-box"><!-- wp:image {"id":3889,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($blockbooster_images[2]) ?>" alt="" class="wp-image-3889" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"big"} -->
                    <h3 class="wp-block-heading has-big-font-size" style="font-style:normal;font-weight:600"><?php esc_html_e('Alex Filips', 'blockbooster') ?></h3>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph -->
                    <p><?php esc_html_e('Project Manager', 'blockbooster') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->