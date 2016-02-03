//POI Auto Map v5.2.5 23October2013 - (c) Gavin Smith 2013 - http://codecanyon.net/item/point-of-interest-poi-auto-map/101599
//Commercial License
// Map Configuration

var defaultAddress = "Eltham, Victoria";
var defaultHTML = "<strong>Eltham,<br/>Victoria, Australia</strong>";
var defaultZoomLevel = 13; //Larger Number = Higher Zoom 
var infoHTML = "<h4>Instructions</h4><p>Drag the map or enter an address above to view a different area's information. Click on the list of categories to show points of interest on the map. Click on each point to see more detail.</p>";

var searchControls = false;
var searchControlText = "Enter a city or address ...";

var directionControls = false;
var mapDivID = "poi_map";
var categoriesList = "poiList";
var sidebarDivID = "poi_sidebar"; //div that contains categories list
var directionsMode = 'DRIVING'; //change to 'walking' WALKING for walking directions
var infoWindowDivID = "";
var toggleList = true; //Adds toggle to show / hide category list
var listExpandWidth = "230px";
var listCollapseWidth = "40px";
var showListExpanded = false;

// Display Photos In Info Window - Make Sure You Add Your Google Places API Key to db/imageproxy.php
var showPhotos = false; 
var maxPhotoWidth = 150; 
// Display Google Places Rating
var showRating = false; 

//geolocation features
var useGeolocation = false;
var autoGeolocation = false;
//Database Bounds Search Extension
var extendLat = 0.05;
var extendLng = 0.005;
var dbPath = window.URLdir+'/wp-content/themes/spacestation/src/poi/db/';
//Places Search Radius (metres)
var searchRadius = 2000;
var autoRadius = false; //Use the visible map instead of searchRadius
//Main Marker Configuration
var mainIconWidth = 43;
var mainIconHeight = 50;
var mainAnchorPointX = 18;
var mainAnchorPointY = 50;
// Icon Configuration
var iconPath = window.URLdir+'/wp-content/themes/spacestation/src/poi/icons/';
var iconWidth = 32;
var iconHeight = 37;
var anchorPointX = 16;
var anchorPointY = 36; 
var iconScaleWidth = 26;
var iconScaleHeight = 26;
var useGoogleIcon = false;

var mapExtra = false; //runs if true: mapPost(function);

/*-------------------------------*/
/*  Do Not edit below this line  */
/*-------------------------------*/
var classAdder;
var markerGroups = "";
var map = null;
var searchCenter;
var addressSet;
var overlayControls;
var wikiLayer;
var photoLayer;
var startAddress;
var directions;
var myPano;
var jsonGroups;
var mapDiv;
var mapLoading;
var ddBoxDiv;
var batchGeoLat = [];
var batchGeoLng = [];
var lastInfoWindow;
var directionDisplay;
var directionsService;
var geoCenter;
var geoLng;
var geoLat;
var listRight;
var travMode;
var overlayDiv;
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();

function setupAddress() {
  if (typeof poiAddress === 'undefined') {
    startAddress = defaultAddress;
  } else {
    startAddress = poiAddress;
  }
  if (typeof poiHTML === 'undefined') {
    markerHTML = defaultHTML;
  } else {
    markerHTML = poiHTML;
  }
  if (typeof poiZoomLevel === 'undefined') {
    zoomLevel = defaultZoomLevel;
  } else {
    zoomLevel = poiZoomLevel;
  }
}

function centerBox(child, parent) {
  var h = document.getElementById(child).offsetHeight;
  var a = Math.round(parseInt(document.getElementById(parent).offsetHeight, 10) / 2);
  var b = Math.round(h / 2);
  var c = (a - b) + "px";
  document.getElementById(child).style.top = c;
  var w = document.getElementById(child).offsetWidth;
  var x = Math.round(parseInt(document.getElementById(parent).offsetWidth, 10) / 2);
  var y = Math.round(w / 2);
  var z = x - y;
  document.getElementById(child).style.left = z + "px";
}

function directionsChange(){
	var dirSelect = document.getElementById('directionsControl');
	if(dirSelect.options[dirSelect.selectedIndex].value == 'DRIVING') {
		travMode = google.maps.DirectionsTravelMode.DRIVING;
	} else {
		travMode = google.maps.DirectionsTravelMode.WALKING;
	}
}

function hideInfoBox() {
  document.getElementById("infoBox").style.display = "none";
}

function geotarget() {
	navigator.geolocation.getCurrentPosition ( 
	function (position) { 
		//alert (position.coords.latitude + ":" + position.coords.longitude);
		var geoCenter = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		map.setCenter(geoCenter);
		startAddress = position.coords.latitude + ", " + position.coords.longitude;
		createMarker(geoCenter, 0, "You Are Here", "pin", "pin", "");
	    getCategories(0);
		if (mapExtra === true) {
			mapPost();
		}
	});
}

