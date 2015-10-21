<?php 
/*
Template Name: Full Width App
*/ 

update_option('current_page_template','Full Width App');

?>
<?php 
global $wp_query;
$id = $wp_query->get_queried_object_id();
$sidebar = get_post_meta($id, "qode_show-sidebar", true);  

$enable_page_comments = false;
if(get_post_meta($id, "qode_enable-page-comments", true) == 'yes') {
	$enable_page_comments = true;
}

if(get_post_meta($id, "qode_page_background_color", true) != ""){
	$background_color = get_post_meta($id, "qode_page_background_color", true);
}else{
	$background_color = "";
}

$content_style_spacing = "";
if(get_post_meta($id, "qode_margin_after_title", true) != ""){
	if(get_post_meta($id, "qode_margin_after_title_mobile", true) == 'yes'){
		$content_style_spacing = "padding-top:".esc_attr(get_post_meta($id, "qode_margin_after_title", true))."px !important";
	}else{
		$content_style_spacing = "padding-top:".esc_attr(get_post_meta($id, "qode_margin_after_title", true))."px";
	}
}

if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
else { $paged = 1; }

?>

<!-- GET HEADER -->
	<?php get_header(); ?>

		<?php if(get_post_meta($id, "qode_page_scroll_amount_for_sticky", true)) { ?>
			<script>
			var page_scroll_amount_for_sticky = <?php echo get_post_meta($id, "qode_page_scroll_amount_for_sticky", true); ?>;
			</script>
		<?php } ?>

<!-- GET TITLE		 -->
			<?php get_template_part( 'title' ); ?>

<!-- REVSLIDER -->
		<?php $revslider = get_post_meta($id, "qode_revolution-slider", true);
		if (!empty($revslider)){ ?>
			<div class="q_slider"><div class="q_slider_inner">
			<?php echo do_shortcode($revslider); ?>
			</div></div>
		<?php } ?>

<!-- DIV full_width	 -->
	<div class="full_width"<?php if($background_color != "") { echo " style='background-color:". $background_color ."'";} ?>>
		<div class="full_width_inner" <?php qode_inline_style($content_style_spacing); ?>>

			<?php the_content(); ?>		

			<?php require_once ('includes/connection.php'); ?> 

			<?php require_once ('includes/main-app.php'); ?>


<!-- GET FOOTER	 -->
	<?php get_footer(); ?>