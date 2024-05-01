<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="/z1/frontend/assets/nobel.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
    crossorigin="anonymous"></script>
  <title>Nobel prizes</title>
</head>
<style>
  button,
  th,
  a {
    text-transform: uppercase;
  }
</style>
<style>
  .cookie-consent-container {
    display: none;
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f1f1f1;
    padding: 10px;
    text-align: center;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, .2);
    z-index: 1000;
  }
</style>
</head>

<body>

  <div class="cookie-consent-container" id="cookieConsentContainer">
    <p>This website uses cookies to improve the user experience.</p>
    <button class="btn btn-primary btn-sm" id="acceptCookieConsent">Accept</button>
  </div>

  <script>
    $(document).ready(function () {
      if (localStorage.getItem('cookieConsent') !== 'true') {
        $('#cookieConsentContainer').show();
      }

      $('#acceptCookieConsent').click(function () {
        localStorage.setItem('cookieConsent', 'true');
        $('#cookieConsentContainer').hide();
      });
    });
  </script>
  <script>
  //   if ('serviceWorker' in navigator) {
    //   window.addEventListener('load', function() {
      //     navigator.serviceWorker.register('frontend/service-worker.js').then(function(registration) {
        //       console.info('ServiceWorker registration successful with scope: ', registration.scope);
        //     }, function(err) {
          //       console.warn('ServiceWorker registration failed: ', err);
          //     });
          //   });
          // }
  </script>

  <script type="module" src="/z1/frontend/js/index.js"></script>

  <body>

    <?php
    require 'menu/menu.php';

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    switch ($path) {
      case '/nobel-app':
        require 'pages/prizes.php';
        break;
      case '/nobel-app/prize':
        require 'pages/prize.php';
        break;
      case '/nobel-app/nobel-receiver':
        require 'pages/nobelReceiver.php';
        break;
      case '/nobel-app/nobel-receiver/new':
        require 'pages/nobelReceiver.php';
        break;
      case '/nobel-app/login':
        require 'pages/login.php';
        break;
      case '/nobel-app/register':
        require 'pages/reg.php';
        break;
      default:
        require 'pages/404.php';
        break;
    }
    ?>
  </body>

</html>