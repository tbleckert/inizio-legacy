<input type="hidden" name="custom_css_meta_box_nonce" value="<?php echo wp_create_nonce('custom_css_meta_box'); ?>" />
<textarea name="_custom-css" id="custom_css_textarea"><?php echo get_post_meta( $_GET['post'], '_custom-css', true ); ?></textarea>