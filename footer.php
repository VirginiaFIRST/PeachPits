
	<footer class="footer">
        <div class="container-fluid top-footer">
	        <p class="pull-left" style="line-height:60px;margin-bottom:0px;">
                <a class="admin-login" href="about">About</a> |
                <a class="admin-login" href="contact">Contact</a>
            </p>
	        <p class="pull-right" style="line-height:60px;margin-bottom:0px;">
		        <?php if (loggedOn()){ ?>
			        <a class="admin-login" href="admin/dashboard?event=<?php echo $currentEvent; ?>">Admin Dashboard</a> | 
			        <a class="admin-login" href="signout">Sign Out</a>
		        <?php } else { ?>
			        <a class="admin-login" href="signin?event=<?php echo $currentEvent; ?>">Admin Sign In</a>
		        <?php } ?>
	        </p>
        </div>
        <div class="container-fluid bottom-footer">Copyright &copy; 2017 Georgia<i>FIRST</i> Robotics, All Rights Reserved</div>
    </footer>
	
	<script src="js/bootstrap.min.js"></script>
	</body>
</html>