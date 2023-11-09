<?php
/**
 * View Userslist main settings HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap bm-view-userlists-settings-wrapper">
	<form method="post" id="mainform" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $slug => $label ) :
				echo '<a href="' . esc_url( admin_url( 'admin.php?page=users_list_table&bm_hub_post_id='.$post_id.'&action=edit&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_view_userlist_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
			endforeach;
			?>
		</nav>
		<h1 class="screen-reader-text"><?php echo esc_html( $current_tab_label ); ?></h1>
			<?php
			do_action( 'blind_matrix_view_userslist_sections_' . $current_view_userlist_tab );
			do_action( 'blind_matrix_view_userslist_settings_tabs_' . $current_view_userlist_tab );
			?>
	</form>
</div>