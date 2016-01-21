//VARS
var $j = jQuery.noConflict();
var gallery_view = $j('.ls-wp-fullwidth-container');
var floorplan_view = $j('#floorplan_container');
var epc_view = $j('#epc_container');
var map_view = $j('#map_container');
var video_view = $j('#myvid');
var description_view = $j('.single_view_info.description');
var arrange_view = $j('.single_view_info.arrange');

var video1;

// GOOGLE MAP
function init_map(){

	// STYLED MAP
		var roadAtlasStyles = [
	    {
	        featureType: "poi.school",
	        elementType: "labels",
	        stylers: [
	              { visibility: "off" }
	        ]
	    }
	  	];
	
	  var styledMapOptions = {
	    	name: 'US Road Atlas'
	  	};
	
	
	// MY STLES MAP
	
		var myStyles =[
	    {
	        featureType: "poi.school",
	        elementType: "labels",
	        stylers: [
	              { visibility: "off" }
	        ]
	    }
		];
	
	// MY OPTIONS	
		var myOptions = {
		    zoom:16,
		    center:new google.maps.LatLng(lat,lng),
		    scrollwheel: false,
		    mapTypeControlOptions: {
        		style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
        		position: google.maps.ControlPosition.LEFT_BOTTOM
    		},
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	
		map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
		
		marker = new google.maps.Marker({
	  		map: map,
	  		position: new google.maps.LatLng(lat,lng)
		});
	
		infowindow = new google.maps.InfoWindow({content:"<b>"+property_name+"</b><br/>"+property_city_postal });
	
	  	var usRoadMapType = new google.maps.StyledMapType(roadAtlasStyles, styledMapOptions);
	
	  	map.mapTypes.set('usroadatlas', usRoadMapType);
	  	map.setMapTypeId('usroadatlas');
	
		google.maps.event.addListener(marker, "click", function(){
			infowindow.open(map,marker);
		});
			
		infowindow.open(map,marker);
}

///////////// hideItem / showItem
function hideItem(items, opacity)
	{
		for(var i=0; i<items.length; i++)
			{
				if(opacity)
				{
					items[i].css({opacity: 0.0, visibility: "hidden"}).animate({opacity: 0}, 200);
					
				}
				else
				{
					items[i].fadeOut(200);
				}
			}
	}
 
function showItem(items, opacity)
	{
		for(var i=0; i<items.length; i++)
		{
			if(opacity)
			{
				items[i].css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1}, 200)
			}
			else
			{
				items[i].fadeIn(300);
			}
		}
	}

// Initialize Unversal Player
function Initialize_Unversal_Player() 
	{
	
		// inject html structure
		if(external == 'false') 
			{
				'use strict';
				$j('#myvid').html('<div class="px-video-img-captions-container"><div class="px-video-captions hide"></div><div class="px-video-wrapper"><video poster="img/poster.jpg" class="px-video" controls ><source src='+video_url+' type="video/mp4" /><div><a href=' + video_url + '><img src="img/poster.jpg" width="640" height="360" alt="download video" /></a></div></video></div></div><div class="px-video-controls"></div>');
		
				//init UVP
				video1 = new InitPxVideo({
				"videoId": "myvid",
				"captionsOnDefault": false,
				"seekInterval": 20,
				"videoTitle": "Ind.ie Launch",
				"debug": true
				});
		
				//Autoplay video after init - optional
				playVid();
		
			}
		else if(external === 'true') 
			{
				$j('#myvid').html('<iframe width="100%" height="500" src="' + youtube_url + '"></iframe>');
			}
	}

function playVid() {
	obj.movie.play();
	obj.btnPlay.className = "px-video-play hide";
	obj.btnPause.className = "px-video-pause px-video-show-inline";
	obj.btnPause.focus();
}

function pauseVid() {
	obj.movie.pause(); 
   	obj.btnPlay.className = "px-video-play px-video-show-inline";
	obj.btnPause.className = "px-video-pause hide";
	obj.btnPlay.focus();
}

