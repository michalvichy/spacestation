<?php

// enqueue the child theme stylesheet

Function wp_schools_enqueue_scripts() {

wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
wp_enqueue_style( 'childstyle' );
// wp_register_style( 'tour_popup_stylesheet', get_stylesheet_directory_uri() . '/css/tour_popup_stylesheet.css'  );
// wp_enqueue_style( 'tour_popup_stylesheet' );

wp_enqueue_script("default", get_stylesheet_directory_uri()."/js/default.js",array(),false,true);
wp_enqueue_script("corner", "http://malsup.github.io/jquery.corner.js",array(),false,true);
wp_enqueue_script("cookie", get_stylesheet_directory_uri()."/js/jquery.cookie.js",array(),false,true);
wp_enqueue_script("json3", get_stylesheet_directory_uri()."/js/json3.min.js",array(),false,true);
wp_enqueue_script("SuperCookie", get_stylesheet_directory_uri()."/js/jquery.SuperCookie.js",array(),false,true);
wp_enqueue_script("tour_popup_default", get_stylesheet_directory_uri()."/js/tour_popup_default.js",array(),false,true);

	/**
	  * LOAD SCRIPTS BASED ON TEMPLATE NAME
	  */

	// IF CURRENT TEMPLATE IS APP

	if(get_option('current_page_template') === 'Full Width App'){

		wp_register_style( 'pb-style', get_stylesheet_directory_uri() . '/css/pb-style.css'  );
		wp_enqueue_style( 'pb-style' );
	
		wp_enqueue_script("masonry", "https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js",array(),false,true);
		wp_enqueue_script("imagesLoaded", "http://imagesloaded.desandro.com/imagesloaded.pkgd.min.js",array(),false,true);
		wp_enqueue_script("PB_app", get_stylesheet_directory_uri()."/js/PB_app.js",array(),false,true);
	
	}
	
	// IF CURRENT TEMPLATE IS LISTING

	if(get_option('current_page_template') === 'Full Width Listing'){

		wp_register_style( 'pb-style', get_stylesheet_directory_uri() . '/css/pb-style.css'  );
		wp_enqueue_style( 'pb-style' );
		wp_register_style( 'px-video', get_stylesheet_directory_uri() . '/css/px-video.css'  );
		wp_enqueue_style( 'px-video' );
		wp_register_style( 'fontello', get_stylesheet_directory_uri() . '/css/fontello.css'  );
		wp_enqueue_style( 'fontello' );
	
		wp_enqueue_script("tabs", site_url()."/wp-includes/js/jquery/ui/tabs.min.js",array(),false,true);
		wp_enqueue_script("jcarousel", "https://cdnjs.cloudflare.com/ajax/libs/jcarousel/0.3.4/jquery.jcarousel.min.js",array(),false,true);
		wp_enqueue_script("jcarouselResponsive", get_stylesheet_directory_uri()."/js/jcarousel.responsive.js",array(),false,true);
		wp_enqueue_script("px-video", get_stylesheet_directory_uri()."/js/px-video.js",array(),false,true);
		wp_enqueue_script("PB_listing", get_stylesheet_directory_uri()."/js/PB_listing.js",array(),false,true);

	}

}
add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);


// get_childTheme_url
function get_childTheme_url() {
    return dirname( get_bloginfo('stylesheet_url') );
}



/* Portfolio list shortcode TUFF*/

