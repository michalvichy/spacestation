<!-- JS VARS -->
<script>
  var lat;
  var lng;
  var property_name;
  var property_address;
  var video_url;
  var youtube_url;
  var external;
  var epc;
  var floorplan;
</script>
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
  
  <!-- BEGIN EMPTY RESULT -->
  
    <?php if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0)){ ?>
      <div id="noresult"> 
        no listings found: 
      </div>
    <?php }else{ ?>
  <!-- END EMPTY RESULT --> 
   
        <?php 
         // VARS
         
          // ini_set('memory_limit','256M');
        
          $mainTitle = $xmlResult->listings->listing->data->name;
          $listing_type = $xmlResult->listings->listing->data->pba__listingtype__c;
          $tenure = $xmlResult->listings->listing->data->tenure__c;
          $floorplan = false;
          $epc = false;
        
         ?>
    <!-- BEGIN RESULT -->
      <div id="result">

                  <h3 class="mainTitle"><?php echo  $mainTitle; ?></h3>   
                  
                  <div class="single_view_navigation">
                    <ul>
                      <li><a id="single_view_nav_gallery" href="#">Gallery</a></li>
                      <li><a id="single_view_nav_floorplan" href="#">Floor Plan</a></li>
                       <li><a id="single_view_nav_epc" href="#">EPC</a></li>
                      <li><a id="single_view_nav_map" href="#">Map</a></li>
                      <li><a id="single_view_nav_video" href="#">Video</a></li>
                    </ul>
                  </div>
				  
                <div class="single_view_media">

                    <!-- BEGIN GALLERY -->
                      <?php foreach ($xmlResult->listings->listing as $item): ?> <!-- begin foreach $item -->
                        
                        <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?> <!-- begin if images not empty -->
                        
                        <div class="ls-wp-fullwidth-container" style="height: 500px;">  
                          <div class="gallery">
                          
                            <?php $i = 0; foreach ($item->media->images->image as $image): ?> <!-- begin foreach $image -->
                            
                                
                                <?php if ($image->tags == 'Interior' || $image->tags == 'Exterior' || $image->tags == '' ) {?><!-- exclude floorplan from gallery flow -->
                                  <div class="gallery-cell">
                                    <img class="itemImage" src="<?php echo $image->baseurl . "/" . $image->filename; ?>"/>
                                    <?php $i++; ?>
                                  </div>
                                <?php } ?>

                            <?php endforeach; ?> <!-- end foreach $image -->

                          </div>
                            <!-- BEGIN THUMBNAILS -->
        
                                    <div class="ls-thumbnail-wrapper" style="position:fixed; z-index:50; bottom:50px; height:60px; width:auto; background: rgba(255,255,255,0.1); visibility: visible;">
                                        <div class="ls-thumbnail">
                                            <div class="ls-thumbnail-inner">
                                                <div class="ls-thumbnail-slide-container">
                                                    <div class="ls-thumbnail-slide" style="height: 60px; margin-left: 0px;">
          
                                                          
                                                          <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                                                            
                                                            <!-- exclude floorplan from gallery flow -->
                                                            <?php if ($image->tags == 'Interior' || $image->tags == 'Exterior' || $image->tags == '' ) {?>
                                                              
                                                              <a class="ls-thumb-<?php echo $i; ?>" href="#" style="width: 100px; height: 60px;">
        
                                                                <img src="<?php echo get_childTheme_url(); ?>/includes/show_image.php?file=<?php echo urldecode($image->url); ?>" /> 
                                                                
                                                                <?php $i++; ?>
                                                              
                                                              </a>
                                                          
                                                            <?php } ?>
        
                                                      <?php endforeach; ?>
        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <!-- END THUMBNAILS -->            
                        </div>  
                        <?php endif; ?> <!-- end if images not empty -->

                    <!-- END GALLERY -->       
                    
                    <!-- BEGIN FLOORPLAN -->
                      <div id="floorplan_container">
                        <?php foreach ($xmlResult->listings->listing as $item): ?>
                          
                          <!-- if images not empty -->
                          <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?>
                           <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                            <?php if ($image->tags == 'Floorplan Quick (JPG)') {?>
                                  <img style="max-height:500px; margin-left:200px;" class="itemImage" src="<?php echo $image[$i]->baseurl . "/" . $image[$i]->filename; ?>"/>
                            <?php $floorplan=true; } ?>
                           <?php endforeach; ?>
                          <?php endif; ?>
  
                          <!-- if documents not empty -->
                          <?php if ($item->media->documents->document != null && count($item->media->documents->document) > 0){ ?>
                            <?php $i = 0; foreach ($item->media->documents->document as $document): ?>
                                <!-- if floorplan PDF       -->
                                <?php if ($document->tags == 'Floorplan Enhanced (PDF)') {?>
                                <br>
                                  <a href="<?php echo $document[$i]->url; ?>" target="_blank">Download Floor Plan PDF</a>
                                <?php $floorplan=true; } ?>
                                
                                <?php $i++; ?>
                            <?php endforeach; ?>
                          <?php } ?>
                          <!-- end if documents not empty -->
  
                        <?php endforeach; ?>
                      </div>
                    <!-- END FLOORPLAN -->

                    <!-- BEGIN EPC -->
                      <div id="epc_container">
                        <?php foreach ($xmlResult->listings->listing as $item): ?>
                          
                          <!-- if images not empty -->
                          <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?>
                           <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                            <?php if ($image->tags == 'EPC') {?>
                                  <img style="max-height:500px; margin-left:200px;" class="itemImage" src="<?php echo $image[$i]->baseurl . "/" . $image[$i]->filename; ?>"/>
                            <?php $epc=true; } ?>
                           <?php endforeach; ?>
                          <?php endif; ?>
  
                        <?php endforeach; ?>
                      </div>
                    <!-- END EPC -->
                    
                    <!-- BEGIN MAP -->
                     
                      <div id="map_container">
                        <div id="gmap_canvas" style="height:500px;width:100%;"></div>
                            <style>#gmap_canvas img{max-width:none!important;background:none!important; }</style>
                        </div>
                      <script type="text/javascript"> 
                        // check if latituee and longitude exists and init map is both does
                         <?php if ( !empty($item->data->pba__latitude_pb__c) && !empty($item->data->pba__longitude_pb__c) ){ ?> 
                            lat =  <?php echo  $item->data->pba__latitude_pb__c; ?>;
                            lng =  <?php echo  $item->data->pba__longitude_pb__c; ?>; 
                            property_name = <?php echo  "'".$item->data->name."';" ?>;
                            property_address = <?php echo  "'".$item->data->pba__address_pb__c."';" ?>;    
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
               <h3 class="itemFact"><?php echo  $item->data->name; ?></h3>
                <em>Please fill in the fields marked with *</em>
               <a class="arrange_viewing_button beige angle_edges_button close" href="#">X</a>
            </div><!-- // end single_view_info_header -->
            <div class="panel full_width">  
                        <form action="/">
                           <fieldset>
                             <label for="name">Name</label>
                             <input type="text" id="name" class="form-text" />
                             <p class="form-help">This is help text under the form field.</p>
                           </fieldset>
                           
                           <fieldset>
                             <label for="email">Email</label>
                             <input type="email" id="email" class="form-text" />
                           </fieldset>

                           <fieldset class="form-actions">
                             <input type="submit" value="Submit" />
                           </fieldset>
                        </form>     
            </div> <!-- // end panel -->  
          </div> <!-- // end single_view_info  --> 

          <div class="single_view_info description">
            <div class="single_view_info_header">
               <h3 class="itemFact"><?php echo  $item->data->name; ?></h3>
               <strong>&#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></strong>
               <p><?php echo $item->data->tenure__c; ?></p>
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
            </div><!-- // end single_view_info_header -->
            <div class="panel half_width">  
              <ul class="info_panel_nav">

                      <!-- if documents not empty -->
                        <?php if ($item->media->documents->document != null && count($item->media->documents->document) > 0){ ?>
                          <?php $i = 0; foreach ($item->media->documents->document as $document): ?>
                              <!-- if Brochure PDF -->
                              <?php if ($document->tags == 'Brochure') {?>
                                 <li><a href="<?php echo $document[$i]->url; ?>">PRINT</a></li>
                              <?php } ?>
                              <?php $i++; ?>
                          <?php endforeach; ?>
                        <?php } ?>
                      <!-- end if documents not empty -->
               
                <li><a href="#">SAVE</a></li>
                <li><a href="#">SHARE</a></li>
              </ul>
            <br>
            <p style="height:345px; overflow-y: auto;"><?php echo $item->data->pba__description_pb__c; ?> </p>
                
            </div> <!-- // end panel -->  
            <div class="panel half_width">  
                        <ul class="itemFacts">
                          <li><h4>FAST FACTS</h4></li>
                          <br>
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
                          <?php } ?>
                          <!-- IF SALE END-->
                          
                          <br>
                          <?php if(!empty($item->data->ff_award__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-award"></i><?php echo $item->data->ff_award__c; ?></li>
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
                           <?php if(!empty($item->data->ff_champagne__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-wine"></i><?php echo $item->data->ff_champagne__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_desiner__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-user"></i><?php echo $item->data->ff_desiner__c; ?></li>
                          <?php } ?>
                          <br>

                          <li class="itemFact">Room list: <?php echo  $item->data->room_list__c; ?></li>
                          <li class="itemFact">Local Authority: <?php echo  $item->data->local_authority__c; ?></li>
                          <li class="itemFact">Council Tax Band: <?php echo  $item->data->council_tax_band__c; ?></li>
                          <li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>
                          <li class="itemFact">Baths: <?php echo  $item->data->pba__fullbathrooms_pb__c; ?></li>
                          <li class="itemFact">Sq.ft: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>
                          <br>
                          <li class="itemFact"><em>lat & long: <?php echo  $item->data->pba__latitude_pb__c .' | '. $item->data->pba__longitude_pb__c; ?></em></li>
                          <li class="itemFact"><em>Video: <a href="<?php echo $item->media->videos->video->url; ?>"><?php echo $item->media->videos->video->title; ?></a></em> </li>  
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

                 <?php endforeach; ?>
                <?php } ?>
                <!-- END ELSE EMPTY RESULT -->

      <script src="<?php echo get_childTheme_url(); ?>/js/px-video.js"></script>
      <script type="text/javascript" src="<?php echo get_childTheme_url(); ?>/js/single_view.js"></script>
</div>

<!-- RELATED POSTS -->

<?php query_posts('tag='.$item->data->pba__propertytype__c.',art'); ?>
<?php echo '<div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;"><div class=" full_section_inner clearfix"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="wpb_wrapper"><div class="qode_carousels_holder clearfix"><div class="qode_carousels"><div class="caroufredsel_wrapper" style="display: block; text-align: left; float: none; position: relative; top: auto; right: auto; bottom: auto; left: auto; z-index: 0; width: 1360px; margin: 0px; overflow: hidden; cursor: move; height: 146px;"><ul class="slides" style="text-align: left; float: none; position: absolute; top: 0px; right: auto; bottom: auto; left: -264.50476799999996px; margin: 0px; width: 5712px; opacity: 1; z-index: 0;">'; ?>
<?php  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <li class="item" style="width: 270px;">
          <div class="carousel_item_holder">
            <a href="<?php the_permalink()?>" target="_self">
              <span class="first_image_holder has_hover_image">
                <?php the_post_thumbnail(); ?>
              </span>
              <span class="second_image_holder has_hover_image">
                <?php the_post_thumbnail(); ?>
              </span>
            </a>
          </div>
        </li>

<?php endwhile; endif ;?>
<?php echo '</ul></div></div></div></div></div></div></div>'; ?>




 
