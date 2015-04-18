<?php
if(!current_user_can('manage_options'))
    wp_die(__('You do not have sufficient permissions to manage options for this site.'));

// handle form submit
if(isset($_POST['submit'])) {
    // check if Genius is enabled
    $cg_enabled = isset($_POST['cg_enabled']) ? 1 : 0;
    update_option('cg-enabled', $cg_enabled);

    // if Genius is enabled process the rest of the options
    if($cg_enabled) {
        update_option('cg-genius-content', wp_kses_post($_POST['cg_genius_content']));
        update_option('cg-genius-width', intval($_POST['cg_genius_width']));
        update_option('cg-genius-height', intval($_POST['cg_genius_height']));
        update_option('cg-genius-padding', intval($_POST['cg_genius_padding']));

        update_option('cg-title', sanitize_text_field($_POST['cg_title']));
        update_option('cg-opacity', floatval($_POST['cg_opacity']));

        update_option('cg-overlay-close', sanitize_text_field($_POST['cg_overlay_close']));
        update_option('cg-esc-close', sanitize_text_field($_POST['cg_esc_close']));
        update_option('cg-close', sanitize_text_field($_POST['cg_close']));

        update_option('cg-text-color', sanitize_text_field($_POST['cg_text_color']));
        update_option('cg-background-color', sanitize_text_field($_POST['cg_background_color']));
    }
}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>Comment <b>Genius</b> Settings</h2>

    <?php
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard_tab';
	if(isset($_GET['tab']))
		$active_tab = $_GET['tab'];
	?>
	<h2 class="nav-tab-wrapper">
		<a href="options-general.php?page=cgm&amp;tab=dashboard_tab" class="nav-tab <?php echo $active_tab == 'dashboard_tab' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
		<a href="options-general.php?page=cgm&amp;tab=settings_tab" class="nav-tab <?php echo $active_tab == 'settings_tab' ? 'nav-tab-active' : ''; ?>">General Settings</a>
	</h2>

    <?php if($active_tab == 'dashboard_tab') { ?>
		<div id="poststuff" class="ui-sortable meta-box-sortables">
            <div class="postbox">
                <h3>Dashboard</h3>
                <div class="inside">
                    <p>Thank you for using Comment <b>Genius</b>, a plugin which allows you to aggressively market your site by presenting a lightbox with Facebook Like/Share, Twitter Tweet, Google AdSense Ads and more to your visitors upon leaving a comment.</p>

                    <p>
                        <small>You are using Comment <b>Genius</b> plugin version <strong><?php echo CGM_PLUGIN_VERSION; ?></strong>.</small>
                        <br><small>Dependencies: <a href="http://www.jacklmoore.com/colorbox/" rel="external">jQuery Colorbox</a> 1.5.4.</small>
                    </p>

                    <p>Comment <b>Genius</b> allows the user to create a customizable lightbox popup (width, height, properties), suitable for Facebook Like/Share, Twitter Tweet, Google AdSense Ads and more. The popup is a pure textarea, in order to allow for source code copy/paste and it supports HTML code for custom text content.</p>
                </div>
            </div>
            <div class="postbox">
                <div class="inside">
                    <p>For support, feature requests and bug reporting, please visit the <a href="//getbutterfly.com/" rel="external">official website</a>.</p>
                    <p>&copy;<?php echo date('Y'); ?> <a href="//getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <a href="//getbutterfly.com/forums/" rel="external">Support forums</a> &middot; <a href="//getbutterfly.com/trac/" rel="external">Trac</a> &middot; <small>Code wrangling since 2005</small></p>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if($active_tab == 'settings_tab') { ?>
		<div id="poststuff" class="ui-sortable meta-box-sortables">
            <div class="postbox">
                <h3><b>Genius</b> Settings</h3>
                <div class="inside">
                    <?php if(isset($_POST['submit'])) : ?>
                        <div id="setting-error-settings_updated" class="updated settings-error"> 
                            <p><strong><?php echo _e('Settings saved.') ?></strong></p>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=cgm&amp;tab=settings_tab">
                        <p>
                            <input name="cg_enabled" type="checkbox" id="cg_enabled" <?php echo 1 == get_option('cg-enabled', 0) ? 'checked' : '' ?>>
                            <label for="cg_enabled">Enable Comment <b>Genius</b></label>
                        </p>
                        <p>
                            <input name="cg_genius_padding" type="number" id="cg_genius_padding" value="<?php echo get_option('cg-genius-padding') ?>" min="0" max="1000">
                            <label for="cg_genius_padding"><b>Genius</b> padding (in pixels)</label>
                        </p>
                        <p>
                            <input name="cg_genius_width" type="number" id="cg_genius_width" value="<?php echo get_option('cg-genius-width') ?>" min="0" max="2560">
                            <label for="cg_genius_width"><b>Genius</b> width (in pixels)</label>
                        </p>
                        <p>
                            <input name="cg_genius_height" type="number" id="cg_genius_height" value="<?php echo get_option('cg-genius-height') ?>" min="0" max="2560">
                            <label for="cg_genius_height"><b>Genius</b> height (in pixels)</label>
                            <br><small>Keep ad size for advertisement or a maximum of 600x400 for other content.</small>
                            <br><small><b>Example #1:</b> Use 336x280 for a Google AdSense 336x280 ad unit.</small>
                            <br><small><b>Example #2:</b> Use 500x250 for a Facebook Like/Share button.</small>
                        </p>
                        <p>
                            <input name="cg_opacity" type="number" id="cg_opacity" value="<?php echo get_option('cg-opacity') ?>" min="0" max="1" step="0.1">
                            <label for="cg_opacity"><b>Genius</b> overlay opacity</label>
                        </p>

                        <hr>
                        <p>
                            <select name="cg_overlay_close" id="cg_overlay_close" class="regulat-text">
                                <option value="true" <?php if(get_option('cg-overlay-close') == 'true') echo 'selected'; ?>>Allow click on overlay to close popup</option>
                                <option value="false" <?php if(get_option('cg-overlay-close') == 'false') echo 'selected'; ?>>Disallow click on overlay to close popup</option>
                            </select>
                            <br><small>This option allows closing the <b>Genius</b> from the X button only. Leave unchecked to allow closing on overlay click.</small>
                        </p>
                        <p>
                            <select name="cg_esc_close" id="cg_esc_close" class="regulat-text">
                                <option value="true" <?php if(get_option('cg-esc-close') == 'true') echo 'selected'; ?>>Allow ESC key to close popup</option>
                                <option value="false" <?php if(get_option('cg-esc-close') == 'false') echo 'selected'; ?>>Disallow ESC key to close popup</option>
                            </select>
                            <br><small>This option allows closing the <b>Genius</b> using ESC key.</small>
                        </p>
                        <p>
                            <select name="cg_close" id="cg_close" class="regulat-text">
                                <option value="true" <?php if(get_option('cg-close') == 'true') echo 'selected'; ?>>Show close button</option>
                                <option value="false" <?php if(get_option('cg-close') == 'false') echo 'selected'; ?>>Hide close button</option>
                            </select>
                            <br><small>This option allows hiding the close button. <b>Not recommended</b> unless you want to annoy visitors.</small>
                        </p>

                        <p>
                            <input type="text" name="cg_title" id="cg_title" class="regular-text" value="<?php echo get_option('cg-title'); ?>">
                            <label for="cg_title"><b>Genius</b> title</label>
                            <br><small>Optionally use a title, such as "Thank you for your comment!" or "Thank you for your contribution".</small>
                        </p>
                        <p>
                            <label for="cg_genius_content"><b>Genius</b> content</label>
							<!--<br><textarea rows="10" name="cg_genius_content" id="cg_genius_content" class="large-text"><?php echo get_option('cg-genius-content') ?></textarea>-->
							<?php wp_editor(get_option('cg-genius-content'), 'cg_genius_content', $settings = array('textarea_rows' => '30',)); ?>
                            <br><a id="cg-genius-trigger" class="colorbox-popup-trigger button button-secondary" href="#"><b>Genius</b> Preview</a>
                            <script>
                            jQuery(document).ready(function(){
                                jQuery('#cg-genius-trigger').click(function(){
                                    //jQuery('#popup_content').html(jQuery('#cg_genius_content').val());
                                    jQuery(".colorbox-popup-trigger").colorbox({
                                        //width:          parseInt(jQuery('#cg_genius_width').val()),
                                        //height:         parseInt(jQuery('#cg_genius_height').val()),
                                        innerWidth:     true,
                                        innerHeight:    true,
                                        inline:         true,
                                        href:           '#popup_content',
                                        transition:     'elastic',
                                        title:          '<?php echo get_option('cg-title'); ?>',
                                        overlayClose:   <?php echo get_option('cg-overlay-close'); ?>,
                                        escKey:         <?php echo get_option('cg-esc-close'); ?>,
                                        closeButton:    <?php echo get_option('cg-close'); ?>,
                                    });
                                });
                            });
                            </script>
                            <div style="display: none;"> 
                                <div id="popup_content" style="width: <?php echo get_option('cg-genius-width'); ?>; height: <?php echo get_option('cg-genius-height'); ?>; padding: <?php echo get_option('cg-genius-padding'); ?>px; background-color: <?php echo get_option('cg-background-color'); ?>; color: <?php echo get_option('cg-text-color'); ?>;"><?php echo get_option('cg-genius-content'); ?></div> 
                            </div> 
                        </p>

                        <hr>
                        <p>
                            <label for="cg_text_color"><b>Genius</b> text colour</label>
                            <br><input type="text" name="cg_text_color" class="cg_colorPicker" data-default-color="#000000" value="<?php echo get_option('cg-text-color'); ?>">
                            <br><small>This is the colour of the text inside the <b>Genius</b> lightbox popup</small>
                            <div id="cg_text_color_picker"></div>
                        </p>
                        <p>
                            <label for="cg_background_color"><b>Genius</b> background colour</label>
                            <br><input type="text" name="cg_background_color" class="cg_colorPicker" data-default-color="#ffffff" value="<?php echo get_option('cg-background-color'); ?>">
                            <br><small>This is the background colour of the <b>Genius</b> lightbox popup</small>
                            <div id="cg_background_color_picker"></div>
				        </p>

                        <?php submit_button(); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </form>
</div>
