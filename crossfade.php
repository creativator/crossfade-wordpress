<?php
/*
Plugin Name: CrossFade Gallery
Plugin URI: http://creativator.net/en/projects/
Description: Image gallery for WordPress with classic crossfade effect
Version: 0.9.3
Author: Creativator Media
Author URI: http://creativator.net
*/

add_action('wp_head', 'crossfade_gallery_head');
add_action( 'init', 'crossfade_gallery_type' );
add_action('admin_menu', 'crossfade_admin_menu');

function crossfade_gallery()
{
	query_posts('post_type=cf_slider&order=ASC');
	if (have_posts()):
		print '<ul class="crossfade_gallery">';
		while (have_posts()): the_post();
			print '<li>';
				the_content();
			print '</li>';
		endwhile;
		print '</ul>';
	endif; 
	wp_reset_query();
}

function crossfade_gallery_type() 
{
	register_post_type( 'cf_slider',
		array(
			'labels' => array(
				'name' => __('CF Gallery', 'crossfade'),
				'singular_name' => __('Slider', 'crossfade'),
				'add_new' => __('Add slider', 'crossfade'),
				'add_new_item' => __('Add new slider', 'crossfade'),
				'edit_item' => __('Edit slider', 'crossfade'),
			),
		'public' => true,
		'has_archive' => true,
		'menu_icon' =>  plugin_dir_url( __FILE__ ).'/images/icon.png',
		)
	);
}

function crossfade_admin_menu() 
{
	add_submenu_page('options-general.php', __('CF Gallery', 'crossfade'), __('CF Gallery', 'crossfade'), 'administrator', __FILE__, 'cf_settings_page', plugin_dir_url( __FILE__ ).'/images/icon.png');
	add_action( 'admin_init', 'cf_settings' );
}


function cf_settings() 
{
	register_setting( 'cf-settings-group', 'cr_gallery_speed' );
	register_setting( 'cf-settings-group', 'cr_gallery_width' );
	register_setting( 'cf-settings-group', 'cr_gallery_height' );
}

function cf_settings_page()
{ ?>
<div class="wrap">
	<h2><?php print __('CF Gallery Settings', 'crossfade')?></h2>
	<form id="template" method="post" action="options.php">
	<?php settings_fields( 'cf-settings-group' ); ?>
    <?php do_settings_fields( 'cf-settings-group',"" ); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php print __('Gallery Width', 'crossfade') ?>:</th>
			<td><input name="cr_gallery_width" step="1" min="1" id="cr_gallery_width" value="<?php echo get_option('cr_gallery_width'); ?>" class="small-text" type="number"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Gallery Height', 'crossfade') ?>:</th>
			<td><input name="cr_gallery_height" step="1" min="1" id="cr_gallery_height" value="<?php echo get_option('cr_gallery_height'); ?>" class="small-text" type="number"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Change slider every * sec.', 'crossfade') ?>:</th>
			<td><input name="cr_gallery_speed" step="1" min="1" id="cr_gallery_speed" value="<?php echo get_option('cr_gallery_speed'); ?>" class="small-text" type="number"></td>
		</tr>
	</table>
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	</form>
</div>	
<?php }

function crossfade_gallery_head()
{
	wp_enqueue_script('crossfade',plugin_dir_url( __FILE__ ).'/js/jquery.crossfade.js', array('jquery'));
	
	// Load user defined script in footer
	add_action( 'wp_footer', 'crossfade_inline_script', 999 );
}

function crossfade_inline_script()
{
	print '<script type="text/javascript">
		jQuery(document).ready(function($){
			$(".crossfade_gallery").crossFadeGallery({
				auto:'.get_option('cr_gallery_speed').',
				width:"'.get_option('cr_gallery_width').'px",
				height:"'.get_option('cr_gallery_height').'px",
				stopOnHover:false
			});
		});
		</script>';
}
?>