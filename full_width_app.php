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

<?php

// VARS
$SuccessVis = "none";
$reference="";
$price_to = 0;
$price_from = 0;
$reference = "";
$orderby = ""; 
$itemsperpage ="";
$recordtypes ="sale;rent";
$propertytype ="";
$tenure ="";
$propertystatus ="";

// SECURE CONNECTION SETUP
define("PB_WEBSERVICEENDPOINT"	, "http://spacestation.force.com/pba__WebserviceListingsQuery"); // Please enter your Propertybase webservice endpoint here
define("PB_SECURITYTOKEN"		, "29bac54992830136725442d9c3c5ea086377d8065900263fb21986a539e72083"); // Please enter your security token here

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

// SET DEDAULTS FOR SELECTED FILTERS
	
	// BEDROOMS
	if (empty($_POST["bedrooms_from"])){ 					// if variable is empty
		$default_bedrooms_from = 1; 	 					// apply default value
	}else{ 								 					// otherwise 
		$default_bedrooms_from = $_POST["bedrooms_from"]; 	// retrieve POSTEed value
	}
	// BATHROOMS
	if (empty($_POST["bathrooms_from"])){ 					
		$default_bathrooms_from = 1; 	 					
	}else{ 								 					
		$default_bathrooms_from = $_POST["bathrooms_from"]; 	
	}
	// ORDER BY
	if (empty($_POST["orderby"])){
		$default_orderby = ""; //* pba__ListingPrice_pb__c;ASC
	}else{ 
		$default_orderby = $_POST["orderby"];
	}
	// PER PAGE
	if (empty($_POST["itemsperpage"])){
		$default_perpage = "20"; //-
	}else{ 
		$default_perpage = $_POST["itemsperpage"];
	}
	//RECORDTYPES
	if (empty($_POST["recordtypes"])){
		$default_recordtypes = "sale;rent"; //=
	}else{ 
		$default_recordtypes = $_POST["recordtypes"];
	}
	//PROPERTYTYPE
	if (empty($_POST["propertytype"])){
		$default_propertytype = ""; //=
	}else{ 
		$default_propertytype = $_POST["propertytype"];
	}
	//TENURE
	if (empty($_POST["tenure"])){
		$default_tenure = ""; //=
	}else{ 
		$default_tenure = $_POST["tenure"];
	}
	//PROPERTYSTATUS
	if (empty($_POST["propertystatus"])){
		$default_propertystatus = ""; //=
	}else{ 
		$default_propertystatus = $_POST["propertystatus"];
	}

// if there's variable in query and is not empty
if(isset($_POST["reference"])){			$reference 				= $_POST["reference"];		}
if(isset($_POST["price_from"])){		$price_from 			= $_POST["price_from"];		}
if(isset($_POST["price_to"])){			$price_to 				= $_POST["price_to"];		}
if(isset($_POST["bedrooms_from"])){		$bedrooms_from 			= $default_bedrooms_from;	}                      
if(isset($_POST["bathrooms_from"])){	$bathrooms_from 		= $default_bathrooms_from;	}                     
if(isset($_POST["orderby"])){			$orderby 				= $default_orderby;			}                 
if(isset($_POST["itemsperpage"])){		$itemsperpage 			= $default_perpage;			}                         
if(isset($_POST["recordtypes"])){		$recordtypes 			= $default_recordtypes;		}                 
if(isset($_POST["propertytype"])){		$propertytype 			= $default_propertytype;	}                     
if(isset($_POST["tenure"])){			$tenure 				= $default_tenure;			}                   
if(isset($_POST["propertystatus"])){	$propertystatus 		= $default_propertystatus;	}                   
if(isset($_POST["page"])){				$page 					= $_POST["page"];			}                       
if(isset($_POST["price_from"])){		$priceParam 			= getFromToParam($price_from,$price_to);}
										$bedsParam 				= getFromToParam($default_bedrooms_from	,null);
										$bathsParam 			= getFromToParam($default_bathrooms_from ,null);

