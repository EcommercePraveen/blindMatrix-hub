<?php
/**
 * View Userslist HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id = isset($_GET['bm_hub_post_id'])?$_GET['bm_hub_post_id']:0;
$object = new BM_Users_List_Object($post_id);
?>
<div class="bm-userslist-view-plugin-settings-wrapper wrap">
	<div class="bm-generate-key-send-mail-table-wrapper">
		<h2><?php esc_html_e('BlindMatrix Plugin Settings','blindmatrix');?></h2>
	 <table class="widefat striped fixed" style="width:90%;">
		<thead>
			<tr>
				<th><?php esc_html_e('User Name','blindmatrix'); ?></th>
				<th><?php esc_html_e('URL','blindmatrix'); ?></th>
				<th><?php esc_html_e('Email Address','blindmatrix'); ?></th>
				<th><?php esc_html_e('IP Address','blindmatrix'); ?></th>
				<th><?php esc_html_e('Status','blindmatrix'); ?></th>
				<th><?php esc_html_e('Plugin Status','blindmatrix'); ?></th>
				<th><?php esc_html_e('Generated Key','blindmatrix'); ?></th>
				<th><?php esc_html_e('Premium Activated Date','blindmatrix'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo is_object($object) && !empty($object->get_user_name()) ? esc_attr($object->get_user_name()):'-';?></td>
				<td><a href="<?php echo is_object($object) && !empty($object->get_url_info()) ? esc_attr($object->get_url_info()):'#';?>" target="_blank"><?php echo is_object($object) && !empty($object->get_url_info()) ? esc_attr($object->get_url_info()):'-';?></a></td>
				<td><?php echo is_object($object) && !empty($object->get_user_email()) ? '<a href="#">'.esc_attr($object->get_user_email()).'</a>':'-';?></td>
				<td><?php echo is_object($object) && !empty($object->get_ip_address()) ? esc_attr($object->get_ip_address()):'-';?></td>
				<td><?php echo is_object($object) && !empty($object->get_post_status()) ? bm_display_post_status($object->get_post_status()):'-';?></td>
				<td><?php echo is_object($object) && !empty($object->get_plugin_status()) ? ucfirst($object->get_plugin_status()):'-';?></td>
				<td>
					<?php if($object->get_activation_key()): ?>
						<a href="#" class="bm-view-activation-key" data-key="<?php echo esc_attr(is_object($object) ? $object->get_activation_key():''); ?>"><?php esc_html_e('View','blindmatrix');?>
					    </a>
					<?php else:
						echo '-';
					endif; ?>
				</td>
				<td><?php echo is_object($object) && !empty($object->get_premium_activated_date()) ? wp_kses_post(bm_get_formatted_date($object->get_premium_activated_date())):'-';?></td>
			</tr>
		</tbody>
	</table>
	  <table class="form-table widefat striped bm-generate-key-send-mail-table" style="width:90%;margin-top:30px;">
		<tbody>
			<tr>
				<th style="padding:25px;"><b><?php esc_html_e('Select URL','blindmatrix');?></b></th>
					<td>
						<select class="bm-server-selection" style="width:50%;">
							<option value="uk" <?php if(is_object($object) && 'uk' == $object->get_url_type()){ ?> selected=selected <?php }?>><?php esc_html_e('blindmatrix.biz','blindmatrix');?></option>
							<option value="us" <?php if(is_object($object) && 'us' == $object->get_url_type()){ ?> selected=selected <?php }?>><?php esc_html_e('blindmatrix.us','blindmatrix');?></option>
							<option value="au" <?php if(is_object($object) && 'au' == $object->get_url_type()){ ?> selected=selected <?php }?>><?php esc_html_e('blindmatrix.net','blindmatrix');?></option>
						</select>
					</td>
			</tr>
			<tr>
				<th style="padding:25px;"><b><?php esc_html_e('API NAME','blindmatrix');?></b></th>
				<td><input type="text" id="bm_hub_api_name" style="width:50%;" value="<?php echo is_object($object) && !empty($object->get_api_name()) ? esc_attr($object->get_api_name()):''; ?>"><p class="bm-error" style="display:none"></p></td>
			</tr>
			<tr>
				<th></th>
				<td>
					<a href="#" class="button button-primary bm-hub-generate-key-send-mail-action" data-post_id="<?php echo esc_attr($post_id);?>"><?php esc_html_e('Generate Key & Send Mail','blindmatrix'); ?></a>
				</td>
			</tr>  
		</tbody>
	  </table>
	</div>
</div>