// DOCUMENT READY - 2st
	$j(document).ready(function() {
		
	//LAYOUT CHANGE BUTTONS LOGIC

	$j('.single_view_navigation ul').on('click','li',function(event){
		event.preventDefault();
		event.stopPropagation();
		switch( $j(this).attr('id') ) {
    		case 'single_view_nav_map': 
    			
    			// if video player has been initialised
    		    if(video1 != undefined){  pauseVid(); }

    		    // if lat & lng defined load map
    		    // if(lat != undefined && lng != undefined)
    		    // {

    		    	map_view.css('z-index', '0');

    		    	hideItem([gallery_view,video_view,floorplan_view,epc_view]);
    		    	showItem([map_view]);
    		    	gallery_view.addClass('niema'); // ! window resize glitch fix

    		    	if( $j('#poi_map').is(':empty') ) 
    		    	{ 
    		    		loadPOI();
    		    	}

				// }else
				// {
						// alert('no longitude and latitude');
				// }

    		    break;
    		
    		case 'single_view_nav_gallery':
    			
    			// if video player has been initialised
    		    if(video1 != undefined){ pauseVid(); }

    		     	hideItem([map_view,video_view,floorplan_view,epc_view]);
					showItem([gallery_view]);
					gallery_view.removeClass('niema');

    		    break;

    		case 'single_view_nav_floorplan':
    			
    			// if video player has been initialised
    		    if(video1 != undefined){ pauseVid(); }

    		    	if(floorplan){

    		    		gallery_view.addClass('niema'); // ! window resize glitch fix

    		     		hideItem([map_view,video_view,gallery_view,epc_view]);
				 		showItem([floorplan_view]);
				 	}else{
				 		alert('no Floorplan');
				 	}

    		    break;

    		case 'single_view_nav_epc':
    			
    			// if video player has been initialised
    		    if(video1 != undefined){ pauseVid(); }

    		    	if(epc)
    		    	{

    		    		gallery_view.addClass('niema'); // ! window resize glitch fix

    		    		hideItem([map_view,video_view,gallery_view,floorplan_view]);
						showItem([epc_view]);
					}
					else
					{
						alert('no EPC image');
					}


    		    break;
    		
    		case 'single_view_nav_video':

    				gallery_view.addClass('niema'); // ! window resize glitch fix
    				
    				// if video hasn't been initialised yet -> do it
    				if(video_url == undefined){
    					alert('no video url');
    				}
    				else if(video1 == undefined && video_url != undefined){ 
    		    		Initialize_Unversal_Player();
    		    	}
    		    	// else video has been initialised and is paused now -> resume video playing
    		    	else
    		    	{
    		    		playVid();
    		    	}

    		    	if( video_url != undefined ){
    		    		hideItem([map_view,gallery_view,floorplan_view,epc_view]);
						showItem([video_view]);
					}
    		    break;
    		
    		default:
        		// default code block
		}
	})

	// ARRANGE VIEWING BUTTON
	$j('.arrange_viewing_button.open').on('click',function(event){
		event.preventDefault(); 
		hideItem([description_view],true);
		showItem([arrange_view],true);

	})

	$j('.arrange_viewing_button.close').on('click',function(event){
		event.preventDefault(); 
		hideItem([arrange_view],true);
		showItem([description_view],true);
	})

	// -------- PRINT BUTTON ------------
	
	// $j('.print_button').on('click', 'a', function(event) {
	// 	event.preventDefault();
	// 	print();
	// });

	// -------- SAVE BUTTON ------------
	
	$j('.add_to_saved_button').on('click', 'a', function(event) {
		event.preventDefault();

		// if 'listing_id' & 'property_name' variable is available
		if(listing_id !== undefined && property_name !== undefined )
		{

			// check if saved_cookie exists
				if($j.super_cookie().check("saved_cookie")){
					//if available -> add another id value to cookie
					var arr = $j.super_cookie().read_indexes("saved_cookie");
					var jso = $j.super_cookie().read_JSON("saved_cookie");
					var already_added_flag = false;

					for (var k = 1; k < arr.length+1; k++) {
						if( jso['property_'+k] == listing_id+'|'+property_name ){
							already_added_flag = true;
							break;
						}
					};

					if( already_added_flag == false ){
						var i = $j.super_cookie().read_indexes("saved_cookie").length + 1;
						$j.super_cookie().add_value("saved_cookie",'property_'+i, listing_id+'|'+property_name);
						updateSavedCounter(listing_id,property_name);
						$j('.success').fadeIn().delay(600).fadeOut('fast');
					}else{
						$j('.warning').fadeIn();
					}

				}else{
					//if not available create saved_cookie and add first id value
					$j.super_cookie({expires: 7,path: "/"}).create("saved_cookie",{'property_1': listing_id+'|'+property_name});
					updateSavedCounter(listing_id,property_name);
					$j('.success').fadeIn().delay(600).fadeOut('fast');
				}
		}

	});
	
	});

// WINDOW LOAD - 3rd
	$j(window).load(function(){

		$j("#listing_gallery_layerslider").layerSlider({
			responsiveUnder: 768, 
			layersContainer: 768, 
			autoStart: false, 
			startInViewport: false, 
			skin: 'noskin', 
			globalBGColor: 'transparent', 
			hoverPrevNext: false, 
			thumbnailNavigation: 'always', 
			autoPlayVideos: false, 
			cbInit: function(element) { }, 
			cbStart: function(data) { }, 
			cbStop: function(data) { }, 
			cbPause: function(data) { }, 
			cbAnimStart: function(data) { }, 
			cbAnimStop: function(data) { }, 
			cbPrev: function(data) { }, 
			cbNext: function(data) { }, 
			skinsPath: window.URLdir+'/wp-content/plugins/LayerSlider/static/skins/'
		});

	});


