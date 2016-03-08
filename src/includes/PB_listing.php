
 <!-- BEGIN ERROR -->
    <?php if (!empty($errorMessage) || !empty($DisplayDebug) || !empty($DisplayQuery) ): ?>
    <div id="error">
      <?php echo $errorMessage; ?>
      <br>
      <?php echo $DisplayDebug ?>
      <br>
      <?php echo $DisplayQuery; ?> 
    </div>
    <?php else: ?>
    <?php endif;?>  
  
  <!-- END ERROR -->
<div id="container_single_view">
  </div>
  
  <!-- BEGIN EMPTY RESULT -->
    <?php if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0)){ ?>
      <!-- redirect to search results with '#no_listing' query  -->
      <script type="text/javascript"> window.location.replace(window.URLdir+"/app/#no_listing");</script> 
    <?php }else{ ?>
  <!-- END EMPTY RESULT --> 
   
        <!-- JS VARS -->

          <script type="text/javascript">
                  
                  var listing_id = <?php echo("'".$xmlResult->listings->listing->data->id."';");  ?>;
                  var lat;
                  var lng;
                  var property_name = <?php echo("'".$xmlResult->listings->listing->data->name."';"); ?>;
                  var property_address;
                  var property_city_postal;
                  var video_url;
                  var youtube_url;
                  var external;
                  var epc;
                  var floorplan;
                  var poiAddress = <?php echo '"'. $xmlResult->listings->listing->data->pba__city_pb__c.', '; ?><?php echo $xmlResult->listings->listing->data->pba__postalcode_pb__c .'";';?>
                  var poiHTML = <?php echo '"'. $xmlResult->listings->listing->data->name.'";'; ?>
                  var poiZoomLevel = 17;
          </script>    


          <?php 
         
         
          // ini_set('memory_limit','256M');
        
          $mainTitle = $xmlResult->listings->listing->data->name;
          $listing_type = $xmlResult->listings->listing->data->pba__listingtype__c;
          $tenure = $xmlResult->listings->listing->data->tenure__c;
          $floorplan = false;
          $epc = false;
        
         ?>

    <!-- BEGIN RESULT -->
      <div id="result">

                  <h3 class="mainTitle"><?php echo  $mainTitle; ?><br><?php echo $xmlResult->listings->listing->data->pba__city_pb__c.', '; ?><?php echo $xmlResult->listings->listing->data->pba__postalcode_pb__c; ?></h3>   
                  
                  <div class="single_view_navigation">
                    <ul>
                      <li id="single_view_nav_gallery" class="active_single_view_nav">Gallery</li>
                      <li id="single_view_nav_floorplan">Floor Plan</a></li>
                      <li id="single_view_nav_epc">EPC</a></li>
                      <li id="single_view_nav_map">Map</a></li>
                      <li id="single_view_nav_video">Video</a></li>
                    </ul>
                  </div>
				  
                <div class="single_view_media">

                    <!-- BEGIN GALLERY -->
                      <?php foreach ($xmlResult->listings->listing as $item): ?> <!-- begin foreach $item -->
                        
                        <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?> <!-- begin if images not empty -->
                        
                        <div class="ls-wp-fullwidth-container" style="height: 770px;">  
                          <div class="ls-wp-fullwidth-helper" style="width: 1402px; height: 770px; left: -40px;">
                            <div id="listing_gallery_layerslider" style="width: 1402px; height: 770px; margin: 0px auto; visibility: visible;">
                              <div class="ls-inner" style="background-color: transparent; width: 1402px; height: 770px;">
                            <?php $i = 0; foreach ($item->media->images->image as $image): ?> <!-- begin foreach $image -->
                            
                                
                                <?php if ($image->tags == 'Interior' || $image->tags == 'Exterior' || $image->tags == '' ) {?><!-- exclude floorplan from gallery flow -->
                                  <div class="ls-layer ls-slide ls-slide-<?php echo $i; ?>" style="width: 1402px; height: 770px;">  
                                    <img src="<?php echo $image->baseurl . "/" . $image->filename; ?>" class="ls-bg" alt="<?php echo $image->filename; ?>"/>
                                    <div class="ls-gpuhack"></div>
                                    <?php $i++; ?>
                                  </div>
                                <?php } ?>

                            <?php endforeach; ?> <!-- end foreach $image -->

                              </div>

                              <div class="ls-bottom-nav-wrapper ls-below-thumbnails" style="visibility: visible;">
                                <a class="ls-nav-start" href="#"></a>
                                <a class="ls-nav-stop ls-nav-stop-active" href="#"></a>
                              </div>
                            
                            </div>
                          </div>
                        </div>  
                        <?php endif; ?> <!-- end if images not empty -->

                    <!-- END GALLERY -->       
                    
                    <!-- BEGIN FLOORPLAN -->
                      <div id="floorplan_container">

                          <!-- if images not empty -->

                           <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                            <?php if ($image->tags == 'Floorplan Quick (JPG)') {?>
                                  <img style="max-height:770px; margin-left:200px;" class="itemImage" src="<?php echo $image[$i]->baseurl . "/" . $image[$i]->filename; ?>"/>
                            <?php $floorplan=true; } ?>
                           <?php endforeach; ?>

                          <!-- if documents not empty -->
                            <?php $i = 0; foreach ($item->media->documents->document as $document): ?>
                              <!-- if floorplan PDF       -->
                              <?php if ($document->tags == 'Floorplan Enhanced (PDF)') {?>
                                <br>
                                  <a href="<?php echo $document[$i]->url; ?>" target="_blank">Download Floor Plan PDF</a>
                              <?php $floorplan=true; } ?>
                            <?php endforeach; ?> 
                          <!-- end if documents not empty -->
                       
                      </div>
                    <!-- END FLOORPLAN -->

                    <!-- BEGIN EPC -->
                      <div id="epc_container">

                           <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                            <?php if ($image->tags == 'EPC') {?>
                                  <img style="max-height:770px; margin-left:200px;" class="itemImage" src="<?php echo $image[$i]->baseurl . "/" . $image[$i]->filename; ?>"/>
                            <?php $epc=true; } ?>
                           <?php endforeach; ?>

                      </div>
                    <!-- END EPC -->
                    
                    <!-- BEGIN MAP -->
                     
                      <div id="map_container">

                          <div id="poi_sidebar">
                          <ul id="poiList">
                            <li class="hidden" data-title="university" id="university">University</li>
                            <li class="hidden" data-title="bank" id="bank">Bank</li>
                            <li data-title="db:playground" id="playground">Playgrounds</li>
                            <li class="" data-title="doctor" id="doctor">Doctors</li>
                            <li class="" data-title="police" id="police">Police</li>
                            <li class="hidden" data-title="gym" id="gym">Fitness</li>
                            <li data-title="food" id="pizza" name="pizza">Pizza</li>
                            <li class="hidden" data-title="establishment" id="church" name="catholic">Catholic Churches</li>
                            <li class="hidden" data-title="dentist" id="dentist">Dentist</li>
                            <li class="" data-title="bakery" id="bread">Bakery</li>
                      
                          </ul>
                          </div>
                          <div id="poi_map" style="height:770px;width:100%;"></div> <!-- !!! must match in pb-style.css -->

                      </div>
                      



                      <script type="text/javascript"> 
                        // check if latituee and longitude exists and init map is both does
                         <?php if ( !empty($item->data->pba__latitude_pb__c) && !empty($item->data->pba__longitude_pb__c) ){ ?> 
                            lat =  <?php echo  $item->data->pba__latitude_pb__c; ?>;
                            lng =  <?php echo  $item->data->pba__longitude_pb__c; ?>; 
                            property_address = <?php echo  "'".$item->data->pba__address_pb__c."';" ?>;    
                            property_city_postal = <?php echo "'".$item->data->pba__city_pb__c.', '.$item->data->pba__postalcode_pb__c."';" ?>;
                         <?php } ?>   

                      </script>
                    <!-- END MAP -->

                    <!-- BEGIN VIDEO -->
                      <div class="px-video-container" id="myvid">
                      </div><!-- end video container -->
                    <!-- END VIDEO -->
                   
                </div> <!-- // end single_view_media -->
        <div class="single_view_description">
          
          <div class="single_view_info arrange">
            <div class="single_view_arrange_header">
                <p>REQUEST THE VIEWING OF</p>
               <h3 class="itemFact"><?php echo  $item->data->name; ?><br><?php echo $item->data->pba__city_pb__c.', '; ?><?php echo $item->data->pba__postalcode_pb__c; ?></h3>
                <i>Please fill in the fields marked with *</i>
               <a class="arrange_viewing_button beige angle_edges_button close" href="#">&nbsp;</a>
            </div><!-- // end single_view_info_header -->
            <div class="panel full_width">  
                        <form id="arrange_form"action="/">
                           <fieldset>
                             <label for="arrange_first_name">FIRST NAME<sup>*</sup></label>
                            <div class="jqcorner">
                             <input type="text" id="arrange_first_name" class="form-text" />
                            </div>
                           </fieldset>

                           <fieldset>
                             <label for="arrange_last_name">LAST NAME<sup>*</sup></label>
                             <div class="jqcorner">
                             <input type="text" id="arrange_last_name" class="form-text" />
                             </div>
                           </fieldset>
                           
                           <fieldset>
                            <label for="arrange_telephone">TELEPHONE<sup>*</sup></label>
                            <div class="jqcorner">
                             <input type="text" id="arrange_telephone" class="form-text" />
                             </div>
                           </fieldset>

                           <fieldset>
                             <label for="arrange_email">EMAIL<sup>*</sup></label>
                             <div class="jqcorner">
                             <input type="email" id="arrange_email" class="form-text" />
                             </div>
                           </fieldset>

                           <fieldset>
                            <label for="arrange_message">MESSAGE / PREFERRED VIEWING TIMES </label>
                            <div class="jqcorner">
                              <textarea id="arrange_message"></textarea>
                            </div>
                          </fieldset>

                          <fieldset>
                            <label for="arrange_where_did_you_hear">WHERE DID YOU HEAR ABOUT US?</label>
                            <div class="jqcorner">
                            <select id="arrange_where_did_you_hear">
                              <option>Male</option>
                              <option>Female</option>
                              <option>Cylon</option>
                            </select>
                            </div>
                          </fieldset>

                          <fieldset>
                            <label for="arrange_are_you_selling">ARE YOU SELLING A PROPERTY?</label>
                            <div class="jqcorner">
                             <input type="text" id="arrange_are_you_selling" class="form-text" />
                           </div>
                          </fieldset>

                          <fieldset class="arrange_check">
                            <label><input type="checkbox" /> I accept the <a href="#">privacy policy</a><sup>*</sup></label>
                          </fieldset>

                           <fieldset class="form-actions">
                             <input type="submit" value="SUBMIT" />
                           </fieldset>
                        </form>     
            </div> <!-- // end panel -->  
          </div> <!-- // end single_view_info  --> 

          <div class="single_view_info description">
            <div class="single_view_info_header">
               <h3 class="itemFact"><?php echo  $item->data->name; ?><br><?php echo $item->data->pba__city_pb__c.', '; ?><?php echo $item->data->pba__postalcode_pb__c; ?></h3>
               <p><strong>&#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></strong><?php echo ' '.$item->data->tenure__c; ?></p>
               <p> 
               <?php
                $listing_bedrooms = $item->data->pba__bedrooms_pb__c;
                if( $listing_bedrooms > 1 ){
                  echo  $item->data->pba__bedrooms_pb__c.' Bedrooms'; 
                }else{
                  echo  $item->data->pba__bedrooms_pb__c.' Bedroom'; 
                }
                ?>
               </p>
               <a href="#" class="arrange_viewing_button beige angle_edges_button open">ARRANGE A VIEWING</a>
               <a href="mailto:" class="arrange_viewing_mailto_button">EMAIL US</a>
               <p class="arrange_viewing_phone_button">020 7613 6262</p>

            </div><!-- // end single_view_info_header -->
            <div class="panel half_width">  
              <ul class="info_panel_nav">

                      <!-- if documents not empty -->
                        <?php if ($item->media->documents->document != null && count($item->media->documents->document) > 0){ ?>
                          <?php $i = 0; foreach ($item->media->documents->document as $document): ?>
                              <!-- if Brochure PDF -->
                              <?php if ($document->tags == 'Brochure') {?>
                                 <li class="print_button"><a href="<?php echo $document[$i]->url; ?>" target="_blank">PRINT</a></li>
                              <?php } ?>
                              <?php $i++; ?>
                          <?php endforeach; ?>
                        <?php } ?>
                      <!-- end if documents not empty -->
               
                <li class="add_to_saved_button"><a href="#">SAVE</a></li>
                <li><?php echo do_shortcode('[social_share_tuff]',false); ?></li>
              </ul>
            <br>
            <p style="overflow-y: auto; height: 353px;"><?php echo $item->data->pba__description_pb__c; ?> </p>
                
            </div> <!-- // end panel -->  
            <div class="panel half_width">  
                <h4>FAST FACTS</h4>
                        <ul class="itemFacts">
                          <li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>
                          
                          <!-- IF RENT -->
                          <?php if($listing_type == 'Rent'){ ?>
                          <li class="itemFact">Weekly Rent: <?php $weekly = number_format((float)$item->data->weekly_rent__c); $monthly = number_format((float)($weekly * 52)/12); echo  '&#163;'.$weekly.' (&#163;'.$monthly.'/month)'; ?></li>
                          <?php } ?>
                          <!-- IF RENT END -->
                          
                          <!-- IF LEASHOLD -->
                          <?php if( $tenure == 'Leasehold' ){ ?>
                          <li class="itemFact">Years Remaining: <?php echo  $item->data->years_remaining_leasehold_only__c; ?></li>
                          <?php } ?>
                          <!-- IF LEASHOLD END-->

                          <!-- IF SALE -->
                          <?php if($listing_type == 'Sale'){ ?>
                          <li class="itemFact">Price: &#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>
                          <li class="itemFact">Stamp Duty: &#163;<?php echo  number_format((float) $item->data->stamp_duty__c); ?></li>
                          <?php } ?>
                          <!-- IF SALE END-->

                          <li class="itemFact">Room list: <?php echo  $item->data->room_list__c; ?></li>
                          <li class="itemFact">Local Authority: <?php echo  $item->data->local_authority__c; ?></li>
                          <li class="itemFact">Council Tax Band: <?php echo  $item->data->council_tax_band__c; ?></li>

                          
                          <br>
                          <?php if(!empty($item->data->ff_aircon__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-megaphone"></i><?php echo $item->data->ff_aircon__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_architect__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-user"></i><?php echo $item->data->ff_architect__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_award__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-award"></i><?php echo $item->data->ff_award__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_balcony__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cloud"></i><?php echo $item->data->ff_balcony__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_basement__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-database"></i><?php echo $item->data->ff_basement__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_blue_plaque__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-note"></i><?php echo $item->data->ff_blue_plaque__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_bottle__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-beaker"></i><?php echo $item->data->ff_bottle__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_brick__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-tag"></i><?php echo $item->data->ff_brick__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_built__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-thumbs-up"></i><?php echo $item->data->ff_built__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_celings__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cloud"></i><?php echo $item->data->ff_celings__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_champagne__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-wine"></i><?php echo $item->data->ff_champagne__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_church__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-money"></i><?php echo $item->data->ff_church__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_cinema__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-desktop"></i><?php echo $item->data->ff_cinema__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_concierge__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-key"></i><?php echo $item->data->ff_concierge__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_desiner__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-user"></i><?php echo $item->data->ff_desiner__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_eco__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-heart"></i><?php echo $item->data->ff_eco__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_elephant__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-thumbs-up"></i><?php echo $item->data->ff_elephant__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_extension__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-shop"></i><?php echo $item->data->ff_extension__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_factory__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-params"></i><?php echo $item->data->ff_factory__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_fireplace__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-fire"></i><?php echo $item->data->ff_fireplace__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_garden__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cloud"></i><?php echo $item->data->ff_garden__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_gym__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-t-shirt"></i><?php echo $item->data->ff_gym__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_high_specification__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-star"></i><?php echo $item->data->ff_high_specification__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_history__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-comment"></i><?php echo $item->data->ff_history__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_hot_tap__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cd"></i><?php echo $item->data->ff_hot_tap__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_lateral_space__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-plane"></i><?php echo $item->data->ff_lateral_space__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_library__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-doc"></i><?php echo $item->data->ff_library__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_lift__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-params"></i><?php echo $item->data->ff_lift__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_light__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-lightbulb"></i><?php echo $item->data->ff_light__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_listed_grade_i__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cog"></i><?php echo $item->data->ff_listed_grade_i__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_listed_grade_ii__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cog"></i><?php echo $item->data->ff_listed_grade_ii__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_map__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-globe"></i><?php echo $item->data->ff_map__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_mews__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-camera"></i><?php echo $item->data->ff_mews__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_mezzanine__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-wallet"></i><?php echo $item->data->ff_mezzanine__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_parking__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-attach"></i><?php echo $item->data->ff_parking__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_penthouse__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-cup"></i><?php echo $item->data->ff_penthouse__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_planning__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-calendar"></i><?php echo $item->data->ff_planning__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_pool__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-inbox"></i><?php echo $item->data->ff_pool__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_refurbished__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-videocam"></i><?php echo $item->data->ff_refurbished__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_roof_terrace__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-diamond"></i><?php echo $item->data->ff_roof_terrace__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_school__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-graduation-cap"></i><?php echo $item->data->ff_school__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_security__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-lock"></i><?php echo $item->data->ff_security__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_solar__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-food"></i><?php echo $item->data->ff_solar__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_sound_system__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-sound"></i><?php echo $item->data->ff_sound_system__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_south_facing__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-camera"></i><?php echo $item->data->ff_south_facing__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_stairs__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-star"></i><?php echo $item->data->ff_stairs__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_townhouse__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-music"></i><?php echo $item->data->ff_townhouse__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_tube__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-photo"></i><?php echo $item->data->ff_tube__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_underfloor_heating__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-mobile"></i><?php echo $item->data->ff_underfloor_heating__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_view__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-eye"></i><?php echo $item->data->ff_view__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_warehouse__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-truck"></i><?php echo $item->data->ff_warehouse__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_windows__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-tv"></i><?php echo $item->data->ff_windows__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_wine_cellar__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-trash"></i><?php echo $item->data->ff_wine_cellar__c; ?></li>
                          <?php } ?>
                          <?php if(!empty($item->data->ff_wooden_floors__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-pencil"></i><?php echo $item->data->ff_wooden_floors__c; ?></li>
                          <?php } ?>
                          <br>
                       </ul>
            </div> <!-- // end panel --> 
          </div> <!-- // end single_view_info  --> 
        </div> <!-- end single_view_description -->

      </div>
    <!-- END RESULT -->

       <!-- if video url create javascript variable with it -->
       <?php if( !empty($item->media->videos->video->url) ){ ?>
                <script type="text/javascript">
                  video_url = <?php echo ("'".$item->media->videos->video->url."';");  ?>
                  youtube_url = video_url; // youtube_url = video_url.replace("watch?v=", "v/");
                  external = <?php echo ("'".$item->media->videos->video->external."';");  ?>
                  floorplan = <?php echo ("'".$floorplan."';");  ?>
                  epc = <?php echo ("'".$epc."';");  ?>
                </script>
       <?php } ?>

                 <?php endforeach; // end foreach $item ?> 
                <?php } ?>
                <!-- END ELSE EMPTY RESULT -->

<!-- RELATED POSTS -->

  <!-- <div class="wpb_tabstabs_holder clearfix" data-interval="0">
    <div class="q_tabs vertical left" style="visibility: visible;">
        <ul class="tabs-nav">
            <li class="active">
                <a href="#tab-1394535786-1-22" style="border-right-color: rgb(255, 255, 255);">SHOP</a>
            </li>
            <li>
                <a href="#tab-1394536165449-1-1">DISCOVER</a>
            </li>
            <li>
                <a href="#tab-1394536167784-2-10">ENJOY</a>
            </li>
        </ul>
        <div class="tabs-container">
            <div class="tab-content" id="tab-1394535786-1-22" style="display: block;">
                <div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;">
                    <div class=" full_section_inner clearfix">
                        <div class="vc_col-sm-12 wpb_column vc_column_container">
                            <div class="wpb_wrapper">
                                <div class="wpb_text_column wpb_content_element">
                                    <div class="wpb_wrapper">
                                        <?php 
                                        $related_shop = str_replace(";",",",(string)$item->data->related_shop__c); 
                                        $tags = str_replace(" ", "-", $related_shop);
                                        $args = array(
                                          'posts_per_page' => 3, 
                                          'category_name' => 'related-businesses',
                                          'tag' => $tags
                                          );
                                        query_posts($args); 
                                        ?>
                                        <?php echo '<div class="jcarousel-wrapper"><div class="jcarousel"><ul>'; ?>
                                        <?php  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                          <li><a href="<?php the_permalink()?>" target="_self"><?php the_post_thumbnail(); ?></a></li>

                                        <?php endwhile; endif ;?>
                                        <?php echo '</ul></div><a href="#" class="jcarousel-control-prev">&lsaquo;</a><a href="#" class="jcarousel-control-next">&rsaquo;</a><p class="jcarousel-pagination"></p></div>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="tab-1394536165449-1-1" style="display: none;">
                <div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;">
                    <div class=" full_section_inner clearfix">
                        <div class="vc_col-sm-12 wpb_column vc_column_container">
                            <div class="wpb_wrapper">
                                <div class="wpb_text_column wpb_content_element">
                                    <div class="wpb_wrapper">
                                        <?php 
                                          $related_discover = str_replace(";",",",(string)$item->data->related_discover__c); 
                                          $tags = str_replace(" ", "-", $related_discover);
                                          $args = array(
                                          'posts_per_page' => 3, 
                                          'category_name' => 'related-businesses',
                                          'tag' => $tags
                                          );
                                        query_posts($args);  
                                         ?>
                                        <?php echo '<div class="jcarousel-wrapper"><div class="jcarousel"><ul>'; ?>
                                        <?php  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                          <li><a href="<?php the_permalink()?>" target="_self"><?php the_post_thumbnail(); ?></a></li>

                                        <?php endwhile; endif ;?>
                                        <?php echo '</ul></div><a href="#" class="jcarousel-control-prev">&lsaquo;</a><a href="#" class="jcarousel-control-next">&rsaquo;</a><p class="jcarousel-pagination"></p></div>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="tab-1394536167784-2-10" style="display: none;">
                <div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;">
                    <div class=" full_section_inner clearfix">
                        <div class="vc_col-sm-12 wpb_column vc_column_container">
                            <div class="wpb_wrapper">
                                <div class="wpb_text_column wpb_content_element">
                                    <div class="wpb_wrapper">
                                       <?php 
                                        $related_enjoy = str_replace(";",",",(string)$item->data->related_enjoy__c); 
                                        $tags = str_replace(" ", "-", $related_enjoy);
                                        $args = array(
                                          'posts_per_page' => 3, 
                                          'category_name' => 'related-businesses',
                                          'tag' => $tags
                                          );
                                        query_posts($args);  
                                       ?>
                                        <?php echo '<div class="jcarousel-wrapper"><div class="jcarousel"><ul>'; ?>
                                        <?php  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                          <li><a href="<?php the_permalink()?>" target="_self"><?php the_post_thumbnail(); ?></a></li>

                                        <?php endwhile; endif ;?>
                                        <?php echo '</ul></div><a href="#" class="jcarousel-control-prev">&lsaquo;</a><a href="#" class="jcarousel-control-next">&rsaquo;</a><p class="jcarousel-pagination"></p></div>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div> -->

<!-- RELATED PROPERTIES -->
  <?php 
  
    // VARS
    $opened_listing_id = $item->data->id;
  
    /////////////// QUERY ARRAY ///////////////
    $reqArray = array("token"       => PB_SECURITYTOKEN,
              "fields"      => "Id;name;pba__ListingPrice_pb__c;pba__status__c;Tenure__c;",
              "recordtypes" => (string)$listing_type,
              "pba__PropertyType__c" => (string)$item->data->pba__propertytype__c,
              "debugmode"   => "true"
              );
    // BUILD HTTP QUERY STRING
    $query    = http_build_query($reqArray,'','&');
    // RETURN XML RESULT
    $xmlResult  = simplexml_load_file(PB_WEBSERVICEENDPOINT . "?" . $query);
    
    if (!empty($xmlResult->errorMessages->message)) {
      $errorMessage = 'Error: '.$xmlResult->errorMessages->message;
      echo '<script>alert("'.$errorMessage.'");</script>';
    } else {
  
      if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0))
      { 
        echo '<script>alert("no listings found");</script>';
      }else{ 

        $numberOfListings = $xmlResult->numberOfListings -1 ;

        echo '<div class="qode_carousels_holder clearfix related_properties"><div class="qode_carousels"><div class="caroufredsel_wrapper" ><ul class="slides" style="background-color: #040404; text-align: left; float: none; position: absolute; top: 0px; right: auto; bottom: auto; margin: 0px; opacity: 1; z-index: 0;">'; 
          
          echo '<script></script>';

          if($numberOfListings > 2){

            $arr = $xmlResult->xpath('listings/listing');
            $random = array_rand($arr, 4);

            foreach ($random as $key):
            $item = $xmlResult->listings->listing[$key];
            if( (string)$opened_listing_id !== (string)$item->data->id ):?>
              <li class="item">
                <div class="carousel_item_holder">
                    <span class="first_image_holder">
                      <span class="arrow-right"></span>
                      <span class="text_holder">
                          <span class="text_outer">
                            <span class="text_inner">
                              <div class="hover_feature_holder_title">
                                <div class="hover_feature_holder_title_inner">
                                  <small>Related properties</small>
                                  <h5 class="portfolio_title">
                                    <?php echo  $item->data->name; ?>
                                  </h5>
                                  <span class="project_category">&#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?><br><?php echo $item->data->pba__status__c; ?></span>
                                  <br>
                                  <a href="<?php echo site_url(); ?>/listing?id=<?php echo  $item->data->id; ?>" target="_self">VIEW</a>
                                </div>
                              </div>
                            </span>
                          </span>
                        </span>
                      <img width="1100" height="619" src="<?php echo $item->media->images->image[0]->baseurl . "/thumbnail/" . $item->media->images->image[0]->filename; ?>" class="attachment-post-thumbnail wp-post-image" alt="<?php echo $item->media->images->image[0]->filename; ?>">
                    </span>
                </div>
              </li>
      <?php endif; 
          endforeach;

          }else{

            foreach ($xmlResult->listings->listing as $item):
            if( (string)$opened_listing_id !== (string)$item->data->id ):?>
              <li class="item">
                <div class="carousel_item_holder">
                    <span class="first_image_holder">
                      <span class="arrow-right"></span>
                      <span class="text_holder">
                          <span class="text_outer">
                            <span class="text_inner">
                              <div class="hover_feature_holder_title">
                                <div class="hover_feature_holder_title_inner">
                                  <small>Related properties</small>
                                  <h5 class="portfolio_title">
                                    <?php echo  $item->data->name; ?>
                                  </h5>
                                  <span class="project_category">&#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?><br><?php echo $item->data->pba__status__c; ?></span>
                                  <br>
                                  <a href="<?php echo site_url(); ?>/listing?id=<?php echo  $item->data->id; ?>" target="_self">VIEW</a>
                                </div>
                              </div>
                            </span>
                          </span>
                        </span>
                      <img width="1100" height="619" src="<?php echo $item->media->images->image[0]->baseurl . "/thumbnail/" . $item->media->images->image[0]->filename; ?>" class="attachment-post-thumbnail wp-post-image" alt="<?php echo $item->media->images->image[0]->filename; ?>">
                    </span>
                </div>
              </li>
      <?php endif; 
          endforeach;

          }

          

          switch ($numberOfListings) {
                                  case 1:
                                    # code...
                                    echo '<li class="item" style="width: 705px;"><div class="carousel_item_holder"><span class="first_image_holder"><span class="text_holder"><span class="text_outer"><span class="text_inner"><div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner"><h5 class="portfolio_title"></h5><span class="project_category"><br></span></div></div></span></span></span><img width="1100" height="619" src="http://www.smartperform.de/wp-content/downloads/photoshop_templates/2014/200x200_transparent_placeholder.png" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata"></span></div></li><li class="item" style="width: 705px;"><div class="carousel_item_holder"><span class="first_image_holder"><span class="text_holder"><span class="text_outer"><span class="text_inner"><div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner"><h5 class="portfolio_title"><a href="#" target="_self"></a></h5><span class="project_category"><br></span></div></div></span></span></span><img width="1100" height="619" src="http://www.smartperform.de/wp-content/downloads/photoshop_templates/2014/200x200_transparent_placeholder.png" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata"></span></div></li><li class="item" style="width: 705px;"><div class="carousel_item_holder"><span class="first_image_holder"><span class="text_holder"><span class="text_outer"><span class="text_inner"><div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner"><h5 class="portfolio_title"><a href="#" target="_self"></a></h5><span class="project_category"></span></div></div></span></span></span><img width="1100" height="619" src="http://www.smartperform.de/wp-content/downloads/photoshop_templates/2014/200x200_transparent_placeholder.png" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata"></span></div></li>';
                                    break;
                                  case 2:
                                    # code...
                                    echo '<li class="item" style="width: 705px;"><div class="carousel_item_holder"><span class="first_image_holder"><span class="text_holder"><span class="text_outer"><span class="text_inner"><div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner"><h5 class="portfolio_title"></h5><span class="project_category"><br></span></div></div></span></span></span><img width="1100" height="619" src="http://www.smartperform.de/wp-content/downloads/photoshop_templates/2014/200x200_transparent_placeholder.png" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata"></span></div></li><li class="item" style="width: 705px;"><div class="carousel_item_holder"><span class="first_image_holder"><span class="text_holder"><span class="text_outer"><span class="text_inner"><div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner"><h5 class="portfolio_title"><a href="#" target="_self"></a></h5><span class="project_category"><br></span></div></div></span></span></span><img width="1100" height="619" src="http://www.smartperform.de/wp-content/downloads/photoshop_templates/2014/200x200_transparent_placeholder.png" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata"></span></div></li>';
                                    break;
                                  case 3:
                                    # code...
                                    echo '<li class="item" style="width: 705px;"><div class="carousel_item_holder"><span class="first_image_holder"><span class="text_holder"><span class="text_outer"><span class="text_inner"><div class="hover_feature_holder_title"><div class="hover_feature_holder_title_inner"><h5 class="portfolio_title"></h5><span class="project_category"><br></span></div></div></span></span></span><img width="1100" height="619" src="http://www.smartperform.de/wp-content/downloads/photoshop_templates/2014/200x200_transparent_placeholder.png" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata"></span></div></li>';
                                    break;
                                  
                                  default:
                                    # code...
                                    #echo 'width: 50%;';
                                    break;
                                }


        echo '</ul></div></div></div>';  
      }
  
    }
  
    ?>

