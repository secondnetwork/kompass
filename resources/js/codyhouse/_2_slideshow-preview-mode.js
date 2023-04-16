// File#: _2_slideshow-preview-mode
// Usage: codyhouse.co/license
(function() {
    var SlideshowPrew = function(opts) {
      this.options = Util.extend(SlideshowPrew.defaults , opts);
      this.element = this.options.element;
      this.list = this.element.getElementsByClassName('js-slideshow-pm__list')[0];
      this.items = this.list.getElementsByClassName('js-slideshow-pm__item');
      this.controls = this.element.getElementsByClassName('js-slideshow-pm__control'); 
      this.selectedSlide = 0;
      this.autoplayId = false;
      this.autoplayPaused = false;
      this.navigation = false;
      this.navCurrentLabel = false;
      this.ariaLive = false;
      this.moveFocus = false;
      this.animating = false;
      this.supportAnimation = Util.cssSupports('transition');
      this.itemWidth = false;
      this.itemMargin = false;
      this.containerWidth = false;
      this.resizeId = false;
      // we will need this to implement keyboard nav
      this.firstFocusable = false;
      this.lastFocusable = false;
      // fallback for browsers not supporting flexbox
      initSlideshow(this);
      initSlideshowEvents(this);
      initAnimationEndEvents(this);
      Util.addClass(this.element, 'slideshow-pm--js-loaded');
    };
  
    SlideshowPrew.prototype.showNext = function(autoplay) {
      showNewItem(this, this.selectedSlide + 1, 'next', autoplay);
    };
  
    SlideshowPrew.prototype.showPrev = function() {
      showNewItem(this, this.selectedSlide - 1, 'prev');
    };
  
    SlideshowPrew.prototype.showItem = function(index) {
      showNewItem(this, index, false);
    };
  
    SlideshowPrew.prototype.startAutoplay = function() {
      var self = this;
      if(this.options.autoplay && !this.autoplayId && !this.autoplayPaused) {
        self.autoplayId = setInterval(function(){
          self.showNext(true);
        }, self.options.autoplayInterval);
      }
    };
  
    SlideshowPrew.prototype.pauseAutoplay = function() {
      var self = this;
      if(this.options.autoplay) {
        clearInterval(self.autoplayId);
        self.autoplayId = false;
      }
    };
  
    function initSlideshow(slideshow) { // basic slideshow settings
      // if no slide has been selected -> select the first one
      if(slideshow.element.getElementsByClassName('slideshow-pm__item--selected').length < 1) Util.addClass(slideshow.items[0], 'slideshow-pm__item--selected');
      slideshow.selectedSlide = Util.getIndexInArray(slideshow.items, slideshow.element.getElementsByClassName('slideshow-pm__item--selected')[0]);
      // now set translate value to the container element
      setTranslateValue(slideshow);
      setTranslate(slideshow);
      resetSlideshowNav(slideshow, 0, slideshow.selectedSlide);
      setFocusableElements(slideshow);
      // if flexbox is not supported, set a width for the list element
      if(!flexSupported) resetSlideshowFlexFallback(slideshow);
      // now add class to animate while translating
      setTimeout(function(){Util.addClass(slideshow.list, 'slideshow-pm__list--has-transition');}, 50);
      // add arai-hidden to not selected slides
      for(var i = 0; i < slideshow.items.length; i++) {
        (i == slideshow.selectedSlide) ? slideshow.items[i].removeAttribute('aria-hidden') : slideshow.items[i].setAttribute('aria-hidden', 'true');
      }
      // create an element that will be used to announce the new visible slide to SR
      var srLiveArea = document.createElement('div');
      Util.setAttributes(srLiveArea, {'class': 'sr-only js-slideshow-pm__aria-live', 'aria-live': 'polite', 'aria-atomic': 'true'});
      slideshow.element.appendChild(srLiveArea);
      slideshow.ariaLive = srLiveArea;
    };
  
    function initSlideshowEvents(slideshow) {
      // if slideshow navigation is on -> create navigation HTML and add event listeners
      if(slideshow.options.navigation) {
        var navigation = document.createElement('ol'),
          navChildren = '';
        
        navigation.setAttribute('class', 'slideshow-pm__navigation');
        for(var i = 0; i < slideshow.items.length; i++) {
          var className = (i == slideshow.selectedSlide) ? 'class="slideshow-pm__nav-item slideshow-pm__nav-item--selected js-slideshow-pm__nav-item"' :  'class="slideshow-pm__nav-item js-slideshow-pm__nav-item"',
            navCurrentLabel = (i == slideshow.selectedSlide) ? '<span class="sr-only js-slideshow-pm__nav-current-label">Current Item</span>' : '';
          navChildren = navChildren + '<li '+className+'><button class="reset"><span class="sr-only">'+ (i+1) + '</span>'+navCurrentLabel+'</button></li>';
        }
  
        navigation.innerHTML = navChildren;
        slideshow.navCurrentLabel = navigation.getElementsByClassName('js-slideshow-pm__nav-current-label')[0]; 
        slideshow.element.appendChild(navigation);
        slideshow.navigation = slideshow.element.getElementsByClassName('js-slideshow-pm__nav-item');
  
        navigation.addEventListener('click', function(event){
          navigateSlide(slideshow, event, true);
        });
        navigation.addEventListener('keyup', function(event){
          navigateSlide(slideshow, event, (event.key.toLowerCase() == 'enter'));
        });
      }
      // slideshow arrow controls
      if(slideshow.controls.length > 0) {
        slideshow.controls[0].addEventListener('click', function(event){
          event.preventDefault();
          slideshow.showPrev();
          updateAriaLive(slideshow);
        });
        slideshow.controls[1].addEventListener('click', function(event){
          event.preventDefault();
          slideshow.showNext(false);
          updateAriaLive(slideshow);
        });
      }
      // navigate slideshow when clicking on preview
      if(slideshow.options.prewNav) {
        slideshow.element.addEventListener('click', function(event){
          var item = event.target.closest('.js-slideshow-pm__item');
          if(item && !Util.hasClass(item, 'slideshow-pm__item--selected')) {
            slideshow.showItem(Util.getIndexInArray(slideshow.items, item));
          }
        });
      }
      // swipe events
      if(slideshow.options.swipe) {
        //init swipe
        new SwipeContent(slideshow.element);
        slideshow.element.addEventListener('swipeLeft', function(event){
          slideshow.showNext(false);
        });
        slideshow.element.addEventListener('swipeRight', function(event){
          slideshow.showPrev();
        });
      }
      // autoplay
      if(slideshow.options.autoplay) {
        slideshow.startAutoplay();
        // pause autoplay if user is interacting with the slideshow
        slideshow.element.addEventListener('mouseenter', function(event){
          slideshow.pauseAutoplay();
          slideshow.autoplayPaused = true;
        });
        slideshow.element.addEventListener('focusin', function(event){
          slideshow.pauseAutoplay();
          slideshow.autoplayPaused = true;
        });
        slideshow.element.addEventListener('mouseleave', function(event){
          slideshow.autoplayPaused = false;
          slideshow.startAutoplay();
        });
        slideshow.element.addEventListener('focusout', function(event){
          slideshow.autoplayPaused = false;
          slideshow.startAutoplay();
        });
      }
      // keyboard navigation
      initKeyboardEvents(slideshow);
      // reset on resize
      window.addEventListener('resize', function(event){
          slideshow.pauseAutoplay();
        clearTimeout(slideshow.resizeId);
        slideshow.resizeId = setTimeout(function(){
          resetSlideshowResize(slideshow);
          setTimeout(function(){slideshow.startAutoplay();}, 60);
        }, 250)
      });
    };
  
    function initKeyboardEvents(slideshow) {
      // tab on selected slide -> if last focusable -> move to prev or next arrow
      // tab + shift selected slide -> if first focusable -> move to container
      if(slideshow.controls.length > 0) {
        // tab+shift on prev arrow -> move focus to last focusable element inside the selected slide (or to the slider container)
        slideshow.controls[0].addEventListener('keydown', function(event){
          if( (event.keyCode && event.keyCode == 9 || event.key && event.key == 'Tab') && event.shiftKey ) moveFocusToLast(slideshow);
        });
        // tab+shift on next arrow -> if first slide selectes -> move focus to last focusable element inside the selected slide (or to the slider container)
        slideshow.controls[1].addEventListener('keydown', function(event){
          if( (event.keyCode && event.keyCode == 9 || event.key && event.key == 'Tab') && event.shiftKey && (slideshow.selectedSlide == 0)) moveFocusToLast(slideshow);
        });
      }
      // check tab is pressed when focus is inside selected slide
      slideshow.element.addEventListener('keydown', function(event){
        if( event.keyCode && event.keyCode == 9 || event.key && event.key == 'Tab' ) {
          var target = event.target.closest('.js-slideshow-pm__item');
          if(target && Util.hasClass(target, 'slideshow-pm__item--selected')) moveFocusOutsideSlide(slideshow, event);
          else if(target || Util.hasClass(event.target, 'js-slideshow-pm') && !event.shiftKey) moveFocusToSelectedSlide(slideshow);
        } 
      });
  
      // detect tab moves to slideshow 
      window.addEventListener('keyup', function(event){
        if( event.keyCode && event.keyCode == 9 || event.key && event.key == 'Tab') {
          var target = event.target.closest('.js-slideshow-prew__item');
          if(target || Util.hasClass(event.target, 'js-slideshow-prew') && !event.shiftKey) moveFocusToSelectedSlide(slideshow);
        }
      });
    };
  
    function moveFocusToLast(slideshow) {
      event.preventDefault();
      if(slideshow.lastFocusable)	{
        slideshow.lastFocusable.focus();
      } else {
        Util.moveFocus(slideshow.element);
      }
    };
  
    function moveFocusToSelectedSlide(slideshow) { // focus is inside a slide that is not selected
      event.preventDefault();
      if(slideshow.firstFocusable)	{
        slideshow.firstFocusable.focus();
      } else if(slideshow.controls.length > 0) {
        (slideshow.selectedSlide == 0) ? slideshow.controls[1].getElementsByTagName('button')[0].focus() : slideshow.controls[0].getElementsByTagName('button')[0].focus();
      } else if(slideshow.options.navigation) {
        slideshow.navigation.getElementsByClassName('js-slideshow-pm__nav-item')[0].getElementsByTagName('button')[0].focus();
      }
    };
  
    function moveFocusOutsideSlide(slideshow, event) {
      if(event.shiftKey && slideshow.firstFocusable && event.target == slideshow.firstFocusable) {
        // shift+tab -> focus was on first foucusable element inside selected slide -> move to container
        event.preventDefault();
        Util.moveFocus(slideshow.element);
      } else if( !event.shiftKey && slideshow.lastFocusable && event.target == slideshow.lastFocusable) {
        event.preventDefault();
        
        if(slideshow.selectedSlide != 0) slideshow.controls[0].getElementsByTagName('button')[0].focus();
        else slideshow.controls[1].getElementsByTagName('button')[0].focus();
      }
    };
  
    function initAnimationEndEvents(slideshow) {
      slideshow.list.addEventListener('transitionend', function(){
        setTimeout(function(){ // add a delay between the end of animation and slideshow reset - improve animation performance
          resetAnimationEnd(slideshow);
        }, 100);
      });
    };
  
    function resetAnimationEnd(slideshow) {
      if(slideshow.moveFocus) Util.moveFocus(slideshow.items[slideshow.selectedSlide]);
      slideshow.items[slideshow.selectedSlide].removeAttribute('aria-hidden');
      slideshow.animating = false;
      slideshow.moveFocus = false;
      slideshow.startAutoplay();
    };
  
    function navigateSlide(slideshow, event, keyNav) { 
      // user has interacted with the slideshow navigation -> update visible slide
      var target = event.target.closest('.js-slideshow-pm__nav-item');
      if(keyNav && target && !Util.hasClass(target, 'slideshow-pm__nav-item--selected')) {
        slideshow.showItem(Util.getIndexInArray(slideshow.navigation, target));
        slideshow.moveFocus = true;
        updateAriaLive(slideshow);
      }
    };
  
    function showNewItem(slideshow, index, bool, autoplay) {
      if(slideshow.animating && slideshow.supportAnimation) return;
      if(autoplay) {
        if(index < 0) index = slideshow.items.length - 1;
        else if(index >= slideshow.items.length) index = 0;
      }
      if(index < 0 || index >= slideshow.items.length) return;
      slideshow.animating = true;
      Util.removeClass(slideshow.items[slideshow.selectedSlide], 'slideshow-pm__item--selected');
      slideshow.items[slideshow.selectedSlide].setAttribute('aria-hidden', 'true'); //hide to sr element that is exiting the viewport
      Util.addClass(slideshow.items[index], 'slideshow-pm__item--selected');
      resetSlideshowNav(slideshow, index, slideshow.selectedSlide);
      slideshow.selectedSlide = index;
      setTranslate(slideshow);
      slideshow.pauseAutoplay();
      setFocusableElements(slideshow);
      if(!transitionSupported) resetAnimationEnd(slideshow);
    };
  
    function updateAriaLive(slideshow) {
      slideshow.ariaLive.innerHTML = 'Item '+(slideshow.selectedSlide + 1)+' of '+slideshow.items.length;
    };
  
    function resetSlideshowResize(slideshow) {
      Util.removeClass(slideshow.list, 'slideshow-pm__list--has-transition');
      setTimeout(function(){
        setTranslateValue(slideshow);
        setTranslate(slideshow);
        Util.addClass(slideshow.list, 'slideshow-pm__list--has-transition');
      }, 30)
    };
  
    function setTranslateValue(slideshow) {
      var itemStyle = window.getComputedStyle(slideshow.items[slideshow.selectedSlide]);
  
      slideshow.itemWidth = parseFloat(itemStyle.getPropertyValue('width'));
      slideshow.itemMargin = parseFloat(itemStyle.getPropertyValue('margin-right'));
      slideshow.containerWidth = parseFloat(window.getComputedStyle(slideshow.element).getPropertyValue('width'));
    };
  
    function setTranslate(slideshow) {
      var translate = parseInt(((slideshow.itemWidth + slideshow.itemMargin) * slideshow.selectedSlide * (-1)) + ((slideshow.containerWidth - slideshow.itemWidth)*0.5));
      slideshow.list.style.transform = 'translateX('+translate+'px)';
      slideshow.list.style.msTransform = 'translateX('+translate+'px)';
    };
  
    function resetSlideshowNav(slideshow, newIndex, oldIndex) {
        if(slideshow.navigation) {
        Util.removeClass(slideshow.navigation[oldIndex], 'slideshow-pm__nav-item--selected');
        Util.addClass(slideshow.navigation[newIndex], 'slideshow-pm__nav-item--selected');
        slideshow.navCurrentLabel.parentElement.removeChild(slideshow.navCurrentLabel);
        slideshow.navigation[newIndex].getElementsByTagName('button')[0].appendChild(slideshow.navCurrentLabel);
      }
      if(slideshow.controls.length > 0) {
        Util.toggleClass(slideshow.controls[0], 'slideshow-pm__control--active', newIndex != 0);
        Util.toggleClass(slideshow.controls[1], 'slideshow-pm__control--active', newIndex != (slideshow.items.length - 1));
        }
    };
  
    function setFocusableElements(slideshow) {
        //get all focusable elements inside the selected slide
      var allFocusable = slideshow.items[slideshow.selectedSlide].querySelectorAll('[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex]:not([tabindex="-1"]), [contenteditable], audio[controls], video[controls], summary');
      getFirstVisible(slideshow, allFocusable);
      getLastVisible(slideshow, allFocusable);
    };
  
    function getFirstVisible(slideshow, elements) {
        slideshow.firstFocusable = false;
      //get first visible focusable element inside the selected slide
      for(var i = 0; i < elements.length; i++) {
        if( elements[i].offsetWidth || elements[i].offsetHeight || elements[i].getClientRects().length ) {
          slideshow.firstFocusable = elements[i];
          return true;
        }
      }
    };
  
    function getLastVisible(slideshow, elements) {
        //get last visible focusable element inside the selected slide
        slideshow.lastFocusable = false;
      for(var i = elements.length - 1; i >= 0; i--) {
        if( elements[i].offsetWidth || elements[i].offsetHeight || elements[i].getClientRects().length ) {
          slideshow.lastFocusable = elements[i];
          return true;
        }
      }
    };
  
    function resetSlideshowFlexFallback(slideshow) {
      slideshow.list.style.width = ((slideshow.items.length+1)*(slideshow.itemMargin+slideshow.itemWidth))+'px';
      for(var i = 0; i < slideshow.items.length; i++) {slideshow.items[i].style.width = slideshow.itemWidth+'px';}
    };
  
    SlideshowPrew.defaults = {
      element : '',
      navigation : true,
      autoplay : false,
      autoplayInterval: 5000,
      prewNav: false,
      swipe: false
    };
  
    window.SlideshowPrew = SlideshowPrew;
    
    // initialize the slideshowsPrew objects
    var slideshowsPrew = document.getElementsByClassName('js-slideshow-pm'),
      flexSupported = Util.cssSupports('align-items', 'stretch'),
      transitionSupported = Util.cssSupports('transition');
    if( slideshowsPrew.length > 0 ) {
      for( var i = 0; i < slideshowsPrew.length; i++) {
        (function(i){
          var navigation = (slideshowsPrew[i].getAttribute('data-navigation') && slideshowsPrew[i].getAttribute('data-navigation') == 'off') ? false : true,
            autoplay = (slideshowsPrew[i].getAttribute('data-autoplay') && slideshowsPrew[i].getAttribute('data-autoplay') == 'on') ? true : false,
            autoplayInterval = (slideshowsPrew[i].getAttribute('data-autoplay-interval')) ? slideshowsPrew[i].getAttribute('data-autoplay-interval') : 5000,
            prewNav = (slideshowsPrew[i].getAttribute('data-pm-nav') && slideshowsPrew[i].getAttribute('data-pm-nav') == 'on' ) ? true : false, 
            swipe = (slideshowsPrew[i].getAttribute('data-swipe') && slideshowsPrew[i].getAttribute('data-swipe') == 'on') ? true : false;
          new SlideshowPrew({element: slideshowsPrew[i], navigation: navigation, autoplay : autoplay, autoplayInterval : autoplayInterval, swipe : swipe, prewNav: prewNav});
        })(i);
      }
    }
  
  }());