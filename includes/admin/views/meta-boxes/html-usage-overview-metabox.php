<?php
/**
 * Usage Overview Metabox HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="bm-usage-count-wrapper">
	<table class="widefat striped">
		<thead>
			<tr>
				<th><b><?php esc_html_e('Total Usage Count','blindmatrix');?></b></th>
				<th><b><?php esc_html_e('Free Trial Usage Count','blindmatrix');?></b></th>				
				<th><b><?php esc_html_e('Premium Usage Count','blindmatrix');?></b></th>
				<th><b><?php esc_html_e('Expired Usage Count','blindmatrix');?></b></th>
				<th><b><?php esc_html_e('Not Activated Usage Count','blindmatrix');?></b></th>
			</tr>	
		</thead>
		<tbody>
			<tr>
				<td><?php echo count(bm_hub_get_user_lists_ids()); ?></td>
				<td><?php echo count(bm_hub_get_user_lists_ids('free_trial')); ?></td>
				<td><?php echo count(bm_hub_get_user_lists_ids('premium')); ?></td>
				<td><?php echo count(bm_hub_get_user_lists_ids('expired')); ?></td>
				<td><?php echo count(bm_hub_get_user_lists_ids('not_activated')); ?></td>
			</tr>
		</tbody>
	</table>
</div>
