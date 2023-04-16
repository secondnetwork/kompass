// File#: _2_slideshow
// Usage: codyhouse.co/license
(function () {
	var Slideshow = function (opts) {
		this.options = slideshowAssignOptions(Slideshow.defaults, opts);
		this.element = this.options.element;
		this.items = this.element.getElementsByClassName('js-slideshow__item');
		this.controls = this.element.getElementsByClassName('js-slideshow__control');
		this.selectedSlide = 0;
		this.autoplayId = false;
		this.autoplayPaused = false;
		this.navigation = false;
		this.navCurrentLabel = false;
		this.ariaLive = false;
		this.moveFocus = false;
		this.animating = false;
		this.supportAnimation = Util.cssSupports('transition');
		this.animationOff = (!Util.hasClass(this.element, 'slideshow--transition-fade') && !Util.hasClass(this.element, 'slideshow--transition-slide') && !Util.hasClass(this.element, 'slideshow--transition-prx'));
		this.animationType = Util.hasClass(this.element, 'slideshow--transition-prx') ? 'prx' : 'slide';
		this.animatingClass = 'slideshow--is-animating';
		initSlideshow(this);
		initSlideshowEvents(this);
		initAnimationEndEvents(this);
	};

	Slideshow.prototype.showNext = function () {
		showNewItem(this, this.selectedSlide + 1, 'next');
	};

	Slideshow.prototype.showPrev = function () {
		showNewItem(this, this.selectedSlide - 1, 'prev');
	};

	Slideshow.prototype.showItem = function (index) {
		showNewItem(this, index, false);
	};

	Slideshow.prototype.startAutoplay = function () {
		var self = this;
		if (this.options.autoplay && !this.autoplayId && !this.autoplayPaused) {
			self.autoplayId = setInterval(function () {
				self.showNext();
			}, self.options.autoplayInterval);
		}
	};

	Slideshow.prototype.pauseAutoplay = function () {
		var self = this;
		if (this.options.autoplay) {
			clearInterval(self.autoplayId);
			self.autoplayId = false;
		}
	};

	function slideshowAssignOptions(defaults, opts) {
		// initialize the object options
		var mergeOpts = {};
		mergeOpts.element = (typeof opts.element !== "undefined") ? opts.element : defaults.element;
		mergeOpts.navigation = (typeof opts.navigation !== "undefined") ? opts.navigation : defaults.navigation;
		mergeOpts.autoplay = (typeof opts.autoplay !== "undefined") ? opts.autoplay : defaults.autoplay;
		mergeOpts.autoplayInterval = (typeof opts.autoplayInterval !== "undefined") ? opts.autoplayInterval : defaults.autoplayInterval;
		mergeOpts.swipe = (typeof opts.swipe !== "undefined") ? opts.swipe : defaults.swipe;
		return mergeOpts;
	};

	function initSlideshow(slideshow) { // basic slideshow settings
		// if no slide has been selected -> select the first one
		if (slideshow.element.getElementsByClassName('slideshow__item--selected').length < 1) Util.addClass(slideshow.items[0], 'slideshow__item--selected');
		slideshow.selectedSlide = Util.getIndexInArray(slideshow.items, slideshow.element.getElementsByClassName('slideshow__item--selected')[0]);
		// create an element that will be used to announce the new visible slide to SR
		var srLiveArea = document.createElement('div');
		Util.setAttributes(srLiveArea, { 'class': 'sr-only js-slideshow__aria-live', 'aria-live': 'polite', 'aria-atomic': 'true' });
		slideshow.element.appendChild(srLiveArea);
		slideshow.ariaLive = srLiveArea;
	};

	function initSlideshowEvents(slideshow) {
		// if slideshow navigation is on -> create navigation HTML and add event listeners
		if (slideshow.options.navigation) {
			// check if navigation has already been included
			if (slideshow.element.getElementsByClassName('js-slideshow__navigation').length == 0) {
				var navigation = document.createElement('ol'),
					navChildren = '';

				var navClasses = 'slideshow__navigation js-slideshow__navigation';
				if (slideshow.items.length <= 1) {
					navClasses = navClasses + ' is-hidden';
				}

				navigation.setAttribute('class', navClasses);
				for (var i = 0; i < slideshow.items.length; i++) {
					var className = (i == slideshow.selectedSlide) ? 'class="slideshow__nav-item slideshow__nav-item--selected js-slideshow__nav-item"' : 'class="slideshow__nav-item js-slideshow__nav-item"',
						navCurrentLabel = (i == slideshow.selectedSlide) ? '<span class="sr-only js-slideshow__nav-current-label">Current Item</span>' : '';
					navChildren = navChildren + '<li ' + className + '><button class="reset"><span class="sr-only">' + (i + 1) + '</span>' + navCurrentLabel + '</button></li>';
				}
				navigation.innerHTML = navChildren;
				slideshow.element.appendChild(navigation);
			}

			slideshow.navCurrentLabel = slideshow.element.getElementsByClassName('js-slideshow__nav-current-label')[0];
			slideshow.navigation = slideshow.element.getElementsByClassName('js-slideshow__nav-item');

			var dotsNavigation = slideshow.element.getElementsByClassName('js-slideshow__navigation')[0];

			dotsNavigation.addEventListener('click', function (event) {
				navigateSlide(slideshow, event, true);
			});
			dotsNavigation.addEventListener('keyup', function (event) {
				navigateSlide(slideshow, event, (event.key.toLowerCase() == 'enter'));
			});
		}
		// slideshow arrow controls
		if (slideshow.controls.length > 0) {
			// hide controls if one item available
			if (slideshow.items.length <= 1) {
				Util.addClass(slideshow.controls[0], 'is-hidden');
				Util.addClass(slideshow.controls[1], 'is-hidden');
			}
			slideshow.controls[0].addEventListener('click', function (event) {
				event.preventDefault();
				slideshow.showPrev();
				updateAriaLive(slideshow);
			});
			slideshow.controls[1].addEventListener('click', function (event) {
				event.preventDefault();
				slideshow.showNext();
				updateAriaLive(slideshow);
			});
		}
		// swipe events
		if (slideshow.options.swipe) {
			//init swipe
			new SwipeContent(slideshow.element);
			slideshow.element.addEventListener('swipeLeft', function (event) {
				slideshow.showNext();
			});
			slideshow.element.addEventListener('swipeRight', function (event) {
				slideshow.showPrev();
			});
		}
		// autoplay
		if (slideshow.options.autoplay) {
			slideshow.startAutoplay();
			// pause autoplay if user is interacting with the slideshow
			slideshow.element.addEventListener('mouseenter', function (event) {
				slideshow.pauseAutoplay();
				slideshow.autoplayPaused = true;
			});
			slideshow.element.addEventListener('focusin', function (event) {
				slideshow.pauseAutoplay();
				slideshow.autoplayPaused = true;
			});
			slideshow.element.addEventListener('mouseleave', function (event) {
				slideshow.autoplayPaused = false;
				slideshow.startAutoplay();
			});
			slideshow.element.addEventListener('focusout', function (event) {
				slideshow.autoplayPaused = false;
				slideshow.startAutoplay();
			});
		}
		// detect if external buttons control the slideshow
		var slideshowId = slideshow.element.getAttribute('id');
		if (slideshowId) {
			var externalControls = document.querySelectorAll('[data-controls="' + slideshowId + '"]');
			for (var i = 0; i < externalControls.length; i++) {
				(function (i) { externalControlSlide(slideshow, externalControls[i]); })(i);
			}
		}
		// custom event to trigger selection of a new slide element
		slideshow.element.addEventListener('selectNewItem', function (event) {
			// check if slide is already selected
			if (event.detail) {
				if (event.detail - 1 == slideshow.selectedSlide) return;
				showNewItem(slideshow, event.detail - 1, false);
			}
		});

		// keyboard navigation
		slideshow.element.addEventListener('keydown', function (event) {
			if (event.keyCode && event.keyCode == 39 || event.key && event.key.toLowerCase() == 'arrowright') {
				slideshow.showNext();
			} else if (event.keyCode && event.keyCode == 37 || event.key && event.key.toLowerCase() == 'arrowleft') {
				slideshow.showPrev();
			}
		});
	};

	function navigateSlide(slideshow, event, keyNav) {
		// user has interacted with the slideshow navigation -> update visible slide
		var target = (Util.hasClass(event.target, 'js-slideshow__nav-item')) ? event.target : event.target.closest('.js-slideshow__nav-item');
		if (keyNav && target && !Util.hasClass(target, 'slideshow__nav-item--selected')) {
			slideshow.showItem(Util.getIndexInArray(slideshow.navigation, target));
			slideshow.moveFocus = true;
			updateAriaLive(slideshow);
		}
	};

	function initAnimationEndEvents(slideshow) {
		// remove animation classes at the end of a slide transition
		for (var i = 0; i < slideshow.items.length; i++) {
			(function (i) {
				slideshow.items[i].addEventListener('animationend', function () { resetAnimationEnd(slideshow, slideshow.items[i]); });
				slideshow.items[i].addEventListener('transitionend', function () { resetAnimationEnd(slideshow, slideshow.items[i]); });
			})(i);
		}
	};

	function resetAnimationEnd(slideshow, item) {
		setTimeout(function () { // add a delay between the end of animation and slideshow reset - improve animation performance
			if (Util.hasClass(item, 'slideshow__item--selected')) {
				if (slideshow.moveFocus) Util.moveFocus(item);
				emitSlideshowEvent(slideshow, 'newItemVisible', slideshow.selectedSlide);
				slideshow.moveFocus = false;
			}
			Util.removeClass(item, 'slideshow__item--' + slideshow.animationType + '-out-left slideshow__item--' + slideshow.animationType + '-out-right slideshow__item--' + slideshow.animationType + '-in-left slideshow__item--' + slideshow.animationType + '-in-right');
			item.removeAttribute('aria-hidden');
			slideshow.animating = false;
			Util.removeClass(slideshow.element, slideshow.animatingClass);
		}, 100);
	};

	function showNewItem(slideshow, index, bool) {
		if (slideshow.items.length <= 1) return;
		if (slideshow.animating && slideshow.supportAnimation) return;
		slideshow.animating = true;
		Util.addClass(slideshow.element, slideshow.animatingClass);
		if (index < 0) index = slideshow.items.length - 1;
		else if (index >= slideshow.items.length) index = 0;
		// skip slideshow item if it is hidden
		if (bool && Util.hasClass(slideshow.items[index], 'is-hidden')) {
			slideshow.animating = false;
			index = bool == 'next' ? index + 1 : index - 1;
			showNewItem(slideshow, index, bool);
			return;
		}
		// index of new slide is equal to index of slide selected item
		if (index == slideshow.selectedSlide) {
			slideshow.animating = false;
			return;
		}
		var exitItemClass = getExitItemClass(slideshow, bool, slideshow.selectedSlide, index);
		var enterItemClass = getEnterItemClass(slideshow, bool, slideshow.selectedSlide, index);
		// transition between slides
		if (!slideshow.animationOff) Util.addClass(slideshow.items[slideshow.selectedSlide], exitItemClass);
		Util.removeClass(slideshow.items[slideshow.selectedSlide], 'slideshow__item--selected');
		slideshow.items[slideshow.selectedSlide].setAttribute('aria-hidden', 'true'); //hide to sr element that is exiting the viewport
		if (slideshow.animationOff) {
			Util.addClass(slideshow.items[index], 'slideshow__item--selected');
		} else {
			Util.addClass(slideshow.items[index], enterItemClass + ' slideshow__item--selected');
		}
		// reset slider navigation appearance
		resetSlideshowNav(slideshow, index, slideshow.selectedSlide);
		slideshow.selectedSlide = index;
		// reset autoplay
		slideshow.pauseAutoplay();
		slideshow.startAutoplay();
		// reset controls/navigation color themes
		resetSlideshowTheme(slideshow, index);
		// emit event
		emitSlideshowEvent(slideshow, 'newItemSelected', slideshow.selectedSlide);
		if (slideshow.animationOff) {
			slideshow.animating = false;
			Util.removeClass(slideshow.element, slideshow.animatingClass);
		}
	};

	function getExitItemClass(slideshow, bool, oldIndex, newIndex) {
		var className = '';
		if (bool) {
			className = (bool == 'next') ? 'slideshow__item--' + slideshow.animationType + '-out-right' : 'slideshow__item--' + slideshow.animationType + '-out-left';
		} else {
			className = (newIndex < oldIndex) ? 'slideshow__item--' + slideshow.animationType + '-out-left' : 'slideshow__item--' + slideshow.animationType + '-out-right';
		}
		return className;
	};

	function getEnterItemClass(slideshow, bool, oldIndex, newIndex) {
		var className = '';
		if (bool) {
			className = (bool == 'next') ? 'slideshow__item--' + slideshow.animationType + '-in-right' : 'slideshow__item--' + slideshow.animationType + '-in-left';
		} else {
			className = (newIndex < oldIndex) ? 'slideshow__item--' + slideshow.animationType + '-in-left' : 'slideshow__item--' + slideshow.animationType + '-in-right';
		}
		return className;
	};

	function resetSlideshowNav(slideshow, newIndex, oldIndex) {
		if (slideshow.navigation) {
			Util.removeClass(slideshow.navigation[oldIndex], 'slideshow__nav-item--selected');
			Util.addClass(slideshow.navigation[newIndex], 'slideshow__nav-item--selected');
			slideshow.navCurrentLabel.parentElement.removeChild(slideshow.navCurrentLabel);
			slideshow.navigation[newIndex].getElementsByTagName('button')[0].appendChild(slideshow.navCurrentLabel);
		}
	};

	function resetSlideshowTheme(slideshow, newIndex) {
		var dataTheme = slideshow.items[newIndex].getAttribute('data-theme');
		if (dataTheme) {
			if (slideshow.navigation) slideshow.navigation[0].parentElement.setAttribute('data-theme', dataTheme);
			if (slideshow.controls[0]) slideshow.controls[0].parentElement.setAttribute('data-theme', dataTheme);
		} else {
			if (slideshow.navigation) slideshow.navigation[0].parentElement.removeAttribute('data-theme');
			if (slideshow.controls[0]) slideshow.controls[0].parentElement.removeAttribute('data-theme');
		}
	};

	function emitSlideshowEvent(slideshow, eventName, detail) {
		var event = new CustomEvent(eventName, { detail: detail });
		slideshow.element.dispatchEvent(event);
	};

	function updateAriaLive(slideshow) {
		slideshow.ariaLive.innerHTML = 'Item ' + (slideshow.selectedSlide + 1) + ' of ' + slideshow.items.length;
	};

	function externalControlSlide(slideshow, button) { // control slideshow using external element
		button.addEventListener('click', function (event) {
			var index = button.getAttribute('data-index');
			if (!index || index == slideshow.selectedSlide + 1) return;
			event.preventDefault();
			showNewItem(slideshow, index - 1, false);
		});
	};

	Slideshow.defaults = {
		element: '',
		navigation: true,
		autoplay: false,
		autoplayInterval: 5000,
		swipe: false
	};

	window.Slideshow = Slideshow;

	//initialize the Slideshow objects
	var slideshows = document.getElementsByClassName('js-slideshow');
	if (slideshows.length > 0) {
		for (var i = 0; i < slideshows.length; i++) {
			(function (i) {
				var navigation = (slideshows[i].getAttribute('data-navigation') && slideshows[i].getAttribute('data-navigation') == 'off') ? false : true,
					autoplay = (slideshows[i].getAttribute('data-autoplay') && slideshows[i].getAttribute('data-autoplay') == 'on') ? true : false,
					autoplayInterval = (slideshows[i].getAttribute('data-autoplay-interval')) ? slideshows[i].getAttribute('data-autoplay-interval') : 5000,
					swipe = (slideshows[i].getAttribute('data-swipe') && slideshows[i].getAttribute('data-swipe') == 'on') ? true : false;
				new Slideshow({ element: slideshows[i], navigation: navigation, autoplay: autoplay, autoplayInterval: autoplayInterval, swipe: swipe });
			})(i);
		}
	}
}());




