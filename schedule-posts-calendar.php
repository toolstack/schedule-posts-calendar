<?php
/*
Plugin Name: Schedule Posts Calendar
Version: 5.3
Plugin URI: http://toolstack.com/SchedulePostsCalendar
Author: Greg Ross
Author URI: http://toolstack.com
Text Domain: schedule-posts-calendar
Description: Adds a JavaScript calendar to the schedule posts options.

Compatible with WordPress 3+.

Read the accompanying readme.txt file for instructions and documentation.

Copyright (c) 2012-23 by Greg Ross

This software is released under the GPL v2.0, see license.txt for details
*/

define( 'SCHEDULEPOSTCALENDARVERSION', '5.3' );

/*
 	This function is called to add the .css and .js files for the calendar to
    the WordPress pages.

 	It's registered at the end of the file with an add_action() call.
*/
function schedule_posts_calendar_add_cal($theme_num, $url)
	{
	// Register and enqueue the calendar css files, create a theme string to use later during the JavaScript inclusion.
	switch( $theme_num )
		{
		case 2:
			wp_register_style( 'dhtmlxcalendar_style', $url . '/skins/css/dhtmlxcalendar_dhx_skyblue.css' );
			break;
		case 3:
			wp_register_style( 'dhtmlxcalendar_style', $url . '/skins/css/dhtmlxcalendar_dhx_web.css' );
			break;
		case 4:
			wp_register_style( 'dhtmlxcalendar_style', $url . '/skins/css/dhtmlxcalendar_dhx_terrace.css' );
			break;
		case 5:
			wp_register_style( 'dhtmlxcalendar_style', $url . '/skins/css/dhtmlxcalendar_material.css' );
			break;
		default:
			wp_register_style( 'dhtmlxcalendar_style', $url . '/skins/css/dhtmlxcalendar_wordpress.css' );
			break;
		}

	wp_register_style( 'dhtmlxcalendar', $url . '/dhtmlxcalendar.css' );
	wp_enqueue_style( 'dhtmlxcalendar' );
	wp_enqueue_style( 'dhtmlxcalendar_style' );

	// Register and enqueue the calender scripts.
	wp_register_script( 'dhtmlxcalendar', $url . '/dhtmlxcalendar.js' );
	wp_enqueue_script( 'dhtmlxcalendar' );
	}

/*
 	This function is called to add the .css and .js files to the WordPress pages.
 	It's registered at the end of the file with an add_action() call.
*/
function schedule_posts_calendar()
	{
	// Find out where our plugin is stored.
	$plugin_url = plugins_url( '', __FILE__ );

	// Retrieve the options.
	$options = get_option( 'schedule_posts_calendar' );

	if( !isset($options['theme']) ) { $options['theme'] = 4; }

	// Register and enqueue the calendar css files, create a theme string to use later during the JavaScript inclusion.
	schedule_posts_calendar_add_cal( $options['theme'], $plugin_url );

	// Add the css file that will hide the default WordPress timestamp field.
	if( array_key_exists( 'hide-timestamp', $options ) && $options['hide-timestamp'] == 1 )
		{
		wp_register_style( 'hide-timestamp', $plugin_url . '/hide-timestamp.css' );
		wp_enqueue_style( 'hide-timestamp' );
		}


	if( ! array_key_exists( 'theme', $options ) ) { $options['theme'] = false; }
	if( ! array_key_exists( 'startofweek', $options ) ) { $options['startofweek'] = false; }
	if( ! array_key_exists( 'popup-calendar', $options ) ) { $options['popup-calendar'] = false; }

	// Register and enqueue the calender scripts.
	wp_register_script( 'schedulepostscalendar', $plugin_url . '/schedule-posts-calendar.js?theme=' . $options['theme'] . '&startofweek=' . $options['startofweek'] . '&popupcalendar=' . $options['popup-calendar'], "dhtmlxcalendar" );
	wp_enqueue_script( 'schedulepostscalendar' );
	}