if (!function_exists('portfolio_list_tuff')) {

    function portfolio_list_tuff($atts, $content = null) {

        global $wp_query;
        global $portfolio_project_id;
        global $qode_options_proya;
        $portfolio_qode_like = "on";
        if (isset($qode_options_proya['portfolio_qode_like'])) {
            $portfolio_qode_like = $qode_options_proya['portfolio_qode_like'];
        }

        // SHORTCODE ARGUMENTS
        $args = array(
            "type"                  		    => "standard",
            "spacing"						    => "",
            "hover_type_standard"               => "default",
            "hover_type_text_on_hover_image"    => "default",
            "hover_type_text_before_hover"      => "default",
            "hover_type_masonry"                => "default",
            "box_border"            		    => "",
            "box_background_color"  		    => "",
            "box_border_color"      		    => "",
            "box_border_width"      		    => "",
            "columns"               		    => "3",
            "portfolio_loading_type" 		    => "",
            "portfolio_loading_type_masonry"    => "",
            "grid_size"               		    => "",
            "image_size"            		    => "",
            "overlay_background_color"          => "",
            "order_by"              		    => "date",
            "order"                 		    => "ASC",
            "number"                		    => "-1",
            "filter"                		    => "no",
            "filter_color"          		    => "",
            "lightbox"              		    => "yes",
            "view_button"           		    => "yes",
            "category"              		    => "",
            "selected_projects"     		    => "",
            "show_load_more"        		    => "yes",
            "show_title"             		    => "",
            "title_tag"             		    => "h5",
            "title_color"                       => "",
            "title_font_size"                   => "",
            "show_categories"                   => "",
            "category_color"                    => "",
            "portfolio_separator"   			=> "",
            "separator_color"                   => "",
            "text_align"			            => ""
        );

        extract(shortcode_atts($args, $atts));

        $headings_array = array('h2', 'h3', 'h4', 'h5', 'h6');

        //get correct heading value. If provided heading isn't valid get the default one
        $title_tag = (in_array($title_tag, $headings_array)) ? $title_tag : $args['title_tag'];

        $html = "";

	// portfolio layout / style classes
        $_type_class = '';
        $_portfolio_space_class = '';
        $_portfolio_masonry_with_space_class = '';
        if ($type == "hover_text") {
            $_type_class = " hover_text";
            $_portfolio_space_class = "portfolio_with_space";
        } elseif ($type == "standard" || $type == "masonry_with_space"){
            $_type_class = " standard";
            $_portfolio_space_class = "portfolio_with_space";
            if($type == "masonry_with_space"){
                $_portfolio_masonry_with_space_class = ' masonry_with_space';
            }
        } elseif ($type == "standard_no_space"){
            $_type_class = " standard_no_space";
            $_portfolio_space_class = "portfolio_no_space";
        } elseif ($type == "hover_text_no_space"){
            $_type_class = " hover_text no_space";
            $_portfolio_space_class = "portfolio_no_space";
        }

        $_portfolio_masonry_with_space_class = '';
        if ($type == "hover_text") {
            $_type_class = " hover_text";
            $_portfolio_space_class = "portfolio_with_space portfolio_with_hover_text";
        } elseif ($type == "standard" || $type == "masonry_with_space" || $type == "masonry_with_space_without_description"){
            $_type_class = " standard";
            $_portfolio_space_class = "portfolio_with_space portfolio_standard";
            if($type == "masonry_with_space" || $type == "masonry_with_space_without_description"){
                $_portfolio_masonry_with_space_class = ' masonry_with_space';

                if($type == "masonry_with_space_without_description") {
                    $_portfolio_masonry_with_space_class .= ' masonry_with_space_only_image';
                }
            }
        } elseif ($type == "standard_no_space"){
            $_type_class = " standard_no_space";
            $_portfolio_space_class = "portfolio_no_space portfolio_standard";
        } elseif ($type == "hover_text_no_space"){
            $_type_class = " hover_text no_space";
            $_portfolio_space_class = "portfolio_no_space portfolio_with_hover_text";
        }
    // 
    // article layout / style classes
        $article_style = "";
        if (($type == "masonry_with_space" || $type == 'masonry_with_space_without_description') && $spacing !== ''){
            $article_style .= "padding: 0 " . intval($spacing)/2 . "px;";
            $article_style .= "margin-bottom: ".$spacing."px !important;";
        }
        $article_style = "style='".$article_style."'";

        $portfolio_box_style = "";
        $portfolio_description_class = "";
        if($box_border == "yes" || $box_background_color != ""){

            $portfolio_box_style .= "style=";
            if($box_border == "yes"){
                $portfolio_box_style .= "border-style:solid;";
                if($box_border_color != "" ){
                    $portfolio_box_style .= "border-color:" . $box_border_color . ";";
                }
                if($box_border_width != "" ){
                    $portfolio_box_style .= "border-width:" . $box_border_width . "px;";
                }
            }
            if($box_background_color != ""){
                $portfolio_box_style .= "background-color:" . $box_background_color . ";";
            }
            $portfolio_box_style .= "'";

        }

        if($text_align !== '') {
            $portfolio_description_class .= 'text_align_'.$text_align;
        }

        $portfolio_separator_aignment = "center";
        if($text_align != ""){
            $portfolio_separator_aignment = $text_align;
        }

    // adding portfolio loading
        $portfolio_loading_class = '';
        if($portfolio_loading_type !== '' && (!in_array($type, array('masonry_with_space', 'masonry','masonry_with_space_without_description'))) ) {
            $portfolio_loading_class = $portfolio_loading_type;
        }
        elseif($portfolio_loading_type_masonry !== ''){
            $portfolio_loading_class = $portfolio_loading_type_masonry;
        }

        $filter_style = "";
        if($filter_color != ""){
            $filter_style = " style='";
            $filter_style .= "color:$filter_color";
            $filter_style .= "'";
        }

    // adding hover type
        $hover_type = "";
        if ($type == 'standard' || $type == 'standard_no_space' || $type == 'masonry_with_space') {
            $hover_type = $hover_type_standard;
        }
        if ($type == 'hover_text' || $type == 'hover_text_no_space' || $type == 'masonry_with_space_without_description') {
            $hover_type = $hover_type_text_on_hover_image;
        }
        if ($type == 'masonry') {
            $hover_type = $hover_type_masonry;
        }

        $overlay_styles= array();
        if($hover_type !== 'default' && $overlay_background_color !== '') {
            $overlay_styles[] = 'background-color: '.$overlay_background_color;
        }
    // title / category / separator styles
        $title_styles = array();
        if($title_color !== '') {
            $title_styles[] = 'color: '.$title_color;
        }

        if($title_font_size !== '') {
            $title_styles[] = 'font-size: '.$title_font_size.'px';
        }

        $category_styles = array();
        if($category_color !== '') {
            $category_styles[] = 'color: '.$category_color;
        }

        $separator_styles = array();
        if($separator_color !== '') {
            $separator_styles[] = 'background-color: '.$separator_color;
        }

		if($columns == ""){
			$columns = '3';
		}
	//
        $show_description_box = $show_title == 'no' && $show_categories == 'no' ? false : true;
    
    //////// IF NOT MASONRY
        if($type != 'masonry') {
            $html .= "<div class='projects_holder_outer v$columns $_portfolio_space_class $_portfolio_masonry_with_space_class'>";
            if ($filter == "yes") {

                if($type == 'masonry_with_space' || $type == 'masonry_with_space_without_description'){
                    $html .= "<div class='filter_outer'>";
                    $html .= "<div class='filter_holder'>
						<ul>
						<li class='filter' data-filter='*'><span>" . __('All', 'qode') . "</span></li>";
                    if ($category == "") {
                        $args = array(
                            'parent' => 0
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    } else {
                        $top_category = get_term_by('slug', $category, 'portfolio_category');
                        $term_id = '';
                        if (isset($top_category->term_id))
                            $term_id = $top_category->term_id;
                        $args = array(
                            'parent' => $term_id
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    }
                    foreach ($portfolio_categories as $portfolio_category) {
                        $html .= "<li class='filter' data-filter='.portfolio_category_$portfolio_category->term_id'><span>$portfolio_category->name</span>";
                        $args = array(
                            'child_of' => $portfolio_category->term_id
                        );
                        $html .= '</li>';
                    }
                    $html .= "</ul></div>";
                    $html .= "</div>";

                }else{

                    $html .= "<div class='filter_outer'>";
                    $html .= "<div class='filter_holder'>
                            <ul>
                            <li class='filter' data-filter='all'><span". $filter_style .">" . __('All', 'qode') . "</span></li>";
                    if ($category == "") {
                        $args = array(
                            'parent' => 0
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    } else {
                        $top_category = get_term_by('slug', $category, 'portfolio_category');
                        $term_id = '';
                        if (isset($top_category->term_id))
                            $term_id = $top_category->term_id;
                        $args = array(
                            'parent' => $term_id
                        );
                        $portfolio_categories = get_terms('portfolio_category', $args);
                    }
                    foreach ($portfolio_categories as $portfolio_category) {
                        $html .= "<li class='filter' data-filter='portfolio_category_$portfolio_category->term_id'><span". $filter_style .">$portfolio_category->name</span>";
                        $args = array(
                            'child_of' => $portfolio_category->term_id
                        );
                        $html .= '</li>';
                    }
                    $html .= "</ul></div>";
                    $html .= "</div>";
                }
            }

            $thumb_size_class = "";
        	//get proper image size
            switch($image_size) {
                case 'landscape':
                    $thumb_size_class = 'portfolio_landscape_image';
                    break;
                case 'portrait':
                    $thumb_size_class = 'portfolio_portrait_image';
                    break;
                case 'square':
                    $thumb_size_class = 'portfolio_square_image';
                    break;
                default:
                    $thumb_size_class = 'portfolio_full_image';
                    break;
            }

            $html .= "<div class='projects_holder portfolio_main_holder clearfix v$columns$_type_class $thumb_size_class $portfolio_loading_class'>\n";
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }

            if ($category == "") {
                $args = array(
                    'post_type' => 'post',
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            } else {
                $args = array(
                    'post_type' => 'post',
                    'portfolio_category' => $category,
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            }
            $project_ids = null;
            if ($selected_projects != "") {
                $project_ids = explode(",", $selected_projects);
                $args['post__in'] = $project_ids;
            }
            query_posts($args);
            if (have_posts()) : while (have_posts()) : the_post();
                $terms = wp_get_post_terms(get_the_ID(), 'portfolio_category');
                $html .= "<article class='mix ";
                foreach ($terms as $term) {
                    $html .= "portfolio_category_$term->term_id ";
                }

                $title = get_the_title();
                $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size

                if(get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true) != ""){
                    $large_image = get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true);
                } else {
                    $large_image = $featured_image_array[0];
                }

                $slug_list_ = "pretty_photo_gallery";

                //get proper image size
                switch($image_size) {
                    case 'landscape':
                        $thumb_size = 'portfolio-landscape';
                        break;
                    case 'portrait':
                        $thumb_size = 'portfolio-portrait';
                        break;
                    case 'square':
                        $thumb_size = 'portfolio-square';
                        break;
                    default:
                        $thumb_size = 'full';
                        break;
                }

                if($type == "masonry_with_space" || $type == "masonry_with_space_without_description"){
                    $thumb_size = 'portfolio_masonry_with_space';
                }

                $custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
                $portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();

                if(get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true) != ""){
                    $custom_portfolio_link_target = get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true);
                } else {
                    $custom_portfolio_link_target = '_blank';
                }

                $target = $custom_portfolio_link != "" ? $custom_portfolio_link_target : '_self';

                $html .="' ";
                $html .= $article_style;
                $html .= ">";

                if($hover_type == 'default') {
                    $html .= "<div class='image_holder'>";
                    $html .= "<a class='portfolio_link_for_touch' href='".$portfolio_link."' target='".$target."'>";
                    $html .= "<span class='image'>";
                    $html .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
                    $html .= "</span>";
                    $html .= "</a>";

                    if ($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") {
                        $html .= "<span class='text_holder'>";
                        $html .= "<span class='text_outer'>";
                        $html .= "<span class='text_inner'>";
                        $html .= "<span class='feature_holder'>";
                        if($lightbox == "yes" || $portfolio_qode_like == "on" || $view_button !== "no"){
                            $html .= '<span class="feature_holder_icons">';
                            if ($lightbox == "yes") {
                                $html .= "<a class='lightbox qbutton small white' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'>" . __('zoom', 'qode'). "</a>";
                            }
                            if($view_button !== "no"){
                                $html .= "<a class='preview qbutton small white' href='" . $portfolio_link . "' target='".$target."'>" . __('view', 'qode'). "</a>";
                            }
                            if ($portfolio_qode_like == "on") {
                                $html .= "<span class='portfolio_like qbutton small white'>";
                                $portfolio_project_id = get_the_ID();

                                if (function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list();
                                }
                                $html .= "</span>";
                            }
                            $html .= "</span>";
                        }
                        $html .= "</span></span></span></span>";


                    } else if ($type == "hover_text" || $type == "hover_text_no_space" || $type = 'masonry_with_space_without_description') {

                        $html .= "<span class='text_holder'>";
                        $html .= "<span class='text_outer'>";
                        $html .= "<span class='text_inner'>";
                        $html .= '<div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner">';

                        if($show_title !== 'no') {
                            $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
                        }

                        if($portfolio_separator == "yes"){
                            $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        }

                        if($show_categories !== 'no') {
                            $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>';
                            $k = 1;
                            foreach ($terms as $term) {
                                $html .= "$term->name";
                                if (count($terms) != $k) {
                                    $html .= ', ';
                                }
                                $k++;
                            }
                            $html .= '</span>';
                        }

                        $html .= '</div></div>';
                        $html .= "<span class='feature_holder'>";
                        if($lightbox == "yes" || $portfolio_qode_like == "on" || $view_button !== "no"){
                            $html .= '<span class="feature_holder_icons">';
                            if ($lightbox == "yes") {
                                $html .= "<a class='lightbox qbutton small white' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'>" . __('zoom', 'qode'). "</a>";
                            }
                            if($view_button !== "no"){
                                $html .= "<a class='preview qbutton small white' href='" . $portfolio_link . "' target='".$target."'>" . __('view', 'qode'). "</a>";
                            }
                            if ($portfolio_qode_like == "on") {
                                $html .= "<span class='portfolio_like qbutton small white'>";
                                $portfolio_project_id = get_the_ID();

                                if (function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list();
                                }
                                $html .= "</span>";
                            }
                            $html .= "</span>";
                        }
                        $html .= "</span></span></span></span>";


                    }
                    $html .= "</div>";
                    if (($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") && $show_description_box) {
                        $html .= "<div class='portfolio_description ".$portfolio_description_class."'". $portfolio_box_style .">";

                        if($show_title !== 'no') {
                            $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
                        }

                        if($portfolio_separator == "yes"){
                            $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        }

                        if($show_categories !== 'no') {
                            $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>';
                            $k = 1;
                            foreach ($terms as $term) {
                                $html .= "$term->name";
                                if (count($terms) != $k) {
                                    $html .= ', ';
                                }
                                $k++;
                            }
                            $html .= '</span>';
                        }

                        $html .= '</div>';
                    }

                } else {
                    $category_html = "";
                    $k = 1;
                    foreach ($terms as $term) {
                        $category_html .= "$term->name";
                        if (count($terms) != $k) {
                            $category_html .= ' / ';
                        }
                        $k++;
                    }

                    $show_icons = "yes";
                    // disable icons on this hover type
                    if ($hover_type == 'cursor_change_hover' || $hover_type == 'thin_plus_only' || $hover_type == 'split_up') {
                        $show_icons = "no";
                    }

                    $disable_link = 'no';
                    // disable link if icons are shown for these hover type
                    if (($hover_type == 'subtle_vertical_hover' || $hover_type == 'image_subtle_rotate_zoom_hover' || $hover_type == 'image_text_zoom_hover') && $show_icons == 'yes') {
                        $disable_link = "yes";
                    }

                    $html .= '<div class="item_holder ' . $hover_type . '">';

                    switch ($hover_type) {
                        case 'subtle_vertical_hover':
                        case 'image_subtle_rotate_zoom_hover':
                        case 'image_text_zoom_hover':
                        case 'thin_plus_only':
                        case 'cursor_change_hover':
                            $html .= '<div class="text_holder">';
                            $html .= '<div class="text_holder_outer">';
                            $html .= '<div class="text_holder_inner">';
                            if($hover_type == 'thin_plus_only'){
                                $html .= '<span class="thin_plus_only_icon">+</span>';
                            }

                            elseif (in_array($type, array('hover_text', 'hover_text_no_space', 'masonry_with_space_without_description'))) {
                                if($show_title !== 'no') {
                                    $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                                }

                                if($portfolio_separator == "yes") {
                                    $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                                }

                                if($show_categories !== 'no') {
                                    $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                                }
                            }

                            if($show_icons == 'yes') {
                                $html .= '<div class="icons_holder">';

                                if($lightbox == "yes") {
                                    $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                }

                                if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list('icon');
                                }

                                if($view_button !== "no") {
                                    $html .= '<a class="preview" title="'.__('Go to Project', 'qode').'" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                }

                                $html .= '</div>'; // icons_holder
                            }

                            $html .= '</div>'; // text_holder_inner
                            $html .= '</div>';  // text_holder_outer
                            $html .= '</div>'; // text_holder

                            break;
                        case 'slow_zoom':
                        case 'split_up':

                            if (in_array($type, array('hover_text', 'hover_text_no_space', 'masonry_with_space_without_description'))) {
                                $html .= '<div class="text_holder">';
                                $html .= '<div class="text_holder_outer">';
                                $html .= '<div class="text_holder_inner">';

                                if($show_title !== 'no') {
                                    $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                                }

                                if($portfolio_separator == "yes") {
                                    $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                                }

                                if($show_categories !== 'no') {
                                    $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                                }

                                $html .= '</div>'; //text_holder_inner
                                $html .= '</div>';  // text_holder_outer
                                $html .= '</div>'; // text_holder
                            }

                            if ($show_icons == 'yes') {
                                $html .= '<div class="icons_holder">';

                                if($lightbox == "yes") {
                                    $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                }

                                if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list('icon');
                                }

                                if($view_button !== "no") {
                                    $html .= '<a class="preview" title="Go to Project" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                }

                                $html .= '</div>';  // icons_holder
                            }
                            break;
                    }

                    if($disable_link == 'no') {
                        $html .= '<a class="portfolio_link_class" title="' . $title . '" href="' . $portfolio_link . '"></a>';
                    }

                    $html .= '<div '.qode_get_inline_style($overlay_styles).' class="portfolio_shader"></div>';
                    $html .= '<div class="image_holder">';
                    $html .= '<span class="image">';
                    $html .= get_the_post_thumbnail(get_the_ID(), $thumb_size);
                    $html .= '</span>';
                    $html .= '</div>'; // close image_holder
                    $html .= '</div>'; // close item_holder
                    // portfolio description start

                    if ($type == "standard" || $type == "standard_no_space" || $type == "masonry_with_space") {
                        $html .= "<div class='portfolio_description " . $portfolio_description_class . "' ". $portfolio_box_style .">";

                        if($show_title !== 'no') {
                            $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" target="' . $target . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                        }

                        if($portfolio_separator == "yes") {
                            $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        }

                        if($show_categories !== 'no') {
                            $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                        }

                        $html .= '</div>'; // close portfolio_description
                    }
                }

                $html .= "</article>\n";

            endwhile;

                $i = 1;
                while ($i <= $columns) {
                    $i++;
                    if ($columns != 1) {
                        $html .= "<div class='filler'></div>\n";
                    }
                }

            else:
                ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'qode'); ?></p>
            <?php
            endif;


            $html .= "</div>";
            if (get_next_posts_link()) {
                if ($show_load_more == "yes" || $show_load_more == "") {
                    $html .= '<div class="portfolio_paging"><span rel="' . $wp_query->max_num_pages . '" class="load_more">' . get_next_posts_link(__('Show more', 'qode')) . '</span></div>';
                    $html .= '<div class="portfolio_paging_loading"><a href="javascript: void(0)" class="qbutton">'.__('Loading...', 'qode').'</a></div>';
                }
            }
            $html .= "</div>";
            wp_reset_query();
        } 
    /////// IF MASONRY
        else {
            if ($filter == "yes") {

                $html .= "<div class='filter_outer'>";
                $html .= "<div class='filter_holder'>
						<ul>
						<li class='filter' data-filter='*'><span>" . __('All', 'qode') . "</span></li>";
                if ($category == "") {
                    $args = array(
                        'parent' => 0
                    );
                    $portfolio_categories = get_terms('portfolio_category', $args);
                } else {
                    $top_category = get_term_by('slug', $category, 'portfolio_category');
                    $term_id = '';
                    if (isset($top_category->term_id))
                        $term_id = $top_category->term_id;
                    $args = array(
                        'parent' => $term_id
                    );
                    $portfolio_categories = get_terms('portfolio_category', $args);
                }
                foreach ($portfolio_categories as $portfolio_category) {
                    $html .= "<li class='filter' data-filter='.portfolio_category_$portfolio_category->term_id'><span>$portfolio_category->name</span>";
                    $args = array(
                        'child_of' => $portfolio_category->term_id
                    );
                    $html .= '</li>';
                }
                $html .= "</ul></div>";
                $html .= "</div>";
            }

            $grid_number_of_columns = "gs5";
            if($grid_size == 4){
                $grid_number_of_columns = "gs4";
            }
            
            $html .= "<div class='projects_masonry_holder portfolio_main_holder ". $grid_number_of_columns ." ".$portfolio_loading_class."'>";
            
            // PAGED
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }

            // CATEGORY
            if ($category == "") {
                $args = array(
                    'post_type' => 'post',
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            } else {
                $args = array(
                    'post_type' => 'post',
                    'category_name' => $category,
                    'orderby' => $order_by,
                    'order' => $order,
                    'posts_per_page' => $number,
                    'paged' => $paged
                );
            }


            $project_ids = null;
            if ($selected_projects != "") {
                $project_ids = explode(",", $selected_projects);
                $args['post__in'] = $project_ids;
            }
            query_posts($args);
            if (have_posts()) : while (have_posts()) : the_post();
                $terms = wp_get_post_terms(get_the_ID(), 'category');
                if(get_field('post_layout')){
                	$post_layout = get_field('post_layout');
            	}else{
            		$post_layout = "rectangle_landscape";
            	}

                $featured_image_array = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); //original size

                if(get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true) != ""){
                    $large_image = get_post_meta(get_the_ID(), 'qode_portfolio-lightbox-link', true);
                } else {
                    $large_image = $featured_image_array[0];
                }

                $custom_portfolio_link = get_post_meta(get_the_ID(), 'qode_portfolio-external-link', true);
                $portfolio_link = $custom_portfolio_link != "" ? $custom_portfolio_link : get_permalink();

                if(get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true) != ""){
                    $custom_portfolio_link_target = get_post_meta(get_the_ID(), 'qode_portfolio-external-link-target', true);
                } else {
                    $custom_portfolio_link_target = '_blank';
                }

                $target = $custom_portfolio_link != "" ? $custom_portfolio_link_target : '_self';

                $masonry_size = "default";
                $masonry_size =  get_post_meta(get_the_ID(), "qode_portfolio_type_masonry_style", true);
                $image_size="";
                if($masonry_size == "large_width"){
                    $image_size = "portfolio_masonry_wide";
                }elseif($masonry_size == "large_height"){
                    $image_size = "portfolio_masonry_tall";
                }elseif($masonry_size == "large_width_height"){
                    $image_size = "portfolio_masonry_large";
                } else{
                    $image_size = "portfolio_masonry_regular";
                }

                if($type == "masonry_with_space"){
                    $image_size = "portfolio_masonry_with_space";
                }

                $slug_list_ = "pretty_photo_gallery";
                $title = get_the_title();
                $html .= "<article class='portfolio_masonry_item ";
                foreach ($terms as $term) {
                    $html .= "portfolio_category_$term->term_id ";
                }
                $html .=" " . $masonry_size;
                $html .=" " . $post_layout;
                $html .="'>";

                //if $hover_type == 'default'
                if($hover_type == 'default') {
                    $html .= "<div class='image_holder'>";
                    $html .= "<a class='portfolio_link_for_touch' href='".$portfolio_link."' target='".$target."'>";
                    $html .= "<span class='image'>";
                    $html .= get_the_post_thumbnail(get_the_ID(), $image_size);
                    $html .= "</span>";
                    $html .= "</a>";
                    $html .= "<span class='text_holder'>";
                    $html .= "<span class='text_outer'>";
                    $html .= "<span class='text_inner'>";
                    $html .= '<div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner">';

                    if($show_categories !== 'no') {
                        $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>';
                        $k = 1;
                        foreach ($terms as $term) {
                            $html .= "$term->name";
                            if (count($terms) != $k) {
                                $html .= ', ';
                            }
                            $k++;
                        }
                        $html .= '</span>';
                    }

                    if($show_title !== 'no') {
                        $html .= '<'.$title_tag.' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).' target="'.$target.'">' . get_the_title() . '</a></'.$title_tag.'>';
                    }

                    if($portfolio_separator == "yes"){
                        $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                        $html .='<div>'.get_the_excerpt().'</div>';
                    }

                    

                    $html .= '</div></div>';
                    if($lightbox == "yes" || $portfolio_qode_like == "on" || $view_button !== "no"){
                        $html .= "<span class='feature_holder'>";

                        $html .= '<span class="feature_holder_icons">';
                        if ($lightbox == "yes") {
                            $html .= "<a class='lightbox qbutton small white' title='" . $title . "' href='" . $large_image . "' data-rel='prettyPhoto[" . $slug_list_ . "]'>" . __('zoom', 'qode'). "</a>";
                        }
                        if($view_button !== "no"){
                            $html .= "<a class='preview qbutton small white' href='" . $portfolio_link . "' target='".$target."'>" . __('view', 'qode'). "</i></a>";
                        }
                        if ($portfolio_qode_like == "on") {
                            $html .= "<span class='portfolio_like qbutton small white'>";
                            $portfolio_project_id = get_the_ID();

                            if (function_exists('qode_like_portfolio_list')) {
                                $html .= qode_like_portfolio_list();
                            }
                            $html .= "</span>";
                        }
                        $html .= "</span>";

                        $html .= "</span>";
                    }
                    $html .= "</span></span></span>";
                    $html .= "</div>";
                } 
                // if $hover_type not 'default'
                else {
                    $category_html = "";
                    $k = 1;
                    foreach ($terms as $term) {
                        $category_html .= "$term->name";
                        if (count($terms) != $k) {
                            $category_html .= ' / ';
                        }
                        $k++;
                    }

                    $show_icons = "yes";
                    // disable icons on this hover type
                    if ($hover_type == 'cursor_change_hover' || $hover_type == 'thin_plus_only' || $hover_type == 'split_up') {
                        $show_icons = "no";
                    }

                    $disable_link = 'no';
                    // disable link if icons are shown for these hover type
                    if (($hover_type == 'subtle_vertical_hover' || $hover_type == 'image_subtle_rotate_zoom_hover' || $hover_type == 'image_text_zoom_hover') && $show_icons == 'yes') {
                        $disable_link = "yes";
                    }

                    $html .= '<div class="item_holder ' . $hover_type . '">';

                    switch ($hover_type) {
                        case 'subtle_vertical_hover':
                        case 'image_subtle_rotate_zoom_hover':
                        case 'cursor_change_hover':
                        case 'image_text_zoom_hover':
                        case 'thin_plus_only':
                            if ( $show_icons == 'yes' || $hover_type == 'thin_plus_only' || $hover_type = 'cursor_change_hover') {
                                $html .= '<div class="text_holder">';
                                $html .= '<div class="text_holder_outer">';
                                $html .= '<div class="text_holder_inner">';
                                if($hover_type == 'thin_plus_only') {
                                    $html .= '<span class="thin_plus_only_icon">+</span>';
                                } else {
                                    if($show_title !== 'no') {
                                        $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                                    }

                                    if($portfolio_separator == "yes") {
                                        $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                                    }

                                    if($show_categories !== 'no') {
                                        $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                                    }

                                    if ($show_icons == 'yes') {
                                        $html .= '<div class="icons_holder">';

                                        if($lightbox == "yes") {
                                            $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                        }

                                        if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                            $html .= qode_like_portfolio_list('icon');
                                        }

                                        if($view_button !== "no") {
                                            $html .= '<a class="preview" title="Go to Project" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                        }

                                        $html .= '</div>'; // icons_holder
                                    }
                                }
                                $html .= '</div>'; // text_holder_inner
                                $html .= '</div>';  // text_holder_outer
                                $html .= '</div>'; // text_holder
                            }

                            break;
                        case 'slow_zoom':
                        case 'split_up':
                            $html .= '<div class="text_holder">';
                            $html .= '<div class="text_holder_outer">';
                            $html .= '<div class="text_holder_inner">';

                            if($show_title !== 'no') {
                                $html .= '<' . $title_tag . ' class="portfolio_title"><a href="' . $portfolio_link . '" '.qode_get_inline_style($title_styles).'>' . get_the_title() . '</a></' . $title_tag . '>';
                            }

                            if($portfolio_separator == "yes") {
                                $html .= '<div '.qode_get_inline_style($separator_styles).' class="portfolio_separator separator  small ' . $portfolio_separator_aignment . '"></div>';
                            }

                            if($show_categories !== 'no') {
                                $html .= '<span class="project_category" '.qode_get_inline_style($category_styles).'>' . $category_html . '</span>';
                            }

                            $html .= '</div>'; //text_holder_inner
                            $html .= '</div>'; // text_holder_outer
                            $html .= '</div>';  // text_holder
                            if ($show_icons == "yes") {
                                $html .= '<div class="icons_holder">';

                                if($lightbox == "yes") {
                                    $html .= '<a class="portfolio_lightbox" title="' . $title . '" href="' . $large_image . '" data-rel="prettyPhoto[' . $slug_list_ . ']" rel="prettyPhoto[' . $slug_list_ . ']"></a>';
                                }

                                if ($portfolio_qode_like == "on" && function_exists('qode_like_portfolio_list')) {
                                    $html .= qode_like_portfolio_list('icon');
                                }

                                if($view_button !== "no") {
                                    $html .= '<a class="preview" title="Preview" href="' . $portfolio_link . '" data-type="portfolio_list" target="' . $target . '" ></a>';
                                }

                                $html .= '</div>';  // icons_holder
                            }

                            break;
                    }

                    if($disable_link == 'no') {
                        $html .= '<a class="portfolio_link_class" title="' . $title . '" href="' . $portfolio_link . '"></a>';
                    }

                    $html .= '<div '.qode_get_inline_style($overlay_styles).' class="portfolio_shader"></div>';
                    $html .= '<div class="image_holder">';
                    $html .= '<span class="image">';
                    $html .= get_the_post_thumbnail(get_the_ID(), $image_size);
                    $html .= '</span>';
                    $html .= '</div>'; // close text_holder
                    $html .= '</div>'; // close item_holder
                }

                $html .= "</article>";

            endwhile;
            else:
                ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'qode'); ?></p>
            <?php
            endif;
            wp_reset_query();
            $html .= "</div>";
        }
        return $html;
    }

}
add_shortcode('portfolio_list_tuff', 'portfolio_list_tuff');

