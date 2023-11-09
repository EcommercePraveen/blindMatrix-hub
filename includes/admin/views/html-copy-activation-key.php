<?php
/**
 * Copy Activation Key HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="bm-copy-activation-key-wrapper" style="border: 2px dotted;background:#ededed;text-align: center;padding: 10px;margin-top:20px;">
		<a href="#" class="bm-activation-key" data-key="<?php echo wp_kses_post($activation_key); ?>" style="text-decoration: none;" title="<?php esc_html_e("Click to Copy",'blindmatrix'); ?>"><?php echo wp_kses_post($activation_key); ?>		 </a>
</div>
