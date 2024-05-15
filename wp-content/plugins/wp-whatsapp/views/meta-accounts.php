<?php 
use NTA_WhatsApp\Helper;
?>
<table class="form-table" id="nta-custom-wc-button-settings">
    <tbody>
        <tr>
            <th scope="row">
                <label for="number">
                    <?php echo __('Account Number or group chat URL', 'wp-whatsapp') ?>
                </label>
            </th>
            <td>
                <p>
                    <input type="text" class="widefat" id="number" name="number" value="<?php echo esc_attr($meta['number']) ?>" autocomplete="off">
                </p>
                <p class="description">
                    <?php echo __('Refer to <a href="https://faq.whatsapp.com/en/general/21016748" target="_blank">https://faq.whatsapp.com/en/general/21016748</a> for a detailed explanation.', 'wp-whatsapp') ?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="wa-title"><?php echo __('Title', 'wp-whatsapp') ?></label>
            </th>
            <td>
                <input type="text" id="wa-title" name="title" value="<?php echo esc_attr($meta['title']) ?>" class="widefat" autocomplete="off">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="predefinedText"><?php echo __('Predefined Text', 'wp-whatsapp') ?></label>
            </th>
            <td>
                <textarea name="predefinedText" id="predefinedText" rows="3" class="widefat"><?php echo esc_textarea($meta['predefinedText']) ?></textarea>
                <p class="description">
                    <?php echo __('Use [njwa_page_title] and [njwa_page_url] shortcodes to output the page\'s title and URL respectively.', 'wp-whatsapp') ?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="isAlwaysAvailable"><?php echo __('Always available online', 'wp-whatsapp') ?></label>
            </th>
            <td>
                <div class="nta-wa-switch-control">
                    <input type="checkbox" id="nta-wa-switch" name="isAlwaysAvailable" <?php checked($meta['isAlwaysAvailable'], 'ON') ?>>
                    <label for="nta-wa-switch" class="green"></label>
                </div>
            </td>
        </tr>

        <tr class="nta-btncustom-offline <?php echo ($meta['isAlwaysAvailable'] === 'ON' ? 'hidden' : '') ?>">
            <th scope="row">
                <label><?php echo __('Custom Availability (PRO)', 'wp-whatsapp') ?></label>
            </th>
            <td>
                <?php
                $haveCustom = array_search('ON', array_column($meta['daysOfWeekWorking'], 'isWorkingOnDay')); 
                if ($haveCustom !== false): ?> 
                <p class="notice notice-warning" style="border-top: 0; border-bottom: 0; border-right: 0; box-shadow: none">
                    <?php echo sprintf(
                        __('This feature is now only available in Pro version.<br>If you still want to use it, please <a href="%s">Upgrade PRO</a> or turn back to <a href="%s">Version 2.6</a><br>Need help? <a href="%s" style="color: #444">Contact us</a>', 'wp-whatsapp'),
                        'https://1.envato.market/Upgrade-WhatsApp-Pro',
                        'https://downloads.wordpress.org/plugin/wp-whatsapp.2.6.zip',
                        'https://ninjateam.org/support/'
                    ) 
                    ?>
                </p>
                <?php endif; ?>
                <table class="form-table time-available">
                    <tbody>
                        <?php foreach ($daysOfWeek as $dayKey) : ?>
                            <?php foreach ($meta['daysOfWeekWorking'][$dayKey]['workHours'] as $i => $workHour): ?>
                            <tr class="working-<?php echo esc_attr($dayKey) ?>">
                                <td width="150">
                                    <?php if($i === 0): ?>
                                        <input class="njt-wa-pro" type="checkbox" id="daysOfWeekWorking[<?php echo esc_attr($dayKey) ?>][isWorkingOnDay]" name="daysOfWeekWorking[<?php echo ($dayKey) ?>][isWorkingOnDay]" <?php checked($meta["daysOfWeekWorking"][$dayKey]['isWorkingOnDay' ], 'ON') ?>>
                                        <label for="daysOfWeekWorking[<?php echo ($dayKey) ?>][isWorkingOnDay]"><?php echo __(ucfirst($dayKey), 'wp-whatsapp') ?> </label>
                                    <?php endif ?>
                                </td>
                                <td width="100">
                                    <select class="njt-wa-pro" name="daysOfWeekWorking[<?php echo esc_attr($dayKey) ?>][workHours][<?php echo $i ?>][startTime]" class="start-time"><?php echo Helper::buildTimeSelector($workHour['startTime']); ?></select>
                                </td>
                                <td width="100">
                                    <select class="njt-wa-pro" name="daysOfWeekWorking[<?php echo esc_attr($dayKey) ?>][workHours][<?php echo $i ?>][endTime]" class="end-time"><?php echo Helper::buildTimeSelector($workHour['endTime']); ?></select>
                                </td>
                                <?php if ($i === 0): ?>
                                    <td><a href="javascript:;" class="add-custom-time">Add</a></td>
                                <?php endif;?>
                                <?php if ($i !== 0): ?>
                                    <td><a style="color: #a00" href="javascript:;" class="remove-custom-time">Remove</a></td>
                                <?php endif; ?>
                                <?php if ($dayKey === 'sunday' && $i === 0) : ?>
                                    <td>
                                        <a href="javascript:;" type="button" class="button" id="btn-apply-time"><?php echo __('Apply to All Days', 'wp-whatsapp') ?></button>
                                    </td>
                                <?php endif ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr class="nta-btncustom-offline <?php echo ($meta['isAlwaysAvailable'] === 'ON' ? 'hidden' : '') ?>">
            <th scope="row"><label for="willBeBackText"><?php echo __('Description text when offline (PRO)', 'wp-whatsapp') ?></label></th>
            <td>
                <input type="text" id="willBeBackText" name="willBeBackText" value="<?php echo esc_attr($meta['willBeBackText']) ?>" class="widefat njt-wa-pro" autocomplete="off" readonly>
                <p class="description"><?php echo __('You can use shortcode [njwa_time_work] to display the exact time this account is back to work on a working day.', 'wp-whatsapp') ?></p>
                <input type="text" id="dayOffsText" name="dayOffsText" value="<?php echo esc_attr($meta['dayOffsText']) ?>" class="widefat njt-wa-pro" autocomplete="off" readonly>
                <p class="description"><?php echo __('You can use this text to display on days this account does not work.', 'wp-whatsapp') ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
