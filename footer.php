
	<footer class="footer">
      <div class="container-fluid" style="height:60px;">
		<p class="pull-left" style="line-height:60px;margin-bottom:0px;">Copyright &copy; 2016</p>
		<p class="pull-right" style="line-height:60px;margin-bottom:0px;">
			<?php if (loggedOn()){ ?>
				<a class="admin-login" href="admin/dashboard.php?event=<?php echo $currentEvent; ?>">Admin Dashboard</a> | 
				<a class="admin-login" href="signout.php">Sign Out</a></li>
			<?php } else { ?>
				<a class="admin-login" href="signin.php?event=<?php echo $currentEvent; ?>">Admin Sign In</a>
			<?php } ?>
		</p>
      </div>
    </footer>
	
	<script src="js/bootstrap.min.js"></script>
	</body>
</html>