/*
 	This function is called to add the .css and .js files to the WordPress list pages.
 	It's registered at the end of the file with an add_action() call.
*/
function schedule_posts_calendar_quick_schedule()
	{
	// Find out where our plugin is stored.
	$plugin_url = plugins_url( '', __FILE__ );

	// Retrieve the options.
	$options = get_option( 'schedule_posts_calendar' );

	// Register and enqueue the calendar css files, create a theme string to use later during the JavaScript inclusion.
	schedule_posts_calendar_add_cal( $options['theme'], $plugin_url );

	// Register and enqueue the calender scripts.
	wp_register_script( 'schedulepostscalendar', $plugin_url . '/schedule-posts-calendar-quick-schedule.js?theme=' . $options['theme'] . '&startofweek=' . $options['startofweek'] . '&popupcalendar=' . $options['popup-calendar'], "dhtmlxcalendar" );
	wp_enqueue_script( 'schedulepostscalendar' );
	}

function schedule_posts_calendar_checked_state( $value, $key )
	{
	if( array_key_exists( $key, $value ) )
		{
		if( $value[$key] == 1 )
			{
			return 1;
			}
		}

	return 0;
	}

/*
 	This function is called when you select the admin page for the plugin, it generates the HTML
 	and is responsible to store the settings.
*/
function schedule_posts_calendar_admin_page()
	{
	$daysoftheweek = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
	$monthsoftheyear = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

	$translatedDOTW = array( 'Monday' => __( 'Monday', 'schedule-posts-calendar' ), 'Tuesday' => __( 'Tuesday', 'schedule-posts-calendar' ), 'Wednesday' => __( 'Wednesday', 'schedule-posts-calendar' ), 'Thursday' => __( 'Thursday', 'schedule-posts-calendar' ), 'Friday' => __( 'Friday', 'schedule-posts-calendar' ), 'Saturday' => __( 'Saturday', 'schedule-posts-calendar' ), 'Sunday' => __( 'Sunday', 'schedule-posts-calendar' ) );
	$translatedMOTY = array( 'January' => __( 'January', 'schedule-posts-calendar' ), 'February' => __( 'February', 'schedule-posts-calendar' ), 'March' => __( 'March', 'schedule-posts-calendar' ), 'April' => __( 'April', 'schedule-posts-calendar' ), 'May' => __( 'May', 'schedule-posts-calendar' ), 'June' => __( 'June', 'schedule-posts-calendar' ), 'July' => __( 'July', 'schedule-posts-calendar' ), 'August' => __( 'August', 'schedule-posts-calendar' ), 'September' => __( 'September', 'schedule-posts-calendar' ), 'October' => __( 'October', 'schedule-posts-calendar' ), 'November' => __( 'November', 'schedule-posts-calendar' ), 'December' => __( 'December', 'schedule-posts-calendar' ) );


	if( array_key_exists( 'schedule_posts_calendar', $_POST ) )
		{
		// Make sure we have the nonce.
		check_admin_referer('schedule-posts-calendar-settings');

		$new_options = array( 'schedule_posts_calendar' => array() );

		if( empty( $_POST['schedule_posts_calendar']['startofweek'] ) ) { $new_options['schedule_posts_calendar']['startofweek'] = 7; } else { $new_options['schedule_posts_calendar']['startofweek'] = intval( $_POST['schedule_posts_calendar']['startofweek'] ); }
		if( empty( $_POST['schedule_posts_calendar']['theme'] ) ) { $new_options['schedule_posts_calendar']['theme'] = 4; } else { $new_options['schedule_posts_calendar']['theme'] = intval( $_POST['schedule_posts_calendar']['theme'] ); }
		$new_options['schedule_posts_calendar']['hide-timestamp'] = schedule_posts_calendar_checked_state( $_POST['schedule_posts_calendar'], 'hide-timestamp' );
		$new_options['schedule_posts_calendar']['popup-calendar'] = schedule_posts_calendar_checked_state( $_POST['schedule_posts_calendar'], 'popup-calendar' );
		$new_options['schedule_posts_calendar']['enable-translation'] = schedule_posts_calendar_checked_state( $_POST['schedule_posts_calendar'], 'enable-translation' );
		$new_options['schedule_posts_calendar']['override-translation'] = schedule_posts_calendar_checked_state( $_POST['schedule_posts_calendar'], 'override-translation' );

		foreach( $monthsoftheyear as $month )
			{
			if( empty( $_POST['schedule_posts_calendar']['FMN'.$month] ) ) { $new_options['schedule_posts_calendar']['FMN'.$month] = $translatedMOTY[$month]; } else { $new_options['schedule_posts_calendar']['FMN'.$month] = sanitize_text_field( $_POST['schedule_posts_calendar']['FMN'.$month] ); }
			if( empty( $_POST['schedule_posts_calendar']['SMN'.$month] ) ) { $new_options['schedule_posts_calendar']['SMN'.$month] = $translatedMOTY[$month]; } else { $new_options['schedule_posts_calendar']['SMN'.$month] = sanitize_text_field( $_POST['schedule_posts_calendar']['SMN'.$month] ); }
			}

		foreach( $daysoftheweek as $day )
			{
			if( empty( $_POST['schedule_posts_calendar']['FDN'.$day] ) ) { $new_options['schedule_posts_calendar']['FDN'.$day] = $translatedDOTW[$day]; } else { $new_options['schedule_posts_calendar']['FDN'.$day] = sanitize_text_field( $_POST['schedule_posts_calendar']['FDN'.$day] ); }
			if( empty( $_POST['schedule_posts_calendar']['SDN'.$day] ) ) { $new_options['schedule_posts_calendar']['SDN'.$day] = $translatedDOTW[$day]; } else { $new_options['schedule_posts_calendar']['SDN'.$day] = sanitize_text_field( $_POST['schedule_posts_calendar']['SDN'.$day] ); }
			}

		if( empty( $_POST['schedule_posts_calendar']['Cancel'] ) ) { $new_options['schedule_posts_calendar']['Cancel'] = __('Cancel', 'schedule-posts-calendar'); } else { $new_options['schedule_posts_calendar']['Cancel'] = sanitize_text_field( $_POST['schedule_posts_calendar']['Cancel'] ); }
		if( empty( $_POST['schedule_posts_calendar']['OK'] ) ) { $new_options['schedule_posts_calendar']['Ok'] = __('OK', 'schedule-posts-calendar'); } else { $new_options['schedule_posts_calendar']['OK'] = sanitize_text_field( $_POST['schedule_posts_calendar']['OK'] ); }
		if( empty( $_POST['schedule_posts_calendar']['Today'] ) ) { $new_options['schedule_posts_calendar']['Today'] = __('Today', 'schedule-posts-calendar'); } else { $new_options['schedule_posts_calendar']['Today'] = sanitize_text_field( $_POST['schedule_posts_calendar']['Today'] ); }
		if( empty( $_POST['schedule_posts_calendar']['Update'] ) ) { $new_options['schedule_posts_calendar']['Update'] = __('Update', 'schedule-posts-calendar'); } else { $new_options['schedule_posts_calendar']['Update'] = sanitize_text_field( $_POST['schedule_posts_calendar']['Update'] ); }

		update_option( 'schedule_posts_calendar', $new_options['schedule_posts_calendar'] );

		print "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>Settings saved.</strong></p></div>\n";
		}

	$options = get_option( 'schedule_posts_calendar' );

	// If the options haven't been set yet, make sure to setup an empty array for them.
	if( !is_array( $options ) ) { $options = array(); }

	if( !array_key_exists( 'startofweek', $options ) ) { $options['startofweek'] = 7; }
	if( !array_key_exists( 'theme', $options ) ) { $options['theme'] = 4; }
	if( !array_key_exists( 'hide-timestamp', $options ) ) { $options['hide-timestamp'] = 0; }
	if( !array_key_exists( 'popup-calendar', $options ) ) { $options['popup-calendar'] = 0; }
	if( !array_key_exists( 'enable-translation', $options ) ) { $options['enable-translation'] = 1; }
	if( !array_key_exists( 'override-translation', $options ) ) { $options['override-translation'] = 0; }

	foreach( $monthsoftheyear as $month )
		{
		if( !array_key_exists( 'FMN'.$month, $options ) ) { $options['FMN'.$month] = $translatedMOTY[$month]; }
		if( !array_key_exists( 'SMN'.$month, $options ) ) { $options['SMN'.$month] = $translatedMOTY[$month]; }
		}

	foreach( $daysoftheweek as $day )
		{
		if( !array_key_exists( 'FDN'.$day, $options ) ) { $options['FDN'.$day] = $translatedDOTW[$day]; }
		if( !array_key_exists( 'SDN'.$day, $options ) ) { $options['SDN'.$day] = $translatedDOTW[$day]; }
		}

	if( !array_key_exists( 'Cancel', $options ) ) { $options['Cancel'] = __('Cancel', 'schedule-posts-calendar'); }
	if( !array_key_exists( 'OK', $options ) ) { $options['OK'] = __('OK', 'schedule-posts-calendar'); }
	if( !array_key_exists( 'Today', $options ) ) { $options['Today'] = __('Today', 'schedule-posts-calendar'); }
	if( !array_key_exists( 'Update', $options ) ) { $options['Update'] = __('Update', 'schedule-posts-calendar'); }

	//***** Start HTML
	?>
<div class="wrap">
	<form method="post">

	<?php wp_nonce_field( 'schedule-posts-calendar-settings' ); // Add a nonce to the form. ?>

	<fieldset style="border:1px solid #cecece;padding:15px; margin-top:25px" >
		<legend><span style="font-size: 24px; font-weight: 700;">&nbsp;Schedule Posts Calendar Options&nbsp;</span></legend>
		<div><?php esc_html_e('Start week on:', 'schedule-posts-calendar');?> <Select name="schedule_posts_calendar[startofweek]">
<?php
		for( $i = 0; $i < 7; $i++ )
			{
			echo "			<option value=" . ($i + 1);
			if( $options['startofweek'] == $i + 1 ) { echo " SELECTED"; }
			echo ">" . $daysoftheweek[$i] . "</option>\r\n";
			}
?>
		</select></div>

		<div>&nbsp;</div>

		<div><?php esc_html_e('Calendar theme:', 'schedule-posts-calendar');?> <Select name="schedule_posts_calendar[theme]">
<?php
		$themes = array( 'WordPress', 'Sky Blue', 'Web', 'Terrace', 'Material' );

		for( $i = 0; $i < count( $themes ); $i++ )
			{
			echo "			<option value=" . ($i + 1);
			if( $options['theme'] == $i + 1 ) { echo " SELECTED"; }
			echo ">" . $themes[$i] . "</option>\r\n";
			}
?>
		</select></div>

		<div>&nbsp;</div>

		<div><input name="schedule_posts_calendar[hide-timestamp]" type="checkbox" value="1" <?php checked($options['hide-timestamp'], 1); ?> /> <?php esc_html_e("Hide WordPress's default time stamp display", 'schedule-posts-calendar'); ?></div>

			<div>&nbsp;</div>

			<div><input name="schedule_posts_calendar[popup-calendar]" type="checkbox" value="1" <?php checked($options['popup-calendar'], 1); ?> /> <?php esc_html_e('Use a popup calendar instead of an inline one (you probably want to hide the default display above)', 'schedule-posts-calendar'); ?></div>

			<div class="submit"><input type="submit" name="info_update" class="button-primary" value="<?php esc_attr_e('Update Options') ?>" /></div>

	</fieldset>

	<fieldset style="border:1px solid #cecece;padding:15px; margin-top:25px" >
		<legend><span style="font-size: 24px; font-weight: 700;">&nbsp;Translation Options&nbsp;</span></legend>
		<div><input name="schedule_posts_calendar[enable-translation]" type="checkbox" value="1" <?php checked($options['enable-translation'], 1); ?> /> <?php esc_html_e('Enable translation', 'schedule-posts-calendar');?></div>

		<div>&nbsp;</div>

		<div><input name="schedule_posts_calendar[override-translation]" type="checkbox" value="1" <?php checked($options['override-translation'], 1); ?> /> <?php esc_html_e('Override translation with the following values:', 'schedule-posts-calendar');?></div>

		<div>&nbsp;</div>

		<table>
			<tr>
				<td colspan=6>
					<b><?php esc_html_e('Miscellaneous', 'schedule-posts-calendar')?>:</b>
				</td>
			</tr>

			<tr>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cancel
				</td>
				<td>
					=
				</td>
				<td>
					<input name="schedule_posts_calendar[Cancel]" type="text" value="<?php echo esc_attr( $options['Cancel'] );?>" size=10>
				</td>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK
				</td>
				<td>
					=
				</td>
				<td>
					<input name="schedule_posts_calendar[OK]" type="text" value="<?php echo esc_attr( $options['OK']);?>" size=10>
				</td>
			</tr>

			<tr>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Today
				</td>
				<td>
					=
				</td>
				<td>
					<input name="schedule_posts_calendar[Today]" type="text" value="<?php echo esc_attr( $options['Today']);?>" size=10>
				</td>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Update
				</td>
				<td>
					=
				</td>
				<td>
					<input name="schedule_posts_calendar[Update]" type="text" value="<?php echo esc_attr( $options['Update']);?>" size=10>
				</td>
			</tr>


			<tr>
				<td colspan=6>
					&nbsp;
				</td>
			</tr>

			<tr>
				<td colspan=3>
					<b><?php esc_html_e('Full Month Names:', 'schedule-posts-calendar')?></b>
				</td>
				<td colspan=3>
					<b><?php esc_html_e('Short Month Names:', 'schedule-posts-calendar')?></b>
				</td>
			</tr>

			<?php
			foreach( $monthsoftheyear as $month )
				{
				echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $translatedMOTY[$month] .'</td><td> = </td><td><input name="schedule_posts_calendar[FMN' . $month . ']" type="text" value="' . esc_attr( $options['FMN'.$month] ) . '" size=10></td>';
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $translatedMOTY[$month] .'</td><td> = </td><td><input name="schedule_posts_calendar[SMN' . $month . ']" type="text" value="' . esc_attr( $options['SMN'.$month] ) . '" size=10></td></tr>';
				}

			?>

			<tr>
				<td colspan=6>
					&nbsp;
				</td>
			</tr>

			<tr>
				<td colspan=3>
					<b><?php esc_html_e('Full Day Names:', 'schedule-posts-calendar')?></b>
				</td>
				<td colspan=3>
					<b><?php esc_html_e('Short Day Names:', 'schedule-posts-calendar')?></b>
				</td>
			</tr>

			<?php
			foreach( $daysoftheweek as $day )
				{
				echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $translatedDOTW[$day] .'</td><td> = </td><td><input name="schedule_posts_calendar[FDN' . $day . ']" type="text" value="' . esc_attr( $options['FDN' . $day] ) . '" size=10></td>';
				echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $translatedDOTW[$day] .'</td><td> = </td><td><input name="schedule_posts_calendar[SDN' . $day . ']" type="text" value="' . esc_attr( $options['SDN' . $day] ) . '" size=10></td></tr>';
				}
			?>
		</table>

		<div class="submit"><input type="submit" name="info_update" class="button-primary" value="<?php esc_attr_e('Update Options') ?>" /></div>

	</fieldset>

	</form>

	<fieldset style="border:1px solid #cecece;padding:15px; margin-top:25px" >
		<legend><span style="font-size: 24px; font-weight: 700;">&nbsp;About&nbsp;</span></legend>
			<p><?php printf( __('Schedule Posts Calendar Version %1$s','schedule-posts-calender'), SCHEDULEPOSTCALENDARVERSION );?></p>
			<p><?php _e('by Greg Ross', 'schedule-posts-calendar');?></p>
			<p>&nbsp;</p>
			<p><?php printf(__('Licenced under the %sGPL Version 2%s', 'schedule-posts-calendar'), '<a href="http://www.gnu.org/licenses/gpl-2.0.html" target=_blank>', '</a>');?></p>
			<p><?php printf(__('To find out more, please visit the %sWordPress Plugin Directory page%s or the plugin home page on %sToolStack.com%s', 'schedule-posts-calendar'), '<a href="http://wordpress.org/plugins/schedule-posts-calendar/" target=_blank>', '</a>', '<a href="http://toolstack.com/schedule-posts-calendar" target=_blank>', '</a>');?></p>
			<p>&nbsp;</p>
			<p><?php printf(__("Don't forget to %srate and review%s it too!", 'schedule-posts-calendar'), '<a href="http://wordpress.org/support/view/plugin-reviews/schedule-posts-calendar" target=_blank>', '</a>');?></p>
	</fieldset>
</div>
	<?php
	//***** End HTML
	}

