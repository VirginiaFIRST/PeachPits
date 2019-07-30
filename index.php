<?php include "header.php"; ?>
  <head>
    <style>
      html, body {
        height: calc(100% - 50px);
      }
      .navbar {
        background-color: transparent;
        z-index: 1000;
      }
      .footer {
        position:relative; /* page specific positioning */
      }
      .mobile-instructions {
        display: none;
      }
      @media only screen and (max-width: 766px) {
        .navbar, .collapsing, .in {
          background-color: #DC7633;
        }
        .collapsing ul li a, .in ul li a {
          color: #555;
        }
        .collapsing ul li a:hover, .in ul li a:hover {
          color: #f1f1f1 !important;
        }
        .mobile-instructions {
          display: block;
        }
      }
    </style>
    <script>
    $(function() {
      $('.scroll').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top}, 500, 'linear');
      });
    });
    </script>
  </head>
  <div class="site-wrapper-container">
  <div class="site-wrapper">
    <div class="site-wrapper-inner">
      <div class="cover-container">
        <div class="inner cover">
          <h1 class="cover-heading">Choose an Event Above To Get Started!</h1>
          <h2 class="mobile-instructions">Click on the menu button in the top right to choose an event.</h2>
        </div>
      </div>
    </div>
  </div>
  <div class="scroll-container text-center">
    <a href="#about" class="scroll">
      <span class="glyphicon glyphicon-menu-down"></span>
      <span style="display:block;">Scroll</span>
    </a>
  </div>
  </div>
  <section id="about">
    <div class="container">
      <div class="row">
        <div class="col-md-offset-2 col-md-8 text-center">
          <p class="lead" style="margin-bottom:0px;">PeachPits makes it easy to manage inspections and create pit maps for your FRC event.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <img src ="imgs/screen_mockups-temp.png" style="width:100%;" alt="PeachPits Screenshots">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <p class="lead">Ready to use PeachPits at your event?</p>
          <a href="signup?event=<?php echo $currentEvent ?>" class="btn btn-default btn-lg">Create an Account <span class="glyphicon glyphicon-chevron-right"></span></a>
        </div>
      </div>
    </div>
  </section>

  <!--<section id="sponsors">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2>PeachPits is presented by</h2>
          <a href="http://www.automationdirect.com/adc/Home/Home"><img src="imgs/adc_logo.png" style="width:50%;" alt="Automation Direct Logo"></a>
        </div>
      </div>
    </div>
  </section>-->


<?php include "footer.php"; ?>