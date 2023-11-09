<?php
/**
 * CSV Emails List HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="widefat striped">
	<thead>
		<tr>
			<th><b><?php esc_html_e('S.No','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Emails','blindmatrix'); ?></b></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($emails as $email): ?>
		<tr>
			<td><?php echo esc_html($count++); ?></td>
			<td><?php echo esc_html($email); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
