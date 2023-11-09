<?php
/**
 * Premium Request Email Template HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(isset($_REQUEST['bm_hub_premium_request_email_subject'])):  ?>	
	<div id="message" class="updated inline"><p><strong><?php esc_html_e('Your settings have been saved.','blindmatrix');?></strong></p></div>
	<?php endif; ?>	
	<div class="bm-premium-request-mail-template-wrapper">				
		<h2><?php esc_html_e('Premium Request Email Template','blindmatrix'); ?></h2>
		<table class="form-table widefat bm-premium-request-mail-table" style="width:90%;">
			<tbody>
				<tr>
					<th style="padding:25px;"><b><?php esc_html_e('Recipient(s)','blindmatrix');?></b></th>
					<td><input type="text" name="bm_hub_premium_request_recipients" placeholder="Enter recipients (comma separated) for this email." value="<?php echo get_option('bm_hub_premium_request_recipients','');?>" style="width:100%;"></td>
				</tr>
				<tr>
					<th style="padding:25px;"><b><?php esc_html_e('Email Subject','blindmatrix');?></b></th>
					<td>
						<input type="text" name="bm_hub_premium_request_email_subject" style="width:100%;" value="<?php echo wp_kses_post($email_subject); ?>">
					</td>
				</tr>
				<tr>
					<th style="padding:25px;"><b><?php esc_html_e('Email Message','blindmatrix');?></b></th>
					<td><?php 
						echo wp_editor( $email_message, 'bm_hub_premium_request_email_message');
						?>
					</td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit" class="button button-primary" 
							   name="bm_hub_save_email_template"
								value="<?php esc_html_e('Save changes','blindmatrix');?>">
							<a href="<?php echo esc_url(admin_url( '?preview_premium_request_mail=true' ));?>" class="button button-primary" target="_blank" style="float:right;"><?php esc_html_e('Click to Preview','blindmatrix');?></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