<!-- RECENTLY VIEWED -->

<?php if( isset($_COOKIE["recently_viewed"]) ) { ?>
<h5 id="recently_viewed_header">RECENTLY VIEWED</h5>

<?php 
  
    // VARS
    $opened_listing_id = $item->data->id;
    $recently_viewed_ids = array();
    $hello = str_replace(':',' => ',stripcslashes($_COOKIE["recently_viewed"]));
    $hello2 = str_replace('{', '(', $hello);
    $hello3 = str_replace('}', ')', $hello2);
    eval('$recently_viewed_cookie = array'.$hello3.';'); 

    $recently_viewed_cookie_slice = array_slice($recently_viewed_cookie, -4);
    
    foreach ($recently_viewed_cookie_slice as $item) {
       array_push($recently_viewed_ids, $item);
    }

    $id_request = implode(";", $recently_viewed_ids);
  
    /////////////// QUERY ARRAY ///////////////
    $reqArray = array("token"       => PB_SECURITYTOKEN,
              "fields"      => "Id;name;pba__ListingPrice_pb__c;pba__status__c;Tenure__c;",
              "id" => 'IN('.$id_request.')',
              "debugmode"   => "true"
              );
    // BUILD HTTP QUERY STRING
    $query    = http_build_query($reqArray,'','&');
    // RETURN XML RESULT
    $xmlResult  = simplexml_load_file(PB_WEBSERVICEENDPOINT . "?" . $query);
    
    if (!empty($xmlResult->errorMessages->message)) {
      $errorMessage = 'Error: '.$xmlResult->errorMessages->message;
      echo '<script>alert("'.$errorMessage.'");</script>';
    } else {
  
      if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0))
      { 
        echo '<script>alert("no listings found");</script>';
      }else{ 

        $numberOfListings = $xmlResult->numberOfListings;

        echo '<div class="qode_carousels_holder clearfix recently_viewed"><div class="qode_carousels"><div class="caroufredsel_wrapper" ><ul class="slides" style="background-color: #fff; text-align: left; float: none; position: absolute; top: 0px; right: auto; bottom: auto; margin: 0px; opacity: 1; z-index: 0;">'; 
          echo '<script></script>';
          foreach ($xmlResult->listings->listing as $item):
            #if( (string)$opened_listing_id !== (string)$item->data->id ):?>
              <li class="item" style="width:3.57%!important; margin:0;">
                <div class="carousel_item_holder">
                    <span class="first_image_holder">
                      <span class="text_holder">
                          <span class="text_outer">
                            <span class="text_inner">
                              <div class="hover_feature_holder_title">
                                <div class="hover_feature_holder_title_inner">
                                  <h5 class="portfolio_title">
                                    <?php echo  $item->data->name; ?>
                                  </h5>
                                  <span class="project_category">&#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?><p></p><?php echo $item->data->pba__status__c; ?></span><p></p>
                                  <a href="<?php echo site_url(); ?>/listing?id=<?php echo  $item->data->id; ?>" target="_self">VIEW</a>
                                </div>
                              </div>
                            </span>
                          </span>
                        </span>
                      <img width="1100" height="619" src="<?php echo $item->media->images->image[0]->baseurl . "/thumbnail/" . $item->media->images->image[0]->filename; ?>" class="attachment-post-thumbnail wp-post-image" alt="qode interactive strata">
                    </span>
                </div>
              </li>
      <?php #endif; 
          endforeach;
        echo '</ul></div></div></div>';  
      }
  
    }
  
    ?>
