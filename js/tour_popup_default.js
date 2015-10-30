var $j = jQuery.noConflict();

var tooltip1 = '<div class="tooltip tooltip1"><a href="#" class="tooltip_marker"></a> \
									<div class="popup_tooltip popup_tooltip1"> \
										<div class="popup_tooltip_inner"><i class="fa fa-times"></i> \
											<div class="tooltip_row clearfix"> \
													<h5 class="tooltip_title">Header Options</h5> \
													<p>Bridge theme comes with an amazing new Qode functionality - choose main menu colors from slide to slide and from page to page. Combine it with dark/light header backgrounds to create beautiful contrasts between pages.</p> \
													<h5>Try a different style</h5> \
													<img class="tooltip_image_1" src="http://demo.qodeinteractive.com/bridge/demo_images/tooltip_image_1.jpg" alt="&nbsp;" /> \
													<a class="qbutton small white tooltip_link_1" href="#">Change</a> \
											</div> \
										</div> \
									</div> \
								</div>\
								';

$j(document).ready(function(){
	"use strict";

    /* prelaod tour screenshoot*/

    // jQuery.preLoadImages(qode_root+"demo-files/landing/img/tour_screenshoot.jpg");

    /* tooltips events - start */

    // $j(document).on( "click", ".tooltip .tooltip_marker", function(){
    //     $j(".popup_tooltip").hide(300);
    //     var $this = $j(this);

    //     if ($this.next(".popup_tooltip").is(":visible")){
    //         $this.next(".popup_tooltip").find(".popup_tooltip_inner").animate({opacity:0},100);
    //         $this.next(".popup_tooltip").hide(300);
    //     }
    //     else{
    //         $this.next(".popup_tooltip").show(300, function(){
    //             $this.next(".popup_tooltip").find(".popup_tooltip_inner").animate({opacity:1},600);
    //         });
    //     }
    //     return false;
    // });

    $j(document).on( "click", ".tooltip .popup_tooltip_inner i", function(){
        // $j(".popup_tooltip").hide(300);

        $j('.tooltip1').fadeToggle('fast');

        // $j(this).closest(".popup_tooltip").find(".popup_tooltip_inner").animate({opacity:0},100);
        // $j(this).closest(".popup_tooltip").hide(300);



        return false;
    });

    // $j(document).click(function() {
    //     $j(".tooltip .popup_tooltip").hide(300);
    //     $j(".tooltip .popup_tooltip .popup_tooltip_inner").animate({opacity:0},100);
    //     $j(".tooltip .popup_tooltip").hide(300);

    // });

    // $j(document).on( "click", ".tooltip", function(event){
    //     event.stopPropagation();
    // });

    // $j(document).on( "click", ".tooltip_link_1", function(e){
    //     e.preventDefault();
    //     if(!$j(this).hasClass('clicked')){
    //         $j(this).addClass('clicked');
    //         $j('html').addClass('normal_header');
    //         $j('.tooltip_image_1').attr('src','http://demo.qodeinteractive.com/bridge/demo_images/tooltip_image_1_reverse.jpg');
    //     }else{
    //         $j(this).removeClass('clicked');
    //         $j('html').removeClass('normal_header');
    //         $j('.tooltip_image_1').attr('src','http://demo.qodeinteractive.com/bridge/demo_images/tooltip_image_1.jpg');
    //     }
    // });

    /* tooltips events - end */

    //init tour popup
    // initTourPopup();

    /////////// SAVED COUNTER CLICK /////////////
    $j('.side_menu_button').on('click', '.saved_button', function(event) {
        event.preventDefault();
        $j('.tooltip1').fadeToggle('fast');
    });

});

$j(window).load(function(){
	"use strict";

    $j('header .header_bottom .container_inner').append(tooltip1);
    // $j('.content').append(tooltip2);

	// calculateCustomDropdownWidth();
});


$j(window).scroll(function(){
    "use strict";  
});


$j(window).resize(function(){
	"use strict";

	// calculateCustomDropdownWidth();

});

/**
 * init tour popup on page load
 */
// function initTourPopup(){
//     "use strict";

