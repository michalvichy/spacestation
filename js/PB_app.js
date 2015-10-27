// VARS
var $j = jQuery.noConflict();
var $rslts;
var $grid;
var appName = 'app';


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

//(re)triggerMasonry
	function triggerMasonry(){

		// don't proceed if $grid has not been selected
		if ( !$grid.length ){
			return;
		}

		$rslts.masonry('layout');
	}
// inintMsnry
	function inintMsnry(){ 
	
		$rslts.masonry({
	    	// options
	    	itemSelector : '.post',
	    	columnWidth: 300,
	    	gutterWidth: 20
	     });

		imagesLoaded( '#result', function() {
        	$rslts.masonry('layout');
    	});

	}
// killMsnry
	function killMsnry(){ 
			$rslts.masonry('destroy');
	}
// layoutButtonActive
	function layoutButtonActive(currentLayout){
		
		$j('.view a').removeClass('active');
		$j('#'+currentLayout).addClass('active');
		
	}


// DOCUMENT READY - 2st
	$j(document).ready(function() {


		// AJAX
		// $j.ajaxSetup({cache:false});
		
		// $j("h1 a").click(function(e){ 
		// 	e.preventDefault();
		// 	var post_id = $j(this).attr("rel");
		// 	$j("#results").html("loading...");
		// 	$j("#results").load("http://localhost/ss/wp-content/themes/spacestation/PB_listing.php?id="+post_id,{id:post_id});
		// 	return false;
		// });


		$rslts = $j('#results');
		$grid = $j('.grid');


		// COOKIE CHECK - 1st
			$j(function() {
				var cc = $j.cookie('list_grid');
				if (cc == 'g') {
					layoutButtonActive('grid');
					$rslts.removeClass('list').addClass('grid');
					inintMsnry();
				} else {
					layoutButtonActive('list');
					$rslts.removeClass('grid').addClass('list');
				}
			});
		
	//LAYOUT CHANGE BUTTONS
		$j('#grid').click(function() {
			$rslts.fadeOut(300, function() {
				$j(this).removeClass('list').addClass('grid').fadeIn(300);
				$j.cookie('list_grid', 'g');
				inintMsnry();
			});
			layoutButtonActive('grid');
			return false;
		});
		
		$j('#list').click(function() {
			$rslts.fadeOut(300, function() {
				$j(this).removeClass('grid').addClass('list').fadeIn(300);
				$j.cookie('list_grid', null);
				killMsnry();
			});
			layoutButtonActive('list');
			return false;
		});

	//onChange FORM SUBMIT

	$j('#searchform > form#theForm').on('change', 'input, select', function() {
		$j(this).closest("form").submit();
	});

	// Clear Filters
	$j('.js-clear-all-filters').on('click',function(e){ 
		e.preventDefault();
		clearForm();
	})

	// sidebar form SUBMIT button 
	
	$j('.filter__submit').on('click', '.js-run-pb-search', function(event) {
		event.preventDefault();

		var pathname = window.location.pathname;
		var documentURL = document.URL;
		// var baseUrl = documentURL.substring(0,documentURL.lastIndexOf(appName))

		// alert(baseUrl+appName);


    	if (pathname.indexOf(appName) >= 0)
    	{
    		alert('app');
			$j(this).closest("form").submit();    	

    	}else
    	{
			alert('not app');
   			$j("form#theForm").attr('action', '/ss/'+appName).closest("form").submit();
	
    	}

	});

	
	
});

// WINDOW LOAD - 3rd
	$j(window).load(function(){


	});

