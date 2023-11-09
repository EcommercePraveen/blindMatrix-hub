<?php
/**
 * Promotional Tab Main Settings HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap bm-email-template-wrapper">
			<form method="post" id="mainform" enctype="multipart/form-data">
				<nav class="nav-tab-wrapper">
				<?php
				foreach ( $tabs as $slug => $label ) :
					echo '<a href="' . esc_html( admin_url( 'admin.php?page=bm_email_template&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
				endforeach;
				?>
				</nav>
				<h1 class="screen-reader-text"><?php echo esc_html( $current_tab_label ); ?></h1>
				<?php
				do_action( 'blind_matrix_sections_' . $current_tab );
				do_action( 'blind_matrix_settings_tabs_' . $current_tab );
				?>
			</form>
</div>