$doSearch = !(empty($reference) && empty($priceParam) && empty($sizeParam)&& empty($bedsParam) );

if(isset($_POST["page"])){if (!is_numeric($page) || $page < 0 ) $page = 0;}else{$page = 0;}

$xmlResult		= null;
$errorMessage 	= null;


/////////////// QUERY ARRAY ///////////////
	$reqArray = array("token" 			=> PB_SECURITYTOKEN,
					  "fields"			=> "ID;name;pba__ListingType__c;pba__PropertyType__c;Tenure__c;pba__Status__c;pba__ListingPrice_pb__c;pba__description_pb__c;pba__Bedrooms_pb__c;pba__FullBathrooms_pb__c;pba__totalarea_pb__c;",
		              "page" 			=> $page ,
		              "getvideos"		=> "true",
		              "debugmode"		=> "true"

		              );
// add FILTERS to QUERY ARRAY
	if (!empty($reference))		$reqArray["name"] 								= '%' . $reference . '%';
	if (!empty($priceParam))	$reqArray["pba__ListingPrice_pb__c"] 			= $priceParam;
	if (!empty($default_bedrooms_from)) $reqArray["pba__Bedrooms_pb__c"] 		= $bedsParam;
	if (!empty($default_bathrooms_from)) $reqArray["pba__FullBathrooms_pb__c"] 	= $bathsParam;
	if (!empty($default_orderby)) $reqArray["orderby"] 							= $orderby; 
	if (!empty($default_perpage)) $reqArray["itemsperpage"] 					= $itemsperpage; 
	if (!empty($default_recordtypes)) $reqArray["recordtypes"] 					= $recordtypes; 
	if (!empty($default_propertytype)) $reqArray["pba__PropertyType__c"] 		= $default_propertytype; 
	if (!empty($default_tenure)) $reqArray["Tenure__c"] 						= $default_tenure; 
	if (!empty($default_propertystatus)) $reqArray["pba__status__c"] 			= $default_propertystatus; 

// BUILD HTTP QUERY STRING
	$query 		= http_build_query($reqArray,'','&');
// RETURN XML RESULT
	$xmlResult 	= simplexml_load_file(PB_WEBSERVICEENDPOINT . "?" . $query);
	
	if (!empty($xmlResult->errorMessages->message)) {
		$errorMessage = 'Error: '.$xmlResult->errorMessages->message;
	} else {
		$debugMessages = 'Debug: '.$xmlResult->debugMessages->message;
		$previousPage 	= $page > 0 ? $page - 1 : null;
		$nextPage 		= ($xmlResult->listingsPerPage * ($page+1) < $xmlResult->numberOfListings) ? $page + 1 : null;
	}
	
	$DisplayQuery = $query;
	$DisplayDebug = $debugMessages;

	
