<?php
/**
 * View Userslist appointment table HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bm-userslist-view-addon-settings-wrapper wrap">
	<div class="bm-appointment-generate-key-mail-table-wrapper">
	   <h2><?php esc_html_e('Appointment Settings','blindmatrix');?></h2>
	 	<table class="widefat striped fixed" style="width:90%;">
			<thead>
				<tr>
					<th><?php esc_html_e('User Name','blindmatrix'); ?></th>
					<th><?php esc_html_e('URL','blindmatrix'); ?></th>
					<th><?php esc_html_e('Email Address','blindmatrix'); ?></th>
					<th><?php esc_html_e('Appointment Status','blindmatrix'); ?></th>
					<th><?php esc_html_e('Generated Key','blindmatrix'); ?></th>
					<th><?php esc_html_e('Appointment Activated Date','blindmatrix'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo is_object($user_lists_object) && !empty($user_lists_object->get_user_name()) ? esc_attr($user_lists_object->get_user_name()):'-';?></td>
					<td><a href="<?php echo is_object($user_lists_object) && !empty($user_lists_object->get_url_info()) ? esc_attr($user_lists_object->get_url_info()):'#';?>" target="_blank"><?php echo is_object($user_lists_object) && !empty($user_lists_object->get_url_info()) ? esc_attr($user_lists_object->get_url_info()):'-';?></a></td>
					<td><?php echo is_object($user_lists_object) && !empty($user_lists_object->get_user_email()) ? '<a href="#">'.esc_attr($user_lists_object->get_user_email()).'</a>':'-';?></td>
					<td><?php echo is_object($user_lists_object) && !empty($user_lists_object->get_appointment_status()) ? esc_attr($user_lists_object->get_appointment_status()):'Disabled';?></td>
					<td>
						<?php if($user_lists_object->get_appointment_activation_key()): ?>
							<a href="#" class="bm-view-appointment-activation-key" data-key="<?php echo esc_attr(is_object($user_lists_object) ? $user_lists_object->get_appointment_activation_key():''); ?>"><?php esc_html_e('View','blindmatrix');?>
					    	</a>
						<?php else:
							echo '-';
						endif; ?>
					</td>
					<td><?php echo is_object($user_lists_object) && !empty($user_lists_object->get_appointment_date()) ? wp_kses_post(bm_get_formatted_date($user_lists_object->get_appointment_date())):'-';?></td>
				</tr>
			</tbody>
		</table>
	  	<table class="form-table widefat striped bm-appointment-generate-key-mail-table" style="width:90%;margin-top:30px;">
			<tbody>
				<tr>
				<th style="padding:25px;"><b><?php esc_html_e('API NAME','blindmatrix');?></b></th>
				<td><input type="text" id="bm_hub_appoinment_api_name" style="width:50%;" value="<?php echo is_object($user_lists_object) && !empty($user_lists_object->get_appointment_api_name()) ? esc_attr($user_lists_object->get_appointment_api_name()):''; ?>"><p class="bm-appointment-error" style="display:none"></p></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<a href="#" class="button button-primary bm-hub-appointment-key-send-mail-action" data-post_id="<?php echo esc_attr($post_id);?>"><?php esc_html_e('Generate Key & Send Mail','blindmatrix'); ?></a>
					</td>
				</tr>  
			</tbody>
		</table>
   	</div>
</div>		