function getScrollBarWidth () {
	var inner = document.createElement('p');
	inner.style.width = "100%";
	inner.style.height = "200px";

	var outer = document.createElement('div');
	outer.style.position = "absolute";
	outer.style.top = "0px";
	outer.style.left = "0px";
	outer.style.visibility = "hidden";
	outer.style.width = "200px";
	outer.style.height = "150px";
	outer.style.overflow = "hidden";
	outer.appendChild (inner);

	document.body.appendChild (outer);
	var w1 = inner.offsetWidth;
	outer.style.overflow = 'scroll';
	var w2 = inner.offsetWidth;
	if (w1 == w2) {	w2 = outer.clientWidth; }
		document.body.removeChild (outer);
		return (w1 - w2);
}

function togglelist() {
	 var toggleListElem = document.getElementById(categoriesList);
	 if (toggleListElem.parentNode.style.width == listExpandWidth){
	    pAddClass(document.getElementById("listToggle"), "listHidden");
	    pRemoveClass(document.getElementById("listToggle"), "listVisible");
		toggleListElem.parentNode.style.width = listCollapseWidth;
	} else {
	    pRemoveClass(document.getElementById("listToggle"), "listHidden");
	    pAddClass(document.getElementById("listToggle"), "listVisible");
		toggleListElem.parentNode.style.width = listExpandWidth;
	}
}
function infoBox() {
  mapLoading = document.createElement('div');
  mapDiv.appendChild(mapLoading);
  mapLoading.style.zIndex = 100;
  mapLoading.setAttribute('id', 'mapLoading');
  centerBox("mapLoading", "poi_map");
  if (infoHTML !== "") {
    var infoBoxDiv = document.createElement('div');
    infoBoxDiv.style.position = "relative";
    infoBoxDiv.setAttribute('id', 'infoBox');
    mapDiv.appendChild(infoBoxDiv);
    infoBoxDiv.innerHTML = infoHTML;
	infoBoxDiv.style.zIndex = "1";
    infoBoxDiv.style.top = (mapDiv.offsetHeight - infoBoxDiv.offsetHeight) + "px";
    var infoBoxClose = document.createElement('a');
    infoBoxClose.setAttribute('id', 'infoBoxClose');
    infoBoxClose.style.position = "absolute";
    infoBoxDiv.appendChild(infoBoxClose);
    infoBoxClose.style.top = "4px";
    infoBoxClose.style.left = "4px";
    infoBoxClose.onclick = function() { 
		hideInfoBox();
	};
  }
  overlayDiv = document.createElement('div');
  overlayDiv.setAttribute('id', 'overlayControl');
  mapDiv.appendChild(overlayDiv);
  overlayDiv.style.zIndex = 2;
  
  var overlayHTML = "";
  if (searchControls === true) {
    overlayHTML = overlayHTML + '<form id="searchForm" action="#" onsubmit="findAddress(this.address.value); return false">';
	overlayHTML = overlayHTML + '<input id="searchTxt" type="text" size="20" name="address" placeholder="' + searchControlText + '" />';
    overlayHTML = overlayHTML + '<input id="searchButton" type="submit" value="Go" /> </form>';
  }
  //geoloation
if (useGeolocation === true){
  if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition( 
 
		function (position) {  
			//Did we get the position correctly?
			//alert (position.coords.latitude + ":" + position.coords.longitude);
				geoTarget = document.createElement('a');
				geoTarget.setAttribute('onclick', 'javascript: geotarget(); return false');
				geoTarget.setAttribute('id', 'geoTarget');
				geoCenter = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				geoLat = position.coords.latitude;
				geoLng = position.coords.longitude;
				
				overlayDiv.appendChild(geoTarget);
				if (autoGeolocation === true) {
					map.setCenter(geoCenter);
					startAddress = geoLat + ", " + geoLng;
					createMarker(geoCenter, 0, "You Are Here", "pin", "pin", "");
					getCategories(0);
					if (mapExtra === true) {
						mapPost();
					}
				}
			}, 
		function (error)
		{
			switch(error.code) 
			{
				case error.TIMEOUT:
//					alert ('Timeout');
					break;
				case error.POSITION_UNAVAILABLE:
//					alert ('Position unavailable');
					break;
				case error.PERMISSION_DENIED:
//					alert ('Permission denied');
					break;
				case error.UNKNOWN_ERROR:
//					alert ('Unknown error');
					break;
				default:
//					alert ('Unknown error');
					break;
			}
		}
		);
	}
}