?>

					<!-- BEGIN ERROR -->
			<?php if (!empty($errorMessage) || !empty($DisplayQuery)|| !empty($DisplayxmlResult) ): ?>
			<div id="error">
				<?php echo $errorMessage; ?>
				<br>
				<?php echo $DisplayDebug ?>
				<br>
				<?php echo $DisplayQuery; ?>
				<br>
				<br>
			</div>
			<?php else: ?>
			<?php endif;?>	
			
			<!-- END ERROR -->
		
			<div id="container_multiple_view">
			<!-- BEGIN SEARCHFORM -->
				<div id="searchform">
			<form method="post" id="theForm" >
		
				<!-- RECORD TYPES -->
						<fieldset id="record-types">
							<input type="radio" name="recordtypes" id="all-types" value="" checked="checked">
							<label for="all-types">ALL</label>
							<br>
							<input type="radio" name="recordtypes" id="sale" value="sale"> 
							<label for="sale">SALE</label> 				
							<br>
		  					<input type="radio" name="recordtypes" id="rent" value="rent">
		  					<label for="rent">RENT</label> 		
		  				</fieldset>
		
		  			<fieldset id="parameters">
						<input type="text" name="reference" placeholder="Property Address" value="<?php echo $reference;?>" style="width: 170px;">
						<br>
						<input class="slider" id="slider1" type="range" min="0" max="100" value="0" oninput="showValue(value, 1);" onchange="showValue(value, 1);" />
			
						<!-- // DETECT CURRENT RECORD TYPE -->
			
						 <!-- IF SALE -->
						<?php if(isset($recordtypes) && ($recordtypes == 'sale') ){ ?>
						<select name="price_from">
							<option value="0">No Min</option>
							<option value="500000">&#163;500,000</option>
							<option value="550000">&#163;550,000</option>
							<option value="600000">&#163;600,000</option>
							<option value="650000">&#163;650,000</option>
							<option value="700000">&#163;700,000</option>
							<option value="750000">&#163;750,000</option>
							<option value="800000">&#163;800,000</option>
							<option value="900000">&#163;900,000</option>
							<option value="1000000">&#163;1,000,000</option>
							<option value="1100000">&#163;1,100,000</option>
							<option value="1200000">&#163;1,200,000</option>
							<option value="1300000">&#163;1,300,000</option>
							<option value="1400000">&#163;1,400,000</option>
							<option value="1500000">&#163;1,500,000</option>
							<option value="1750000">&#163;1,750,000</option>
							<option value="2000000">&#163;2,000,000</option>
							<option value="2500000">&#163;2,500,000</option>
						</select>
		
						<select name="price_to">
							<option value="0">No Max</option>
							<option value="500000">&#163;500,000</option>
							<option value="550000">&#163;550,000</option>
							<option value="600000">&#163;600,000</option>
							<option value="650000">&#163;650,000</option>
							<option value="700000">&#163;700,000</option>
							<option value="750000">&#163;750,000</option>
							<option value="800000">&#163;800,000</option>
							<option value="900000">&#163;900,000</option>
							<option value="1000000">&#163;1,000,000</option>
							<option value="1100000">&#163;1,100,000</option>
							<option value="1200000">&#163;1,200,000</option>
							<option value="1300000">&#163;1,300,000</option>
							<option value="1400000">&#163;1,400,000</option>
							<option value="1500000">&#163;1,500,000</option>
							<option value="1750000">&#163;1,750,000</option>
							<option value="2000000">&#163;2,000,000</option>
							<option value="2500000">&#163;2,500,000</option>
							<option value="3000000">&#163;3,000,000</option>
							<option value="3500000">&#163;3,500,000</option>
							<option value="4000000">&#163;4,000,000</option>
							<option value="4500000">&#163;4,500,000</option>
							<option value="5000000">&#163;5,000,000</option>
						</select>
						<br>
						 <!-- END IF SALE + IF RENT -->
						<?php }else if(isset($recordtypes) && ($recordtypes == 'rent') ){ ?>
		
							<select name="price_from">
							<option value="0">No Min rent</option>
							<option value="300">&#163;300 <?php echo '(&#163;'. number_format((float)(300*52)/12) .'/month)'; ?></option>
							<option value="350">&#163;350 <?php echo '(&#163;'. number_format((float)(350*52)/12) .'/month)'; ?></option>
							<option value="400">&#163;400 <?php echo '(&#163;'. number_format((float)(400*52)/12) .'/month)'; ?></option>
							<option value="450">&#163;450 <?php echo '(&#163;'. number_format((float)(450*52)/12) .'/month)'; ?></option>
							<option value="500">&#163;500 <?php echo '(&#163;'. number_format((float)(500*52)/12) .'/month)'; ?></option>
							<option value="550">&#163;550 <?php echo '(&#163;'. number_format((float)(550*52)/12) .'/month)'; ?></option>
							<option value="600">&#163;600 <?php echo '(&#163;'. number_format((float)(600*52)/12) .'/month)'; ?></option>
							<option value="650">&#163;650 <?php echo '(&#163;'. number_format((float)(650*52)/12) .'/month)'; ?></option>
							<option value="700">&#163;700 <?php echo '(&#163;'. number_format((float)(700*52)/12) .'/month)'; ?></option>
							<option value="750">&#163;750 <?php echo '(&#163;'. number_format((float)(750*52)/12) .'/month)'; ?></option>
							<option value="800">&#163;800 <?php echo '(&#163;'. number_format((float)(800*52)/12) .'/month)'; ?></option>
							<option value="1000">&#163;1,000 <?php echo '(&#163;'. number_format((float)(1000*52)/12) .'/month)'; ?></option>
							<option value="1500">&#163;1,500 <?php echo '(&#163;'. number_format((float)(1500*52)/12) .'/month)'; ?></option>
							<option value="2000">&#163;2,000 <?php echo '(&#163;'. number_format((float)(2000*52)/12) .'/month)'; ?></option>
							<option value="2500">&#163;2,500 <?php echo '(&#163;'. number_format((float)(2500*52)/12) .'/month)'; ?></option>
							<option value="3000">&#163;3,000 <?php echo '(&#163;'. number_format((float)(3000*52)/12) .'/month)'; ?></option>
						</select>
		
						<select name="price_to">
							<option value="0">No Max rent</option>
							<option value="300">&#163;300 <?php echo '(&#163;'. number_format((float)(300*52)/12) .'/month)'; ?></option>
							<option value="350">&#163;350 <?php echo '(&#163;'. number_format((float)(350*52)/12) .'/month)'; ?></option>
							<option value="400">&#163;400 <?php echo '(&#163;'. number_format((float)(400*52)/12) .'/month)'; ?></option>
							<option value="450">&#163;450 <?php echo '(&#163;'. number_format((float)(450*52)/12) .'/month)'; ?></option>
							<option value="500">&#163;500 <?php echo '(&#163;'. number_format((float)(500*52)/12) .'/month)'; ?></option>
							<option value="550">&#163;550 <?php echo '(&#163;'. number_format((float)(550*52)/12) .'/month)'; ?></option>
							<option value="600">&#163;600 <?php echo '(&#163;'. number_format((float)(600*52)/12) .'/month)'; ?></option>
							<option value="650">&#163;650 <?php echo '(&#163;'. number_format((float)(650*52)/12) .'/month)'; ?></option>
							<option value="700">&#163;700 <?php echo '(&#163;'. number_format((float)(700*52)/12) .'/month)'; ?></option>
							<option value="750">&#163;750 <?php echo '(&#163;'. number_format((float)(750*52)/12) .'/month)'; ?></option>
							<option value="800">&#163;800 <?php echo '(&#163;'. number_format((float)(800*52)/12) .'/month)'; ?></option>
							<option value="1000">&#163;1,000 <?php echo '(&#163;'. number_format((float)(1000*52)/12) .'/month)'; ?></option>
							<option value="1500">&#163;1,500 <?php echo '(&#163;'. number_format((float)(1500*52)/12) .'/month)'; ?></option>
							<option value="2000">&#163;2,000 <?php echo '(&#163;'. number_format((float)(2000*52)/12) .'/month)'; ?></option>
							<option value="2500">&#163;2,500 <?php echo '(&#163;'. number_format((float)(2500*52)/12) .'/month)'; ?></option>
							<option value="3000">&#163;3,000 <?php echo '(&#163;'. number_format((float)(3000*52)/12) .'/month)'; ?></option>
							<option value="3500">&#163;3,500 <?php echo '(&#163;'. number_format((float)(3500*52)/12) .'/month)'; ?></option>
							<option value="4000">&#163;4,000 <?php echo '(&#163;'. number_format((float)(4000*52)/12) .'/month)'; ?></option>
							<option value="4500">&#163;4,500 <?php echo '(&#163;'. number_format((float)(4500*52)/12) .'/month)'; ?></option>
							<option value="5000">&#163;5,000 <?php echo '(&#163;'. number_format((float)(5000*52)/12) .'/month)'; ?></option>
						</select>
						<br>
		
						<?php } ?>
						 <!-- END IF RENT -->
						<select name="propertytype">
							<option value="">Type</option>
							<option value="Apartment">Apartment</option>
							<option value="Commercial">Commercial</option>
							<option value="Condo">Condo</option>
							<option value="Detached">Detached</option>
							<option value="End Terrace">End Terrace</option>
							<option value="House">House</option>
							<option value="Loft">Loft</option>
							<option value="Maisonette">Maisonette</option>
							<option value="Multi Family">Multi Family</option>
							<option value="Other">Other</option>
							<option value="Penthouse">Penthouse</option>
							<option value="Single Family">Single Family</option>
							<option value="Studio">Studio</option>
							<option value="Terraced">Terraced</option>
							<option value="Townhouse">Townhouse</option>
							<option value="Villa">Villa</option>
						</select>
						 <!-- IF SALE -->
							<?php if(isset($recordtypes) && ($recordtypes == 'sale') ){ ?> 
							<select name="tenure">
								<option value="">Tenure</option>
								<option value="Leasehold">Leasehold</option>
								<option value="Freehold">Freehold</option>
								<option value="Share of Freehold">Share of Freehold</option>
							</select>
						<?php } ?>		
						<!-- END IF SALE -->
						<select name="bedrooms_from">
							<option value="1">Bedrooms</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5+</option>
						</select>
		
					<br>
						<select name="bathrooms_from">
							<option value="1">Bathrooms</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5+</option>
						</select>
		
						<div class="filter__submit">
								<a href="#" class="js-clear-all-filters">Clear Filters</a>
								<!-- <button type="submit" class="js-run-pb-search">See Results</button> -->
						</div>
					
				</fieldset>
		
				<fieldset id="bottomFilters">
						<fieldset id="property-status">
							<input type="radio" name="propertystatus" id="all-status" value="" checked>
							<label for="all-status">All</label>
							
							<input type="radio" name="propertystatus" id="active" value="Active">
							<label for="active">Active</label>  				
		  					<!-- //////// if SALE or ALL /////////// -->
						<?php if(isset($recordtypes) && ($recordtypes == 'sale' ||  $recordtypes == 'sale;rent') ){ ?>
		  					<input type="radio" name="propertystatus" id="sold" value="Sold">
		  					<label for="sold">Sold</label>	
		  				<?php } ?>	
		  					<input type="radio" name="propertystatus" id="blocked" value="Blocked">
		  					<label for="blocked">Blocked</label>	
		  				<!-- //////// if not SALE or ALL = RENT /////////// -->
						<?php if(isset($recordtypes) && ($recordtypes != 'sale' ||  $recordtypes == 'sale;rent') ){ ?>	
		  					<input type="radio" name="propertystatus" id="rented" value="Rented">
		  					<label for="rented">Rented</label>	
		  				<?php } ?>		
		  					<input type="radio" name="propertystatus" id="archived" value="Archived">
		  					<label for="archived">Archived</label>	
		  					
		  					<input type="radio" name="propertystatus" id="in-preparation" value="In Preparation">
		  					<label for="in-preparation">In Preparation</label>	
		  					
		  					<input type="radio" name="propertystatus" id="in-acquisition" value="In Acquisition">	
		  					<label for="in-acquisition">In Acquisition</label>
		  					
		  					<input type="radio" name="propertystatus" id="reserved" value="Reserved">		
		  					<label for="reserved">Reserved</label>
		  				</fieldset>
		
		
						<select name="orderby">
							<option value="">Order By:</option>
							<option value="CreatedDate;DESC">Created Date DESC</option>
							<option value="CreatedDate;ASC">Created Date ASC</option>
							<option value="pba__ListingPrice_pb__c;DESC">Price DESC</option>
							<option value="pba__ListingPrice_pb__c;ASC">Price ASC</option>
							<option value="pba__Bedrooms_pb__c;DESC">Bedrooms DESC</option>
							<option value="pba__Bedrooms_pb__c;ASC">Bedrooms ASC</option>
						</select>
		
						<select name="itemsperpage">
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="50">50</option>
						</select>
				</fieldset>		
			</form>
				</div>
				<div class="view">
					<a id="list" href="#"> List View </a><span>|<span>
		    		<a id="grid" href="#"> Grid View </a>
				</div>
			<!-- END SEARCHFORM -->      
		             
			
			<!-- BEGIN EMPTY RESULT -->
			<?php if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0)){ ?>
				<div id="noresult">
					no listings found: 
				</div>
			<?php }else{ ?>
				<!-- END EMPTY RESULT -->
				
				
				<!-- PAGINATION -->
					<div id="paging">
					
						<?php if($nextPage !== null):?>
							<form method="post">
								<input type="hidden" name="page" value="<?php echo $nextPage;?>">
								<input type="hidden" name="reference" value="<?php echo $reference;?>">
								<input type="hidden" name="price_from" value="<?php echo $price_from;?>">
								<input type="hidden" name="price_to" value="<?php echo $price_to;?>">
								<!-- <input type="hidden" name="size_from" value="<?php echo $size_from;?>"> -->
								<input type="hidden" name="bedrooms_from" value="<?php echo $bedrooms_from;?>">	
								<input type="hidden" name="bathrooms_from" value="<?php echo $bathrooms_from;?>">	
								<input type="hidden" name="orderby" value="<?php echo $orderby;?>">	
								<input type="hidden" name="itemsperpage" value="<?php echo $itemsperpage;?>">
								<input type="hidden" name="recordtypes" value="<?php echo $recordtypes;?>">	
								<input type="hidden" name="propertytype" value="<?php echo $propertytype;?>">
								<input type="hidden" name="tenure" value="<?php echo $tenure;?>">
								<input type="hidden" name="propertystatus" value="<?php echo $propertystatus;?>">						
								<input class="formButton" value="Next Page >>" type="submit">					
							</form>
						<?php endif;?>				
					
						<?php if($previousPage !== null):?>
							<form method="post">
								<input type="hidden" name="page" value="<?php echo $previousPage;?>">
								<input type="hidden" name="reference" value="<?php echo $reference;?>">
								<input type="hidden" name="price_from" value="<?php echo $price_from;?>">
								<input type="hidden" name="price_to" value="<?php echo $price_to;?>">
								<!-- <input type="hidden" name="size_from" value="<?php echo $size_from;?>"> -->
								<input type="hidden" name="bedrooms_from" value="<?php echo $bedrooms_from;?>">
								<input type="hidden" name="bathrooms_from" value="<?php echo $bathrooms_from;?>">	
								<input type="hidden" name="orderby" value="<?php echo $orderby;?>">
								<input type="hidden" name="itemsperpage" value="<?php echo $itemsperpage;?>">
								<input type="hidden" name="recordtypes" value="<?php echo $recordtypes;?>">		
								<input type="hidden" name="propertytype" value="<?php echo $propertytype;?>">
								<input type="hidden" name="tenure" value="<?php echo $tenure;?>">
								<input type="hidden" name="propertystatus" value="<?php echo $propertystatus;?>">					
								<input class="formButton" value="<< Previous Page" type="submit">					
							</form>
						<?php endif;?>
						<br>
					</div>
					<!-- END PAGINATION -->
				<!-- BEGIN RESULT -->
				<div id="result">
		
					<div id="results">
						<?php foreach ($xmlResult->listings->listing as $item): ?>
		    				<div class="post <?php echo $item->data->pba__status__c; ?>">
		    					<!-- <h1><a href="listing.php?id=<?php echo  $item->data->id; ?>" rel="<?php echo  $item->data->id; ?>"><?php echo  $item->data->name; ?></a></h1> -->
		    					<h1><a href="#" rel="<?php echo  $item->data->id; ?>"><?php echo  $item->data->name; ?></a></h1>
		    					<p><?php echo $item->data->pba__status__c; ?></p>
		    					<?php if ($item->media->images->image != null && count($item->media->images->image) > 0){ ?>
		    				    <div class="thumbnail">
		    				    	<a href= "http://localhost/ss/listing?id=<?php echo  $item->data->id; ?>" rel="<?php echo  $item->data->id; ?>"> 
		    				    	<img src="<?php echo $item->media->images->image[0]->baseurl . "/thumbnail/" . $item->media->images->image[0]->filename; ?>"></a>
		    				    </div>
		    				    <?php }; ?>
		    				    
		    				    <ul>
									<li class="itemFact">Price: &#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>			
									<li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>	
									<li class="itemFact">Baths: <?php echo  $item->data->pba__fullbathrooms_pb__c; ?></li>			
									<li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>	
									<li class="itemFact">Tenure: <?php echo  $item->data->pba__propertytype__c; ?></li>			
									<li class="itemFact">Sq.ft: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>	
		    				    </ul>
		    				</div>
		    			<?php endforeach; ?>
					</div>
		
					
				</div>
				<!-- END RESULT -->
			
			<?php }; ?>
			<!-- END ELSE ERROR -->
		
		
		        </div>
		      </td>
		      
		           
		            
		
			</div>
		
			</div>
		
		<script type="text/javascript">
		
		//CHANGE SELECT OPTION FILTERS BASED ON CURRENT SELECTION	
		    var $j = jQuery.noConflict();


		    $j("select[name='price_from'] option[value='"+<?php Print($price_from)  ?>+"']").prop('selected', true);
    $j("select[name='price_to'] option[value='"+<?php Print($price_to) ?>+"']").prop('selected', true);
    $j("select[name='bedrooms_from'] option[value='"+<?php Print($bedrooms_from) ?>+"']").prop('selected', true);

    <?php if( $default_recordtypes == "sale;rent" ){ ?>
     $j("input[name='recordtypes']").first().prop('checked', true);
    <?php }else{ ?>
     $j("input[name='recordtypes'][value=<?php echo("'".$recordtypes."'"); ?>]").prop('checked', true);
     <?php } ?>
  
    $j("select[name='orderby'] option[value=<?php echo("'".$orderby."'"); ?>]").prop('selected', true);
    <?php
    	if (!empty($itemsperpage)) echo( "\$j(\"select[name='itemsperpage'] option[value='".$itemsperpage."']\").prop('selected', true);"); 
    	if (!empty($bathrooms_from)) echo( "\$j(\"select[name='bathrooms_from'] option[value='".$bathrooms_from."']\").prop('selected', true);"); 
    	if (!empty($default_propertytype)) echo( "\$j(\"select[name='propertytype'] option[value='".$default_propertytype."']\").prop('selected', true);");
    	if (!empty($default_tenure)) echo( "\$j(\"select[name='tenure'] option[value='".$default_tenure."']\").prop('selected', true);");
    	if (!empty($default_propertystatus)) echo( "\$j(\"input[name='propertystatus'][value='".$default_propertystatus."']\").prop('checked', true);"); 
    ?>

		    
		    
		
		
		</script>
			<script src="<?php echo get_childTheme_url(); ?>/js/main.js"></script>

		</div>
	</div>	

<!-- GET FOOTER	 -->
	<?php get_footer(); ?>