!function () { var e = function (e) { var t; this.element = e, this.delta = [!1, !1], this.dragging = !1, this.intervalId = !1, (t = this).element.addEventListener("mousedown", o.bind(t)), t.element.addEventListener("touchstart", o.bind(t)) }; function o(e) { switch (e.type) { case "mousedown": case "touchstart": n = e, (t = this).dragging = !0, (i = t).element.addEventListener("mousemove", o.bind(i)), i.element.addEventListener("touchmove", o.bind(i)), i.element.addEventListener("mouseup", o.bind(i)), i.element.addEventListener("mouseleave", o.bind(i)), i.element.addEventListener("touchend", o.bind(i)), t.delta = [parseInt(r(n).clientX), parseInt(r(n).clientY)], d(t, "dragStart", t.delta, n.target); break; case "mousemove": case "touchmove": !function (e, t) { if (!e.dragging) return; window.requestAnimationFrame ? e.intervalId = window.requestAnimationFrame(s.bind(e, t)) : e.intervalId = setTimeout(function () { s.bind(e, t) }, 250) }(this, e); break; case "mouseup": case "mouseleave": case "touchend": !function (e, t) { n = e, n.intervalId && (window.requestAnimationFrame ? window.cancelAnimationFrame(n.intervalId) : clearInterval(n.intervalId), n.intervalId = !1), n.element.removeEventListener("mousemove", o.bind(n)), n.element.removeEventListener("touchmove", o.bind(n)), n.element.removeEventListener("mouseup", o.bind(n)), n.element.removeEventListener("mouseleave", o.bind(n)), n.element.removeEventListener("touchend", o.bind(n)); var n; var i = parseInt(r(t).clientX), s = parseInt(r(t).clientY); if (e.delta && (e.delta[0] || 0 === e.delta[0])) { var a = u(i - e.delta[0]); 30 < Math.abs(i - e.delta[0]) && d(e, a < 0 ? "swipeLeft" : "swipeRight", [i, s]), e.delta[0] = !1 } if (e.delta && (e.delta[1] || 0 === e.delta[1])) { var l = u(s - e.delta[1]); 30 < Math.abs(s - e.delta[1]) && d(e, l < 0 ? "swipeUp" : "swipeDown", [i, s]), e.delta[1] = !1 } d(e, "dragEnd", [i, s]), e.dragging = !1 }(this, e) }var t, n, i } function s(e) { d(this, "dragging", [parseInt(r(e).clientX), parseInt(r(e).clientY)]) } function r(e) { return e.changedTouches ? e.changedTouches[0] : e } function d(e, t, n, i) { var s = !1; i && (s = i); var a = new CustomEvent(t, { detail: { x: n[0], y: n[1], origin: s } }); e.element.dispatchEvent(a) } function u(e) { return Math.sign ? Math.sign(e) : (0 < e) - (e < 0) || +e } window.SwipeContent = e; var t = document.getElementsByClassName("js-swipe-content"); if (0 < t.length) for (var n = 0; n < t.length; n++)new e(t[n]) }(), function () { var n = function (e) { var t; this.options = Util.extend(n.defaults, e), this.element = this.options.element, this.list = this.element.getElementsByClassName("js-slideshow-pm__list")[0], this.items = this.list.getElementsByClassName("js-slideshow-pm__item"), this.controls = this.element.getElementsByClassName("js-slideshow-pm__control"), this.selectedSlide = 0, this.autoplayId = !1, this.autoplayPaused = !1, this.navigation = !1, this.navCurrentLabel = !1, this.ariaLive = !1, this.moveFocus = !1, this.animating = !1, this.supportAnimation = Util.cssSupports("transition"), this.itemWidth = !1, this.itemMargin = !1, this.containerWidth = !1, this.resizeId = !1, this.firstFocusable = !1, this.lastFocusable = !1, function (e) { e.element.getElementsByClassName("x-r_b").length < 1 && Util.addClass(e.items[0], "x-r_b"); e.selectedSlide = Util.getIndexInArray(e.items, e.element.getElementsByClassName("x-r_b")[0]), u(e), m(e), a(e, 0, e.selectedSlide), c(e), y || function (e) { e.list.style.width = (e.items.length + 1) * (e.itemMargin + e.itemWidth) + "px"; for (var t = 0; t < e.items.length; t++)e.items[t].style.width = e.itemWidth + "px" }(e); setTimeout(function () { Util.addClass(e.list, "x-r_y") }, 50); for (var t = 0; t < e.items.length; t++)t == e.selectedSlide ? e.items[t].removeAttribute("aria-hidden") : e.items[t].setAttribute("aria-hidden", "true"); var n = document.createElement("div"); Util.setAttributes(n, { class: "sr-only js-slideshow-pm__aria-live", "aria-live": "polite", "aria-atomic": "true" }), e.element.appendChild(n), e.ariaLive = n }(this), function (n) { if (n.options.navigation) { var e = document.createElement("ol"), t = ""; e.setAttribute("class", "slideshow-pm__navigation"); for (var i = 0; i < n.items.length; i++) { var s = i == n.selectedSlide ? 'class="slideshow-pm__nav-item slideshow-pm__nav-item--selected js-slideshow-pm__nav-item"' : 'class="slideshow-pm__nav-item js-slideshow-pm__nav-item"', a = i == n.selectedSlide ? '<span class="sr-only js-slideshow-pm__nav-current-label">Current Item</span>' : ""; t = t + "<li " + s + '><button class="reset"><span class="sr-only">' + (i + 1) + "</span>" + a + "</button></li>" } e.innerHTML = t, n.navCurrentLabel = e.getElementsByClassName("js-slideshow-pm__nav-current-label")[0], n.element.appendChild(e), n.navigation = n.element.getElementsByClassName("js-slideshow-pm__nav-item"), e.addEventListener("click", function (e) { r(n, e, !0) }), e.addEventListener("keyup", function (e) { r(n, e, "enter" == e.key.toLowerCase()) }) } 0 < n.controls.length && (n.controls[0].addEventListener("click", function (e) { e.preventDefault(), n.showPrev(), d(n) }), n.controls[1].addEventListener("click", function (e) { e.preventDefault(), n.showNext(!1), d(n) })); n.options.prewNav && n.element.addEventListener("click", function (e) { var t = e.target.closest(".js-slideshow-pm__item"); t && !Util.hasClass(t, "x-r_b") && n.showItem(Util.getIndexInArray(n.items, t)) }); n.options.swipe && (new SwipeContent(n.element), n.element.addEventListener("swipeLeft", function (e) { n.showNext(!1) }), n.element.addEventListener("swipeRight", function (e) { n.showPrev() })); n.options.autoplay && (n.startAutoplay(), n.element.addEventListener("mouseenter", function (e) { n.pauseAutoplay(), n.autoplayPaused = !0 }), n.element.addEventListener("focusin", function (e) { n.pauseAutoplay(), n.autoplayPaused = !0 }), n.element.addEventListener("mouseleave", function (e) { n.autoplayPaused = !1, n.startAutoplay() }), n.element.addEventListener("focusout", function (e) { n.autoplayPaused = !1, n.startAutoplay() })); (function (s) { 0 < s.controls.length && (s.controls[0].addEventListener("keydown", function (e) { (e.keyCode && 9 == e.keyCode || e.key && "Tab" == e.key) && e.shiftKey && l(s) }), s.controls[1].addEventListener("keydown", function (e) { (e.keyCode && 9 == e.keyCode || e.key && "Tab" == e.key) && e.shiftKey && 0 == s.selectedSlide && l(s) })); s.element.addEventListener("keydown", function (e) { if (e.keyCode && 9 == e.keyCode || e.key && "Tab" == e.key) { var t = e.target.closest(".js-slideshow-pm__item"); t && Util.hasClass(t, "x-r_b") ? (n = s, (i = e).shiftKey && n.firstFocusable && i.target == n.firstFocusable ? (i.preventDefault(), Util.moveFocus(n.element)) : !i.shiftKey && n.lastFocusable && i.target == n.lastFocusable && (i.preventDefault(), 0 != n.selectedSlide ? n.controls[0].getElementsByTagName("button")[0].focus() : n.controls[1].getElementsByTagName("button")[0].focus())) : (t || Util.hasClass(e.target, "js-slideshow-pm") && !e.shiftKey) && o(s) } var n, i }), window.addEventListener("keyup", function (e) { if (e.keyCode && 9 == e.keyCode || e.key && "Tab" == e.key) { var t = e.target.closest(".js-slideshow-prew__item"); (t || Util.hasClass(e.target, "js-slideshow-prew") && !e.shiftKey) && o(s) } }) })(n), window.addEventListener("resize", function (e) { n.pauseAutoplay(), clearTimeout(n.resizeId), n.resizeId = setTimeout(function () { var e; e = n, Util.removeClass(e.list, "x-r_y"), setTimeout(function () { u(e), m(e), Util.addClass(e.list, "x-r_y") }, 30), setTimeout(function () { n.startAutoplay() }, 60) }, 250) }) }(this), (t = this).list.addEventListener("transitionend", function () { setTimeout(function () { s(t) }, 100) }), Util.addClass(this.element, "x-r_m") }; function l(e) { event.preventDefault(), e.lastFocusable ? e.lastFocusable.focus() : Util.moveFocus(e.element) } function o(e) { event.preventDefault(), e.firstFocusable ? e.firstFocusable.focus() : 0 < e.controls.length ? 0 == e.selectedSlide ? e.controls[1].getElementsByTagName("button")[0].focus() : e.controls[0].getElementsByTagName("button")[0].focus() : e.options.navigation && e.navigation.getElementsByClassName("js-slideshow-pm__nav-item")[0].getElementsByTagName("button")[0].focus() } function s(e) { e.moveFocus && Util.moveFocus(e.items[e.selectedSlide]), e.items[e.selectedSlide].removeAttribute("aria-hidden"), e.animating = !1, e.moveFocus = !1, e.startAutoplay() } function r(e, t, n) { var i = t.target.closest(".js-slideshow-pm__nav-item"); n && i && !Util.hasClass(i, "slideshow-pm__nav-item--selected") && (e.showItem(Util.getIndexInArray(e.navigation, i)), e.moveFocus = !0, d(e)) } function t(e, t, n, i) { e.animating && e.supportAnimation || (i && (t < 0 ? t = e.items.length - 1 : t >= e.items.length && (t = 0)), t < 0 || t >= e.items.length || (e.animating = !0, Util.removeClass(e.items[e.selectedSlide], "x-r_b"), e.items[e.selectedSlide].setAttribute("aria-hidden", "true"), Util.addClass(e.items[t], "x-r_b"), a(e, t, e.selectedSlide), e.selectedSlide = t, m(e), e.pauseAutoplay(), c(e), w || s(e))) } function d(e) { e.ariaLive.innerHTML = "Item " + (e.selectedSlide + 1) + " of " + e.items.length } function u(e) { var t = window.getComputedStyle(e.items[e.selectedSlide]); e.itemWidth = parseFloat(t.getPropertyValue("width")), e.itemMargin = parseFloat(t.getPropertyValue("margin-right")), e.containerWidth = parseFloat(window.getComputedStyle(e.element).getPropertyValue("width")) } function m(e) { var t = parseInt((e.itemWidth + e.itemMargin) * e.selectedSlide * -1 + .5 * (e.containerWidth - e.itemWidth)); e.list.style.transform = "translateX(" + t + "px)", e.list.style.msTransform = "translateX(" + t + "px)" } function a(e, t, n) { e.navigation && (Util.removeClass(e.navigation[n], "slideshow-pm__nav-item--selected"), Util.addClass(e.navigation[t], "slideshow-pm__nav-item--selected"), e.navCurrentLabel.parentElement.removeChild(e.navCurrentLabel), e.navigation[t].getElementsByTagName("button")[0].appendChild(e.navCurrentLabel)), 0 < e.controls.length && (Util.toggleClass(e.controls[0], "x-r_x", 0 != t), Util.toggleClass(e.controls[1], "x-r_x", t != e.items.length - 1)) } function c(e) { var t = e.items[e.selectedSlide].querySelectorAll('[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex]:not([tabindex="-1"]), [contenteditable], audio[controls], video[controls], summary'); !function (e, t) { e.firstFocusable = !1; for (var n = 0; n < t.length; n++)if (t[n].offsetWidth || t[n].offsetHeight || t[n].getClientRects().length) return e.firstFocusable = t[n] }(e, t), function (e, t) { e.lastFocusable = !1; for (var n = t.length - 1; 0 <= n; n--)if (t[n].offsetWidth || t[n].offsetHeight || t[n].getClientRects().length) return e.lastFocusable = t[n] }(e, t) } n.prototype.showNext = function (e) { t(this, this.selectedSlide + 1, "next", e) }, n.prototype.showPrev = function () { t(this, this.selectedSlide - 1, "prev") }, n.prototype.showItem = function (e) { t(this, e, !1) }, n.prototype.startAutoplay = function () { var e = this; !this.options.autoplay || this.autoplayId || this.autoplayPaused || (e.autoplayId = setInterval(function () { e.showNext(!0) }, e.options.autoplayInterval)) }, n.prototype.pauseAutoplay = function () { this.options.autoplay && (clearInterval(this.autoplayId), this.autoplayId = !1) }, n.defaults = { element: "", navigation: !0, autoplay: !1, autoplayInterval: 5e3, prewNav: !1, swipe: !1 }, window.SlideshowPrew = n; var e, i, h, v, p, g, f = document.getElementsByClassName("js-slideshow-pm"), y = Util.cssSupports("align-items", "stretch"), w = Util.cssSupports("transition"); if (0 < f.length) for (var b = 0; b < f.length; b++)void 0, i = !f[e = b].getAttribute("data-navigation") || "off" != f[e].getAttribute("data-navigation"), h = !(!f[e].getAttribute("data-autoplay") || "on" != f[e].getAttribute("data-autoplay")), v = f[e].getAttribute("data-autoplay-interval") ? f[e].getAttribute("data-autoplay-interval") : 5e3, p = !(!f[e].getAttribute("data-pm-nav") || "on" != f[e].getAttribute("data-pm-nav")), g = !(!f[e].getAttribute("data-swipe") || "on" != f[e].getAttribute("data-swipe")), new n({ element: f[e], navigation: i, autoplay: h, autoplayInterval: v, swipe: g, prewNav: p }) }();