if(directionControls === true) {
		
		var driveSelect = "";
		var walkSelect = "";
		if (directionsMode  == 'DRIVING'){
			driveSelect = " selected='selected'";
		} else {
			walkSelect = " selected='selected'";
		}
		
		overlayHTML = overlayHTML + "<select id='directionsControl' onchange='javascript:directionsChange();'>";
		overlayHTML = overlayHTML + '<option value="DRIVING"'+ driveSelect +'>Driving</option>';
		overlayHTML = overlayHTML + '<option value="WALKING"'+ walkSelect +'>Walking</option>';
		overlayHTML = overlayHTML + '</select>';

	}
  overlayDiv.innerHTML = overlayHTML;
  overlayDiv.style.position = "absolute";
 if (toggleList === true){
  var listElem = document.getElementById(categoriesList);
  var listToggle = document.createElement('a');
  listToggle.innerHTML = "Hide List";
  listToggle.setAttribute("ID", "listToggle");
  //listToggle.onclick = function() { togglelist() };
  listToggle.setAttribute("onclick", "togglelist();");
  listToggle.style.cursor = "pointer";
  listElem.parentNode.insertBefore(listToggle, listElem);
  //compensate for scroll bars
  if (listElem.offsetHeight > document.getElementById(mapDivID).offsetHeight){
	listCollapseWidth = parseInt(listCollapseWidth, 10) + getScrollBarWidth();
	listCollapseWidth =  listCollapseWidth + "px";
	listExpandWidth = parseInt(listExpandWidth, 10) + getScrollBarWidth();
	listExpandWidth =  listExpandWidth + "px";
  }
  listElem.parentNode.style.width = listCollapseWidth;
  
	if (showListExpanded === true) {
		pAddClass(listToggle, "listVisible");
		listElem.parentNode.style.width = listExpandWidth;
	} else {
		pAddClass(listToggle, "listHidden");
	}
}
 mapLoading.style.display = "none";
}

function iwHandle(infowindow, map, marker, style) {

	if (infoWindowDivID !== ""){
		externalDiv = document.getElementById(infoWindowDivID);
		externalDiv.innerHTML = infowindow.content;
	} else {
		infowindow.open(map,marker);
		lastInfoWindow = infowindow;
	}
}

function isTouchDevice(){
	try{
		document.createEvent("TouchEvent");
		return true;
	}catch(e){
		return false;
	}
}

function mobileScroll(id){
	if(isTouchDevice()){ 
		var el = document.getElementById(id);
		var scrollStartPosY=0;
		var scrollStartPosX=0;

		document.getElementById(id).addEventListener("touchstart", function(event) {
			scrollStartPosY=this.scrollTop+event.touches[0].pageY;
			//scrollStartPosX=this.scrollLeft+event.touches[0].pageX;
			//event.preventDefault();
		},false);

		document.getElementById(id).addEventListener("touchmove", function(event) {
			if ((this.scrollTop < this.scrollHeight-this.offsetHeight &&
				this.scrollTop+event.touches[0].pageY < scrollStartPosY-5) ||
				(this.scrollTop !== 0 && this.scrollTop+event.touches[0].pageY > scrollStartPosY+5)) {
					event.preventDefault();
				}
//			if ((this.scrollLeft < this.scrollWidth-this.offsetWidth &&
//				this.scrollLeft+event.touches[0].pageX < scrollStartPosX-500) ||
//				(this.scrollLeft != 0 && this.scrollLeft+event.touches[0].pageX > scrollStartPosX+5))
//					event.preventDefault();	
			this.scrollTop=scrollStartPosY-event.touches[0].pageY;
			this.scrollLeft=scrollStartPosX-event.touches[0].pageX;
		},false);
	}
}

function controlToggle(state) {
	if (state == "show"){
		overlayDiv.style.display = "block";
		document.getElementById(sidebarDivID).style.display = "block";
	} else {
		overlayDiv.style.display = "none";
		document.getElementById(sidebarDivID).style.display = "none";
	}
}