/*
 	This function is called to check if we need to add the above .css and .js files
 	on this page.  ONLY the posts pages need to include the files, all other admin pages
 	don't need them.
*/
function SCP_Add_Calendar_Includes()
	{
	// First check to make sure we have a server variable set to the script name, if we
	// don't fall back to including the .css and .js files on all admin pages.
	if(isset($_SERVER['SCRIPT_NAME']) )
		{
		// Grab the lower case base name of the script file.
		$pagename = strtolower(basename($_SERVER['SCRIPT_NAME'], ".php"));

		// There are only two pages we really need to include the files on, so
		// use a switch to make it easier for later if we need to add more page
		// names to the list.
		switch( $pagename )
			{
			case "post":
			case "post-new":
				return "schedule_posts_calendar";
			case "edit":
				return "schedule_posts_calendar_quick_schedule";
			default:
				return "";
			}
		}
	else
		{
		return true;
		}
	}

/*
 	This function is called to add the options page to the settings menu.
 	It's registered at the end of the file with an add_action() call.
*/
function schedule_posts_calendar_admin()
	{
	add_options_page( 'Schedule Posts Calendar', 'Schedule Posts Calendar', 'manage_options', basename( __FILE__ ), 'schedule_posts_calendar_admin_page');
	}

/*
   Add the link to action list for post_row_actions.
*/
function schedule_posts_calendar_link_row($actions, $post)
	{
	$actions['schedule'] = '<a href="#" class="editinlineschedule" title="Schedule this item" onClick="scp_calendar_quick_schedule_edit(' . $post->ID . ');">' . __('Schedule', 'schedule-posts-calendar') . '</a>';

	return $actions;
	}