//     var popup_holder = "<div class='tour_wrapper'>" +
//                             "<div class='tour_wrapper_inner'>"+
//                                 "<div class='tour_text'>" +
//                                     "<h3>The largest update ever.</h3>"+
//                                     "<h2>The best selling new theme of 2014</h2>" +
//                                     "<div class='separator  small center' ></div>"+
//                                     "<p>Click to see all 100 Bridge demos, 24 new concepts, and endless possibilities.</p>"+
//                                     "<a  href='#' class='qbutton green'>launch tour</a>" +
//                                 "</div>" +
//                                 "<div class='tour_text_closed_wrapper'>"+
//                                     "<div class='tour_text_closed'>"+
//                                         "<h2>Explore Bridge</h2>"+
//                                         "<p>Take a quick tour</p>"+
//                                     "</div>"+
//                                     "<div class='tour_holder'>" +
//                                         "<div class='tour_holder_snapshoot'>" +
//                                             "<div class='snapshoot_shader'></div>"+
//                                         "</div>" +
//                                         "<div class='tour_holder_content'></div>" +
//                                     "</div>" +
//                                 "</div>" +
//                                 "<div class='tour_close_wrapper'><a href='#'><span class='icon_close' aria-hidden='true'></span></a></div>" +
//                                 "<div class='tour_closed_button'><a href='#' class='qbutton green'>Launch  tour</a></div>"+
//                             "</div>"+
//                         "</div>";

//     if($j('body').hasClass('home') && !$j('html').hasClass('touch')){

//         //insert tour popup holder with its predefined structure
//         $j('body').append(popup_holder);

//         //register tour variables
//         var $tour_wrapper = $j('.tour_wrapper');
//         var $tour_open = $j('.tour_text .qbutton, .tour_holder, .tour_closed_button .qbutton');
//         var $tour_holder_snapshoot = $j('.tour_holder_snapshoot');
//         var $tour_holder_content = $j('.tour_holder_content');
//         var $tour_close = $j('.tour_close_wrapper a, .tour_close_bar a');

//         //if cookie is true, show minimized tour popup in hovered state
//         if(Cookies.get('tour_cookie') == 'true'){
//             $tour_wrapper.addClass('minimized_tour hovered');
//             $j('html').removeClass('tour_opened');

//             //remove 'hovered' class from tour wrapper
//             $tour_wrapper.one("mouseenter", function(){
//                 $tour_wrapper.removeClass('hovered');
//             });
//         }

//         //show tour popup
//         $tour_wrapper.delay(1000).fadeIn(1000);

//         //open tour holder on clik
//         $j($tour_open).one( "click", function() {
            
//             $tour_wrapper.removeClass('minimized_tour').addClass('opened');

//             //show modified loader
//             $j('body').addClass('tour_loader');
//             $j('.ajax_loader .pulse').addClass('cube').text('B').removeClass('pulse');
//             $j('.ajax_loader_1').append('<p>Tour is loading, just a sec...</p>');
//             $j('.ajax_loader').show();


//             //hide defaulr scrollbar
//             $j('html').addClass('tour_opened');
            
//             //get tour page and put it in iframe
//             $tour_holder_content.append("<iframe class='tour_iframe' src='http://demo.qodeinteractive.com/bridgetour/'><iframe>").fadeIn(500);
//             $j('iframe.tour_iframe').load(function(){
//                 Cookies.remove('tour_cookie');
//                 $j('.ajax_loader').hide();
//                 $j('body').removeClass('tour_loader');
//                 $j('.ajax_loader .cube').addClass('pulse').text('').removeClass('cube');
//                 $j('.ajax_loader_1 p').remove();

//                 $tour_holder_snapshoot.fadeOut(500);
//             });
//         });

//         //close tour popup
//         $tour_close.on('click', function (e) {
//             e.preventDefault();

//             //close tour popup and remove it
//             //$tour_wrapper.fadeOut(600, function () {
//             //    $tour_wrapper.remove();
//             //});

//             $tour_wrapper.addClass('minimized_tour');
//             $j('html').removeClass('tour_opened');

//         });

//     }
// }

function calculateCustomDropdownWidth(){
    if($j(window).width() < 1200 && $j(window).width() > 1000){
        $j('.dropdown_three_colums .second').css('width',$j('.header_bottom').width());
        $j('.dropdown_three_colums .second ul').css('width',$j('.header_bottom').width());
        $j('.dropdown_three_colums .second').css('left',-($j('.dropdown_three_colums').offset().left - 45));

    } else if($j(window).width() >= 1200){
        $j('.dropdown_three_colums .second').css('left',-($j('.dropdown_three_colums').offset().left - $j('header .header_inner_left').offset().left));
    }

}

(function($) {
    var cache = [];
    // Arguments are image paths relative to the current page.
    $.preLoadImages = function() {
        var args_len = arguments.length;
        for (var i = args_len; i--;) {
            var cacheImage = document.createElement('img');
            cacheImage.src = arguments[i];
            cache.push(cacheImage);
        }
    }
})(jQuery)

