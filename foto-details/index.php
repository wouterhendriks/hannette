<?php
  $imageurl = isset($_GET['foto']) ? $_GET['foto'] : "";
  if ($imageurl != '') {
    $size = getimagesize($imageurl);
  }
?><!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8"/>

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-93105650-1', 'auto');
    ga('send', 'pageview');
  </script>

  <script>
    var key = 'pink-care-6309';
    var base = '/';
    if (location.host === 'wouterhendriks.github.io') {
      key = 'github';
      base = 'https://wouterhendriks.github.io/hannette/';
    }
    else if (location.host === 'salon-hannette.local') {
      key = 'localhost';
      base = 'http://salon-hannette.local/'
    }

    document.write('<base href="' + base + '" />');
  </script>

  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title data-simply-field="meta title">Portfolio Dames & Heren Salon Hannette</title>
  <meta name="author" content="Salon Hannette" data-simply-field="meta author" />
  <meta name="description" data-simply-field="meta description" content="Doe inspiratie op voor uw nieuwe kapsel bij Dames & Heren Salon Hannette in Haaksbergen" />
  <link rel="stylesheet" href="/assets/css/main.css"/>
  <link rel="shortcut icon" href="/assets/img/favicon.png">
  <link rel="icon" href="/assets/img/favicon.png">

  <?php
    $currentLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  ?>

  <meta property="fb:app_id" content="1727412697287223"/>
  <meta property="og:url" content="<?php echo($currentLink) ?>"/>
  <meta property="og:locale" content="nl_NL"/>
  <meta property="og:type" content="website"/>
  <meta property="og:title" content="Portfolio Dames & Heren Salon Hannette" />
  <meta property="og:description" content="Doe inspiratie op voor uw nieuwe kapsel bij Dames & Heren Salon Hannette in Haaksbergen" />
  <meta property="og:image" content="<?php echo($imageurl); ?>" />
  <?php
  if ($size) {
    echo '<meta property="og:image:width" content="' . $size[0] . '"/>';
    echo '<meta property="og:image:height" content="' . $size[1] . '"/>';
  }
  ?>
</head>
<body>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId            : '1727412697287223',
        autoLogAppEvents : true,
        xfbml            : true,
        version          : 'v2.9'
      });
      FB.AppEvents.logPageView();
    };

    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));
  </script>

  <div id="loaderoverlay"></div>
  <div class="header-phone">
    <a class="button header-phone__button" href="tel:+31535723756"><span class="fa fa-phone"></span><span class="header-phone__number">053-572 37 56</span></a>
  </div>
  <div id="page-wrapper">
  <div id="header">
    <img id="headerbgimage" src="/assets/img/header.jpg" data-simply-field="headerimage" style="visibility:visible"/>
    <div class="inner">
      <header>
        <h1><a href="/" id="logo" data-simply-field="hero title"><img src="/assets/img/logo-hannette-salon.png" /></a></h1>
        <hr/>
        <p data-simply-content="text" data-simply-field="hero description"></p>
      </header>
      <footer>
        <a href="#start" class="button circled scrolly" data-simply-field="hero start">Start</a>
      </footer>
    </div>

    <nav id="nav">
      <ul data-simply-list="menu" data-simply-path="/" data-simply-sortable>
        <li><a href="/">Home</a></li>
        <template data-simply-template="menu item">
          <li>
            <a href="#" data-simply-field="link">
              <span data-simply-content="text">Menu-item</span>
            </a>
            <ul data-simply-list="items-lvl-1" data-simply-sortable>
              <template data-simply-template="menu item-lvl-1">
                <li>
                  <a href="#" data-simply-field="link">Menu-item</a>
                </li>
              </template>
            </ul>
          </li>
        </template>
      </ul>
    </nav>
  </div>

  <div class="wrapper photodetails">
    <article class="container special">
      <img class="photodetails__image" src="<?php echo($imageurl) ?>" />
    </article>
  </div>

  <div id="footer">
    <div class="container">
      <div class="row">
        <section class="6u 12u(mobile)">
          <div data-simply-path="/" data-simply-field="footer address">Contactgegevens</div>
        </section>
        <section class="6u 12u(mobile)">
          <iframe data-simply-path="/"
                  data-simply-field="footer googlemaps"
                  width="100%"
                  height="300"
                  frameborder="0"
                  scrolling="no"
                  marginheight="0"
                  marginwidth="0"
                  src="https://www.google.nl/maps?hl=nl&q=Jhr.+von+Heijdenstraat+23,+Haaksbergen&amp;sll=52.1541359,6.7425128&amp;ie=UTF8&amp;hq=&amp;hnear=Jhr.+von+Heijdenstraat+23,+7481+EC+Haaksbergen,+Overijssel&amp;t=m&amp;ll=52.1541359,6.7425128&amp;spn=0.001316,0.003219&amp;z=17&amp;iwloc=A&amp;output=embed"></iframe>
        </section>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <section class="12u msologo">
          <div class="text">Website ontwikkeld met</div>
          <div><a href="https://www.mijnsiteonline.nu/" target="_blank"><img src="/assets/img/logo-mso-white.png" alt="MijnSiteOnline" width="286" /></a></div>
        </section>
      </div>
    </div>
  </div>
</div>

  <script src="/assets/js/jquery.min.js" type="text/javascript"></script>
  <script src="/assets/js/jquery.dropotron.min.js" type="text/javascript"></script>
  <script src="/assets/js/jquery.scrolly.min.js" type="text/javascript"></script>
  <script src="/assets/js/jquery.onvisible.min.js" type="text/javascript"></script>
  <script src="/assets/js/skel.min.js" type="text/javascript"></script>
  <script src="/assets/js/util.js" type="text/javascript"></script>
  <script src="/assets/js/main.js" type="text/javascript"></script>

  <script>
  var seSettings = {
    'simply-image' : {
      'responsive' : {
        'sizes' : [ 1024, 720, 640, 320, 160 ]
      }
    },
    'plugins' : [ 'plugin.simply-scaler.html' ]
  };
  </script>

  <script>
    document.write( '\x3Cscript '
                  + 'src="https://cdn.simplyedit.io/1/simply-edit.js"'
                  + 'data-api-key="' + key + '"'
                  + 'data-simply-images="img/"'
                  + 'data-simply-files="/files/"'
                  + 'data-simply-settings="seSettings"'
                  + '>\x3C/script>');
  </script>
</body>
</html>
