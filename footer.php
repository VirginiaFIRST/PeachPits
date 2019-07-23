	<footer class="footer" id="footer">
	<section id="sponsors" style="padding: 0px !important;background-color:#F0F1F1;">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h2 id="sponsor-text">PeachPits is presented by</h2>
					<a href="http://www.automationdirect.com/adc/Home/Home" name="AutomationDirect"><img src="imgs/adc_logo.png" alt="Automation Direct Logo" style="width:50%;"></a>
				</div>
			</div>
		</div>
	</section>
        <div class="container-fluid top-footer">
	        <p class="pull-left" id="top-footer-left" style="line-height:60px;margin-bottom:0px;">
                <a class="admin-login" href="about">About</a> |
                <a class="admin-login" href="contact">Contact</a>
          </p>
		      <?php if (loggedOn()){ ?>
            <p class="pull-right" id="top-footer-right" style="line-height:60px;margin-bottom:0px;">
              <a class="admin-login" id="dashboard-link" href="admin/dashboard?event=<?php echo $currentEvent; ?>">Dashboard</a>
            </p>
		      <?php } ?>
        </div>
        <div class="container-fluid bottom-footer">Copyright &copy; 2017 Georgia <i>FIRST</i> Robotics, All Rights Reserved</div>
    </footer>
	<script src="js/bootstrap.min.js"></script>
	<script>
		var noSponsorPathnames = ["/peachpits/chooseevent", "/peachpits/peachtalk/manage-requests", "/peachpits/peachtalk/manage-users", "/peachpits/peachtalk/manage-messages", "/peachpits/peachtalk/export"];
		var noFooterPathnames = ["/peachpits/peachtalk/general", "/peachpits/peachtalk/schedule", "/peachpits/peachtalk/parts", "/peachpits/peachtalk/safety", "/peachpits/peachtalk/private-message"];        
		if (noSponsorPathnames.indexOf(window.location.pathname) >= 0) {
            document.getElementById("sponsors").style.display = "none";
        }
		if (noFooterPathnames.indexOf(window.location.pathname) >= 0) {
            document.getElementById("footer").style.display = "none";
        }
		$(window).load(function () {
			if (window.location.pathname == "/peachpits/chooseevent") {
				document.body.style.marginBottom = 0;				
			}
			else {
				document.getElementById("body-header").style.marginBottom = document.getElementById("footer").clientHeight;
			}
		});
		$(window).resize(function () {
			document.getElementById("body-header").style.marginBottom = document.getElementById("footer").clientHeight;
		});
		
    </script>
	</body>
</html>