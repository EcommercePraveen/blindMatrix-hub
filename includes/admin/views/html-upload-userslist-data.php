<?php
/**
 * Upload Userlist Data HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="bm-userslist-import-wrapper wrap">
				<form method="post" enctype="multipart/form-data">	
				 <h1 class="wp-heading-inline"><?php esc_html_e('Import Settings','blindmatrix');?>
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=users_list_table')); ?>" class="button"><?php esc_html_e('Back','blindmatrix');?></a>
				 </h1>
				 <hr class="wp-header-end">
				 <?php if(isset($_REQUEST['bm-upload-userlist-action']) && class_exists('BM_Admin') ): 
					$return        = self::handle_upload_for_user_list_table();
					$error_message = isset($return['status'],$return['msg']) && $return['status'] == 'error';
					if($error_message):
						?>
						<div id="error" class="error inline">
							<p><strong><?php echo $return['msg']; ?></strong></p>
						</div>
					<?php endif; ?>
				 <?php endif; ?>
				 <table class="form-table widefat striped" style="width:90%;margin:50px 50px 0 0;">
					<tbody>
						<tr>
							<th style="padding:25px;">
								<b><?php esc_html_e('Choose a CSV file','blindmatrix');?></b>
								<span class="required">*</span>
							</th>
							<td>
								<input type="file" id="bm_upload_userlist_file" name="import" size="25" value="<?php isset($_FILES['import']['name']) ?$_FILES['import']['name']:'' ?>" />
							</td>
						</tr>
						<tr>
							<th style="padding:25px;"></th>
							<td>
								<input type="submit" class="button" name="bm-upload-userlist-action" value="<?php esc_html_e('Upload','blindmatrix');?>"> 
							</td>
						</tr>
					</tbody>
				</table>
			  </form>
			</div>