<?php

if( empty($_GET['id']) ):?>
	<script type="text/javascript"> window.location.replace("http://localhost/ss/app/#no_listing");</script>
<?php endif; 

// SECURE CONNECTION SETUP
	define("PB_WEBSERVICEENDPOINT"	, "http://spacestation.force.com/pba__WebserviceListingsQuery"); // Please enter your Propertybase webservice endpoint here
	define("PB_SECURITYTOKEN"		, "29bac54992830136725442d9c3c5ea086377d8065900263fb21986a539e72083"); // Please enter your security token here

	if (empty($_POST["bedrooms_from"])){
		$default_bedrooms_from = 1;
	}
		else{ 
		$default_bedrooms_from = $_POST["bedrooms_from"];
		}
// GET FROM TO PARAM FUNCTION		
	function getFromToParam($from,$to){
		
		if (empty($from) && empty($to)) return null;
	
		$p = '[';
		if (!empty($from)) $p .= $from;
		$p .= ';';
		if (!empty($to)) $p .= $to;
		$p .= ']';
				
		return $p;	 	
	}

// if there's variable in query and is not empty	
	if(isset($_POST["reference"])){		$reference 		= $_POST["reference"];}
	if(isset($_POST["price_from"])){	$price_from 	= $_POST["price_from"];}
	if(isset($_POST["price_to"])){		$price_to 		= $_POST["price_to"];}
	if(isset($_POST["size_from"])){		$size_from 		= $_POST["size_from"];}
	if(isset($_POST["bedrooms_from"])){	$bedrooms_from 	= $default_bedrooms_from ;}
	if(isset($_POST["price_from"])){	$priceParam 	= getFromToParam($price_from	,$price_to);}
	if(isset($_POST["size_from"])){		$sizeParam 		= getFromToParam($size_from		,null);}
										$bedsParam 		= getFromToParam($default_bedrooms_from	,null);
	
	$doSearch = !(empty($reference) && empty($priceParam) && empty($sizeParam)&& empty($bedsParam)  );
	
	
	$xmlResult		= null;
	$errorMessage 	= null;

/////////////// QUERY ARRAY ///////////////
	$reqArray = array("token" 			=> PB_SECURITYTOKEN,
					  "id"				=> $_GET['id'],	
					  "fields"			=> "Id;Stamp_Duty__c;Related_shop__c;Related_discover__c;Related_enjoy__c;pba__Status__c;pba__ListingType__c;name;pba__PropertyType__c;Weekly_Rent__c;Room_list__c;Tenure__c;Council_Tax_Band__c;Local_Authority__c;Years_Remaining_Leasehold_only__c;pba__ListingPrice_pb__c;pba__Bedrooms_pb__c;pba__FullBathrooms_pb__c;pba__totalarea_pb__c;pba__Description_pb__c;pba__Longitude_pb__c;pba__Latitude_pb__c;pba__Address_pb__c;pba__City_pb__c;pba__PostalCode_pb__c;FF_Aircon__c;FF_Architect__c;FF_Award__c;FF_Balcony__c;FF_Basement__c;FF_Blue_plaque__c;FF_Bottle__c;FF_Brick__c;FF_Built__c;FF_Celings__c;FF_Champagne__c;FF_Church__c;FF_Cinema__c;FF_Concierge__c;FF_Designer__c;FF_Eco__c;FF_Elephant__c;FF_Extension__c;FF_Factory__c;FF_Fireplace__c;FF_Garden__c;FF_Gym__c;FF_High_Specification__c;FF_History__c;FF_Hot_tap__c;FF_Lateral_Space__c;FF_Library__c;FF_Lift__c;FF_Light__c;FF_Listed_Grade_I__c;FF_Listed_Grade_II__c;FF_Map__c;FF_Mews__c;FF_Mezzanine__c;FF_Parking__c;FF_Penthouse__c;FF_Planning__c;FF_Pool__c;FF_Refurbished__c;FF_Roof_terrace__c;FF_School__c;FF_Security__c;FF_Solar__c;FF_Sound_system__c;FF_South_facing__c;FF_Stairs__c;FF_Townhouse__c;FF_Tube__c;FF_Underfloor_heating__c;FF_View__c;FF_Warehouse__c;FF_Windows__c;FF_Wine_cellar__c;FF_Wooden_floors__c;",
					  "itemsperpage"	=> "1",
					  // "orderby"			=> "pba__ListingPrice_pb__c;ASC",
					  "getvideos"		=> "true",
					  "getdocuments"	=> "true",
		              "debugmode"		=> "true"
					  );

// add FILTERS to QUERY ARRAY	
	if (!empty($reference))		$reqArray["name"] 								= '%' . $reference . '%';
	if (!empty($priceParam))	$reqArray["pba__ListingPrice_pb__c"] 			= $priceParam;
	if (!empty($size_from)) 	$reqArray["pba__TotalArea_pb__c"] 				= $sizeParam;
	if (!empty($default_bedrooms_from)) $reqArray["pba__Bedrooms_pb__c"] 		= $bedsParam;
	$reqArray["show_on_website__c"] 			= "true"; 

// BUILD HTTP QUERY STRING	
	$query 		= http_build_query($reqArray,'','&');
// RETURN XML RESULT
	$xmlResult 	= simplexml_load_file(PB_WEBSERVICEENDPOINT . "?" . $query);
	
	if (!empty($xmlResult->errorMessages->message)) {
		$errorMessage = 'Error: '.$xmlResult->errorMessages->message;
	} else {
		$debugMessages = 'Debug: '.$xmlResult->debugMessages->message;
	}

	$DisplayQuery = $query;
	$DisplayDebug = $debugMessages;

?>