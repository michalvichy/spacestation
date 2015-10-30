var $j = jQuery.noConflict();



$j(document).ready(function(){
	"use strict";

    if($j.super_cookie().check("saved_cookie")){
        var data = $j.super_cookie().read_JSON("saved_cookie");
        var str = " ";
        $j.each(data,function(i, item) { str = str + '<li><a href="listing/?id='+data[i]+'">listing</a></li>'; });

        $j('.tooltip1 ul').append(str); 
    }

    $j(document).on( "click", ".tooltip .popup_tooltip_inner i", function(){

        $j('.tooltip1').fadeToggle('fast');
        return false;
    });

    /* tooltips events - end */

    //init tour popup
    // initTourPopup();

    /////////// SAVED COUNTER CLICK /////////////
    $j('.side_menu_button').on('click', '.saved_button', function(event) {
        event.preventDefault();
        $j('.tooltip1').fadeToggle('fast');
    });

    /////////// DELETE COOKIE BUTTON /////////////
        
    $j(document).on('click', '.tooltip_link_1', function(event) {
        event.preventDefault();

        if($j.super_cookie().check("saved_cookie")){
            $j.removeCookie('saved_cookie', { path: '/' });
            $j('.tooltip1 ul').html('');
            // $j('.tooltip1').fadeToggle('fast');
            updateSavedCounter();
        }
    });
    

});

$j(window).load(function(){
	"use strict";

});

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