function createMarker(latlng, index, html, category, icon, src, title, iconURL) {

    var myHtml;
	if ( icon === undefined ) {
      icon = category;
   }
  if (category == "pin") {

		if (markerGroups["pin"]) {

		for (var i = 0; i < markerGroups["pin"].length; i++) {
		  markerGroups["pin"][i].setMap(null);
		}
		markerGroups["pin"].length = 0;
		}

	  var image = new google.maps.MarkerImage(iconPath + category + ".png",
      new google.maps.Size(mainIconWidth, mainIconHeight),
      new google.maps.Point(0,0),
      new google.maps.Point(mainAnchorPointX, mainAnchorPointY));
	  
	  var shadow = new google.maps.MarkerImage( iconPath + category + "-shadow.png",
      new google.maps.Size(mainIconWidth, mainIconHeight),
      new google.maps.Point(0,0),
      new google.maps.Point(mainAnchorPointX, mainAnchorPointY));

	  var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        shadow: '',
        icon: image
    });
	  markerGroups["pin"].push(marker);
	  var infowindow = new google.maps.InfoWindow({
        content: html
    });
	  google.maps.event.addListener(marker, 'click', function() {
		//map.panTo(latlng);
		controlToggle("hide");
		google.maps.event.addListener(infowindow, 'closeclick', function() {
			controlToggle("show");
		});
		infowindow.open(map, marker);
    });

  } else {
  if (icon === ""){ icon = category; }
  
  
  if(useGoogleIcon === true){
	  if (iconURL === null){ 
			iconURL = iconPath + icon + ".png";
		}
  	  image = new google.maps.MarkerImage( iconURL,
      new google.maps.Size(iconWidth, iconHeight),
      new google.maps.Point(0,0),
      new google.maps.Point(anchorPointX, anchorPointY),
	  new google.maps.Size(iconScaleWidth, iconScaleHeight));
  } else {
  	  image = new google.maps.MarkerImage( iconPath + icon + ".png",
      new google.maps.Size(iconWidth, iconHeight),
      new google.maps.Point(0,0),
      new google.maps.Point(anchorPointX, anchorPointY),
	  new google.maps.Size(iconScaleWidth, iconScaleHeight));
	}

}
  if (category == "xml") {
	//xml processing disabled
		xml = "blank";
	} else {

	    marker = new google.maps.Marker({
        position: latlng,
        map: map,
        shadow: '',
        icon: image,
//		animation: google.maps.Animation.DROP,
		title: category
    });
	marker.setTitle(title);
	markerGroups[category].push(marker);
	   infowindow = new google.maps.InfoWindow({
        content: html
    });
	  google.maps.event.addListener(marker, 'click', function() {   
	  if (lastInfoWindow) {
			controlToggle("show");
			lastInfoWindow.close();
		}
		controlToggle("hide");
		google.maps.event.addListener(infowindow, 'closeclick', function() {
			controlToggle("show");
		});
		map.panTo(latlng);
	  if (src == "api"){
		strMatch = infowindow.content;
		strResult = strMatch.match(/api:/ig);
			if(strResult == "api:"){
				reference = strMatch.replace("api:","");
//				console.log("https://maps.googleapis.com/maps/api/place/details/json?reference=" + reference + "&sensor=false&key=AIzaSyB_tVsms-ULE2W1QGg0XFx2VNHoUlHDfCs");
				var JSON = dbPath + "jsonproxy.php?url=" + encodeURIComponent("https://maps.googleapis.com/maps/api/place/details/json?reference=" + reference + "&sensor=false");
				mapLoading.style.display = "block";
				jQuery.getJSON(JSON, function(data) {
					if (data.result.hasOwnProperty('website')) {
						htmlURL = data.result.website;
					} else {
						htmlURL = data.result.url;
					}
					if (data.result.hasOwnProperty('rating')) {
						ratingVal = data.result.rating;
					} else {
						ratingVal = "no";
					}
					if (data.result.hasOwnProperty('photos')) {
						photo = data.result.photos[0].photo_reference;
						photoHeight = data.result.photos[0].height;
						photoWidth = data.result.photos[0].width;
					} else {
						photo = "no";
						photoHeight = 0;
						photoWidth = 0;
					}
					var resultHTML = createHTML(data.result.name, htmlURL, data.result.formatted_address, "", "", data.result.formatted_phone_number, data.result.geometry.location.lat, data.result.geometry.location.lng, ratingVal, data.result.url, photo, photoHeight, photoWidth);
					infowindow.content = resultHTML;
					iwHandle(infowindow,map,marker,"");
					mapLoading.style.display = "none";
				});
			} else {
					iwHandle(infowindow,map,marker,"");
			}
		} else {
					iwHandle(infowindow,map,marker,"");
		}
    });
  }
}

