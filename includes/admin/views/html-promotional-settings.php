<?php
/**
 * Promotional Settings HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$stored_email_ids = !empty(get_option('bm_stored_csv_email_ids')) ? get_option('bm_stored_csv_email_ids'):array();
?>
<div class="bm-promotional-mail-settings-wrapper">
			<form method="post">	
				<h2><?php esc_html_e('Promotional Email Settings','blindmatrix'); ?>
				<?php if(isset($_GET['import'])): ?>
					<a href="<?php echo admin_url( 'admin.php?page=bm_email_template&tab=promotional&section=settings'); ?>" class="button" style="margin-left:10px;"><?php esc_html_e('Back','blindmatrix');?></a>
				<?php endif; ?>
				</h2>
				<?php if(isset($_REQUEST['bm-upload-action']) && class_exists('BM_Admin') ): 
					$return        = self::handle_upload();
					$error_message = isset($return['status'],$return['msg']) && $return['status'] == 'error';
					if($error_message):
						?>
						<div id="error" class="error inline">
							<p><strong><?php echo $return['msg']; ?></strong></p>
						</div>
					<?php endif; ?>
				<?php endif; ?>
		    	<table class="form-table widefat striped bm-promotional-mail-settings-table" style="width:90%;">
			    	<tbody>
						<?php if(!isset($_GET['import'])): ?>
						<tr>
							<th style="padding:25px;"><b><?php esc_html_e('Select Type','blindmatrix');?></b></th>
								<td>
									<select class="bm-status-selection" style="width:50%;">
									<option value="all"><?php esc_html_e('All','blindmatrix');?></option>
									<option value="free_trial"><?php esc_html_e('Free Trial','blindmatrix');?></option>
									<option value="premium"><?php esc_html_e('Premium','blindmatrix');?></option>
									<option value="expired"><?php esc_html_e('Expired','blindmatrix');?></option>
									<option value="not_activated"><?php esc_html_e('Not Activated','blindmatrix');?></option>
									<option value="import" <?php if(isset($_REQUEST['bm-upload-action']) || isset($_GET['import'])):?> selected="selected" <?php endif; ?>><?php esc_html_e('Import','blindmatrix');?></option>
									</select>
								</td>
						</tr>
						<?php endif; ?>
						<?php if(!isset($_GET['import'])): ?>
						<tr>
							<th style="padding:25px;">
								<b><?php esc_html_e('Choose a CSV file','blindmatrix');?></b>
							</th>
							<td>
								<input type="file" id="bm_upload" name="import" size="25" value="<?php isset($_FILES['import']['name']) ?$_FILES['import']['name']:'' ?>" />
								<p class="description"><?php echo __('<b>Note:</b><br/> 1 .csv and .txt file extensions are supported for Import<br/>2. File Column should be <b>Email IDs</b>','blindmatrix');?></p>
							</td>
						</tr>
						<?php endif; ?>
						
						<?php if(!isset($_GET['import'])): ?>
						<tr>
							<th style="padding:25px;"></th>
							<td>
								<input type="submit" class="button bm-upload-action" name="bm-upload-action" value="<?php esc_html_e('Upload','blindmatrix');?>"> 
							</td>
						</tr>
						<?php endif; ?>
						
						<?php if(isset($_GET['import'])): ?>
						<tr>
							<th style="padding:25px;">
								<b><?php esc_html_e('Preview the File','blindmatrix');?></b>
							</th>
							<td>
								<a href="#" class="button bm-preview-action"><?php esc_html_e('Preview the file','blindmatrix');?></a>
							</td>
						</tr>
						<?php endif; ?>
						
						<tr>
							<th></th>
							<td>
								<a href="#" class="button button-primary bm-hub-promotional-send-mail-action"><?php esc_html_e('Send Mail','blindmatrix'); ?></a>
								<input type="hidden" class="bm-import-page"value="<?php echo isset($_GET['import'])? 'yes':'no';?>">
							</td>
						</tr>
				</tbody>
			</table>
		</form>
</div>