/*
   Add the link to settings from the plugin list.
*/
function schedule_posts_calendar_plugin_actions( $actions, $plugin_file, $plugin_data, $context )
	{
	array_unshift( $actions, '<a href="' . admin_url() . 'options-general.php?page=schedule-posts-calendar.php">' . __('Settings', 'schedule-posts-calendar') . '</a>' );

	return $actions;
	}

/*
	Add a function that the JavaScript code can use to retrieve translation information for.
*/
function schedule_posts_calendar_lang()
	{
	// Get the options.
	$options = get_option( 'schedule_posts_calendar' );

	// If the 'enable-translation' option hasn't been set yet, for example if the settings
	// haven't been saved since the upgrade, assume translations should be enabled.
	if( !isset( $options['enable-translation'] ) ) { $options['enable-translation'] = 1; }

	// We're outputting the script no matter what (so we don't have to check for the function's
	// existence in the JavaScript code, so setup the first part of it.
	// Note we make it a function for two reasons:
	//     1. GLOBALs are BAD
	//     2. We're early enough in the code that the dhtmlXCalendar code hasn't been
	//        added yet so the object definition isn't available to use.
	echo '<script type="text/javascript">' . "\n";
	echo 'function SchedulePostsCalenderLang() {' . "\n";

	// Check to see if translation is enabled
	if( $options['enable-translation'] == 1 )
		{
		// Let's create the update code for the dhtmlXCalendar.
		echo '    dhtmlXCalendarObject.prototype.langData["wordpress"] = {' . "\n";

		// Check to see if we're using the WordPress translation or not
		if( array_key_exists( 'override-translation', $options ) && $options['override-translation'] == 1 )
			{
			// Overriding may be useful if the WordPress functions don't return 'good' translations.
			echo '        monthesFNames: ["' . esc_html( $options['FMNJanuary'] ) . '","' . esc_html( $options['FMNFebruary'] ) . '","' . esc_html( $options['FMNMarch'] ) . '","' . esc_html( $options['FMNApril'] ) . '","' . esc_html( $options['FMNMay'] ) . '","' . esc_html( $options['FMNJune'] . '","' ) . esc_html( $options['FMNJuly'] ) . '","' . esc_html( $options['FMNAugust'] ) . '","' . esc_html( $options['FMNSeptember'] ) . '","' . esc_html( $options['FMNOctober'] ) . '","' . esc_html( $options['FMNNovember'] ) . '","' . esc_html( $options['FMNDecember'] ) . '"],' . "\n";
			echo '        monthesSNames: ["' . esc_html( $options['SMNJanuary'] ) . '","' . esc_html( $options['SMNFebruary'] ) . '","' . esc_html( $options['SMNMarch'] ) . '","' . esc_html( $options['SMNApril'] ) . '","' . esc_html( $options['SMNMay'] ) . '","' . esc_html( $options['SMNJune'] ) . '","' . esc_html( $options['SMNJuly'] ) . '","' . esc_html( $options['SMNAugust'] ) . '","' . esc_html( $options['SMNSeptember'] ) . '","' . esc_html( $options['SMNOctober'] ) . '","' . esc_html( $options['SMNNovember'] ) . '","' . esc_html( $options['SMNDecember'] ) . '"],' . "\n";
			echo '        daysFNames: ["' . esc_html( $options['FDNSunday'] ) . '","' . esc_html( $options['FDNMonday'] ) . '","' . esc_html( $options['FDNTuesday'] ) . '","' . esc_html( $options['FDNWednesday'] ) . '","' . esc_html( $options['FDNThursday'] ) . '","' . esc_html( $options['FDNFriday'] ) . '","' . esc_html( $options['FDNSaturday'] ) . '"],' . "\n";
			echo '        daysSNames: ["' . esc_html( $options['SDNSunday'] ) . '","' . esc_html( $options['SDNMonday'] ) . '","' . esc_html( $options['SDNTuesday'] ) . '","' . esc_html( $options['SDNWednesday'] ) . '","' . esc_html( $options['SDNThursday'] ) . '","' . esc_html( $options['SDNFriday'] ) . '","' . esc_html( $options['SDNSaturday'] ) . '"],' . "\n";
			echo '        };' . "\n";
			echo '    var langs = { Today:"' . esc_html( $options["Today"] ) . '", Cancel:"' . esc_html( $options["Cancel"] ) . '", Update:"' . esc_html( $options["Update"] ) . '", OK:"' . esc_html( $options["OK"] ) . '"};' . "\n";
			}
		else
			{
			// Of course, WordPress is probably fine so just use it.
			echo '        monthesFNames: ["' . __('January', 'schedule-posts-calendar') . '","' . __('February', 'schedule-posts-calendar') . '","' . __('March', 'schedule-posts-calendar') . '","' . __('April', 'schedule-posts-calendar') . '","' . __('May', 'schedule-posts-calendar') . '","' . __('June', 'schedule-posts-calendar') . '","' . __('July', 'schedule-posts-calendar') . '","' . __('August', 'schedule-posts-calendar') . '","' . __('September', 'schedule-posts-calendar') . '","' . __('October', 'schedule-posts-calendar') . '","' . __('November', 'schedule-posts-calendar') . '","' . __('December', 'schedule-posts-calendar') . '"],' . "\n";
			echo '        monthesSNames: ["' . __('Jan', 'schedule-posts-calendar') . '","' . __('Feb', 'schedule-posts-calendar') . '","' . __('Mar', 'schedule-posts-calendar') . '","' . __('Apr', 'schedule-posts-calendar') . '","' . __('May', 'schedule-posts-calendar') . '","' . __('Jun', 'schedule-posts-calendar') . '","' . __('Jul', 'schedule-posts-calendar') . '","' . __('Aug', 'schedule-posts-calendar') . '","' . __('Sep', 'schedule-posts-calendar') . '","' . __('Oct', 'schedule-posts-calendar') . '","' . __('Nov', 'schedule-posts-calendar') . '","' . __('Dec', 'schedule-posts-calendar') . '"],' . "\n";
			echo '        daysFNames: ["' . __('Sunday', 'schedule-posts-calendar') . '","' . __('Monday', 'schedule-posts-calendar') . '","' . __('Tuesday', 'schedule-posts-calendar') . '","' . __('Wednesday', 'schedule-posts-calendar') . '","' . __('Thursday', 'schedule-posts-calendar') . '","' . __('Friday', 'schedule-posts-calendar') . '","' . __('Saturday', 'schedule-posts-calendar') . '"],' . "\n";
			echo '        daysSNames: ["' . __('Sun', 'schedule-posts-calendar') . '","' . __('Mon', 'schedule-posts-calendar') . '","' . __('Tue', 'schedule-posts-calendar') . '","' . __('Wed', 'schedule-posts-calendar') . '","' . __('Thu', 'schedule-posts-calendar') . '","' . __('Fri', 'schedule-posts-calendar') . '","' . __('Sat', 'schedule-posts-calendar') . '"]' . "\n";
			echo '        };' . "\n";
			echo '    var langs = { Today:"' . __('Today', 'schedule-posts-calendar') . '", Cancel:"' . __('Cancel', 'schedule-posts-calendar') . '", Update:"' . __('Update', 'schedule-posts-calendar') . '", OK:"' . __('OK', 'schedule-posts-calendar') . '"};' . "\n";
			}
		}
	else
		{
		// If translation is disabled, we don't touch the dhmtlXCalendar object but we still need to return some
		// misc. strings for us to use.
		echo '    var langs = { Today:"Today", Cancel:"Cancel", Update:"Update", OK:"OK"};' . "\n";
		}

	// Finish off the function and close the script.
	echo '    return langs;' . "\n";
	echo '    }' . "\n";
	echo '</script>' . "\n";
	}

// Load the translations.
add_action( 'init', 'schedule_posts_calendar_init' );

function schedule_posts_calendar_init() {
	load_plugin_textdomain( 'schedule-posts-calendar', false, false );
}

// Time to register the .css and .js pages, if we need to of course ;)

// First find out if we're in a post/page list, in a post/page edit page or somewhere we don't care about.
$fname = SCP_Add_Calendar_Includes();

// If we're somewhere we care about, do the admin_init action.
if( $fname <> "" )
	{
	add_action( 'admin_init', $fname );

	add_action('admin_print_scripts', 'schedule_posts_calendar_lang' );
	}

// If we're in the post/page list, add the quick schedule menu items.
if( $fname == "schedule_posts_calendar_quick_schedule" )
{
	add_filter('post_row_actions', 'schedule_posts_calendar_link_row',10,2);
	add_filter('page_row_actions', 'schedule_posts_calendar_link_row',10,2);

	add_action('admin_print_scripts', 'schedule_posts_calendar_lang' );
}

// Now add the admin menu items
if ( is_admin() )
	{
	add_action( 'admin_menu', 'schedule_posts_calendar_admin', 1 );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'schedule_posts_calendar_plugin_actions', 10, 4);
	}


?>