function createHTML(title, url, address1, address2, website, phone, lat, lng, rating, placesURL, photo, photoHeight, photoWidth) {
  var onClickSVCode = '"' + lat + '","' + lng + '","' + address1 + '","' + address2 + '"';
  var onClickDDCode = '"' + address1 + ' ' + address2 + '"';
  var finalHTML;
  finalHTML = '<div class="gs-localResult gs-result">';
  finalHTML = finalHTML + '<div class="gs-title"><a target="_blank" class="gs-title" href="' + url + '">' + title + '</a></div>';
  if ( showRating == true && rating !== "no") {
	finalHTML = finalHTML + '<div><a target="_blank" href="' + placesURL + '" class="gs-rating-' + Math.round(rating) + '"></a></div>';
  }
  finalHTML = finalHTML + '<div class="gs-address">';
  finalHTML = finalHTML + '<div class="gs-street gs-addressLine">' + address1 + '</div>';
  if (address2 !== null) {
    finalHTML = finalHTML + '<div class="gs-city gs-addressLine">' + address2 + '</div>';
  }
  finalHTML = finalHTML + '</div>';
  finalHTML = finalHTML + '<div class="gs-phone">Phone: ' + phone + '</div>';
  //Get Image If Exists
  if (showPhotos == true && photo !== "no") {
	if (maxPhotoWidth > photoWidth) {
		//do nothing
	} else {
		photoHeight = ( maxPhotoWidth / photoWidth) * photoHeight;
		photoWidth = maxPhotoWidth;
	}
	finalHTML = finalHTML + '<div class="gs-photo" style="height: ' + photoHeight + 'px; width: ' + photoWidth + 'px" ><a href="' + placesURL + '" target="_blank"><img src="' + dbPath + 'imageproxy.php?size=' + maxPhotoWidth + '&ref=' + photo + ' "/></a></div>';
  }
  finalHTML = finalHTML + "<div class='gs-streetview'><a class='gs-sv-link' onclick='showStreetView(" + onClickSVCode + ")' style='cursor: pointer'>Street View</a><a class='gs-dd-link' onclick='showDirections(" + onClickDDCode + ")' style='cursor: pointer'>Directions</a></div>";
  finalHTML = finalHTML + '</div>';
  return finalHTML;
}

function createXmlHTML(address, title, html, url, lat, lng) {
  var onClickSVCode = '"' + lat + '","' + lng + '","' + address + '"';
  var onClickDDCode = '"' + address + '"';
  var finalHTML;
  finalHTML = '<div class="gs-localResult gs-result">';
  finalHTML = finalHTML + '<div class="gs-title"><a target="_blank" class="gs-title" href="' + url + '">' + title + '</a></div>';
  finalHTML = finalHTML + '<div class="gs-customHTML">' + html;
  finalHTML = finalHTML + '</div>';
  finalHTML = finalHTML + "<div class='gs-streetview'><a class='gs-sv-link' onclick='showStreetView(" + onClickSVCode + ")' style='cursor: pointer'>Street View</a><a class='gs-dd-link' onclick='showDirections(" + onClickDDCode + ")' style='cursor: pointer'>Directions</a></div>";
  finalHTML = finalHTML + '</div>';
  return finalHTML;
}

function downloadScript(url) {
  var script = document.createElement('script');
  script.src = url;
  document.body.appendChild(script);
}

function html_entity_decode(str) {
	var ta=document.createElement("textarea");
	ta.innerHTML=str.replace(/</g,"&lt;").replace(/>/g,"&gt;");
	return ta.value;
}

function doSearch(keyword, type) {

 mapLoading.style.display = "block";
  currentCategory = type;
  var icon;
  
  		if (markerGroups[type]) {

		for (var i = 0; i < markerGroups[type].length; i++) {
		  markerGroups[type][i].setMap(null);
		}
		markerGroups[type].length = 0;
		}

  var hider = document.getElementById(type).getAttribute("caption");
  if (keyword.substr(0,3) == "db:"){ 
  var bounds = map.getBounds();
  var southWest = bounds.getSouthWest();
  var northEast = bounds.getNorthEast();
  var swLat = southWest.lat();
  var swLng = southWest.lng();
  var neLat = northEast.lat();
  var neLng = northEast.lng();
  var dbCat = keyword.substr(3);
    if (hider != "hidden") {
  var filename = dbPath + "db.php?cat="+ dbCat + "&swLat="+ swLat + "&swLng="+ swLng + "&neLat="+ neLat + "&neLng="+ neLng + "&extendLat="+ extendLat + "&extendLng="+ extendLng;
	jQuery.getJSON(filename, function(data) {
			for( i = 0; i < data.results.length; i++) {
				var result = data.results[i].geometry.location;
				if(result.icon === "") {
					icon = type;
				} else {
					icon = result.icon;
				}
					cleanHTML = html_entity_decode(result.html);
					var xmlHTML = createXmlHTML(result.address, result.name, cleanHTML, result.url, result.lat, result.lng);
					var latlng = new google.maps.LatLng(parseFloat(result.lat), parseFloat(result.lng));
					createMarker(latlng, i, xmlHTML, type, icon, "db", result.name);
      }

     mapLoading.style.display = "none";
});
  } else {mapLoading.style.display = "none";}
  } else {
			if (type == "user") {
				var userName = document.getElementById(type).getAttribute("name");
					if (userName === null) {
							hider = "hidden";
					} else {
						keyword = "establishment";
						searchName = userName;
					}
				}
				if (hider != "hidden") {
				var searchName = document.getElementById(type).getAttribute("name");
				if (searchName === null){
					searchName = "";
				} else {
					searchName = "&name=" + searchName.replace(/ /gi, "/");
				}
					var ctr = map.getCenter();
					//alert("Center: " + ctr)
					var jsonLAT = ctr.lat();
					var jsonLNG = ctr.lng();
					if (autoRadius === true){
						searchRadius = distance( map.getBounds().getNorthEast().lat(), map.getBounds().getNorthEast().lng(), map.getBounds().getSouthWest().lat(), map.getBounds().getSouthWest().lng());
					}
  					var JSON = dbPath + "jsonproxy.php?url=" + encodeURIComponent("https://maps.googleapis.com/maps/api/place/search/json?location=" + jsonLAT + "," + jsonLNG + "&radius=" + searchRadius + "&types=" + keyword + searchName + "&sensor=false");
					jQuery.getJSON(JSON, function(data) {
					
				if(data.status == "REQUEST_DENIED") {
                    alert('Google Places API has returned "REQUEST_DENIED". \n\n Please check that you have entered your Google Places API key corectly \n and have enabled it in the Google API console. Also try setting your key to allow "any referrers"');
				} else {
					classAdder[type] = document.getElementById(type);
					pAddClass(classAdder[type], "zeroResults");
						if(data.status == "ZERO_RESULTS") {
							classAdder = document.getElementById(type);
							classAdder.attributes.getNamedItem("caption").value = "";
							//removeClass(classAdder, "visibleLayer");
							pAddClass(classAdder, "zero_results");
							mapLoading.style.display = "none";
						} else {
							classAdder = document.getElementById(type);
							pRemoveClass(classAdder, "zero_results");
							for (i = 0; i < data.results.length; i++) {
							  var result = data.results[i];
							  var latlng = new google.maps.LatLng(parseFloat(result.geometry.location.lat), parseFloat(result.geometry.location.lng));
							  var resultHTML = "api:" + result.reference;
							  createMarker(latlng, i, resultHTML, type, type, "api", result.name, result.icon);
							  if (hider == "hidden") {
								markerGroups[type][i].hide();
							  }
							}
						}
					}
						mapLoading.style.display = "none";
					});
				} else {
				mapLoading.style.display = "none";
						//console.log(keyword + " - " + type); 
				}
  }
  return 1;
}

