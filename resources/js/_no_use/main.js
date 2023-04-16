
//wow
        
wow = new WOW(
        {
          boxClass: 'bb-animate',
          animateClass: 'animated',
          offset:       100
        }
      );
wow.init();
// DOM Ready


(function($) {
    
    
                var BRUSHED = window.BRUSHED || {};
        
	// SVG fallback
	// toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script#update
	if (!Modernizr.svg) {
		var imgs = document.getElementsByTagName('img');
		for (var i = 0; i < imgs.length; i++) {
			if (/.*\.svg$/.test(imgs[i].src)) {
				imgs[i].src = imgs[i].src.slice(0, -3) + 'png';
			}
		}
	}
        
        
        var shrinkHeader = 50;
          $(window).scroll(function() {
            var scroll = getCurrentScroll();
              if ( scroll >= shrinkHeader ) {
                   $('.header').addClass('shrink');
                   $('.fullscreen-about-me .row').fadeOut();
                }
                else {
                    $('.header').removeClass('shrink');
                    $('.fullscreen-about-me .row').fadeIn();
                }
          });
        function getCurrentScroll() {
            return window.pageYOffset;
            }
     
        
    
        jQuery(".boxer").boxer();
        //Mobil Nav
//        $('.flexpanel').flexpanel({
//        animation: 'slide',
//        direction: 'right'
//        });
//        
//        $(window).scroll(function () {
//            var scrollTop = $(window).scrollTop();
//
//            if (scrollTop > 200) {
//                    $('.header').addClass('color-top');
//
//            } else if (scrollTop == 0)   {
//                    $('.header').removeClass('color-top');
//            }
//        });
        

	var $window_height = $(window).height();
	var $window_width = $(window).width();

	// Header adjustment
	function fc_header_adjustment(){
            
		// Execute
		fc_execute_header_adjustment();

		$(window).resize(function(){
			
			fc_execute_header_adjustment();
		});
	}
	// Header adjustment execute
	function fc_execute_header_adjustment(){
		// Some variables
		$window_height = $(window).height();
		// Adjust header
		$('.fullplate').height($window_height);
		$('.webplate').fadeIn('fast');
	}
	// Scroll down
	function fc_scroll_down(){	
		$('.scroller').on('click', function(){			
			$('.webplate-content').animate({ scrollTop: $window_height }, 1000, 'easeInOutCubic');
		});
	}
	// Screen width value
	function fc_screen_width(){	
		$('.screen-width').text($window_width);
		$(window).resize(function(){		
			$window_width = $(window).width();
			$('.screen-width').text($window_width);
		});
	}
        
        
/* ==================================================
   Thumbs / Social Effects
================================================== */

BRUSHED.utils = function(){
	
	$('.item-thumbs').bind('touchstart', function(){
		$(".active").removeClass("active");
      	$(this).addClass('active');
    });
	
	$('.image-wrap').bind('touchstart', function(){
		$(".active").removeClass("active");
      	$(this).addClass('active');
    });
	
	$('#social ul li').bind('touchstart', function(){
		$(".active").removeClass("active");
      	$(this).addClass('active');
    });
	
}

/* ==================================================
   Accordion
================================================== */

BRUSHED.accordion = function(){
	var accordion_trigger = $('.accordion-heading.accordionize');
	
	accordion_trigger.delegate('.accordion-toggle','click', function(event){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		   	$(this).addClass('inactive');
		}
		else{
		  	accordion_trigger.find('.active').addClass('inactive');          
		  	accordion_trigger.find('.active').removeClass('active');   
		  	$(this).removeClass('inactive');
		  	$(this).addClass('active');
	 	}
		event.preventDefault();
	});
}

/* ==================================================
   Toggle
================================================== */

BRUSHED.toggle = function(){
	var accordion_trigger_toggle = $('.accordion-heading.togglize');
	
	accordion_trigger_toggle.delegate('.accordion-toggle','click', function(event){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		   	$(this).addClass('inactive');
		}
		else{
		  	$(this).removeClass('inactive');
		  	$(this).addClass('active');
	 	}
		event.preventDefault();
	});
}

/* ==================================================
   Tooltip
================================================== */

BRUSHED.toolTip = function(){ 
    $('a[data-toggle=tooltip]').tooltip();
}


/* ==================================================
	Init
================================================== */
        
        
	fc_header_adjustment();
	fc_scroll_down();
	fc_screen_width();
        BRUSHED.utils();
	BRUSHED.accordion();
	BRUSHED.toggle();
	BRUSHED.toolTip();
        

    
    
// Instantiate MixItUp:

$('.portfolioContainer').mixItUp(    {
animation: {
    duration: 400,
    effects: 'fade stagger(104ms) translateZ(-40px) rotateX(-29deg)',
    easing: 'ease'
}
});
            
    
    	//mobile - open lateral menu clicking on the menu icon
	$('.cd-nav-trigger').on('click', function(event){
		event.preventDefault();
		if( $('.cd-main-content').hasClass('nav-is-visible') ) {
			closeNav();
			$('.cd-overlay').removeClass('is-visible');
		} else {
			$(this).addClass('nav-is-visible');
			$('.cd-primary-nav').addClass('nav-is-visible');
			$('.cd-main-header').addClass('nav-is-visible');
			$('.cd-main-content').addClass('nav-is-visible').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
				$('body').addClass('overflow-hidden');
			});
			toggleSearch('close');
			$('.cd-overlay').addClass('is-visible');
		}
	});        
    
            //SlidePushMenus
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ), //das sind alles ID Klassen
				menuRight = document.getElementById( 'cbp-spmenu-s2' ),
				menuTop = document.getElementById( 'cbp-spmenu-s3' ),
				menuBottom = document.getElementById( 'cbp-spmenu-s4' ),
				showLeft = document.getElementById( 'showLeft' ),
				showRight = document.getElementById( 'showRight' ),
				showTop = document.getElementById( 'showTop' ),
				showBottom = document.getElementById( 'showBottom' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				showRightPush = document.getElementById( 'showRightPush' ),
				body = document.body;

			showLeft.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeft' );
			};
			showRight.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuRight, 'cbp-spmenu-open' );
				disableOther( 'showRight' );
			};
			showTop.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuTop, 'cbp-spmenu-open' );
				disableOther( 'showTop' );
			};
			showBottom.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuBottom, 'cbp-spmenu-open' );
				disableOther( 'showBottom' );
			};
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			showRightPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toleft' );
				classie.toggle( menuRight, 'cbp-spmenu-open' );
				disableOther( 'showRightPush' );
			};

			function disableOther( button ) {
				if( button !== 'showLeft' ) {
					classie.toggle( showLeft, 'disabled' );
				}
				if( button !== 'showRight' ) {
					classie.toggle( showRight, 'disabled' );
				}
				if( button !== 'showTop' ) {
					classie.toggle( showTop, 'disabled' );
				}
				if( button !== 'showBottom' ) {
					classie.toggle( showBottom, 'disabled' );
				}
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
				if( button !== 'showRightPush' ) {
					classie.toggle( showRightPush, 'disabled' );
				}
			}



})(jQuery);