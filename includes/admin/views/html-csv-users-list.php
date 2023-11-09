<?php
/**
 * CSV Users List HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table class="widefat striped fixed">
	<thead>
		<tr>
			<th><b><?php esc_html_e('Post ID','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Url Name','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('User ID','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('User Name','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Email','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('IP Address','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Trial Activation Date','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Premium Activation Date','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Plugin Expired Date','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Status Name','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Plugin Status','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Activation Key','blindmatrix'); ?></b></th>
			<th><b><?php esc_html_e('Date Created','blindmatrix'); ?></b></th>
		</tr>
	</thead>
		<tbody>
			<?php foreach($userslist_data as $value): ?>
					<tr>
						<td><?php echo esc_html(isset($value[0]) && !empty($value[0]) ? $value[0]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[1]) && !empty($value[1]) ? $value[1]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[2]) && !empty($value[2]) ? $value[2]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[3]) && !empty($value[3]) ? $value[3]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[4]) && !empty($value[4]) ? $value[4]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[5]) && !empty($value[5]) ? $value[5]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[6]) && !empty($value[6]) ? $value[6]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[7]) && !empty($value[7]) ? $value[7]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[8]) && !empty($value[8]) ? $value[8]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[9]) && !empty($value[9]) ? $value[9]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[10]) && !empty($value[10]) ? $value[10]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[11]) && !empty($value[11]) ? $value[11]:'-'); ?></td>
						<td><?php echo esc_html(isset($value[12]) && !empty($value[12]) ? $value[12]:'-'); ?></td>
					</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