function pHasClass(ele, cls) {
  return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

function pAddClass(ele, cls) {
  if (!this.pHasClass(ele, cls)) {
	  ele.className += " " + cls;
  }
}

function pRemoveClass(ele, cls) {
  if (pHasClass(ele, cls)) {
    var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
    ele.className = ele.className.replace(reg, ' ');
  }
}

function toggleGroup(type) {
  classAdder = document.getElementById(type);
  if (markerGroups[type].length !== 0) {
    for (var i = 0; i < markerGroups[type].length; i++) {
      var marker = markerGroups[type][i];
      if (marker.getVisible() === false) {
        classAdder.attributes.getNamedItem("caption").value = "";
        pAddClass(classAdder, "visibleLayer");
        marker.setVisible(true);
      } else {
        classAdder.attributes.getNamedItem("caption").value = "hidden";
        pRemoveClass(classAdder, "visibleLayer");
        marker.setVisible(false);
      }
    }
  } else {
    classAdder.attributes.getNamedItem("caption").value = "";
    pAddClass(classAdder, "visibleLayer");
	doSearch(classAdder.getAttribute('data-title'), type); 
  }
}

function handleDDErrors() {
  var ddError = "<h4>Unable to retreive driving directions to this location.</h4><a onclick='closeDirections();' style='text-decoration: underline; cursor: pointer; color: blue'>close</a>";
  var ddErrorDiv = document.createElement('div');
  ddErrorDiv.setAttribute('id', 'ddError');
  ddBoxDiv.appendChild(ddErrorDiv);
  ddErrorDiv.innerHTML = ddError;
  ddErrorDiv.style.width = "50%";
  ddErrorDiv.style.marginLeft = "10%";
}


function closeDirections() {
  mapDiv.removeChild(document.getElementById("ddFrame"));
}

function showDirections(toAddress) {
  var ddFrame = document.createElement('div');
  ddFrame.setAttribute('id', 'ddFrame');
  mapDiv.appendChild(ddFrame);
  centerBox("ddFrame", "poi_map");
  ddBoxDiv = document.createElement('div');
  ddBoxDiv.setAttribute('id', 'ddBox');
  ddFrame.appendChild(ddBoxDiv);
  ddBoxDiv.style.position = "absolute";
  ddBoxDiv.style.left = "5px";
  var ddBoxClose = document.createElement('a');
  ddBoxClose.setAttribute('id', 'ddBoxClose');
  ddFrame.appendChild(ddBoxClose);
  ddBoxClose.style.position = "absolute";
  ddBoxClose.style.zIndex = "10";
  ddBoxClose.style.top = "0px";
  ddBoxClose.style.left = (ddFrame.offsetWidth - ddBoxClose.offsetWidth - 4) + "px";
  ddBoxClose.onclick = function() { 
    closeDirections();
  };
  var ddBoxPrint = document.createElement('a');
  ddBoxPrint.setAttribute('id', 'ddBoxPrint');
  ddFrame.appendChild(ddBoxPrint);
  ddBoxPrint.innerHTML = "<span>Print</span>";
  ddBoxPrint.style.position = "absolute";
  ddBoxPrint.style.zIndex = "10";
  ddBoxPrint.style.top = "4px";
  ddBoxPrint.style.left = (ddFrame.offsetWidth - ddBoxClose.offsetWidth - 29) + "px";
  ddBoxPrint.setAttribute("href", "print/print.html?start=" + escape(startAddress) + "&end=" + escape(toAddress));
  ddBoxPrint.setAttribute("target", "_blank");
  directionsDisplay.setMap(null);
  directionsDisplay.setMap(map);
  directionsDisplay.setPanel(ddBoxDiv);

  var loadStr = "from: " + startAddress + " to: " + toAddress;

    var request = {
        origin: startAddress, 
        destination: toAddress,
        travelMode: travMode
    };
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
		return false;
      } else {
		handleDDErrors();  
		return false;
	  }
    });


}

