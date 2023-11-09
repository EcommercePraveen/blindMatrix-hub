<?php
/**
 * Import Userslist Data HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="bm-userlist-import-settings-wrapper">
	<h2>Import Settings					
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=users_list_table&bm_userlist_upload=yes')); ?>" class="button" style="margin-left:10px;">Back</a>
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=users_list_table')); ?>" class="button" style="margin-left:10px;">Back to User List Table</a>
	</h2>
	<table class="form-table widefat striped bm-userlist-import-settings-table" style="width:90%;">
			    	<tbody>
						<tr>
							<th style="padding:25px;">
								<b>Preview the File</b>
							</th>
							<td>
								<a href="#" class="button bm-userslist-preview-action">Preview the file</a>
							</td>
						</tr>
						<tr>
							<th></th>
							<td>
								<a href="#" class="button button-primary bm-hub-userslist-import-action">Import</a>
								<input type="hidden" class="bm-import-page" value="yes">
							</td>
						</tr>
				</tbody>
	</table>
</div>