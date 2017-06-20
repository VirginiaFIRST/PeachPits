<title>PeachPits - Contact</title>
<?php include "header.php"; ?>

<div class="page-head">
  <div class="container">
    <h1>Contact Us</h1>
  </div>
</div>
<div id="startchange" class="container">
	<div class="page-header">
		<h1><small>Questions? Comments? Inquiries? Send us a message and we will get in touch with you as soon as possible.</small></h1>
    </div>
    <br>
    <form class="form-horizontal" name="contactform" method="post" action="send_form_email.php">
	    <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
        	    <input type="text" class="form-control input-lg no-radius" name="name" id="name" placeholder="Name">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
        	    <input type="email" class="form-control input-lg no-radius" name="email" id="email" placeholder="Email">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
        	    <select class="form-control input-lg no-radius" name="topic" id="topic">
                    <option>What do you want to know?</option>
                    <option>About Byomes</option>
                    <option>Press</option>
                    <option>Other</option>
                </select>
            </div>
        </div>
	    <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
        	    <textarea style="resize: none" class="form-control input-lg no-radius" name="message" id="message" rows="7" placeholder="Message"></textarea>
            </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-1 col-sm-10">
            <button type="submit" class="btn btn-default btn-lg no-radius pull-right">Submit</button>
          </div>
        </div>
    </form>




</div>



<?php include "footer.php"; ?>