function closeStreetView() {
	controlToggle("hide");
	mapDiv.removeChild(document.getElementById("svFrame"));
	map.setStreetView(null);
	map.getStreetView().setVisible(false);
	controlToggle("hide");
}

function showStreetView(lat, lng, address1, address2) {
  var svFrame = document.createElement('div');
  svFrame.setAttribute('id', 'svFrame');
  mapDiv.appendChild(svFrame);
  centerBox("svFrame", "poi_map");
  var svBoxDiv = document.createElement('div');
  svBoxDiv.setAttribute('id', 'svBox');
  svFrame.appendChild(svBoxDiv);
  svBoxDiv.style.position = "absolute";
  var svBoxClose = document.createElement('a');
  svBoxClose.setAttribute('id', 'svBoxClose');
  svFrame.appendChild(svBoxClose);
  svBoxClose.style.position = "absolute";
  svBoxClose.style.zIndex = "10";
  svBoxClose.style.top = "0px";
  svBoxClose.style.left = (svFrame.offsetWidth - svBoxClose.offsetWidth - 5) + "px";
  svBoxClose.onclick = function() { 
    closeStreetView();
  };
  var svLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
	var panoramaOptions = {
		position: svLatLng
	};
	var panorama = new  google.maps.StreetViewPanorama(document.getElementById("svBox"), panoramaOptions);
	map.setStreetView(panorama);
	
	var streetViewAvailable;
	var streetViewCheck = new google.maps.StreetViewService();  
	
	streetViewCheck.getPanoramaByLocation(svLatLng, 50, function(result, status) {
    if (status == "OK") {
        streetViewAvailable = 0;        
    }else{
		closeStreetView();
		alert("Street View unavailable for this location");
    }
  controlToggle("hide");
});

}


function toggleOverlay(layerState, layer, control) {
  if (overlayControls === true) {	
	  var toggleControl = document.getElementById(control);
	  if (layerState == "off") {
		if (layer.isHidden() === true) {
		  layer.show();
		} else {
		  map.addOverlay(layer);
		}
		toggleControl.style.backgroundColor = "#fc9";
		toggleControl.onclick = function() { toggleOverlay('on', layer , control );};
	  }
	  if (layerState == "on") {
		layer.hide();
		toggleControl.style.backgroundColor = "#fff";
		toggleControl.onclick = function() { toggleOverlay('off', layer , control );};
	  }
  }
}


