// VARS
var $j = jQuery.noConflict();
var $rslts;
var $grid;
var $dynamic_view = $j('#dynamic_view');
var $grid_view = $j('#grid_view');
var $app_header = $j('#app_header');

var layouts = new Array(
            'standard left',
            'standard left space',
            'standard right',
            'standard right space',
            'square_big top-left',
            'square_big top-right'
            );


// Range SLIDER
	/* We need to change slider appearance oninput and onchange */
	function showValue(val,slidernum) {
	  // console.log(val);
	}
	/* we often need a function to set the slider values on page load */
	function setValue(val,num) {
	  $j("#slider"+num).value = val;
	  showValue(val,num);
	}


// clearForm
function clearForm() { 
                $form = $j('fieldset#parameters');
                $form.find('input').val('');
                // $form.find('input[type=checkbox]').attr('checked', false);
                $form.find('option:selected').attr('selected', false);

                $form.find('.select').each(function() {
                    var $element = $j(this);
                    var $select = $element.find('select');
                    var $value = $element.find('.select-value');

                    $value.text($select.find(':selected').html());
                });

                $j('form#theForm').submit();
            }





	function generateLayout(currentLayout){

		$j('article.portfolio_masonry_item').removeClass('standard').removeClass('left').removeClass('right').removeClass('space').removeClass('top-left').removeClass('top-right').addClass('square_big').addClass('top-full');
		if($j('.arrow-right , .arrow-left').length){
						$j('.arrow-right , .arrow-left').remove();
					}

		switch(currentLayout){

					case 'grid':
					
						$rslts.removeClass('list').addClass('grid');
						$grid_view.addClass('active');
						$dynamic_view.removeClass('active');
						break;
					
					case 'dynamic':

						$rslts.removeClass('grid').addClass('list');
						$dynamic_view.addClass('active');
						$grid_view.removeClass('active');

						$j('article.portfolio_masonry_item').removeClass('square_big').removeClass('top-full').each(function(index, el) {
							var randomLayout = layouts[Math.floor(Math.random()*layouts.length)];
							$j(this).addClass(randomLayout);

							if (randomLayout.toLowerCase().indexOf("square_big") == -1 && randomLayout.toLowerCase().indexOf("left") >= 0){
								$j(this).find('.portfolio_link_for_touch').after("<span class='arrow-right'></span>");
							}else if (randomLayout.toLowerCase().indexOf("square_big") == -1 && randomLayout.toLowerCase().indexOf("right") >= 0){
								$j(this).find('.portfolio_link_for_touch').after("<span class='arrow-left'></span>");
							}

						});

						break;
					
					default:
						alert('no "layout_cookie"');
				}

	}


// DOCUMENT READY - 2st
	$j(document).ready(function() {

		// $j('#app_header').html('ALL');


		$rslts = $j('.tuff');
		$grid = $j('.grid');

		switch($rt){
			
			case 'sale;rent':
				$j('#app_header').html('ALL PROPERTIES');
				break;
			
			case 'sale':
				$app_header.html('FOR SALE');
				break;
			
			case 'rent':
				$app_header.html('FOR RENT');
				break;

			default:
				$app_header.html('');
		}


		// LAYOUT COOKIE CHECK - 1st
			$j(function() {

				generateLayout($j.super_cookie().read_value("layout_cookie","layout"));

			});
		
	//LAYOUT CHANGE BUTTONS
		$j('#grid_view').click(function(event) {
			event.preventDefault();
			$rslts.fadeOut(300, function() {
				$j(this).removeClass('list').addClass('grid').fadeIn(300);
				
				generateLayout('grid');

				// check if layout_cookie exists
				if($j.super_cookie().check("layout_cookie")){
					//if available -> replace value to 'grid'
					$j.super_cookie().replace_value("layout_cookie","layout","grid");
				}else{
					//if not available create layout_cookie with value "grid"
					$j.super_cookie({expires: 7,path: "/"}).create("layout_cookie",{layout:"grid"});
				}
				
				setTimeout(function(){ initPortfolioMasonry(); },300 );
			});

			$j(this).addClass('active');
			$j('#dynamic_view').removeClass('active');
			return false;
		});
		
		$j('#dynamic_view').click(function(event) {
			event.preventDefault(); 
			$rslts.fadeOut(300, function() {
				$j(this).removeClass('grid').addClass('list').fadeIn(300);
				
				generateLayout('dynamic');

				// check if layout_cookie exists
				if($j.super_cookie().check("layout_cookie")){
					//if available -> replace value to 'dynamic'
					$j.super_cookie().replace_value("layout_cookie","layout","dynamic");
				}else{
					//if not available create layout_cookie with value "dynamic"
					$j.super_cookie({expires: 7,path: "/"}).create("layout_cookie",{layout:"dynamic"});
				}

				setTimeout(function(){ initPortfolioMasonry(); },300 );
			});

			return false;
		});

	//onChange FORM SUBMIT

		$j('form#theForm').on('change', 'input', function() {
			$j(this).closest("form").submit();
		});
	
		$j('form[name="PB_page_form"]').on('change', 'select, input', function() {
			$j(this).closest("form").submit();
		});

	// app header change
	
	function app_header_change(section){

		// switch(section){
			
		// 	case 'all-types':
		// 		$j('#app_header').html('ALL');
		// 		break;
			
		// 	case 'sale':
		// 		$app_header.html('FOR SALE');
		// 		break;
			
		// 	case 'rent':
		// 		$app_header.html('FOR RENT');
		// 		break;

		// 	default:
		// 		$app_header.html('');
		// }
	}

	$j('#record-types').on('click', 'input[type="radio"]', function(event) {
		event.preventDefault();
		event.stopPropagation();
		/* Act on the event */
		// alert($j(this).attr('id'));
		app_header_change($j(this).attr('id'));
	});

	// Clear Filters
		$j('.js-clear-all-filters').on('click',function(e){ 
			e.preventDefault();
			clearForm();
		})

	
	
});

// WINDOW LOAD - 3rd
	$j(window).load(function(){

		// fade in error box if '#no_listing' url query
		if(document.URL.split('#')[1] === 'no_listing'){
			$j('.error').fadeIn();
		}

	});