<?php } ?>

 
<!-- MESSAGE BOXED -->
<!-- SUCCESS BOX -->
<div class="q_message  with_icon message_box success" style="background-color: #2ecc71;"><div class="q_message_inner"><div class="q_message_icon_holder"><div class="q_message_icon"><div class="q_message_icon_inner"><i class="fa fa-thumbs-o-up fa-lg" style=""></i></div></div></div><a href="#" class="close"><i class="fa fa-times" style=""></i></a><div class="message_text_holder"><div class="message_text" style="height: 26px;"><div class="message_text_inner">Successfully saved to favourites</div></div></div></div></div>
<!-- WARNING BOX  -->
<div class="q_message  with_icon message_box warning" style="background-color: #f1c40f;"><div class="q_message_inner"><div class="q_message_icon_holder"><div class="q_message_icon"><div class="q_message_icon_inner"><i class="fa fa-exclamation fa-lg" style=""></i></div></div></div><a href="#" class="close"><i class="fa fa-times" style=""></i></a><div class="message_text_holder"><div class="message_text" style="height: 26px;"><div class="message_text_inner">Already in favourites - select different property</div></div></div></div></div>
<!-- ERROR BOX -->
<div class="q_message  with_icon message_box error" style="background-color: #e74c3c;"><div class="q_message_inner"><div class="q_message_icon_holder"><div class="q_message_icon"><div class="q_message_icon_inner"><i class="fa fa-exclamation-triangle fa-lg" style=""></i></div></div></div><a href="#" class="close"><i class="fa fa-times" style=""></i></a><div class="message_text_holder"><div class="message_text" style="height: 26px;"><div class="message_text_inner">Sample error message box</div></div></div></div></div>
</div>