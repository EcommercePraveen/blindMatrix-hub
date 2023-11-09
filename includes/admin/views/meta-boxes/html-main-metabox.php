<?php
/**
 * Promotional Settings HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap">
	<form method="post">
		<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
	<div id="poststuff" style="margin-top:30px;">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="postbox-container-1" class="postbox-container">
					<?php do_meta_boxes( $screen_id, 'side', null ); ?>
				</div>
			<div id="postbox-container-2" class="postbox-container">
					<?php do_meta_boxes( $screen_id, 'normal', null ); ?>
					<?php do_meta_boxes( $screen_id, 'advanced', null ); ?>
			</div>
	</div>
	<br class="clear">
	</div>
	</form>
</div>

