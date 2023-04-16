var sn = {};
(function ($) {


  var headerheight = 100;
  var headerheight_no_sub = 100;
  var headerheight_offset = headerheight - 50;
  // Prüfe ob es sich um einen Touch-Screen handelt


  function is_touch_device() {
    return !!('ontouchstart' in window);
  }

  if (is_touch_device()) {
    $('html').addClass('touch');
  }
  else {
    $('html').addClass('no-touch');
  }

  // var mainHeader = $('.header-teaser-lead'),
  //   secondaryNavigation = $('.cd-secondary-nav'),
  //   //this applies only if secondary nav is below intro section
  //   belowNavHeroContent = $('.sub-nav-hero'),
  //   headerHeight = mainHeader.height();

  // $(window).scroll(function () {
  //   if ($(this).scrollTop() > headerHeight - headerheight) {
  //     secondaryNavigation.addClass('fixed');
  //   } else {
  //     secondaryNavigation.removeClass('fixed');
  //   }
  // });

  // /***************** shrink Header ******************/

  // sn.headerOnScroll = function () {
  //   //Navigation wird beim Scrollen kleiner
  //   $(window).on('load scroll', function () {
  //     if ($(this).scrollTop() >= headerheight_offset) {
  //       $('.header').addClass('shrink');
  //     } else {
  //       $('.header').removeClass('shrink');
  //     }
  //   });
  // };

  // sn.scrollToDefaultOffset = -headerheight;

  // sn.scrollTo = function ($target, $top, offset) {
  //   if (typeof offset == 'undefined') {
  //     offset = sn.scrollToDefaultOffset;
  //   }

  //   var $px = $target.offset().top,
  //     $wpx = $(window).scrollTop(),
  //     $hNav = $('.header').height();
  //   if ($wpx > $px) offset = offset * -1;
  //   $px = !$top ? $target.offset().top : $target.offset().top - (headerheight) - offset;
  //   $('html, body').stop().animate({
  //     'scrollTop': $px
  //   }, 500, 'swing', function () {
  //     if (!$top && offset != 0) {
  //       sn.scrollTo($target, true, 0);
  //     }
  //   });
  // }

  // sn.runScrollTo = function () {
  //   $(window).bind('hashchange', function (e) {
  //     sn.runScrollToInitial();
  //   });

  // }

  // sn.runScrollToInitial = function () {
  //   if (typeof location.hash != 'undefined') {
  //     $target = $(location.hash);
  //     if ($target.length) {
  //       sn.scrollTo($target, false, sn.scrollToDefaultOffset);
  //       return false;
  //     }
  //   }
  // }



  var goTop = function () {
    $(window).scroll(function () {
      if ($(this).scrollTop() > 800) {
        $('.go-top').addClass('show');
      } else {
        $('.go-top').removeClass('show');
      }
    });

    $('.go-top').on('click', function () {
      $("html, body").animate({
        scrollTop: 0
      }, 1000);
      return false;
    });
  };

  var goto = function () {
    $('.trailer-arrow').on('click', function () {
      $("html, body").animate({
        scrollTop: $(".hiroend").offset().top - headerheight_no_sub
      }, 1000);
      return false;
    });
  };
  // /* ---------------------------------------------- /*
  //    Smooth Scrolling
  
  //    a[href*=#]:not([href=#])
  // /* ---------------------------------------------- */
  SmoothScrolling = function () {
    $('a[href*=\\#]:not([href=\\#])').click(function () {
      $('a').each(function () {
        $(this).removeClass('active');
        $('.mzp-c-menu').removeClass('it-open');
        $('.hamburger').removeClass('is-active');
        $('body').removeClass('navigation-is-open');
      })
      // $(this).addClass('active');
      if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {

        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
          $('html,body').animate({
            scrollTop: target.offset().top - headerheight
          }, 1000);
          $('.cd-primary-nav').removeClass('is-visible')
          return false;
        }
      }
    });
  };

  $('.hamburger').click(function (event) {
    event.preventDefault();
    $('.mzp-c-menu').toggleClass('it-open');
    $('.hamburger').toggleClass('is-active');
    $('body').toggleClass('navigation-is-open');
  });




  $("a").filter(function () {
    return this.hostname && this.hostname.replace('www.', '') !== location.hostname.replace('www.', '');
  }).attr('target', '_blank').addClass('externicon');

  // /* ---------------------------------------------- /*
  //    NAV
  // /* ---------------------------------------------- */

  // var sections = $('.sprungmarken'),
  //   nav = $('.cd-secondary-nav'),
  //   bigcontent = $('.bigcontent').height();
  // $(window).on('scroll', function () {
  //   var cur_pos = $(this).scrollTop();

  //   sections.each(function () {
  //     var top = $(this).offset().top - headerheight - 5;
  //     if (cur_pos >= top) {

  //       nav.find('a').removeClass('active');
  //       sections.removeClass('active');

  //       $(this).addClass('active');
  //       nav.find('a[href="#' + $(this).attr('id') + '"]').addClass('active');
  //     }
  //     if (cur_pos <= bigcontent) {
  //       nav.find('a').removeClass('active');
  //       sections.removeClass('active');
  //     }
  //   });
  // });


  $('.formmessage').on('submit', function () {

    // Add text 'loading...' right after clicking on the submit button. 
    $('.output_message').text('bitte warten...');

    var form = $(this);
    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: form.serialize(),

      success: function (data) {

        if (data == 'success') {
          $('.output_message').html('<h3>Vielen Dank</h3><p>Wir werden uns so schnell wie möglich bei Dir melden.</p>');
          $('.formmessage').slideUp(400);
          $('.contact-form h2').slideUp(400);
        } else {
          $('.output_message').html('<div class="error">Beim Versuch, deine Mitteilung zu versenden, ist ein Fehler aufgetreten. Bitte versuche es später noch einmal.</div>');
        }
      }

    });

    // Prevents default submission of the form after clicking on the submit button. 
    return false;
  });


  //dsgvo checkbox
  $("#checkbox").click(function () {
    if ($(this).is(":checked")) {
      $(this).val('true');
      $("#submitButton").removeAttr("disabled");
    } else {
      $("#submitButton").attr("disabled", "true");
      $(this).val('false');
    }
  });


  // //video youtube


  // function YouTubeGetID(url) {
  //   var ID = '';
  //   url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
  //   if (url[2] !== undefined) {
  //     ID = url[2].split(/[^0-9a-z_\-]/i);
  //     ID = ID[0];
  //   } else {
  //     ID = url;
  //   }
  //   return ID;
  // }

  // document.addEventListener("DOMContentLoaded",
  //   function () {
  //     var div, n,
  //       v = document.getElementsByClassName("youtube-player");
  //     for (n = 0; n < v.length; n++) {
  //       div = document.createElement("div");
  //       div.setAttribute("data-id", YouTubeGetID(v[n].dataset.id));
  //       div.innerHTML = labnolThumb(YouTubeGetID(v[n].dataset.id));
  //       // div.onclick = labnolIframe;
  //       v[n].appendChild(div);
  //     }
  //   });

  // function labnolThumb(id) {
  //   var thumb = '<img src="https://i.ytimg.com/vi/ID/maxresdefault.jpg">',
  //     play = '<div class="play"></div> <div class="fade fade-3"></div>';
  //   return thumb.replace("ID", id) + play;
  // }

  // function labnolIframe() {
  //   var iframe = document.createElement("iframe");
  //   var embed = "https://www.youtube.com/embed/ID?autoplay=1&hd=1&controls=2&autohide=1&showinfo=0";
  //   iframe.setAttribute("src", embed.replace("ID", this.dataset.id));
  //   iframe.setAttribute("frameborder", "0");
  //   iframe.setAttribute("allowfullscreen", "1");
  //   this.parentNode.replaceChild(iframe, this);
  // }

  // // Dom Ready
  $(function () {
    // nvamobil();
    // fullscreen();
    // headerFixed();
    // sn.runScrollTo();
    // sn.runScrollToInitial();
    // sn.headerOnScroll();
    goTop();
    SmoothScrolling();
    goto();

  });



})(jQuery);