function getCategories(initial) {
  var i;
  var elem = document.getElementById(categoriesList);
  if (initial == 1) {
  jsonGroups = "";
  jsonGroups = '{ xml: [], "pin": [] ';
  for (i = 0; i < elem.childNodes.length; i++) {
    if (elem.childNodes[i].nodeName == "LI") {
      jsonGroups = jsonGroups + ',  "' + elem.childNodes[i].attributes.getNamedItem("id").value + '": [] ';
    }
  }
  jsonGroups = jsonGroups + "}";
  markerGroups = eval('(' + jsonGroups + ')');

for (i = 0; i < elem.childNodes.length; i++) {
      if (elem.childNodes[i].nodeName == "LI") {
        var elemID = elem.childNodes[i].attributes.getNamedItem("id").value;
        if (elemID != "user") {
			elem.childNodes[i].innerHTML = "<a style='background: url(" + iconPath + "menu/" + elemID + ".png) 0px -1px no-repeat' onclick='" + 'toggleGroup("' + elemID + '")' + "'>" + elem.childNodes[i].innerHTML + "</a>";
		} else {
          elem.childNodes[i].innerHTML = '<form id="userPOIForm" action="#" onsubmit="userPOIFind(this.userPOI.value); return false"><input id="userPOITxt" size="20" name="userPOI" placeholder="' + elem.childNodes[i].innerHTML + '" type="text"><input id="userPOIButton" value="Go" type="submit"> </form>';
        }
        if (pHasClass(elem.childNodes[i], "hidden") !== null) {
          elem.childNodes[i].setAttribute("caption", "hidden");
        } else {
          elem.childNodes[i].setAttribute("caption", "");
        }
        if (elem.childNodes[i].attributes.getNamedItem("caption").value != "hidden") {
          classAdder = document.getElementById(elemID);
          pAddClass(classAdder, "visibleLayer");
        }
      }
    }
  }
  for (i = 0; i < elem.childNodes.length; i++) {
    if (elem.childNodes[i].nodeName == "LI") {
		var catType = elem.childNodes[i].attributes.getNamedItem("id").value;
		//result = doSearch(elem.childNodes[i].attributes.getNamedItem("title").value, elem.childNodes[i].attributes.getNamedItem("id").value);
		result = doSearch(elem.childNodes[i].getAttribute('data-title'), elem.childNodes[i].attributes.getNamedItem("id").value);

    }
  }
    //     mapLoading.style.display = "none";
}
function distance(lat1,lon1,lat2,lon2) {
    var R = 6371; // km (change this constant to get miles)
    var dLat = (lat2-lat1) * Math.PI / 180;
    var dLon = (lon2-lon1) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    if (d>1) {return Math.round(d) * 1000;
     } else if (d<=1){ return Math.round(d*1000);
	} else { 
    return d;
	}
}

function userPOIFind(searchText) {
  document.getElementById("user").setAttribute("name", searchText);
  document.getElementById("user").setAttribute('data-title', searchText)
  getCategories(0);
}

function findAddress(address, HTML) {
  if (HTML === undefined) {
    HTML = "<strong>" + address + "</strong>";
  }
  markerHTML = HTML;
  
  	var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
   		addressSet = 1;
		startAddress = address;
		searchCenter = results[0].geometry.location;
		createMarker(searchCenter, 0, markerHTML, "pin", "pin", "");
		getCategories(0);
		if (mapExtra === true) {
			mapPost();
		}

	} else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
	
}

function loadPOI() {
	setupAddress();
	mapDiv = document.getElementById(mapDivID);
	var myLatlng = new google.maps.LatLng(0,0);
	var myOptions = {
		zoom: zoomLevel,
		scrollwheel: false,
		disableDoubleClickZoom: true,
		center: myLatlng,
		mapTypeControl: true,
		mapTypeControlOptions: {
  			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
        	position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		zoomControl: true,
		zoomControlOptions: {
		  style: google.maps.ZoomControlStyle.SMALL,
  		  position: google.maps.ControlPosition.LEFT_TOP
		},
		panControl: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		overviewMapControl: true,
		overviewMapControlOptions: {
			opened: false
			}
	};
	map = new google.maps.Map(mapDiv, myOptions);

	// var styles = [
	//   {
	// 	"featureType": "poi",
	// 	"stylers": [
	// 	  { "visibility": "off" }
	// 	]
	//   }
	// ] 


	var styles = [
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            }
        ]
    }
] 

 
	map.setOptions({styles: styles});
	
	if(directionsMode == 'DRIVING') {
		travMode = google.maps.DirectionsTravelMode.DRIVING;
	} else {
		travMode = google.maps.DirectionsTravelMode.WALKING;
	}
	//streetview stuff
	var tempPanorama = map.getStreetView();

	google.maps.event.addListener(tempPanorama, 'visible_changed', function() {
	    if (tempPanorama.getVisible()) {
			controlToggle("hide");
		} else {
			controlToggle("show");
	    }
	});
	
	infoBox();
	
	// Create the search box and link it to the UI element.
	  var input = document.getElementById('searchTxt');
	  var searchBox = new google.maps.places.SearchBox(input);
	  
	google.maps.event.addListener(searchBox, 'places_changed', function() {
		var goPlaces = searchBox.getPlaces();
		//findAddress(place.geometry.location);
	});

	var geocoder = new google.maps.Geocoder();
    var address = startAddress;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
   		addressSet = 1;
		searchCenter = results[0].geometry.location;
        getCategories(1);
		createMarker(searchCenter, 0, markerHTML, "pin", "pin","");
//		var trafficLayer = new google.maps.TrafficLayer();
//		trafficLayer.setMap(map);
	    google.maps.event.addListener(map, "dragend", function () {
				controlToggle("show");
			    getCategories(0);
	    });
	    google.maps.event.addListener(map, "zoom_changed", function () {
				controlToggle("show");
			    getCategories(0);
	    });
		mobileScroll(sidebarDivID);
		if (autoGeolocation === true) {
					geotarget();
		}
		if (mapExtra === true) {
			mapPost();
		}
		} else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });

}
jQuery(document).ready(function() {
  //loadPOI();
});