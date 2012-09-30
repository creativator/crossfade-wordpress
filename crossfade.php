<?php
/*
Plugin Name: CrossFade Gallery
Plugin URI: http://creativator.net/en/projects/
Description: Image gallery for WordPress with classic crossfade effect
Version: 0.9.6
Author: Creativator Media
Author URI: http://creativator.net
*/

add_action('wp_head', 'crossfade_gallery_head');
add_action('init', 'crossfade_gallery_type' );
add_action('admin_menu', 'crossfade_admin_menu');

function crossfade_gallery()
{
	query_posts('post_type=cf_slider&orderby=menu_order&order=ASC');
	if (have_posts()):
		print '<ul class="crossfade_gallery">';
		while (have_posts()): the_post();
			print '<li>';
				if(has_post_thumbnail()):
					 the_post_thumbnail(array(get_option('cr_gallery_width',640),get_option('cr_gallery_height',480)));
				else:
					the_content();
				endif;
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
			'supports' => array(
				'editor',
				'thumbnail',
				'page-attributes',
			)
		)
	);
	
	add_theme_support('post-thumbnails', array('cf_slider'));
}

function crossfade_admin_menu() 
{
	add_submenu_page('options-general.php', __('CF Gallery', 'crossfade'), __('CF Gallery', 'crossfade'), 'administrator', __FILE__, 'cf_settings_page', plugin_dir_url( __FILE__ ).'/images/icon.png');
	add_action( 'admin_init', 'cf_settings' );
}


function cf_settings() 
{
	register_setting( 'cf-settings-group', 'cr_gallery_speed');
	register_setting( 'cf-settings-group', 'cr_gallery_width');
	register_setting( 'cf-settings-group', 'cr_gallery_height');
	register_setting( 'cf-settings-group', 'cr_gallery_thumbnails');
	register_setting( 'cf-settings-group', 'cr_gallery_tooltips');
	register_setting( 'cf-settings-group', 'cr_gallery_controls');
	register_setting( 'cf-settings-group', 'cr_gallery_stop_on_hover');
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
			<td><input name="cr_gallery_width" step="1" min="1" id="cr_gallery_width" value="<?php echo get_option('cr_gallery_width',640); ?>" class="small-text" type="number"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Gallery Height', 'crossfade') ?>:</th>
			<td><input name="cr_gallery_height" step="1" min="1" id="cr_gallery_height" value="<?php echo get_option('cr_gallery_height',480); ?>" class="small-text" type="number"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Change slider every * sec.', 'crossfade') ?>:</th>
			<td><input name="cr_gallery_speed" step="1" min="1" id="cr_gallery_speed" value="<?php echo get_option('cr_gallery_speed',5); ?>" class="small-text" type="number"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Enable thumbnails', 'crossfade') ?>:</th>
			<td><input type="checkbox" name="cr_gallery_thumbnails" id="cr_gallery_thumbnails" value="true" <?php if(get_option('cr_gallery_thumbnails',true)) print 'checked'; ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Enable tooltips', 'crossfade') ?>:</th>
			<td><input type="checkbox" name="cr_gallery_tooltips" id="cr_gallery_tooltips" value="true" <?php if(get_option('cr_gallery_tooltips',true)) print 'checked'; ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Enable controls', 'crossfade') ?>:</th>
			<td><input type="checkbox" name="cr_gallery_controls" id="cr_gallery_controls" value="true" <?php if(get_option('cr_gallery_controls',true)) print 'checked'; ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php print __('Stop on slide hover', 'crossfade') ?>:</th>
			<td><input type="checkbox" name="cr_gallery_stop_on_hover" id="cr_gallery_stop_on_hover" value="true" <?php if(get_option('cr_gallery_stop_on_hover',false)) print 'checked'; ?> /></td>
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
			$(".crossfade_gallery").crossFadeGallery({';
			
		if($auto = get_option('cr_gallery_speed')) print "auto:{$auto},";	
		if($width = get_option('cr_gallery_width')) print "width:{$width},";	
		if($height = get_option('cr_gallery_height')) print "height:{$height},";	
		if($stopOnHover = get_option('cr_gallery_stop_on_hover')) print "stopOnHover:{$stopOnHover},";	
		if(!$controls = get_option('cr_gallery_controls')) print "controls:false,";	
		if(!$tooltips = get_option('cr_gallery_tooltips')) print "tooltips:false,";	
		if(!$thumbnails = get_option('cr_gallery_thumbnails')) print "thumbnails:false,";	
	
	
	print '	});
		});
		</script>';
}
?>