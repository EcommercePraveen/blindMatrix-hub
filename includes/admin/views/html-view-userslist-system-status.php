<?php
/**
 * View Userslist System Status table HTML
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if(!is_object($reports)):
	return;
endif;

$environment        = $reports->environment;
$database           = $reports->database;
$security           = $reports->security;
$active_plugins     = $reports->active_plugins;
$inactive_plugins   = $reports->inactive_plugins;
$settings           = $reports->settings;
$theme              = $reports->theme;
$plugin_status = $user_lists_object->get_plugin_status();
?>
<div class="bm-userslist-view-system-status-wrapper wrap">
	<?php if(!empty($environment) && is_object($environment)): ?>
    	<br>
		<table class="widefat striped wp-environment-content">
    		<thead>
				<tr>
					<th colspan="3" data-export-label="WordPress Environment">
                    <b><?php esc_html_e( 'WordPress Environment', 'blindmatrix' ); ?></b></th>
				</tr>
			</thead>
            <tbody>
            	<tr>
					<td><?php esc_html_e( 'WordPress address (URL)', 'blindmatrix' ); ?>:</td>
					<td><a href="<?php echo esc_url( $environment->site_url ); ?>" target="_blank"><?php echo esc_html( $environment->site_url ); ?><a></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'Site address (URL)', 'blindmatrix' ); ?>:</td>
					<td><a href="<?php echo esc_url( $environment->home_url ); ?>" target="_blank"><?php echo esc_html( $environment->home_url ); ?></a></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'WooCommerce version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html( $environment->version ); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'Wordpress version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html( $environment->wp_version ); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'WordPress multisite', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html( $environment->wp_multisite ) ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'WordPress memory limit', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html( size_format($environment->wp_memory_limit) ); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'WordPress debug mode', 'blindmatrix' ); ?>:</td>
					<td>
						<?php if ( $environment->wp_debug_mode ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
					</td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'WordPress cron', 'blindmatrix' ); ?>:</td>
					<td>
						<?php if ( $environment->wp_cron ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
					</td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'Language', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html( $environment->language ); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'External object cache', 'blindmatrix' ); ?>:</td>
					<td><?php if ( $environment->external_object_cache ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
                    </td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'Server info', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($environment->server_info); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'PHP version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($environment->php_version); ?></td>
				</tr>
                 <tr>
					<td><?php esc_html_e( 'PHP Post Max Size', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html(size_format($environment->php_post_max_size)); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'PHP time limit', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($environment->php_max_execution_time); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'PHP max input vars', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($environment->php_max_input_vars); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'SQL version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($environment->mysql_version); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'Max upload size', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html(size_format($environment->max_upload_size)); ?></td>
				</tr>
                <tr>
					<td><?php esc_html_e( 'Default timezone is UTC', 'blindmatrix' ); ?>:</td>
					<td><?php
						if ( 'UTC' !== $environment->default_timezone ) :
							echo '<span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'blindmatrix' ), esc_html( $environment->default_timezone ) );
						else:
							echo '<span class="dashicons dashicons-yes"></span>';
						endif;
						?>
                    </td>
				</tr>
                <tr>
                	<td><?php esc_html_e( 'WooCommerce database version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($database->wc_database_version); ?></td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Database prefix', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($database->database_prefix); ?></td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Secure connection (HTTPS)', 'blindmatrix' ); ?>:</td>
					<td>
                    	<?php if ( $security->secure_connection ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
                    </td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Hide errors from visitors', 'blindmatrix' ); ?>:</td>
					<td>
                    	<?php if ( $security->hide_errors ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
                    </td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Force SSL', 'blindmatrix' ); ?>:</td>
					<td><?php 
                    	if ( $settings->api_enabled ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
                    </td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Currency', 'blindmatrix' ); ?>:</td>
					<td><?php 
                    	echo esc_html($settings->currency.' ('.$settings->currency_symbol.')'); ?>
                    </td>
                </tr>
                <?php if ( $theme->is_child_theme ) : ?>
                <tr>
                	<td><?php esc_html_e( 'Parent Theme', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($theme->parent_name); ?></td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Parent Theme Version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($theme->parent_version); ?></td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Child theme', 'blindmatrix' ); ?>:</td>
					<td><?php 
                    	if ( $theme->is_child_theme ) : ?>
							<span class="dashicons dashicons-yes"></span>
						<?php else : ?>
							&ndash;
						<?php endif; ?>
                    </td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Child Theme Name', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($theme->name); ?></td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Child Theme Version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($theme->version); ?></td>
                </tr>
                <?php 
                else:
                ?>
                <tr>
                	<td><?php esc_html_e( 'Theme Name', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($theme->name); ?></td>
                </tr>
                <tr>
                	<td><?php esc_html_e( 'Theme Version', 'blindmatrix' ); ?>:</td>
					<td><?php echo esc_html($theme->version); ?></td>
                </tr>
                <?php
                endif; ?>
            </tbody>
    	</table>
        <br>
        <?php 
        $our_plugin_data = array();
        if(!empty($active_plugins)): 
        	foreach($active_plugins as $active_plugin):
            	if('BlindMatrix eCommerce' == $active_plugin->name):
                		$plugin_info = '<b>'.$active_plugin->name.'</b> '.'v'.$active_plugin->version.' ';
                            if('' != $active_plugin->author_name):
                            	$plugin_info.= 'by '.$active_plugin->author_name;
                            endif;
                	  $our_plugin_data['name'] = $plugin_info;   
                endif;
             endforeach;
         endif;
         if(!empty($inactive_plugins)): 
        	foreach($inactive_plugins as $inactive_plugin):
            	if('BlindMatrix eCommerce' == $inactive_plugin->name):
                		$plugin_info = '<b>'.$inactive_plugin->name.'</b> '.'v'.$inactive_plugin->version.' ';
                            if('' != $inactive_plugin->author_name):
                            	$plugin_info.= 'by '.$inactive_plugin->author_name;
                            endif;
                	  $our_plugin_data['name'] = $plugin_info;   
                endif;
         	endforeach;
        endif;
       
        if($active_plugins): 
        	$plugin_count = 0;
        	foreach($active_plugins as $active_plugin):
            	if('BlindMatrix eCommerce' == $active_plugin->name):
                	continue;
                endif;
                $plugin_count = 1;
             endforeach;
          endif;
            if($plugin_count):
        ?>
		  <table class="widefat striped">
        	<thead>
            	<tr>
					<th colspan="3" data-export-label="Active Plugins">
                    	<b><?php esc_html_e( 'Active Plugins', 'blindmatrix' ); ?></b>					
                    </th>
				</tr>
            </thead>
            <tbody>
            	<?php if('activated' == $plugin_status): ?>
            		<tr>
                		<td><?php echo $our_plugin_data['name']; ?></td>
                	</tr>
            	<?php 
                	endif;
                	foreach($active_plugins as $active_plugin_data):
                    	if('BlindMatrix eCommerce' == $active_plugin_data->name):
                        	continue;
                        endif;
                    	?>
                		<tr>
                        	<td><?php
                            $plugin_info = '<b>'.$active_plugin_data->name.'</b> '.'v'.$active_plugin_data->version.' ';
                            if('' != $active_plugin_data->author_name):
                            	$plugin_info.= 'by '.$active_plugin_data->author_name;
                            endif;
                            echo wp_kses_post($plugin_info); ?></td>
                		</tr>
                        <?php 
                	endforeach;
                    ?>
            </tbody>
          </table>
        <?php 
        	endif;
       	endif;
        ?>
        <br>
        <?php if(!empty($inactive_plugins)): 
        	$plugin_count = 0;
        	foreach($inactive_plugins as $inactive_plugin):
            	if('BlindMatrix eCommerce' == $inactive_plugin->name):
                	continue;
                endif;
                
                $plugin_count = 1;
            endforeach;
            
            if($plugin_count):
        ?>
		 <table class="widefat striped">
        	<thead>
            	<tr>
					<th colspan="3" data-export-label="Inactive Plugins">
                    	<b><?php esc_html_e( 'Inactive Plugins', 'blindmatrix' ); ?></b>					
                    </th>
				</tr>
            </thead>
            <tbody>
            	<?php if('activated' != $plugin_status): ?>
            		<tr>
                		<td><?php echo $our_plugin_data['name']; ?></td>
                	</tr>
            	<?php 
                	endif;
                	foreach($inactive_plugins as $inactive_plugin_data):
                    	if('BlindMatrix eCommerce' == $inactive_plugin_data->name):
                        	continue;
                        endif;
                    	?>
                		<tr>
                        	<td><?php echo wp_kses_post('<b>'.$inactive_plugin_data->name.'</b> '.'v'.$inactive_plugin_data->version.' '.'by '.$inactive_plugin_data->author_name); ?></td>
                		</tr>
                        <?php 
                	endforeach;
               ?>
            </tbody>
        </table>
    <?php 
    	 endif;
    endif; ?>
</div>