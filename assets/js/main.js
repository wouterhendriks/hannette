
(function($) {

  document.addEventListener("simply-content-loaded", function() {

    $('meta[property="og:title"]').attr('content', $('title').text());
    $('meta[property="og:description"]').attr('content', $('meta[name="description"]').attr('content'));

    // apply photo share options to images in bannercarousels
    $('.bannercarousel p[data-simply-field="carousel description"] > img').each(function() {
      $(this).wrap('<span class="carousel-item-share"></span>');
      var container = $(this).parent();

      var shareLink = getAbsoluteUrl('/foto-details/?foto=' + encodeURIComponent(getAbsoluteUrl($(this).attr('src'))));
      var overlayLink = '<a class="overlay" href="' + shareLink + '"><i class="fa fa-facebook"></i></a>';

      container.append(overlayLink);

      container.on('click', '.overlay', function(evt) {
        evt.preventDefault();

        FB.ui({
          method: 'share',
          href: shareLink,
        });
      });
    });

    $('#loaderoverlay').addClass('hide');
  });

  //cache
  var headerImageWidth = 0;
  var headerImageHeight = 0;
  var $headerImage = null;
  var $header = null;
  var simplyEditMode;

  //settings
  var settings = {

    // Carousels
      carousels: {
        speed: 4,
        fadeIn: true,
        fadeDelay: 250
      },

  };

  skel.breakpoints({
    wide: '(max-width: 1680px)',
    normal: '(max-width: 1280px)',
    narrow: '(max-width: 960px)',
    narrower: '(max-width: 840px)',
    mobile: '(max-width: 736px)'
  });

  $(function() {
    simplyEditMode = location.hash === '#simply-edit';

    $('html').toggleClass('no-simply-edit', !simplyEditMode);
    $('html').toggleClass('simply-edit', simplyEditMode);

    if ($('#form-next').length && location.host === 'test.salonhannette.nl')
      $('#form-next').attr('value', $('#form-next').attr('value').replace('www.', 'test.'));

    var $headerImage = $('#headerbgimage');
    var $header = $('#header');

    var isHome =  location.pathname === '/'
               || (location.host === 'wouterhendriks.github.io' && location.pathname === '/hannette/');

    $('body').toggleClass('homepage', isHome);

    var $window = $(window),
      $body = $('body');

      $( window ).resize(function() {
        // calculate header image position
        if (headerImageWidth == 0) {
          headerImageWidth = $headerImage.width();
          headerImageHeight = $headerImage.height();
        }

        var coverCoords = getCoverCoordinates(headerImageWidth,
                                              headerImageHeight,
                                              $header.outerWidth(),
                                              $header.outerHeight(),
                                              false);

        $headerImage.css({ top: coverCoords.top,
                           left: coverCoords.left,
                           width: coverCoords.width,
                           height: coverCoords.height,
                           visibility: 'visible',
                         });
      }).trigger('resize');

    // Prioritize "important" elements on mobile.
      skel.on('+mobile -mobile', function() {
        $.prioritize(
          '.important\\28 mobile\\29',
          skel.breakpoint('mobile').active
        );
      });

    // Dropdowns.
      if (!simplyEditMode) {
        $('#nav > ul').dropotron({
          mode: 'fade',
          speed: 350,
          hideDelay: 0,
          expandMode: 'hover',
          noOpenerFade: true,
          alignment: 'center'
        });
      }

    // Scrolly links.
      if (!simplyEditMode) {
        $('.scrolly').scrolly();
      }

    // Off-Canvas Navigation.

      // Navigation Button.
        $(
          '<div id="navButton">' +
            '<a href="#navPanel" class="toggle"></a>' +
          '</div>'
        )
          .appendTo($body);

      // Navigation Panel.
        $(
          '<div id="navPanel">' +
            '<nav>' +
              $('#nav').navList() +
            '</nav>' +
          '</div>'
        )
          .appendTo($body)
          .panel({
            delay: 500,
            hideOnClick: true,
            hideOnSwipe: true,
            resetScroll: true,
            resetForms: true,
            target: $body,
            visibleClass: 'navPanel-visible'
          });

      // Fix: Remove navPanel transitions on WP<10 (poor/buggy performance).
        if (skel.vars.os == 'wp' && skel.vars.osVersion < 10)
          $('#navButton, #navPanel, #page-wrapper')
            .css('transition', 'none');

    // Carousels.
      $('.carousel').each(function() {

        var $t = $(this),
          $forward = $('<span class="forward"></span>'),
          $backward = $('<span class="backward"></span>'),
          $reel = $t.children('.reel'),
          $items = $reel.children('article');

        var pos = 0,
          leftLimit,
          rightLimit,
          itemWidth,
          reelWidth,
          timerId;

        // Items.
          if (settings.carousels.fadeIn) {

            $items.addClass('loading');

            $t.onVisible(function() {
              var timerId,
                limit = $items.length - Math.ceil($window.width() / itemWidth);

              timerId = window.setInterval(function() {
                var x = $items.filter('.loading'), xf = x.first();

                if (x.length <= limit) {

                  window.clearInterval(timerId);
                  $items.removeClass('loading');
                  return;

                }

                if (skel.vars.IEVersion < 10) {

                  xf.fadeTo(750, 1.0);
                  window.setTimeout(function() {
                    xf.removeClass('loading');
                  }, 50);

                }
                else
                  xf.removeClass('loading');

              }, settings.carousels.fadeDelay);
            }, 50);
          }

        // Main.
          $t._update = function() {
            pos = 0;
            rightLimit = (-1 * reelWidth) + $window.width();
            leftLimit = 0;
            $t._updatePos();
          };

          if (skel.vars.IEVersion < 9)
            $t._updatePos = function() { $reel.css('left', pos); };
          else
            $t._updatePos = function() { $reel.css('transform', 'translate(' + pos + 'px, 0)'); };

        // Forward.
          $forward
            .appendTo($t)
            .hide()
            .mouseenter(function(e) {
              timerId = window.setInterval(function() {
                pos -= settings.carousels.speed;

                if (pos <= rightLimit)
                {
                  window.clearInterval(timerId);
                  pos = rightLimit;
                }

                $t._updatePos();
              }, 10);
            })
            .mouseleave(function(e) {
              window.clearInterval(timerId);
            });

        // Backward.
          $backward
            .appendTo($t)
            .hide()
            .mouseenter(function(e) {
              timerId = window.setInterval(function() {
                pos += settings.carousels.speed;

                if (pos >= leftLimit) {

                  window.clearInterval(timerId);
                  pos = leftLimit;

                }

                $t._updatePos();
              }, 10);
            })
            .mouseleave(function(e) {
              window.clearInterval(timerId);
            });

        // Init.
          $window.load(function() {

            reelWidth = $reel[0].scrollWidth;

            skel.on('change', function() {

              if (skel.vars.touch) {

                $reel
                  .css('overflow-y', 'hidden')
                  .css('overflow-x', 'scroll')
                  .scrollLeft(0);
                $forward.hide();
                $backward.hide();

              }
              else {

                $reel
                  .css('overflow', 'visible')
                  .scrollLeft(0);
                $forward.show();
                $backward.show();

              }

              $t._update();

            });

            $window.resize(function() {
              reelWidth = $reel[0].scrollWidth;
              $t._update();
            }).trigger('resize');

          });

      });
  });

})(jQuery);

// get the information needed to properly stretch an image
// (to fully cover (fit or fill) the available space (outwidth/outheight) with the original size (inwidth/inheight) while keeping the aspect ratio)
function getCoverCoordinates(inwidth, inheight, outwidth, outheight, fit)
{
  var infx = !(outwidth > 0);
  var infy = !(outheight > 0);
  var dx = infx ? 0 : inwidth / outwidth;
  var dy = infy ? 0 : inheight / outheight;
  var scale;
  if(infx)
    scale=dy;
  else if(infy)
    scale=dx;
  else if(fit)
    scale = Math.max(dx,dy);
  else
    scale = Math.min(dx,dy);

  return { width: inwidth/scale
         , height: inheight/scale
         , top: (outheight - (inheight/scale))/2
         , left: (outwidth - (inwidth/scale))/2
         };
}

var getAbsoluteUrl = (function() {
  var a = null;
  return function(url) {
    a = a || document.createElement('a');
    a.href = url;

    return a.href;
  };
})();