/* Social Share shortcode TUFF */

if (!function_exists('social_share_tuff')) {
    function social_share_tuff($atts, $content = null) {
        global $qode_options_proya;
        if(isset($qode_options_proya['twitter_via']) && !empty($qode_options_proya['twitter_via'])) {
            $twitter_via = " via " . $qode_options_proya['twitter_via'] . " ";
        } else {
            $twitter_via =  "";
        }
        if(isset($_SERVER["https"])) {
            $count_char = 23;
        } else{
            $count_char = 22;
        }
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        $html = "";
        if(isset($qode_options_proya['enable_social_share']) && $qode_options_proya['enable_social_share'] == "yes") {
            $post_type = get_post_type();
            if(isset($qode_options_proya["post_types_names_$post_type"])) {
                if($qode_options_proya["post_types_names_$post_type"] == $post_type) {
                    if($post_type == "portfolio_page") {
                        $html .= '<div class="portfolio_share">';
                    } elseif($post_type == "post") {
                        $html .= '<div class="blog_share">';
                    } elseif($post_type == "page") {
                        $html .= '<div class="page_share">';
                    }
                    $html .= '<div class="social_share_holder">';
                    $html .= '<a href="javascript:void(0)" target="_self"><span class="social_share_icon"></span>';
                    $html .= '<span class="social_share_title">'.  __('Share','qode') .'</span>';
                    $html .= '</a>';
                    $html .= '<div class="social_share_dropdown"><div class="inner_arrow"></div><ul>';
                    if(isset($qode_options_proya['enable_facebook_share']) &&  $qode_options_proya['enable_facebook_share'] == "yes") {
                        $html .= '<li class="facebook_share">';
                        $html .= '<a href="#" onclick="window.open(\'http://www.facebook.com/sharer.php?s=100&amp;p[title]=' . urlencode(qode_addslashes(get_the_title())) . '&amp;p[summary]=' . urlencode(qode_addslashes(get_the_excerpt())) . '&amp;p[url]=' . urlencode(get_permalink()) . '&amp;&p[images][0]=';
                        if(function_exists('the_post_thumbnail')) {
                            $html .=  wp_get_attachment_url(get_post_thumbnail_id());
                        }
                        $html .='\', \'sharer\', \'toolbar=0,status=0,width=620,height=280\');">';
                        if(!empty($qode_options_proya['facebook_icon'])) {
                            $html .= '<img src="' . $qode_options_proya["facebook_icon"] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-facebook"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Facebook","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }

                    if($qode_options_proya['enable_twitter_share'] == "yes") {
                        $html .= '<li class="twitter_share">';
                        // $html .= '<a href="#" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(the_excerpt_max_charlength($count_char) . $twitter_via) . get_permalink() . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                         $html .= '<a href="#" onclick="popUp=window.open(\'http://twitter.com/home?status=' . urlencode(the_excerpt_max_charlength($count_char) . $twitter_via) . "http://" . $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"] . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false;">';
                        if(!empty($qode_options_proya['twitter_icon'])) {
                            $html .= '<img src="' . $qode_options_proya["twitter_icon"] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-twitter"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Twitter", 'qode') . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if($qode_options_proya['enable_google_plus'] == "yes") {
                        $html .= '<li  class="google_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'https://plus.google.com/share?url=' . urlencode(get_permalink()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['google_plus_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['google_plus_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-google-plus"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Google+","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_linkedin']) && $qode_options_proya['enable_linkedin'] == "yes") {
                        $html .= '<li  class="linkedin_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['linkedin_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['linkedin_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-linkedin"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("LinkedIn","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_tumblr']) && $qode_options_proya['enable_tumblr'] == "yes") {
                        $html .= '<li  class="tumblr_share">';
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://www.tumblr.com/share/link?url=' . urlencode(get_permalink()). '&amp;name=' . urlencode(get_the_title()) .'&amp;description='.urlencode(get_the_excerpt()) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['tumblr_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['tumblr_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-tumblr"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Tumblr","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_pinterest']) && $qode_options_proya['enable_pinterest'] == "yes") {
                        $html .= '<li  class="pinterest_share">';
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()). '&amp;description=' . qode_addslashes(get_the_title()) .'&amp;media='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['pinterest_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['pinterest_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-pinterest"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("Pinterest","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    if(isset($qode_options_proya['enable_vk']) && $qode_options_proya['enable_vk'] == "yes") {
                        $html .= '<li  class="vk_share">';
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                        $html .= '<a href="#" onclick="popUp=window.open(\'http://vkontakte.ru/share.php?url=' . urlencode(get_permalink()). '&amp;title=' . urlencode(get_the_title()) .'&amp;description=' . urlencode(get_the_excerpt()) .'&amp;image='.urlencode($image[0]) . '\', \'popupwindow\', \'scrollbars=yes,width=800,height=400\');popUp.focus();return false">';
                        if(!empty($qode_options_proya['vk_icon'])) {
                            $html .= '<img src="' . $qode_options_proya['vk_icon'] . '" alt="" />';
                        } else {
                            $html .= '<i class="fa fa-vk"></i>';
                        }
                        //$html .= "<span class='share_text'>" . __("VK","qode") . "</span>";
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                    $html .= "</ul></div>";
                    $html .= "</div>";

                    if($post_type == "portfolio_page" || $post_type == "post" || $post_type == "page") {
                        $html .= '</div>';
                    }
                }
            }
        }
        return $html;
    }
}
add_shortcode('social_share_tuff', 'social_share_tuff');


/* Qode Carousel shortcode */

if (!function_exists('qode_carousel_tuff')) {
    function qode_carousel_tuff( $atts, $content = null ) {
        $args = array(
            "carousel" => "",
            "orderby"  => "date",
            "order"    => "ASC",
            "show_in_two_rows" => ""
        );
        extract(shortcode_atts($args, $atts));

        $html = "";
        $carousel_holder_classes = "";
        if ($carousel != "") {

            if($show_in_two_rows == 'yes') {
                $carousel_holder_classes = ' two_rows';
            }

            $html .= "<div class='tuff_carousel qode_carousels_holder clearfix " . $carousel_holder_classes  ."'><div class='carousel_nav'><a id='carousel_nav-next' href='#'><i class='qode_icon_font_awesome fa fa-angle-right '></i></a><br><br><a id='carousel_nav-prev' href='#'><i class='qode_icon_font_awesome fa fa-angle-left '></i></a></div><div class='qode_carousels '><ul class='slides clearfix '>";

            // $q = array('post_type'=> 'carousels', 'carousels_category' => $carousel, 'orderby' => $orderby, 'order' => $order, 'posts_per_page' => '-1');

            $q = array(
                    'post_type' => 'post',
                    'category_name' => '',
                    'orderby' => $orderby,
                    'order' => $order,
                    'posts_per_page' => '-1'
                );

            query_posts($q);

            if ( have_posts() ) : $postCount = 1; while ( have_posts() ) : the_post();

                $thumb_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumb_id, array(100,100) , true);
                $image = $thumb_url[0];

                $hover_image = "yes";
                $has_hover_image = "has_hover_image";

                $link = get_permalink();

                $target = "_self";

                $title = get_the_title();

                $post_layout = get_post_meta(get_the_ID(), "post_layout", true);

                //is current item not on even position in array and two rows option is chosen?
                if($postCount % 2 !== 0 && $show_in_two_rows == 'yes') {
                    $html .= "<li class='item'>";
                } elseif($show_in_two_rows == '') {
                    $html .= "<li class='item'>";
                }
                $html .= '<div class="carousel_item_holder " >';
                if($link != ""){
                    $html .= "<a href='".$link."' target='".$target."' class='clearfix' >";
                }

                $first_image = qode_get_attachment_id_from_url($image);

                if($image != ""){
                    $html .= "<span class='first_image_holder ".$has_hover_image."' >";

                    if(is_int($first_image)) {
                        $html .= wp_get_attachment_image($first_image, 'full');
                    } else {
                        $html .= '<img src="'.$image.'" alt="carousel image" />';
                    }

                    $html .= "</span>";
                }

                $second_image = qode_get_attachment_id_from_url($hover_image);

                if($hover_image != ""){
                    $html .= "<div class='second_image_holder clearfix ".$has_hover_image."' >";

                    $html .= '<h3>'.$title.'</h3>';


                    $html .= "</div>";
                }

                if($link != ""){
                    $html .= "</a>";
                }

                $html .= '</div>';

                //is current item on even position in array and two rows option is chosen?
                if($postCount % 2 == 0 && $show_in_two_rows == 'yes') {
                    $html .= "</li>";
                } elseif($show_in_two_rows == '') {
                    $html .= "</li>";
                }

                $postCount++;

            endwhile;

            else:
                $html .= __('Sorry, no posts matched your criteria.','qode');
            endif;

            wp_reset_query();

            $html .= "</ul>";
            $html .= "</div></div>";

        }

        return $html;
    }
}
add_shortcode('qode_carousel_tuff', 'qode_carousel_tuff');


